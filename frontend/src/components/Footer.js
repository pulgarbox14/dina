import { useState } from "react";
import { Link } from "react-router-dom";
import { ArrowUpRight, Instagram, MessageCircle, Mail, Truck, ShieldCheck, Hand } from "lucide-react";
import { toast } from "sonner";
import { Logo } from "@/components/Logo";
import { BRAND, whatsappLink } from "@/lib/config";
import { api } from "@/lib/api";

const COLS = [
  { title: "Boutique", links: [["Sacs", "/boutique"], ["Mini-sacs", "/boutique"], ["Parures & bijoux", "/boutique"], ["Panier", "/panier"]], tid: "boutique" },
  { title: "Maison", links: [["L'Artisane", "/artisane"], ["À propos", "/a-propos"], ["Contact", "/contact"], ["Sur mesure", "/contact"]], tid: "maison" },
];

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
    <footer data-testid="footer" className="px-3 sm:px-5 pb-5 mt-28">
      <div className="rounded-[40px] bg-[var(--bg-elevated)] px-6 sm:px-10 lg:px-16 pt-14 pb-8 overflow-hidden relative">
        <div className="grid lg:grid-cols-12 gap-10 items-end pb-12 border-b border-[var(--line)]">
          <div className="lg:col-span-7">
            <span className="eyebrow">Perlae Atelier</span>
            <h2 className="font-display text-4xl sm:text-5xl lg:text-6xl leading-[1.02] mt-4">Une pièce unique <br className="hidden sm:block" /> vous attend.</h2>
          </div>
          <div className="lg:col-span-5 flex flex-wrap gap-3 lg:justify-end">
            <Link to="/boutique" data-testid="footer-cta-boutique" className="btn-pill btn-dark">Voir la boutique <ArrowUpRight size={16} /></Link>
            <a href={whatsappLink("Bonjour Perlae Atelier !")} target="_blank" rel="noreferrer" data-testid="footer-whatsapp-link" className="btn-pill btn-ghost bg-white"><MessageCircle size={16} /> WhatsApp</a>
          </div>
        </div>

        <div className="grid sm:grid-cols-2 lg:grid-cols-12 gap-10 py-12">
          <div className="lg:col-span-4">
            <Logo />
            <p className="text-sm text-[var(--ink-2)] mt-6 max-w-xs leading-relaxed">Sacs et parures en perles nacrées, tissés à la main à {BRAND.city}. Chaque perle est posée une à une.</p>
            <div className="flex gap-3 mt-6">
              {[[Instagram, "#", "footer-instagram"], [MessageCircle, whatsappLink("Bonjour !"), "footer-whatsapp-icon"], [Mail, `mailto:${BRAND.email}`, "footer-mail"]].map(([Icon, href, tid]) => (
                <a key={tid} href={href} target="_blank" rel="noreferrer" data-testid={tid} className="h-11 w-11 rounded-full bg-white border border-[var(--line)] flex items-center justify-center hover:bg-[var(--ink)] hover:text-white transition-colors"><Icon size={17} strokeWidth={1.6} /></a>
              ))}
            </div>
          </div>
          {COLS.map((c) => (
            <div key={c.title} className="lg:col-span-2">
              <div className="text-sm font-medium mb-5">{c.title}</div>
              <ul className="space-y-3 text-sm text-[var(--ink-2)]">
                {c.links.map(([l, to]) => <li key={l}><Link to={to} data-testid={`footer-link-${c.tid}-${l.toLowerCase().replace(/[^a-z]+/g, "-")}`} className="link-underline hover:text-[var(--ink)]">{l}</Link></li>)}
              </ul>
            </div>
          ))}
          <div className="lg:col-span-4">
            <div className="text-sm font-medium mb-5">Newsletter</div>
            <p className="text-sm text-[var(--ink-2)]">Les nouvelles pièces partent vite. Soyez prévenu(e) en premier.</p>
            <form onSubmit={submit} className="mt-5 flex items-center bg-white rounded-full border border-[var(--line)] p-1.5 pl-5" data-testid="newsletter-form">
              <input data-testid="newsletter-email-input" type="email" required value={email} onChange={(e) => setEmail(e.target.value)} placeholder="votre@email.com" className="!border-0 !py-2 text-sm" />
              <button data-testid="newsletter-submit-button" className="h-10 px-5 rounded-full bg-[var(--ink)] text-white text-sm shrink-0 hover:bg-[var(--gold)] hover:text-[var(--ink)] transition-colors">S'inscrire</button>
            </form>
          </div>
        </div>

        <div className="flex flex-col md:flex-row justify-between gap-4 pt-6 border-t border-[var(--line)] text-xs text-[var(--ink-3)]">
          <span>© {new Date().getFullYear()} {BRAND.name}. Fait main avec patience.</span>
          <div className="flex flex-wrap gap-2">
            {[[Hand, "100 % fait main"], [Truck, "Livraison Afrique & monde"], [ShieldCheck, "Wave · Orange Money · Livraison"]].map(([Icon, t]) => (
              <span key={t} className="inline-flex items-center gap-1.5 bg-white border border-[var(--line)] rounded-full px-3 h-7"><Icon size={12} /> {t}</span>
            ))}
          </div>
        </div>
      </div>
    </footer>
  );
};
