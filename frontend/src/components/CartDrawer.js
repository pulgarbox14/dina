import { Link } from "react-router-dom";
import { X, Minus, Plus, Trash2 } from "lucide-react";
import { AnimatePresence, motion } from "framer-motion";
import { useCart } from "@/context/CartContext";
import { formatPrice } from "@/lib/config";

export const CartDrawer = () => {
  const { items, total, open, setOpen, setQty, remove } = useCart();
  return (
    <AnimatePresence>
      {open && (
        <>
          <motion.div
            data-testid="cart-drawer-overlay"
            className="fixed inset-0 z-[70] bg-[var(--ink)]/30 backdrop-blur-sm"
            initial={{ opacity: 0 }} animate={{ opacity: 1 }} exit={{ opacity: 0 }}
            onClick={() => setOpen(false)}
          />
          <motion.aside
            data-testid="cart-drawer"
            data-lenis-prevent
            className="fixed right-0 top-0 h-full w-full max-w-md z-[80] bg-[var(--bg)] flex flex-col shadow-2xl"
            initial={{ x: "100%" }} animate={{ x: 0 }} exit={{ x: "100%" }}
            transition={{ type: "spring", damping: 28, stiffness: 240 }}
          >
            <div className="flex items-center justify-between px-8 h-[76px] border-b border-[var(--line)]">
              <span className="font-display text-2xl">Votre panier</span>
              <button data-testid="cart-drawer-close" onClick={() => setOpen(false)} className="h-10 w-10 rounded-full border border-[var(--line)] flex items-center justify-center"><X size={16} /></button>
            </div>
            <div className="flex-1 overflow-y-auto px-8 py-6 space-y-6">
              {items.length === 0 && <p data-testid="cart-empty-message" className="text-[var(--ink-3)] text-sm">Votre panier est vide. Laissez-vous tenter par une pièce unique.</p>}
              {items.map((i) => (
                <div key={i.id} className="flex gap-4" data-testid={`cart-item-${i.id}`}>
                  <img src={i.image} alt={i.name} className="h-24 w-20 object-cover bg-[var(--luster)]" />
                  <div className="flex-1">
                    <div className="flex justify-between gap-2">
                      <span className="font-display text-lg leading-tight">{i.name}</span>
                      <button data-testid={`cart-remove-${i.id}`} onClick={() => remove(i.id)} className="text-[var(--ink-3)] hover:text-[var(--ink)]"><Trash2 size={15} /></button>
                    </div>
                    <span className="font-mono text-xs text-[var(--ink-2)]">{formatPrice(i.price)}</span>
                    <div className="mt-3 inline-flex items-center border border-[var(--line)] rounded-full">
                      <button data-testid={`cart-qty-minus-${i.id}`} onClick={() => setQty(i.id, i.qty - 1)} className="h-8 w-8 flex items-center justify-center"><Minus size={12} /></button>
                      <span data-testid={`cart-qty-${i.id}`} className="w-6 text-center text-sm">{i.qty}</span>
                      <button data-testid={`cart-qty-plus-${i.id}`} onClick={() => setQty(i.id, i.qty + 1)} className="h-8 w-8 flex items-center justify-center"><Plus size={12} /></button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
            <div className="px-8 py-6 border-t border-[var(--line)] space-y-4">
              <div className="flex justify-between text-sm">
                <span className="text-[var(--ink-2)]">Sous-total</span>
                <span className="font-mono" data-testid="cart-drawer-total">{formatPrice(total)}</span>
              </div>
              <Link to="/panier" onClick={() => setOpen(false)} data-testid="cart-drawer-checkout-link" className={`btn-pill btn-dark w-full ${items.length === 0 ? "pointer-events-none opacity-40" : ""}`}>
                Passer commande
              </Link>
            </div>
          </motion.aside>
        </>
      )}
    </AnimatePresence>
  );
};
