<?php
/**
 * Texte révélé ligne par ligne (équivalent de LineReveal).
 *
 * @var list<string> $lines
 * @var float|null $delay  délai avant la première ligne, en secondes
 * @var string|null $highlight  mot ou groupe de mots affiché en dégradé orange → rose
 */
$delay ??= 0;
$highlight ??= null;
?>
<span class="line-reveal" data-reveal="lines" style="--d: <?= (float) $delay ?>s">
    <?php foreach ($lines as $i => $line): ?>
        <span class="block overflow-hidden pb-[0.08em]"><span class="line block" style="--i: <?= $i ?>"><?= $highlight !== null && str_contains($line, $highlight)
            ? str_replace(e($highlight), '<span class="text-gradient">' . e($highlight) . '</span>', e($line))
            : e($line) ?></span></span>
    <?php endforeach; ?>
</span>
