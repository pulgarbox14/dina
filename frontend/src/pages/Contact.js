import { useState } from "react";
import { MessageCircle, Mail, MapPin, Instagram } from "lucide-react";
import { toast } from "sonner";
import { api } from "@/lib/api";
import { BRAND, whatsappLink } from "@/lib/config";
import { Reveal } from "@/components/Reveal";
import { PageHero } from "@/components/SectionHeading";
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from "@/components/ui/accordion";

const FAQ = [
  ["Livrez-vous hors du Bénin ?", "Oui. Afrique de l'Ouest en 3 à 7 jours, Europe et Amérique du Nord en 7 à 14 jours via transporteur suivi."],
  ["Puis-je commander un modèle sur mesure ?", "Absolument. Choisissez la couleur des fleurs, la taille et même vos initiales. Comptez 2 à 3 semaines de fabrication."],
  ["Comment entretenir mon sac en perles ?", "Un chiffon doux et sec suffit. Évitez l'eau, les parfums et le soleil direct. Rangez-le dans sa pochette."],
  ["Quels moyens de paiement acceptez-vous ?", "Paiement à la livraison à Cotonou, MTN MoMo, Moov Money et virement pour l'international."],
];

export default function Contact() {
  const [form, setForm] = useState({ name: "", email: "", subject: "", message: "" });
  const [loading, setLoading] = useState(false);
  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const submit = async (e) => {
    e.preventDefault();
    setLoading(true);
    try {
      await api.contact(form);
      toast.success("Message envoyé. Nous répondons sous 24 h.");
      setForm({ name: "", email: "", subject: "", message: "" });
    } catch {
      toast.error("Envoi impossible. Vérifiez les champs.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div data-testid="contact-page">
      <PageHero eyebrow="Contact" title={<>Parlons de votre <em className="font-light">prochaine pièce.</em></>} text="Une question, une commande sur mesure, une collaboration ? L'atelier vous répond sous 24 heures." />
      <div className="max-w-[1440px] mx-auto px-6 lg:px-12 grid lg:grid-cols-12 gap-16 pb-32">
        <Reveal className="lg:col-span-4 space-y-8">
          {[
            [MessageCircle, "WhatsApp", `+${BRAND.whatsapp}`, whatsappLink("Bonjour Dina Perles !"), "contact-whatsapp-link"],
            [Mail, "E-mail", BRAND.email, `mailto:${BRAND.email}`, "contact-email-link"],
            [Instagram, "Instagram", BRAND.instagram, "#", "contact-instagram-link"],
            [MapPin, "Atelier", BRAND.city, null, "contact-address"],
          ].map(([Icon, l, v, href, tid]) => (
            <div key={l} className="flex gap-4 items-start">
              <span className="h-11 w-11 rounded-full border border-[var(--line)] flex items-center justify-center shrink-0"><Icon size={17} strokeWidth={1.6} /></span>
              <div>
                <div className="eyebrow">{l}</div>
                {href ? <a href={href} target="_blank" rel="noreferrer" data-testid={tid} className="link-underline mt-1 inline-block">{v}</a> : <div data-testid={tid} className="mt-1">{v}</div>}
              </div>
            </div>
          ))}
        </Reveal>
        <Reveal delay={0.1} className="lg:col-span-8">
          <form onSubmit={submit} className="grid sm:grid-cols-2 gap-x-8 gap-y-6" data-testid="contact-form">
            <input data-testid="contact-name-input" required placeholder="Votre nom" value={form.name} onChange={set("name")} />
            <input data-testid="contact-email-input" required type="email" placeholder="Votre e-mail" value={form.email} onChange={set("email")} />
            <input data-testid="contact-subject-input" required placeholder="Sujet" value={form.subject} onChange={set("subject")} className="sm:col-span-2" />
            <textarea data-testid="contact-message-input" required rows={5} placeholder="Votre message" value={form.message} onChange={set("message")} className="sm:col-span-2" />
            <button data-testid="contact-submit-button" disabled={loading} className="btn-pill btn-dark w-fit disabled:opacity-40">{loading ? "Envoi…" : "Envoyer le message"}</button>
          </form>
          <div className="mt-24">
            <h2 className="font-display text-4xl mb-6">Questions fréquentes</h2>
            <Accordion type="single" collapsible data-testid="faq-accordion">
              {FAQ.map(([q, a], i) => (
                <AccordionItem key={q} value={`q${i}`}><AccordionTrigger data-testid={`faq-trigger-${i}`} className="text-left font-display text-xl font-normal">{q}</AccordionTrigger><AccordionContent className="text-[var(--ink-2)]">{a}</AccordionContent></AccordionItem>
              ))}
            </Accordion>
          </div>
        </Reveal>
      </div>
    </div>
  );
}
