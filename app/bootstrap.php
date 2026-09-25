<?php
declare(strict_types=1);

/**
 * Point de démarrage commun : configuration, chargement des classes, session, erreurs.
 * Aucun framework ni Composer : le site tourne sur n'importe quel hébergement PHP 8.1+.
 */

define('APP_ROOT', __DIR__);

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $file = APP_ROOT . '/src/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require APP_ROOT . '/helpers.php';

App\Config::load(dirname(APP_ROOT) . '/.env');

date_default_timezone_set('Africa/Porto-Novo');
mb_internal_encoding('UTF-8');

$debug = (bool) App\Config::get('app.debug', false);
ini_set('display_errors', $debug ? '1' : '0');
error_reporting(E_ALL);

set_exception_handler(static function (Throwable $e) use ($debug): void {
    error_log((string) $e);
    if (!headers_sent()) {
        http_response_code(500);
    }
    if (App\Http::wantsJson()) {
        App\Http::json(['ok' => false, 'message' => 'Petit contretemps, merci de réessayer dans un instant.'], 500);
        return;
    }
    if ($debug) {
        echo '<pre>' . e((string) $e) . '</pre>';
        return;
    }
    try {
        echo App\View::render('pages/erreur', ['title' => 'Petit contretemps', 'code' => 500]);
    } catch (Throwable) {
        // La page d'erreur complète a elle-même besoin de MySQL (panier) : version minimale.
        echo '<!doctype html><meta charset="utf-8"><title>Dina Perles</title>'
            . '<p style="font-family:sans-serif;text-align:center;margin-top:20vh">Petit contretemps : merci de réessayer dans un instant.</p>';
    }
});

App\Session::start();
