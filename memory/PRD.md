# Perlae Atelier — PRD

## Problème initial
Page d'atterrissage e-commerce/portfolio orientée 3D pour une artisane qui confectionne des sacs et bijoux en perles. Site blanc, pages Accueil / Boutique / À propos / Contact / détail produit / panier / page artisane. Devise FCFA. Photos retouchées façon studio.

## Architecture
- Frontend React 19 (CRA/craco) + Tailwind + shadcn + framer-motion + lenis + @react-three/fiber/drei (three)
- Backend FastAPI (`/api`) + MongoDB (motor). Seed produits au démarrage (`seed_data.py`).
- Panier en localStorage (CartContext). Commandes enregistrées en base + lien WhatsApp pré-rempli.

## Endpoints
- GET /api/products?category=&featured=
- GET /api/products/{id}
- POST /api/orders · GET /api/orders
- POST /api/contact
- POST /api/newsletter

## Implémenté (24/09/2026)
- Héros 3D (sac en perles Three.js, suit la souris) + révélation ligne par ligne
- Marquee éditorial, bento produits phares, section artisane (parallaxe), process, avis
- Boutique avec filtres catégories, détail produit (galerie, quantité, accordéons, WhatsApp)
- Tiroir panier + page panier + formulaire de commande + WhatsApp
- Pages L'Artisane, À propos, Contact (formulaire + FAQ), footer newsletter
- 10 photos retouchées en studio (génération IA à partir des photos client)

## Backlog
- P0 : numéro WhatsApp réel (`src/lib/config.js` → BRAND.whatsapp), nom réel de l'artisane
- P1 : espace admin pour ajouter produits/photos (upload), gestion des commandes
- P1 : paiement en ligne (Wave / Orange Money / Stripe)
- P2 : multi-langue, avis clients dynamiques, Instagram feed
