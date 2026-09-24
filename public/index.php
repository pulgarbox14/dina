<?php
declare(strict_types=1);

/**
 * Contrôleur frontal : toutes les URL du site passent par ce fichier
 * (voir public/.htaccess). Seul le dossier public/ est exposé sur le web.
 */

require dirname(__DIR__) . '/app/bootstrap.php';

use App\Http;
use App\Session;

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

if (Http::method() === 'POST' && !Session::checkCsrf($_POST['_csrf'] ?? null)) {
    if (Http::wantsJson()) {
        Http::json(['ok' => false, 'message' => 'Session expirée, rechargez la page.'], 419);
        exit;
    }
    Session::flash('error', 'Session expirée, merci de réessayer.');
    Http::back();
}

/** @var App\Router $router */
$router = require dirname(__DIR__) . '/app/routes.php';
$router->dispatch(Http::method(), $_SERVER['REQUEST_URI'] ?? '/');
