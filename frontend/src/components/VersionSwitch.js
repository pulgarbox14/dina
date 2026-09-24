import { NavLink } from "react-router-dom";

export const VersionSwitch = () => (
  <div data-testid="artisan-version-switch" className="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 glass border border-white/70 shadow-xl rounded-full p-1 flex items-center gap-1 text-xs">
    <span className="px-3 text-[var(--ink-3)] uppercase tracking-[0.18em] text-[10px]">Rendu</span>
    {[["/artisane", "Version A", "a"], ["/artisane-2", "Version B", "b"]].map(([to, l, id]) => (
      <NavLink key={to} to={to} data-testid={`version-switch-${id}`} className={({ isActive }) => `h-9 px-4 rounded-full flex items-center font-medium transition-colors ${isActive ? "bg-[var(--ink)] text-white" : "hover:bg-white"}`}>{l}</NavLink>
    ))}
  </div>
);
