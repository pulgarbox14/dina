-- Avis de démonstration, pour voir le rendu en local uniquement.
-- NE PAS importer sur le site en ligne : ce ne sont pas de vrais avis.
-- Ils ne sont reliés à aucune commande, donc n'affichent pas « Achat vérifié ».
-- Réexécutable : les avis de démonstration existants sont d'abord supprimés.
-- Usage : mysql -u <user> -p <base> < database/demo-avis.sql

SET NAMES utf8mb4;

DELETE FROM reviews WHERE order_item_id IS NULL AND author_name LIKE '%(démo)';

INSERT INTO reviews (product_id, author_name, city, rating, title, body, status, published_at) VALUES
('sac-lune-nacre', 'Fatou N. (démo)', 'Cotonou', 5, 'Il a fait sensation', "Mon sac Lune Nacre a fait sensation au mariage de ma sœur. On m'a demandé dix fois où je l'avais trouvé.", 'publie', '2026-09-12 10:00:00'),
('sac-lune-nacre', 'Aïcha B. (démo)', 'Porto-Novo', 5, 'Finitions impeccables', "Les perles sont très régulières et l'anse est solide. Il est encore plus beau en vrai.", 'publie', '2026-09-03 18:20:00'),
('sac-lune-nacre', 'Nadège H. (démo)', 'Lomé', 4, 'Très beau, un peu petit pour moi', "Magnifique travail. Je n'arrive pas à y glisser un grand téléphone, mais pour une soirée c'est parfait.", 'publie', '2026-08-21 09:15:00'),
('sac-lune-nacre', 'Rachida S. (démo)', 'Cotonou', 5, 'Conseil parfait', "Secondina m'a conseillée sur la taille et m'a envoyé des photos avant la livraison. Rien à redire.", 'publie', '2026-08-10 14:00:00'),
('sac-lune-nacre', 'Estelle A. (démo)', 'Dakar', 4, 'Belle pièce', "Livraison un peu plus longue que prévu jusqu'à Dakar, mais le sac est arrivé en parfait état.", 'publie', '2026-07-28 11:30:00'),
('sac-lune-nacre', 'Mariam K. (démo)', 'Abidjan', 5, NULL, "Le travail est incroyablement régulier. On sent les heures passées dessus.", 'publie', '2026-07-02 16:45:00'),
('sac-lune-nacre', 'Sandrine G. (démo)', 'Bruxelles', 3, 'Joli mais délicat', "Très joli, mais il faut en prendre soin : une perle s'est détachée après quelques sorties. Réparée rapidement par l'atelier.", 'publie', '2026-06-15 08:00:00'),
('cabas-fleur-de-soleil', 'Claire D. (démo)', 'Paris', 5, 'Encore plus beau qu''en photo', "Une vraie pièce d'artisanat. Les fleurs orange sont encore plus belles qu'en photo.", 'publie', '2026-09-08 12:00:00'),
('cabas-fleur-de-soleil', 'Fatou N. (démo)', 'Cotonou', 4, 'Je le porte tous les jours', "Pratique et joyeux, il va avec toutes mes tenues.", 'publie', '2026-08-02 17:00:00'),
('panier-fleur-amethyste', 'Aïcha B. (démo)', 'Porto-Novo', 5, 'Ma mère l''adore', "J'ai offert ce panier à ma mère, elle ne le quitte plus.", 'publie', '2026-09-01 10:00:00');
