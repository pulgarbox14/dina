import { Link } from "react-router-dom";
import { ArrowUpRight, Hand, Clock, MapPin, Award } from "lucide-react";
import { motion } from "framer-motion";
import { ARTISAN_PHOTO, BRAND } from "@/lib/config";
import { LineReveal } from "@/components/Reveal";

const ease = [0.16, 1, 0.3, 1];
const fade = (delay) => ({ initial: { opacity: 0, y: 24 }, animate: { opacity: 1, y: 0 }, transition: { duration: 1, ease, delay } });

const Badge = ({ icon: Icon, title, text, className, delay }) => (
  <motion.div {...fade(delay)} className={`absolute bg-white rounded-2xl p-4 pr-6 shadow-[0_24px_60px_-24px_rgba(40,30,20,0.4)] flex items-center gap-3 ${className}`}>
    <span className="h-10 w-10 rounded-xl bg-[var(--luster)] flex items-center justify-center"><Icon size={18} strokeWidth={1.7} /></span>
    <div>
      <div className="font-display text-lg leading-none">{title}</div>
      <div className="text-[11px] text-[var(--ink-3)] mt-1">{text}</div>
    </div>
  </motion.div>
);

export const ArtisanHero = () => (
  <section data-testid="artisan-hero" className="px-3 sm:px-5 pt-[92px]">
    <div className="relative rounded-[32px] sm:rounded-[40px] overflow-hidden min-h-[620px] h-[calc(100svh-110px)] max-h-[860px]" style={{ background: "linear-gradient(160deg,#d9d2c8 0%,#c9c0b4 55%,#bfb4a6 100%)" }}>
      <motion.img
        src={ARTISAN_PHOTO}
        alt={BRAND.artisan}
        initial={{ opacity: 0, y: 30 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 1.6, ease }}
        className="absolute left-1/2 -translate-x-1/2 lg:left-[56%] bottom-0 h-full w-auto max-w-none object-cover object-top"
        style={{ maskImage: "linear-gradient(to right, transparent 0%, black 12%, black 88%, transparent 100%)", WebkitMaskImage: "linear-gradient(to right, transparent 0%, black 12%, black 88%, transparent 100%)" }}
      />
      <div className="absolute inset-x-0 bottom-0 h-1/2 bg-[linear-gradient(to_top,rgba(60,48,36,0.35),transparent)]" />

      <div className="absolute left-6 sm:left-10 lg:left-14 top-[10%] lg:top-[14%] max-w-[90%] lg:max-w-[42%] text-white">
        <motion.span {...fade(0.2)} className="eyebrow !text-white/85 block">L'artisane · Fondatrice</motion.span>
        <h1 className="font-display text-[12vw] sm:text-[8vw] lg:text-[5.2vw] leading-[0.98] tracking-[-0.035em] mt-5 drop-shadow-[0_2px_20px_rgba(0,0,0,0.15)]">
          <LineReveal lines={[BRAND.artisan.split(" ")[0], BRAND.artisan.split(" ")[1], "maître perlière."]} delay={0.35} />
        </h1>
        <motion.p {...fade(0.95)} className="mt-6 max-w-sm text-sm sm:text-base text-white/90 leading-relaxed">
          Elle conçoit, dessine et tisse chaque sac elle-même, dans son atelier de {BRAND.city}.
        </motion.p>
        <motion.div {...fade(1.1)}>
          <Link to="/boutique" data-testid="artisan-cta-boutique" className="btn-pill bg-white text-[var(--ink)] mt-8 hover:-translate-y-0.5">Voir ses créations <ArrowUpRight size={16} /></Link>
        </motion.div>
      </div>

      <Badge icon={Hand} title="100 % main" text="Aucune machine" className="hidden md:flex left-[30%] bottom-[30%]" delay={1.2} />
      <Badge icon={Clock} title="16 à 30 h" text="par pièce tissée" className="hidden md:flex left-[36%] bottom-10" delay={1.35} />
      <Badge icon={Award} title="+200 pièces" text="depuis 2019" className="hidden md:flex right-8 lg:right-12 top-[16%]" delay={1.3} />
      <Badge icon={MapPin} title={BRAND.city.split(",")[0]} text="Atelier & formation" className="hidden md:flex right-10 lg:right-16 bottom-12" delay={1.45} />
    </div>
  </section>
);
