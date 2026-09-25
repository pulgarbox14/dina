<?php /* Affiché à la place des produits quand MySQL ne répond pas (voir ProductRepository::guard). */ ?>
<div data-testid="catalog-unavailable" class="card-3d p-10 text-center max-w-xl mx-auto">
    <p class="font-display text-2xl">Nos créations reviennent dans un instant.</p>
    <p class="text-sm text-[var(--ink-2)] mt-3">Le catalogue est momentanément indisponible. Vous pouvez nous écrire directement sur WhatsApp.</p>
    <a href="<?= e(whatsapp_link('Bonjour Dina Perles ! Je souhaite voir vos créations.')) ?>" target="_blank" rel="noopener" class="btn-pill btn-dark mt-6"><?= brand_icon('whatsapp', 16) ?> Écrire sur WhatsApp</a>
</div>
