<?php
/**
 * Avis clientes en bande qui défile en continu (pause au survol).
 * La liste est répétée pour remplir les grands écrans, puis dupliquée pour une boucle sans à-coup :
 * seule la première copie est lue par les lecteurs d'écran.
 */
$reviews = [
    ['Fatou N.', 'Cotonou', "Mon sac Lune Nacre a fait sensation au mariage de ma sœur. On m'a demandé dix fois où je l'avais trouvé."],
    ['Mariam K.', 'Abidjan', "Le travail est incroyablement régulier. On sent les heures passées dessus. Livraison rapide jusqu'en Côte d'Ivoire."],
    ['Claire D.', 'Paris', "Une vraie pièce d'artisanat. Les fleurs orange sont encore plus belles qu'en photo."],
];
$band = array_merge($reviews, $reviews);

$card = static function (array $review, int $i): string {
    [$name, $city, $text] = $review;
    ob_start(); ?>
    <figure class="card-3d p-7 w-[280px] sm:w-[360px] shrink-0 flex flex-col justify-between min-h-[230px]">
        <div>
            <div class="flex gap-1 text-[var(--orange)]" aria-label="5 étoiles sur 5">
                <?php for ($s = 0; $s < 5; $s++): ?><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.5l2.9 6 6.6.9-4.8 4.6 1.2 6.5L12 17.4l-5.9 3.1 1.2-6.5L2.5 9.4l6.6-.9z"/></svg><?php endfor; ?>
            </div>
            <blockquote class="font-display text-base sm:text-lg leading-snug font-normal mt-5">« <?= e($text) ?> »</blockquote>
        </div>
        <figcaption class="mt-8 flex items-center gap-3">
            <span class="pearl-dot <?= $i % 2 ? 'pearl-dot-rose' : 'pearl-dot-warm' ?> h-9 w-9 rounded-full"></span>
            <span>
                <span class="block text-sm font-semibold"><?= e($name) ?></span>
                <span class="block text-xs text-[var(--ink-3)]"><?= e($city) ?> · Achat vérifié</span>
            </span>
        </figcaption>
    </figure>
    <?php return (string) ob_get_clean();
};
?>
<section data-testid="testimonials-section" class="py-20 overflow-hidden">
    <div class="max-w-[1280px] mx-auto px-6 lg:px-12">
        <?= App\View::partial('partials/section-heading', ['eyebrow' => 'Elles les portent', 'titleHtml' => 'Ce qu\'elles en <span class="text-gradient">disent</span>', 'align' => 'center']) ?>
    </div>
    <div class="reviews-band mt-12" data-testid="reviews-band">
        <div class="reviews-track flex w-max gap-6 py-6">
            <div class="flex gap-6 shrink-0">
                <?php foreach ($band as $i => $r) echo $card($r, $i); ?>
            </div>
            <div class="flex gap-6 shrink-0" aria-hidden="true">
                <?php foreach ($band as $i => $r) echo $card($r, $i); ?>
            </div>
        </div>
    </div>
</section>
