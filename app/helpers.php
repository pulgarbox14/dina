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

/** Logo officiel d'un réseau social (Instagram, WhatsApp) en SVG plein. */
function brand_icon(string $name, int $size = 18, string $class = ''): string
{
    static $icons = null;
    $icons ??= require __DIR__ . '/brand-icons.php';
    return sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="currentColor" class="%2$s" aria-hidden="true"><path d="%3$s"/></svg>',
        $size,
        e($class),
        $icons[$name] ?? ''
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
        'artisan'   => 'Zinsou Secondina',
        'city'      => 'Cotonou, Bénin',
        'whatsapp'  => (string) Config::get('brand.whatsapp'),
        'email'     => (string) Config::get('brand.email'),
        'instagram' => '@dina.perles',
        'facebook'  => 'Dina Perles',
        'tiktok'    => '@dina.perles',
    ];
    return $brand[$key] ?? '';
}

/** Lien vers un réseau social (instagram, facebook, tiktok) défini dans .env, « # » tant qu'il n'est pas renseigné. */
function social_url(string $network): string
{
    $url = trim((string) Config::get('social.' . $network, ''));
    return $url !== '' ? $url : '#';
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

/** Photo stockée en base : URL complète gardée telle quelle, sinon chemin du dossier public/. */
function media(string $path): string
{
    return preg_match('#^https?://#', $path) ? $path : url($path);
}

/** Photos des pages Artisane / À propos (fichiers de public/assets/img/produits/). */
function gallery(string $key): string
{
    $files = [
        'lune'        => 'sac-lune-nacre-1',
        'classique'   => 'sac-nacre-classique',
        'orangeRound' => 'cabas-fleur-de-soleil-1',
        'orangeTote'  => 'panier-soleil-structure',
        'purple'      => 'panier-fleur-amethyste-1',
        'trio'        => 'collection-mini-trio',
        'whiteSet'    => 'parure-perles-blanches',
        'ringSet'     => 'parure-nacre-bague',
        'amber'       => 'parure-ambre-1',
        'amberPlate'  => 'parure-ambre-2',
    ];
    return url('assets/img/produits/' . ($files[$key] ?? $files['lune']) . '.jpg');
}

/** Erreur de validation d'un champ, prête à afficher sous l'input. */
function field_error(array $errors, string $field): string
{
    return isset($errors[$field])
        ? '<p class="text-xs text-red-600 mt-1" data-field-error data-testid="error-' . e($field) . '">' . e($errors[$field]) . '</p>'
        : '';
}
