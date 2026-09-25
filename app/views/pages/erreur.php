<?php
/**
 * Page d'erreur affichée aux visiteurs : des mots simples, jamais de code technique.
 * Le code HTTP (404, 500…) reste envoyé au navigateur, mais n'est pas affiché.
 *
 * @var int $code
 * @var bool|null $productNotFound
 */
[$eyebrow, $heading, $message] = match (true) {
    !empty($productNotFound) => [
        'Boutique',
        'Cette création n\'est plus disponible.',
        'Elle a peut-être trouvé sa propriétaire. Découvrez les autres pièces de l\'atelier.',
    ],
    $code === 404, $code === 405 => [
        'Petit détour',
        'Cette page s\'est égarée.',
        'Le lien est peut-être incomplet ou la page a été déplacée.',
    ],
    $code === 503 => [
        'Un instant',
        'Nos créations reviennent très vite.',
        'Le catalogue est momentanément indisponible. Vous pouvez aussi nous écrire directement sur WhatsApp.',
    ],
    default => [
        'Petit contretemps',
        'Quelque chose n\'a pas fonctionné.',
        'Merci de réessayer dans un instant. Si le problème continue, écrivez-nous sur WhatsApp.',
    ],
};
?>
<div class="pt-48 pb-32 px-6 max-w-2xl mx-auto text-center" data-testid="<?= !empty($productNotFound) ? 'product-not-found' : 'error-page' ?>">
    <span class="eyebrow"><?= e($eyebrow) ?></span>
    <h1 class="font-display text-3xl sm:text-4xl mt-6"><?= e($heading) ?></h1>
    <p class="text-[var(--ink-2)] mt-6"><?= e($message) ?></p>
    <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-3">
        <a href="<?= e(url('/boutique')) ?>" class="btn-pill btn-dark">Voir la boutique <?= icon('arrow-up-right', 16) ?></a>
        <a href="<?= e(whatsapp_link('Bonjour Dina Perles !')) ?>" target="_blank" rel="noopener" class="btn-pill btn-ghost"><?= icon('message-circle', 16) ?> WhatsApp</a>
    </div>
</div>
