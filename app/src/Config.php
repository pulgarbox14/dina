<?php
declare(strict_types=1);

namespace App;

/**
 * Configuration lue dans le fichier `.env` à la racine du projet.
 * Priorité : variable d'environnement du serveur > fichier .env > valeur par défaut.
 */
final class Config
{
    /** Clé du .env => chemin dans la configuration. */
    private const ENV_MAP = [
        'APP_DEBUG'      => 'app.debug',
        'APP_BASE_PATH'  => 'app.base_path',
        'DB_HOST'        => 'db.host',
        'DB_PORT'        => 'db.port',
        'DB_NAME'        => 'db.name',
        'DB_USER'        => 'db.user',
        'DB_PASSWORD'    => 'db.password',
        'BRAND_WHATSAPP' => 'brand.whatsapp',
        'BRAND_EMAIL'    => 'brand.email',
        'INSTAGRAM_URL'  => 'social.instagram',
        'FACEBOOK_URL'   => 'social.facebook',
        'TIKTOK_URL'     => 'social.tiktok',
    ];

    private static array $values = [
        'app'   => ['debug' => false, 'base_path' => ''],
        'db'    => ['host' => '127.0.0.1', 'port' => 3306, 'name' => 'dina_perles', 'user' => 'root', 'password' => ''],
        'brand' => ['whatsapp' => '22990000000', 'email' => 'bonjour@dinaperles.com'],
    ];

    public static function load(string $envFile): void
    {
        $file = is_file($envFile) ? self::parseEnvFile($envFile) : [];
        foreach (self::ENV_MAP as $name => $key) {
            $value = getenv($name);
            if ($value === false) {
                $value = $file[$name] ?? null;
            }
            if ($value !== null) {
                self::set($key, $name === 'APP_DEBUG' ? filter_var($value, FILTER_VALIDATE_BOOLEAN) : $value);
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

    /**
     * Lecteur de .env minimal : lignes CLE=valeur, commentaires #, guillemets facultatifs.
     *
     * @return array<string, string>
     */
    private static function parseEnvFile(string $file): array
    {
        $values = [];
        foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }
            [$name, $value] = array_map('trim', explode('=', $line, 2));
            if (preg_match('/^(["\'])(.*?)\1/', $value, $m)) {
                $value = $m[2]; // entre guillemets : # et espaces gardés, commentaire après ignoré
            } else {
                $value = trim(preg_replace('/\s+#.*$/', '', $value));
            }
            $values[$name] = $value;
        }
        return $values;
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
