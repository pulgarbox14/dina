<?php
declare(strict_types=1);

namespace App;

/** Lecture du catalogue dans MySQL. */
final class ProductRepository
{
    public const CATEGORIES = [
        'tous'   => 'Tout',
        'sacs'   => 'Sacs',
        'minis'  => 'Mini-sacs',
        'bijoux' => 'Parures & bijoux',
    ];

    /** @return list<array<string, mixed>> */
    public static function all(?string $category = null, ?bool $featured = null): array
    {
        $sql = 'SELECT * FROM products WHERE active = 1';
        $params = [];
        if ($category !== null && $category !== 'tous') {
            $sql .= ' AND category = :category';
            $params['category'] = $category;
        }
        if ($featured !== null) {
            $sql .= ' AND featured = :featured';
            $params['featured'] = $featured ? 1 : 0;
        }
        $sql .= ' ORDER BY sort_order, created_at';

        $stmt = Database::pdo()->prepare($sql);
        $stmt->execute($params);
        return self::withImages($stmt->fetchAll());
    }

    public static function find(string $id): ?array
    {
        $stmt = Database::pdo()->prepare('SELECT * FROM products WHERE id = :id AND active = 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? self::withImages([$row])[0] : null;
    }

    /**
     * Prix et noms à jour pour une liste d'identifiants : c'est la seule source de vérité
     * pour calculer un total (jamais les prix envoyés par le navigateur).
     *
     * @param list<string> $ids
     * @return array<string, array<string, mixed>> indexé par id
     */
    public static function findMany(array $ids): array
    {
        $ids = array_values(array_unique($ids));
        if ($ids === []) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Database::pdo()->prepare("SELECT * FROM products WHERE active = 1 AND id IN ($placeholders)");
        $stmt->execute($ids);
        $out = [];
        foreach (self::withImages($stmt->fetchAll()) as $p) {
            $out[$p['id']] = $p;
        }
        return $out;
    }

    /** Ajoute `images` (liste d'URL) et convertit `featured` en booléen. */
    private static function withImages(array $rows): array
    {
        if ($rows === []) {
            return [];
        }
        $ids = array_column($rows, 'id');
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Database::pdo()->prepare(
            "SELECT product_id, url FROM product_images WHERE product_id IN ($placeholders) ORDER BY position, id"
        );
        $stmt->execute($ids);
        $images = [];
        foreach ($stmt->fetchAll() as $img) {
            $images[$img['product_id']][] = $img['url'];
        }
        return array_map(static function (array $p) use ($images): array {
            $p['featured'] = (bool) $p['featured'];
            $p['images'] = $images[$p['id']] ?? [];
            return $p;
        }, $rows);
    }
}
