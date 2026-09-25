<?php
declare(strict_types=1);

namespace App;

/**
 * Routeur minimal : associe « méthode + chemin » à une action.
 * Les segments `{nom}` capturent une partie de l'URL (ex. /produit/{id}).
 */
final class Router
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [];

    public function get(string $path, callable $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, callable $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = rawurldecode(parse_url($uri, PHP_URL_PATH) ?: '/');
        $base = rtrim((string) Config::get('app.base_path', ''), '/');
        if ($base !== '' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base)) ?: '/';
        }
        $path = $path === '/' ? '/' : rtrim($path, '/');

        foreach ($this->routes[$method] ?? [] as $pattern => $action) {
            $regex = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[a-z0-9-]+)', $pattern) . '$#';
            if (preg_match($regex, $path, $m)) {
                $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
                $action(...$params);
                return;
            }
        }

        $allowed = array_filter(['GET', 'POST'], fn ($m) => $m !== $method && $this->matchesAny($m, $path));
        if ($allowed) {
            header('Allow: ' . implode(', ', $allowed));
            http_response_code(405);
            echo View::render('pages/erreur', ['title' => 'Page introuvable', 'code' => 405]);
            return;
        }

        http_response_code(404);
        echo View::render('pages/erreur', ['title' => 'Page introuvable', 'code' => 404]);
    }

    private function matchesAny(string $method, string $path): bool
    {
        foreach (array_keys($this->routes[$method] ?? []) as $pattern) {
            if (preg_match('#^' . preg_replace('#\{(\w+)\}#', '[a-z0-9-]+', $pattern) . '$#', $path)) {
                return true;
            }
        }
        return false;
    }
}
