<?php
/** @var array{old: array, errors: array} $form */
use App\View;

$old = $form['old'];
$errors = $form['errors'];
$faq = [
    ['Livrez-vous hors du Bénin ?', "Oui. Afrique de l'Ouest en 3 à 7 jours, Europe et Amérique du Nord en 7 à 14 jours via transporteur suivi."],
    ['Puis-je commander un modèle sur mesure ?', 'Absolument. Choisissez la couleur des fleurs, la taille et même vos initiales. Comptez 2 à 3 semaines de fabrication.'],
    ['Comment entretenir mon sac en perles ?', "Un chiffon doux et sec suffit. Évitez l'eau, les parfums et le soleil direct. Rangez-le dans sa pochette."],
    ['Quels moyens de paiement acceptez-vous ?', "Paiement à la livraison à Cotonou, MTN MoMo, Moov Money et virement pour l'international."],
];
$channels = [
    // [pastille (HTML), libellé, valeur, lien, data-testid]
    ['<span class="social-badge social-whatsapp h-11 w-11 rounded-full flex items-center justify-center shrink-0">' . brand_icon('whatsapp', 20) . '</span>', 'WhatsApp', '+' . brand('whatsapp'), whatsapp_link('Bonjour Dina Perles !'), 'contact-whatsapp-link'],
    ['<span class="social-badge social-mail h-11 w-11 rounded-full flex items-center justify-center shrink-0">' . icon('mail', 18, 2) . '</span>', 'E-mail', brand('email'), 'mailto:' . brand('email'), 'contact-email-link'],
    ['<span class="social-badge social-instagram h-11 w-11 rounded-full flex items-center justify-center shrink-0">' . brand_icon('instagram', 19) . '</span>', 'Instagram', brand('instagram'), '#', 'contact-instagram-link'],
    ['<span class="h-11 w-11 rounded-full border border-[var(--line)] flex items-center justify-center shrink-0">' . icon('map-pin', 17, 1.6) . '</span>', 'Atelier', brand('city'), null, 'contact-address'],
];
?>
<div data-testid="contact-page">
    <?= View::partial('partials/page-hero', [
        'eyebrow'   => 'Contact',
        'titleHtml' => 'Parlons de votre <em class="not-italic text-gradient">prochaine pièce.</em>',
        'text'      => "Une question, une commande sur mesure, une collaboration ? L'atelier vous répond sous 24 heures.",
    ]) ?>
    <div class="max-w-[1280px] mx-auto px-6 lg:px-12 grid lg:grid-cols-12 gap-16 pb-32">
        <div data-reveal class="lg:col-span-4 space-y-8">
            <?php foreach ($channels as [$badge, $label, $value, $href, $tid]): ?>
                <div class="flex gap-4 items-center">
                    <?= $badge ?>
                    <div>
                        <div class="eyebrow"><?= e($label) ?></div>
                        <?php if ($href): ?>
                            <a href="<?= e($href) ?>" target="_blank" rel="noopener" data-testid="<?= $tid ?>" class="link-underline mt-1 inline-block"><?= e($value) ?></a>
                        <?php else: ?>
                            <div data-testid="<?= $tid ?>" class="mt-1"><?= e($value) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div data-reveal style="--delay: .1s" class="lg:col-span-8">
            <form method="post" action="<?= e(url('/contact')) ?>" data-ajax-form data-reset-on-success novalidate class="relative grid sm:grid-cols-2 gap-x-8 gap-y-6" data-testid="contact-form">
                <?= csrf_field() ?>
                <?= honeypot_field() ?>
                <div>
                    <input data-testid="contact-name-input" name="name" required minlength="2" maxlength="120" placeholder="Votre nom" aria-label="Votre nom" value="<?= e($old['name'] ?? '') ?>">
                    <?= field_error($errors, 'name') ?>
                </div>
                <div>
                    <input data-testid="contact-email-input" name="email" required type="email" maxlength="190" placeholder="Votre e-mail" aria-label="Votre e-mail" value="<?= e($old['email'] ?? '') ?>">
                    <?= field_error($errors, 'email') ?>
                </div>
                <div class="sm:col-span-2">
                    <input data-testid="contact-subject-input" name="subject" required minlength="2" maxlength="190" placeholder="Sujet" aria-label="Sujet" value="<?= e($old['subject'] ?? '') ?>">
                    <?= field_error($errors, 'subject') ?>
                </div>
                <div class="sm:col-span-2">
                    <textarea data-testid="contact-message-input" name="message" required minlength="5" maxlength="5000" rows="5" placeholder="Votre message" aria-label="Votre message"><?= e($old['message'] ?? '') ?></textarea>
                    <?= field_error($errors, 'message') ?>
                </div>
                <button data-testid="contact-submit-button" class="btn-pill btn-dark w-fit disabled:opacity-40" data-loading-text="Envoi…">Envoyer le message</button>
            </form>
            <div class="mt-24">
                <h2 class="font-display text-3xl mb-6">Questions fréquentes</h2>
                <div data-testid="faq-accordion" class="divide-y divide-[var(--line)] border-b border-[var(--line)]">
                    <?php foreach ($faq as $i => [$q, $a]): ?>
                        <details class="accordion group" name="faq">
                            <summary data-testid="faq-trigger-<?= $i ?>" class="flex items-center justify-between gap-4 py-4 text-left font-display text-xl font-normal cursor-pointer list-none hover:underline">
                                <?= e($q) ?>
                                <span class="shrink-0 transition-transform duration-200 group-open:rotate-180"><?= icon('chevron-down', 16) ?></span>
                            </summary>
                            <div class="pb-4 text-sm text-[var(--ink-2)]"><?= e($a) ?></div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
