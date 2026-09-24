import { Link } from "react-router-dom";
import { ArrowUpRight } from "lucide-react";
import { Reveal } from "@/components/Reveal";
import { SectionHeading } from "@/components/SectionHeading";
import { Marquee } from "@/components/Marquee";
import { TiltCard } from "@/components/TiltCard";
import { GALLERY } from "@/lib/config";

const VALUES = [
  { t: "Tout à la main", d: "Aucune machine n'intervient. Le fil, l'aiguille et des milliers de gestes répétés." },
  { t: "Une pièce, une personne", d: "Aïssatou tisse chaque commande elle-même, du premier nœud aux finitions." },
  { t: "Transmission", d: "Elle forme aujourd'hui de jeunes femmes de son quartier au tissage de perles." },
];

export const ArtisanBody = () => (
  <>
    <section className="max-w-[1440px] mx-auto px-6 lg:px-12 pt-24 grid lg:grid-cols-12 gap-12">
      <Reveal className="lg:col-span-6">
        <div className="grid grid-cols-2 gap-4">
          <TiltCard className="aspect-[3/4] overflow-hidden rounded-[28px] img-zoom bg-[var(--luster)] shadow-[0_30px_60px_-36px_rgba(20,20,20,0.4)]"><img src={GALLERY.lune} alt="Sac Lune Nacre" className="h-full w-full object-cover" data-testid="artisan-portrait" /></TiltCard>
          <TiltCard className="aspect-[3/4] overflow-hidden rounded-[28px] img-zoom bg-[var(--luster)] mt-10 shadow-[0_30px_60px_-36px_rgba(20,20,20,0.4)]"><img src={GALLERY.orangeTote} alt="Panier Soleil" className="h-full w-full object-cover" /></TiltCard>
        </div>
      </Reveal>
      <div className="lg:col-span-5 lg:col-start-8 lg:pt-12 space-y-8">
        <Reveal>
          <p className="font-display text-2xl sm:text-3xl leading-snug">« J'ai commencé avec un sachet de perles et une idée : faire un sac que personne n'avait jamais vu. »</p>
        </Reveal>
        <Reveal delay={0.1}>
          <p className="text-[var(--ink-2)] leading-relaxed">Autodidacte, Aïssatou a appris le tissage de perles en observant sa grand-mère confectionner des colliers de cérémonie. Elle a transposé ce geste ancestral au sac à main, en inventant ses propres motifs : la fleur orange, la fleur d'améthyste, la trame lune.</p>
          <p className="text-[var(--ink-2)] leading-relaxed mt-5">Aujourd'hui, ses créations voyagent de Dakar à Abidjan, Paris et Montréal. Chaque pièce porte sa signature discrète : une perle dorée cousue à l'intérieur.</p>
        </Reveal>
        <Reveal delay={0.15}>
          <Link to="/contact" data-testid="artisan-cta-contact" className="btn-pill btn-dark">Commander sur mesure <ArrowUpRight size={16} /></Link>
        </Reveal>
      </div>
    </section>

    <section className="max-w-[1440px] mx-auto px-6 lg:px-12 py-28">
      <SectionHeading eyebrow="Journal d'atelier" title="Quelques pièces sorties de ses mains" />
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-14" data-testid="atelier-gallery">
        {[GALLERY.classique, GALLERY.orangeRound, GALLERY.whiteSet, GALLERY.purple, GALLERY.amberPlate, GALLERY.trio, GALLERY.orangeTote, GALLERY.ringSet].map((src, i) => (
          <Reveal key={src} delay={(i % 4) * 0.08} className={`img-zoom overflow-hidden rounded-[24px] bg-[var(--luster)] ${i % 3 === 0 ? "row-span-2 aspect-[3/5]" : "aspect-[3/4]"}`}>
            <img src={src} alt="Création Perlae Atelier" loading="lazy" className="h-full w-full object-cover" />
          </Reveal>
        ))}
      </div>
    </section>

    <Marquee />

    <section className="max-w-[1440px] mx-auto px-6 lg:px-12 py-28 grid lg:grid-cols-3 gap-6">
      {VALUES.map((v, i) => (
        <Reveal key={v.t} delay={i * 0.1}>
          <TiltCard className="card-3d p-9 h-full">
            <span className="font-mono text-xs text-[var(--ink-3)]">0{i + 1}</span>
            <h3 className="font-display text-2xl mt-4">{v.t}</h3>
            <p className="text-[var(--ink-2)] mt-4 leading-relaxed text-sm">{v.d}</p>
          </TiltCard>
        </Reveal>
      ))}
    </section>
  </>
);
