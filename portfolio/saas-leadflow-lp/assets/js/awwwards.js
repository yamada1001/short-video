/**
 * LeadFlow - Awwwards-style Ultra Rich LP
 * Three.js + GSAP + Lenis Implementation
 */

// ====================================
// 1. Loading Animation
// ====================================
let loadingProgress = 0;
const loader = document.getElementById('loader');
const loaderProgress = document.getElementById('loaderProgress');

const loadingInterval = setInterval(() => {
    loadingProgress += Math.random() * 15;
    if (loadingProgress >= 100) {
        loadingProgress = 100;
        clearInterval(loadingInterval);
        setTimeout(() => {
            loader.style.opacity = '0';
            loader.style.visibility = 'hidden';
        }, 500);
    }
    loaderProgress.style.width = loadingProgress + '%';
}, 150);

// ====================================
// 2. Custom Cursor
// ====================================
const cursor = document.getElementById('cursor');
const cursorFollower = document.getElementById('cursorFollower');

document.addEventListener('mousemove', (e) => {
    cursor.style.left = e.clientX + 'px';
    cursor.style.top = e.clientY + 'px';

    setTimeout(() => {
        cursorFollower.style.left = e.clientX + 'px';
        cursorFollower.style.top = e.clientY + 'px';
    }, 100);
});

// Hover effects for interactive elements
const interactiveElements = document.querySelectorAll('a, button, .feature-card, .pricing-card');
interactiveElements.forEach(el => {
    el.addEventListener('mouseenter', () => {
        cursorFollower.classList.add('hover');
    });
    el.addEventListener('mouseleave', () => {
        cursorFollower.classList.remove('hover');
    });
});

// ====================================
// 3. Three.js Background
// ====================================
const canvas = document.getElementById('webgl');
const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });

renderer.setSize(window.innerWidth, window.innerHeight);
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
camera.position.z = 5;

// Create wave mesh
const geometry = new THREE.PlaneGeometry(15, 15, 50, 50);
const material = new THREE.ShaderMaterial({
    uniforms: {
        uTime: { value: 0 },
        uColorStart: { value: new THREE.Color('#667eea') },
        uColorEnd: { value: new THREE.Color('#764ba2') }
    },
    vertexShader: `
        uniform float uTime;
        varying vec2 vUv;
        varying float vElevation;

        void main() {
            vUv = uv;

            vec3 pos = position;
            float elevation = sin(pos.x * 2.0 + uTime * 0.5) * 0.3;
            elevation += sin(pos.y * 3.0 + uTime * 0.3) * 0.2;
            pos.z = elevation;

            vElevation = elevation;

            gl_Position = projectionMatrix * modelViewMatrix * vec4(pos, 1.0);
        }
    `,
    fragmentShader: `
        uniform vec3 uColorStart;
        uniform vec3 uColorEnd;
        varying vec2 vUv;
        varying float vElevation;

        void main() {
            vec3 color = mix(uColorStart, uColorEnd, vElevation + 0.5);
            float alpha = 0.1 + vElevation * 0.2;
            gl_FragColor = vec4(color, alpha);
        }
    `,
    transparent: true,
    wireframe: false
});

const mesh = new THREE.Mesh(geometry, material);
mesh.rotation.x = -Math.PI * 0.3;
scene.add(mesh);

// Add particle system
const particlesGeometry = new THREE.BufferGeometry();
const particlesCount = 500;
const positions = new Float32Array(particlesCount * 3);

for (let i = 0; i < particlesCount * 3; i++) {
    positions[i] = (Math.random() - 0.5) * 20;
}

particlesGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));

const particlesMaterial = new THREE.PointsMaterial({
    color: '#667eea',
    size: 0.02,
    transparent: true,
    opacity: 0.6,
    blending: THREE.AdditiveBlending
});

const particles = new THREE.Points(particlesGeometry, particlesMaterial);
scene.add(particles);

// Animation loop
const clock = new THREE.Clock();

function animateThree() {
    const elapsedTime = clock.getElapsedTime();

    // Update wave
    material.uniforms.uTime.value = elapsedTime;
    mesh.rotation.z = elapsedTime * 0.05;

    // Update particles
    particles.rotation.y = elapsedTime * 0.05;
    particles.rotation.x = elapsedTime * 0.03;

    renderer.render(scene, camera);
    requestAnimationFrame(animateThree);
}

animateThree();

// Responsive
window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
});

// ====================================
// 4. Lenis Smooth Scroll
// ====================================
const lenis = new Lenis({
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smooth: true
});

function raf(time) {
    lenis.raf(time);
    requestAnimationFrame(raf);
}

requestAnimationFrame(raf);

// Anchor link smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            lenis.scrollTo(target, { offset: 0 });
        }
    });
});

// ====================================
// 5. GSAP Animations
// ====================================
gsap.registerPlugin(ScrollTrigger);

// Hero title reveal
gsap.from('.hero__title-line', {
    y: 100,
    opacity: 0,
    duration: 1.2,
    stagger: 0.2,
    ease: 'power4.out',
    delay: 1
});

gsap.from('.hero__subtitle', {
    y: 30,
    opacity: 0,
    duration: 1,
    delay: 1.6,
    ease: 'power3.out'
});

