<?php
declare(strict_types=1);

use App\Controllers\CartController;
use App\Controllers\FormController;
use App\Controllers\PageController;
use App\Router;

$router = new Router();

// Pages
$router->get('/', [PageController::class, 'home']);
$router->get('/boutique', [PageController::class, 'boutique']);
$router->get('/produit/{id}', [PageController::class, 'produit']);
$router->get('/produit/{id}/avis', [PageController::class, 'avis']);
$router->get('/artisane', [PageController::class, 'artisane']);
$router->get('/a-propos', [PageController::class, 'aPropos']);
$router->get('/contact', [PageController::class, 'contact']);
$router->get('/panier', [PageController::class, 'panier']);
$router->get('/commande/confirmee', [PageController::class, 'commandeConfirmee']);

// Panier
$router->post('/panier/ajouter', [CartController::class, 'add']);
$router->post('/panier/modifier', [CartController::class, 'update']);
$router->post('/panier/retirer', [CartController::class, 'remove']);

// Formulaires
$router->post('/commande', [FormController::class, 'commande']);
$router->post('/contact', [FormController::class, 'contact']);
$router->post('/newsletter', [FormController::class, 'newsletter']);

return $router;
