import { Link } from "react-router-dom";
import { Plus } from "lucide-react";
import { toast } from "sonner";
import { TiltCard } from "@/components/TiltCard";
import { useCart } from "@/context/CartContext";
import { formatPrice } from "@/lib/config";

export const ProductCard = ({ product, aspect = "aspect-[4/5]" }) => {
  const { add } = useCart();
  const quickAdd = (e) => {
    e.preventDefault();
    add(product);
    toast.success(`${product.name} ajouté au panier`);
  };

  return (
    <TiltCard className="group card-3d p-3" data-testid={`product-card-${product.id}`}>
      <Link to={`/produit/${product.id}`} className="block">
        <div className={`relative overflow-hidden rounded-[22px] ${aspect} img-zoom bg-[var(--luster)]`}>
          <img src={product.images[0]} alt={product.name} loading="lazy" className="h-full w-full object-cover" />
          {product.tag && (
            <span className="absolute top-4 left-4 bg-white/85 backdrop-blur px-3 py-1 rounded-full text-[10px] uppercase tracking-[0.2em]">
              {product.tag}
            </span>
          )}
          <button
            data-testid={`quick-add-${product.id}`}
            onClick={quickAdd}
            aria-label="Ajouter au panier"
            className="absolute bottom-4 right-4 h-11 w-11 rounded-full bg-[var(--ink)] text-[var(--pearl)] flex items-center justify-center translate-y-3 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-[transform,opacity] duration-500"
          >
            <Plus size={18} />
          </button>
        </div>
        <div className="pt-5 pb-3 px-2 flex justify-between gap-4">
          <div>
            <h3 className="font-display text-xl leading-tight">{product.name}</h3>
            <p className="text-sm text-[var(--ink-3)] mt-1">{product.subtitle}</p>
          </div>
          <span className="font-mono text-sm mt-1 whitespace-nowrap" data-testid={`product-price-${product.id}`}>{formatPrice(product.price)}</span>
        </div>
      </Link>
    </TiltCard>
  );
};
