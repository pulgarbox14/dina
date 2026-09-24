import { useRef } from "react";

export const TiltCard = ({ children, className = "", max = 9, ...rest }) => {
  const ref = useRef(null);

  const onMove = (e) => {
    const el = ref.current;
    const r = el.getBoundingClientRect();
    const px = (e.clientX - r.left) / r.width;
    const py = (e.clientY - r.top) / r.height;
    el.style.setProperty("--mx", `${px * 100}%`);
    el.style.setProperty("--my", `${py * 100}%`);
    el.style.transform = `perspective(1100px) rotateX(${(0.5 - py) * max}deg) rotateY(${(px - 0.5) * max}deg) translateY(-6px)`;
    el.style.boxShadow = "0 30px 60px -30px rgba(18,19,22,0.35)";
  };
  const onLeave = () => {
    const el = ref.current;
    el.style.transform = "perspective(1100px) rotateX(0deg) rotateY(0deg) translateY(0)";
    el.style.boxShadow = "none";
  };

  return (
    <div ref={ref} onMouseMove={onMove} onMouseLeave={onLeave} className={`tilt-card relative ${className}`} {...rest}>
      {children}
      <span className="tilt-shine" />
    </div>
  );
};
