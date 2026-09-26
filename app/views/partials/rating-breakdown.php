<?php
/**
 * Répartition des notes (5 à 1 étoile) en barres horizontales.
 *
 * @var array{count: int, average: float, distribution: array<int, int>} $stats
 * @var string|null $filterUrl  URL de la page des avis : chaque ligne devient un filtre (?note=N)
 * @var int|null $active        note actuellement filtrée
 */
$filterUrl ??= null;
$active ??= null;
?>
<ul class="space-y-2.5" data-testid="rating-breakdown">
    <?php foreach ($stats['distribution'] as $n => $total):
        $pct = $stats['count'] > 0 ? (int) round($total / $stats['count'] * 100) : 0;
        $row = '<span class="w-16 shrink-0">' . $n . ' étoile' . ($n > 1 ? 's' : '') . '</span>'
             . '<span class="rating-bar flex-1 h-2.5 rounded-full overflow-hidden"><span style="width: ' . $pct . '%"></span></span>'
             . '<span class="w-10 shrink-0 text-right font-mono text-xs">' . $pct . ' %</span>'; ?>
        <li>
            <?php if ($filterUrl !== null && $total > 0): ?>
                <a href="<?= e($filterUrl . '?note=' . $n) ?>" class="flex items-center gap-3 text-sm rounded-lg -mx-2 px-2 py-0.5 hover:bg-[var(--bg-elevated)] <?= $active === $n ? 'bg-[var(--bg-elevated)] font-semibold' : '' ?>"
                   aria-label="Voir les avis <?= $n ?> étoile<?= $n > 1 ? 's' : '' ?> (<?= $total ?>)"><?= $row ?></a>
            <?php else: ?>
                <div class="flex items-center gap-3 text-sm py-0.5 <?= $total === 0 ? 'text-[var(--ink-3)]' : '' ?>"><?= $row ?></div>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
