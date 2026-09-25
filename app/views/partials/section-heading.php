<?php
/**
 * @var string|null $eyebrow
 * @var string $titleHtml  titre (HTML de confiance écrit dans les gabarits)
 * @var string|null $text
 * @var string|null $align  'left' | 'center'
 */
$align ??= 'left';
?>
<div data-reveal class="<?= $align === 'center' ? 'text-center mx-auto' : '' ?> max-w-2xl">
    <?php if (!empty($eyebrow)): ?><span class="eyebrow"><?= e($eyebrow) ?></span><?php endif; ?>
    <h2 class="font-display text-[1.75rem] sm:text-4xl leading-[1.08] tracking-tight mt-4"><?= $titleHtml ?></h2>
    <?php if (!empty($text)): ?><p class="text-base text-[var(--ink-2)] mt-6 leading-relaxed"><?= e($text) ?></p><?php endif; ?>
</div>
