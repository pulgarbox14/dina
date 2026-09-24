/*
 * Dina Perles — sacs et parures en perles en 3D (Three.js, sans React).
 * Portage de frontend/src/components/three/{PearlModel,PearlScene}.js :
 * même géométrie perle par perle, mêmes lumières, mêmes réglages de caméra.
 *
 * Utilisation dans une page PHP :
 *   <div data-pearl-scene data-shape="round|tote|necklace" data-accent="#f97316" data-camera="7"></div>
 * Mise à jour : element.dispatchEvent(new CustomEvent("pearl:update", { detail: { shape, accent } }))
 *
 * Compilation : npm run build:js  →  public/assets/js/pearl3d.js
 */
import {
    ACESFilmicToneMapping,
    AmbientLight,
    Clock,
    DirectionalLight,
    Group,
    InstancedMesh,
    MathUtils,
    MeshPhysicalMaterial,
    Object3D,
    PerspectiveCamera,
    PointLight,
    Scene,
    SphereGeometry,
    SpotLight,
    WebGLRenderer,
} from "three";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";

const R = 0.115;
const STEP = 0.245;

/* ——— Géométrie : positions [x, y, z, échelle] de chaque perle ——— */

const range = (a, b, s) => {
    const out = [];
    for (let v = a; v <= b + 1e-6; v += s) out.push(v);
    return out;
};

