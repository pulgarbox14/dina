<?php
declare(strict_types=1);

namespace App;

/**
 * Lecture des avis publiés.
 *
 * Comme pour le catalogue, une base indisponible (ou une table `reviews` pas encore créée)
 * ne fait jamais planter la page : les avis sont simplement considérés comme absents.
 */
final class ReviewRepository
{
    /** Nombre d'avis affichés avant le lien « Voir tous les avis ». */
    public const PREVIEW = 5;

    /** @var array<string, array{count: int, average: float}>|null */
    private static ?array $summaries = null;

    /**
     * Note moyenne et nombre d'avis d'un produit, pour les cartes et l'en-tête produit.
     * Toutes les notes sont chargées en une seule requête par page, quel que soit le nombre de cartes.
     *
     * @return array{count: int, average: float}
     */
    public static function summary(string $productId): array
    {
        self::$summaries ??= self::guard(static function (): array {
            $rows = Database::pdo()->query(
                "SELECT product_id, COUNT(*) AS n, AVG(rating) AS avg_rating
                 FROM reviews WHERE status = 'publie' GROUP BY product_id"
            )->fetchAll();
            $out = [];
            foreach ($rows as $r) {
                $out[$r['product_id']] = ['count' => (int) $r['n'], 'average' => round((float) $r['avg_rating'], 1)];
            }
            return $out;
        }, []);
        return self::$summaries[$productId] ?? ['count' => 0, 'average' => 0.0];
    }

    /**
     * Statistiques complètes d'un produit : moyenne, total et répartition par nombre d'étoiles.
     *
     * @return array{count: int, average: float, distribution: array<int, int>}
     */
    public static function stats(string $productId): array
    {
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $rows = self::guard(static function () use ($productId): array {
            $stmt = Database::pdo()->prepare(
                "SELECT rating, COUNT(*) AS n FROM reviews
                 WHERE product_id = :id AND status = 'publie' GROUP BY rating"
            );
            $stmt->execute(['id' => $productId]);
            return $stmt->fetchAll();
        }, []);
        $count = 0;
        $sum = 0;
        foreach ($rows as $r) {
            $distribution[(int) $r['rating']] = (int) $r['n'];
            $count += (int) $r['n'];
            $sum += (int) $r['rating'] * (int) $r['n'];
        }
        return [
            'count'        => $count,
            'average'      => $count > 0 ? round($sum / $count, 1) : 0.0,
            'distribution' => $distribution,
        ];
    }

    /**
     * Avis publiés d'un produit, du plus récent au plus ancien.
     *
     * @return list<array<string, mixed>>
     */
    public static function forProduct(string $productId, ?int $rating = null): array
    {
        return self::guard(static function () use ($productId, $rating): array {
            $sql = "SELECT id, author_name, city, rating, title, body, order_item_id,
                           COALESCE(published_at, created_at) AS date
                    FROM reviews WHERE product_id = :id AND status = 'publie'";
            $params = ['id' => $productId];
            if ($rating !== null) {
                $sql .= ' AND rating = :rating';
                $params['rating'] = $rating;
            }
            $stmt = Database::pdo()->prepare($sql . ' ORDER BY date DESC, id DESC');
            $stmt->execute($params);
            return array_map(static function (array $r): array {
                $r['rating'] = (int) $r['rating'];
                $r['verified'] = $r['order_item_id'] !== null;
                return $r;
            }, $stmt->fetchAll());
        }, []);
    }

    private static function guard(callable $read, mixed $fallback): mixed
    {
        try {
            return $read();
        } catch (\PDOException $e) {
            error_log('Avis indisponibles : ' . $e->getMessage());
            return $fallback;
        }
    }
}
