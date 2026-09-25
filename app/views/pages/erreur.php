<?php
/**
 * @var int $code
 * @var bool|null $productNotFound
 */
$message = match (true) {
    !empty($productNotFound) => 'Cette création n\'existe pas ou n\'est plus disponible.',
    $code === 404 => 'La page que vous cherchez n\'existe pas.',
    $code === 503 => 'Le catalogue est momentanément indisponible. Merci de réessayer dans un instant.',
    default => 'Une erreur est survenue. Merci de réessayer dans un instant.',
};
?>
<div class="pt-48 pb-32 px-6 max-w-2xl mx-auto text-center" data-testid="<?= !empty($productNotFound) ? 'product-not-found' : 'error-page' ?>">
    <span class="eyebrow">Erreur <?= (int) $code ?></span>
    <h1 class="font-display text-5xl mt-6"><?= match ($code) { 404 => 'Introuvable.', 503 => 'Un instant.', default => 'Oups.' } ?></h1>
    <p class="text-[var(--ink-2)] mt-6"><?= e($message) ?></p>
    <a href="<?= e(url('/boutique')) ?>" class="btn-pill btn-dark mt-10">Retour à la boutique</a>
</div>
