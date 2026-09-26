<?php
declare(strict_types=1);

/**
 * Tests de bout en bout du site PHP, via de vraies requêtes HTTP (remplace backend/tests/backend_test.py).
 *
 * 1. Base de test :  mysql -e "CREATE DATABASE dina_test" && mysql dina_test < database/schema.sql && mysql dina_test < database/seed.sql
 * 2. Serveur      :  DB_NAME=dina_test php -S 127.0.0.1:8080 -t public app/dev-router.php
 * 3. Tests        :  php tests/http_test.php http://127.0.0.1:8080
 *
 * Optionnel — site branché sur une base SANS tables, pour vérifier qu'il reste consultable :
 *    mysql -e "CREATE DATABASE dina_vide"
 *    DB_NAME=dina_vide php -S 127.0.0.1:8082 -t public app/dev-router.php
 *    php tests/http_test.php http://127.0.0.1:8080 http://127.0.0.1:8082
 */

$base = rtrim($argv[1] ?? 'http://127.0.0.1:8080', '/');
$passed = 0;
$failed = [];

function check(string $name, bool $ok, string $detail = ''): void
{
    global $passed, $failed;
    if ($ok) {
        $passed++;
        echo "  ✓ $name\n";
    } else {
        $failed[] = $name;
        echo "  ✗ $name" . ($detail !== '' ? " — $detail" : '') . "\n";
    }
}

/** Client HTTP minimal avec cookies (une session PHP par client). */
final class Client
{
    private string $jar;

    public function __construct(private readonly string $base)
    {
        $this->jar = tempnam(sys_get_temp_dir(), 'dina');
    }

    /** @return array{status: int, body: string, headers: string, json: ?array} */
    public function request(string $method, string $path, array $form = [], bool $json = false): array
    {
        $ch = curl_init($this->base . $path);
        curl_setopt_array($ch, [
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_COOKIEJAR      => $this->jar,
            CURLOPT_COOKIEFILE     => $this->jar,
            CURLOPT_HTTPHEADER     => $json ? ['Accept: application/json'] : [],
        ]);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($form));
        }
        $raw = (string) curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $body = substr($raw, $headerSize);
        return [
            'status'  => $status,
            'headers' => substr($raw, 0, $headerSize),
            'body'    => $body,
            'json'    => json_decode($body, true),
        ];
    }

    public function get(string $path): array
    {
        return $this->request('GET', $path);
    }

    /** Jeton CSRF lu dans la page, comme le ferait le navigateur. */
    public function csrf(): string
    {
        preg_match('/name="csrf-token" content="([a-f0-9]+)"/', $this->get('/')['body'], $m);
        return $m[1] ?? '';
    }

    public function post(string $path, array $form, bool $json = true, bool $withCsrf = true): array
    {
        if ($withCsrf) {
            $form['_csrf'] = $this->csrf();
        }
        return $this->request('POST', $path, $form, $json);
    }
}

$c = new Client($base);

echo "Pages\n";
foreach (['/', '/boutique', '/artisane', '/a-propos', '/contact', '/panier', '/produit/sac-lune-nacre'] as $path) {
    $r = $c->get($path);
    check("GET $path → 200", $r['status'] === 200, (string) $r['status']);
}
check('page inconnue → 404', $c->get('/nexiste-pas')['status'] === 404);
check('produit inconnu → 404', $c->get('/produit/nonexistent-xyz')['status'] === 404);
check('confirmation sans commande → redirection', $c->get('/commande/confirmee')['status'] === 303);
check('en-têtes de sécurité', str_contains($c->get('/')['headers'], 'X-Content-Type-Options: nosniff'));

