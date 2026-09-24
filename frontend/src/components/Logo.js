export const Logo = ({ className = "", dark = false }) => (
  <span className={`inline-flex items-center gap-3 ${className}`} data-testid="brand-logo">
    <svg width="34" height="34" viewBox="0 0 64 64" aria-hidden="true">
      <rect width="64" height="64" rx="14" fill={dark ? "#FAF8F5" : "#121316"} />
      <circle cx="32" cy="36" r="13" fill={dark ? "#121316" : "#FAF8F5"} />
      <circle cx="27" cy="31" r="4" fill={dark ? "#3a3b40" : "#ffffff"} opacity="0.9" />
      <path d="M20 26 C20 12 44 12 44 26" stroke="#D4AF37" strokeWidth="3.5" fill="none" strokeLinecap="round" />
    </svg>
    <span className="leading-none">
      <span className="font-display text-[22px] tracking-tight block">Perlae</span>
      <span className="eyebrow block mt-1 !tracking-[0.32em]">Atelier</span>
    </span>
  </span>
);
