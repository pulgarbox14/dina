import { HeroEditorial } from "@/components/home/HeroEditorial";
import { Marquee } from "@/components/Marquee";
import { Atelier3D } from "@/components/home/Atelier3D";
import { FeaturedBento } from "@/components/home/FeaturedBento";
import { Process } from "@/components/home/Process";
import { Testimonials } from "@/components/home/Testimonials";

export default function Home() {
  return (
    <div data-testid="home-page">
      <HeroEditorial />
      <Marquee />
      <FeaturedBento />
      <Atelier3D />
      <Process />
      <Testimonials />
    </div>
  );
}