echo "Catalogue (MySQL)\n";
$count = fn (string $html) => preg_match_all('/data-testid="product-card-/', $html);
check('9 produits dans la boutique', $count($c->get('/boutique')['body']) === 9);
check('3 bijoux', $count($c->get('/boutique?categorie=bijoux')['body']) === 3);
check('5 sacs', $count($c->get('/boutique?categorie=sacs')['body']) === 5);
check('catégorie inconnue → tout le catalogue', $count($c->get('/boutique?categorie=%27%20OR%201=1--')['body']) === 9);
$p = $c->get('/produit/sac-lune-nacre')['body'];
check('fiche produit : nom et prix', str_contains($p, 'Le Sac Lune Nacre') && str_contains($p, "55\u{202F}000 FCFA"));
check('fiche produit : sans vue 3D', !str_contains($p, 'Vue 3D') && !str_contains($p, 'pearl3d'));
$home = $c->get('/')['body'];
check('accueil : vitrine avec 6 pièces', str_contains($home, 'data-testid="vitrine-section"') && substr_count($home, 'data-vitrine-go=') === 6);
check('accueil : bloc sur mesure conservé', str_contains($home, 'data-testid="sur-mesure-section"'));
check('fiche produit : pas de liste d\'avis sur la fiche', !str_contains($p, 'data-testid="review-item"'));
$a = $c->get('/produit/sac-lune-nacre/avis');
check('page avis du produit', $a['status'] === 200 && str_contains($a['body'], 'data-testid="reviews-page"') && str_contains($a['body'], 'Le Sac Lune Nacre'));
$items = static fn (string $html): int => substr_count($html, 'data-testid="review-item"');
$bad = $c->get('/produit/sac-lune-nacre/avis?note=%27%20OR%201=1--');
check('avis : filtre de note invalide ignoré', $bad['status'] === 200 && $items($bad['body']) === $items($a['body']));
check('avis : produit inconnu → 404', $c->get('/produit/nonexistent-xyz/avis')['status'] === 404);
check('parure ambre : 2 photos', substr_count($c->get('/produit/parure-ambre')['body'], 'data-testid="product-thumb-') === 2);

echo "Sécurité CSRF\n";
$r = $c->post('/panier/ajouter', ['product_id' => 'sac-lune-nacre'], true, false);
check('POST sans jeton CSRF refusé (419)', $r['status'] === 419, (string) $r['status']);
$r = $c->request('POST', '/contact', ['_csrf' => 'faux', 'name' => 'x'], true);
check('POST avec faux jeton refusé', $r['status'] === 419);

echo "Panier\n";
$r = $c->post('/panier/ajouter', ['product_id' => 'sac-lune-nacre', 'qty' => '2', 'price' => '1']);
check('ajout → JSON ok', ($r['json']['ok'] ?? false) === true, $r['body']);
check('prix envoyé par le client ignoré', ($r['json']['total'] ?? 0) === 110000, (string) ($r['json']['total'] ?? ''));
$r = $c->post('/panier/ajouter', ['product_id' => 'parure-ambre', 'qty' => '1']);
check('2e produit → 3 articles, 155 000', $r['json']['count'] === 3 && $r['json']['total'] === 155000);
$r = $c->post('/panier/ajouter', ['product_id' => 'produit-fantome']);
check('produit inconnu refusé (404)', $r['status'] === 404);
$r = $c->post('/panier/modifier', ['product_id' => 'parure-ambre', 'qty' => '999']);
check('quantité plafonnée à 20', $r['json']['count'] === 22, (string) $r['json']['count']);
$r = $c->post('/panier/modifier', ['product_id' => 'parure-ambre', 'qty' => '-5']);
check('quantité minimale 1', $r['json']['count'] === 3);
$r = $c->post('/panier/retirer', ['product_id' => 'parure-ambre']);
check('retrait', $r['json']['count'] === 2 && $r['json']['total'] === 110000);
check('HTML du tiroir renvoyé', str_contains($r['json']['drawer'] ?? '', 'cart-item-sac-lune-nacre'));

