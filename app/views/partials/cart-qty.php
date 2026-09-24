<?php
/**
 * Sélecteur de quantité d'une ligne du panier (fonctionne aussi sans JavaScript).
 *
 * @var array $item
 * @var string $prefix  préfixe des data-testid (cart ou cart-page)
 * @var string $size    classes de taille des boutons
 */
$minusId = $prefix === 'cart' ? "cart-qty-minus-{$item['id']}" : "cart-page-minus-{$item['id']}";
$plusId = $prefix === 'cart' ? "cart-qty-plus-{$item['id']}" : "cart-page-plus-{$item['id']}";
?>
<div class="inline-flex items-center border border-[var(--line)] rounded-full">
    <form method="post" action="<?= e(url('/panier/modifier')) ?>" data-cart-form>
        <?= csrf_field() ?>
        <input type="hidden" name="product_id" value="<?= e($item['id']) ?>">
        <input type="hidden" name="qty" value="<?= $item['qty'] - 1 ?>">
        <button data-testid="<?= e($minusId) ?>" aria-label="Retirer un exemplaire" class="<?= $size ?> flex items-center justify-center" <?= $item['qty'] <= 1 ? 'disabled' : '' ?>><?= icon('minus', 12) ?></button>
    </form>
    <span data-testid="<?= e($prefix) ?>-qty-<?= e($item['id']) ?>" class="w-6 text-center text-sm"><?= $item['qty'] ?></span>
    <form method="post" action="<?= e(url('/panier/modifier')) ?>" data-cart-form>
        <?= csrf_field() ?>
        <input type="hidden" name="product_id" value="<?= e($item['id']) ?>">
        <input type="hidden" name="qty" value="<?= $item['qty'] + 1 ?>">
        <button data-testid="<?= e($plusId) ?>" aria-label="Ajouter un exemplaire" class="<?= $size ?> flex items-center justify-center" <?= $item['qty'] >= App\Cart::MAX_QTY ? 'disabled' : '' ?>><?= icon('plus', 12) ?></button>
    </form>
</div>
