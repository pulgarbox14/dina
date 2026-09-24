<?php
declare(strict_types=1);

/**
 * Fonctions utilisées dans les gabarits. Règle d'or : tout texte venant de la base
 * ou du visiteur passe par e() avant d'être affiché (protection XSS).
 */

use App\Config;
use App\Session;

/** Échappe une valeur pour l'afficher dans du HTML. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Lien interne, préfixé si le site est installé dans un sous-dossier. */
function url(string $path = '/'): string
{
    return rtrim((string) Config::get('app.base_path', ''), '/') . '/' . ltrim($path, '/');
}

/** Fichier de public/assets, avec numéro de version pour forcer la mise à jour du cache. */
function asset(string $path): string
{
    $file = dirname(__DIR__) . '/public/assets/' . ltrim($path, '/');
    $version = is_file($file) ? '?v=' . filemtime($file) : '';
    return url('assets/' . ltrim($path, '/')) . $version;
}

/** Page courante (sans le sous-dossier), pour surligner le lien actif de la navbar. */
function current_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = rtrim((string) Config::get('app.base_path', ''), '/');
    if ($base !== '' && str_starts_with($path, $base)) {
        $path = substr($path, strlen($base)) ?: '/';
    }
    return $path === '/' ? '/' : rtrim($path, '/');
}

function is_active(string $path): bool
{
    $current = current_path();
    return $path === '/' ? $current === '/' : str_starts_with($current, $path);
}

/** 55000 → « 55 000 FCFA » (espace fine insécable, comme Intl fr-FR). */
function price(int $amount): string
{
    return number_format($amount, 0, ',', "\u{202F}") . ' FCFA';
}

/** Icône Lucide en SVG inline. */
function icon(string $name, int $size = 16, float $stroke = 2, string $class = ''): string
{
    static $icons = null;
    $icons ??= require __DIR__ . '/icons.php';
    $paths = $icons[$name] ?? '';
    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round" class="%3$s" aria-hidden="true">%4$s</svg>',
        $size,
        $stroke,
        e($class),
        $paths
    );
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(Session::csrfToken()) . '">';
}

/**
 * Champ piège invisible : un humain le laisse vide, un robot de spam le remplit.
 * `!w-px` passe devant le style global des champs (width: 100 %).
 */
function honeypot_field(): string
{
    return '<input type="text" name="website" value="" tabindex="-1" autocomplete="off" aria-hidden="true" class="absolute -left-[9999px] !w-px !h-px opacity-0">';
}

/** Identifiant unique (UUID v4) pour les commandes et messages. */
function uuid4(): string
{
    $b = random_bytes(16);
    $b[6] = chr((ord($b[6]) & 0x0f) | 0x40);
    $b[8] = chr((ord($b[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($b), 4));
}

/** Informations de la marque (le numéro WhatsApp et l'e-mail viennent de la config). */
function brand(string $key): string
{
    static $brand = null;
    $brand ??= [
        'name'      => 'Dina Perles',
        'tagline'   => 'Haute Perlerie Artisanale',
        'artisan'   => 'Zinsou Secondina Dagbédé',
        'city'      => 'Cotonou, Bénin',
        'whatsapp'  => (string) Config::get('brand.whatsapp'),
        'email'     => (string) Config::get('brand.email'),
        'instagram' => '@dina.perles',
    ];
    return $brand[$key] ?? '';
}

function whatsapp_link(string $text): string
{
    return 'https://wa.me/' . rawurlencode(brand('whatsapp')) . '?text=' . rawurlencode($text);
}

/** Lien WhatsApp pré-rempli avec le contenu du panier. */
function cart_whatsapp_link(array $cart): string
{
    $lines = array_map(
        static fn (array $i): string => '• ' . $i['name'] . ' x' . $i['qty'] . ' — ' . price($i['line_total']),
        $cart['items']
    );
    return whatsapp_link("Bonjour Dina Perles ! Je souhaite commander :\n" . implode("\n", $lines) . "\nTotal : " . price($cart['total']));
}

/**
 * Photos d'ambiance des pages Artisane / À propos.
 * Elles sont encore hébergées chez Emergent : pour les héberger vous-même,
 * copiez-les dans public/assets/img/ et remplacez $base par url('assets/img/').
 */
function gallery(string $key): string
{
    $base = 'https://static.prod-images.emergentagent.com/jobs/7ee9dc22-104f-4f5f-bce4-68271cc60488/images/';
    $files = [
        'lune'        => '46cb48231813f0acda019e8e5e4356b4088a4400185d9ab3649fcedbbc567ab0.jpeg',
        'classique'   => '948ae007bf90f1cccd44d63de2610a179d9d7fd504a584b2a390c834a7a1bdf1.jpeg',
        'orangeRound' => '638c5bb9c6b1c9f2320fb89647f1941b48f217bba82e11ac795513904763fac3.jpeg',
        'orangeTote'  => '6fb44d546c549ddb989ca3038d6b535177b7e6eda7b6cbf12e5d215a4ce25389.jpeg',
        'purple'      => 'd483f02574a8bd6c9b24c025cad33c6617fc51b1ae1c176f97e8ab23d3ca16b1.jpeg',
        'trio'        => '3162899abd160da05fb509fe34b1c229746b7566748d4ebef2af7e3423814f43.jpeg',
        'whiteSet'    => '9f479b1f426704ec3959febadbe5f31f0d208ea76b3585c35a18461aa8ea1c11.jpeg',
        'ringSet'     => '6d9b5a4bf8e02572a0ce17141868ead5d8303ba81184d4615a61c7354f9762b7.jpeg',
        'amber'       => '85bcddc3e7e29cc572b81600bcbe7c945eda731b37605ed8314ff3aec5ca8d36.jpeg',
        'amberPlate'  => '84b8e25e1f496e0775005a7db0dde27dd7a11a3e05c54e45b9a9f534c0c0ba65.jpeg',
    ];
    return $base . ($files[$key] ?? '');
}

/**
 * Forme et couleur du modèle 3D d'un produit (même logique que PearlModel.js).
 *
 * @return array{shape: string, accent: ?string}
 */
function pearl_model(array $product): array
{
    $accents = [
        'Fleur orange'       => '#f97316',
        'Fleur violette'     => '#a855f7',
        'Ambre'              => '#f59e0b',
        'Blanc, bleu & gris' => '#60a5fa',
    ];
    $shape = match (true) {
        $product['category'] === 'bijoux' => 'necklace',
        in_array($product['id'], ['sac-lune-nacre', 'sac-nacre-classique', 'cabas-fleur-de-soleil'], true) => 'round',
        default => 'tote',
    };
    return ['shape' => $shape, 'accent' => $accents[$product['accent']] ?? null];
}

/** Erreur de validation d'un champ, prête à afficher sous l'input. */
function field_error(array $errors, string $field): string
{
    return isset($errors[$field])
        ? '<p class="text-xs text-red-600 mt-1" data-field-error data-testid="error-' . e($field) . '">' . e($errors[$field]) . '</p>'
        : '';
}
