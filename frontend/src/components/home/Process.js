import { Reveal } from "@/components/Reveal";
import { SectionHeading } from "@/components/SectionHeading";

const STEPS = [
  { n: "01", t: "Sélection des perles", d: "Chaque perle nacrée est triée à la main : calibre, brillance, régularité." },
  { n: "02", t: "Dessin du motif", d: "Les fleurs orange, violettes ou bleues sont placées sur une grille avant le tissage." },
  { n: "03", t: "Tissage", d: "Entre 14 et 30 heures de tissage au fil renforcé, sans colle ni machine." },
  { n: "04", t: "Finitions", d: "Anses, doublure, fermoir : les détails qui font durer un sac des années." },
];

export const Process = () => (
  <section data-testid="process-section" className="bg-[var(--bg-elevated)] py-32">
    <div className="max-w-[1440px] mx-auto px-6 lg:px-12">
      <SectionHeading eyebrow="Savoir-faire" title="Quatre gestes, une pièce unique." />
      <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-px bg-[var(--line)] mt-16 border border-[var(--line)]">
        {STEPS.map((s, i) => (
          <Reveal key={s.n} delay={i * 0.1} className="bg-[var(--bg-elevated)] p-10 min-h-[300px] flex flex-col justify-between group hover:bg-white transition-colors duration-500">
            <span className="font-mono text-xs text-[var(--ink-3)]">{s.n}</span>
            <div>
              <div className="pearl-dot h-5 w-5 rounded-full mb-6 group-hover:scale-125 transition-transform duration-500" />
              <h3 className="font-display text-3xl leading-tight">{s.t}</h3>
              <p className="text-sm text-[var(--ink-2)] mt-4 leading-relaxed">{s.d}</p>
            </div>
          </Reveal>
        ))}
      </div>
    </div>
  </section>
);
