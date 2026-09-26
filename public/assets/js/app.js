/*
 * Dina Perles — interactions du site (JavaScript sans framework).
 * Le site reste utilisable sans JavaScript : chaque action est un vrai formulaire
 * que ce script « améliore » (envoi sans recharger la page, animations…).
 */
(() => {
    "use strict";

    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
    const csrf = () => $('meta[name="csrf-token"]')?.content || "";
    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    /* ——— Toasts ——— */
    const ICONS = {
        success: '<path d="M20 6 9 17l-5-5"/>',
        error: '<circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>',
    };
    function toast(message, type = "success") {
        if (!message) return;
        const box = $("#toasts");
        const el = document.createElement("div");
        el.className = "toast pointer-events-auto flex items-center gap-3 bg-white border border-[var(--line)] rounded-2xl shadow-xl px-4 py-3 text-sm max-w-sm w-full sm:w-auto";
        el.setAttribute("role", type === "error" ? "alert" : "status");
        el.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="${type === "error" ? "#dc2626" : "#16a34a"}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${ICONS[type] || ICONS.success}</svg><span></span>`;
        el.querySelector("span").textContent = message;
        box.appendChild(el);
        setTimeout(() => {
            el.classList.add("is-leaving");
            setTimeout(() => el.remove(), 300);
        }, 4000);
    }
    window.dinaToast = toast;

    function showFlashes() {
        try {
            JSON.parse($("#flash-data")?.textContent || "[]").forEach((f) => toast(f.message, f.type));
        } catch (_) {
            /* rien à afficher */
        }
    }

    /* ——— Défilement fluide (Lenis) ——— */
    let lenis = null;
    function initSmoothScroll() {
        if (reducedMotion || typeof window.Lenis !== "function") return;
        lenis = new window.Lenis({ lerp: 0.09, smoothWheel: true });
        const raf = (t) => {
            lenis.raf(t);
            requestAnimationFrame(raf);
        };
        requestAnimationFrame(raf);
    }

    /* ——— Apparitions au défilement ——— */
    function initReveal() {
        const items = $$("[data-reveal]");
        if (!("IntersectionObserver" in window)) {
            items.forEach((el) => el.classList.add("is-visible"));
            return;
        }
        const io = new IntersectionObserver(
            (entries) =>
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("is-visible");
                        io.unobserve(entry.target);
                    }
                }),
            { rootMargin: "0px 0px -80px 0px" }
        );
        items.forEach((el) => io.observe(el));
    }

    /* ——— Cartes inclinées au survol (TiltCard) ——— */
    function initTilt() {
        if (reducedMotion || !window.matchMedia("(hover: hover)").matches) return;
        const max = 9;
        $$("[data-tilt]").forEach((el) => {
            el.addEventListener("mousemove", (e) => {
                const r = el.getBoundingClientRect();
                const px = (e.clientX - r.left) / r.width;
                const py = (e.clientY - r.top) / r.height;
                el.style.setProperty("--mx", `${px * 100}%`);
                el.style.setProperty("--my", `${py * 100}%`);
                el.style.transform = `perspective(1100px) rotateX(${(0.5 - py) * max}deg) rotateY(${(px - 0.5) * max}deg) translateY(-6px)`;
                el.style.boxShadow = "0 30px 60px -30px rgba(18,19,22,0.35)";
            });
            el.addEventListener("mouseleave", () => {
                el.style.transform = "perspective(1100px) rotateX(0deg) rotateY(0deg) translateY(0)";
                el.style.boxShadow = "";
            });
        });
    }

    /* ——— Navbar : ombre au défilement + menu mobile ——— */
    function initNavbar() {
        const bar = $("[data-navbar-bar]");
        const onScroll = () => {
            const scrolled = window.scrollY > 24;
            bar?.classList.toggle("shadow-[0_18px_50px_-24px_rgba(20,20,20,0.35)]", scrolled);
            bar?.classList.toggle("shadow-[0_10px_40px_-24px_rgba(20,20,20,0.25)]", !scrolled);
        };
        window.addEventListener("scroll", onScroll, { passive: true });
        onScroll();

        const toggle = $("[data-menu-toggle]");
        const menu = $("[data-mobile-menu]");
        if (!toggle || !menu) return;
        toggle.addEventListener("click", () => {
            const open = menu.classList.contains("hidden");
            menu.classList.toggle("hidden", !open);
            menu.classList.toggle("is-open", open);
            toggle.setAttribute("aria-expanded", String(open));
            $('[data-menu-icon="open"]', toggle).classList.toggle("hidden", open);
            $('[data-menu-icon="close"]', toggle).classList.toggle("hidden", !open);
        });
    }

    /* ——— Tiroir panier ——— */
    const drawer = () => $("[data-cart-drawer]");
    let lastFocus = null;
    function openCart() {
        const d = drawer();
        if (!d) return;
        lastFocus = document.activeElement;
        d.classList.add("is-open");
        d.setAttribute("aria-hidden", "false");
        document.body.classList.add("cart-is-open");
        lenis?.stop();
        setTimeout(() => $("[data-cart-close].h-10", d)?.focus(), 50);
    }
    function closeCart() {
        const d = drawer();
        if (!d || !d.classList.contains("is-open")) return;
        d.classList.remove("is-open");
        d.setAttribute("aria-hidden", "true");
        document.body.classList.remove("cart-is-open");
        lenis?.start();
        lastFocus?.focus?.();
    }
    function initCartDrawer() {
        document.addEventListener("click", (e) => {
            const opener = e.target.closest("[data-cart-open]");
            if (opener) {
                e.preventDefault();
                openCart();
            }
            if (e.target.closest("[data-cart-close]")) closeCart();
        });
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") closeCart();
        });
    }

    function updateCartUI(data) {
        $$("[data-cart-count]").forEach((el) => {
            el.textContent = data.count;
            el.classList.toggle("hidden", data.count === 0);
        });
        const body = $("[data-cart-drawer-body]");
        if (body && typeof data.drawer === "string") body.innerHTML = data.drawer;

        const pageItems = $("[data-cart-page-items]");
        if (pageItems && typeof data.page === "string") {
            pageItems.innerHTML = data.page;
            $$("[data-cart-total]").forEach((el) => (el.textContent = data.totalFormatted));
            const wa = $("[data-cart-whatsapp]");
            if (wa) {
                wa.href = data.whatsapp;
                wa.classList.toggle("pointer-events-none", data.count === 0);
                wa.classList.toggle("opacity-40", data.count === 0);
            }
            $$("[data-requires-items]").forEach((b) => (b.disabled = data.count === 0));
        }
    }

    /** Formulaires du panier (ajouter, modifier, retirer) envoyés sans recharger la page. */
    function initCartForms() {
        document.addEventListener("submit", async (e) => {
            const form = e.target.closest("[data-cart-form]");
            if (!form) return;
            e.preventDefault();
            const button = e.submitter || $("button", form);
            if (button) button.disabled = true;
            try {
                const res = await fetch(form.action, {
                    method: "POST",
                    body: new FormData(form),
                    headers: { Accept: "application/json" },
                    credentials: "same-origin",
                });
                const data = await res.json();
                if (!res.ok || !data.ok) throw new Error(data.message || "Erreur");
                updateCartUI(data);
                if (data.message) toast(data.message);
                if (data.open) openCart();
            } catch (err) {
                toast(err.message && err.message !== "Erreur" ? err.message : "Action impossible. Réessayez.", "error");
            } finally {
                if (button && document.contains(button)) button.disabled = false;
            }
        });
    }

    /* ——— Formulaires contact / newsletter envoyés sans recharger ——— */
    function clearErrors(form) {
        $$("[data-field-error]", form).forEach((el) => el.remove());
        $$('[aria-invalid="true"]', form).forEach((el) => el.removeAttribute("aria-invalid"));
    }
    function showErrors(form, errors) {
        Object.entries(errors || {}).forEach(([name, message]) => {
            const field = form.elements.namedItem(name);
            if (!field) return;
            field.setAttribute("aria-invalid", "true");
            const p = document.createElement("p");
            p.className = "text-xs text-red-600 mt-1";
            p.dataset.fieldError = "";
            p.textContent = message;
            field.insertAdjacentElement("afterend", p);
        });
    }
    function initAjaxForms() {
        document.addEventListener("submit", async (e) => {
            const form = e.target.closest("[data-ajax-form]");
            if (!form) return;
            e.preventDefault();
            const button = $("button:not([type=button])", form);
            const label = button?.innerHTML;
            if (button) {
                button.disabled = true;
                if (button.dataset.loadingText) button.textContent = button.dataset.loadingText;
            }
            clearErrors(form);
            try {
                const res = await fetch(form.action, {
                    method: "POST",
                    body: new FormData(form),
                    headers: { Accept: "application/json" },
                    credentials: "same-origin",
                });
                const data = await res.json();
                if (!data.ok) {
                    showErrors(form, data.errors);
                    toast(data.message, "error");
                    return;
                }
                toast(data.message);
                form.reset();
            } catch (_) {
                toast("Envoi impossible. Vérifiez votre connexion.", "error");
            } finally {
                if (button) {
                    button.disabled = false;
                    button.innerHTML = label;
                }
            }
        });

        // Anti double-clic sur le formulaire de commande (envoi classique).
        $("[data-checkout-form]")?.addEventListener("submit", (e) => {
            const button = $("button", e.currentTarget);
            if (!e.currentTarget.checkValidity()) return;
            setTimeout(() => {
                button.disabled = true;
                button.textContent = button.dataset.loadingText || button.textContent;
            }, 0);
        });
    }

    /* ——— Page produit : quantité et miniatures ——— */
    function initProductPage() {
        const page = $("[data-product-page]");
        if (!page) return;

        const qtyInput = $("[data-qty-input]", page);
        const qtyValue = $("[data-qty-value]", page);
        const wa = $("[data-wa-template]", page);
        const setQty = (q) => {
            q = Math.max(1, Math.min(20, q));
            qtyInput.value = q;
            qtyValue.textContent = q;
            if (wa) {
                const text = wa.dataset.waTemplate.replace("{qty}", q);
                wa.href = `https://wa.me/${encodeURIComponent(wa.dataset.waPhone)}?text=${encodeURIComponent(text)}`;
            }
        };
        $$("[data-qty-step]", page).forEach((b) =>
            b.addEventListener("click", () => setQty(Number(qtyInput.value) + Number(b.dataset.qtyStep)))
        );

        const main = $("[data-main-image]", page);
        $$("[data-thumb]", page).forEach((thumb) =>
            thumb.addEventListener("click", () => {
                main.src = thumb.dataset.thumb;
                $$("[data-thumb]", page).forEach((t) => {
                    const active = t === thumb;
                    t.setAttribute("aria-pressed", String(active));
                    t.classList.toggle("border-[var(--rose)]", active);
                    t.classList.toggle("border-transparent", !active);
                });
            })
        );
    }

    /* ——— Note produit : le panneau de répartition se ferme au clic extérieur, avec Échap ou en allant aux avis ——— */
    function initRatingPopover() {
        const pop = $("[data-rating-pop]");
        if (!pop) return;
        const close = () => pop.removeAttribute("open");
        document.addEventListener("click", (e) => {
            if (pop.open && !pop.contains(e.target)) close();
        });
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && pop.open) {
                close();
                $("summary", pop).focus();
            }
        });
        $$("[data-rating-close]", pop).forEach((a) => a.addEventListener("click", close));
    }

    /* ——— Avis clientes : un clic sur une carte ouvre l'avis complet et la pièce achetée ——— */
    function initReviews() {
        const root = $("[data-reviews]");
        const dialog = $("[data-review-dialog]", root || document);
        if (!root || !dialog || typeof dialog.showModal !== "function") return;
        let reviews;
        try {
            reviews = JSON.parse($("[data-reviews-data]", root).textContent);
        } catch (_) {
            return;
        }
        const product = $("[data-review-product]", dialog);
        const cta = $("[data-review-cta]", dialog);
        const shopUrl = cta.getAttribute("href");

        root.addEventListener("click", (e) => {
            const card = e.target.closest("[data-review]");
            if (!card) return;
            const r = reviews[Number(card.dataset.review)];
            if (!r) return;
            $("[data-review-text]", dialog).textContent = `« ${r.text} »`;
            $("[data-review-name]", dialog).textContent = r.name;
            $("[data-review-city]", dialog).textContent = r.city;
            if (r.product) {
                product.hidden = false;
                product.href = r.product.url;
                $("[data-review-product-image]", dialog).src = r.product.image;
                $("[data-review-product-image]", dialog).alt = r.product.name;
                $("[data-review-product-name]", dialog).textContent = r.product.name;
                $("[data-review-product-price]", dialog).textContent = r.product.price;
                cta.href = r.product.url;
                cta.firstChild.textContent = "Voir la pièce ";
            } else {
                product.hidden = true;
                cta.href = shopUrl;
                cta.firstChild.textContent = "Voir la boutique ";
            }
            lenis?.stop();
            dialog.showModal();
        });
        // Toucher (mobile) : la carte « nage » un instant, comme au survol sur ordinateur.
        root.addEventListener("touchstart", (e) => {
            const card = e.target.closest("[data-review]");
            if (!card) return;
            card.classList.add("is-swimming");
            clearTimeout(card._swim);
            card._swim = setTimeout(() => card.classList.remove("is-swimming"), 2600);
        }, { passive: true });

        const close = () => dialog.close();
        $$("[data-review-close]", dialog).forEach((b) => b.addEventListener("click", close));
        // Clic sur le fond assombri (en dehors de la fenêtre) : fermeture.
        dialog.addEventListener("click", (e) => {
            if (e.target === dialog) close();
        });
        dialog.addEventListener("close", () => lenis?.start());
    }

    /* ——— Vitrine produits de l'accueil : flèches, miniatures, clavier ——— */
    function initVitrine() {
        const root = $("[data-vitrine]");
        if (!root) return;
        let slides;
        try {
            slides = JSON.parse($("[data-vitrine-slides]", root).textContent);
        } catch (_) {
            return;
        }
        if (!slides.length) return;
        let current = 0;
        const fading = $$(".vitrine-fade", root);

        const render = (i) => {
            const s = slides[i];
            const img = $("[data-vitrine-image]", root);
            img.src = s.image;
            img.alt = s.name;
            const set = (sel, value) => $$(sel, root).forEach((el) => (el.textContent = value));
            set("[data-vitrine-tag]", s.tag);
            set("[data-vitrine-name]", s.name);
            set("[data-vitrine-subtitle]", s.subtitle);
            set("[data-vitrine-price]", s.price);
            set("[data-vitrine-hours]", s.hours);
            set("[data-vitrine-accent]", s.accent);
            set("[data-vitrine-index]", String(i + 1).padStart(2, "0"));
            $$("[data-vitrine-link]", root).forEach((a) => (a.href = s.url));
            $("[data-vitrine-id]", root).value = s.id;
            $$("[data-vitrine-go]", root).forEach((b) =>
                b.setAttribute("aria-selected", String(Number(b.dataset.vitrineGo) === i))
            );
        };

        const go = (i) => {
            i = (i + slides.length) % slides.length;
            if (i === current) return;
            current = i;
            if (reducedMotion) return render(i);
            fading.forEach((el) => el.classList.add("is-switching"));
            setTimeout(() => {
                render(i);
                fading.forEach((el) => el.classList.remove("is-switching"));
            }, 250);
        };

        // Précharge les photos pour un changement instantané.
        slides.forEach((s) => (new Image().src = s.image));

        $("[data-vitrine-prev]", root)?.addEventListener("click", () => go(current - 1));
        $("[data-vitrine-next]", root)?.addEventListener("click", () => go(current + 1));
        $$("[data-vitrine-go]", root).forEach((b) => b.addEventListener("click", () => go(Number(b.dataset.vitrineGo))));
        root.addEventListener("keydown", (e) => {
            if (e.key === "ArrowLeft") go(current - 1);
            if (e.key === "ArrowRight") go(current + 1);
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        initSmoothScroll();
        initReveal();
        initTilt();
        initNavbar();
        initCartDrawer();
        initCartForms();
        initAjaxForms();
        initProductPage();
        initVitrine();
        initReviews();
        initRatingPopover();
        showFlashes();
    });
})();
