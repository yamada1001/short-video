/**
 * タスクル - メインJavaScript
 * Three.js + GSAP + ScrollTriggerアニメーション
 */

document.addEventListener('DOMContentLoaded', () => {
    // Lucideアイコンの初期化
    lucide.createIcons();

    // ========================================
    // Three.js 3D Background
    // ========================================
    const canvas = document.getElementById('three-bg');
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });

    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    camera.position.z = 5;

    // 白背景
    scene.background = new THREE.Color(0xffffff);
    scene.fog = new THREE.Fog(0xffffff, 5, 20);

    // 波形メッシュ（適度な色）
    const geometry = new THREE.PlaneGeometry(20, 20, 80, 80);
    const material = new THREE.ShaderMaterial({
        uniforms: {
            uTime: { value: 0 },
            uMouseX: { value: 0 },
            uMouseY: { value: 0 }
        },
        vertexShader: `
            uniform float uTime;
            uniform float uMouseX;
            uniform float uMouseY;
            varying vec2 vUv;
            varying float vElevation;

            void main() {
                vUv = uv;
                vec3 pos = position;

                // 波のアニメーション
                float elevation = sin(pos.x * 0.4 + uTime * 0.4) * 0.4;
                elevation += sin(pos.y * 0.3 + uTime * 0.3) * 0.3;
                elevation += sin(pos.x * 0.2 + pos.y * 0.2 + uTime * 0.2) * 0.3;

                // マウスインタラクション
                float distX = pos.x - uMouseX * 10.0;
                float distY = pos.y - uMouseY * 10.0;
                float dist = sqrt(distX * distX + distY * distY);
                elevation += sin(dist - uTime) * 0.25 * (1.0 - dist / 10.0);

                pos.z = elevation;
                vElevation = elevation;

                gl_Position = projectionMatrix * modelViewMatrix * vec4(pos, 1.0);
            }
        `,
        fragmentShader: `
            uniform float uTime;
            varying vec2 vUv;
            varying float vElevation;

            void main() {
                // 適度なグラデーション (violet → blue → cyan)
                vec3 color1 = vec3(0.65, 0.55, 0.85); // ミディアムバイオレット
                vec3 color2 = vec3(0.45, 0.65, 0.90); // ミディアムブルー
                vec3 color3 = vec3(0.40, 0.80, 0.88); // ミディアムシアン

                float mixValue = (vElevation + 1.0) * 0.5;
                vec3 color = mix(color1, color2, mixValue);
                color = mix(color, color3, sin(uTime * 0.2 + vUv.x * 2.0) * 0.5 + 0.5);

                float alpha = 0.25 + vElevation * 0.15; // 適度な透明度
                gl_FragColor = vec4(color, alpha);
            }
        `,
        transparent: true,
        wireframe: false
    });

    const mesh = new THREE.Mesh(geometry, material);
    mesh.rotation.x = -Math.PI * 0.3;
    mesh.position.z = -3;
    scene.add(mesh);

    // パーティクルシステム（適度な数＋見える色）
    const particlesGeometry = new THREE.BufferGeometry();
    const particlesCount = 300; // 150→300に増量
    const positions = new Float32Array(particlesCount * 3);
    const colors = new Float32Array(particlesCount * 3);

    for (let i = 0; i < particlesCount; i++) {
        positions[i * 3] = (Math.random() - 0.5) * 30;
        positions[i * 3 + 1] = (Math.random() - 0.5) * 30;
        positions[i * 3 + 2] = (Math.random() - 0.5) * 30;

        // 見えるカラー (violet, blue, cyan, pink)
        const colorChoice = Math.random();
        if (colorChoice < 0.25) {
            colors[i * 3] = 0.7; colors[i * 3 + 1] = 0.5; colors[i * 3 + 2] = 0.9; // violet
        } else if (colorChoice < 0.5) {
            colors[i * 3] = 0.4; colors[i * 3 + 1] = 0.6; colors[i * 3 + 2] = 0.95; // blue
        } else if (colorChoice < 0.75) {
            colors[i * 3] = 0.3; colors[i * 3 + 1] = 0.8; colors[i * 3 + 2] = 0.9; // cyan
        } else {
            colors[i * 3] = 0.9; colors[i * 3 + 1] = 0.5; colors[i * 3 + 2] = 0.7; // pink
        }
    }

    particlesGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    particlesGeometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const particlesMaterial = new THREE.PointsMaterial({
        size: 0.05,
        transparent: true,
        opacity: 0.6, // 0.3→0.6に増量
        vertexColors: true,
        blending: THREE.AdditiveBlending,
        sizeAttenuation: true
    });

    const particles = new THREE.Points(particlesGeometry, particlesMaterial);
    scene.add(particles);

    // マウス座標
    let mouseX = 0;
    let mouseY = 0;

    document.addEventListener('mousemove', (event) => {
        mouseX = (event.clientX / window.innerWidth) * 2 - 1;
        mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
    });

    // アニメーションループ
    const clock = new THREE.Clock();

    function animateThree() {
        const elapsedTime = clock.getElapsedTime();

        // 波形メッシュの更新
        material.uniforms.uTime.value = elapsedTime;
        material.uniforms.uMouseX.value = mouseX;
        material.uniforms.uMouseY.value = mouseY;
        mesh.rotation.z = elapsedTime * 0.05;

        // パーティクルの回転
        particles.rotation.y = elapsedTime * 0.05;
        particles.rotation.x = elapsedTime * 0.02;

        renderer.render(scene, camera);
        requestAnimationFrame(animateThree);
    }

    animateThree();

    // リサイズ対応
    window.addEventListener('resize', () => {
        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    });

    // ========================================
    // ヘッダースクロール時の影
    // ========================================
    const header = document.querySelector('.header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // ========================================
    // モバイルメニュー
    // ========================================
    const burgerBtn = document.getElementById('burgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    burgerBtn.addEventListener('click', () => {
        burgerBtn.classList.toggle('active');
        mobileMenu.classList.toggle('active');
    });

    // メニューリンククリック時にメニューを閉じる
    const mobileMenuLinks = mobileMenu.querySelectorAll('a');
    mobileMenuLinks.forEach(link => {
        link.addEventListener('click', () => {
            burgerBtn.classList.remove('active');
            mobileMenu.classList.remove('active');
        });
    });

    // ========================================
    // FAQアコーディオン
    // ========================================
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const faqItem = question.parentElement;
            const isActive = faqItem.classList.contains('active');

            // すべてのFAQを閉じる
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });

            // クリックされたFAQを開く（既に開いていたら閉じる）
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // 最初のFAQを開いておく
    if (faqQuestions.length > 0) {
        faqQuestions[0].parentElement.classList.add('active');
    }

    // ========================================
    // GSAP + ScrollTrigger設定
    // ========================================
    gsap.registerPlugin(ScrollTrigger);

    // セクションのフェードインアニメーション
    gsap.utils.toArray('.fade-in-section').forEach(section => {
        gsap.from(section, {
            opacity: 0,
            y: 30,
            duration: 0.8,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: section,
                start: 'top 80%',
                toggleActions: 'play none none none'
            }
        });
    });

    // 課題カードのスタッガーアニメーション
    gsap.from('.problem-card', {
        opacity: 0,
        y: 40,
        duration: 0.6,
        stagger: 0.15,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#problems',
            start: 'top 70%',
            toggleActions: 'play none none none'
        }
    });

    // 機能カードのスタッガーアニメーション
    gsap.from('.feature-card', {
        opacity: 0,
        y: 40,
        duration: 0.6,
        stagger: 0.12,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#features',
            start: 'top 70%',
            toggleActions: 'play none none none'
        }
    });

    // 料金カードのスタッガーアニメーション
    gsap.from('.pricing-card', {
        opacity: 0,
        scale: 0.95,
        duration: 0.6,
        stagger: 0.15,
        ease: 'back.out(1.2)',
        scrollTrigger: {
            trigger: '#pricing',
            start: 'top 70%',
            toggleActions: 'play none none none'
        }
    });

    // 導入事例カードのスタッガーアニメーション
    gsap.from('.case-card', {
        opacity: 0,
        y: 40,
        duration: 0.6,
        stagger: 0.15,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '#cases',
            start: 'top 70%',
            toggleActions: 'play none none none'
        }
    });

    // ========================================
    // 数字カウントアップ（信頼指標）
    // ========================================
    // 1,500社のカウントアップ
    const companiesCount = { value: 0 };
    gsap.to(companiesCount, {
        value: 1500,
        duration: 2,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: '.hero',
            start: 'top 50%',
            toggleActions: 'play none none none'
        },
        onUpdate: function() {
            const companiesElements = document.querySelectorAll('.hero strong');
            if (companiesElements[0]) {
                companiesElements[0].textContent = Math.ceil(companiesCount.value).toLocaleString() + '社';
            }
        }
    });

    // ========================================
    // スムーズスクロール（アンカーリンク）
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '#cta') {
                // #cta は別途処理
                if (href === '#cta') {
                    e.preventDefault();
                    const ctaSection = document.getElementById('cta');
                    if (ctaSection) {
                        ctaSection.scrollIntoView({ behavior: 'smooth' });
                    }
                }
                return;
            }

            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const headerHeight = 64; // ヘッダーの高さ
                const targetPosition = target.offsetTop - headerHeight;
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    console.log('タスクル LP初期化完了');
});
