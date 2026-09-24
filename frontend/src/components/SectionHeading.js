import { Reveal } from "@/components/Reveal";

export const SectionHeading = ({ eyebrow, title, text, align = "left", className = "" }) => (
  <Reveal className={`${align === "center" ? "text-center mx-auto" : ""} max-w-2xl ${className}`}>
    {eyebrow && <span className="eyebrow">{eyebrow}</span>}
    <h2 className="font-display text-4xl sm:text-5xl leading-[1.05] tracking-tight mt-4">{title}</h2>
    {text && <p className="text-base text-[var(--ink-2)] mt-6 leading-relaxed">{text}</p>}
  </Reveal>
);

export const PageHero = ({ eyebrow, title, text, children }) => (
  <section className="pt-40 pb-16 px-6 lg:px-12 max-w-[1440px] mx-auto">
    <Reveal>
      <span className="eyebrow">{eyebrow}</span>
      <h1 className="font-display text-5xl sm:text-6xl lg:text-7xl leading-[0.98] tracking-tight mt-5 max-w-3xl">{title}</h1>
      {text && <p className="text-base sm:text-lg text-[var(--ink-2)] mt-8 max-w-xl leading-relaxed">{text}</p>}
      {children}
    </Reveal>
  </section>
);
