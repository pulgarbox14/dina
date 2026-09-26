<?php
/**
 * @var array $product
 * @var string|null $aspect
 */
$aspect ??= 'aspect-[4/5]';
$rating = App\ReviewRepository::summary((string) $product['id']);
?>
<div data-tilt class="tilt-card relative group card-3d h-full p-2 sm:p-3 max-sm:!rounded-[20px]" data-testid="product-card-<?= e($product['id']) ?>">
    <a href="<?= e(url('/produit/' . $product['id'])) ?>" class="block">
        <div class="relative overflow-hidden rounded-[14px] sm:rounded-[22px] <?= e($aspect) ?> img-zoom bg-[var(--luster)]">
            <img src="<?= e($product['images'][0] ?? '') ?>" alt="<?= e($product['name']) ?>" loading="lazy" class="h-full w-full object-cover">
            <?php if (!empty($product['tag'])): ?>
                <span class="absolute top-2 left-2 sm:top-4 sm:left-4 bg-white/90 backdrop-blur px-2 sm:px-3 py-0.5 sm:py-1 rounded-full text-[8px] sm:text-[10px] font-semibold uppercase tracking-[0.16em] sm:tracking-[0.2em] text-[var(--rose-deep)]"><?= e($product['tag']) ?></span>
            <?php endif; ?>
        </div>
        <div class="pt-3 sm:pt-5 pb-1 sm:pb-3 px-1 sm:px-2 flex flex-col sm:flex-row sm:justify-between gap-1 sm:gap-4">
            <div>
                <h3 class="font-display text-sm sm:text-xl leading-tight"><?= e($product['name']) ?></h3>
                <p class="hidden sm:block text-sm text-[var(--ink-3)] mt-1"><?= e($product['subtitle']) ?></p>
                <?php if ($rating['count'] > 0): ?>
                    <span class="flex items-center gap-1.5 mt-1.5 text-xs text-[var(--ink-2)]" data-testid="card-rating-<?= e($product['id']) ?>">
                        <?= rating_stars($rating['average'], 12) ?>
                        <span class="font-semibold"><?= e(rating_value($rating['average'])) ?></span>
                        <span class="text-[var(--ink-3)]">(<?= $rating['count'] ?>)</span>
                    </span>
                <?php endif; ?>
            </div>
            <span class="font-mono text-xs sm:text-sm sm:mt-1 whitespace-nowrap" data-testid="product-price-<?= e($product['id']) ?>"><?= price((int) $product['price']) ?></span>
        </div>
    </a>
    <?php /* Hors du lien : un formulaire ne peut pas être imbriqué dans un <a>. */ ?>
    <form method="post" action="<?= e(url('/panier/ajouter')) ?>" data-cart-form class="absolute top-2 left-2 right-2 sm:top-3 sm:left-3 sm:right-3 <?= e($aspect) ?> pointer-events-none">
        <?= csrf_field() ?>
        <input type="hidden" name="product_id" value="<?= e($product['id']) ?>">
        <input type="hidden" name="qty" value="1">
        <button data-testid="quick-add-<?= e($product['id']) ?>" aria-label="Ajouter <?= e($product['name']) ?> au panier"
                class="pointer-events-auto absolute bottom-2 right-2 sm:bottom-4 sm:right-4 h-9 w-9 sm:h-11 sm:w-11 rounded-full bg-[var(--ink)] text-[var(--pearl)] flex items-center justify-center max-sm:opacity-100 sm:translate-y-3 sm:opacity-0 group-hover:translate-y-0 group-hover:opacity-100 focus-visible:translate-y-0 focus-visible:opacity-100 transition-[transform,opacity] duration-500"><?= icon('plus', 18) ?></button>
    </form>
    <span class="tilt-shine"></span>
</div>
