<?php
/**
 * @var array $cart  résultat de App\Cart::summary()
 * @var array{old: array, errors: array} $form
 */
use App\View;

$old = $form['old'];
$errors = $form['errors'];
$empty = $cart['items'] === [];
?>
<div data-testid="panier-page" data-cart-page>
    <?= View::partial('partials/page-hero', ['eyebrow' => 'Panier', 'titleHtml' => 'Votre sélection']) ?>
    <div class="max-w-[1280px] mx-auto px-6 lg:px-12 grid lg:grid-cols-12 gap-16 pb-24">
        <div class="lg:col-span-7" data-cart-page-items>
            <?= View::partial('partials/cart-page-items', ['cart' => $cart]) ?>
        </div>

        <div data-reveal class="lg:col-span-5">
            <div class="bg-white border border-[var(--line)] p-8 lg:p-10 lg:sticky lg:top-32">
                <div class="flex justify-between font-display text-3xl">
                    <span>Total</span>
                    <span class="font-mono text-xl" data-cart-total data-testid="cart-page-total"><?= price($cart['total']) ?></span>
                </div>
                <p class="text-xs text-[var(--ink-3)] mt-2">Livraison calculée à la confirmation · Paiement à la livraison, MTN MoMo ou Moov Money</p>

                <form method="post" action="<?= e(url('/commande')) ?>" class="mt-8 space-y-4" data-testid="checkout-form" data-checkout-form>
                    <?= csrf_field() ?>
                    <div>
                        <input data-testid="checkout-name-input" name="customer_name" required minlength="2" maxlength="120" autocomplete="name" placeholder="Nom complet" aria-label="Nom complet" value="<?= e($old['customer_name'] ?? '') ?>">
                        <?= field_error($errors, 'customer_name') ?>
                    </div>
                    <div>
                        <input data-testid="checkout-phone-input" name="phone" required minlength="6" maxlength="40" type="tel" autocomplete="tel" placeholder="Téléphone (WhatsApp)" aria-label="Téléphone (WhatsApp)" value="<?= e($old['phone'] ?? '') ?>">
                        <?= field_error($errors, 'phone') ?>
                    </div>
                    <div>
                        <input data-testid="checkout-email-input" name="email" type="email" maxlength="190" autocomplete="email" placeholder="E-mail (optionnel)" aria-label="E-mail (optionnel)" value="<?= e($old['email'] ?? '') ?>">
                        <?= field_error($errors, 'email') ?>
                    </div>
                    <div>
                        <input data-testid="checkout-address-input" name="address" required minlength="3" maxlength="255" autocomplete="street-address" placeholder="Adresse de livraison / Ville" aria-label="Adresse de livraison / Ville" value="<?= e($old['address'] ?? '') ?>">
                        <?= field_error($errors, 'address') ?>
                    </div>
                    <textarea data-testid="checkout-note-input" name="note" rows="2" maxlength="2000" placeholder="Une précision ? (couleur, sur mesure…)" aria-label="Une précision"><?= e($old['note'] ?? '') ?></textarea>
                    <button data-testid="checkout-submit-button" data-requires-items data-loading-text="Envoi…" <?= $empty ? 'disabled' : '' ?>
                            class="btn-pill btn-dark w-full disabled:opacity-40 disabled:pointer-events-none">Confirmer la commande</button>
                </form>
                <div class="flex items-center gap-4 my-6 text-xs text-[var(--ink-3)]"><span class="h-px flex-1 bg-[var(--line)]"></span>ou<span class="h-px flex-1 bg-[var(--line)]"></span></div>
                <a href="<?= e(cart_whatsapp_link($cart)) ?>" target="_blank" rel="noopener" data-testid="whatsapp-checkout-button" data-cart-whatsapp
                   class="btn-pill btn-ghost w-full <?= $empty ? 'pointer-events-none opacity-40' : '' ?>"><?= icon('message-circle', 16) ?> Commander sur WhatsApp</a>
            </div>
        </div>
    </div>
</div>