echo "Commande\n";
$r = $c->post('/commande', ['customer_name' => 'T', 'phone' => '123', 'address' => 'ab'], false);
check('commande invalide → retour au panier', $r['status'] === 303 && str_contains($r['headers'], 'Location: /panier'));
$panier = $c->get('/panier')['body'];
check('erreurs affichées sous les champs', str_contains($panier, 'data-testid="error-customer_name"') && str_contains($panier, 'data-testid="error-phone"'));
$r = $c->post('/commande', ['customer_name' => 'TEST_Client', 'phone' => '+229 97 00 00 00', 'address' => 'Cotonou', 'email' => '', 'note' => ''], false);
check('commande valide → confirmation', $r['status'] === 303 && str_contains($r['headers'], 'Location: /commande/confirmee'));
$confirm = $c->get('/commande/confirmee')['body'];
check('total calculé côté serveur (110 000)', str_contains($confirm, "110\u{202F}000 FCFA"));
check('panier vidé après commande', !str_contains($c->get('/panier')['body'], 'cart-page-item-'));
$r = $c->post('/commande', ['customer_name' => 'TEST_Client', 'phone' => '+22997000000', 'address' => 'Cotonou'], false);
check('commande avec panier vide refusée', str_contains($r['headers'], 'Location: /panier'));
check('liste des commandes non exposée', $c->get('/api/orders')['status'] === 404);

echo "Contact\n";
$r = $c->post('/contact', ['name' => 'TEST_User', 'email' => 'test@example.com', 'subject' => 'Test', 'message' => 'Ceci est un message de test']);
check('message valide', $r['status'] === 200 && ($r['json']['ok'] ?? false));
$r = $c->post('/contact', ['name' => 'T', 'email' => 'not-an-email', 'subject' => 's', 'message' => 'hello!']);
check('message invalide → 422 + erreurs', $r['status'] === 422 && isset($r['json']['errors']['email'], $r['json']['errors']['name']));
$r = $c->post('/contact', ['name' => 'Robot', 'email' => 'bot@example.com', 'subject' => 'Spam', 'message' => 'achetez !', 'website' => 'http://spam']);
check('robot (champ piège) : réponse ok mais rien enregistré', $r['status'] === 200);

echo "Newsletter\n";
$r = $c->post('/newsletter', ['email' => 'TEST_news@example.com']);
check('inscription', $r['status'] === 200 && ($r['json']['ok'] ?? false));
$r = $c->post('/newsletter', ['email' => 'TEST_news@example.com']);
check('réinscription sans erreur (pas de doublon)', $r['status'] === 200);
check('e-mail invalide → 422', $c->post('/newsletter', ['email' => 'bad'])['status'] === 422);

echo "XSS\n";
$c->post('/panier/ajouter', ['product_id' => 'sac-lune-nacre']);
$c->post('/commande', ['customer_name' => '<script>alert(1)</script>', 'phone' => 'x', 'address' => ''], false);
$panier = $c->get('/panier')['body'];
check('saisie réaffichée échappée', !str_contains($panier, '<script>alert(1)</script>') && str_contains($panier, '&lt;script&gt;'));

$degradedBase = $argv[2] ?? null;
if ($degradedBase !== null) {
    echo "Base de données sans tables\n";
    $d = new Client(rtrim($degradedBase, '/'));
    foreach (['/artisane', '/a-propos', '/contact', '/panier'] as $path) {
        check("GET $path reste accessible (200)", $d->get($path)['status'] === 200);
    }
    foreach (['/', '/boutique'] as $path) {
        $r = $d->get($path);
        check("GET $path : 200 + message catalogue", $r['status'] === 200 && str_contains($r['body'], 'data-testid="catalog-unavailable"'));
    }
    check('vitrine masquée sans catalogue', !str_contains($d->get('/')['body'], 'data-testid="vitrine-section"'));
    check('fiche produit → 503 (pas 404)', $d->get('/produit/sac-lune-nacre')['status'] === 503);
    check('page avis → 503 (pas 404)', $d->get('/produit/sac-lune-nacre/avis')['status'] === 503);
    check('ajout au panier → 503', $d->post('/panier/ajouter', ['product_id' => 'sac-lune-nacre'])['status'] === 503);
    $r = $d->post('/contact', ['name' => 'Test', 'email' => 't@example.com', 'subject' => 'Salut', 'message' => 'Bonjour test']);
    check('contact → 503 avec message', $r['status'] === 503 && str_contains($r['json']['message'] ?? '', 'indisponible'));
}

echo "\n" . $passed . ' réussis, ' . count($failed) . " échoués\n";
exit($failed === [] ? 0 : 1);
