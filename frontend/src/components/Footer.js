import { useState } from "react";
import { Link } from "react-router-dom";
import { ArrowUpRight } from "lucide-react";
import { toast } from "sonner";
import { Logo } from "@/components/Logo";
import { BRAND, whatsappLink } from "@/lib/config";
import { api } from "@/lib/api";

export const Footer = () => {
  const [email, setEmail] = useState("");
  const submit = async (e) => {
    e.preventDefault();
    try {
      await api.newsletter(email);
      toast.success("Merci ! Vous recevrez nos nouvelles créations en avant-première.");
      setEmail("");
    } catch {
      toast.error("Adresse e-mail invalide.");
    }
  };

  return (
    <footer data-testid="footer" className="bg-[var(--ink)] text-[var(--pearl)] mt-32">
      <div className="max-w-[1440px] mx-auto px-6 lg:px-12 pt-24 pb-12">
        <div className="grid lg:grid-cols-12 gap-14">
          <div className="lg:col-span-5">
            <Logo dark />
            <p className="font-display text-3xl sm:text-4xl leading-tight mt-10 max-w-md">
              Chaque perle est posée à la main. Chaque sac raconte une journée d'atelier.
            </p>
          </div>
          <div className="lg:col-span-3 grid grid-cols-2 gap-8 text-sm">
            <div className="flex flex-col gap-4">
              <span className="eyebrow !text-[var(--ink-3)]">Naviguer</span>
              <Link to="/boutique" data-testid="footer-link-boutique" className="link-underline w-fit">Boutique</Link>
              <Link to="/artisane" data-testid="footer-link-artisane" className="link-underline w-fit">L'Artisane</Link>
              <Link to="/a-propos" data-testid="footer-link-a-propos" className="link-underline w-fit">À propos</Link>
              <Link to="/contact" data-testid="footer-link-contact" className="link-underline w-fit">Contact</Link>
            </div>
            <div className="flex flex-col gap-4">
              <span className="eyebrow !text-[var(--ink-3)]">Atelier</span>
              <span>{BRAND.city}</span>
              <a href={whatsappLink("Bonjour Perlae Atelier !")} target="_blank" rel="noreferrer" data-testid="footer-whatsapp-link" className="link-underline w-fit">WhatsApp</a>
              <a href={`mailto:${BRAND.email}`} className="link-underline w-fit break-all">{BRAND.email}</a>
              <span>{BRAND.instagram}</span>
            </div>
          </div>
          <div className="lg:col-span-4">
            <span className="eyebrow !text-[var(--ink-3)]">Newsletter</span>
            <p className="mt-4 text-sm text-white/70">Les nouvelles pièces sont limitées à quelques exemplaires. Soyez prévenu(e) en premier.</p>
            <form onSubmit={submit} className="mt-6 flex items-end gap-3" data-testid="newsletter-form">
              <input
                data-testid="newsletter-email-input"
                type="email"
                required
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                placeholder="votre@email.com"
                className="!border-white/30 text-[var(--pearl)] placeholder:!text-white/40 focus:!border-[var(--gold)]"
              />
              <button data-testid="newsletter-submit-button" className="h-12 w-12 shrink-0 rounded-full bg-[var(--pearl)] text-[var(--ink)] flex items-center justify-center hover:bg-[var(--gold)] transition-colors">
                <ArrowUpRight size={18} />
              </button>
            </form>
          </div>
        </div>
        <div className="mt-20 pt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between gap-4 text-xs text-white/50">
          <span>© {new Date().getFullYear()} {BRAND.name}. Fait main avec patience.</span>
          <span>Paiement à la livraison · Wave · Orange Money</span>
        </div>
      </div>
    </footer>
  );
};
