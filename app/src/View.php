<?php
declare(strict_types=1);

namespace App;

/**
 * Rendu des gabarits PHP de `app/views`.
 * Une page est rendue puis insérée dans le gabarit général (navbar, footer, panier).
 */
final class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/main'): string
    {
        $content = self::partial($view, $data);
        if ($layout === null) {
            return $content;
        }
        return self::partial($layout, $data + ['content' => $content]);
    }

    public static function partial(string $view, array $data = []): string
    {
        $file = APP_ROOT . '/views/' . $view . '.php';
        if (!is_file($file)) {
            throw new \RuntimeException("Vue introuvable : $view");
        }
        extract($data, EXTR_SKIP);
        ob_start();
        try {
            require $file;
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
        return (string) ob_get_clean();
    }
}
