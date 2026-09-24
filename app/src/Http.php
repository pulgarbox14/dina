<?php
declare(strict_types=1);

namespace App;

/** Petites aides autour de la requête et de la réponse HTTP. */
final class Http
{
    public static function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    /** Requête envoyée par notre JavaScript (fetch) qui attend du JSON. */
    public static function wantsJson(): bool
    {
        return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    }

    public static function input(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? $default;
        return is_string($value) ? trim($value) : $default;
    }

    public static function query(string $key, string $default = ''): string
    {
        $value = $_GET[$key] ?? $default;
        return is_string($value) ? trim($value) : $default;
    }

    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }

    public static function redirect(string $path, int $status = 303): never
    {
        header('Location: ' . url($path), true, $status);
        exit;
    }

    /** Revient à la page d'origine, seulement si elle appartient à notre site. */
    public static function back(string $fallback = '/'): never
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $parts = parse_url($referer);
        if ($referer !== '' && isset($parts['host']) && $parts['host'] === explode(':', $host)[0]) {
            $target = ($parts['path'] ?? '/') . (isset($parts['query']) ? '?' . $parts['query'] : '');
            header('Location: ' . $target, true, 303);
            exit;
        }
        self::redirect($fallback);
    }
}
