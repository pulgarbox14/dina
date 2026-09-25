<?php
/**
 * @var array $product
 * @var string|null $aspect
 */
$aspect ??= 'aspect-[4/5]';
?>
<div data-tilt class="tilt-card relative group card-3d p-3" data-testid="product-card-<?= e($product['id']) ?>">
    <a href="<?= e(url('/produit/' . $product['id'])) ?>" class="block">
        <div class="relative overflow-hidden rounded-[22px] <?= e($aspect) ?> img-zoom bg-[var(--luster)]">
            <img src="<?= e($product['images'][0] ?? '') ?>" alt="<?= e($product['name']) ?>" loading="lazy" class="h-full w-full object-cover">
            <?php if (!empty($product['tag'])): ?>
                <span class="absolute top-4 left-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-semibold uppercase tracking-[0.2em] text-[var(--rose-deep)]"><?= e($product['tag']) ?></span>
            <?php endif; ?>
        </div>
        <div class="pt-5 pb-3 px-2 flex justify-between gap-4">
            <div>
                <h3 class="font-display text-xl leading-tight"><?= e($product['name']) ?></h3>
                <p class="text-sm text-[var(--ink-3)] mt-1"><?= e($product['subtitle']) ?></p>
            </div>
            <span class="font-mono text-sm mt-1 whitespace-nowrap" data-testid="product-price-<?= e($product['id']) ?>"><?= price((int) $product['price']) ?></span>
        </div>
    </a>
    <?php /* Hors du lien : un formulaire ne peut pas être imbriqué dans un <a>. */ ?>
    <form method="post" action="<?= e(url('/panier/ajouter')) ?>" data-cart-form class="absolute top-3 left-3 right-3 <?= e($aspect) ?> pointer-events-none">
        <?= csrf_field() ?>
        <input type="hidden" name="product_id" value="<?= e($product['id']) ?>">
        <input type="hidden" name="qty" value="1">
        <button data-testid="quick-add-<?= e($product['id']) ?>" aria-label="Ajouter <?= e($product['name']) ?> au panier"
                class="pointer-events-auto absolute bottom-4 right-4 h-11 w-11 rounded-full bg-[var(--ink)] text-[var(--pearl)] flex items-center justify-center translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 focus-visible:translate-y-0 focus-visible:opacity-100 transition-[transform,opacity] duration-500"><?= icon('plus', 18) ?></button>
    </form>
    <span class="tilt-shine"></span>
</div>
