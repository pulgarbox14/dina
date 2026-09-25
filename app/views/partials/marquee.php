<?php
/** @var bool|null $dark */
$items = ['Fait main au Bénin', 'Perles nacrées haute qualité', 'Éditions limitées', 'Livraison Afrique & International', 'Commandes sur mesure'];
$row = array_merge($items, $items);
?>
<div data-testid="marquee" aria-hidden="true"
     class="overflow-hidden border-y <?= !empty($dark) ? 'border-white/10 bg-[var(--ink)] text-[var(--pearl)]' : 'border-[#fbe3ea] bg-warm' ?> py-5">
    <div class="marquee-track flex w-max">
        <?php foreach ($row as $k => $t): ?>
            <span class="flex items-center gap-8 pr-8 font-display text-lg sm:text-3xl whitespace-nowrap">
                <?= e($t) ?>
                <span class="pearl-dot <?= $k % 2 ? 'pearl-dot-rose' : 'pearl-dot-warm' ?> h-3 w-3 rounded-full inline-block"></span>
            </span>
        <?php endforeach; ?>
    </div>
</div>
