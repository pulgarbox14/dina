<?php
$reviews = [
    ['Fatou N.', 'Cotonou', "Mon sac Lune Nacre a fait sensation au mariage de ma sœur. On m'a demandé dix fois où je l'avais trouvé."],
    ['Mariam K.', 'Abidjan', "Le travail est incroyablement régulier. On sent les heures passées dessus. Livraison rapide jusqu'en Côte d'Ivoire."],
    ['Claire D.', 'Paris', "Une vraie pièce d'artisanat. Les fleurs orange sont encore plus belles qu'en photo."],
];
?>
<section data-testid="testimonials-section" class="max-w-[1440px] mx-auto px-6 lg:px-12 py-32">
    <?= App\View::partial('partials/section-heading', ['eyebrow' => 'Elles les portent', 'titleHtml' => "Ce qu'elles en disent", 'align' => 'center']) ?>
    <div class="grid md:grid-cols-3 gap-6 mt-16">
        <?php foreach ($reviews as $i => [$name, $city, $text]): ?>
            <div data-reveal style="--delay: <?= $i * 0.1 ?>s">
                <div data-tilt class="tilt-card relative card-3d p-9 flex flex-col justify-between min-h-[280px]">
                    <p class="font-display text-xl leading-snug font-normal">« <?= e($text) ?> »</p>
                    <div class="mt-8 flex items-center gap-3">
                        <span class="pearl-dot <?= $i % 2 ? 'pearl-dot-rose' : 'pearl-dot-warm' ?> h-9 w-9 rounded-full"></span>
                        <div>
                            <div class="text-sm font-medium"><?= e($name) ?></div>
                            <div class="text-xs text-[var(--ink-3)]"><?= e($city) ?> · Achat vérifié</div>
                        </div>
                    </div>
                    <span class="tilt-shine"></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
