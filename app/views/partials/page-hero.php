<?php
/**
 * @var string $eyebrow
 * @var string $titleHtml  titre (HTML de confiance écrit dans les gabarits)
 * @var string|null $text
 * @var string|null $extra  HTML ajouté sous le texte (ex. filtres de la boutique)
 */
?>
<section class="pt-40 pb-16 px-6 lg:px-12 max-w-[1440px] mx-auto">
    <div data-reveal>
        <span class="eyebrow"><?= e($eyebrow) ?></span>
        <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl leading-[0.98] tracking-tight mt-5 max-w-3xl"><?= $titleHtml ?></h1>
        <?php if (!empty($text)): ?><p class="text-base sm:text-lg text-[var(--ink-2)] mt-8 max-w-xl leading-relaxed"><?= e($text) ?></p><?php endif; ?>
        <?= $extra ?? '' ?>
    </div>
</section>
