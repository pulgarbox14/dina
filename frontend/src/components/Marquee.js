const ITEMS = [
  "Fait main à Dakar",
  "Perles nacrées haute qualité",
  "Éditions limitées",
  "Livraison Afrique & International",
  "Commandes sur mesure",
];

export const Marquee = ({ dark = false }) => {
  const row = [...ITEMS, ...ITEMS];
  return (
    <div
      data-testid="marquee"
      className={`overflow-hidden border-y ${dark ? "border-white/10 bg-[var(--ink)] text-[var(--pearl)]" : "border-[var(--line)] bg-[var(--bg-elevated)]"} py-5`}
    >
      <div className="marquee-track flex w-max">
        {row.map((t, i) => (
          <span key={i} className="flex items-center gap-8 pr-8 font-display text-2xl sm:text-3xl italic font-light whitespace-nowrap">
            {t}
            <span className="pearl-dot h-3 w-3 rounded-full inline-block" />
          </span>
        ))}
      </div>
    </div>
  );
};
