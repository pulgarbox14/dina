import { Reveal } from "@/components/Reveal";
import { PageHero, SectionHeading } from "@/components/SectionHeading";
import { Process } from "@/components/home/Process";
import { GALLERY } from "@/lib/config";

const PILLARS = [
  { t: "Matières", d: "Perles nacrées haute résistance, fil de tissage renforcé, anses doublées. Des matières choisies pour tenir des années, pas une saison." },
  { t: "Production lente", d: "Nous ne produisons pas en stock. Chaque pièce est tissée à la commande ou en très petite série, sans gaspillage." },
  { t: "Impact local", d: "L'atelier est basé à Cotonou et forme de jeunes femmes au tissage. Acheter un sac, c'est financer une formation." },
];

export default function APropos() {
  return (
    <div data-testid="a-propos-page">
      <PageHero eyebrow="À propos" title={<>Une maison de <em className="font-light">haute perlerie</em> née au Bénin.</>} text="Perlae Atelier est une marque d'accessoires faits main : sacs, pochettes et parures en perles nacrées, tissés un par un dans notre atelier." />

      <section className="max-w-[1440px] mx-auto px-6 lg:px-12 grid md:grid-cols-3 gap-4">
        {[GALLERY.lune, GALLERY.amber, GALLERY.orangeTote].map((src, i) => (
          <Reveal key={src} delay={i * 0.1} className={`img-zoom overflow-hidden rounded-[28px] bg-[var(--luster)] ${i === 1 ? "md:mt-16" : ""} aspect-[3/4]`}>
            <img src={src} alt="Création Perlae Atelier" className="h-full w-full object-cover" />
          </Reveal>
        ))}
      </section>

      <section className="max-w-[1440px] mx-auto px-6 lg:px-12 py-32">
        <SectionHeading eyebrow="Nos engagements" title="Ce que nous promettons" />
        <div className="grid md:grid-cols-3 gap-12 mt-16">
          {PILLARS.map((p, i) => (
            <Reveal key={p.t} delay={i * 0.1} className="border-t border-[var(--ink)] pt-8">
              <h3 className="font-display text-3xl">{p.t}</h3>
              <p className="text-[var(--ink-2)] mt-4 leading-relaxed">{p.d}</p>
            </Reveal>
          ))}
        </div>
      </section>

      <Process />

      <section className="max-w-[1440px] mx-auto px-6 lg:px-12 py-32 text-center">
        <Reveal>
          <span className="eyebrow">Perlae</span>
          <p className="font-display text-4xl sm:text-5xl lg:text-6xl leading-tight max-w-4xl mx-auto mt-6">
            « Perlae », du latin <em>perla</em> — la perle. Un nom pour une promesse : la lumière portée à la main.
          </p>
        </Reveal>
      </section>
    </div>
  );
}
