# Dina Perles — site PHP + MySQL

Boutique et portfolio de **Zinsou Secondina**, artisane en haute perlerie à Cotonou (Bénin) :
sacs et parures en perles tissés à la main, prix en FCFA, commande en ligne ou via WhatsApp.

> L'ancienne version React + FastAPI + MongoDB reste disponible sur la branche `main`.

## Technologies

| Couche | Choix | Pourquoi |
|---|---|---|
| Serveur | **PHP 8.1+**, sans framework ni Composer | Fonctionne chez n'importe quel hébergeur mutualisé |
| Base de données | **MySQL 5.7+ / MariaDB 10.3+** via PDO | Requêtes préparées (protection contre l'injection SQL) |
| Pages | Gabarits PHP (`app/views`) | HTML généré par le serveur : rapide et bien référencé |
| Style | Tailwind CSS, **compilé** dans `public/assets/css/app.css` | Aucun outil nécessaire sur l'hébergement |
| Interactions | JavaScript sans framework (`public/assets/js/app.js`) | Panier sans rechargement, animations, menu mobile |

Sans JavaScript, le site reste utilisable : chaque action (panier, commande, contact) est un vrai formulaire.

## Structure

```
.env.example             modèle de configuration → copier en .env (jamais dans Git)
app/
  bootstrap.php          démarrage (« bootstrap ») : config, session, erreurs
  routes.php             toutes les URL du site
  helpers.php            fonctions des gabarits (e(), price(), icon()…)
  src/                   classes : Database, Cart, ProductRepository, OrderRepository…
    Controllers/         PageController (pages), CartController (panier), FormController (formulaires)
  views/                 gabarits PHP : layouts/, partials/, pages/
database/
  schema.sql             création des tables
  seed.sql               les 9 créations de départ (réexécutable)
  avis.sql               avis réels des clientes (réexécutable)
  migrations/            modifications à appliquer sur une base déjà créée
  avis-clientes.exemple.csv  modèle pour importer les avis réels des clientes
bin/
  sql.php                exécute des fichiers SQL avec la connexion du .env
  importer-avis.php      import des avis réels depuis un fichier CSV
public/                  ← SEUL dossier exposé sur le web
  index.php              point d'entrée unique
  .htaccess              réécriture d'URL (Apache)
  assets/                CSS, JS et bibliothèques compilés
resources/               source du CSS Tailwind (à recompiler après modification)
tests/http_test.php      tests de bout en bout (51 vérifications)
```

## Pages et URL

| URL | Contenu |
|---|---|
| `/` | Accueil : héros, sélection, vitrine produits, sur mesure, commander en 3 étapes, avis |
| `/boutique?categorie=sacs\|minis\|bijoux` | Catalogue filtré |
| `/produit/{id}` | Fiche produit : photos, quantité, panier, WhatsApp |
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
   ├── .env
   ├── app/
   └── public_html/     (contenu de public/)
   ```
   Si l'hébergeur permet de choisir le dossier racine du domaine, envoyez tout le projet et pointez le domaine sur `public/`.
4. **Configurer** : copier `.env.example` en `.env` (à côté de `app/`, jamais dans `public_html/`) et renseigner la base, le numéro WhatsApp et l'e-mail.
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
mysql -u root dina_perles < database/avis.sql

# 2. Configuration
cp .env.example .env   # puis éditer (APP_DEBUG=true en local)

# 3. Serveur PHP intégré
php -S localhost:8000 -t public app/dev-router.php
```

Base créée avant le 26/09/2026 : lancer aussi `database/migrations/001-avis-sans-piece.sql` (avis sur l'atelier sans pièce associée).

Si le client `mysql` refuse l'accès (« Access denied »), passez par le site lui-même, qui utilise les identifiants du `.env` :

```bash
php bin/sql.php database/schema.sql database/migrations/001-avis-sans-piece.sql database/avis.sql
```

`schema.sql` est réexécutable : après une mise à jour qui ajoute une table (par exemple `reviews`), il suffit de le relancer, les tables existantes et leurs données ne sont pas touchées.

Une variable d'environnement du serveur (ex. `DB_NAME=dina_test`) a priorité sur la même clé du `.env`.

### Importer les avis des clientes

Pour publier les avis que Secondina a déjà reçus (WhatsApp, messages, appels…) :

1. Copier le modèle : `cp database/avis-clientes.exemple.csv database/avis-clientes.csv`
2. Remplir une ligne par avis, dans un tableur ou un éditeur de texte (séparateur `;`, enregistrer en CSV UTF-8) :
   `produit;nom;ville;note;titre;avis;date` : `produit` est l'identifiant de l'adresse `/produit/…` (vide pour un avis sur l'atelier en général, affiché sur l'accueil seulement), `note` va de 1 à 5, `ville`, `titre` et `date` (AAAA-MM-JJ) sont facultatifs.
3. Lancer : `php bin/importer-avis.php database/avis-clientes.csv`

Le fichier est vérifié en entier avant tout import : à la moindre erreur, rien n'est enregistré et la ligne fautive est indiquée. Relancer l'import ne crée pas de doublons. `database/avis-clientes.csv` contient des noms de clientes : il est ignoré par Git.

Uniquement de vrais avis, avec l'accord des clientes : les faux avis sont interdits (pratique commerciale trompeuse) et les clientes l'ont vite repéré.

### Modifier le style

Nécessite Node.js **uniquement sur votre ordinateur**, jamais sur l'hébergement :

```bash
npm install
npm run build        # recompile public/assets/css/app.css
npm run watch:css    # recompile le CSS à chaque modification des gabarits
```

Pensez à recompiler le CSS après avoir ajouté des classes Tailwind dans un gabarit, puis à committer `public/assets/`.

### Tests

```bash
mysql -u root -e "CREATE DATABASE dina_test"
mysql -u root dina_test < database/schema.sql && mysql -u root dina_test < database/seed.sql
DB_NAME=dina_test php -S 127.0.0.1:8080 -t public app/dev-router.php &
php tests/http_test.php http://127.0.0.1:8080

# Optionnel : vérifier que le site reste consultable si la base n'a pas de tables
mysql -u root -e "CREATE DATABASE dina_vide"
DB_NAME=dina_vide php -S 127.0.0.1:8082 -t public app/dev-router.php &
php tests/http_test.php http://127.0.0.1:8080 http://127.0.0.1:8082
```

## Sécurité

- **Prix jamais fournis par le navigateur** : le panier ne garde que « produit → quantité » en session, et le total est recalculé depuis MySQL.
- **Jeton CSRF** sur tous les formulaires, **requêtes préparées** PDO, **échappement HTML** de toute donnée affichée (`e()`).
- Cookie de session `HttpOnly` + `SameSite=Lax` (+ `Secure` en HTTPS), en-têtes `nosniff`, `X-Frame-Options`, `Referrer-Policy`.
- Champ piège anti-spam sur les formulaires de contact et de newsletter.
- Les commandes ne sont **pas** consultables publiquement : ni `GET /api/orders` ni autre liste exposée.
- `.env` hors du dossier web et ignoré par Git ; un `.htaccess` racine le bloque si le domaine pointe par erreur sur la racine du projet.
- **Tolérance aux pannes** : si MySQL ne répond pas, seules les zones produits affichent un message (avec lien WhatsApp) ; les autres pages restent consultables et les formulaires répondent « service indisponible » en gardant la saisie.
- Les erreurs sont enregistrées dans le journal PHP, jamais affichées aux visiteurs (sauf `debug => true`).

## À faire

- **Numéro WhatsApp réel** dans `.env` (`BRAND_WHATSAPP`) : c'est encore un numéro fictif.
- **Photos** : incluses dans `public/assets/img/produits/` (chemins enregistrés dans la table `product_images`). Pour ajouter une création, déposez sa photo dans ce dossier et ajoutez la ligne correspondante en base.
- **Espace admin** : en attendant, les commandes, messages et inscriptions se consultent dans phpMyAdmin (tables `orders`, `order_items`, `contact_messages`, `newsletter_subscribers`).
- **Notification** e-mail ou WhatsApp à Secondina pour chaque nouvelle commande.
