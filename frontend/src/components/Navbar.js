import { useEffect, useState } from "react";
import { Link, NavLink, useLocation } from "react-router-dom";
import { ShoppingBag, Menu, X } from "lucide-react";
import { AnimatePresence, motion } from "framer-motion";
import { Logo } from "@/components/Logo";
import { useCart } from "@/context/CartContext";

const LINKS = [
  { to: "/", label: "Accueil", id: "accueil" },
  { to: "/boutique", label: "Boutique", id: "boutique" },
  { to: "/artisane", label: "L'Artisane", id: "artisane" },
  { to: "/a-propos", label: "À propos", id: "a-propos" },
  { to: "/contact", label: "Contact", id: "contact" },
];

export const Navbar = () => {
  const { count, setOpen } = useCart();
  const [menu, setMenu] = useState(false);
  const [scrolled, setScrolled] = useState(false);
  const { pathname } = useLocation();

  useEffect(() => {
    const onScroll = () => setScrolled(window.scrollY > 24);
    window.addEventListener("scroll", onScroll, { passive: true });
    return () => window.removeEventListener("scroll", onScroll);
  }, []);
  useEffect(() => setMenu(false), [pathname]);

  return (
    <header
      data-testid="navbar"
      className="fixed top-3 sm:top-5 inset-x-3 sm:inset-x-5 z-50"
    >
      <div className={`max-w-[1400px] mx-auto px-4 sm:px-6 h-[64px] rounded-full flex items-center justify-between glass border border-white/60 transition-shadow duration-500 ${scrolled ? "shadow-[0_18px_50px_-24px_rgba(20,20,20,0.35)]" : "shadow-[0_10px_40px_-24px_rgba(20,20,20,0.25)]"}`}>
        <Link to="/" data-testid="nav-logo-link">
          <Logo />
        </Link>
        <nav className="hidden md:flex items-center gap-10">
          {LINKS.map((l) => (
            <NavLink
              key={l.to}
              to={l.to}
              end={l.to === "/"}
              data-testid={`nav-link-${l.id}`}
              className={({ isActive }) => `link-underline text-sm tracking-wide ${isActive ? "active" : "text-[var(--ink-2)]"}`}
            >
              {l.label}
            </NavLink>
          ))}
        </nav>
        <div className="flex items-center gap-3">
          <span className="hidden md:block"><Link to="/boutique" data-testid="nav-cta-button" className="btn-pill btn-dark !h-11 !px-6">Commander</Link></span>
          <button
            data-testid="cart-drawer-toggle"
            onClick={() => setOpen(true)}
            className="relative h-11 w-11 rounded-full bg-white border border-[var(--line)] flex items-center justify-center hover:bg-[var(--ink)] hover:text-[var(--pearl)] transition-colors duration-300"
            aria-label="Panier"
          >
            <ShoppingBag size={18} strokeWidth={1.6} />
            {count > 0 && (
              <span
                data-testid="cart-count-badge"
                className="absolute -top-1 -right-1 h-5 min-w-5 px-1 rounded-full bg-[var(--gold)] text-[var(--ink)] text-[11px] font-semibold flex items-center justify-center"
              >
                {count}
              </span>
            )}
          </button>
          <button
            data-testid="mobile-menu-toggle"
            onClick={() => setMenu((m) => !m)}
            className="md:hidden h-11 w-11 rounded-full bg-white border border-[var(--line)] flex items-center justify-center"
            aria-label="Menu"
          >
            {menu ? <X size={18} /> : <Menu size={18} />}
          </button>
        </div>
      </div>
      <AnimatePresence>
        {menu && (
          <motion.nav
            data-testid="mobile-menu"
            initial={{ opacity: 0, y: -12 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -12 }}
            className="md:hidden glass mt-3 rounded-[28px] border border-white/60 shadow-xl px-6 py-8 flex flex-col gap-6"
          >
            {LINKS.map((l) => (
              <NavLink key={l.to} to={l.to} data-testid={`mobile-nav-link-${l.id}`} className="font-display text-3xl">
                {l.label}
              </NavLink>
            ))}
          </motion.nav>
        )}
      </AnimatePresence>
    </header>
  );
};
