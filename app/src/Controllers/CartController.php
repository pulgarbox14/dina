<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Cart;
use App\Http;
use App\ProductRepository;
use App\Session;
use App\View;

/**
 * Actions du panier. Fonctionnent avec et sans JavaScript :
 * - via fetch (Accept: application/json) → JSON + HTML du tiroir panier à jour ;
 * - via un formulaire classique → message flash + retour à la page précédente.
 */
final class CartController
{
    public static function add(): void
    {
        $product = ProductRepository::find(Http::input('product_id'));
        if ($product === null) {
            self::respond(false, 'Produit introuvable.', 404);
            return;
        }
        Cart::add($product['id'], max(1, (int) Http::input('qty', '1')));
        self::respond(true, $product['name'] . ' ajouté au panier', 200, open: true);
    }

    public static function update(): void
    {
        Cart::set(Http::input('product_id'), (int) Http::input('qty', '1'));
        self::respond(true, '');
    }

    public static function remove(): void
    {
        Cart::remove(Http::input('product_id'));
        self::respond(true, '');
    }

    private static function respond(bool $ok, string $message, int $status = 200, bool $open = false): void
    {
        if (!Http::wantsJson()) {
            if ($message !== '') {
                Session::flash($ok ? 'success' : 'error', $message);
            }
            Http::back('/panier');
        }
        $cart = Cart::summary();
        Http::json([
            'ok'      => $ok,
            'message' => $message,
            'open'    => $open,
            'count'   => $cart['count'],
            'total'   => $cart['total'],
            'totalFormatted' => price($cart['total']),
            'whatsapp' => cart_whatsapp_link($cart),
            'drawer'  => View::partial('partials/cart-drawer-body', ['cart' => $cart]),
            'page'    => View::partial('partials/cart-page-items', ['cart' => $cart]),
        ], $status);
    }
}
