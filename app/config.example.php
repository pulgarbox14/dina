<?php
/**
 * Copiez ce fichier en `app/config.local.php` et renseignez vos valeurs.
 * `config.local.php` est ignoré par Git : ne mettez jamais de mot de passe dans le dépôt.
 *
 * Chaque valeur peut aussi venir d'une variable d'environnement (DB_HOST, DB_NAME…),
 * pratique en local ou sur un hébergement qui les propose.
 */
return [
    'app' => [
        // true uniquement en local : affiche le détail des erreurs PHP.
        'debug' => false,
        // Laisser vide si le site est à la racine du domaine, sinon ex. '/boutique'.
        'base_path' => '',
    ],
    'db' => [
        'host'     => 'localhost',
        'port'     => 3306,
        'name'     => 'dina_perles',
        'user'     => 'dina_user',
        'password' => 'change-moi',
    ],
    'brand' => [
        // Numéro WhatsApp au format international, sans + ni espaces.
        'whatsapp' => '22990000000',
        'email'    => 'bonjour@dinaperles.com',
    ],
];
