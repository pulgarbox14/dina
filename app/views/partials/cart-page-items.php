<?php /** @var array $cart  liste des articles de la page Panier (remplacée en direct par le JavaScript) */ ?>
<?php if (App\ProductRepository::unavailable()): ?>
    <?= App\View::partial('partials/catalog-unavailable') ?>
<?php elseif ($cart['items'] === []): ?>
    <div data-testid="cart-page-empty" class="border border-dashed border-[var(--line)] p-16 text-center">
        <p class="text-[var(--ink-2)]">Votre panier est vide.</p>
        <a href="<?= e(url('/boutique')) ?>" data-testid="cart-page-empty-link" class="btn-pill btn-ghost mt-6">Découvrir la boutique</a>
    </div>
<?php else: ?>
    <div class="divide-y divide-[var(--line)] border-y border-[var(--line)]">
        <?php foreach ($cart['items'] as $item): ?>
            <div class="py-6 flex gap-6" data-testid="cart-page-item-<?= e($item['id']) ?>">
                <img src="<?= e($item['image']) ?>" alt="<?= e($item['name']) ?>" class="h-32 w-24 object-cover bg-[var(--luster)]">
                <div class="flex-1 flex flex-col justify-between">
                    <div class="flex justify-between gap-4">
                        <a href="<?= e(url('/produit/' . $item['id'])) ?>" class="font-display text-2xl leading-tight"><?= e($item['name']) ?></a>
                        <span class="font-mono text-sm"><?= price($item['line_total']) ?></span>
                    </div>
                    <div class="flex items-center justify-between">
                        <?= App\View::partial('partials/cart-qty', ['item' => $item, 'prefix' => 'cart-page', 'size' => 'h-9 w-9']) ?>
                        <form method="post" action="<?= e(url('/panier/retirer')) ?>" data-cart-form>
                            <?= csrf_field() ?>
                            <input type="hidden" name="product_id" value="<?= e($item['id']) ?>">
                            <button data-testid="cart-page-remove-<?= e($item['id']) ?>" class="text-xs text-[var(--ink-3)] hover:text-[var(--ink)] inline-flex items-center gap-1"><?= icon('trash-2', 13) ?> Retirer</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
