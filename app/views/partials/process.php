<?php
$steps = [
    ['01', 'Sélection des perles', 'Chaque perle nacrée est triée à la main : calibre, brillance, régularité.'],
    ['02', 'Dessin du motif', 'Les fleurs orange, violettes ou bleues sont placées sur une grille avant le tissage.'],
    ['03', 'Tissage', 'Entre 14 et 30 heures de tissage au fil renforcé, sans colle ni machine.'],
    ['04', 'Finitions', 'Anses, doublure, fermoir : les détails qui font durer un sac des années.'],
];
?>
<section data-testid="process-section" class="px-3 sm:px-5 py-8">
    <div class="rounded-[40px] bg-[var(--bg-elevated)] py-28 px-6 lg:px-16">
        <?= App\View::partial('partials/section-heading', ['eyebrow' => 'Savoir-faire', 'titleHtml' => 'Quatre gestes, une pièce unique.']) ?>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mt-16">
            <?php foreach ($steps as $i => [$n, $t, $d]): ?>
                <div data-reveal style="--delay: <?= $i * 0.1 ?>s">
                    <div data-tilt class="tilt-card relative card-3d p-9 min-h-[300px] flex flex-col justify-between group">
                        <span class="font-mono text-xs text-[var(--ink-3)]"><?= $n ?></span>
                        <div>
                            <div class="pearl-dot h-5 w-5 rounded-full mb-6 group-hover:scale-125 transition-transform duration-500"></div>
                            <h3 class="font-display text-2xl leading-tight"><?= e($t) ?></h3>
                            <p class="text-sm text-[var(--ink-2)] mt-4 leading-relaxed"><?= e($d) ?></p>
                        </div>
                        <span class="tilt-shine"></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
