<?php
/** Bloc « Sur mesure » de l'accueil : personnalisation d'une pièce. */
$options = [
    ['sparkles', '#f97316', 'Couleur des fleurs', 'Orange, violet, bleu lagon, ambre… ou la teinte exacte de votre tenue.'],
    ['shopping-bag', '#ec4899', 'Forme et taille', 'Pochette, cabas, mini-sac : des dimensions pensées pour votre usage.'],
    ['hand', '#f43f5e', 'Initiales et finitions', 'Vos initiales tissées en perles, une anse plus longue, un fermoir choisi.'],
];
// Étapes d'une commande sur mesure, affichées sur la photo.
$process = [['Votre idée', 'Échange sur WhatsApp'], ['Aperçu validé', 'Avant tout tissage'], ['Tissage main', '2 à 3 semaines']];
$waText = 'Bonjour Secondina ! Je souhaite une pièce sur mesure : ';
?>
<section data-testid="sur-mesure-section" class="max-w-[1280px] mx-auto px-6 lg:px-12 py-14 sm:py-20 grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">
    <div data-reveal class="lg:col-span-6 relative">
        <div class="img-zoom overflow-hidden rounded-[36px] aspect-[4/5] bg-[var(--luster)] shadow-[0_40px_80px_-50px_rgba(236,72,153,0.55)]">
            <img src="<?= e(gallery('purple')) ?>" alt="Panier en perles à fleur violette, réalisé sur mesure" loading="lazy" class="h-full w-full object-cover">
        </div>
        <div class="hidden sm:block absolute right-4 lg:-right-10 top-10 w-40 lg:w-48 aspect-[3/4] overflow-hidden rounded-[24px] border-4 border-white shadow-xl rotate-3">
            <img src="<?= e(gallery('orangeRound')) ?>" alt="" loading="lazy" class="h-full w-full object-cover">
        </div>
        <div class="absolute left-4 right-4 sm:left-6 sm:right-6 bottom-6 card-3d px-5 py-4" data-testid="sur-mesure-process">
            <div class="text-[10px] uppercase tracking-[0.22em] font-semibold text-[var(--rose-deep)]">De l'idée à la pièce</div>
            <ol class="mt-3 grid grid-cols-3 gap-3">
                <?php foreach ($process as $i => [$t, $d]): ?>
                    <li class="relative">
                        <?php if ($i < count($process) - 1): ?><span class="absolute left-3 right-[-0.75rem] top-[5px] h-px bg-[var(--line)]" aria-hidden="true"></span><?php endif; ?>
                        <span class="relative block h-[11px] w-[11px] rounded-full border-2 border-white shadow" style="background: var(--accent-gradient)" aria-hidden="true"></span>
                        <span class="block text-sm font-semibold leading-tight mt-2"><?= e($t) ?></span>
                        <span class="block text-[11px] text-[var(--ink-3)] mt-0.5"><?= e($d) ?></span>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>

    <div class="lg:col-span-6">
        <div data-reveal>
            <span class="eyebrow">Sur mesure</span>
            <h2 class="font-display text-[1.75rem] sm:text-4xl leading-[1.02] tracking-tight mt-4">Votre pièce, <span class="text-gradient">vos couleurs.</span></h2>
            <p class="text-[var(--ink-2)] mt-6 leading-relaxed max-w-xl">Chaque sac est tissé à la commande. Décrivez votre idée à Secondina : elle vous conseille, vous envoie un aperçu, puis tisse une pièce qui n'existera qu'une fois.</p>
        </div>

        <ul class="mt-10 space-y-4">
            <?php foreach ($options as $i => [$ic, $color, $title, $text]): ?>
                <li data-reveal style="--delay: <?= 0.08 * ($i + 1) ?>s" class="group flex gap-4 items-start card-3d p-5">
                    <span class="icon-tile h-12 w-12 shrink-0 rounded-2xl" style="--c: <?= $color ?>" aria-hidden="true"><?= icon($ic, 22, 1.8) ?></span>
                    <div>
                        <h3 class="font-display text-lg leading-tight"><?= e($title) ?></h3>
                        <p class="text-sm text-[var(--ink-2)] mt-1 leading-relaxed"><?= e($text) ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <div data-reveal style="--delay: .3s" class="mt-10 flex flex-wrap gap-3">
            <a href="<?= e(whatsapp_link($waText)) ?>" target="_blank" rel="noopener" data-testid="sur-mesure-whatsapp" class="btn-pill btn-dark"><?= brand_icon('whatsapp', 16) ?> Décrire mon idée sur WhatsApp</a>
            <a href="<?= e(url('/contact')) ?>" data-testid="sur-mesure-contact" class="btn-pill btn-ghost">Écrire un message</a>
        </div>
    </div>
</section>
