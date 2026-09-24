<?php
declare(strict_types=1);

namespace App;

use PDO;

/**
 * Connexion PDO unique, ouverte à la première requête.
 * Requêtes préparées natives (pas d'émulation) : protège contre l'injection SQL
 * et renvoie les entiers MySQL en int PHP.
 */
final class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                Config::get('db.host'),
                (int) Config::get('db.port'),
                Config::get('db.name')
            );
            self::$pdo = new PDO($dsn, (string) Config::get('db.user'), (string) Config::get('db.password'), [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ]);
        }
        return self::$pdo;
    }
}
