/**
 * 大分県3Dマップ
 * Three.js + GSAP による大分県のインタラクティブ3D地図
 * より大分県らしい形状と可愛いデザイン
 */

(function() {
    'use strict';

    // DOM要素
    const canvas = document.getElementById('mapCanvas');
    const tooltip = document.getElementById('mapTooltip');
    const loading = document.getElementById('mapLoading');
    const container = document.getElementById('mapContainer');

    if (!canvas || !areasData) return;

    // Three.js 変数
    let scene, camera, renderer;
    let markers = [];
    let oitaGroup;
    let raycaster, mouse;
    let isDragging = false;
    let previousMousePosition = { x: 0, y: 0 };
    let targetRotation = { x: 0.3, y: 0 };
    let currentRotation = { x: 0.3, y: 0 };

    // 大分県の地理的範囲
    const oitaBounds = {
        minLat: 32.7,
        maxLat: 33.8,
        minLng: 130.8,
        maxLng: 132.0
    };

    // カラー設定（サイトのトンマナに合わせる）
    const colors = {
        background: 0xf5f3f0,
        prefecture: 0x8B7355,
        prefectureEdge: 0x6B5335,
        markerCity: 0x8B7355,
        markerTown: 0xa08060,
        markerHover: 0xd4a574,
        grid: 0xe5ddd5
    };

    // 初期化
    function init() {
        // シーン
        scene = new THREE.Scene();
        scene.background = new THREE.Color(colors.background);

        // カメラ
        const aspect = container.clientWidth / container.clientHeight;
        camera = new THREE.PerspectiveCamera(45, aspect, 0.1, 1000);
        camera.position.set(0, 0, 12);

        // レンダラー
        renderer = new THREE.WebGLRenderer({
            canvas: canvas,
            antialias: true
        });
        renderer.setSize(container.clientWidth, container.clientHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

        // レイキャスター
        raycaster = new THREE.Raycaster();
        mouse = new THREE.Vector2();

        // ライト
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.7);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5);
        directionalLight.position.set(5, 5, 5);
        scene.add(directionalLight);

        const backLight = new THREE.DirectionalLight(0xffffff, 0.3);
        backLight.position.set(-5, -5, -5);
        scene.add(backLight);

        // グループ作成
        oitaGroup = new THREE.Group();
        scene.add(oitaGroup);

        // 大分県の形状
        createOitaShape();

        // マーカー作成
        createMarkers();

        // イベントリスナー
        setupEventListeners();

        // ローディング非表示
        loading.style.display = 'none';

        // アニメーション開始
        animate();

        // 初期アニメーション（GSAPを使用）
        gsap.from(camera.position, {
            z: 25,
            duration: 2,
            ease: 'power3.out'
        });

        gsap.from(oitaGroup.rotation, {
            y: -Math.PI,
            duration: 2,
            ease: 'power3.out'
        });
    }

    // 大分県の形状作成（より正確な形状）
    function createOitaShape() {
        const shape = new THREE.Shape();

        // 大分県の形状（国東半島、佐伯のリアス式海岸などを表現）
        const points = [
            // 北西部（中津・日田方面）
            [-3.2, 1.5],
            [-3.0, 2.2],
            [-2.5, 2.8],
            // 北部（宇佐・豊後高田）
            [-1.8, 3.0],
            [-1.0, 3.2],
            // 国東半島
            [0, 3.5],
            [0.8, 3.8],
            [1.5, 3.6],
            [2.2, 3.2],
            [2.5, 2.8],
            // 姫島方向への突出
            [2.8, 2.5],
            // 東海岸（別府・大分）
            [3.0, 2.0],
            [3.2, 1.2],
            [3.3, 0.5],
            // 臼杵・津久見
            [3.2, -0.3],
            [3.0, -1.0],
            // 佐伯（リアス式海岸を表現）
            [2.8, -1.8],
            [2.5, -2.3],
            [2.0, -2.8],
            [1.5, -3.0],
            // 南西部
            [0.8, -2.8],
            [0, -2.5],
            [-0.8, -2.2],
            // 竹田・豊後大野
            [-1.5, -1.8],
            [-2.2, -1.2],
            [-2.8, -0.5],
            // 日田方面へ戻る
            [-3.2, 0.3],
            [-3.3, 1.0],
            [-3.2, 1.5]
        ];

        shape.moveTo(points[0][0], points[0][1]);
        for (let i = 1; i < points.length; i++) {
            shape.lineTo(points[i][0], points[i][1]);
        }

        const extrudeSettings = {
            steps: 1,
            depth: 0.4,
            bevelEnabled: true,
            bevelThickness: 0.15,
            bevelSize: 0.1,
            bevelSegments: 5
        };

        const geometry = new THREE.ExtrudeGeometry(shape, extrudeSettings);
        const material = new THREE.MeshPhongMaterial({
            color: colors.prefecture,
            transparent: true,
            opacity: 0.85,
            side: THREE.DoubleSide,
            shininess: 30
        });

        const mesh = new THREE.Mesh(geometry, material);
        mesh.position.z = -0.3;
        oitaGroup.add(mesh);

        // エッジライン（輪郭を強調）
        const edges = new THREE.EdgesGeometry(geometry);
        const lineMaterial = new THREE.LineBasicMaterial({
            color: colors.prefectureEdge,
            linewidth: 2
        });
        const line = new THREE.LineSegments(edges, lineMaterial);
        line.position.z = -0.3;
        oitaGroup.add(line);

        // 装飾：周りにドット
        createDecorationDots();
    }

    // 装飾用のドット
    function createDecorationDots() {
        const dotGeometry = new THREE.CircleGeometry(0.08, 16);
        const dotMaterial = new THREE.MeshBasicMaterial({
            color: colors.grid,
            transparent: true,
            opacity: 0.5
        });

        for (let i = 0; i < 50; i++) {
            const dot = new THREE.Mesh(dotGeometry, dotMaterial.clone());
            const angle = Math.random() * Math.PI * 2;
            const radius = 4 + Math.random() * 2;
            dot.position.x = Math.cos(angle) * radius;
            dot.position.y = Math.sin(angle) * radius;
            dot.position.z = -0.5;
            oitaGroup.add(dot);
        }
    }

    // 緯度経度を3D座標に変換
    function latLngToPosition(lat, lng) {
        const x = ((lng - oitaBounds.minLng) / (oitaBounds.maxLng - oitaBounds.minLng) - 0.5) * 6.5;
        const y = ((lat - oitaBounds.minLat) / (oitaBounds.maxLat - oitaBounds.minLat) - 0.5) * 6.5;
        return { x, y, z: 0.3 };
    }

    // マーカー作成
    function createMarkers() {
        areasData.forEach((area, index) => {
            const pos = latLngToPosition(area.lat, area.lng);

            // マーカーグループ
            const markerGroup = new THREE.Group();
            markerGroup.position.set(pos.x, pos.y, pos.z);

            // ピンの頭部分（丸）
            const headGeometry = new THREE.SphereGeometry(0.18, 32, 32);
            const headMaterial = new THREE.MeshPhongMaterial({
                color: area.type === '市' ? colors.markerCity : colors.markerTown,
                emissive: area.type === '市' ? 0x3a2a15 : 0x4a3a25,
                shininess: 80
            });
            const head = new THREE.Mesh(headGeometry, headMaterial);
            head.position.y = 0.3;
            markerGroup.add(head);

            // ピンの針部分
            const pinGeometry = new THREE.ConeGeometry(0.08, 0.3, 8);
            const pinMaterial = new THREE.MeshPhongMaterial({
                color: area.type === '市' ? colors.markerCity : colors.markerTown
            });
            const pin = new THREE.Mesh(pinGeometry, pinMaterial);
            pin.rotation.x = Math.PI;
            markerGroup.add(pin);

            markerGroup.userData = {
                area: area,
                originalColor: headMaterial.color.getHex(),
                head: head,
                headMaterial: headMaterial
            };

            oitaGroup.add(markerGroup);
            markers.push(markerGroup);

            // 登場アニメーション（GSAP）
            markerGroup.scale.set(0, 0, 0);
            gsap.to(markerGroup.scale, {
                x: 1,
                y: 1,
                z: 1,
                duration: 0.6,
                delay: 0.5 + index * 0.05,
                ease: 'back.out(1.7)'
            });

            // フローティングアニメーション
            gsap.to(markerGroup.position, {
                z: pos.z + 0.1,
                duration: 1 + Math.random() * 0.5,
                repeat: -1,
                yoyo: true,
                ease: 'sine.inOut',
                delay: Math.random() * 2
            });
        });
    }

    // イベントリスナー設定
    function setupEventListeners() {
        // マウス移動
        canvas.addEventListener('mousemove', onMouseMove);
        canvas.addEventListener('touchmove', onTouchMove, { passive: false });

        // クリック
        canvas.addEventListener('click', onClick);
        canvas.addEventListener('touchend', onTouchEnd);

        // ドラッグ操作
        canvas.addEventListener('mousedown', onMouseDown);
        canvas.addEventListener('mouseup', onMouseUp);
        canvas.addEventListener('mouseleave', onMouseUp);
        canvas.addEventListener('touchstart', onTouchStart);

        // リサイズ
        window.addEventListener('resize', onResize);

        // コントロールボタン
        document.getElementById('resetView').addEventListener('click', resetView);
        document.getElementById('zoomIn').addEventListener('click', zoomIn);
        document.getElementById('zoomOut').addEventListener('click', zoomOut);
    }

    // マウス移動処理
    function onMouseMove(event) {
        const rect = canvas.getBoundingClientRect();
        mouse.x = ((event.clientX - rect.left) / rect.width) * 2 - 1;
        mouse.y = -((event.clientY - rect.top) / rect.height) * 2 + 1;

        if (isDragging) {
            const deltaX = event.clientX - previousMousePosition.x;
            const deltaY = event.clientY - previousMousePosition.y;

            targetRotation.y += deltaX * 0.008;
            targetRotation.x += deltaY * 0.008;
            targetRotation.x = Math.max(-0.3, Math.min(0.8, targetRotation.x));

            previousMousePosition = { x: event.clientX, y: event.clientY };
        }

        checkIntersection(event.clientX, event.clientY);
    }

    // タッチ移動処理
    function onTouchMove(event) {
        event.preventDefault();
        if (event.touches.length === 1) {
            const touch = event.touches[0];
            const rect = canvas.getBoundingClientRect();
            mouse.x = ((touch.clientX - rect.left) / rect.width) * 2 - 1;
            mouse.y = -((touch.clientY - rect.top) / rect.height) * 2 + 1;

            if (isDragging) {
                const deltaX = touch.clientX - previousMousePosition.x;
                const deltaY = touch.clientY - previousMousePosition.y;

                targetRotation.y += deltaX * 0.008;
                targetRotation.x += deltaY * 0.008;
                targetRotation.x = Math.max(-0.3, Math.min(0.8, targetRotation.x));

                previousMousePosition = { x: touch.clientX, y: touch.clientY };
            }
        }
    }

    // 交差判定とツールチップ表示
    function checkIntersection(clientX, clientY) {
        raycaster.setFromCamera(mouse, camera);

        // マーカーの頭部分だけを対象にする
        const heads = markers.map(m => m.userData.head);
        const intersects = raycaster.intersectObjects(heads);

        // リセット
        markers.forEach(marker => {
            if (!marker.userData.isHovered) return;
            marker.userData.isHovered = false;

            gsap.to(marker.scale, {
                x: 1,
                y: 1,
                z: 1,
                duration: 0.3,
                ease: 'power2.out'
            });

            gsap.to(marker.userData.headMaterial.color, {
                r: ((marker.userData.originalColor >> 16) & 255) / 255,
                g: ((marker.userData.originalColor >> 8) & 255) / 255,
                b: (marker.userData.originalColor & 255) / 255,
                duration: 0.3
            });
        });

        if (intersects.length > 0) {
            const head = intersects[0].object;
            const marker = head.parent;
            marker.userData.isHovered = true;

            // スケールアップ
            gsap.to(marker.scale, {
                x: 1.4,
                y: 1.4,
                z: 1.4,
                duration: 0.3,
                ease: 'back.out(1.5)'
            });

            // 色変更
            gsap.to(marker.userData.headMaterial.color, {
                r: ((colors.markerHover >> 16) & 255) / 255,
                g: ((colors.markerHover >> 8) & 255) / 255,
                b: (colors.markerHover & 255) / 255,
                duration: 0.3
            });

            // ツールチップ表示
            const area = marker.userData.area;
            tooltip.innerHTML = `<strong>${area.name}</strong><br><span style="font-size:0.75rem;opacity:0.8;">${area.population}</span>`;
            tooltip.style.left = clientX + 15 + 'px';
            tooltip.style.top = clientY - 10 + 'px';
            tooltip.classList.add('active');

            canvas.style.cursor = 'pointer';
        } else {
            tooltip.classList.remove('active');
            canvas.style.cursor = isDragging ? 'grabbing' : 'grab';
        }
    }

    // クリック処理
    function onClick(event) {
        if (isDragging) return;

        const heads = markers.map(m => m.userData.head);
        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(heads);

        if (intersects.length > 0) {
            const marker = intersects[0].object.parent;
            const area = marker.userData.area;

            // クリックアニメーション
            gsap.to(marker.scale, {
                x: 1.8,
                y: 1.8,
                z: 1.8,
                duration: 0.15,
                yoyo: true,
                repeat: 1,
                ease: 'power2.inOut',
                onComplete: () => {
                    window.location.href = `/area/?area=${area.slug}`;
                }
            });
        }
    }

    // タッチ終了処理
    function onTouchEnd(event) {
        if (!isDragging) {
            const touch = event.changedTouches[0];
            const rect = canvas.getBoundingClientRect();
            mouse.x = ((touch.clientX - rect.left) / rect.width) * 2 - 1;
            mouse.y = -((touch.clientY - rect.top) / rect.height) * 2 + 1;

            const heads = markers.map(m => m.userData.head);
            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(heads);

            if (intersects.length > 0) {
                const area = intersects[0].object.parent.userData.area;
                window.location.href = `/area/?area=${area.slug}`;
            }
        }
        isDragging = false;
    }

    // マウスダウン
    function onMouseDown(event) {
        isDragging = true;
        previousMousePosition = { x: event.clientX, y: event.clientY };
        canvas.style.cursor = 'grabbing';
    }

    // マウスアップ
    function onMouseUp() {
        isDragging = false;
        canvas.style.cursor = 'grab';
    }

    // タッチスタート
    function onTouchStart(event) {
        if (event.touches.length === 1) {
            isDragging = true;
            previousMousePosition = {
                x: event.touches[0].clientX,
                y: event.touches[0].clientY
            };
        }
    }

    // リサイズ処理
    function onResize() {
        const width = container.clientWidth;
        const height = container.clientHeight;

        camera.aspect = width / height;
        camera.updateProjectionMatrix();

        renderer.setSize(width, height);
    }

    // ビューリセット
    function resetView() {
        gsap.to(targetRotation, {
            x: 0.3,
            y: 0,
            duration: 0.8,
            ease: 'power2.out'
        });
        gsap.to(camera.position, {
            z: 12,
            duration: 0.8,
            ease: 'power2.out'
        });
    }

    // ズームイン
    function zoomIn() {
        gsap.to(camera.position, {
            z: Math.max(camera.position.z - 2, 6),
            duration: 0.4,
            ease: 'power2.out'
        });
    }

    // ズームアウト
    function zoomOut() {
        gsap.to(camera.position, {
            z: Math.min(camera.position.z + 2, 20),
            duration: 0.4,
            ease: 'power2.out'
        });
    }

    // アニメーションループ
    function animate() {
        requestAnimationFrame(animate);

        // 回転の補間
        currentRotation.x += (targetRotation.x - currentRotation.x) * 0.08;
        currentRotation.y += (targetRotation.y - currentRotation.y) * 0.08;

        // グループを回転
        oitaGroup.rotation.x = currentRotation.x;
        oitaGroup.rotation.y = currentRotation.y;

        renderer.render(scene, camera);
    }

    // 初期化実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
