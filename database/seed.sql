-- Dina Perles — données de départ (9 créations)
-- Usage : mysql -u <user> -p <base> < database/seed.sql
-- Réexécutable : remet les produits d'origine à jour sans dupliquer.

SET NAMES utf8mb4;

INSERT INTO products (id, name, subtitle, price, category, accent, tag, weaving_hours, dimensions, description, featured, sort_order) VALUES
    ('sac-lune-nacre', 'Le Sac Lune Nacre', 'Sac rigide en perles blanches tressées', 55000, 'sacs', 'Blanc pur', 'Bestseller', 16, '20 × 14 × 6 cm', 'Forme structurée en demi-lune, intégralement tissée à la main avec plus de 1 200 perles nacrées blanches. Anse perlée renforcée et intérieur doublé.', 1, 1),
    ('sac-nacre-classique', 'Le Sac Nacre Classique', 'Sac à main & bracelet assorti', 48000, 'sacs', 'Blanc pur', 'Duo', 14, '19 × 13 × 6 cm', 'Le classique de l''atelier, livré avec son bracelet en perles assorti. Un ensemble lumineux pour les cérémonies et les grandes occasions.', 1, 2),
    ('cabas-fleur-de-soleil', 'Le Cabas Fleur de Soleil', 'Perles blanches et fleurs orange ambré', 68000, 'sacs', 'Fleur orange', 'Édition limitée', 22, '24 × 14 × 9 cm', 'Sublimé par de délicates fleurs en perles orange insérées dans un maillage blanc. Un hommage aux soleils couchants de la côte ouest-africaine.', 1, 3),
    ('panier-soleil-structure', 'Le Panier Soleil Structuré', 'Cabas rectangulaire à motifs floraux', 72000, 'sacs', 'Fleur orange', 'Nouveauté', 26, '22 × 18 × 8 cm', 'Silhouette architecturale, neuf fleurs orange tissées en relief sur une trame de perles blanches. Se porte à la main, tête haute.', 1, 4),
    ('panier-fleur-amethyste', 'Le Panier Fleur d''Améthyste', 'Cabas carré à fleur violette', 62000, 'sacs', 'Fleur violette', 'Coup de cœur', 18, '18 × 16 × 9 cm', 'Un panier au tissage ajouré, illuminé d''une fleur violette profonde. Conçu pour accompagner vos tenues de célébration.', 1, 5),
    ('collection-mini-trio', 'La Collection Mini Trio', 'Trois mini-sacs : rond, anse ronde, rayé', 95000, 'minis', 'Blanc, bleu & gris', 'Coffret', 30, '3 pièces · 12 à 15 cm', 'Un coffret de trois mini-sacs : la pochette ronde à chaîne et fleur bleue, le mini-sac à anse ronde et le sac rayé aux perles marbrées grises.', 0, 6),
    ('parure-perles-blanches', 'La Parure Perles Blanches', 'Collier double rang & boucles d''oreilles', 35000, 'bijoux', 'Blanc pur', 'Essentiel', 8, 'Collier 48 cm · Boucles 5 cm', 'Un collier double rang aux perles graduées, accompagné de ses boucles d''oreilles pendantes. Parure de mariée intemporelle.', 1, 7),
    ('parure-nacre-bague', 'La Parure Nacre & Bague', 'Collier, boucles d''oreilles et bague', 42000, 'bijoux', 'Blanc pur', NULL, 10, 'Collier 50 cm · Bague ajustable', 'Ensemble complet trois pièces : collier double rang, boucles d''oreilles perles et bague tissée assortie.', 0, 8),
    ('parure-ambre', 'La Parure Ambre', 'Fleurs de cristal ambré & perles transparentes', 45000, 'bijoux', 'Ambre', 'Nouveauté', 12, 'Collier 46 cm · Boucles 6 cm', 'Des fleurs en cristal facetté couleur ambre, reliées par des perles transparentes. Collier et boucles d''oreilles à dormeuses argentées.', 1, 9)
ON DUPLICATE KEY UPDATE name = VALUES(name), subtitle = VALUES(subtitle), price = VALUES(price), category = VALUES(category), accent = VALUES(accent), tag = VALUES(tag), weaving_hours = VALUES(weaving_hours), dimensions = VALUES(dimensions), description = VALUES(description), featured = VALUES(featured), sort_order = VALUES(sort_order);

DELETE FROM product_images WHERE product_id IN ('sac-lune-nacre', 'sac-nacre-classique', 'cabas-fleur-de-soleil', 'panier-soleil-structure', 'panier-fleur-amethyste', 'collection-mini-trio', 'parure-perles-blanches', 'parure-nacre-bague', 'parure-ambre');
-- Chemins relatifs au dossier public/ (photos incluses dans le projet).
INSERT INTO product_images (product_id, url, position) VALUES
    ('sac-lune-nacre', 'assets/img/produits/sac-lune-nacre-1.jpg', 0),
    ('sac-lune-nacre', 'assets/img/produits/sac-lune-nacre-2.jpg', 1),
    ('sac-nacre-classique', 'assets/img/produits/sac-nacre-classique.jpg', 0),
    ('cabas-fleur-de-soleil', 'assets/img/produits/cabas-fleur-de-soleil-1.jpg', 0),
    ('cabas-fleur-de-soleil', 'assets/img/produits/cabas-fleur-de-soleil-2.jpg', 1),
    ('panier-soleil-structure', 'assets/img/produits/panier-soleil-structure.jpg', 0),
    ('panier-fleur-amethyste', 'assets/img/produits/panier-fleur-amethyste-1.jpg', 0),
    ('panier-fleur-amethyste', 'assets/img/produits/panier-fleur-amethyste-2.jpg', 1),
    ('collection-mini-trio', 'assets/img/produits/collection-mini-trio.jpg', 0),
    ('parure-perles-blanches', 'assets/img/produits/parure-perles-blanches.jpg', 0),
    ('parure-nacre-bague', 'assets/img/produits/parure-nacre-bague.jpg', 0),
    ('parure-ambre', 'assets/img/produits/parure-ambre-1.jpg', 0),
    ('parure-ambre', 'assets/img/produits/parure-ambre-2.jpg', 1);
