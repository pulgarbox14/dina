-- À lancer une fois si la table `reviews` a été créée avant le 26/09/2026 :
-- autorise les avis sur l'atelier en général (sans pièce associée).
-- Réexécutable sans risque.
-- Usage : mysql -u <user> -p <base> < database/migrations/001-avis-sans-piece.sql

ALTER TABLE reviews MODIFY product_id VARCHAR(80) NULL;
