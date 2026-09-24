import { Suspense, useRef } from "react";
import { Canvas } from "@react-three/fiber";
import { Float, OrbitControls } from "@react-three/drei";
import { useInView } from "framer-motion";
import { PearlModel, PearlLights } from "@/components/three/PearlModel";

export const PearlScene = ({ shape, accent, camera = 7.2, controls = true, float = true, autoRotate = true, testId = "pearl-scene" }) => {
  const ref = useRef(null);
  const inView = useInView(ref, { margin: "100px" });
  return (
  <div ref={ref} className="absolute inset-0" data-testid={testId}>
    {inView && (
    <Canvas camera={{ position: [0, 0.3, camera], fov: 36 }} dpr={[1, 1.5]} gl={{ antialias: true, alpha: true, powerPreference: "high-performance" }}>
      <PearlLights />
      <Suspense fallback={null}>
        {float ? (
          <Float speed={1.4} rotationIntensity={0.1} floatIntensity={0.5}>
            <PearlModel shape={shape} accent={accent} spin={!controls} />
          </Float>
        ) : (
          <PearlModel shape={shape} accent={accent} spin={!controls} />
        )}
      </Suspense>
      {controls && <OrbitControls enableZoom={false} enablePan={false} autoRotate={autoRotate} autoRotateSpeed={1.6} minPolarAngle={0.9} maxPolarAngle={2.1} />}
    </Canvas>
    )}
  </div>
  );
};
