import { Hero } from "@/components/home/Hero";
import { Marquee } from "@/components/Marquee";
import { StorySplit } from "@/components/home/StorySplit";
import { FeaturedBento } from "@/components/home/FeaturedBento";
import { Process } from "@/components/home/Process";
import { Testimonials } from "@/components/home/Testimonials";

export default function Home() {
  return (
    <div data-testid="home-page">
      <Hero />
      <Marquee />
      <FeaturedBento />
      <StorySplit />
      <Process />
      <Testimonials />
    </div>
  );
}
