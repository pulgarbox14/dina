<?php
/**
 * @var array $product
 * @var list<array> $related
 */
use App\View;

$p = $product;
$model = pearl_model($p);
$waTemplate = 'Bonjour, je souhaite commander « ' . $p['name'] . ' » (' . price((int) $p['price']) . ') x{qty}.';
$accordions = [
    ['care', 'Entretien des perles', "Essuyer avec un chiffon doux et sec. Éviter l'eau, les parfums et l'exposition prolongée au soleil. Ranger dans sa pochette en tissu."],
    ['shipping', 'Livraison & délais', "Cotonou : 24 à 48 h. Afrique de l'Ouest : 3 à 7 jours. International : 7 à 14 jours. Les pièces sur mesure demandent 2 à 3 semaines."],
    ['custom', 'Sur mesure', "Couleur des fleurs, dimensions, initiales : chaque pièce peut être adaptée. Écrivez-nous sur WhatsApp avec votre idée."],
];
?>
<div data-testid="product-page" class="pt-32" data-product-page>
    <div class="max-w-[1440px] mx-auto px-6 lg:px-12">
        <nav class="text-xs text-[var(--ink-3)] flex gap-2 mb-10" data-testid="breadcrumbs" aria-label="Fil d'Ariane">
            <a href="<?= e(url('/')) ?>" class="hover:text-[var(--ink)]">Accueil</a><span>/</span>
            <a href="<?= e(url('/boutique')) ?>" class="hover:text-[var(--ink)]">Boutique</a><span>/</span>
            <span class="text-[var(--ink)]"><?= e($p['name']) ?></span>
        </nav>
        <div class="grid lg:grid-cols-12 gap-12">
            <div data-reveal class="lg:col-span-7">
                <div class="relative">
                    <div data-view-panel="photo" class="aspect-[4/5] overflow-hidden rounded-[28px] bg-[var(--luster)]">
                        <img data-main-image data-testid="product-main-image" src="<?= e($p['images'][0] ?? '') ?>" alt="<?= e($p['name']) ?>" class="h-full w-full object-cover">
                    </div>
                    <div data-view-panel="3d" data-testid="product-3d-viewer" class="hidden relative aspect-[4/5] overflow-hidden rounded-[28px] bg-[radial-gradient(ellipse_at_50%_40%,#ffffff_0%,#fff1ea_65%,#fde2ee_100%)]">
                        <div id="product-scene" class="absolute inset-0" data-pearl-scene data-shape="<?= e($model['shape']) ?>" data-accent="<?= e($model['accent'] ?? '') ?>"
                             data-camera="<?= $model['shape'] === 'necklace' ? '7.6' : '7' ?>" data-testid="product-3d-canvas"></div>
                        <div class="absolute top-5 left-5 glass rounded-full px-4 h-9 flex items-center gap-2 text-xs font-medium"><?= icon('rotate-ccw', 13) ?> Glissez pour faire tourner</div>
                        <div class="absolute bottom-5 left-5 right-5 flex items-center justify-between gap-3">
                            <span class="text-[10px] uppercase tracking-[0.22em] text-[var(--ink-3)]">Aperçu 3D · rendu fidèle au tissage</span>
                            <div class="flex gap-2" data-testid="viewer-accent-options" data-pearl-group="accent">
                                <?php foreach ([['Blanc', ''], ['Orange', '#f97316'], ['Violet', '#a855f7'], ['Ambre', '#f59e0b'], ['Bleu', '#60a5fa']] as [$l, $c]): ?>
                                    <button type="button" data-pearl-target="product-scene" data-accent="<?= $c ?>" data-testid="viewer-accent-<?= strtolower($l) ?>" title="<?= $l ?>" aria-label="Accent <?= $l ?>"
                                            aria-pressed="<?= ($model['accent'] ?? '') === $c ? 'true' : 'false' ?>"
                                            class="pearl-swatch h-7 w-7 rounded-full border-2 border-white transition-transform duration-300 hover:scale-110"
                                            style="background: <?= $c ?: 'linear-gradient(135deg,#fff,#e6e2da)' ?>"></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-5 right-5 glass rounded-full p-1 flex gap-1" data-testid="product-view-toggle">
                        <button type="button" data-view-toggle="photo" data-testid="view-mode-photo" aria-pressed="true" class="view-toggle h-9 px-4 rounded-full text-xs font-medium inline-flex items-center gap-2 transition-colors"><?= icon('image', 13) ?> Photo</button>
                        <button type="button" data-view-toggle="3d" data-testid="view-mode-3d" aria-pressed="false" class="view-toggle h-9 px-4 rounded-full text-xs font-medium inline-flex items-center gap-2 transition-colors"><?= icon('box', 13) ?> Vue 3D</button>
                    </div>
                </div>
                <?php if (count($p['images']) > 1): ?>
                    <div class="flex gap-3 mt-4" data-view-panel="photo" data-thumbs>
                        <?php foreach ($p['images'] as $i => $src): ?>
                            <button type="button" data-thumb="<?= e($src) ?>" data-testid="product-thumb-<?= $i ?>" aria-label="Photo <?= $i + 1 ?>" aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>"
                                    class="thumb h-24 w-20 overflow-hidden border <?= $i === 0 ? 'border-[var(--ink)]' : 'border-transparent' ?>">
                                <img src="<?= e($src) ?>" alt="" class="h-full w-full object-cover">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div data-reveal style="--delay: .1s" class="lg:col-span-5 lg:sticky lg:top-32 self-start">
                <?php if (!empty($p['tag'])): ?><span class="eyebrow"><?= e($p['tag']) ?></span><?php endif; ?>
                <h1 data-testid="product-name" class="font-display text-5xl lg:text-6xl leading-[1] tracking-tight mt-3"><?= e($p['name']) ?></h1>
                <p class="text-[var(--ink-2)] mt-3"><?= e($p['subtitle']) ?></p>
                <div data-testid="product-price" class="font-mono text-2xl mt-8"><?= price((int) $p['price']) ?></div>
                <p class="text-base text-[var(--ink-2)] leading-relaxed mt-8"><?= e($p['description']) ?></p>

                <dl class="grid grid-cols-3 gap-4 mt-10 text-sm border-y border-[var(--line)] py-6">
                    <div><dt class="eyebrow">Dimensions</dt><dd class="mt-2"><?= e($p['dimensions']) ?></dd></div>
                    <div><dt class="eyebrow">Tissage</dt><dd class="mt-2"><?= (int) $p['weaving_hours'] ?> h de travail</dd></div>
                    <div><dt class="eyebrow">Accent</dt><dd class="mt-2"><?= e($p['accent']) ?></dd></div>
                </dl>

                <form method="post" action="<?= e(url('/panier/ajouter')) ?>" data-cart-form data-qty-form class="flex flex-wrap items-center gap-4 mt-10">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= e($p['id']) ?>">
                    <input type="hidden" name="qty" value="1" data-qty-input>
                    <div class="inline-flex items-center border border-[var(--line)] rounded-full h-12">
                        <button type="button" data-qty-step="-1" data-testid="qty-minus-button" aria-label="Diminuer la quantité" class="h-12 w-12 flex items-center justify-center"><?= icon('minus', 14) ?></button>
                        <span data-qty-value data-testid="qty-value" class="w-8 text-center" aria-live="polite">1</span>
                        <button type="button" data-qty-step="1" data-testid="qty-plus-button" aria-label="Augmenter la quantité" class="h-12 w-12 flex items-center justify-center"><?= icon('plus', 14) ?></button>
                    </div>
                    <button data-testid="add-to-cart-button" class="btn-pill btn-dark flex-1 min-w-[200px]"><?= icon('shopping-bag', 16) ?> Ajouter au panier</button>
                </form>
                <a href="<?= e(whatsapp_link(str_replace('{qty}', '1', $waTemplate))) ?>" data-wa-template="<?= e($waTemplate) ?>" data-wa-phone="<?= e(brand('whatsapp')) ?>"
                   target="_blank" rel="noopener" data-testid="whatsapp-order-button" class="btn-pill btn-ghost w-full mt-4"><?= icon('message-circle', 16) ?> Commander sur WhatsApp</a>

                <div class="mt-10 divide-y divide-[var(--line)] border-b border-[var(--line)]">
                    <?php foreach ($accordions as [$id, $label, $body]): ?>
                        <details class="accordion group" name="product-info">
                            <summary data-testid="accordion-<?= $id ?>" class="flex items-center justify-between py-4 text-sm font-medium cursor-pointer list-none hover:underline">
                                <?= e($label) ?>
                                <span class="transition-transform duration-200 group-open:rotate-180"><?= icon('chevron-down', 16) ?></span>
                            </summary>
                            <div class="pb-4 text-sm"><?= e($body) ?></div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <section class="mt-32">
            <h2 class="font-display text-4xl mb-10">Vous aimerez aussi</h2>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8" data-testid="related-products">
                <?php foreach ($related as $r) echo View::partial('partials/product-card', ['product' => $r]); ?>
            </div>
        </section>
    </div>
</div>
