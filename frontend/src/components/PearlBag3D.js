import { useMemo, useRef } from "react";
import { Canvas, useFrame } from "@react-three/fiber";
import { Float } from "@react-three/drei";
import * as THREE from "three";

const R = 0.115;
const STEP = 0.245;
const W = 1.35, H = 0.75, D = 0.5;

const range = (a, b, s) => {
  const out = [];
  for (let v = a; v <= b + 1e-6; v += s) out.push(v);
  return out;
};

function buildBeads() {
  const white = [];
  const accent = [];
  const xs = range(-W, W, STEP), ys = range(-H, H, STEP), zs = range(-D, D, STEP);
  const flower = new Set(["0,0", "1,0", "-1,0", "0,1", "0,-1"]);
  xs.forEach((x, xi) =>
    ys.forEach((y, yi) => {
      const key = `${xi - Math.floor(xs.length / 2)},${yi - Math.floor(ys.length / 2)}`;
      (flower.has(key) ? accent : white).push([x, y, D]);
      white.push([x, y, -D]);
    })
  );
  ys.forEach((y) => zs.slice(1, -1).forEach((z) => { white.push([-W, y, z]); white.push([W, y, z]); }));
  xs.slice(1, -1).forEach((x) => zs.slice(1, -1).forEach((z) => white.push([x, -H, z])));
  const hr = 0.85;
  for (let i = 0; i <= 22; i++) {
    const t = Math.PI * (i / 22);
    white.push([Math.cos(t) * hr, H + Math.sin(t) * hr + 0.05, 0]);
  }
  return { white, accent };
}

const Beads = ({ positions, color, emissive = "#000000" }) => {
  const ref = useRef();
  const dummy = useMemo(() => new THREE.Object3D(), []);
  useMemo(() => {
    if (!ref.current) return;
  }, []);
  useFrame(() => {
    if (!ref.current || ref.current.userData.done) return;
    positions.forEach((p, i) => {
      dummy.position.set(...p);
      dummy.updateMatrix();
      ref.current.setMatrixAt(i, dummy.matrix);
    });
    ref.current.instanceMatrix.needsUpdate = true;
    ref.current.userData.done = true;
  });
  return (
    <instancedMesh ref={ref} args={[null, null, positions.length]} castShadow>
      <sphereGeometry args={[R, 28, 28]} />
      <meshPhysicalMaterial color={color} roughness={0.18} metalness={0.05} clearcoat={1} clearcoatRoughness={0.08} emissive={emissive} emissiveIntensity={0.15} />
    </instancedMesh>
  );
};

const Bag = () => {
  const group = useRef();
  const { white, accent } = useMemo(buildBeads, []);
  const target = useRef({ x: 0, y: 0 });

  useFrame(({ pointer, clock }) => {
    target.current.x = THREE.MathUtils.lerp(target.current.x, pointer.y * 0.35, 0.05);
    target.current.y = THREE.MathUtils.lerp(target.current.y, pointer.x * 0.6, 0.05);
    group.current.rotation.x = -target.current.x + 0.12;
    group.current.rotation.y = clock.getElapsedTime() * 0.25 + target.current.y;
  });

  return (
    <group ref={group} position={[0, -0.25, 0]}>
      <Beads positions={white} color="#FAF8F5" />
      <Beads positions={accent} color="#c084fc" emissive="#7c3aed" />
    </group>
  );
};

export const PearlBag3D = () => (
  <div className="absolute inset-0" data-testid="hero-3d-canvas">
    <Canvas camera={{ position: [0, 0.4, 5.2], fov: 38 }} dpr={[1, 1.8]} gl={{ antialias: true, alpha: true }}>
      <ambientLight intensity={1.1} color="#FFF8F0" />
      <directionalLight position={[5, 8, 6]} intensity={2.6} />
      <directionalLight position={[-6, -3, -2]} intensity={0.9} color="#E2D5C3" />
      <pointLight position={[0, 2, 3]} intensity={12} color="#FFF3E0" />
      <spotLight position={[-3, 5, 4]} intensity={20} angle={0.4} penumbra={1} color="#ffffff" />
      <Float speed={1.4} rotationIntensity={0.15} floatIntensity={0.6}>
        <Bag />
      </Float>
    </Canvas>
  </div>
);
