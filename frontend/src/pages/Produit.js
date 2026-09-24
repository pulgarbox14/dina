import { useState } from "react";
import { Link, useParams } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { Minus, Plus, MessageCircle, ShoppingBag, Box, Image as ImageIcon } from "lucide-react";
import { ProductViewer3D } from "@/components/ProductViewer3D";
import { toast } from "sonner";
import { api } from "@/lib/api";
import { useCart } from "@/context/CartContext";
import { formatPrice, whatsappLink } from "@/lib/config";
import { ProductCard } from "@/components/ProductCard";
import { Reveal } from "@/components/Reveal";
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion";

export default function Produit() {
  const { id } = useParams();
  const { add } = useCart();
  const [qty, setQty] = useState(1);
  const [img, setImg] = useState(0);
  const [mode, setMode] = useState("photo");
  const { data: p, isLoading } = useQuery({ queryKey: ["product", id], queryFn: () => api.product(id) });
  const { data: related = [] } = useQuery({ queryKey: ["products", "tous"], queryFn: () => api.products({}) });

  if (isLoading) return <div className="pt-40 px-6 text-center text-[var(--ink-3)]">Chargement…</div>;
  if (!p) return <div className="pt-40 px-6 text-center" data-testid="product-not-found">Produit introuvable.</div>;

  const addToCart = () => {
    add(p, qty);
    toast.success(`${p.name} ajouté au panier`);
  };
  const wa = whatsappLink(`Bonjour, je souhaite commander « ${p.name} » (${formatPrice(p.price)}) x${qty}.`);

  return (
    <div data-testid="product-page" className="pt-32">
      <div className="max-w-[1440px] mx-auto px-6 lg:px-12">
        <nav className="text-xs text-[var(--ink-3)] flex gap-2 mb-10" data-testid="breadcrumbs">
          <Link to="/" className="hover:text-[var(--ink)]">Accueil</Link><span>/</span>
          <Link to="/boutique" className="hover:text-[var(--ink)]">Boutique</Link><span>/</span>
          <span className="text-[var(--ink)]">{p.name}</span>
        </nav>
        <div className="grid lg:grid-cols-12 gap-12">
          <Reveal className="lg:col-span-7">
            <div className="relative">
              {mode === "photo" ? (
                <div className="aspect-[4/5] overflow-hidden rounded-[28px] bg-[var(--luster)]">
                  <img data-testid="product-main-image" src={p.images[img]} alt={p.name} className="h-full w-full object-cover" />
                </div>
              ) : (
                <ProductViewer3D product={p} />
              )}
              <div className="absolute top-5 right-5 glass rounded-full p-1 flex gap-1" data-testid="product-view-toggle">
                <button data-testid="view-mode-photo" onClick={() => setMode("photo")} className={`h-9 px-4 rounded-full text-xs font-medium inline-flex items-center gap-2 transition-colors ${mode === "photo" ? "bg-[var(--ink)] text-white" : "hover:bg-white"}`}><ImageIcon size={13} /> Photo</button>
                <button data-testid="view-mode-3d" onClick={() => setMode("3d")} className={`h-9 px-4 rounded-full text-xs font-medium inline-flex items-center gap-2 transition-colors ${mode === "3d" ? "bg-[var(--ink)] text-white" : "hover:bg-white"}`}><Box size={13} /> Vue 3D</button>
              </div>
            </div>
            {p.images.length > 1 && mode === "photo" && (
              <div className="flex gap-3 mt-4">
                {p.images.map((src, i) => (
                  <button key={src} data-testid={`product-thumb-${i}`} onClick={() => setImg(i)} className={`h-24 w-20 overflow-hidden border ${img === i ? "border-[var(--ink)]" : "border-transparent"}`}>
                    <img src={src} alt="" className="h-full w-full object-cover" />
                  </button>
                ))}
              </div>
            )}
          </Reveal>
          <Reveal delay={0.1} className="lg:col-span-5 lg:sticky lg:top-32 self-start">
            {p.tag && <span className="eyebrow">{p.tag}</span>}
            <h1 data-testid="product-name" className="font-display text-5xl lg:text-6xl leading-[1] tracking-tight mt-3">{p.name}</h1>
            <p className="text-[var(--ink-2)] mt-3">{p.subtitle}</p>
            <div data-testid="product-price" className="font-mono text-2xl mt-8">{formatPrice(p.price)}</div>
            <p className="text-base text-[var(--ink-2)] leading-relaxed mt-8">{p.description}</p>

            <dl className="grid grid-cols-3 gap-4 mt-10 text-sm border-y border-[var(--line)] py-6">
              <div><dt className="eyebrow">Dimensions</dt><dd className="mt-2">{p.dimensions}</dd></div>
              <div><dt className="eyebrow">Tissage</dt><dd className="mt-2">{p.weaving_hours} h de travail</dd></div>
              <div><dt className="eyebrow">Accent</dt><dd className="mt-2">{p.accent}</dd></div>
            </dl>

            <div className="flex flex-wrap items-center gap-4 mt-10">
              <div className="inline-flex items-center border border-[var(--line)] rounded-full h-12">
                <button data-testid="qty-minus-button" onClick={() => setQty((q) => Math.max(1, q - 1))} className="h-12 w-12 flex items-center justify-center"><Minus size={14} /></button>
                <span data-testid="qty-value" className="w-8 text-center">{qty}</span>
                <button data-testid="qty-plus-button" onClick={() => setQty((q) => q + 1)} className="h-12 w-12 flex items-center justify-center"><Plus size={14} /></button>
              </div>
              <button data-testid="add-to-cart-button" onClick={addToCart} className="btn-pill btn-dark flex-1 min-w-[200px]">
                <ShoppingBag size={16} /> Ajouter au panier
              </button>
            </div>
            <a href={wa} target="_blank" rel="noreferrer" data-testid="whatsapp-order-button" className="btn-pill btn-ghost w-full mt-4">
              <MessageCircle size={16} /> Commander sur WhatsApp
            </a>

            <Accordion type="single" collapsible className="mt-10">
              <AccordionItem value="care"><AccordionTrigger data-testid="accordion-care">Entretien des perles</AccordionTrigger><AccordionContent>Essuyer avec un chiffon doux et sec. Éviter l'eau, les parfums et l'exposition prolongée au soleil. Ranger dans sa pochette en tissu.</AccordionContent></AccordionItem>
              <AccordionItem value="ship"><AccordionTrigger data-testid="accordion-shipping">Livraison & délais</AccordionTrigger><AccordionContent>Dakar : 24 à 48 h. Afrique de l'Ouest : 3 à 7 jours. International : 7 à 14 jours. Les pièces sur mesure demandent 2 à 3 semaines.</AccordionContent></AccordionItem>
              <AccordionItem value="custom"><AccordionTrigger data-testid="accordion-custom">Sur mesure</AccordionTrigger><AccordionContent>Couleur des fleurs, dimensions, initiales : chaque pièce peut être adaptée. Écrivez-nous sur WhatsApp avec votre idée.</AccordionContent></AccordionItem>
            </Accordion>
          </Reveal>
        </div>

        <section className="mt-32">
          <h2 className="font-display text-4xl mb-10">Vous aimerez aussi</h2>
          <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-8" data-testid="related-products">
            {related.filter((r) => r.id !== p.id).slice(0, 4).map((r) => <ProductCard key={r.id} product={r} />)}
          </div>
        </section>
      </div>
    </div>
  );
}
