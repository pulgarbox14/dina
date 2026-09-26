<?php
declare(strict_types=1);

/**
 * Exécute un ou plusieurs fichiers SQL avec la connexion du site (identifiants du .env).
 * Évite les soucis d'accès du client `mysql` (mot de passe, socket, utilisateur différent).
 *
 * Usage :  php bin/sql.php database/schema.sql [database/avis.sql …]
 *
 * Les fichiers du dossier database/ terminent chaque requête par « ; » en fin de ligne :
 * c'est ce qui sert à les découper. Les lignes commençant par « -- » sont ignorées.
 */

use App\Database;

require dirname(__DIR__) . '/app/bootstrap.php';

$files = array_slice($argv, 1);
if ($files === []) {
    fwrite(STDERR, "Usage : php bin/sql.php fichier.sql [autre.sql …]\n");
    exit(1);
}

$pdo = Database::pdo();
foreach ($files as $file) {
    if (!is_readable($file)) {
        fwrite(STDERR, "Fichier introuvable : $file\n");
        exit(1);
    }
    $sql = preg_replace('/^\s*--.*$/m', '', (string) file_get_contents($file));
    $statements = array_filter(array_map('trim', preg_split('/;\s*$/m', $sql)));
    foreach ($statements as $statement) {
        $pdo->exec($statement);
    }
    printf("%s : %d requête(s) exécutée(s).\n", $file, count($statements));
}
