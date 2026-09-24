<?php
declare(strict_types=1);

namespace App;

/**
 * Configuration : valeurs par défaut < app/config.local.php < variables d'environnement.
 */
final class Config
{
    private const ENV_MAP = [
        'DB_HOST'      => 'db.host',
        'DB_PORT'      => 'db.port',
        'DB_NAME'      => 'db.name',
        'DB_USER'      => 'db.user',
        'DB_PASSWORD'  => 'db.password',
        'APP_DEBUG'    => 'app.debug',
        'APP_BASE_PATH' => 'app.base_path',
        'BRAND_WHATSAPP' => 'brand.whatsapp',
    ];

    private static array $values = [
        'app'   => ['debug' => false, 'base_path' => ''],
        'db'    => ['host' => '127.0.0.1', 'port' => 3306, 'name' => 'dina_perles', 'user' => 'root', 'password' => ''],
        'brand' => ['whatsapp' => '22990000000', 'email' => 'bonjour@dinaperles.com'],
    ];

    public static function load(string $file): void
    {
        if (is_file($file)) {
            $local = require $file;
            if (is_array($local)) {
                self::$values = array_replace_recursive(self::$values, $local);
            }
        }
        foreach (self::ENV_MAP as $env => $key) {
            $value = getenv($env);
            if ($value !== false && $value !== '') {
                self::set($key, $env === 'APP_DEBUG' ? filter_var($value, FILTER_VALIDATE_BOOLEAN) : $value);
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $node = self::$values;
        foreach (explode('.', $key) as $part) {
            if (!is_array($node) || !array_key_exists($part, $node)) {
                return $default;
            }
            $node = $node[$part];
        }
        return $node;
    }

    private static function set(string $key, mixed $value): void
    {
        $node = &self::$values;
        foreach (explode('.', $key) as $part) {
            $node = &$node[$part];
        }
        $node = $value;
    }
}
