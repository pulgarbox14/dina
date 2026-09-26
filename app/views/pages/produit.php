<?php
/**
 * @var array $product
 * @var list<array> $related
 * @var array{count: int, average: float, distribution: array<int, int>} $reviewStats
 * @var list<array> $reviews
 * @var int|null $reviewNote
 * @var bool $reviewsAll
 */
use App\ReviewRepository;
use App\View;

$p = $product;
$productUrl = url('/produit/' . $p['id']);
$rs = $reviewStats;
$visibleReviews = $reviewsAll ? $reviews : array_slice($reviews, 0, ReviewRepository::PREVIEW);
$evaluations = static fn (int $n): string => $n . ' évaluation' . ($n > 1 ? 's' : '');
$waTemplate = 'Bonjour, je souhaite commander « ' . $p['name'] . ' » (' . price((int) $p['price']) . ') x{qty}.';
$accordions = [
    ['care', 'Entretien des perles', "Essuyer avec un chiffon doux et sec. Éviter l'eau, les parfums et l'exposition prolongée au soleil. Ranger dans sa pochette en tissu."],
    ['shipping', 'Livraison & délais', "Cotonou : 24 à 48 h. Afrique de l'Ouest : 3 à 7 jours. International : 7 à 14 jours. Les pièces sur mesure demandent 2 à 3 semaines."],
    ['custom', 'Sur mesure', "Couleur des fleurs, dimensions, initiales : chaque pièce peut être adaptée. Écrivez-nous sur WhatsApp avec votre idée."],
];
?>
<div data-testid="product-page" class="pt-32" data-product-page>
    <div class="max-w-[1280px] mx-auto px-6 lg:px-12">
        <nav class="text-xs text-[var(--ink-3)] flex gap-2 mb-10" data-testid="breadcrumbs" aria-label="Fil d'Ariane">
            <a href="<?= e(url('/')) ?>" class="hover:text-[var(--ink)]">Accueil</a><span>/</span>
            <a href="<?= e(url('/boutique')) ?>" class="hover:text-[var(--ink)]">Boutique</a><span>/</span>
            <span class="text-[var(--ink)]"><?= e($p['name']) ?></span>
        </nav>
        <div class="grid lg:grid-cols-12 gap-12">
            <div data-reveal class="lg:col-span-6">
                <div class="aspect-[4/5] overflow-hidden rounded-[28px] bg-[var(--luster)]">
                    <img data-main-image data-testid="product-main-image" src="<?= e($p['images'][0] ?? '') ?>" alt="<?= e($p['name']) ?>" class="h-full w-full object-cover">
                </div>
                <?php if (count($p['images']) > 1): ?>
                    <div class="flex gap-3 mt-4" data-thumbs>
                        <?php foreach ($p['images'] as $i => $src): ?>
                            <button type="button" data-thumb="<?= e($src) ?>" data-testid="product-thumb-<?= $i ?>" aria-label="Photo <?= $i + 1 ?>" aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>"
                                    class="thumb h-24 w-20 overflow-hidden rounded-2xl border-2 <?= $i === 0 ? 'border-[var(--rose)]' : 'border-transparent' ?>">
                                <img src="<?= e($src) ?>" alt="" class="h-full w-full object-cover">
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div data-reveal style="--delay: .1s" class="lg:col-span-5 lg:col-start-8 lg:sticky lg:top-32 self-start">
                <?php if (!empty($p['tag'])): ?><span class="eyebrow"><?= e($p['tag']) ?></span><?php endif; ?>
                <h1 data-testid="product-name" class="font-display text-3xl sm:text-4xl lg:text-5xl leading-[1] tracking-tight mt-3"><?= e($p['name']) ?></h1>
                <?php if ($rs['count'] > 0): ?>
                    <?php /* Note façon grandes boutiques : « 4,7 ★★★★½ ˅ (12) ». Le chevron ouvre la répartition, le nombre mène aux avis. */ ?>
                    <div class="mt-3 flex items-center gap-2 text-sm" data-testid="product-rating">
                        <details class="rating-pop relative" data-rating-pop>
                            <summary class="flex items-center gap-1.5 cursor-pointer list-none rounded-md" aria-label="Note moyenne <?= e(rating_value($rs['average'])) ?> sur 5, voir la répartition">
                                <span class="font-semibold"><?= e(rating_value($rs['average'])) ?></span>
                                <?= rating_stars($rs['average'], 16) ?>
                                <span class="rating-chevron transition-transform duration-200 text-[var(--ink-2)]"><?= icon('chevron-down', 14) ?></span>
                            </summary>
                            <div class="absolute left-0 top-full mt-3 z-30 w-[300px] max-w-[calc(100vw-3rem)] rounded-2xl border border-[var(--line)] bg-white p-5 shadow-xl" data-testid="rating-popover">
                                <div class="flex items-center gap-2"><?= rating_stars($rs['average'], 18) ?> <span class="font-semibold"><?= e(rating_value($rs['average'])) ?> sur 5</span></div>
                                <div class="text-xs text-[var(--ink-3)] mt-1 mb-4"><?= $evaluations($rs['count']) ?></div>
                                <?= View::partial('partials/rating-breakdown', ['stats' => $rs]) ?>
                                <a href="#avis" data-rating-close class="mt-4 pt-4 border-t border-[var(--line)] flex items-center justify-between text-sm font-medium hover:text-[var(--rose-deep)]">Voir les avis clientes <?= icon('arrow-down', 14) ?></a>
                            </div>
                        </details>
                        <a href="#avis" data-testid="product-rating-count" class="text-[var(--rose-deep)] hover:underline" aria-label="Voir les <?= $evaluations($rs['count']) ?>">(<?= $rs['count'] ?>)</a>
                    </div>
                <?php endif; ?>
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
                   target="_blank" rel="noopener" data-testid="whatsapp-order-button" class="btn-pill btn-ghost w-full mt-4"><?= brand_icon('whatsapp', 16) ?> Commander sur WhatsApp</a>

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

        <?php /* ——— Avis clientes ——— */ ?>
        <section id="avis" data-testid="reviews-section" class="mt-24 pt-16 border-t border-[var(--line)] scroll-mt-28 grid lg:grid-cols-12 gap-10 lg:gap-16">
            <div class="lg:col-span-4">
                <h2 class="font-display text-3xl">Avis clientes</h2>
                <?php if ($rs['count'] > 0): ?>
                    <div class="flex items-center gap-3 mt-4" data-testid="reviews-average">
                        <?= rating_stars($rs['average'], 22) ?>
                        <span class="text-lg font-semibold"><?= e(rating_value($rs['average'])) ?> sur 5</span>
                    </div>
                    <div class="text-sm text-[var(--ink-3)] mt-2 mb-6"><?= $evaluations($rs['count']) ?></div>
                    <?= View::partial('partials/rating-breakdown', ['stats' => $rs, 'filterUrl' => $productUrl, 'active' => $reviewNote]) ?>
                <?php else: ?>
                    <p class="text-sm text-[var(--ink-2)] mt-4">Cette pièce n'a pas encore reçu d'avis.</p>
                <?php endif; ?>

                <div class="mt-8 rounded-2xl bg-warm p-5">
                    <div class="flex items-center gap-2 font-semibold text-sm"><?= icon('check', 16) ?> Uniquement des achats vérifiés</div>
                    <p class="text-xs text-[var(--ink-2)] mt-2 leading-relaxed">Seules les clientes qui ont commandé cette pièce peuvent la noter, une fois leur commande livrée. Chaque avis est relu avant publication.</p>
                </div>
            </div>

            <div class="lg:col-span-8">
                <?php if ($reviewNote !== null): ?>
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 rounded-2xl border border-[var(--line)] px-5 py-3 text-sm" data-testid="reviews-filter">
                        <span>Avis <?= $reviewNote ?> étoile<?= $reviewNote > 1 ? 's' : '' ?> · <?= count($reviews) ?></span>
                        <a href="<?= e($productUrl) ?>#avis" class="font-medium text-[var(--rose-deep)] hover:underline">Afficher tous les avis</a>
                    </div>
                <?php endif; ?>

                <?php if ($visibleReviews === []): ?>
                    <div class="rounded-[24px] border border-dashed border-[var(--line)] p-10 text-center" data-testid="reviews-empty">
                        <span class="inline-flex text-[var(--ink-3)]"><?= rating_stars(0, 22) ?></span>
                        <p class="font-display text-xl mt-4">Soyez la première à donner votre avis</p>
                        <p class="text-sm text-[var(--ink-2)] mt-2 max-w-sm mx-auto">Après la livraison de votre commande, vous pourrez noter cette pièce et partager votre expérience.</p>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-[var(--line)]" data-testid="reviews-list">
                        <?php foreach ($visibleReviews as $r): ?>
                            <article class="py-7 first:pt-0" data-testid="review-item">
                                <div class="flex items-center gap-3">
                                    <span class="h-9 w-9 rounded-full bg-warm flex items-center justify-center text-sm font-semibold text-[var(--rose-deep)]" aria-hidden="true"><?= e(mb_strtoupper(mb_substr($r['author_name'], 0, 1))) ?></span>
                                    <span class="text-sm font-semibold"><?= e($r['author_name']) ?></span>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-3">
                                    <?= rating_stars((float) $r['rating'], 15) ?>
                                    <?php if (!empty($r['title'])): ?><h3 class="font-semibold"><?= e($r['title']) ?></h3><?php endif; ?>
                                </div>
                                <div class="text-xs text-[var(--ink-3)] mt-1.5">
                                    <?= !empty($r['city']) ? e($r['city']) . ' · ' : '' ?>le <?= e(date_fr((string) $r['date'])) ?>
                                    <?php if ($r['verified']): ?>
                                        · <span class="font-semibold text-[var(--orange)]" data-testid="review-verified">Achat vérifié</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-[var(--ink-2)] leading-relaxed mt-3 whitespace-pre-line"><?= e($r['body']) ?></p>
                            </article>
                        <?php endforeach; ?>
                    </div>
                    <?php if (!$reviewsAll && count($reviews) > count($visibleReviews)): ?>
                        <a href="<?= e($productUrl . '?' . http_build_query(array_filter(['note' => $reviewNote, 'avis' => 'tous']))) ?>#avis" data-testid="reviews-more" class="btn-pill btn-ghost mt-6">Voir les <?= count($reviews) ?> avis <?= icon('chevron-down', 16) ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

        <section class="mt-24">
            <h2 class="font-display text-3xl mb-8">Vous aimerez aussi</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-8" data-testid="related-products">
                <?php foreach ($related as $r) echo View::partial('partials/product-card', ['product' => $r]); ?>
            </div>
        </section>
    </div>
</div>
