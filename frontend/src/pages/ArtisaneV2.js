import { ArtisanHeroV2 } from "@/components/ArtisanHeroV2";
import { ArtisanBody } from "@/components/ArtisanBody";
import { VersionSwitch } from "@/components/VersionSwitch";

export default function ArtisaneV2() {
  return (
    <div data-testid="artisane-v2-page">
      <ArtisanHeroV2 />
      <ArtisanBody />
      <VersionSwitch />
    </div>
  );
}
