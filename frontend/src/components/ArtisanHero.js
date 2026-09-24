import { Link } from "react-router-dom";
import { ArrowUpRight, Hand, Clock, MapPin, Award, Sparkles } from "lucide-react";
import { motion } from "framer-motion";
import { BRAND } from "@/lib/config";
import { LineReveal } from "@/components/Reveal";

const ease = [0.16, 1, 0.3, 1];
const fade = (delay) => ({ initial: { opacity: 0, y: 24 }, animate: { opacity: 1, y: 0 }, transition: { duration: 1, ease, delay } });

const Badge = ({ icon: Icon, title, text, className, delay, rotate = 0, color = "#D4AF37" }) => (
  <motion.div
    initial={{ opacity: 0, y: 30, rotate: rotate - 6 }}
    animate={{ opacity: 1, y: 0, rotate }}
    transition={{ duration: 1.1, ease, delay }}
    className={`absolute card-3d p-4 pr-6 flex items-center gap-3 ${className}`}
  >
    <span className="h-11 w-11 rounded-xl flex items-center justify-center" style={{ background: `${color}22`, color }}><Icon size={20} strokeWidth={1.8} /></span>
    <div>
      <div className="font-display text-lg leading-none">{title}</div>
      <div className="text-[11px] text-[var(--ink-3)] mt-1">{text}</div>
    </div>
  </motion.div>
);

export const ArtisanHero = () => (
  <section data-testid="artisan-hero" className="relative pt-[100px] lg:pt-[120px] overflow-hidden bg-white">
    <div className="flex flex-col-reverse">
    <div className="relative w-full max-w-[1440px] mx-auto px-6 text-center mt-4 lg:mt-8">
      <motion.span {...fade(0.15)} className="inline-flex items-center gap-2 bg-[var(--bg-elevated)] rounded-full px-4 h-8 text-[11px] uppercase tracking-[0.22em] text-[var(--ink-2)]">
        <Sparkles size={12} /> L'artisane · Fondatrice
      </motion.span>
      <h1 className="font-display text-3xl sm:text-5xl lg:text-[4.6vw] leading-[1.08] tracking-[-0.03em] mt-5 max-w-5xl mx-auto">
        <LineReveal lines={[BRAND.artisan, "tisse chaque perle à la main."]} delay={0.3} />
      </h1>
      <motion.p {...fade(0.9)} className="mt-6 max-w-lg mx-auto text-[var(--ink-2)] leading-relaxed">
        Fondatrice de Dina Perles, elle conçoit, dessine et tisse chaque sac elle-même dans son atelier de {BRAND.city}.
      </motion.p>
      <motion.div {...fade(1.05)} className="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
        <Link to="/boutique" data-testid="artisan-cta-boutique" className="btn-pill btn-dark">Voir ses créations <ArrowUpRight size={16} /></Link>
        <Link to="/contact" data-testid="artisan-cta-contact-hero" className="btn-pill btn-ghost">Commander sur mesure</Link>
      </motion.div>
    </div>

    <div className="relative mt-0 h-[400px] sm:h-[520px] lg:h-[600px] w-full max-w-[1440px] mx-auto flex items-end justify-center">
      <motion.img
        src="/artisan-cutout.png"
        alt={BRAND.artisan}
        initial={{ opacity: 0, y: 60 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 1.5, ease, delay: 0.5 }}
        className="max-h-full max-w-full w-auto object-contain object-bottom drop-shadow-[0_40px_80px_rgba(20,20,20,0.18)]"
        style={{ maskImage: "linear-gradient(to right, transparent 0%, black 9%, black 100%)", WebkitMaskImage: "linear-gradient(to right, transparent 0%, black 9%, black 100%)" }}
        data-testid="artisan-cutout"
      />
      <div className="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-white via-white/70 to-transparent" />

      <Badge icon={Hand} title="100 % main" text="Aucune machine" className="hidden md:flex left-[8%] lg:left-[16%] top-[18%]" delay={1.2} rotate={-6} color="#7c3aed" />
      <Badge icon={Clock} title="16 à 30 h" text="par pièce tissée" className="hidden md:flex left-[4%] lg:left-[12%] bottom-[22%]" delay={1.35} rotate={4} color="#ea580c" />
      <Badge icon={Award} title="+200 pièces" text="depuis 2019" className="hidden md:flex right-[8%] lg:right-[16%] top-[14%]" delay={1.3} rotate={5} color="#D4AF37" />
      <Badge icon={MapPin} title={BRAND.city.split(",")[0]} text="Atelier & formation" className="hidden md:flex right-[4%] lg:right-[12%] bottom-[26%]" delay={1.45} rotate={-4} color="#0ea5e9" />
    </div>
    </div>

    <div className="relative max-w-[1100px] mx-auto px-6 lg:px-12 mt-14 pb-6">
      <div className="grid grid-cols-3 gap-3 sm:gap-5">
        {[["+200", "pièces créées", Award, "#D4AF37"], ["6 ans", "d'atelier", Clock, "#ea580c"], ["3", "pays livrés", MapPin, "#0ea5e9"]].map(([n, l, Icon, color], i) => (
          <motion.div key={l} {...fade(1.4 + i * 0.1)} className="card-3d p-5 sm:p-7 flex flex-col items-center text-center gap-3">
            <span className="h-10 w-10 rounded-full flex items-center justify-center" style={{ background: `${color}22`, color }}><Icon size={18} strokeWidth={1.8} /></span>
            <div className="font-display text-2xl sm:text-5xl leading-none whitespace-nowrap">{n}</div>
            <div className="eyebrow">{l}</div>
          </motion.div>
        ))}
      </div>
    </div>
  </section>
);
