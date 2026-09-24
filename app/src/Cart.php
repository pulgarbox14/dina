<?php
declare(strict_types=1);

namespace App;

/**
 * Panier stocké dans la session PHP : on ne garde que « id produit → quantité ».
 * Noms, photos et prix sont toujours relus dans MySQL, donc toujours à jour
 * et impossibles à falsifier depuis le navigateur.
 */
final class Cart
{
    public const MAX_QTY = 20;
    private const KEY = 'cart';

    /** @return array<string, int> */
    public static function raw(): array
    {
        $cart = Session::get(self::KEY, []);
        return is_array($cart) ? $cart : [];
    }

    public static function add(string $productId, int $qty = 1): void
    {
        $cart = self::raw();
        $cart[$productId] = self::clamp(($cart[$productId] ?? 0) + $qty);
        Session::put(self::KEY, $cart);
    }

    public static function set(string $productId, int $qty): void
    {
        $cart = self::raw();
        if (isset($cart[$productId])) {
            $cart[$productId] = self::clamp($qty);
            Session::put(self::KEY, $cart);
        }
    }

    public static function remove(string $productId): void
    {
        $cart = self::raw();
        unset($cart[$productId]);
        Session::put(self::KEY, $cart);
    }

    public static function clear(): void
    {
        Session::put(self::KEY, []);
    }

    /**
     * Contenu détaillé du panier. Les produits retirés du catalogue disparaissent d'eux-mêmes.
     *
     * @return array{items: list<array<string, mixed>>, count: int, total: int}
     */
    public static function summary(): array
    {
        $raw = self::raw();
        $products = ProductRepository::findMany(array_map('strval', array_keys($raw)));
        $items = [];
        $count = 0;
        $total = 0;
        foreach ($raw as $id => $qty) {
            $p = $products[(string) $id] ?? null;
            if ($p === null) {
                continue;
            }
            $line = (int) $p['price'] * $qty;
            $items[] = [
                'id'         => $p['id'],
                'name'       => $p['name'],
                'price'      => (int) $p['price'],
                'image'      => $p['images'][0] ?? '',
                'qty'        => $qty,
                'line_total' => $line,
            ];
            $count += $qty;
            $total += $line;
        }
        return ['items' => $items, 'count' => $count, 'total' => $total];
    }

    private static function clamp(int $qty): int
    {
        return max(1, min(self::MAX_QTY, $qty));
    }
}
