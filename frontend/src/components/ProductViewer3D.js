import { useState } from "react";
import { RotateCcw } from "lucide-react";
import { PearlScene } from "@/components/three/PearlScene";
import { ACCENTS, shapeFor } from "@/components/three/PearlModel";

export const ProductViewer3D = ({ product }) => {
  const [accent, setAccent] = useState(ACCENTS[product.accent] || null);
  const shape = shapeFor(product);
  return (
    <div data-testid="product-3d-viewer" className="relative aspect-[4/5] overflow-hidden rounded-[28px] bg-[radial-gradient(ellipse_at_50%_40%,#ffffff_0%,#f2f2f4_70%,#e6e6ea_100%)]">
      <PearlScene shape={shape} accent={accent} camera={shape === "necklace" ? 7.6 : 7} testId="product-3d-canvas" />
      <div className="absolute top-5 left-5 glass rounded-full px-4 h-9 flex items-center gap-2 text-xs font-medium">
        <RotateCcw size={13} /> Glissez pour faire tourner
      </div>
      <div className="absolute bottom-5 left-5 right-5 flex items-center justify-between gap-3">
        <span className="text-[10px] uppercase tracking-[0.22em] text-[var(--ink-3)]">Aperçu 3D · rendu fidèle au tissage</span>
        <div className="flex gap-2" data-testid="viewer-accent-options">
          {[["Blanc", null], ["Orange", "#f97316"], ["Violet", "#a855f7"], ["Ambre", "#f59e0b"], ["Bleu", "#60a5fa"]].map(([l, c]) => (
            <button
              key={l}
              data-testid={`viewer-accent-${l.toLowerCase()}`}
              title={l}
              onClick={() => setAccent(c)}
              className={`h-7 w-7 rounded-full border-2 transition-transform duration-300 hover:scale-110 ${accent === c ? "border-[var(--ink)] scale-110" : "border-white"}`}
              style={{ background: c || "linear-gradient(135deg,#fff,#e6e2da)" }}
            />
          ))}
        </div>
      </div>
    </div>
  );
};
