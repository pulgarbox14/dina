import { useState } from "react";
import { Link } from "react-router-dom";
import { ArrowUpRight } from "lucide-react";
import { Reveal } from "@/components/Reveal";
import { PearlScene } from "@/components/three/PearlScene";

const OPTIONS = [
  { l: "Nacre blanche", c: null },
  { l: "Fleur orange", c: "#f97316" },
  { l: "Fleur violette", c: "#a855f7" },
  { l: "Bleu lagon", c: "#60a5fa" },
];

export const Atelier3D = () => {
  const [accent, setAccent] = useState("#f97316");
  const [shape, setShape] = useState("round");
  return (
    <section data-testid="atelier-3d-section" className="max-w-[1440px] mx-auto px-6 lg:px-12 py-28 grid lg:grid-cols-12 gap-12 items-center">
      <Reveal className="lg:col-span-7 relative aspect-[5/4] rounded-[36px] overflow-hidden bg-[radial-gradient(ellipse_at_50%_45%,#ffffff_0%,#f3f3f5_65%,#e7e7eb_100%)]">
        <PearlScene shape={shape} accent={accent} camera={shape === "tote" ? 7.4 : 7} testId="atelier-3d-canvas" />
        <div className="absolute top-6 left-6 glass rounded-full px-4 h-9 flex items-center text-xs font-medium">Interactif · glissez pour tourner</div>
      </Reveal>
      <div className="lg:col-span-5">
        <Reveal>
          <span className="eyebrow">Composez votre pièce</span>
          <h2 className="font-display text-4xl sm:text-5xl leading-[1.02] tracking-tight mt-4">Choisissez la forme, choisissez la fleur.</h2>
          <p className="text-[var(--ink-2)] mt-6 leading-relaxed">Chaque sac est tissé à la commande. Jouez avec les formes et les accents de couleur, puis demandez votre modèle sur mesure.</p>
        </Reveal>
        <Reveal delay={0.1} className="mt-8">
          <div className="eyebrow mb-3">Forme</div>
          <div className="flex gap-2" data-testid="atelier-shape-options">
            {[["round", "Arrondi"], ["tote", "Cabas"], ["necklace", "Parure"]].map(([s, l]) => (
              <button key={s} data-testid={`atelier-shape-${s}`} onClick={() => setShape(s)} className={`rounded-full px-5 h-10 text-sm border transition-colors ${shape === s ? "bg-[var(--ink)] text-white border-[var(--ink)]" : "border-[var(--line)] hover:border-[var(--ink)]"}`}>{l}</button>
            ))}
          </div>
          <div className="eyebrow mb-3 mt-8">Accent</div>
          <div className="flex flex-wrap gap-2" data-testid="atelier-accent-options">
            {OPTIONS.map((o) => (
              <button key={o.l} data-testid={`atelier-accent-${o.l.split(" ")[1] || "blanc"}`} onClick={() => setAccent(o.c)} className={`inline-flex items-center gap-2 rounded-full pl-2 pr-4 h-10 text-sm border transition-colors ${accent === o.c ? "border-[var(--ink)]" : "border-[var(--line)] hover:border-[var(--ink)]"}`}>
                <span className="h-6 w-6 rounded-full border border-white shadow" style={{ background: o.c || "linear-gradient(135deg,#fff,#e6e2da)" }} />
                {o.l}
              </button>
            ))}
          </div>
        </Reveal>
        <Reveal delay={0.15}>
          <Link to="/contact" data-testid="atelier-custom-cta" className="btn-pill btn-dark mt-10">Demander ce modèle <ArrowUpRight size={16} /></Link>
        </Reveal>
      </div>
    </section>
  );
};
