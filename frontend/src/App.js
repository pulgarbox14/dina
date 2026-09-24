import "@/App.css";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { Toaster } from "sonner";
import { CartProvider } from "@/context/CartContext";
import { SmoothScroll } from "@/components/SmoothScroll";
import { Navbar } from "@/components/Navbar";
import { Footer } from "@/components/Footer";
import { CartDrawer } from "@/components/CartDrawer";
import Home from "@/pages/Home";
import Boutique from "@/pages/Boutique";
import Produit from "@/pages/Produit";
import Artisane from "@/pages/Artisane";
import ArtisaneV2 from "@/pages/ArtisaneV2";
import APropos from "@/pages/APropos";
import Panier from "@/pages/Panier";
import Contact from "@/pages/Contact";

function App() {
  return (
    <div className="App grain">
      <BrowserRouter>
        <CartProvider>
          <SmoothScroll>
            <Navbar />
            <CartDrawer />
            <main>
              <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/boutique" element={<Boutique />} />
                <Route path="/produit/:id" element={<Produit />} />
                <Route path="/artisane" element={<Artisane />} />
                <Route path="/artisane-2" element={<ArtisaneV2 />} />
                <Route path="/a-propos" element={<APropos />} />
                <Route path="/panier" element={<Panier />} />
                <Route path="/contact" element={<Contact />} />
              </Routes>
            </main>
            <Footer />
          </SmoothScroll>
          <Toaster position="bottom-right" toastOptions={{ style: { fontFamily: "Manrope" } }} />
        </CartProvider>
      </BrowserRouter>
    </div>
  );
}

export default App;
