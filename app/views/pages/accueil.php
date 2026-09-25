<?php
/** @var list<array> $featured  produits phares */
use App\View;

$heroProduct = null;
foreach ($featured as $p) {
    if ($p['id'] === 'sac-lune-nacre') {
        $heroProduct = $p;
    }
}
$heroProduct ??= $featured[0] ?? null;
$minis = array_slice(array_values(array_filter($featured, fn ($p) => $p['id'] !== ($heroProduct['id'] ?? null))), 0, 3);

$miniCard = static function (array $p, float $delay): string {
    ob_start(); ?>
    <div data-reveal style="--delay: <?= $delay ?>s; --y: 24px" class="w-[168px] shrink-0 card-3d p-3" data-testid="hero-mini-card-<?= e($p['id']) ?>">
        <a href="<?= e(url('/produit/' . $p['id'])) ?>" class="block">
            <div class="aspect-square rounded-xl overflow-hidden bg-[var(--luster)] img-zoom">
                <img src="<?= e($p['images'][0] ?? '') ?>" alt="<?= e($p['name']) ?>" class="h-full w-full object-cover">
            </div>
            <div class="font-display text-lg mt-3 leading-none"><?= price((int) $p['price']) ?></div>
            <div class="text-[12px] text-[var(--ink-2)] mt-1 leading-snug line-clamp-2 h-8"><?= e($p['name']) ?></div>
            <div class="flex items-center justify-between mt-2">
                <span class="text-[10px] text-[var(--ink-3)]"><?= (int) $p['weaving_hours'] ?> h de tissage</span>
                <span class="h-7 px-3 rounded-full bg-[var(--ink)] text-white text-[11px] flex items-center">Voir</span>
            </div>
        </a>
    </div>
    <?php return (string) ob_get_clean();
};
?>
<div data-testid="home-page">

    <?php /* ——— Héros éditorial ——— */ ?>
    <section data-testid="hero-section" class="px-3 sm:px-5 pt-[92px]">
        <div class="relative rounded-[32px] sm:rounded-[40px] overflow-hidden min-h-[720px] sm:min-h-[640px] h-[calc(100svh-110px)] max-h-[760px] bg-warm border border-[#fbe3ea]">
            <div class="pointer-events-none absolute -top-24 right-[18%] h-[420px] w-[420px] rounded-full bg-[#fdba74] opacity-40 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-[-120px] right-[34%] h-[460px] w-[460px] rounded-full bg-[#f9a8d4] opacity-40 blur-3xl"></div>
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_60%_100%,#ffffff_0%,transparent_60%)]"></div>
            <?php /* Portrait en fond, derrière le texte : il remplit le bloc sur mobile et tablette, et se place sous le titre sur ordinateur. */ ?>
            <img src="<?= e(url('artisan-cutout.png')) ?>" alt="L'artisane et son sac en perles" data-reveal style="--y: 40px; --dur: 1.6s" data-testid="hero-portrait"
                 class="hero-portrait absolute inset-0 h-full w-full object-cover object-[50%_30%] lg:inset-auto lg:bottom-0 lg:left-[14%] xl:left-[18%] lg:h-full lg:w-auto lg:max-w-none lg:object-contain lg:object-bottom z-0">
            <div class="absolute inset-x-0 bottom-0 h-20 sm:h-32 bg-gradient-to-t from-[#fdeef6] via-[#fdeef6]/60 to-transparent"></div>
            <?php /* Voile clair derrière le texte pour qu'il reste lisible sur la photo (vertical sur mobile, horizontal sur ordinateur). */ ?>
            <div class="lg:hidden pointer-events-none absolute inset-0 bg-gradient-to-b from-[#fff6f0]/90 via-[#fff6f0]/55 via-45% to-transparent to-75%"></div>
            <div class="hidden lg:block pointer-events-none absolute inset-0 bg-gradient-to-r from-[#fff5ee]/90 via-[#fff5ee]/50 via-35% to-transparent to-55%"></div>

            <div class="hero-copy absolute left-6 sm:left-10 lg:left-14 top-[7%] lg:top-[12%] max-w-[92%] lg:max-w-[46%] text-[var(--ink)] z-10">
                <span data-reveal style="--delay: .2s; --y: 24px" class="inline-flex items-center gap-2 bg-white border border-[var(--line)] rounded-full px-4 h-8 text-[11px] uppercase tracking-[0.22em] text-[var(--ink-2)]">
                    <?= icon('sparkles', 12) ?> Haute perlerie · Cotonou
                </span>
                <h1 class="font-display text-[10.5vw] sm:text-[7vw] lg:text-[clamp(3rem,4vw,4.5rem)] leading-[0.98] tracking-[-0.035em] mt-6">
                    <?= View::partial('partials/line-reveal', ['lines' => ['Portez la', 'lumière, perle', 'après perle.'], 'delay' => 0.35, 'highlight' => 'lumière']) ?>
                </h1>
                <p data-reveal style="--delay: .95s; --y: 24px" class="mt-6 max-w-[78%] sm:max-w-sm text-sm sm:text-base text-[var(--ink-2)] leading-relaxed">
                    Sacs et parures en perles nacrées, entièrement tissés à la main dans notre atelier. Une seule pièce par modèle.
                </p>
                <div data-reveal style="--delay: 1.1s; --y: 24px" class="mt-8 flex flex-wrap gap-3">
                    <a href="<?= e(url('/boutique')) ?>" data-testid="hero-cta-explore-button" class="btn-pill btn-dark">Découvrir la boutique <?= icon('arrow-up-right', 16) ?></a>
                    <a href="<?= e(url('/artisane')) ?>" data-testid="hero-cta-artisan-button" class="btn-pill btn-ghost bg-white/90 backdrop-blur">Rencontrer l'artisane</a>
                </div>
            </div>

            <?php /* Mobile et tablette : « +200 pièces » en bas du bloc, posé sur un fondu clair qui remonte depuis le bas de la photo. */ ?>
            <div class="lg:hidden pointer-events-none absolute inset-x-0 bottom-0 h-[30%] bg-gradient-to-t from-[#fdeef6] via-[#fdeef6]/85 via-45% to-transparent"></div>
            <div class="hero-copy lg:hidden absolute left-6 right-6 sm:left-10 bottom-6 z-10 flex items-center gap-4" data-testid="hero-social-proof-mobile">
                <div class="flex -space-x-2.5 shrink-0">
                    <?php foreach (['orangeRound', 'purple', 'amber'] as $g): ?>
                        <img src="<?= e(gallery($g)) ?>" alt="" class="h-10 w-10 rounded-full object-cover border-2 border-white shadow">
                    <?php endforeach; ?>
                </div>
                <div>
                    <div class="font-display text-2xl leading-none">+200</div>
                    <div class="text-[10px] uppercase tracking-[0.16em] text-[var(--ink-2)] mt-1">pièces confiées à leurs propriétaires</div>
                </div>
            </div>
            <div data-reveal style="--delay: 1.2s; --y: 24px" class="hidden lg:flex absolute right-12 top-[14%] flex-col items-end gap-3" data-testid="hero-social-proof">
                <div class="flex -space-x-3">
                    <?php foreach (['orangeRound', 'purple', 'amber'] as $g): ?>
                        <img src="<?= e(gallery($g)) ?>" alt="" class="h-12 w-12 rounded-full object-cover border-2 border-white shadow-lg">
                    <?php endforeach; ?>
                </div>
                <div class="font-display text-4xl leading-none">+200</div>
                <div class="text-[11px] uppercase tracking-[0.2em] text-[var(--ink-3)]">pièces confiées à leurs propriétaires</div>
            </div>

            <?php if ($heroProduct): ?>
                <div data-reveal style="--delay: 1.35s; --y: 24px" class="hidden lg:block absolute right-12 top-[38%] text-right" data-testid="hero-featured-product">
                    <div class="text-xs text-[var(--ink-3)] max-w-[220px] ml-auto"><?= e($heroProduct['name']) ?> · <?= e($heroProduct['subtitle']) ?></div>
                    <form method="post" action="<?= e(url('/panier/ajouter')) ?>" data-cart-form>
                        <?= csrf_field() ?>
                        <input type="hidden" name="product_id" value="<?= e($heroProduct['id']) ?>">
                        <input type="hidden" name="qty" value="1">
                        <button data-testid="hero-add-to-cart-button"
                                class="mt-3 inline-flex items-center gap-3 bg-white border border-[var(--line)] text-[var(--ink)] rounded-full pl-5 pr-1.5 h-12 text-sm font-medium shadow-[0_20px_40px_-24px_rgba(20,20,20,0.35)] hover:-translate-y-0.5 transition-transform">
                            Ajouter au panier · <?= price((int) $heroProduct['price']) ?>
                            <span class="h-9 w-9 rounded-full bg-[var(--ink)] text-white flex items-center justify-center"><?= icon('shopping-bag', 15) ?></span>
                        </button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="hidden lg:flex absolute right-8 lg:right-12 bottom-8 gap-4 z-10" data-testid="hero-mini-cards">
                <?php foreach (array_slice($minis, 0, 2) as $i => $p) echo $miniCard($p, 1.3 + $i * 0.12); ?>
            </div>
        </div>

        <div class="lg:hidden flex gap-4 overflow-x-auto px-3 py-6 -mx-3" data-testid="hero-mini-cards-mobile">
            <?php foreach ($minis as $i => $p) echo $miniCard($p, 0.2 + $i * 0.1); ?>
        </div>
    </section>

    <?php /* ——— Sélection (bento) ——— */ ?>
    <?php [$a, $b, $c, $d, $f] = array_pad($featured, 5, null); ?>
    <section data-testid="featured-section" class="max-w-[1280px] mx-auto px-6 lg:px-12 py-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
            <?= View::partial('partials/section-heading', ['eyebrow' => 'Sélection', 'titleHtml' => 'Les pièces du <span class="text-gradient">moment</span>', 'text' => "Une sélection courte : chaque modèle n'existe qu'en quelques exemplaires."]) ?>
            <div data-reveal style="--delay: .1s">
                <a href="<?= e(url('/boutique')) ?>" data-testid="featured-view-all-link" class="btn-pill btn-ghost">Toute la boutique <?= icon('arrow-up-right', 16) ?></a>
            </div>
        </div>
        <?php if (App\ProductRepository::unavailable()) echo View::partial('partials/catalog-unavailable'); ?>
        <?php if ($featured): ?>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                <?php foreach ([[$a, 0], [$b, .06], [$c, .12], [$d, .18]] as [$p, $delay]): ?>
                    <?php if ($p): ?>
                        <div data-reveal style="--delay: <?= $delay ?>s">
                            <?= View::partial('partials/product-card', ['product' => $p]) ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if ($f):
                    // Autres créations à découvrir : celles qui ne sont ni dans la sélection ni la pièce mise en avant.
                    $shown = array_filter([$a['id'] ?? null, $b['id'] ?? null, $c['id'] ?? null, $d['id'] ?? null, $f['id']]);
                    $more = array_slice(array_values(array_filter($showcase, fn ($p) => !in_array($p['id'], $shown, true))), 0, 4);
                ?>
                    <div data-reveal style="--delay: .1s" class="col-span-2 lg:col-span-4">
                        <div data-testid="featured-wide-card-<?= e($f['id']) ?>" class="grid md:grid-cols-[340px_1fr] bg-[var(--ink)] text-[var(--pearl)] overflow-hidden rounded-[24px] sm:rounded-[32px] shadow-[0_30px_70px_-40px_rgba(20,20,20,0.6)]">
                            <?php /* Colonne photo à la largeur d'une photo portrait : la pièce remplit tout le cadre. */ ?>
                            <a href="<?= e(url('/produit/' . $f['id'])) ?>" class="img-zoom overflow-hidden block aspect-[4/5] md:aspect-auto md:h-full md:min-h-[420px] bg-[var(--luster)]" aria-label="<?= e($f['name']) ?>">
                                <img src="<?= e($f['images'][0] ?? '') ?>" alt="<?= e($f['name']) ?>" loading="lazy" class="h-full w-full object-cover">
                            </a>
                            <div class="p-6 sm:p-10 lg:p-12 flex flex-col justify-between gap-8">
                                <div>
                                    <span class="eyebrow !text-[#f9a8d4]"><?= e($f['tag'] ?: 'Collection') ?></span>
                                    <h3 class="font-display text-2xl sm:text-3xl lg:text-4xl leading-tight mt-4"><a href="<?= e(url('/produit/' . $f['id'])) ?>" class="hover:text-[#f9a8d4] transition-colors"><?= e($f['name']) ?></a></h3>
                                    <p class="text-white/70 mt-3 max-w-md text-sm sm:text-base"><?= e($f['description']) ?></p>
                                    <div class="flex items-center gap-4 mt-5">
                                        <span class="font-mono text-lg"><?= price((int) $f['price']) ?></span>
                                        <a href="<?= e(url('/produit/' . $f['id'])) ?>" class="inline-flex items-center gap-2 text-sm link-underline">Voir la pièce <?= icon('arrow-up-right', 16) ?></a>
                                    </div>
                                </div>
                                <?php if ($more): ?>
                                    <div>
                                        <div class="text-[11px] uppercase tracking-[0.2em] text-white/50 font-semibold">À découvrir aussi</div>
                                        <div class="grid grid-cols-4 gap-2 sm:gap-3 mt-3 max-w-md" data-testid="featured-more">
                                            <?php foreach ($more as $m): ?>
                                                <a href="<?= e(url('/produit/' . $m['id'])) ?>" class="group/mini block" title="<?= e($m['name']) ?>">
                                                    <span class="block aspect-square overflow-hidden rounded-xl sm:rounded-2xl bg-white/10 ring-1 ring-white/10 group-hover/mini:ring-[#f9a8d4] transition">
                                                        <img src="<?= e($m['images'][0] ?? '') ?>" alt="<?= e($m['name']) ?>" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover/mini:scale-110">
                                                    </span>
                                                    <span class="block font-mono text-[10px] sm:text-[11px] text-white/60 mt-1.5 truncate"><?= price((int) $m['price']) ?></span>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>

    <?php /* ——— Vitrine produits ——— */ ?>
    <?= View::partial('partials/vitrine', ['products' => $showcase]) ?>

    <?php /* ——— Sur mesure ——— */ ?>
    <?= View::partial('partials/sur-mesure') ?>

    <?= View::partial('partials/order-steps') ?>
    <?= View::partial('partials/testimonials') ?>
</div>