const flowerKeys = (centers) => {
    const set = new Set();
    centers.forEach(([r, a]) =>
        [[r, a], [r + 1, a], [r - 1, a], [r, a + 1], [r, a - 1]].forEach(([x, y]) => set.add(`${x},${y}`))
    );
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

/* ——— Rendu ——— */

const sphere = new SphereGeometry(R, 28, 28);
const dummy = new Object3D();

function beads(items, color, emissive = "#000000") {
    const material = new MeshPhysicalMaterial({
        color,
        roughness: 0.16,
        metalness: 0.05,
        clearcoat: 1,
        clearcoatRoughness: 0.06,
        emissive,
        emissiveIntensity: 0.12,
    });
    const mesh = new InstancedMesh(sphere, material, items.length);
    items.forEach(([x, y, z, s], i) => {
        dummy.position.set(x, y, z);
        dummy.scale.setScalar(s);
        dummy.updateMatrix();
        mesh.setMatrixAt(i, dummy.matrix);
    });
    mesh.instanceMatrix.needsUpdate = true;
    return mesh;
}

function addLights(scene) {
    scene.add(new AmbientLight("#ffffff", 1.1));
    const key = new DirectionalLight("#ffffff", 2.6);
    key.position.set(5, 8, 6);
    const fill = new DirectionalLight("#e9e9ee", 0.9);
    fill.position.set(-6, -3, -2);
    const point = new PointLight("#ffffff", 12);
    point.position.set(0, 2, 3);
    const spot = new SpotLight("#ffffff", 20, 0, 0.4, 1);
    spot.position.set(-3, 5, 4);
    scene.add(key, fill, point, spot);
}

class PearlScene {
    constructor(el) {
        this.el = el;
        this.shape = el.dataset.shape || "round";
        this.accent = el.dataset.accent || null;
        this.fixedCamera = el.dataset.camera ? Number(el.dataset.camera) : null;
        this.visible = false;
        this.clock = new Clock();

        this.renderer = new WebGLRenderer({ antialias: true, alpha: true, powerPreference: "high-performance" });
        this.renderer.setPixelRatio(Math.min(Math.max(window.devicePixelRatio || 1, 1), 1.5));
        this.renderer.toneMapping = ACESFilmicToneMapping;
        this.renderer.setClearColor(0x000000, 0);
        el.appendChild(this.renderer.domElement);

        this.scene = new Scene();
        addLights(this.scene);
        this.camera = new PerspectiveCamera(36, 1, 0.1, 100);

        // Équivalent de <Float speed={1.4} rotationIntensity={0.1} floatIntensity={0.5}> (drei).
        this.float = new Group();
        this.model = new Group();
        this.float.add(this.model);
        this.scene.add(this.float);
        this.floatOffset = Math.random() * 10000;

        // Équivalent de <OrbitControls enableZoom={false} enablePan={false} autoRotate …> (drei).
        this.controls = new OrbitControls(this.camera, this.renderer.domElement);
        Object.assign(this.controls, {
            enableZoom: false,
            enablePan: false,
            enableDamping: true,
            autoRotate: true,
            autoRotateSpeed: 1.6,
            minPolarAngle: 0.9,
            maxPolarAngle: 2.1,
        });

        this.build();
        this.resize();
        new ResizeObserver(() => this.resize()).observe(el);
        el.addEventListener("pearl:update", (e) => this.update(e.detail || {}));
        this.loop = this.loop.bind(this);
    }

    cameraDistance() {
        if (this.fixedCamera) return this.fixedCamera;
        return this.shape === "tote" ? 7.4 : 7;
    }

    build() {
        this.model.children.forEach((m) => m.material.dispose());
        this.model.clear();
        const { white, accent } = BUILDERS[this.shape](!!this.accent);
        this.model.add(beads(white, "#ffffff"));
        if (this.accent && accent.length) this.model.add(beads(accent, this.accent, this.accent));
        this.model.position.y = this.shape === "necklace" ? 0.1 : -0.2;

        // Garde l'angle de vue choisi par le visiteur, ajuste seulement la distance.
        const dir = this.camera.position.lengthSq() > 0 ? this.camera.position.clone().normalize() : null;
        const d = this.cameraDistance();
        if (dir) this.camera.position.copy(dir.multiplyScalar(d));
        else this.camera.position.set(0, 0.3, d);
        this.controls.update();
        this.render();
    }

    update({ shape, accent }) {
        if (shape !== undefined && BUILDERS[shape]) this.shape = shape;
        if (accent !== undefined) this.accent = accent || null;
        this.build();
    }

    resize() {
        const { clientWidth: w, clientHeight: h } = this.el;
        if (!w || !h) return;
        this.renderer.setSize(w, h, false);
        this.camera.aspect = w / h;
        this.camera.updateProjectionMatrix();
        this.render();
    }

    setVisible(visible) {
        if (visible === this.visible) return;
        this.visible = visible;
        if (visible) {
            this.clock.getDelta();
            requestAnimationFrame(this.loop);
        }
    }

    loop() {
        if (!this.visible) return;
        const delta = Math.min(this.clock.getDelta(), 0.1);
        const t = this.floatOffset + this.clock.elapsedTime;
        const speed = 1.4, rot = 0.1, floatIntensity = 0.5;
        this.float.rotation.set(
            (Math.cos((t / 4) * speed) / 8) * rot,
            (Math.sin((t / 4) * speed) / 8) * rot,
            (Math.sin((t / 4) * speed) / 20) * rot
        );
        this.float.position.y = MathUtils.mapLinear(Math.sin((t / 4) * speed) / 10, -0.1, 0.1, -0.1, 0.1) * floatIntensity;
        this.controls.update(delta);
        this.render();
        requestAnimationFrame(this.loop);
    }

    render() {
        this.renderer.render(this.scene, this.camera);
    }
}

function webglAvailable() {
    try {
        const c = document.createElement("canvas");
        return !!(window.WebGLRenderingContext && (c.getContext("webgl2") || c.getContext("webgl")));
    } catch (_) {
        return false;
    }
}

function init() {
    const elements = document.querySelectorAll("[data-pearl-scene]");
    if (!elements.length || !webglAvailable()) return;
    const reduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    // Création paresseuse : la scène n'est construite qu'à l'approche de l'écran,
    // puis l'animation se met en pause dès qu'elle en sort (économise batterie et processeur).
    const scenes = new Map();
    const io = new IntersectionObserver(
        (entries) =>
            entries.forEach((entry) => {
                let scene = scenes.get(entry.target);
                if (!scene && entry.isIntersecting) {
                    try {
                        scene = new PearlScene(entry.target);
                        if (reduced) scene.controls.autoRotate = false;
                        scenes.set(entry.target, scene);
                    } catch (err) {
                        console.warn("3D indisponible :", err);
                        io.unobserve(entry.target);
                        return;
                    }
                }
                scene?.setVisible(entry.isIntersecting);
            }),
        { rootMargin: "100px" }
    );
    elements.forEach((el) => io.observe(el));
}

if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", init);
else init();
