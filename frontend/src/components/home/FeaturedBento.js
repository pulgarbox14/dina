import { Link } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { ArrowUpRight } from "lucide-react";
import { api } from "@/lib/api";
import { ProductCard } from "@/components/ProductCard";
import { Reveal } from "@/components/Reveal";
import { SectionHeading } from "@/components/SectionHeading";

export const FeaturedBento = () => {
  const { data: products = [] } = useQuery({ queryKey: ["products", "featured"], queryFn: () => api.products({ featured: true }) });
  const [a, b, c, d, e, f] = products;

  return (
    <section data-testid="featured-section" className="max-w-[1440px] mx-auto px-6 lg:px-12 py-24">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-16">
        <SectionHeading eyebrow="Sélection" title="Les pièces du moment" text="Une sélection courte : chaque modèle n'existe qu'en quelques exemplaires." />
        <Reveal delay={0.1}>
          <Link to="/boutique" data-testid="featured-view-all-link" className="btn-pill btn-ghost">Toute la boutique <ArrowUpRight size={16} /></Link>
        </Reveal>
      </div>
      {products.length > 0 && (
        <div className="grid grid-cols-1 md:grid-cols-6 gap-6 lg:gap-8">
          <Reveal className="md:col-span-3">{a && <ProductCard product={a} aspect="aspect-[4/5]" />}</Reveal>
          <Reveal className="md:col-span-3" delay={0.1}>{b && <ProductCard product={b} aspect="aspect-[4/5]" />}</Reveal>
          <Reveal className="md:col-span-2" delay={0.05}>{c && <ProductCard product={c} aspect="aspect-[3/4]" />}</Reveal>
          <Reveal className="md:col-span-2" delay={0.1}>{d && <ProductCard product={d} aspect="aspect-[3/4]" />}</Reveal>
          <Reveal className="md:col-span-2" delay={0.15}>{e && <ProductCard product={e} aspect="aspect-[3/4]" />}</Reveal>
          {f && <Reveal className="md:col-span-6" delay={0.1}><WideCard product={f} /></Reveal>}
        </div>
      )}
    </section>
  );
};

const WideCard = ({ product }) => (
  <Link to={`/produit/${product.id}`} data-testid={`featured-wide-card-${product.id}`} className="group grid md:grid-cols-2 bg-[var(--ink)] text-[var(--pearl)] overflow-hidden rounded-[32px] shadow-[0_30px_70px_-40px_rgba(20,20,20,0.6)]">
    <div className="img-zoom overflow-hidden aspect-[4/3] md:aspect-auto md:min-h-[420px]">
      <img src={product.images[0]} alt={product.name} className="h-full w-full object-cover" />
    </div>
    <div className="p-10 lg:p-16 flex flex-col justify-between">
      <span className="eyebrow !text-white/50">{product.tag || "Collection"}</span>
      <div>
        <h3 className="font-display text-4xl lg:text-5xl leading-tight mt-8">{product.name}</h3>
        <p className="text-white/70 mt-4 max-w-md">{product.description}</p>
      </div>
      <span className="mt-10 inline-flex items-center gap-2 text-sm link-underline w-fit">Voir la pièce <ArrowUpRight size={16} /></span>
    </div>
  </Link>
);
