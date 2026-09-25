<?php /** @var array{id: string, total: int, customer_name: string, phone: string} $order */ ?>
<div data-testid="order-success" class="pt-48 pb-32 px-6 max-w-2xl mx-auto text-center">
    <div class="h-16 w-16 rounded-full bg-[image:var(--accent-gradient)] text-white flex items-center justify-center mx-auto"><?= icon('check', 24) ?></div>
    <h1 class="font-display text-4xl mt-8">Merci, <?= e(explode(' ', $order['customer_name'])[0]) ?>.</h1>
    <p class="text-[var(--ink-2)] mt-6">
        Votre commande <span class="font-mono text-sm" data-testid="order-id">#<?= e(substr($order['id'], 0, 8)) ?></span>
        d'un montant de <strong><?= price($order['total']) ?></strong> est bien enregistrée.
        Secondina vous contactera au <?= e($order['phone']) ?> pour confirmer la livraison.
    </p>
    <a href="<?= e(url('/boutique')) ?>" data-testid="order-success-back-link" class="btn-pill btn-dark mt-10">Retour à la boutique</a>
</div>
