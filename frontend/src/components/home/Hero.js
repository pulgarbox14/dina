import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowDown, ArrowUpRight } from "lucide-react";
import { PearlBag3D } from "@/components/PearlBag3D";
import { LineReveal } from "@/components/Reveal";

const ease = [0.16, 1, 0.3, 1];

export const Hero = () => (
  <section data-testid="hero-section" className="relative min-h-[100svh] overflow-hidden" style={{ background: "linear-gradient(180deg,#FFFFFF 0%,#FAFAFA 60%,#F3F1EC 100%)" }}>
    <div className="absolute inset-0 lg:left-[42%]">
      <PearlBag3D />
    </div>
    <div className="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_70%_50%,rgba(212,175,55,0.10),transparent_55%)]" />
    <div className="relative max-w-[1440px] mx-auto px-6 lg:px-12 pt-40 lg:pt-52 pb-24 min-h-[100svh] flex flex-col justify-between">
      <div>
        <motion.span className="eyebrow block" initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 0.2, duration: 1 }}>
          Haute perlerie · Dakar
        </motion.span>
        <h1 className="font-display font-light text-[13vw] sm:text-[9vw] lg:text-[6.6vw] leading-[0.95] tracking-[-0.02em] mt-6 lg:max-w-[55%]">
          <LineReveal lines={["L'art du sac", "en perles,", <em key="e" className="font-normal">tissé à la main.</em>]} delay={0.3} />
        </h1>
        <motion.p
          className="mt-10 max-w-md text-base sm:text-lg text-[var(--ink-2)] leading-relaxed"
          initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 1, duration: 1, ease }}
        >
          Des pièces uniques, perle après perle. Sacs, pochettes et parures façonnés dans notre atelier, pour celles qui portent la lumière.
        </motion.p>
        <motion.div className="mt-10 flex flex-wrap gap-4" initial={{ opacity: 0, y: 20 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 1.15, duration: 1, ease }}>
          <Link to="/boutique" data-testid="hero-cta-explore-button" className="btn-pill btn-dark">
            Découvrir la boutique <ArrowUpRight size={16} />
          </Link>
          <Link to="/artisane" data-testid="hero-cta-artisan-button" className="btn-pill btn-ghost">
            Rencontrer l'artisane
          </Link>
        </motion.div>
      </div>
      <motion.div className="flex items-end justify-between mt-16" initial={{ opacity: 0 }} animate={{ opacity: 1 }} transition={{ delay: 1.6, duration: 1 }}>
        <div className="flex items-center gap-3 text-xs uppercase tracking-[0.25em] text-[var(--ink-3)]">
          <ArrowDown size={14} className="animate-bounce" /> Faire défiler
        </div>
        <div className="hidden sm:flex gap-12 text-right">
          {[["+1 200", "perles par sac"], ["16 h", "de tissage"], ["1", "seul exemplaire"]].map(([n, l]) => (
            <div key={l}>
              <div className="font-display text-4xl leading-none">{n}</div>
              <div className="eyebrow mt-2">{l}</div>
            </div>
          ))}
        </div>
      </motion.div>
    </div>
  </section>
);
