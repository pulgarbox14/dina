<?php
/** Bloc « Commander en trois étapes » (accueil et À propos). */
$steps = [
    [
        '01', 'shopping-bag', '#f97316', 'Choisissez votre pièce',
        'Parcourez la boutique ou composez votre modèle en 3D : forme, couleur des fleurs, dimensions.',
        ['Pièces uniques', 'Sur mesure possible'],
    ],
    [
        '02', 'message-circle', '#ec4899', 'Commandez en ligne ou sur WhatsApp',
        'Validez votre panier sur le site ou envoyez votre sélection sur WhatsApp. Secondina vous rappelle pour confirmer les détails.',
        ['Réponse sous 24 h', 'Conseil personnalisé'],
    ],
    [
        '03', 'truck', '#f43f5e', 'Payez et recevez',
        "Paiement à la livraison à Cotonou, par MTN MoMo ou Moov Money. Livraison en 24 à 48 h à Cotonou, 3 à 7 jours en Afrique de l'Ouest.",
        ['MTN MoMo · Moov Money', 'Livraison suivie'],
    ],
];
?>
<section data-testid="order-steps-section" class="px-3 sm:px-5 py-8">
    <div class="rounded-[40px] bg-warm py-20 lg:py-28 px-6 lg:px-16">
        <div class="max-w-[1440px] mx-auto">
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">
                <?= App\View::partial('partials/section-heading', [
                    'eyebrow'   => 'Commander',
                    'titleHtml' => 'Votre pièce en <span class="text-gradient">trois étapes.</span>',
                    'text'      => 'Pas de compte à créer, pas de paiement en ligne obligatoire : nous confirmons chaque commande avec vous.',
                ]) ?>
                <div data-reveal style="--delay: .1s" class="flex flex-wrap gap-3">
                    <a href="<?= e(url('/boutique')) ?>" data-testid="order-steps-shop-link" class="btn-pill btn-dark">Voir la boutique <?= icon('arrow-up-right', 16) ?></a>
                    <a href="<?= e(whatsapp_link('Bonjour Dina Perles ! Je souhaite passer commande.')) ?>" target="_blank" rel="noopener" data-testid="order-steps-whatsapp-link" class="btn-pill btn-ghost bg-white"><?= icon('message-circle', 16) ?> WhatsApp</a>
                </div>
            </div>

            <ol class="grid md:grid-cols-3 gap-6 mt-14">
                <?php foreach ($steps as $i => [$n, $ic, $color, $title, $text, $tags]): ?>
                    <li data-reveal style="--delay: <?= $i * 0.1 ?>s">
                        <div data-tilt class="tilt-card relative card-3d p-8 lg:p-10 h-full flex flex-col" data-testid="order-step-<?= $n ?>">
                            <div class="flex items-center justify-between">
                                <span class="h-14 w-14 rounded-2xl flex items-center justify-center" style="background: <?= $color ?>1f; color: <?= $color ?>"><?= icon($ic, 24, 1.8) ?></span>
                                <span class="font-display text-5xl leading-none opacity-25" style="color: <?= $color ?>" aria-hidden="true"><?= $n ?></span>
                            </div>
                            <h3 class="font-display text-2xl leading-tight mt-8"><?= e($title) ?></h3>
                            <p class="text-sm text-[var(--ink-2)] mt-4 leading-relaxed flex-1"><?= e($text) ?></p>
                            <div class="flex flex-wrap gap-2 mt-8">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 h-8 text-xs font-medium" style="background: <?= $color ?>14; color: <?= $color ?>"><?= icon('check', 12) ?> <?= e($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <span class="tilt-shine"></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</section>
