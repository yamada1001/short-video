/**
 * Three.js Global Background - スクロール連動
 * 全画面固定背景、スクロールに応じて色・動きが変化
 */

(function() {
    'use strict';

    console.log('🌌 Three.js Global Background - 初期化開始');

    // モバイルではスキップ
    if (window.innerWidth < 768) {
        console.log('📱 モバイルデバイスのためThree.js背景をスキップ');
        return;
    }

    // Three.jsライブラリチェック
    if (typeof THREE === 'undefined') {
        console.warn('⚠️ Three.js is not loaded');
        return;
    }

    // シーン、カメラ、レンダラーの初期化
    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({
        alpha: true,
        antialias: true
    });

    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    // キャンバスを固定背景として配置
    const canvas = renderer.domElement;
    canvas.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        pointer-events: none;
    `;
    document.body.appendChild(canvas);

    console.log('✅ Three.js Canvas - 固定背景として配置完了');

    // パーティクルシステムの作成
    const particleCount = 1000;
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);
    const velocities = [];

    // ブランドカラー
    const brownColor = new THREE.Color(0x8B7355); // #8B7355
    const greenColor = new THREE.Color(0x428570); // #428570
    const beigeColor = new THREE.Color(0xF5F3F0); // #F5F3F0

    // パーティクルの初期配置
    for (let i = 0; i < particleCount; i++) {
        const i3 = i * 3;

        // 位置（広範囲に配置）
        positions[i3] = (Math.random() - 0.5) * 100;
        positions[i3 + 1] = (Math.random() - 0.5) * 100;
        positions[i3 + 2] = (Math.random() - 0.5) * 50;

        // 初期色（茶色）
        colors[i3] = brownColor.r;
        colors[i3 + 1] = brownColor.g;
        colors[i3 + 2] = brownColor.b;

        // 速度
        velocities.push({
            x: (Math.random() - 0.5) * 0.02,
            y: (Math.random() - 0.5) * 0.02,
            z: (Math.random() - 0.5) * 0.02
        });
    }

    // ジオメトリとマテリアル
    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

    const material = new THREE.PointsMaterial({
        size: 2,
        vertexColors: true,
        transparent: true,
        opacity: 0.6,
        blending: THREE.AdditiveBlending
    });

    const particles = new THREE.Points(geometry, material);
    scene.add(particles);

    // カメラ位置
    camera.position.z = 30;

    console.log(`✅ パーティクルシステム - ${particleCount}個生成完了`);

    // スクロール状態
    let scrollY = window.pageYOffset;
    let targetCameraZ = 30;
    let targetRotationY = 0;

    // スクロールイベント（passive）
    window.addEventListener('scroll', function() {
        scrollY = window.pageYOffset;
    }, { passive: true });

    // アニメーションループ
    function animate() {
        requestAnimationFrame(animate);

        // スクロール進捗率を計算
        const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
        const scrollProgress = Math.min(scrollY / maxScroll, 1);

        // スクロールに応じた色の変化
        const positions = geometry.attributes.position.array;
        const colors = geometry.attributes.color.array;

        for (let i = 0; i < particleCount; i++) {
            const i3 = i * 3;

            // パーティクルの移動
            positions[i3] += velocities[i].x;
            positions[i3 + 1] += velocities[i].y;
            positions[i3 + 2] += velocities[i].z;

            // 範囲外に出たら反対側に戻す
            if (Math.abs(positions[i3]) > 50) positions[i3] *= -0.9;
            if (Math.abs(positions[i3 + 1]) > 50) positions[i3 + 1] *= -0.9;
            if (Math.abs(positions[i3 + 2]) > 25) positions[i3 + 2] *= -0.9;

            // スクロールに応じた色の変化
            let targetColor;
            if (scrollProgress < 0.33) {
                // ヒーローセクション: 茶色メイン
                targetColor = brownColor;
            } else if (scrollProgress < 0.66) {
                // サービス/ニュースセクション: 緑メイン
                targetColor = greenColor;
            } else {
                // ブログ/フッター: ベージュと緑のミックス
                targetColor = beigeColor;
            }

            // 色を滑らかに変化
            colors[i3] += (targetColor.r - colors[i3]) * 0.01;
            colors[i3 + 1] += (targetColor.g - colors[i3 + 1]) * 0.01;
            colors[i3 + 2] += (targetColor.b - colors[i3 + 2]) * 0.01;
        }

        geometry.attributes.position.needsUpdate = true;
        geometry.attributes.color.needsUpdate = true;

        // カメラの動き（スクロール連動）
        targetCameraZ = 30 + scrollProgress * 20;
        targetRotationY = scrollProgress * Math.PI * 0.5;

        camera.position.z += (targetCameraZ - camera.position.z) * 0.05;
        particles.rotation.y += (targetRotationY - particles.rotation.y) * 0.05;

        // パーティクル全体の回転
        particles.rotation.x += 0.0005;
        particles.rotation.y += 0.001;

        renderer.render(scene, camera);
    }

    // リサイズ対応
    window.addEventListener('resize', function() {
        if (window.innerWidth < 768) {
            // モバイルになったら削除
            canvas.remove();
            return;
        }

        camera.aspect = window.innerWidth / window.innerHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(window.innerWidth, window.innerHeight);
    });

    // アニメーション開始
    animate();

    console.log('🎉 Three.js Global Background - 初期化完了');

})();
