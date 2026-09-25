<?php
/**
 * @var string $category
 * @var list<array> $products
 */
use App\ProductRepository;
use App\View;

ob_start(); ?>
<nav class="flex flex-wrap gap-3 mt-12" data-testid="category-filter" aria-label="Catégories">
    <?php foreach (ProductRepository::CATEGORIES as $id => $label): ?>
        <a href="<?= e(url($id === 'tous' ? '/boutique' : '/boutique?categorie=' . $id)) ?>" data-testid="category-filter-<?= $id ?>"
           <?= $category === $id ? 'aria-current="page"' : '' ?>
           class="inline-flex items-center rounded-full px-5 h-10 text-sm border transition-colors duration-300 <?= $category === $id ? 'bg-[var(--ink)] text-[var(--pearl)] border-[var(--ink)]' : 'border-[var(--line)] hover:border-[var(--ink)]' ?>"><?= e($label) ?></a>
    <?php endforeach; ?>
</nav>
<?php $filters = ob_get_clean(); ?>
<div data-testid="boutique-page">
    <?= View::partial('partials/page-hero', [
        'eyebrow'   => 'Boutique',
        'titleHtml' => 'Pièces uniques, tissées <span class="text-gradient">pour vous.</span>',
        'text'      => 'Chaque création est fabriquée à la main dans notre atelier. Les quantités sont volontairement limitées.',
        'extra'     => $filters,
    ]) ?>
    <section class="max-w-[1440px] mx-auto px-6 lg:px-12 pb-24">
        <div class="flex justify-between text-xs text-[var(--ink-3)] uppercase tracking-[0.2em] mb-8 border-b border-[var(--line)] pb-4">
            <span data-testid="products-count"><?= count($products) ?> création<?= count($products) > 1 ? 's' : '' ?></span>
            <span>Prix en FCFA</span>
        </div>
        <?php if (ProductRepository::unavailable()) echo View::partial('partials/catalog-unavailable'); ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14" data-testid="products-grid">
            <?php foreach ($products as $i => $p): ?>
                <div data-reveal style="--delay: <?= ($i % 3) * 0.08 ?>s">
                    <?= View::partial('partials/product-card', ['product' => $p]) ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?= View::partial('partials/marquee', ['dark' => true]) ?>
</div>
