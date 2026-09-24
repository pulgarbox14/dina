<?php
/** @var int $count */
$links = [
    ['/', 'Accueil', 'accueil'],
    ['/boutique', 'Boutique', 'boutique'],
    ['/artisane', "L'Artisane", 'artisane'],
    ['/a-propos', 'À propos', 'a-propos'],
    ['/contact', 'Contact', 'contact'],
];
?>
<header data-testid="navbar" class="fixed top-3 sm:top-5 inset-x-3 sm:inset-x-5 z-50">
    <div data-navbar-bar class="max-w-[1400px] mx-auto px-4 sm:px-6 h-[64px] rounded-full flex items-center justify-between glass border border-white/60 transition-shadow duration-500 shadow-[0_10px_40px_-24px_rgba(20,20,20,0.25)]">
        <a href="<?= e(url('/')) ?>" data-testid="nav-logo-link" aria-label="Dina Perles — accueil">
            <?= App\View::partial('partials/logo') ?>
        </a>
        <nav class="hidden md:flex items-center gap-10">
            <?php foreach ($links as [$to, $label, $id]): ?>
                <a href="<?= e(url($to)) ?>" data-testid="nav-link-<?= $id ?>"
                   class="link-underline text-sm tracking-wide <?= is_active($to) ? 'active' : 'text-[var(--ink-2)]' ?>"
                   <?= is_active($to) ? 'aria-current="page"' : '' ?>><?= e($label) ?></a>
            <?php endforeach; ?>
        </nav>
        <div class="flex items-center gap-3">
            <span class="hidden md:block"><a href="<?= e(url('/boutique')) ?>" data-testid="nav-cta-button" class="btn-pill btn-dark !h-11 !px-6">Commander</a></span>
            <a href="<?= e(url('/panier')) ?>" data-cart-open data-testid="cart-drawer-toggle" aria-label="Panier"
               class="relative h-11 w-11 rounded-full bg-white border border-[var(--line)] flex items-center justify-center hover:bg-[var(--ink)] hover:text-[var(--pearl)] transition-colors duration-300">
                <?= icon('shopping-bag', 18, 1.6) ?>
                <span data-cart-count data-testid="cart-count-badge"
                      class="absolute -top-1 -right-1 h-5 min-w-5 px-1 rounded-full bg-[var(--gold)] text-[var(--ink)] text-[11px] font-semibold flex items-center justify-center <?= $count > 0 ? '' : 'hidden' ?>"><?= $count ?></span>
            </a>
            <button type="button" data-menu-toggle data-testid="mobile-menu-toggle" aria-label="Menu" aria-expanded="false" aria-controls="mobile-menu"
                    class="md:hidden h-11 w-11 rounded-full bg-white border border-[var(--line)] flex items-center justify-center">
                <span data-menu-icon="open"><?= icon('menu', 18) ?></span>
                <span data-menu-icon="close" class="hidden"><?= icon('x', 18) ?></span>
            </button>
        </div>
    </div>
    <nav id="mobile-menu" data-mobile-menu data-testid="mobile-menu"
         class="mobile-menu hidden md:!hidden glass mt-3 rounded-[28px] border border-white/60 shadow-xl px-6 py-8 flex flex-col gap-6">
        <?php foreach ($links as [$to, $label, $id]): ?>
            <a href="<?= e(url($to)) ?>" data-testid="mobile-nav-link-<?= $id ?>" class="font-display text-3xl"><?= e($label) ?></a>
        <?php endforeach; ?>
    </nav>
</header>
