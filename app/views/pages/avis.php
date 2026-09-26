<?php
/**
 * Tous les avis d'une seule pièce (/produit/{id}/avis).
 *
 * @var array $product
 * @var array{count: int, average: float, distribution: array<int, int>} $reviewStats
 * @var list<array> $reviews
 * @var int|null $reviewNote
 */
use App\View;

$p = $product;
$rs = $reviewStats;
$productUrl = url('/produit/' . $p['id']);
$reviewsUrl = $productUrl . '/avis';
$evaluations = static fn (int $n): string => $n . ' évaluation' . ($n > 1 ? 's' : '');
?>
<div data-testid="reviews-page" class="pt-32 pb-24">
    <div class="max-w-[1280px] mx-auto px-6 lg:px-12">
        <nav class="text-xs text-[var(--ink-3)] flex flex-wrap gap-2 mb-10" data-testid="breadcrumbs" aria-label="Fil d'Ariane">
            <a href="<?= e(url('/')) ?>" class="hover:text-[var(--ink)]">Accueil</a><span>/</span>
            <a href="<?= e(url('/boutique')) ?>" class="hover:text-[var(--ink)]">Boutique</a><span>/</span>
            <a href="<?= e($productUrl) ?>" class="hover:text-[var(--ink)]"><?= e($p['name']) ?></a><span>/</span>
            <span class="text-[var(--ink)]">Avis</span>
        </nav>

        <?php /* Rappel de la pièce notée. */ ?>
        <a href="<?= e($productUrl) ?>" data-testid="reviews-product" class="group flex items-center gap-4 sm:gap-6 rounded-[24px] border border-[var(--line)] p-3 pr-5 sm:pr-8 hover:shadow-md transition-shadow max-w-2xl">
            <img src="<?= e($p['images'][0] ?? '') ?>" alt="" class="h-20 w-16 sm:h-24 sm:w-20 rounded-2xl object-cover bg-[var(--luster)] shrink-0">
            <span class="flex-1 min-w-0">
                <span class="block text-[10px] uppercase tracking-[0.2em] font-semibold text-[var(--rose-deep)]">Avis sur</span>
                <span class="block font-display text-xl sm:text-2xl leading-tight mt-1 truncate"><?= e($p['name']) ?></span>
                <span class="block font-mono text-sm mt-1"><?= price((int) $p['price']) ?></span>
            </span>
            <span class="hidden sm:inline-flex items-center gap-2 text-sm font-medium group-hover:text-[var(--rose-deep)]">Voir la pièce <?= icon('arrow-up-right', 16) ?></span>
        </a>

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 mt-14">
            <aside class="lg:col-span-4">
                <h1 class="font-display text-3xl sm:text-4xl">Avis clientes</h1>
                <?php if ($rs['count'] > 0): ?>
                    <div class="flex items-center gap-3 mt-4" data-testid="reviews-average">
                        <?= rating_stars($rs['average'], 22) ?>
                        <span class="text-lg font-semibold"><?= e(rating_value($rs['average'])) ?> sur 5</span>
                    </div>
                    <div class="text-sm text-[var(--ink-3)] mt-2 mb-6"><?= $evaluations($rs['count']) ?></div>
                    <?= View::partial('partials/rating-breakdown', ['stats' => $rs, 'filterUrl' => $reviewsUrl, 'active' => $reviewNote]) ?>
                <?php else: ?>
                    <p class="text-sm text-[var(--ink-2)] mt-4">Cette pièce n'a pas encore reçu d'avis.</p>
                <?php endif; ?>

                <div class="mt-8 rounded-2xl bg-warm p-5">
                    <div class="flex items-center gap-2 font-semibold text-sm"><?= icon('check', 16) ?> Uniquement des achats vérifiés</div>
                    <p class="text-xs text-[var(--ink-2)] mt-2 leading-relaxed">Seules les clientes qui ont commandé cette pièce peuvent la noter, une fois leur commande livrée. Chaque avis est relu avant publication.</p>
                </div>
            </aside>

            <div class="lg:col-span-8">
                <?php if ($reviewNote !== null): ?>
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-6 rounded-2xl border border-[var(--line)] px-5 py-3 text-sm" data-testid="reviews-filter">
                        <span>Avis <?= $reviewNote ?> étoile<?= $reviewNote > 1 ? 's' : '' ?> · <?= count($reviews) ?></span>
                        <a href="<?= e($reviewsUrl) ?>" class="font-medium text-[var(--rose-deep)] hover:underline">Afficher tous les avis</a>
                    </div>
                <?php endif; ?>

                <?php if ($reviews === []): ?>
                    <div class="rounded-[24px] border border-dashed border-[var(--line)] p-10 text-center" data-testid="reviews-empty">
                        <?= rating_stars(0, 22) ?>
                        <p class="font-display text-xl mt-4">Aucun avis pour l'instant</p>
                        <p class="text-sm text-[var(--ink-2)] mt-2 max-w-sm mx-auto">Vous avez commandé cette pièce ? Vous pourrez la noter dès que votre commande sera livrée.</p>
                        <a href="<?= e($productUrl) ?>" class="btn-pill btn-ghost mt-6">Retour à la pièce</a>
                    </div>
                <?php else: ?>
                    <div class="divide-y divide-[var(--line)]" data-testid="reviews-list">
                        <?php foreach ($reviews as $r): ?>
                            <article class="py-7 first:pt-0" data-testid="review-item">
                                <div class="flex items-center gap-3">
                                    <span class="h-9 w-9 rounded-full bg-warm flex items-center justify-center text-sm font-semibold text-[var(--rose-deep)]" aria-hidden="true"><?= e(mb_strtoupper(mb_substr($r['author_name'], 0, 1))) ?></span>
                                    <span class="text-sm font-semibold"><?= e($r['author_name']) ?></span>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-3">
                                    <?= rating_stars((float) $r['rating'], 15) ?>
                                    <?php if (!empty($r['title'])): ?><h2 class="font-semibold"><?= e($r['title']) ?></h2><?php endif; ?>
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
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
