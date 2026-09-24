import { Reveal } from "@/components/Reveal";
import { TiltCard } from "@/components/TiltCard";
import { SectionHeading } from "@/components/SectionHeading";

const STEPS = [
  { n: "01", t: "Sélection des perles", d: "Chaque perle nacrée est triée à la main : calibre, brillance, régularité." },
  { n: "02", t: "Dessin du motif", d: "Les fleurs orange, violettes ou bleues sont placées sur une grille avant le tissage." },
  { n: "03", t: "Tissage", d: "Entre 14 et 30 heures de tissage au fil renforcé, sans colle ni machine." },
  { n: "04", t: "Finitions", d: "Anses, doublure, fermoir : les détails qui font durer un sac des années." },
];

export const Process = () => (
  <section data-testid="process-section" className="px-3 sm:px-5 py-8">
    <div className="rounded-[40px] bg-[var(--bg-elevated)] py-28 px-6 lg:px-16">
      <SectionHeading eyebrow="Savoir-faire" title="Quatre gestes, une pièce unique." />
      <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mt-16">
        {STEPS.map((s, i) => (
          <Reveal key={s.n} delay={i * 0.1}>
            <TiltCard className="card-3d p-9 min-h-[300px] flex flex-col justify-between group">
              <span className="font-mono text-xs text-[var(--ink-3)]">{s.n}</span>
              <div>
                <div className="pearl-dot h-5 w-5 rounded-full mb-6 group-hover:scale-125 transition-transform duration-500" />
                <h3 className="font-display text-2xl leading-tight">{s.t}</h3>
                <p className="text-sm text-[var(--ink-2)] mt-4 leading-relaxed">{s.d}</p>
              </div>
            </TiltCard>
          </Reveal>
        ))}
      </div>
    </div>
  </section>
);
