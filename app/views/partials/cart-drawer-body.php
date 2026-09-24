<?php /** @var array $cart  résultat de App\Cart::summary() */ ?>
<div class="flex-1 overflow-y-auto px-8 py-6 space-y-6" data-lenis-prevent>
    <?php if ($cart['items'] === []): ?>
        <p data-testid="cart-empty-message" class="text-[var(--ink-3)] text-sm">Votre panier est vide. Laissez-vous tenter par une pièce unique.</p>
    <?php endif; ?>
    <?php foreach ($cart['items'] as $item): ?>
        <div class="flex gap-4" data-testid="cart-item-<?= e($item['id']) ?>">
            <img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" class="h-24 w-20 object-cover bg-[var(--luster)]">
            <div class="flex-1">
                <div class="flex justify-between gap-2">
                    <span class="font-display text-lg leading-tight"><?= e($item['name']) ?></span>
                    <form method="post" action="<?= e(url('/panier/retirer')) ?>" data-cart-form>
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= e($item['id']) ?>">
                        <button data-testid="cart-remove-<?= e($item['id']) ?>" aria-label="Retirer <?= e($item['name']) ?>" class="text-[var(--ink-3)] hover:text-[var(--ink)]"><?= icon('trash-2', 15) ?></button>
                    </form>
                </div>
                <span class="font-mono text-xs text-[var(--ink-2)]"><?= price($item['price']) ?></span>
                <div class="mt-3">
                    <?= App\View::partial('partials/cart-qty', ['item' => $item, 'prefix' => 'cart', 'size' => 'h-8 w-8']) ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="px-8 py-6 border-t border-[var(--line)] space-y-4">
    <div class="flex justify-between text-sm">
        <span class="text-[var(--ink-2)]">Sous-total</span>
        <span class="font-mono" data-testid="cart-drawer-total"><?= price($cart['total']) ?></span>
    </div>
    <a href="<?= e(url('/panier')) ?>" data-testid="cart-drawer-checkout-link"
       class="btn-pill btn-dark w-full <?= $cart['items'] === [] ? 'pointer-events-none opacity-40' : '' ?>">Passer commande</a>
</div>
