import { ArtisanHero } from "@/components/ArtisanHero";
import { ArtisanBody } from "@/components/ArtisanBody";

export default function Artisane() {
  return (
    <div data-testid="artisane-page">
      <ArtisanHero />
      <ArtisanBody />
    </div>
  );
}
