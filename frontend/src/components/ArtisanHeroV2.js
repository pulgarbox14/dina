import { Link } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { motion } from "framer-motion";
import { ArrowLeft, ArrowRight, ShoppingBag } from "lucide-react";
import { useState } from "react";
import { api } from "@/lib/api";
import { ARTISAN_PHOTO, BRAND, GALLERY, formatPrice } from "@/lib/config";
import { LineReveal } from "@/components/Reveal";

const ease = [0.16, 1, 0.3, 1];
const fade = (delay) => ({ initial: { opacity: 0, y: 24 }, animate: { opacity: 1, y: 0 }, transition: { duration: 1, ease, delay } });

const CircleText = () => (
  <svg viewBox="0 0 200 200" className="h-36 w-36 spin-slow" aria-hidden="true">
    <defs><path id="circ" d="M100,100 m-72,0 a72,72 0 1,1 144,0 a72,72 0 1,1 -144,0" /></defs>
    <text className="font-display" fontSize="15" letterSpacing="3.2" fill="#121316">
      <textPath href="#circ">PERLES TISSÉES À LA MAIN · PERLAE ATELIER · DAKAR ·</textPath>
    </text>
  </svg>
);

export const ArtisanHeroV2 = () => {
  const { data: products = [] } = useQuery({ queryKey: ["products", "featured"], queryFn: () => api.products({ featured: true }) });
  const [idx, setIdx] = useState(0);
  const p = products[idx % (products.length || 1)];

  return (
    <section data-testid="artisan-hero-v2" className="pt-[120px] pb-8 bg-white">
      <div className="max-w-[1440px] mx-auto px-6 lg:px-12 relative lg:min-h-[620px]">
        <div className="relative z-10 pt-6 lg:max-w-[64%]">
          <h1 className="font-display font-normal text-5xl sm:text-6xl lg:text-[4.6vw] leading-[1.05] tracking-[-0.03em]">
            <LineReveal lines={[BRAND.artisan + ",", "l'artisane qui tisse", "la lumière à la main."]} delay={0.25} />
          </h1>
          <motion.p {...fade(0.9)} className="mt-6 max-w-sm text-sm text-[var(--ink-2)] leading-relaxed">
            Fondatrice de Perlae Atelier. Chaque sac naît de ses mains, perle après perle, dans son atelier de {BRAND.city}.
          </motion.p>
          <motion.div {...fade(1.05)} className="mt-6">
            <Link to="/boutique" data-testid="artisan-v2-cta-boutique" className="btn-pill btn-dark !h-11">Voir ses créations</Link>
          </motion.div>
          <motion.div {...fade(1.2)} className="mt-10 flex gap-3">
            {[GALLERY.classique, GALLERY.orangeRound].map((s) => (
              <div key={s} className="h-32 w-20 rounded-full overflow-hidden bg-[var(--luster)] shadow-[0_20px_40px_-24px_rgba(20,20,20,0.35)]"><img src={s} alt="" className="h-full w-full object-cover" /></div>
            ))}
          </motion.div>
        </div>

        <motion.div
          initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} transition={{ duration: 1.4, ease, delay: 0.3 }}
          className="relative mt-10 lg:mt-0 lg:absolute lg:left-[42%] lg:top-0 w-full max-w-[420px] xl:max-w-[460px]"
        >
          <div className="aspect-[4/5] rounded-[28px] overflow-hidden shadow-[0_40px_90px_-40px_rgba(40,30,20,0.45)]">
            <img src={ARTISAN_PHOTO} alt={BRAND.artisan} className="h-full w-full object-cover" data-testid="artisan-v2-photo" />
          </div>
          <div className="absolute -left-14 -bottom-10 hidden sm:block"><CircleText /></div>
        </motion.div>

        {p && (
          <motion.div {...fade(1.1)} className="relative mt-10 lg:mt-0 lg:absolute lg:right-12 lg:top-6 w-full max-w-[240px] z-10">
            <div className="card-3d p-5" data-testid="artisan-v2-product-card">
              <div className="font-display text-lg leading-tight">{p.name}</div>
              <div className="flex items-baseline gap-2 mt-1"><span className="font-medium">{formatPrice(p.price)}</span><span className="text-xs text-[var(--ink-3)] line-through">{formatPrice(Math.round(p.price * 1.2))}</span></div>
              <Link to={`/produit/${p.id}`} data-testid="artisan-v2-shop-now" className="btn-pill btn-dark !h-9 !px-4 text-xs mt-3"><ShoppingBag size={13} /> Commander</Link>
              <div className="aspect-[4/5] rounded-2xl overflow-hidden mt-4 bg-[var(--luster)] img-zoom"><img src={p.images[0]} alt={p.name} className="h-full w-full object-cover" /></div>
            </div>
            <div className="flex justify-end gap-2 mt-4">
              <button data-testid="artisan-v2-prev" onClick={() => setIdx((i) => (i - 1 + products.length) % products.length)} className="h-10 w-10 rounded-xl border border-[var(--line)] flex items-center justify-center hover:bg-[var(--bg-elevated)]"><ArrowLeft size={16} /></button>
              <button data-testid="artisan-v2-next" onClick={() => setIdx((i) => (i + 1) % products.length)} className="h-10 w-10 rounded-xl bg-[var(--ink)] text-white flex items-center justify-center"><ArrowRight size={16} /></button>
            </div>
          </motion.div>
        )}
      </div>
    </section>
  );
};
