/**
 * Three.js 背景エフェクト
 * パーティクルの波とグラデーション効果
 */

(function() {
    'use strict';

    let scene, camera, renderer, particles;
    let mouseX = 0, mouseY = 0;
    let windowHalfX = window.innerWidth / 2;
    let windowHalfY = window.innerHeight / 2;

    // デバイス判定
    const isMobile = window.innerWidth <= 768;
    const particleCount = isMobile ? 800 : 2000; // SP版はパーティクル数を減らす

    /**
     * 初期化
     */
    function init() {
        // シーン作成
        scene = new THREE.Scene();

        // カメラ作成
        camera = new THREE.PerspectiveCamera(
            75,
            window.innerWidth / window.innerHeight,
            1,
            1000
        );
        camera.position.z = 400;

        // レンダラー作成
        renderer = new THREE.WebGLRenderer({
            alpha: true,
            antialias: !isMobile // SP版はアンチエイリアスOFFでパフォーマンス向上
        });
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2)); // 最大2倍まで
        renderer.setClearColor(0x000000, 0); // 透明背景

        // canvasを背景に配置
        const canvas = renderer.domElement;
        canvas.style.position = 'fixed';
        canvas.style.top = '0';
        canvas.style.left = '0';
        canvas.style.width = '100%';
        canvas.style.height = '100%';
        canvas.style.zIndex = '-1';
        canvas.style.pointerEvents = 'none';
        document.body.insertBefore(canvas, document.body.firstChild);

        // パーティクル作成
        createParticles();

        // イベントリスナー
        document.addEventListener('mousemove', onDocumentMouseMove, false);
        window.addEventListener('resize', onWindowResize, false);

        // アニメーション開始
        animate();
    }

    /**
     * パーティクル作成
     */
    function createParticles() {
        const geometry = new THREE.BufferGeometry();
        const positions = [];
        const colors = [];
        const sizes = [];

        // カラーパレット（余日のブランドカラー）
        const color1 = new THREE.Color(0x4A90E2); // アクセントブルー
        const color2 = new THREE.Color(0x8B7355); // ナチュラルブラウン
        const color3 = new THREE.Color(0xF5F3F0); // 背景ベージュ

        for (let i = 0; i < particleCount; i++) {
            // ランダムな位置
            const x = Math.random() * 2000 - 1000;
            const y = Math.random() * 2000 - 1000;
            const z = Math.random() * 2000 - 1000;

            positions.push(x, y, z);

            // グラデーションカラー
            const mixRatio = Math.random();
            const color = new THREE.Color();

            if (mixRatio < 0.33) {
                color.lerpColors(color1, color2, Math.random());
            } else if (mixRatio < 0.66) {
                color.lerpColors(color2, color3, Math.random());
            } else {
                color.lerpColors(color1, color3, Math.random());
            }

            colors.push(color.r, color.g, color.b);

            // ランダムなサイズ
            sizes.push(Math.random() * 3 + 1);
        }

        geometry.setAttribute('position', new THREE.Float32BufferAttribute(positions, 3));
        geometry.setAttribute('color', new THREE.Float32BufferAttribute(colors, 3));
        geometry.setAttribute('size', new THREE.Float32BufferAttribute(sizes, 1));

        // マテリアル
        const material = new THREE.PointsMaterial({
            size: 2,
            vertexColors: true,
            transparent: true,
            opacity: 0.6,
            blending: THREE.AdditiveBlending,
            sizeAttenuation: true
        });

        particles = new THREE.Points(geometry, material);
        scene.add(particles);
    }

    /**
     * マウス移動イベント
     */
    function onDocumentMouseMove(event) {
        mouseX = (event.clientX - windowHalfX) * 0.1;
        mouseY = (event.clientY - windowHalfY) * 0.1;
    }

    /**
     * リサイズイベント
     */
    function onWindowResize() {
        windowHalfX = window.innerWidth / 2;
        windowHalfY = window.innerHeight / 2;

        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();

        renderer.setSize(window.innerWidth, window.innerHeight);
    }

    /**
     * アニメーションループ
     */
    function animate() {
        requestAnimationFrame(animate);
        render();
    }

    /**
     * レンダリング
     */
    function render() {
        const time = Date.now() * 0.00005;

        // カメラをマウスに追従（ゆっくり）
        camera.position.x += (mouseX - camera.position.x) * 0.05;
        camera.position.y += (-mouseY - camera.position.y) * 0.05;
        camera.lookAt(scene.position);

        // パーティクルを波のように動かす
        const positions = particles.geometry.attributes.position.array;
        const colors = particles.geometry.attributes.color.array;

        for (let i = 0; i < particleCount; i++) {
            const i3 = i * 3;

            // 波のようなうねり
            positions[i3] = positions[i3] + Math.sin(time + i) * 0.5;
            positions[i3 + 1] = positions[i3 + 1] + Math.cos(time + i * 1.1) * 0.5;

            // 境界チェック（パーティクルが画面外に出たら反対側に戻す）
            if (positions[i3] > 1000) positions[i3] = -1000;
            if (positions[i3] < -1000) positions[i3] = 1000;
            if (positions[i3 + 1] > 1000) positions[i3 + 1] = -1000;
            if (positions[i3 + 1] < -1000) positions[i3 + 1] = 1000;

            // カラーを時間で変化させる（微妙に）
            const colorShift = Math.sin(time + i * 0.1) * 0.1;
            colors[i3] = Math.max(0, Math.min(1, colors[i3] + colorShift * 0.01));
            colors[i3 + 1] = Math.max(0, Math.min(1, colors[i3 + 1] + colorShift * 0.01));
            colors[i3 + 2] = Math.max(0, Math.min(1, colors[i3 + 2] + colorShift * 0.01));
        }

        particles.geometry.attributes.position.needsUpdate = true;
        particles.geometry.attributes.color.needsUpdate = true;

        // パーティクル全体をゆっくり回転
        particles.rotation.y = time * 0.3;
        particles.rotation.x = time * 0.2;

        renderer.render(scene, camera);
    }

    // ページ読み込み完了後に初期化
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
