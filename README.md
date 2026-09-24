# Dina Perles — site PHP + MySQL

Boutique et portfolio de **Zinsou Secondina Dagbédé**, artisane en haute perlerie à Cotonou (Bénin) :
sacs et parures en perles tissés à la main, prix en FCFA, commande en ligne ou via WhatsApp.

> L'ancienne version React + FastAPI + MongoDB reste disponible sur la branche `main`.

## Technologies

| Couche | Choix | Pourquoi |
|---|---|---|
| Serveur | **PHP 8.1+**, sans framework ni Composer | Fonctionne chez n'importe quel hébergeur mutualisé |
| Base de données | **MySQL 5.7+ / MariaDB 10.3+** via PDO | Requêtes préparées (protection contre l'injection SQL) |
| Pages | Gabarits PHP (`app/views`) | HTML généré par le serveur : rapide et bien référencé |
| Style | Tailwind CSS, **compilé** dans `public/assets/css/app.css` | Aucun outil nécessaire sur l'hébergement |
| 3D | Three.js, **compilé** dans `public/assets/js/pearl3d.js` | Mêmes sacs en perles 3D que la version React |
| Interactions | JavaScript sans framework (`public/assets/js/app.js`) | Panier sans rechargement, animations, menu mobile |

Sans JavaScript, le site reste utilisable : chaque action (panier, commande, contact) est un vrai formulaire.

## Structure

```
app/
  bootstrap.php          démarrage : config, session, erreurs
  config.example.php     modèle de configuration → copier en config.local.php
  routes.php             toutes les URL du site
  helpers.php            fonctions des gabarits (e(), price(), icon()…)
  src/                   classes : Database, Cart, ProductRepository, OrderRepository…
    Controllers/         PageController (pages), CartController (panier), FormController (formulaires)
  views/                 gabarits PHP : layouts/, partials/, pages/
database/
  schema.sql             création des tables
  seed.sql               les 9 créations de départ (réexécutable)
public/                  ← SEUL dossier exposé sur le web
  index.php              point d'entrée unique
  .htaccess              réécriture d'URL (Apache)
  assets/                CSS, JS et bibliothèques compilés
resources/               sources du CSS et de la 3D (à recompiler après modification)
tests/http_test.php      tests de bout en bout (42 vérifications)
```

## Pages et URL

| URL | Contenu |
|---|---|
| `/` | Accueil : héros, sélection, configurateur 3D, savoir-faire, avis |
| `/boutique?categorie=sacs\|minis\|bijoux` | Catalogue filtré |
| `/produit/{id}` | Fiche produit : photos, vue 3D, quantité, panier, WhatsApp |
| `/artisane`, `/a-propos` | Pages de présentation |
| `/contact` | Formulaire de contact + FAQ |
| `/panier` → `/commande/confirmee` | Panier, commande, confirmation |
| `POST /panier/ajouter`, `/panier/modifier`, `/panier/retirer` | Actions du panier |
| `POST /commande`, `/contact`, `/newsletter` | Formulaires |

## Installation chez un hébergeur (Hostinger, o2switch, LWS…)

1. **Créer la base MySQL** dans le panneau de l'hébergeur (nom, utilisateur, mot de passe).
2. **Importer** dans phpMyAdmin : d'abord `database/schema.sql`, puis `database/seed.sql`.
3. **Envoyer les fichiers** (FTP ou gestionnaire de fichiers) :
   - le **contenu** de `public/` dans `public_html/` (ou `www/`) ;
   - le dossier `app/` **à côté** de `public_html/`, jamais dedans, pour qu'il ne soit pas accessible depuis le web.

   ```
   /home/votre-compte/
   ├── app/
   └── public_html/     (contenu de public/)
   ```
   Si l'hébergeur permet de choisir le dossier racine du domaine, envoyez tout le projet et pointez le domaine sur `public/`.
4. **Configurer** : copier `app/config.example.php` en `app/config.local.php` et renseigner la base, le numéro WhatsApp et l'e-mail.
5. **Activer HTTPS** (Let's Encrypt, gratuit chez la plupart des hébergeurs).

Si `app/` est placé à côté de `public_html/`, le chemin `dirname(__DIR__) . '/app/…'` de `public/index.php` fonctionne tel quel.

### Nginx (VPS)

```nginx
root /var/www/dina/public;
index index.php;
location / { try_files $uri /index.php$is_args$args; }
location ~ \.php$ { include fastcgi_params; fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; fastcgi_pass unix:/run/php/php8.3-fpm.sock; }
```

## Développement local

```bash
# 1. Base de données
mysql -u root -e "CREATE DATABASE dina_perles CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
mysql -u root dina_perles < database/schema.sql
mysql -u root dina_perles < database/seed.sql

# 2. Configuration
cp app/config.example.php app/config.local.php   # puis éditer (mettre 'debug' => true en local)

# 3. Serveur PHP intégré
php -S localhost:8000 -t public app/dev-router.php
```

Les variables d'environnement `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASSWORD`, `APP_DEBUG` et `BRAND_WHATSAPP` remplacent `config.local.php` si elles sont définies.

### Modifier le style ou la 3D

Nécessite Node.js **uniquement sur votre ordinateur**, jamais sur l'hébergement :

```bash
npm install
npm run build        # recompile public/assets/css/app.css et public/assets/js/pearl3d.js
npm run watch:css    # recompile le CSS à chaque modification des gabarits
```

Pensez à recompiler le CSS après avoir ajouté des classes Tailwind dans un gabarit, puis à committer `public/assets/`.

### Tests

```bash
mysql -u root -e "CREATE DATABASE dina_test"
mysql -u root dina_test < database/schema.sql && mysql -u root dina_test < database/seed.sql
DB_NAME=dina_test php -S 127.0.0.1:8080 -t public app/dev-router.php &
php tests/http_test.php http://127.0.0.1:8080
```

## Sécurité

- **Prix jamais fournis par le navigateur** : le panier ne garde que « produit → quantité » en session, et le total est recalculé depuis MySQL.
- **Jeton CSRF** sur tous les formulaires, **requêtes préparées** PDO, **échappement HTML** de toute donnée affichée (`e()`).
- Cookie de session `HttpOnly` + `SameSite=Lax` (+ `Secure` en HTTPS), en-têtes `nosniff`, `X-Frame-Options`, `Referrer-Policy`.
- Champ piège anti-spam sur les formulaires de contact et de newsletter.
- Les commandes ne sont **pas** consultables publiquement : ni `GET /api/orders` ni autre liste exposée.
- Les erreurs sont enregistrées dans le journal PHP, jamais affichées aux visiteurs (sauf `debug => true`).

## À faire

- **Numéro WhatsApp réel** dans `app/config.local.php` (`brand.whatsapp`) : c'est encore un numéro fictif.
- **Photos** : elles sont hébergées chez Emergent (`static.prod-images.emergentagent.com`). Pour ne plus en dépendre, copiez-les dans `public/assets/img/` et mettez à jour les URL (table `product_images` et fonction `gallery()` dans `app/helpers.php`).
- **Espace admin** : en attendant, les commandes, messages et inscriptions se consultent dans phpMyAdmin (tables `orders`, `order_items`, `contact_messages`, `newsletter_subscribers`).
- **Notification** e-mail ou WhatsApp à Secondina pour chaque nouvelle commande.
