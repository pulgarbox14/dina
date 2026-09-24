import { Reveal } from "@/components/Reveal";
import { TiltCard } from "@/components/TiltCard";
import { SectionHeading } from "@/components/SectionHeading";

const REVIEWS = [
  { name: "Fatou N.", city: "Cotonou", text: "Mon sac Lune Nacre a fait sensation au mariage de ma sœur. On m'a demandé dix fois où je l'avais trouvé." },
  { name: "Mariam K.", city: "Abidjan", text: "Le travail est incroyablement régulier. On sent les heures passées dessus. Livraison rapide jusqu'en Côte d'Ivoire." },
  { name: "Claire D.", city: "Paris", text: "Une vraie pièce d'artisanat. Les fleurs orange sont encore plus belles qu'en photo." },
];

export const Testimonials = () => (
  <section data-testid="testimonials-section" className="max-w-[1440px] mx-auto px-6 lg:px-12 py-32">
    <SectionHeading eyebrow="Elles les portent" title="Ce qu'elles en disent" align="center" />
    <div className="grid md:grid-cols-3 gap-6 mt-16">
      {REVIEWS.map((r, i) => (
        <Reveal key={r.name} delay={i * 0.1}>
          <TiltCard className="card-3d p-9 flex flex-col justify-between min-h-[280px]">
          <p className="font-display text-xl leading-snug font-normal">« {r.text} »</p>
          <div className="mt-8 flex items-center gap-3">
            <span className="pearl-dot h-9 w-9 rounded-full" />
            <div>
              <div className="text-sm font-medium">{r.name}</div>
              <div className="text-xs text-[var(--ink-3)]">{r.city} · Achat vérifié</div>
            </div>
          </div>
          </TiltCard>
        </Reveal>
      ))}
    </div>
  </section>
);
