<?php
declare(strict_types=1);

/**
 * Importe des avis réels de clientes depuis un fichier CSV (séparateur « ; », encodage UTF-8).
 *
 * Usage :  php bin/importer-avis.php database/avis-clientes.csv
 *
 * Colonnes : produit;nom;ville;note;titre;avis;date
 *   - produit : identifiant de la pièce (celui de l'adresse /produit/…), ex. sac-lune-nacre ;
 *               vide pour un avis sur l'atelier en général (affiché sur l'accueil seulement)
 *   - note    : 1 à 5
 *   - ville, titre et date (AAAA-MM-JJ) sont facultatifs
 *
 * Le fichier est entièrement vérifié avant d'écrire quoi que ce soit : à la moindre erreur,
 * rien n'est importé. Relancer l'import ne crée pas de doublons (même pièce, même nom, même texte).
 * Ces avis ne sont reliés à aucune commande du site : ils s'affichent sans le badge « Achat vérifié ».
 */

use App\Database;

require dirname(__DIR__) . '/app/bootstrap.php';

$file = $argv[1] ?? '';
if ($file === '' || !is_readable($file)) {
    fwrite(STDERR, "Usage : php bin/importer-avis.php chemin/vers/avis.csv\n");
    exit(1);
}

$pdo = Database::pdo();
$products = $pdo->query('SELECT id FROM products')->fetchAll(PDO::FETCH_COLUMN);

$handle = fopen($file, 'rb');
$rows = [];
$errors = [];
$line = 0;
while (($cols = fgetcsv($handle, 0, ';', '"', '')) !== false) {
    $line++;
    if ($line === 1 && isset($cols[0]) && preg_replace('/^\xEF\xBB\xBF/', '', trim($cols[0])) === 'produit') {
        continue; // ligne d'en-tête (avec ou sans BOM ajouté par Excel)
    }
    if ($cols === [null] || trim(implode('', $cols)) === '') {
        continue; // ligne vide
    }
    [$product, $name, $city, $rating, $title, $body, $date] = array_map('trim', array_pad($cols, 7, ''));

    $problems = [];
    if ($product !== '' && !in_array($product, $products, true)) {
        $problems[] = "pièce « $product » inconnue";
    }
    if ($name === '') {
        $problems[] = 'nom manquant';
    }
    if (!ctype_digit($rating) || (int) $rating < 1 || (int) $rating > 5) {
        $problems[] = 'note entre 1 et 5 attendue';
    }
    if ($body === '') {
        $problems[] = "texte de l'avis manquant";
    }
    if ($date !== '' && \DateTime::createFromFormat('!Y-m-d', $date) === false) {
        $problems[] = 'date au format AAAA-MM-JJ attendue';
    }
    if ($problems !== []) {
        $errors[] = "ligne $line : " . implode(', ', $problems);
        continue;
    }
    $rows[] = [
        'product_id'   => $product !== '' ? $product : null,
        'author_name'  => mb_substr($name, 0, 120),
        'city'         => $city !== '' ? mb_substr($city, 0, 120) : null,
        'rating'       => (int) $rating,
        'title'        => $title !== '' ? mb_substr($title, 0, 160) : null,
        'body'         => $body,
        'published_at' => ($date !== '' ? $date : date('Y-m-d')) . ' 12:00:00',
    ];
}
fclose($handle);

if ($errors !== []) {
    fwrite(STDERR, "Rien n'a été importé, corrigez d'abord le fichier :\n  " . implode("\n  ", $errors) . "\n");
    exit(1);
}

$exists = $pdo->prepare('SELECT 1 FROM reviews WHERE product_id <=> ? AND author_name = ? AND body = ? LIMIT 1');
$insert = $pdo->prepare(
    "INSERT INTO reviews (product_id, author_name, city, rating, title, body, status, published_at)
     VALUES (:product_id, :author_name, :city, :rating, :title, :body, 'publie', :published_at)"
);
$added = 0;
$pdo->beginTransaction();
foreach ($rows as $r) {
    $exists->execute([$r['product_id'], $r['author_name'], $r['body']]);
    if ($exists->fetchColumn() !== false) {
        continue;
    }
    $insert->execute($r);
    $added++;
}
$pdo->commit();

printf("%d avis importé(s), %d déjà présent(s).\n", $added, count($rows) - $added);
