<?php
use App\View;

$pillars = [
    ['Matières', 'Perles nacrées haute résistance, fil de tissage renforcé, anses doublées. Des matières choisies pour tenir des années, pas une saison.'],
    ['Production lente', 'Nous ne produisons pas en stock. Chaque pièce est tissée à la commande ou en très petite série, sans gaspillage.'],
    ['Impact local', "L'atelier est basé à Cotonou et forme de jeunes femmes au tissage. Acheter un sac, c'est financer une formation."],
];
?>
<div data-testid="a-propos-page">
    <?= View::partial('partials/page-hero', [
        'eyebrow'   => 'À propos',
        'titleHtml' => 'Une maison de <em class="not-italic text-gradient">haute perlerie</em> née au Bénin.',
        'text'      => "Dina Perles est une marque d'accessoires faits main : sacs, pochettes et parures en perles nacrées, tissés un par un dans notre atelier.",
    ]) ?>

    <section class="max-w-[1280px] mx-auto px-6 lg:px-12 grid md:grid-cols-3 gap-4">
        <?php foreach (['lune', 'amber', 'orangeTote'] as $i => $g): ?>
            <div data-reveal style="--delay: <?= $i * 0.1 ?>s" class="img-zoom overflow-hidden rounded-[28px] bg-[var(--luster)] <?= $i === 1 ? 'md:mt-16' : '' ?> aspect-[3/4]">
                <img src="<?= e(gallery($g)) ?>" alt="Création Dina Perles" class="h-full w-full object-cover">
            </div>
        <?php endforeach; ?>
    </section>

    <section class="max-w-[1280px] mx-auto px-6 lg:px-12 py-24">
        <?= View::partial('partials/section-heading', ['eyebrow' => 'Nos engagements', 'titleHtml' => 'Ce que nous promettons']) ?>
        <div class="grid md:grid-cols-3 gap-12 mt-16">
            <?php foreach ($pillars as $i => [$t, $d]): ?>
                <div data-reveal style="--delay: <?= $i * 0.1 ?>s" class="border-t border-[var(--ink)] pt-8">
                    <h3 class="font-display text-3xl"><?= e($t) ?></h3>
                    <p class="text-[var(--ink-2)] mt-4 leading-relaxed"><?= e($d) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?= View::partial('partials/order-steps') ?>

    <section class="max-w-[1280px] mx-auto px-6 lg:px-12 py-24 text-center">
        <div data-reveal>
            <span class="eyebrow">Dina Perles</span>
            <p class="font-display text-3xl sm:text-4xl lg:text-5xl leading-tight max-w-4xl mx-auto mt-6">
                « Dina Perles », le prénom de sa fondatrice et la perle qu'elle tisse. Un nom pour une promesse : la lumière portée à la main.
            </p>
        </div>
    </section>
</div>
