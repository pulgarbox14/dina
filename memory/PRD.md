# Dina Perles — PRD

## Problème initial
Page d'atterrissage e-commerce/portfolio orientée 3D pour une artisane qui confectionne des sacs et bijoux en perles. Site blanc, pages Accueil / Boutique / À propos / Contact / détail produit / panier / page artisane. Devise FCFA. Photos retouchées façon studio.

## Architecture (branche PHP)
- PHP 8.1+ sans framework : contrôleur frontal `public/index.php`, routeur maison, gabarits `app/views`
- MySQL / MariaDB via PDO (requêtes préparées) : `database/schema.sql` + `database/seed.sql`
- Tailwind compilé (`public/assets/css/app.css`), 3D Three.js compilée (`public/assets/js/pearl3d.js`), JS sans framework (`public/assets/js/app.js`)
- Panier en session PHP (id → quantité), prix toujours relus en base. Commandes enregistrées en base + lien WhatsApp pré-rempli.
- Ancienne architecture (branche `main`) : React 19 + FastAPI + MongoDB.

## Routes
- GET / · /boutique?categorie= · /produit/{id} · /artisane · /a-propos · /contact · /panier · /commande/confirmee
- POST /panier/ajouter · /panier/modifier · /panier/retirer (JSON si Accept: application/json)
- POST /commande · /contact · /newsletter
- Protection CSRF sur tous les POST ; aucune liste de commandes exposée publiquement.

## Implémenté (24/09/2026)
- Héros 3D (sac en perles Three.js, suit la souris) + révélation ligne par ligne
- Marquee éditorial, bento produits phares, section artisane (parallaxe), process, avis
- Boutique avec filtres catégories, détail produit (galerie, quantité, accordéons, WhatsApp)
- Tiroir panier + page panier + formulaire de commande + WhatsApp
- Pages L'Artisane, À propos, Contact (formulaire + FAQ), footer newsletter
- 10 photos retouchées en studio (génération IA à partir des photos client)

## Implémenté (24/09/2026 — itération 3)
- Police Poppins (réf. Veluno), blocs et cartes arrondis avec profondeur 3D (card-3d + tilt)
- Page L'Artisane en 2 rendus à comparer : /artisane (Version A, portrait détouré + badges flottants, réf. Copyscale) et /artisane-2 (Version B, éditorial avec carte produit, réf. Veluno) + switch flottant
- Nouveau footer (bloc arrondi clair, CTA, colonnes, newsletter)
- Portrait détouré généré localement (rembg) → /frontend/public/artisan-cutout.png

## Implémenté (24/09/2026 — itération 4)
- Version A retenue pour L'Artisane (switch et /artisane-2 supprimés)
- Palette 100 % blanche/neutre (plus de crème/taupe) : héros accueil sur fond blanc-gris avec portrait détouré
- Portrait détouré recadré sur mobile (visage visible), fondu doux sur le bord du bras + fondu bas
- Bouton « Commander » masqué sur mobile

## Implémenté (24/09/2026 — migration PHP + MySQL)
- Réécriture complète en PHP (pages, panier, commande, contact, newsletter) avec rendu identique
- 3D portée en Three.js pur (mêmes modèles perle par perle, Float + OrbitControls reproduits)
- Sécurité : total recalculé serveur, CSRF, XSS, anti-spam, GET /api/orders supprimé
- Tests : tests/http_test.php (42 vérifications)

## Backlog
- P0 : numéro WhatsApp réel (`.env` → BRAND_WHATSAPP)
- P0 : rapatrier les photos hébergées chez Emergent dans public/assets/img
- P1 : espace admin pour ajouter produits/photos (upload), gestion des commandes
- P1 : paiement en ligne (Wave / Orange Money / Stripe)
- P2 : multi-langue, avis clients dynamiques, Instagram feed
