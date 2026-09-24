<?php
declare(strict_types=1);

namespace App;

final class OrderRepository
{
    /**
     * Enregistre une commande et ses lignes dans une transaction :
     * soit tout est écrit, soit rien (jamais de commande sans articles).
     *
     * @param array{customer_name: string, phone: string, email: string, address: string, note: string} $customer
     * @param list<array{id: string, name: string, price: int, qty: int}> $items prix relus en base
     * @return array{id: string, total: int}
     */
    public static function create(array $customer, array $items): array
    {
        if ($items === []) {
            throw new \InvalidArgumentException('Une commande doit contenir au moins un article.');
        }
        $id = uuid4();
        $total = array_sum(array_map(static fn (array $i): int => $i['price'] * $i['qty'], $items));

        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $pdo->prepare(
                'INSERT INTO orders (id, customer_name, phone, email, address, note, total)
                 VALUES (:id, :customer_name, :phone, :email, :address, :note, :total)'
            )->execute([
                'id'            => $id,
                'customer_name' => $customer['customer_name'],
                'phone'         => $customer['phone'],
                'email'         => $customer['email'] !== '' ? $customer['email'] : null,
                'address'       => $customer['address'],
                'note'          => $customer['note'] !== '' ? $customer['note'] : null,
                'total'         => $total,
            ]);

            $line = $pdo->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, unit_price, quantity)
                 VALUES (:order_id, :product_id, :product_name, :unit_price, :quantity)'
            );
            foreach ($items as $item) {
                $line->execute([
                    'order_id'     => $id,
                    'product_id'   => $item['id'],
                    'product_name' => $item['name'],
                    'unit_price'   => $item['price'],
                    'quantity'     => $item['qty'],
                ]);
            }
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }

        return ['id' => $id, 'total' => $total];
    }
}
