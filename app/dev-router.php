<?php
// Routeur pour le serveur intégré de PHP, en développement uniquement :
//   php -S localhost:8000 -t public app/dev-router.php
// Les fichiers existants (CSS, JS, images) sont servis directement, le reste passe par index.php.
$file = __DIR__ . '/../public' . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($_SERVER['REQUEST_URI'] !== '/' && is_file($file)) {
    return false;
}
require __DIR__ . '/../public/index.php';
