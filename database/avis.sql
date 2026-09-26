-- Avis réels envoyés par les clientes de Dina Perles.
-- Réexécutable : un avis déjà présent (même nom, même texte) n'est pas ajouté deux fois.
-- Usage : mysql -u <user> -p <base> < database/avis.sql
-- Pour en ajouter : une ligne de plus ci-dessous, ou l'import CSV (bin/importer-avis.php).

SET NAMES utf8mb4;

-- Nettoyage des anciens avis de démonstration (« (démo) »), s'ils avaient été importés.
DELETE FROM reviews WHERE order_item_id IS NULL AND author_name LIKE '%(démo)';

INSERT INTO reviews (product_id, author_name, city, rating, title, body, status, published_at)
SELECT * FROM (
    SELECT 'sac-lune-nacre' AS product_id, 'Fatou N.' AS author_name, 'Cotonou' AS city, 5 AS rating, NULL AS title,
           'Mon sac Lune Nacre a fait sensation au mariage de ma sœur. On m''a demandé dix fois où je l''avais trouvé.' AS body,
           'publie' AS status, NOW() AS published_at
    UNION ALL
    SELECT NULL, 'Mariam K.', 'Abidjan', 5, NULL,
           'Le travail est incroyablement régulier. On sent les heures passées dessus. Livraison rapide jusqu''en Côte d''Ivoire.',
           'publie', NOW()
) AS nouveaux
WHERE NOT EXISTS (
    SELECT 1 FROM reviews r WHERE r.author_name = nouveaux.author_name AND r.body = nouveaux.body
);
