<?php
declare(strict_types=1);

namespace App;

/**
 * Session PHP sécurisée + messages « flash » (affichés une seule fois, après une redirection)
 * + jeton CSRF qui protège tous les formulaires POST.
 */
final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }
        $https = ($_SERVER['HTTPS'] ?? '') === 'on' || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
        session_name('dina_session');
        session_set_cookie_params([
            'lifetime' => 60 * 60 * 24 * 30,
            'path'     => '/',
            'secure'   => $https,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        ini_set('session.use_strict_mode', '1');
        ini_set('session.gc_maxlifetime', (string) (60 * 60 * 24 * 30));
        session_start();
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function pull(string $key, mixed $default = null): mixed
    {
        $value = $_SESSION[$key] ?? $default;
        unset($_SESSION[$key]);
        return $value;
    }

    /** Message affiché en « toast » sur la page suivante. */
    public static function flash(string $type, string $message): void
    {
        $_SESSION['_flash'][] = ['type' => $type, 'message' => $message];
    }

    public static function takeFlashes(): array
    {
        return self::pull('_flash', []);
    }

    /** Saisie et erreurs d'un formulaire, pour le réafficher après une redirection. */
    public static function flashForm(string $form, array $old, array $errors): void
    {
        $_SESSION['_form'][$form] = ['old' => $old, 'errors' => $errors];
    }

    public static function takeForm(string $form): array
    {
        $data = $_SESSION['_form'][$form] ?? ['old' => [], 'errors' => []];
        unset($_SESSION['_form'][$form]);
        return $data;
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function checkCsrf(?string $token): bool
    {
        return is_string($token) && !empty($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
    }
}
