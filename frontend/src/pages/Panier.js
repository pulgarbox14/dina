import { useState } from "react";
import { Link } from "react-router-dom";
import { Minus, Plus, Trash2, MessageCircle, Check } from "lucide-react";
import { toast } from "sonner";
import { useCart } from "@/context/CartContext";
import { formatPrice, whatsappLink } from "@/lib/config";
import { api } from "@/lib/api";
import { Reveal } from "@/components/Reveal";
import { PageHero } from "@/components/SectionHeading";

export default function Panier() {
  const { items, total, setQty, remove, clear } = useCart();
  const [form, setForm] = useState({ customer_name: "", phone: "", email: "", address: "", note: "" });
  const [done, setDone] = useState(null);
  const [loading, setLoading] = useState(false);
  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const waText = () =>
    `Bonjour Dina Perles ! Je souhaite commander :\n${items.map((i) => `• ${i.name} x${i.qty} — ${formatPrice(i.price * i.qty)}`).join("\n")}\nTotal : ${formatPrice(total)}`;

  const submit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      const order = await api.createOrder({ ...form, items: items.map((i) => ({ product_id: i.id, name: i.name, price: i.price, quantity: i.qty })) });
      setDone(order);
      clear();
      toast.success("Commande envoyée ! Nous vous contactons très vite.");
    } catch {
      toast.error("Impossible d'envoyer la commande. Vérifiez vos informations.");
    } finally {
      setLoading(false);
    }
  };

  if (done) {
    return (
      <div data-testid="order-success" className="pt-48 pb-32 px-6 max-w-2xl mx-auto text-center">
        <div className="h-16 w-16 rounded-full bg-[var(--ink)] text-[var(--pearl)] flex items-center justify-center mx-auto"><Check /></div>
        <h1 className="font-display text-5xl mt-8">Merci, {done.customer_name.split(" ")[0]}.</h1>
        <p className="text-[var(--ink-2)] mt-6">Votre commande <span className="font-mono text-sm" data-testid="order-id">#{done.id.slice(0, 8)}</span> d'un montant de <strong>{formatPrice(done.total)}</strong> est bien enregistrée. Secondina vous contactera au {done.phone} pour confirmer la livraison.</p>
        <Link to="/boutique" data-testid="order-success-back-link" className="btn-pill btn-dark mt-10">Retour à la boutique</Link>
      </div>
    );
  }

  return (
    <div data-testid="panier-page">
      <PageHero eyebrow="Panier" title="Votre sélection" />
      <div className="max-w-[1440px] mx-auto px-6 lg:px-12 grid lg:grid-cols-12 gap-16 pb-24">
        <div className="lg:col-span-7">
          {items.length === 0 ? (
            <div data-testid="cart-page-empty" className="border border-dashed border-[var(--line)] p-16 text-center">
              <p className="text-[var(--ink-2)]">Votre panier est vide.</p>
              <Link to="/boutique" data-testid="cart-page-empty-link" className="btn-pill btn-ghost mt-6">Découvrir la boutique</Link>
            </div>
          ) : (
            <div className="divide-y divide-[var(--line)] border-y border-[var(--line)]">
              {items.map((i) => (
                <div key={i.id} className="py-6 flex gap-6" data-testid={`cart-page-item-${i.id}`}>
                  <img src={i.image} alt={i.name} className="h-32 w-24 object-cover bg-[var(--luster)]" />
                  <div className="flex-1 flex flex-col justify-between">
                    <div className="flex justify-between gap-4">
                      <Link to={`/produit/${i.id}`} className="font-display text-2xl leading-tight">{i.name}</Link>
                      <span className="font-mono text-sm">{formatPrice(i.price * i.qty)}</span>
                    </div>
                    <div className="flex items-center justify-between">
                      <div className="inline-flex items-center border border-[var(--line)] rounded-full">
                        <button data-testid={`cart-page-minus-${i.id}`} onClick={() => setQty(i.id, i.qty - 1)} className="h-9 w-9 flex items-center justify-center"><Minus size={12} /></button>
                        <span data-testid={`cart-page-qty-${i.id}`} className="w-6 text-center text-sm">{i.qty}</span>
                        <button data-testid={`cart-page-plus-${i.id}`} onClick={() => setQty(i.id, i.qty + 1)} className="h-9 w-9 flex items-center justify-center"><Plus size={12} /></button>
                      </div>
                      <button data-testid={`cart-page-remove-${i.id}`} onClick={() => remove(i.id)} className="text-xs text-[var(--ink-3)] hover:text-[var(--ink)] inline-flex items-center gap-1"><Trash2 size={13} /> Retirer</button>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        <Reveal className="lg:col-span-5">
          <div className="bg-white border border-[var(--line)] p-8 lg:p-10 lg:sticky lg:top-32">
            <div className="flex justify-between font-display text-3xl">
              <span>Total</span>
              <span className="font-mono text-xl" data-testid="cart-page-total">{formatPrice(total)}</span>
            </div>
            <p className="text-xs text-[var(--ink-3)] mt-2">Livraison calculée à la confirmation · Paiement à la livraison, MTN MoMo ou Moov Money</p>

            <form onSubmit={submit} className="mt-8 space-y-4" data-testid="checkout-form">
              <input data-testid="checkout-name-input" required placeholder="Nom complet" value={form.customer_name} onChange={set("customer_name")} />
              <input data-testid="checkout-phone-input" required placeholder="Téléphone (WhatsApp)" value={form.phone} onChange={set("phone")} />
              <input data-testid="checkout-email-input" type="email" placeholder="E-mail (optionnel)" value={form.email} onChange={set("email")} />
              <input data-testid="checkout-address-input" required placeholder="Adresse de livraison / Ville" value={form.address} onChange={set("address")} />
              <textarea data-testid="checkout-note-input" rows={2} placeholder="Une précision ? (couleur, sur mesure…)" value={form.note} onChange={set("note")} />
              <button data-testid="checkout-submit-button" disabled={items.length === 0 || loading} className="btn-pill btn-dark w-full disabled:opacity-40 disabled:pointer-events-none">
                {loading ? "Envoi…" : "Confirmer la commande"}
              </button>
            </form>
            <div className="flex items-center gap-4 my-6 text-xs text-[var(--ink-3)]"><span className="h-px flex-1 bg-[var(--line)]" />ou<span className="h-px flex-1 bg-[var(--line)]" /></div>
            <a href={whatsappLink(waText())} target="_blank" rel="noreferrer" data-testid="whatsapp-checkout-button" className={`btn-pill btn-ghost w-full ${items.length === 0 ? "pointer-events-none opacity-40" : ""}`}>
              <MessageCircle size={16} /> Commander sur WhatsApp
            </a>
          </div>
        </Reveal>
      </div>
    </div>
  );
}
