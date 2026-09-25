<?php
/**
 * Gabarit général : <head>, navbar, tiroir panier, contenu de la page, footer.
 *
 * @var string $content  HTML de la page
 * @var string|null $title
 * @var string|null $description
 */
$cart = App\Cart::summary();
$pageTitle = isset($title) ? $title . ' — Dina Perles' : 'Dina Perles — Haute Perlerie Artisanale';
$metaDescription = $description ?? 'Dina Perles — sacs et bijoux en perles tissés à la main. Haute perlerie artisanale.';
$flashes = App\Session::takeFlashes();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FAFAFA">
    <meta name="description" content="<?= e(mb_strimwidth($metaDescription, 0, 180, '…')) ?>">
    <meta name="csrf-token" content="<?= e(App\Session::csrfToken()) ?>">
    <title><?= e($pageTitle) ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= e(url('favicon.svg')) ?>">
    <link rel="preload" href="<?= e(url('assets/fonts/syne-latin-wght-normal.woff2')) ?>" as="font" type="font/woff2" crossorigin>
    <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
    <script>document.documentElement.classList.add('js')</script>
</head>
<body>
    <?= App\View::partial('partials/navbar', ['count' => $cart['count']]) ?>
    <?= App\View::partial('partials/cart-drawer', ['cart' => $cart]) ?>

    <main>
        <?= $content ?>
    </main>

    <?= App\View::partial('partials/footer') ?>

    <div id="toasts" class="fixed bottom-4 right-4 left-4 sm:left-auto z-[100] flex flex-col gap-2 items-end pointer-events-none" aria-live="polite"></div>
    <script type="application/json" id="flash-data"><?= json_encode($flashes, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?></script>

    <script src="<?= e(asset('vendor/lenis.min.js')) ?>" defer></script>
    <script src="<?= e(asset('js/app.js')) ?>" defer></script>
</body>
</html>
