import { Link } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { motion } from "framer-motion";
import { ArrowUpRight, ShoppingBag, Sparkles } from "lucide-react";
import { toast } from "sonner";
import { api } from "@/lib/api";
import { useCart } from "@/context/CartContext";
import { formatPrice, GALLERY } from "@/lib/config";
import { LineReveal } from "@/components/Reveal";

const ease = [0.16, 1, 0.3, 1];
const fade = (delay) => ({ initial: { opacity: 0, y: 24 }, animate: { opacity: 1, y: 0 }, transition: { duration: 1, ease, delay } });

const MiniCard = ({ product, delay }) => (
  <motion.div {...fade(delay)} className="w-[168px] shrink-0 card-3d p-3" data-testid={`hero-mini-card-${product.id}`}>
    <Link to={`/produit/${product.id}`} className="block">
      <div className="aspect-square rounded-xl overflow-hidden bg-[var(--luster)] img-zoom">
        <img src={product.images[0]} alt={product.name} className="h-full w-full object-cover" />
      </div>
      <div className="font-display text-lg mt-3 leading-none">{formatPrice(product.price)}</div>
      <div className="text-[12px] text-[var(--ink-2)] mt-1 leading-snug line-clamp-2 h-8">{product.name}</div>
      <div className="flex items-center justify-between mt-2">
        <span className="text-[10px] text-[var(--ink-3)]">{product.weaving_hours} h de tissage</span>
        <span className="h-7 px-3 rounded-full bg-[var(--ink)] text-white text-[11px] flex items-center">Voir</span>
      </div>
    </Link>
  </motion.div>
);

export const HeroEditorial = () => {
  const { data: products = [] } = useQuery({ queryKey: ["products", "featured"], queryFn: () => api.products({ featured: true }) });
  const { add } = useCart();
  const heroProduct = products.find((p) => p.id === "sac-lune-nacre") || products[0];
  const minis = products.filter((p) => p.id !== heroProduct?.id).slice(0, 3);

  return (
    <section data-testid="hero-section" className="px-3 sm:px-5 pt-[92px]">
      <div className="relative rounded-[32px] sm:rounded-[40px] overflow-hidden min-h-[860px] sm:min-h-[640px] h-[calc(100svh-110px)] max-h-[900px] bg-[var(--bg-elevated)] border border-[var(--line)]">
        <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_60%_100%,#ffffff_0%,transparent_60%)]" />
        <motion.img
          src="/artisan-cutout.png"
          alt="L'artisane et son sac en perles"
          initial={{ opacity: 0, y: 40 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1.6, ease }}
          className="absolute right-[-8%] sm:right-[4%] lg:right-[24%] bottom-0 h-[40%] sm:h-[80%] lg:h-[96%] w-auto max-w-none object-contain object-bottom drop-shadow-[0_40px_80px_rgba(20,20,20,0.18)]"
          style={{ maskImage: "linear-gradient(to right, transparent 0%, black 9%, black 100%)", WebkitMaskImage: "linear-gradient(to right, transparent 0%, black 9%, black 100%)" }}
        />
        <div className="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-[var(--bg-elevated)] via-[var(--bg-elevated)]/70 to-transparent" />

        <div className="absolute left-6 sm:left-10 lg:left-14 top-[7%] lg:top-[12%] max-w-[92%] lg:max-w-[46%] text-[var(--ink)]">
          <motion.span {...fade(0.2)} className="inline-flex items-center gap-2 bg-white border border-[var(--line)] rounded-full px-4 h-8 text-[11px] uppercase tracking-[0.22em] text-[var(--ink-2)]">
            <Sparkles size={12} /> Haute perlerie · Dakar
          </motion.span>
          <h1 className="font-display text-[12vw] sm:text-[8vw] lg:text-[4.9vw] leading-[0.98] tracking-[-0.035em] mt-6">
            <LineReveal lines={["Portez la", "lumière, perle", "après perle."]} delay={0.35} />
          </h1>
          <motion.p {...fade(0.95)} className="mt-6 max-w-[78%] sm:max-w-sm text-sm sm:text-base text-[var(--ink-2)] leading-relaxed">
            Sacs et parures en perles nacrées, entièrement tissés à la main dans notre atelier. Une seule pièce par modèle.
          </motion.p>
          <motion.div {...fade(1.1)} className="mt-8 flex flex-wrap gap-3">
            <Link to="/boutique" data-testid="hero-cta-explore-button" className="btn-pill btn-dark">
              Découvrir la boutique <ArrowUpRight size={16} />
            </Link>
            <Link to="/artisane" data-testid="hero-cta-artisan-button" className="btn-pill btn-ghost bg-white">
              Rencontrer l'artisane
            </Link>
          </motion.div>
        </div>

        <motion.div {...fade(1.2)} className="hidden md:flex absolute right-8 lg:right-12 top-[14%] flex-col items-end gap-3" data-testid="hero-social-proof">
          <div className="flex -space-x-3">
            {[GALLERY.orangeRound, GALLERY.purple, GALLERY.amber].map((s) => (
              <img key={s} src={s} alt="" className="h-12 w-12 rounded-full object-cover border-2 border-white shadow-lg" />
            ))}
          </div>
          <div className="font-display text-4xl leading-none">+200</div>
          <div className="text-[11px] uppercase tracking-[0.2em] text-[var(--ink-3)]">pièces confiées à leurs propriétaires</div>
        </motion.div>

        {heroProduct && (
          <motion.div {...fade(1.35)} className="hidden md:block absolute right-8 lg:right-12 top-[38%] text-right" data-testid="hero-featured-product">
            <div className="text-xs text-[var(--ink-3)] max-w-[220px] ml-auto">{heroProduct.name} · {heroProduct.subtitle}</div>
            <button
              data-testid="hero-add-to-cart-button"
              onClick={() => { add(heroProduct); toast.success(`${heroProduct.name} ajouté au panier`); }}
              className="mt-3 inline-flex items-center gap-3 bg-white border border-[var(--line)] text-[var(--ink)] rounded-full pl-5 pr-1.5 h-12 text-sm font-medium shadow-[0_20px_40px_-24px_rgba(20,20,20,0.35)] hover:-translate-y-0.5 transition-transform"
            >
              Ajouter au panier · {formatPrice(heroProduct.price)}
              <span className="h-9 w-9 rounded-full bg-[var(--ink)] text-white flex items-center justify-center"><ShoppingBag size={15} /></span>
            </button>
          </motion.div>
        )}

        <div className="hidden lg:flex absolute right-8 lg:right-12 bottom-8 gap-4" data-testid="hero-mini-cards">
          {minis.map((p, i) => <MiniCard key={p.id} product={p} delay={1.3 + i * 0.12} />)}
        </div>
      </div>

      <div className="lg:hidden flex gap-4 overflow-x-auto px-3 py-6 -mx-3" data-testid="hero-mini-cards-mobile">
        {minis.map((p, i) => <MiniCard key={p.id} product={p} delay={0.2 + i * 0.1} />)}
      </div>
    </section>
  );
};
