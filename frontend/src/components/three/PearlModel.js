import { useMemo, useRef } from "react";
import { useFrame } from "@react-three/fiber";
import * as THREE from "three";

const R = 0.115;
const STEP = 0.245;

export const ACCENTS = {
  "Fleur orange": "#f97316",
  "Fleur violette": "#a855f7",
  Ambre: "#f59e0b",
  "Blanc, bleu & gris": "#60a5fa",
};

export const shapeFor = (product) => {
  if (!product) return "round";
  if (product.category === "bijoux") return "necklace";
  if (["sac-lune-nacre", "sac-nacre-classique", "cabas-fleur-de-soleil"].includes(product.id)) return "round";
  return "tote";
};

const range = (a, b, s) => {
  const out = [];
  for (let v = a; v <= b + 1e-6; v += s) out.push(v);
  return out;
};

const flowerKeys = (centers) => {
  const set = new Set();
  centers.forEach(([r, a]) => [[r, a], [r + 1, a], [r - 1, a], [r, a + 1], [r, a - 1]].forEach(([x, y]) => set.add(`${x},${y}`)));
  return set;
};

function buildTote(hasAccent) {
  const W = 1.25, H = 0.75, D = 0.5;
  const white = [], accent = [];
  const xs = range(-W, W, STEP), ys = range(-H, H, STEP), zs = range(-D, D, STEP);
  const cx = Math.floor(xs.length / 2), cy = Math.floor(ys.length / 2);
  const flowers = hasAccent ? flowerKeys([[0, 0], [3, 1], [-3, 1]]) : new Set();
  xs.forEach((x, xi) =>
    ys.forEach((y, yi) => {
      const k = `${xi - cx},${yi - cy}`;
      (flowers.has(k) ? accent : white).push([x, y, D, 1]);
      white.push([x, y, -D, 1]);
    })
  );
  ys.forEach((y) => zs.slice(1, -1).forEach((z) => { white.push([-W, y, z, 1]); white.push([W, y, z, 1]); }));
  xs.slice(1, -1).forEach((x) => zs.slice(1, -1).forEach((z) => white.push([x, -H, z, 1])));
  for (let i = 0; i <= 22; i++) {
    const t = Math.PI * (i / 22);
    white.push([Math.cos(t) * 0.85, H + Math.sin(t) * 0.85 + 0.05, 0, 1]);
  }
  return { white, accent };
}

function buildRound(hasAccent) {
  const RX = 1.25, RZ = 0.6, H = 0.7, N = 36;
  const white = [], accent = [];
  const ys = range(-H, H, STEP);
  const cy = Math.floor(ys.length / 2);
  const front = Math.round(N / 4);
  const flowers = hasAccent ? flowerKeys([[0, front], [1, front - 6], [-1, front + 6]]) : new Set();
  ys.forEach((y, yi) => {
    for (let i = 0; i < N; i++) {
      const t = (i / N) * Math.PI * 2;
      const k = `${yi - cy},${i}`;
      (flowers.has(k) ? accent : white).push([Math.cos(t) * RX, y, Math.sin(t) * RZ, 1]);
    }
  });
  range(-RX + STEP, RX - STEP, STEP).forEach((x) =>
    range(-RZ + STEP, RZ - STEP, STEP).forEach((z) => {
      if ((x / RX) ** 2 + (z / RZ) ** 2 < 0.85) white.push([x, -H, z, 1]);
    })
  );
  [-0.15, 0.15].forEach((z) => {
    for (let i = 0; i <= 20; i++) {
      const t = Math.PI * (i / 20);
      white.push([Math.cos(t) * 0.7, H + Math.sin(t) * 0.75 + 0.05, z, 0.85]);
    }
  });
  return { white, accent };
}

function buildNecklace(hasAccent) {
  const white = [], accent = [];
  const N = 64;
  for (let i = 0; i < N; i++) {
    const t = (i / N) * Math.PI * 2;
    const drop = Math.max(0, -Math.cos(t));
    const s = 0.8 + drop * 0.9;
    const p = [Math.sin(t) * 1.45, Math.cos(t) * 1.0 - drop * 0.35, 0, s];
    (hasAccent && drop > 0.6 && i % 3 === 0 ? accent : white).push(p);
  }
  for (let i = 0; i < N; i++) {
    const t = (i / N) * Math.PI * 2;
    white.push([Math.sin(t) * 1.15, Math.cos(t) * 0.8 + 0.1, 0.05, 0.65]);
  }
  [-2.2, 2.2].forEach((x) => {
    for (let j = 0; j < 4; j++) white.push([x, 0.6 - j * 0.22, 0, 0.6]);
    (hasAccent ? accent : white).push([x, -0.35, 0, 1.15]);
  });
  return { white, accent };
}

const BUILDERS = { tote: buildTote, round: buildRound, necklace: buildNecklace };

const Beads = ({ items, color, emissive = "#000000" }) => {
  const ref = useRef();
  const dummy = useMemo(() => new THREE.Object3D(), []);
  useFrame(() => {
    if (!ref.current || ref.current.userData.done) return;
    items.forEach(([x, y, z, s], i) => {
      dummy.position.set(x, y, z);
      dummy.scale.setScalar(s);
      dummy.updateMatrix();
      ref.current.setMatrixAt(i, dummy.matrix);
    });
    ref.current.instanceMatrix.needsUpdate = true;
    ref.current.userData.done = true;
  });
  if (items.length === 0) return null;
  return (
    <instancedMesh ref={ref} args={[null, null, items.length]}>
      <sphereGeometry args={[R, 28, 28]} />
      <meshPhysicalMaterial color={color} roughness={0.16} metalness={0.05} clearcoat={1} clearcoatRoughness={0.06} emissive={emissive} emissiveIntensity={0.12} />
    </instancedMesh>
  );
};

export const PearlModel = ({ shape = "round", accent = null, spin = true }) => {
  const group = useRef();
  const { white, accent: acc } = useMemo(() => BUILDERS[shape](!!accent), [shape, accent]);
  useFrame(({ clock }) => {
    if (spin && group.current) group.current.rotation.y = clock.getElapsedTime() * 0.3;
  });
  return (
    <group ref={group} position={[0, shape === "necklace" ? 0.1 : -0.2, 0]}>
      <Beads key={`w-${shape}-${!!accent}`} items={white} color="#ffffff" />
      {accent && <Beads key={`a-${shape}-${accent}`} items={acc} color={accent} emissive={accent} />}
    </group>
  );
};

export const PearlLights = () => (
  <>
    <ambientLight intensity={1.1} color="#ffffff" />
    <directionalLight position={[5, 8, 6]} intensity={2.6} />
    <directionalLight position={[-6, -3, -2]} intensity={0.9} color="#e9e9ee" />
    <pointLight position={[0, 2, 3]} intensity={12} color="#ffffff" />
    <spotLight position={[-3, 5, 4]} intensity={20} angle={0.4} penumbra={1} />
  </>
);
