<?php
$cols = [
    ['Boutique', 'boutique', [['Sacs', '/boutique?categorie=sacs', 'sacs'], ['Mini-sacs', '/boutique?categorie=minis', 'mini-sacs'], ['Parures & bijoux', '/boutique?categorie=bijoux', 'parures-bijoux'], ['Panier', '/panier', 'panier']]],
    ['Maison', 'maison', [["L'Artisane", '/artisane', 'l-artisane'], ['À propos', '/a-propos', '-propos'], ['Contact', '/contact', 'contact'], ['Sur mesure', '/contact', 'sur-mesure']]],
];
$form = App\Session::takeForm('newsletter');
?>
<footer data-testid="footer" class="px-3 sm:px-5 pb-5 mt-28">
    <div class="rounded-[40px] bg-warm px-6 sm:px-10 lg:px-16 pt-14 pb-8 overflow-hidden relative">
        <div class="grid lg:grid-cols-12 gap-10 items-end pb-12 border-b border-[var(--line)]">
            <div class="lg:col-span-7">
                <span class="eyebrow">Dina Perles</span>
                <h2 class="font-display text-4xl sm:text-5xl lg:text-6xl leading-[1.02] mt-4">Une pièce <span class="text-gradient">unique</span> <br class="hidden sm:block"> vous attend.</h2>
            </div>
            <div class="lg:col-span-5 flex flex-wrap gap-3 lg:justify-end">
                <a href="<?= e(url('/boutique')) ?>" data-testid="footer-cta-boutique" class="btn-pill btn-dark">Voir la boutique <?= icon('arrow-up-right', 16) ?></a>
                <a href="<?= e(whatsapp_link('Bonjour Dina Perles !')) ?>" target="_blank" rel="noopener" data-testid="footer-whatsapp-link" class="btn-pill btn-ghost bg-white"><?= icon('message-circle', 16) ?> WhatsApp</a>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-12 gap-10 py-12">
            <div class="lg:col-span-4">
                <?= App\View::partial('partials/logo') ?>
                <p class="text-sm text-[var(--ink-2)] mt-6 max-w-xs leading-relaxed">Sacs et parures en perles nacrées, tissés à la main à <?= e(brand('city')) ?>. Chaque perle est posée une à une.</p>
                <div class="flex gap-3 mt-6">
                    <?php foreach ([['instagram', '#', 'footer-instagram', 'Instagram'], ['message-circle', whatsapp_link('Bonjour !'), 'footer-whatsapp-icon', 'WhatsApp'], ['mail', 'mailto:' . brand('email'), 'footer-mail', 'E-mail']] as [$ic, $href, $tid, $label]): ?>
                        <a href="<?= e($href) ?>" target="_blank" rel="noopener" data-testid="<?= $tid ?>" aria-label="<?= e($label) ?>" class="h-11 w-11 rounded-full bg-white border border-[var(--line)] flex items-center justify-center hover:bg-[var(--ink)] hover:text-white transition-colors"><?= icon($ic, 17, 1.6) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php foreach ($cols as [$title, $tid, $links]): ?>
                <div class="lg:col-span-2">
                    <div class="text-sm font-medium mb-5"><?= e($title) ?></div>
                    <ul class="space-y-3 text-sm text-[var(--ink-2)]">
                        <?php foreach ($links as [$label, $to, $slug]): ?>
                            <li><a href="<?= e(url($to)) ?>" data-testid="footer-link-<?= $tid ?>-<?= $slug ?>" class="link-underline hover:text-[var(--ink)]"><?= e($label) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
            <div class="lg:col-span-4">
                <div class="text-sm font-medium mb-5">Newsletter</div>
                <p class="text-sm text-[var(--ink-2)]">Les nouvelles pièces partent vite. Soyez prévenu(e) en premier.</p>
                <form method="post" action="<?= e(url('/newsletter')) ?>" data-ajax-form data-testid="newsletter-form"
                      class="relative mt-5 flex items-center bg-white rounded-full border border-[var(--line)] p-1.5 pl-5">
                    <?= csrf_field() ?>
                    <?= honeypot_field() ?>
                    <input data-testid="newsletter-email-input" type="email" name="email" required maxlength="190" aria-label="Votre e-mail"
                           value="<?= e($form['old']['email'] ?? '') ?>" placeholder="votre@email.com" class="!border-0 !py-2 text-sm">
                    <button data-testid="newsletter-submit-button" class="h-10 px-5 rounded-full bg-[var(--ink)] text-white text-sm shrink-0 hover:bg-[image:var(--accent-gradient)] transition-colors">S'inscrire</button>
                </form>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between gap-4 pt-6 border-t border-[var(--line)] text-xs text-[var(--ink-3)]">
            <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                <span data-testid="footer-copyright">© <?= date('Y') ?> <?= e(brand('name')) ?>. Tous droits réservés.</span>
                <span class="hidden sm:inline" aria-hidden="true">·</span>
                <span data-testid="footer-credit">Développé par <span class="font-semibold text-[var(--ink-2)]">Pascal Carmel GUEZO</span></span>
            </div>
            <div class="flex flex-wrap gap-2">
                <?php foreach ([['hand', '100 % fait main'], ['truck', 'Livraison Afrique & monde'], ['shield-check', 'MTN MoMo · Moov Money · Livraison']] as [$ic, $t]): ?>
                    <span class="inline-flex items-center gap-1.5 bg-white border border-[var(--line)] rounded-full px-3 h-7"><?= icon($ic, 12) ?> <?= e($t) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</footer>