gsap.from('.hero__cta', {
    y: 30,
    opacity: 0,
    duration: 1,
    delay: 1.8,
    ease: 'power3.out'
});

gsap.from('.hero__scroll', {
    opacity: 0,
    duration: 1,
    delay: 2.2,
    ease: 'power3.out'
});

// Stats counter animation
const statNumbers = document.querySelectorAll('.stat-item__number');
statNumbers.forEach(stat => {
    const target = parseInt(stat.dataset.count);

    ScrollTrigger.create({
        trigger: stat,
        start: 'top 80%',
        onEnter: () => {
            gsap.to(stat, {
                innerHTML: target,
                duration: 2,
                ease: 'power2.out',
                snap: { innerHTML: 1 },
                onUpdate: function() {
                    stat.innerHTML = Math.ceil(stat.innerHTML);
                }
            });
        },
        once: true
    });
});

// Section animations
gsap.utils.toArray('.section').forEach((section, i) => {
    if (i === 0) return; // Skip hero

    gsap.from(section, {
        y: 100,
        opacity: 0,
        duration: 1.2,
        ease: 'power3.out',
        scrollTrigger: {
            trigger: section,
            start: 'top 80%',
            once: true
        }
    });
});

// Feature cards
gsap.from('.feature-card', {
    y: 60,
    opacity: 0,
    duration: 1,
    stagger: 0.2,
    ease: 'power3.out',
    scrollTrigger: {
        trigger: '.features__grid',
        start: 'top 70%',
        once: true
    }
});

// Pricing cards
gsap.from('.pricing-card', {
    y: 60,
    opacity: 0,
    duration: 1,
    stagger: 0.15,
    ease: 'power3.out',
    scrollTrigger: {
        trigger: '.pricing__grid',
        start: 'top 70%',
        once: true
    }
});

// Demo section
gsap.from('.demo__text', {
    x: -100,
    opacity: 0,
    duration: 1.2,
    ease: 'power3.out',
    scrollTrigger: {
        trigger: '.demo',
        start: 'top 70%',
        once: true
    }
});

gsap.from('.demo__visual', {
    x: 100,
    opacity: 0,
    duration: 1.2,
    ease: 'power3.out',
    scrollTrigger: {
        trigger: '.demo',
        start: 'top 70%',
        once: true
    }
});

// CTA section
gsap.from('.cta__content', {
    scale: 0.9,
    opacity: 0,
    duration: 1.2,
    ease: 'back.out(1.2)',
    scrollTrigger: {
        trigger: '.cta',
        start: 'top 70%',
        once: true
    }
});

// Nav scroll effect
ScrollTrigger.create({
    start: 'top -80',
    onUpdate: (self) => {
        const nav = document.querySelector('.nav');
        if (self.direction === -1) {
            nav.style.transform = 'translateY(0)';
        } else {
            if (self.scroll() > 100) {
                nav.style.transform = 'translateY(-100%)';
            }
        }
    }
});

// Parallax effect on scroll
gsap.to(mesh.rotation, {
    x: -Math.PI * 0.5,
    scrollTrigger: {
        trigger: 'body',
        start: 'top top',
        end: 'bottom bottom',
        scrub: 1
    }
});

// ====================================
// 6. Mobile Menu
// ====================================
const navBurger = document.getElementById('navBurger');
const navLinks = document.querySelector('.nav__links');

navBurger.addEventListener('click', () => {
    navBurger.classList.toggle('active');
    navLinks.classList.toggle('active');
});

// Close menu on link click
document.querySelectorAll('.nav__link').forEach(link => {
    link.addEventListener('click', () => {
        navBurger.classList.remove('active');
        navLinks.classList.remove('active');
    });
});

// ====================================
// 7. Form handling
// ====================================
const ctaForm = document.querySelector('.cta__form');
if (ctaForm) {
    ctaForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const email = ctaForm.querySelector('input[type="email"]').value;
        console.log('Email submitted:', email);

        // Success feedback
        gsap.to(ctaForm, {
            scale: 0.95,
            duration: 0.1,
            yoyo: true,
            repeat: 1,
            onComplete: () => {
                alert('Thank you! We will contact you soon.');
                ctaForm.reset();
            }
        });
    });
}

// ====================================
// 8. Scroll progress indicator
// ====================================
const scrollProgress = document.createElement('div');
scrollProgress.className = 'scroll-progress';
scrollProgress.style.cssText = `
    position: fixed;
    top: 0;
    left: 0;
    width: 0%;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
    z-index: 9998;
    transition: width 0.1s ease-out;
`;
document.body.appendChild(scrollProgress);

window.addEventListener('scroll', () => {
    const windowHeight = document.documentElement.scrollHeight - window.innerHeight;
    const scrolled = (window.scrollY / windowHeight) * 100;
    scrollProgress.style.width = scrolled + '%';
});

// ====================================
// 9. Performance optimization
// ====================================
// Reduce Three.js complexity on mobile
if (window.innerWidth < 768) {
    particlesMaterial.opacity = 0.3;
    mesh.rotation.x = -Math.PI * 0.2;
}

console.log('LeadFlow Awwwards LP initialized successfully');
