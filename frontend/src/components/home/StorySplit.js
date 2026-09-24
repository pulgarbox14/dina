import { Link } from "react-router-dom";
import { ArrowUpRight } from "lucide-react";
import { motion, useScroll, useTransform } from "framer-motion";
import { useRef } from "react";
import { Reveal } from "@/components/Reveal";
import { ARTISAN_PHOTO, BRAND } from "@/lib/config";

export const StorySplit = () => {
  const ref = useRef(null);
  const { scrollYProgress } = useScroll({ target: ref, offset: ["start end", "end start"] });
  const y = useTransform(scrollYProgress, [0, 1], ["-8%", "8%"]);

  return (
    <section ref={ref} data-testid="story-section" className="max-w-[1440px] mx-auto px-6 lg:px-12 py-32 grid lg:grid-cols-12 gap-12 items-center">
      <Reveal className="lg:col-span-5 relative">
        <div className="relative aspect-[4/5] overflow-hidden rounded-t-[999px]">
          <motion.img src={ARTISAN_PHOTO} alt={BRAND.artisan} style={{ y }} className="absolute inset-0 h-[116%] w-full object-cover -top-[8%]" />
        </div>
        <span className="absolute -bottom-6 -right-4 lg:-right-10 bg-white px-6 py-4 shadow-xl font-display text-xl italic rotate-[-4deg]">
          « Perle après perle »
        </span>
      </Reveal>
      <div className="lg:col-span-6 lg:col-start-7">
        <Reveal>
          <span className="eyebrow">L'artisane</span>
          <h2 className="font-display text-4xl sm:text-5xl lg:text-6xl leading-[1.02] tracking-tight mt-5">
            Des mains, un fil, <br /> et beaucoup de patience.
          </h2>
        </Reveal>
        <Reveal delay={0.15}>
          <p className="text-base sm:text-lg text-[var(--ink-2)] mt-8 leading-relaxed max-w-xl">
            {BRAND.artisan} conçoit et tisse elle-même chaque création dans son atelier de {BRAND.city}. Aucune machine : seulement du fil, des perles nacrées sélectionnées une à une et des heures de tissage minutieux.
          </p>
          <p className="text-base text-[var(--ink-2)] mt-5 leading-relaxed max-w-xl">
            Chaque sac est une pièce signée, pensée pour durer et pour être transmise.
          </p>
          <Link to="/artisane" data-testid="story-cta-link" className="btn-pill btn-ghost mt-10">
            Son histoire <ArrowUpRight size={16} />
          </Link>
        </Reveal>
      </div>
    </section>
  );
};
