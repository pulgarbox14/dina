<?php
/**
 * Vitrine produits de l'accueil : grande photo, carte produit, flèches et miniatures.
 * Sans JavaScript, la première pièce reste affichée avec des liens fonctionnels ;
 * avec JavaScript (app.js → initVitrine), les flèches et miniatures changent de pièce.
 *
 * @var list<array> $products
 */
if ($products === []) {
    return;
}
$products = array_slice($products, 0, 6);
$slides = array_map(static fn (array $p): array => [
    'id'       => $p['id'],
    'name'     => $p['name'],
    'subtitle' => $p['subtitle'],
    'tag'      => $p['tag'] ?: 'Pièce unique',
    'price'    => price((int) $p['price']),
    'hours'    => (int) $p['weaving_hours'] . ' h',
    'size'     => $p['dimensions'],
    'accent'   => $p['accent'],
    'image'    => $p['images'][0] ?? '',
    'url'      => url('/produit/' . $p['id']),
], $products);
$first = $slides[0];
$count = count($slides);
?>
<section data-testid="vitrine-section" data-vitrine class="max-w-[1440px] mx-auto px-6 lg:px-12 py-28" aria-roledescription="carrousel" aria-label="Vitrine des créations">
    <script type="application/json" data-vitrine-slides><?= json_encode($slides, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?></script>

    <div class="grid lg:grid-cols-12 gap-10 lg:gap-8 items-center">
        <?php /* ——— Texte + miniatures ——— */ ?>
        <div class="lg:col-span-4 order-1">
            <div data-reveal>
                <span class="eyebrow">Vitrine</span>
                <h2 class="font-display text-4xl sm:text-5xl leading-[1.02] tracking-tight mt-4">Nos pièces <span class="text-gradient">signature.</span></h2>
                <p class="text-[var(--ink-2)] mt-6 leading-relaxed max-w-sm">Sacs et parures tissés perle après perle dans l'atelier de Cotonou. Faites défiler pour découvrir chaque création.</p>
                <a href="<?= e(url('/boutique')) ?>" data-testid="vitrine-all-link" class="btn-pill btn-dark mt-8">Tout voir <?= icon('arrow-up-right', 16) ?></a>
            </div>
            <div data-reveal style="--delay: .15s" class="flex gap-3 mt-10" role="tablist" aria-label="Choisir une création">
                <?php foreach ($slides as $i => $s): ?>
                    <button type="button" role="tab" data-vitrine-go="<?= $i ?>" data-testid="vitrine-thumb-<?= $i ?>" aria-selected="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="<?= e($s['name']) ?>"
                            class="vitrine-thumb h-24 w-16 sm:h-28 sm:w-[72px] lg:h-24 lg:w-14 shrink-0 overflow-hidden rounded-full bg-[var(--luster)] border-2 transition <?= $i > 3 ? 'hidden xl:block' : '' ?>">
                        <img src="<?= e($s['image']) ?>" alt="" loading="lazy" class="h-full w-full object-cover">
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <?php /* ——— Grande photo + badge circulaire ——— */ ?>
        <div data-reveal class="lg:col-span-5 order-2 relative">
            <a href="<?= e($first['url']) ?>" data-vitrine-link class="block img-zoom overflow-hidden rounded-[32px] aspect-[4/5] bg-[var(--luster)] shadow-[0_40px_80px_-50px_rgba(236,72,153,0.6)]">
                <img data-vitrine-image data-testid="vitrine-image" src="<?= e($first['image']) ?>" alt="<?= e($first['name']) ?>" class="vitrine-fade h-full w-full object-cover">
            </a>
            <div class="absolute -left-6 sm:-left-12 bottom-10 h-32 w-32 sm:h-36 sm:w-36 rounded-full bg-white/90 backdrop-blur shadow-xl flex items-center justify-center" aria-hidden="true">
                <svg viewBox="0 0 120 120" class="absolute inset-0 h-full w-full spin-slow">
                    <defs><path id="vitrine-cercle" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/></defs>
                    <text font-size="10.5" font-weight="600" letter-spacing="2.6" fill="#121316">
                        <textPath href="#vitrine-cercle">PIÈCE UNIQUE • TISSÉE À LA MAIN • </textPath>
                    </text>
                </svg>
                <span class="h-10 w-10 rounded-full bg-[image:var(--accent-gradient)] text-white flex items-center justify-center"><?= icon('sparkles', 18, 1.8) ?></span>
            </div>
        </div>

        <?php /* ——— Carte produit + flèches ——— */ ?>
        <div class="lg:col-span-3 order-3">
            <div data-reveal style="--delay: .1s" class="card-3d p-7" aria-live="polite">
                <div class="vitrine-fade" data-vitrine-card>
                    <span class="eyebrow" data-vitrine-tag><?= e($first['tag']) ?></span>
                    <h3 class="font-display text-2xl leading-tight mt-3" data-vitrine-name data-testid="vitrine-name"><?= e($first['name']) ?></h3>
                    <p class="text-sm text-[var(--ink-3)] mt-2" data-vitrine-subtitle><?= e($first['subtitle']) ?></p>
                    <div class="font-mono text-2xl mt-5" data-vitrine-price data-testid="vitrine-price"><?= e($first['price']) ?></div>
                    <dl class="grid grid-cols-2 gap-3 mt-6 text-sm">
                        <div class="rounded-2xl bg-warm p-3"><dt class="text-[10px] uppercase tracking-[0.18em] text-[var(--ink-3)]">Tissage</dt><dd class="mt-1 font-semibold" data-vitrine-hours><?= e($first['hours']) ?></dd></div>
                        <div class="rounded-2xl bg-warm p-3"><dt class="text-[10px] uppercase tracking-[0.18em] text-[var(--ink-3)]">Accent</dt><dd class="mt-1 font-semibold truncate" data-vitrine-accent><?= e($first['accent']) ?></dd></div>
                    </dl>
                </div>
                <form method="post" action="<?= e(url('/panier/ajouter')) ?>" data-cart-form class="mt-6">
                    <?= csrf_field() ?>
                    <input type="hidden" name="product_id" value="<?= e($first['id']) ?>" data-vitrine-id>
                    <input type="hidden" name="qty" value="1">
                    <button data-testid="vitrine-add" class="btn-pill btn-dark w-full"><?= icon('shopping-bag', 16) ?> Ajouter au panier</button>
                </form>
                <a href="<?= e($first['url']) ?>" data-vitrine-link class="btn-pill btn-ghost w-full mt-3">Voir la pièce</a>
            </div>
            <div class="flex items-center justify-between mt-6">
                <span class="font-mono text-sm text-[var(--ink-3)]"><span data-vitrine-index>01</span> / <?= sprintf('%02d', $count) ?></span>
                <div class="flex gap-2">
                    <button type="button" data-vitrine-prev data-testid="vitrine-prev" aria-label="Création précédente" class="h-12 w-12 rounded-full border border-[var(--line)] bg-white flex items-center justify-center hover:border-[var(--ink)] transition-colors"><?= icon('arrow-left', 18) ?></button>
                    <button type="button" data-vitrine-next data-testid="vitrine-next" aria-label="Création suivante" class="h-12 w-12 rounded-full bg-[var(--ink)] text-white flex items-center justify-center hover:bg-[image:var(--accent-gradient)] transition-colors"><?= icon('arrow-right', 18) ?></button>
                </div>
            </div>
        </div>
    </div>
</section>
