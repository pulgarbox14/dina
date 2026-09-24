<?php
/** @var bool|null $dark */
$items = ['Fait main au Bénin', 'Perles nacrées haute qualité', 'Éditions limitées', 'Livraison Afrique & International', 'Commandes sur mesure'];
$row = array_merge($items, $items);
?>
<div data-testid="marquee" aria-hidden="true"
     class="overflow-hidden border-y <?= !empty($dark) ? 'border-white/10 bg-[var(--ink)] text-[var(--pearl)]' : 'border-[var(--line)] bg-[var(--bg-elevated)]' ?> py-5">
    <div class="marquee-track flex w-max">
        <?php foreach ($row as $t): ?>
            <span class="flex items-center gap-8 pr-8 font-display text-2xl sm:text-3xl italic font-light whitespace-nowrap">
                <?= e($t) ?>
                <span class="pearl-dot h-3 w-3 rounded-full inline-block"></span>
            </span>
        <?php endforeach; ?>
    </div>
</div>
