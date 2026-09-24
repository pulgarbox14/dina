import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { api } from "@/lib/api";
import { ProductCard } from "@/components/ProductCard";
import { Reveal } from "@/components/Reveal";
import { PageHero } from "@/components/SectionHeading";
import { Marquee } from "@/components/Marquee";

const CATS = [
  { id: "tous", label: "Tout" },
  { id: "sacs", label: "Sacs" },
  { id: "minis", label: "Mini-sacs" },
  { id: "bijoux", label: "Parures & bijoux" },
];

export default function Boutique() {
  const [cat, setCat] = useState("tous");
  const { data: products = [], isLoading } = useQuery({ queryKey: ["products", cat], queryFn: () => api.products({ category: cat }) });

  return (
    <div data-testid="boutique-page">
      <PageHero eyebrow="Boutique" title="Pièces uniques, tissées pour vous." text="Chaque création est fabriquée à la main dans notre atelier. Les quantités sont volontairement limitées.">
        <div className="flex flex-wrap gap-3 mt-12" data-testid="category-filter">
          {CATS.map((c) => (
            <button
              key={c.id}
              data-testid={`category-filter-${c.id}`}
              onClick={() => setCat(c.id)}
              className={`rounded-full px-5 h-10 text-sm border transition-colors duration-300 ${cat === c.id ? "bg-[var(--ink)] text-[var(--pearl)] border-[var(--ink)]" : "border-[var(--line)] hover:border-[var(--ink)]"}`}
            >
              {c.label}
            </button>
          ))}
        </div>
      </PageHero>
      <section className="max-w-[1440px] mx-auto px-6 lg:px-12 pb-24">
        <div className="flex justify-between text-xs text-[var(--ink-3)] uppercase tracking-[0.2em] mb-8 border-b border-[var(--line)] pb-4">
          <span data-testid="products-count">{isLoading ? "Chargement…" : `${products.length} création${products.length > 1 ? "s" : ""}`}</span>
          <span>Prix en FCFA</span>
        </div>
        <div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14" data-testid="products-grid">
          {products.map((p, i) => (
            <Reveal key={p.id} delay={(i % 3) * 0.08}>
              <ProductCard product={p} />
            </Reveal>
          ))}
        </div>
      </section>
      <Marquee dark />
    </div>
  );
}
