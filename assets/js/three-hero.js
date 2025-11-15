/**
 * Three.js Hero Background
 * 3Dパーティクルフィールド - マウスインタラクティブ
 */

(function() {
    'use strict';

    console.log('🎨 Three.js Hero Background - 初期化開始');

    // Three.jsの読み込みを確認
    if (typeof THREE === 'undefined') {
        console.warn('⚠️ Three.js is not loaded');
        return;
    }

    // ヒーローセクションを取得
    const heroSection = document.querySelector('.hero-v2');
    if (!heroSection) {
        console.warn('⚠️ Hero section not found');
        return;
    }

    // モバイルではスキップ
    if (window.innerWidth < 768) {
        console.log('📱 モバイルデバイスのためThree.jsをスキップ');
        return;
    }

    // Canvas要素を作成
    const canvas = document.createElement('canvas');
    canvas.className = 'hero-three-canvas';
    canvas.style.cssText = `
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
    `;
    heroSection.insertBefore(canvas, heroSection.firstChild);

    // Three.js設定
    let scene, camera, renderer, particles;
    let mouseX = 0, mouseY = 0;
    let targetX = 0, targetY = 0;

    const particleCount = 800; // パーティクル数
    const particlePositions = [];
    const particleColors = [];

    /**
     * シーンの初期化
     */
    function initScene() {
        // シーン作成
        scene = new THREE.Scene();

        // カメラ作成
        camera = new THREE.PerspectiveCamera(
            75,
            heroSection.offsetWidth / heroSection.offsetHeight,
            0.1,
            1000
        );
        camera.position.z = 50;

        // レンダラー作成
        renderer = new THREE.WebGLRenderer({
            canvas: canvas,
            alpha: true,
            antialias: true
        });
        renderer.setSize(heroSection.offsetWidth, heroSection.offsetHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

        console.log('✅ Three.js シーン初期化完了');
    }

    /**
     * パーティクルシステムの作成
     */
    function createParticles() {
        const geometry = new THREE.BufferGeometry();

        // パーティクルの位置を生成
        for (let i = 0; i < particleCount; i++) {
            const x = (Math.random() - 0.5) * 100;
            const y = (Math.random() - 0.5) * 100;
            const z = (Math.random() - 0.5) * 100;

            particlePositions.push(x, y, z);

            // 色（ブラウンとグリーンのグラデーション）
            const color = new THREE.Color();
            if (Math.random() > 0.5) {
                // ブラウン系: #8B7355
                color.setRGB(0.545, 0.451, 0.333);
            } else {
                // グリーン系: #428570
                color.setRGB(0.259, 0.522, 0.439);
            }

            particleColors.push(color.r, color.g, color.b);
        }

        // ジオメトリに位置と色を設定
        geometry.setAttribute(
            'position',
            new THREE.Float32BufferAttribute(particlePositions, 3)
        );
        geometry.setAttribute(
            'color',
            new THREE.Float32BufferAttribute(particleColors, 3)
        );

        // マテリアル作成
        const material = new THREE.PointsMaterial({
            size: 0.8,
            vertexColors: true,
            transparent: true,
            opacity: 0.6,
            blending: THREE.AdditiveBlending,
            sizeAttenuation: true
        });

        // パーティクルシステムを作成
        particles = new THREE.Points(geometry, material);
        scene.add(particles);

        console.log(`✨ ${particleCount}個のパーティクルを生成`);
    }

    /**
     * マウス移動イベント
     */
    function onMouseMove(event) {
        mouseX = (event.clientX / window.innerWidth) * 2 - 1;
        mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
    }

    /**
     * リサイズ対応
     */
    function onWindowResize() {
        camera.aspect = heroSection.offsetWidth / heroSection.offsetHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(heroSection.offsetWidth, heroSection.offsetHeight);
    }

    /**
     * アニメーションループ
     */
    function animate() {
        requestAnimationFrame(animate);

        // マウスに追従（スムーズに）
        targetX += (mouseX - targetX) * 0.05;
        targetY += (mouseY - targetY) * 0.05;

        // パーティクルシステムを回転
        particles.rotation.x += 0.0003;
        particles.rotation.y += 0.0005;

        // マウスの動きに応じてカメラを移動
        camera.position.x += (targetX * 5 - camera.position.x) * 0.05;
        camera.position.y += (targetY * 5 - camera.position.y) * 0.05;
        camera.lookAt(scene.position);

        // パーティクルの個別アニメーション
        const positions = particles.geometry.attributes.position.array;
        const time = Date.now() * 0.0001;

        for (let i = 0; i < particleCount; i++) {
            const i3 = i * 3;

            // ゆっくりとした波打つ動き
            positions[i3 + 1] += Math.sin(time + positions[i3]) * 0.01;

            // 境界チェック（パーティクルが遠くに行きすぎないように）
            if (positions[i3 + 1] > 50) positions[i3 + 1] = -50;
            if (positions[i3 + 1] < -50) positions[i3 + 1] = 50;
        }

        particles.geometry.attributes.position.needsUpdate = true;

        // レンダリング
        renderer.render(scene, camera);
    }

    /**
     * 初期化
     */
    function init() {
        try {
            initScene();
            createParticles();

            // イベントリスナー
            window.addEventListener('mousemove', onMouseMove, false);
            window.addEventListener('resize', onWindowResize, false);

            // アニメーション開始
            animate();

            console.log('✅ Three.js Hero Background - 初期化完了');
        } catch (error) {
            console.error('❌ Three.js初期化エラー:', error);
        }
    }

    // ページロード後に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
