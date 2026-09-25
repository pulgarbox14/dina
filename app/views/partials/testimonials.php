<?php
/**
 * Avis clientes en bande qui défile en continu (pause au survol).
 * La liste est répétée pour remplir les grands écrans, puis dupliquée pour une boucle sans à-coup :
 * seule la première copie est lue par les lecteurs d'écran.
 * Un clic sur une carte ouvre une fenêtre avec l'avis complet et la pièce achetée (app.js → initReviews).
 */
use App\ProductRepository;

// [nom, ville, avis, identifiant de la pièce citée dans l'avis (null si aucune)]
$reviews = [
    ['Fatou N.', 'Cotonou', "Mon sac Lune Nacre a fait sensation au mariage de ma sœur. On m'a demandé dix fois où je l'avais trouvé.", 'sac-lune-nacre'],
    ['Mariam K.', 'Abidjan', "Le travail est incroyablement régulier. On sent les heures passées dessus. Livraison rapide jusqu'en Côte d'Ivoire.", null],
    ['Claire D.', 'Paris', "Une vraie pièce d'artisanat. Les fleurs orange sont encore plus belles qu'en photo.", 'cabas-fleur-de-soleil'],
];
$products = ProductRepository::findMany(array_values(array_filter(array_column($reviews, 3))));
$details = array_map(static function (array $r) use ($products): array {
    $p = $r[3] !== null ? ($products[$r[3]] ?? null) : null;
    return [
        'name'    => $r[0],
        'city'    => $r[1],
        'text'    => $r[2],
        'product' => $p === null ? null : [
            'name'  => $p['name'],
            'price' => price((int) $p['price']),
            'image' => $p['images'][0] ?? '',
            'url'   => url('/produit/' . $p['id']),
        ],
    ];
}, $reviews);
$band = array_merge($reviews, $reviews);

$stars = static function (int $size = 16): string {
    return str_repeat('<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.5l2.9 6 6.6.9-4.8 4.6 1.2 6.5L12 17.4l-5.9 3.1 1.2-6.5L2.5 9.4l6.6-.9z"/></svg>', 5);
};

$card = static function (array $review, int $i, bool $focusable) use ($stars, $reviews): string {
    [$name, $city, $text] = $review;
    $index = $i % count($reviews);
    ob_start(); ?>
    <button type="button" data-review="<?= $index ?>" <?= $focusable ? '' : 'tabindex="-1"' ?> aria-label="Lire l'avis de <?= e($name) ?>"
            class="review-card card-3d p-7 w-[280px] sm:w-[360px] shrink-0 flex flex-col justify-between min-h-[230px] text-left cursor-pointer">
        <span class="block">
            <span class="flex gap-1 text-[var(--orange)]" aria-label="5 étoiles sur 5"><?= $stars() ?></span>
            <span class="block font-display text-base sm:text-lg leading-snug font-normal mt-5">« <?= e($text) ?> »</span>
        </span>
        <span class="mt-8 flex items-center justify-between gap-3">
            <span class="flex items-center gap-3">
                <span class="pearl-dot <?= $i % 2 ? 'pearl-dot-rose' : 'pearl-dot-warm' ?> h-9 w-9 rounded-full"></span>
                <span>
                    <span class="block text-sm font-semibold"><?= e($name) ?></span>
                    <span class="block text-xs text-[var(--ink-3)]"><?= e($city) ?> · Achat vérifié</span>
                </span>
            </span>
            <span class="review-more h-8 w-8 rounded-full flex items-center justify-center bg-[var(--bg-elevated)] text-[var(--ink-2)] transition-colors"><?= icon('plus', 14) ?></span>
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
                <span class="flex gap-1 text-[var(--orange)]" aria-label="5 étoiles sur 5"><?= $stars(18) ?></span>
                <button type="button" data-review-close aria-label="Fermer" class="h-10 w-10 -mt-2 -mr-2 rounded-full border border-[var(--line)] flex items-center justify-center hover:bg-[var(--bg-elevated)]"><?= icon('x', 16) ?></button>
            </div>
            <p class="font-display text-xl sm:text-2xl leading-snug mt-5" data-review-text></p>
            <div class="mt-6 flex items-center gap-3">
                <span class="pearl-dot pearl-dot-rose h-11 w-11 rounded-full"></span>
                <div>
                    <div class="font-semibold" id="review-dialog-name" data-review-name></div>
                    <div class="text-xs text-[var(--ink-3)]"><span data-review-city></span> · Achat vérifié</div>
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
