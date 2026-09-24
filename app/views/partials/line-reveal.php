<?php
/**
 * Texte révélé ligne par ligne (équivalent de LineReveal).
 *
 * @var list<string> $lines
 * @var float|null $delay  délai avant la première ligne, en secondes
 */
$delay ??= 0;
?>
<span class="line-reveal" data-reveal="lines" style="--d: <?= (float) $delay ?>s">
    <?php foreach ($lines as $i => $line): ?>
        <span class="block overflow-hidden pb-[0.08em]"><span class="line block" style="--i: <?= $i ?>"><?= e($line) ?></span></span>
    <?php endforeach; ?>
</span>
