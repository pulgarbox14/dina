<?php
/** Bloc « Sur mesure » de l'accueil : personnalisation d'une pièce. */
$options = [
    ['sparkles', '#f97316', 'Couleur des fleurs', 'Orange, violet, bleu lagon, ambre… ou la teinte exacte de votre tenue.'],
    ['shopping-bag', '#ec4899', 'Forme et taille', 'Pochette, cabas, mini-sac : des dimensions pensées pour votre usage.'],
    ['hand', '#f43f5e', 'Initiales et finitions', 'Vos initiales tissées en perles, une anse plus longue, un fermoir choisi.'],
];
$swatches = ['#ffffff', '#f97316', '#a855f7', '#60a5fa', '#f59e0b', '#ec4899'];
$waText = 'Bonjour Secondina ! Je souhaite une pièce sur mesure : ';
?>
<section data-testid="sur-mesure-section" class="max-w-[1440px] mx-auto px-6 lg:px-12 py-28 grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">
    <div data-reveal class="lg:col-span-6 relative">
        <div class="img-zoom overflow-hidden rounded-[36px] aspect-[4/5] bg-[var(--luster)] shadow-[0_40px_80px_-50px_rgba(236,72,153,0.55)]">
            <img src="<?= e(gallery('purple')) ?>" alt="Panier en perles à fleur violette, réalisé sur mesure" loading="lazy" class="h-full w-full object-cover">
        </div>
        <div class="hidden sm:block absolute -right-6 lg:-right-10 top-10 w-40 lg:w-48 aspect-[3/4] overflow-hidden rounded-[24px] border-4 border-white shadow-xl rotate-3">
            <img src="<?= e(gallery('orangeRound')) ?>" alt="" loading="lazy" class="h-full w-full object-cover">
        </div>
        <div class="absolute left-4 right-4 sm:left-6 sm:right-auto bottom-6 card-3d p-5 flex items-center gap-4">
            <div>
                <div class="text-[11px] uppercase tracking-[0.2em] font-semibold text-[var(--rose-deep)]">Couleurs possibles</div>
                <div class="flex -space-x-1.5 mt-2" aria-hidden="true">
                    <?php foreach ($swatches as $c): ?>
                        <span class="h-7 w-7 rounded-full border-2 border-white shadow" style="background: <?= $c ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="pl-4 border-l border-[var(--line)]">
                <div class="font-display text-xl leading-none">2 à 3</div>
                <div class="text-[11px] text-[var(--ink-3)] mt-1">semaines de tissage</div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-6">
        <div data-reveal>
            <span class="eyebrow">Sur mesure</span>
            <h2 class="font-display text-4xl sm:text-5xl leading-[1.02] tracking-tight mt-4">Votre pièce, <span class="text-gradient">vos couleurs.</span></h2>
            <p class="text-[var(--ink-2)] mt-6 leading-relaxed max-w-xl">Chaque sac est tissé à la commande. Décrivez votre idée à Secondina : elle vous conseille, vous envoie un aperçu, puis tisse une pièce qui n'existera qu'une fois.</p>
        </div>

        <ul class="mt-10 space-y-4">
            <?php foreach ($options as $i => [$ic, $color, $title, $text]): ?>
                <li data-reveal style="--delay: <?= 0.08 * ($i + 1) ?>s" class="flex gap-4 items-start card-3d p-5">
                    <span class="h-12 w-12 shrink-0 rounded-2xl flex items-center justify-center" style="background: <?= $color ?>1f; color: <?= $color ?>"><?= icon($ic, 22, 1.8) ?></span>
                    <div>
                        <h3 class="font-display text-lg leading-tight"><?= e($title) ?></h3>
                        <p class="text-sm text-[var(--ink-2)] mt-1 leading-relaxed"><?= e($text) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <div data-reveal style="--delay: .3s" class="mt-10 flex flex-wrap gap-3">
            <a href="<?= e(whatsapp_link($waText)) ?>" target="_blank" rel="noopener" data-testid="sur-mesure-whatsapp" class="btn-pill btn-dark"><?= icon('message-circle', 16) ?> Décrire mon idée sur WhatsApp</a>
            <a href="<?= e(url('/contact')) ?>" data-testid="sur-mesure-contact" class="btn-pill btn-ghost">Écrire un message</a>
        </div>
    </div>
</section>
