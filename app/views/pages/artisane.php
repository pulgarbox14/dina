<?php
use App\View;

$badges = [
    ['hand', '100 % main', 'Aucune machine', 'hidden md:flex left-[8%] lg:left-[16%] top-[18%]', 1.2, -6, '#ec4899'],
    ['clock', '16 à 30 h', 'par pièce tissée', 'hidden md:flex left-[4%] lg:left-[12%] bottom-[22%]', 1.35, 4, '#f97316'],
    ['award', '+200 pièces', 'depuis 2019', 'hidden md:flex right-[8%] lg:right-[16%] top-[14%]', 1.3, 5, '#f43f5e'],
    ['map-pin', explode(',', brand('city'))[0], 'Atelier & formation', 'hidden md:flex right-[4%] lg:right-[12%] bottom-[26%]', 1.45, -4, '#db2777'],
];
$stats = [['+200', 'pièces créées', 'award', '#f43f5e'], ['6 ans', 'de perlage', 'clock', '#f97316'], ['3', 'pays livrés', 'map-pin', '#ec4899']];
$values = [
    ['Tout à la main', "Aucune machine n'intervient. Le fil, l'aiguille et des milliers de gestes répétés."],
    ['Une pièce, une personne', 'Secondina tisse chaque commande elle-même, du premier nœud aux finitions.'],
    ['Un savoir-faire certifié', "Formée deux ans auprès d'une patronne puis diplômée d'État (CQM), Secondina applique une technique maîtrisée à chaque pièce."],
];
$journal = ['classique', 'orangeRound', 'whiteSet', 'purple', 'amberPlate', 'trio', 'orangeTote', 'ringSet'];
?>
<div data-testid="artisane-page">
    <section data-testid="artisan-hero" class="relative pt-[100px] lg:pt-[120px] overflow-hidden bg-white">
        <?php /* Pastille en tête de page, au-dessus du portrait. */ ?>
        <div class="text-center px-6 mb-4 lg:mb-6">
            <span data-reveal style="--delay: .15s; --y: 24px" data-testid="artisan-badge" class="inline-flex items-center gap-2 bg-[var(--bg-elevated)] rounded-full px-4 h-8 text-[11px] uppercase tracking-[0.22em] text-[var(--ink-2)]">
                <?= icon('sparkles', 12) ?> L'artisane · Fondatrice
            </span>
        </div>
        <div class="flex flex-col-reverse">
            <div class="relative w-full max-w-[1280px] mx-auto px-6 text-center mt-4 lg:mt-8">
                <h1 class="font-display text-3xl sm:text-5xl lg:text-[clamp(2.75rem,3.6vw,4rem)] leading-[1.08] tracking-[-0.03em] max-w-5xl mx-auto">
                    <?php $artisan = mb_strtoupper(brand('artisan')); ?>
                    <?= View::partial('partials/line-reveal', ['lines' => [$artisan, 'tisse chaque perle à la main.'], 'delay' => 0.3, 'highlight' => $artisan]) ?>
                </h1>
                <p data-reveal style="--delay: .9s; --y: 24px" class="mt-6 max-w-lg mx-auto text-[var(--ink-2)] leading-relaxed">
                    Fondatrice de Dina Perles, diplômée d'État (CQM), elle conçoit, dessine et tisse chaque pièce elle-même dans son atelier de <?= e(brand('city')) ?>.
                </p>
                <div data-reveal style="--delay: 1.05s; --y: 24px" class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="<?= e(url('/boutique')) ?>" data-testid="artisan-cta-boutique" class="btn-pill btn-dark">Voir ses créations <?= icon('arrow-up-right', 16) ?></a>
                    <a href="<?= e(url('/contact')) ?>" data-testid="artisan-cta-contact-hero" class="btn-pill btn-ghost">Commander sur mesure</a>
                </div>
            </div>

            <div class="relative mt-0 h-[400px] sm:h-[520px] lg:h-[600px] w-full max-w-[1280px] mx-auto flex items-end justify-center">
                <img src="<?= e(url('artisan-cutout.png')) ?>" alt="<?= e(brand('artisan')) ?>" data-reveal style="--delay: .5s; --y: 60px; --dur: 1.5s" data-testid="artisan-cutout"
                     class="artisan-mask max-h-full max-w-full w-auto object-contain object-bottom drop-shadow-[0_40px_80px_rgba(20,20,20,0.18)]">
                <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-white via-white/70 to-transparent"></div>

                <?php foreach ($badges as [$ic, $title, $text, $pos, $delay, $rotate, $color]): ?>
                    <div data-reveal style="--delay: <?= $delay ?>s; --y: 30px; --r: <?= $rotate ?>deg; --dur: 1.1s" class="reveal-rotate absolute card-3d p-4 pr-6 flex items-center gap-3 <?= $pos ?>">
                        <span class="h-11 w-11 rounded-xl flex items-center justify-center" style="background: <?= $color ?>22; color: <?= $color ?>"><?= icon($ic, 20, 1.8) ?></span>
                        <div>
                            <div class="font-display text-lg leading-none"><?= e($title) ?></div>
                            <div class="text-[11px] text-[var(--ink-3)] mt-1"><?= e($text) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="relative max-w-[1100px] mx-auto px-6 lg:px-12 mt-14 pb-6">
            <div class="grid grid-cols-3 gap-3 sm:gap-5">
                <?php foreach ($stats as $i => [$n, $l, $ic, $color]): ?>
                    <div data-reveal style="--delay: <?= 1.4 + $i * 0.1 ?>s; --y: 24px" class="card-3d p-5 sm:p-7 flex flex-col items-center text-center gap-3">
                        <span class="h-10 w-10 rounded-full flex items-center justify-center" style="background: <?= $color ?>22; color: <?= $color ?>"><?= icon($ic, 18, 1.8) ?></span>
                        <div class="font-display text-2xl sm:text-5xl leading-none whitespace-nowrap"><?= e($n) ?></div>
                        <div class="eyebrow"><?= e($l) ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-6 lg:px-12 pt-24 grid lg:grid-cols-12 gap-12">
        <div data-reveal class="lg:col-span-6">
            <div class="grid grid-cols-2 gap-4">
                <div data-tilt class="tilt-card relative aspect-[3/4] overflow-hidden rounded-[28px] img-zoom bg-[var(--luster)] shadow-[0_30px_60px_-36px_rgba(20,20,20,0.4)]"><img src="<?= e(gallery('lune')) ?>" alt="Sac Lune Nacre" class="h-full w-full object-cover" data-testid="artisan-portrait"><span class="tilt-shine"></span></div>
                <div data-tilt class="tilt-card relative aspect-[3/4] overflow-hidden rounded-[28px] img-zoom bg-[var(--luster)] mt-10 shadow-[0_30px_60px_-36px_rgba(20,20,20,0.4)]"><img src="<?= e(gallery('orangeTote')) ?>" alt="Panier Soleil" class="h-full w-full object-cover"><span class="tilt-shine"></span></div>
            </div>
        </div>
        <div class="lg:col-span-5 lg:col-start-8 lg:pt-12 space-y-8">
            <div data-reveal>
                <p class="font-display text-2xl sm:text-3xl leading-snug">Deux ans d'apprentissage, un diplôme d'État, <span class="text-gradient">six ans de perlage.</span></p>
            </div>
            <div data-reveal style="--delay: .1s">
                <p class="text-[var(--ink-2)] leading-relaxed">Secondina a appris le perlage au cours d'une formation professionnelle de deux ans, auprès d'une patronne qui lui a transmis les gestes du métier. Elle a ensuite obtenu son CQM, le Certificat de Qualification aux Métiers : un diplôme d'État, délivré avec son attestation.</p>
                <p class="text-[var(--ink-2)] leading-relaxed mt-5">Voilà maintenant environ six ans qu'elle perle. Elle imagine ses propres modèles (la fleur orange, la fleur d'améthyste, la trame nacrée) et tisse chaque commande elle-même, perle après perle, dans son atelier de Cotonou.</p>
            </div>
            <?php /* Parcours de l'artisane (informations fournies par Secondina). */ ?>
            <ol data-reveal style="--delay: .12s" class="grid sm:grid-cols-3 gap-3" data-testid="artisan-parcours">
                <?php foreach ([
                    ['hand', '#f97316', 'Formation', "2 ans auprès d'une patronne"],
                    ['award', '#ec4899', 'CQM', "Diplôme d'État et attestation"],
                    ['sparkles', '#f43f5e', '6 ans', 'dans le perlage'],
                ] as [$ic, $color, $t, $d]): ?>
                    <li class="card-3d p-4 flex sm:flex-col items-center sm:items-start gap-3">
                        <span class="h-10 w-10 shrink-0 rounded-xl flex items-center justify-center" style="background: <?= $color ?>1f; color: <?= $color ?>"><?= icon($ic, 18, 1.8) ?></span>
                        <span>
                            <span class="block font-display text-lg leading-tight"><?= e($t) ?></span>
                            <span class="block text-xs text-[var(--ink-3)] mt-1"><?= e($d) ?></span>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ol>
            <div data-reveal style="--delay: .15s">
                <a href="<?= e(url('/contact')) ?>" data-testid="artisan-cta-contact" class="btn-pill btn-dark">Commander sur mesure <?= icon('arrow-up-right', 16) ?></a>
            </div>
        </div>
    </section>

    <section class="max-w-[1280px] mx-auto px-6 lg:px-12 py-20">
        <?= View::partial('partials/section-heading', ['eyebrow' => "Journal d'atelier", 'titleHtml' => 'Quelques pièces sorties de ses mains']) ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-14" data-testid="atelier-gallery">
            <?php foreach ($journal as $i => $g): ?>
                <div data-reveal style="--delay: <?= ($i % 4) * 0.08 ?>s" class="img-zoom overflow-hidden rounded-[24px] bg-[var(--luster)] <?= $i % 3 === 0 ? 'row-span-2 aspect-[3/5]' : 'aspect-[3/4]' ?>">
                    <img src="<?= e(gallery($g)) ?>" alt="Création Dina Perles" loading="lazy" class="h-full w-full object-cover">
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <?= View::partial('partials/marquee') ?>

    <section class="max-w-[1280px] mx-auto px-6 lg:px-12 py-20 grid lg:grid-cols-3 gap-6">
        <?php foreach ($values as $i => [$t, $d]): ?>
            <div data-reveal style="--delay: <?= $i * 0.1 ?>s">
                <div data-tilt class="tilt-card relative card-3d p-9 h-full">
                    <span class="font-mono text-xs text-[var(--ink-3)]">0<?= $i + 1 ?></span>
                    <h3 class="font-display text-2xl mt-4"><?= e($t) ?></h3>
                    <p class="text-[var(--ink-2)] mt-4 leading-relaxed text-sm"><?= e($d) ?></p>
                    <span class="tilt-shine"></span>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</div>
