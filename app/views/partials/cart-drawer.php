<?php /** @var array $cart */ ?>
<div data-cart-drawer class="cart-drawer" aria-hidden="true">
    <div data-cart-close data-testid="cart-drawer-overlay" class="cart-overlay fixed inset-0 z-[70] bg-[var(--ink)]/30 backdrop-blur-sm"></div>
    <aside data-testid="cart-drawer" data-lenis-prevent role="dialog" aria-modal="true" aria-label="Votre panier"
           class="cart-panel fixed right-0 top-0 h-full w-full max-w-md z-[80] bg-[var(--bg)] flex flex-col shadow-2xl">
        <div class="flex items-center justify-between px-8 h-[76px] border-b border-[var(--line)]">
            <span class="font-display text-2xl">Votre panier</span>
            <button type="button" data-cart-close data-testid="cart-drawer-close" aria-label="Fermer le panier"
                    class="h-10 w-10 rounded-full border border-[var(--line)] flex items-center justify-center"><?= icon('x', 16) ?></button>
        </div>
        <div data-cart-drawer-body class="flex-1 flex flex-col min-h-0">
            <?= App\View::partial('partials/cart-drawer-body', ['cart' => $cart]) ?>
        </div>
    </aside>
</div>
