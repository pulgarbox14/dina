<?php
/**
 * Avis clientes en bande qui défile en continu (pause au survol), lus dans la base (avis publiés).
 * Une courte liste est répétée pour remplir les grands écrans, puis la bande est dupliquée pour une boucle sans à-coup :
 * seule la première copie est lue par les lecteurs d'écran.
 * Un clic sur une carte ouvre une fenêtre avec l'avis complet et la pièce achetée (app.js → initReviews).
 * Sans avis publié, la section n'est pas affichée.
 */
use App\ProductRepository;
use App\ReviewRepository;

$latest = ReviewRepository::latest();
if ($latest === []) {
    return;
}
// [nom, ville, avis, note, achat vérifié]
$reviews = array_map(static fn (array $r): array => [$r['author_name'], (string) $r['city'], $r['body'], $r['rating'], $r['verified']], $latest);
$products = ProductRepository::findMany(array_values(array_filter(array_column($latest, 'product_id'))));
$details = array_map(static function (array $r) use ($products): array {
    $p = $r['product_id'] !== null ? ($products[$r['product_id']] ?? null) : null;
    return [
        'name'     => $r['author_name'],
        'city'     => (string) $r['city'],
        'text'     => $r['body'],
        'rating'   => $r['rating'],
        'verified' => $r['verified'],
        'product'  => $p === null ? null : [
            'name'  => $p['name'],
            'price' => price((int) $p['price']),
            'image' => $p['images'][0] ?? '',
            'url'   => url('/produit/' . $p['id']),
        ],
    ];
}, $latest);
$band = [];
while (count($band) < 6) {
    $band = array_merge($band, $reviews);
}

$card = static function (array $review, int $i, bool $focusable) use ($reviews): string {
    [$name, $city, $text, $rating, $verified] = $review;
    $meta = implode(' · ', array_filter([$city, $verified ? 'Achat vérifié' : '']));
    $index = $i % count($reviews);
    ob_start(); ?>
    <button type="button" data-review="<?= $index ?>" <?= $focusable ? '' : 'tabindex="-1"' ?> aria-label="Lire l'avis de <?= e($name) ?>"
            class="review-card card-3d p-7 w-[280px] sm:w-[360px] shrink-0 flex flex-col justify-between min-h-[230px] text-left cursor-pointer">
        <span class="block">
            <?= rating_stars((float) $rating, 16) ?>
            <span class="block font-display text-base sm:text-lg leading-snug font-normal mt-5">« <?= e($text) ?> »</span>
        </span>
        <span class="mt-8 flex items-center gap-3">
            <span class="pearl-dot <?= $i % 2 ? 'pearl-dot-rose' : 'pearl-dot-warm' ?> h-9 w-9 rounded-full"></span>
            <span>
                <span class="block text-sm font-semibold"><?= e($name) ?></span>
                <?php if ($meta !== ''): ?><span class="block text-xs text-[var(--ink-3)]"><?= e($meta) ?></span><?php endif; ?>
            </span>
        </span>
    </button>
    <?php return (string) ob_get_clean();
};
?>
<section data-testid="testimonials-section" class="py-20 overflow-hidden" data-reviews>
    <script type="application/json" data-reviews-data><?= json_encode($details, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>
    <div class="max-w-[1280px] mx-auto px-6 lg:px-12">
        <?= App\View::partial('partials/section-heading', ['eyebrow' => 'Elles les portent', 'titleHtml' => 'Ce qu\'elles en <span class="text-gradient">disent</span>', 'align' => 'center']) ?>
    </div>
    <div class="reviews-band mt-12" data-testid="reviews-band">
        <div class="reviews-track flex w-max gap-6 py-6">
            <div class="flex gap-6 shrink-0">
                <?php foreach ($band as $i => $r) echo $card($r, $i, $i < count($reviews)); ?>
            </div>
            <div class="flex gap-6 shrink-0" aria-hidden="true">
                <?php foreach ($band as $i => $r) echo $card($r, $i, false); ?>
            </div>
        </div>
    </div>

    <?php /* Fenêtre de détail d'un avis (élément <dialog> natif : Échap, focus et fond gérés par le navigateur). */ ?>
    <dialog data-review-dialog data-testid="review-dialog" class="review-dialog rounded-[28px] p-0 w-[min(92vw,520px)] bg-white text-[var(--ink)] shadow-2xl" aria-labelledby="review-dialog-name">
        <div class="p-7 sm:p-9">
            <div class="flex items-start justify-between gap-4">
                <span data-review-stars><?= rating_stars(5, 18) ?></span>
                <button type="button" data-review-close aria-label="Fermer" class="h-10 w-10 -mt-2 -mr-2 rounded-full border border-[var(--line)] flex items-center justify-center hover:bg-[var(--bg-elevated)]"><?= icon('x', 16) ?></button>
            </div>
            <p class="font-display text-xl sm:text-2xl leading-snug mt-5" data-review-text></p>
            <div class="mt-6 flex items-center gap-3">
                <span class="pearl-dot pearl-dot-rose h-11 w-11 rounded-full"></span>
                <div>
                    <div class="font-semibold" id="review-dialog-name" data-review-name></div>
                    <div class="text-xs text-[var(--ink-3)]" data-review-meta></div>
                </div>
            </div>
            <a data-review-product href="#" class="mt-7 flex items-center gap-4 rounded-2xl bg-warm p-3 pr-5 hover:shadow-md transition-shadow">
                <img data-review-product-image src="" alt="" class="h-20 w-16 rounded-xl object-cover bg-[var(--luster)]">
                <span class="flex-1 min-w-0">
                    <span class="block text-[10px] uppercase tracking-[0.2em] font-semibold text-[var(--rose-deep)]">Pièce achetée</span>
                    <span class="block font-display text-lg leading-tight mt-1 truncate" data-review-product-name></span>
                    <span class="block font-mono text-sm mt-1" data-review-product-price></span>
                </span>
                <?= icon('arrow-up-right', 18) ?>
            </a>
            <div class="mt-7 flex flex-wrap gap-3">
                <a data-review-cta href="<?= e(url('/boutique')) ?>" class="btn-pill btn-dark">Voir la boutique <?= icon('arrow-up-right', 16) ?></a>
                <button type="button" data-review-close class="btn-pill btn-ghost">Fermer</button>
            </div>
        </div>
    </dialog>
</section>
