<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Cart;
use App\Http;
use App\ProductRepository;
use App\Session;
use App\View;

/** Pages affichées en GET. */
final class PageController
{
    public static function home(): void
    {
        echo View::render('pages/accueil', [
            'featured' => ProductRepository::all(featured: true),
            'showcase' => ProductRepository::all(),
        ]);
    }

    public static function boutique(): void
    {
        $cat = Http::query('categorie', 'tous');
        if (!array_key_exists($cat, ProductRepository::CATEGORIES)) {
            $cat = 'tous';
        }
        echo View::render('pages/boutique', [
            'title'    => 'Boutique',
            'category' => $cat,
            'products' => ProductRepository::all($cat),
        ]);
    }

    public static function produit(string $id): void
    {
        $product = ProductRepository::find($id);
        if ($product === null && ProductRepository::unavailable()) {
            http_response_code(503);
            echo View::render('pages/erreur', ['title' => 'Catalogue indisponible', 'code' => 503]);
            return;
        }
        if ($product === null) {
            http_response_code(404);
            echo View::render('pages/erreur', ['title' => 'Produit introuvable', 'code' => 404, 'productNotFound' => true]);
            return;
        }
        $related = array_values(array_filter(ProductRepository::all(), fn (array $p) => $p['id'] !== $id));
        echo View::render('pages/produit', [
            'title'       => $product['name'],
            'description' => $product['subtitle'] . ' — ' . $product['description'],
            'product'     => $product,
            'related'     => array_slice($related, 0, 4),
        ]);
    }

    public static function artisane(): void
    {
        echo View::render('pages/artisane', ['title' => "L'Artisane"]);
    }

    public static function aPropos(): void
    {
        echo View::render('pages/a-propos', ['title' => 'À propos']);
    }

    public static function contact(): void
    {
        echo View::render('pages/contact', ['title' => 'Contact', 'form' => Session::takeForm('contact')]);
    }

    public static function panier(): void
    {
        echo View::render('pages/panier', [
            'title' => 'Panier',
            'cart'  => Cart::summary(),
            'form'  => Session::takeForm('checkout'),
        ]);
    }

    public static function commandeConfirmee(): void
    {
        $order = Session::pull('last_order');
        if (!is_array($order)) {
            Http::redirect('/boutique');
        }
        echo View::render('pages/commande-confirmee', ['title' => 'Commande confirmée', 'order' => $order]);
    }
}
