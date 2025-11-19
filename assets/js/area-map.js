/**
 * 大分県3Dマップ
 * Three.js + GSAP による大分県のインタラクティブ3D地図
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
    let raycaster, mouse;
    let isDragging = false;
    let previousMousePosition = { x: 0, y: 0 };
    let targetRotation = { x: 0, y: 0 };
    let currentRotation = { x: 0, y: 0 };

    // 大分県の地理的範囲（概算）
    const oitaBounds = {
        minLat: 32.7,
        maxLat: 33.8,
        minLng: 130.8,
        maxLng: 132.0
    };

    // 初期化
    function init() {
        // シーン
        scene = new THREE.Scene();
        scene.background = new THREE.Color(0x1a1a2e);

        // カメラ
        const aspect = container.clientWidth / container.clientHeight;
        camera = new THREE.PerspectiveCamera(45, aspect, 0.1, 1000);
        camera.position.set(0, 0, 15);

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
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(5, 5, 5);
        scene.add(directionalLight);

        // グリッド（背景効果）
        createGrid();

        // 大分県の形状（簡略化）
        createOitaShape();

        // マーカー作成
        createMarkers();

        // イベントリスナー
        setupEventListeners();

        // ローディング非表示
        loading.style.display = 'none';

        // アニメーション開始
        animate();

        // 初期アニメーション
        gsap.from(camera.position, {
            z: 30,
            duration: 1.5,
            ease: 'power2.out'
        });
    }

    // グリッド作成
    function createGrid() {
        const gridHelper = new THREE.GridHelper(20, 20, 0x444444, 0x333333);
        gridHelper.position.y = -3;
        gridHelper.rotation.x = Math.PI / 2;
        scene.add(gridHelper);
    }

    // 大分県の形状作成（簡略化した形状）
    function createOitaShape() {
        const shape = new THREE.Shape();

        // 大分県の簡略化した輪郭
        const points = [
            [-3, 2], [-2.5, 3], [-1, 3.5], [0.5, 3.8], [2, 3.5],
            [3, 2.5], [3.5, 1], [3, -0.5], [2.5, -2], [1.5, -2.5],
            [0, -2.8], [-1.5, -2.5], [-2.5, -1.5], [-3, 0], [-3, 2]
        ];

        shape.moveTo(points[0][0], points[0][1]);
        for (let i = 1; i < points.length; i++) {
            shape.lineTo(points[i][0], points[i][1]);
        }

        const extrudeSettings = {
            steps: 1,
            depth: 0.3,
            bevelEnabled: true,
            bevelThickness: 0.1,
            bevelSize: 0.1,
            bevelSegments: 3
        };

        const geometry = new THREE.ExtrudeGeometry(shape, extrudeSettings);
        const material = new THREE.MeshPhongMaterial({
            color: 0x4a5568,
            transparent: true,
            opacity: 0.7,
            side: THREE.DoubleSide
        });

        const mesh = new THREE.Mesh(geometry, material);
        mesh.position.z = -0.5;
        scene.add(mesh);
    }

    // 緯度経度を3D座標に変換
    function latLngToPosition(lat, lng) {
        const x = ((lng - oitaBounds.minLng) / (oitaBounds.maxLng - oitaBounds.minLng) - 0.5) * 7;
        const y = ((lat - oitaBounds.minLat) / (oitaBounds.maxLat - oitaBounds.minLat) - 0.5) * 7;
        return { x, y, z: 0.5 };
    }

    // マーカー作成
    function createMarkers() {
        areasData.forEach((area, index) => {
            const pos = latLngToPosition(area.lat, area.lng);

            // マーカー（球体）
            const geometry = new THREE.SphereGeometry(0.15, 16, 16);
            const material = new THREE.MeshPhongMaterial({
                color: area.type === '市' ? 0x667eea : 0x48bb78,
                emissive: area.type === '市' ? 0x334499 : 0x256640,
                shininess: 100
            });

            const marker = new THREE.Mesh(geometry, material);
            marker.position.set(pos.x, pos.y, pos.z);
            marker.userData = {
                area: area,
                originalScale: 1,
                originalColor: material.color.getHex()
            };

            scene.add(marker);
            markers.push(marker);

            // 登場アニメーション
            marker.scale.set(0, 0, 0);
            gsap.to(marker.scale, {
                x: 1,
                y: 1,
                z: 1,
                duration: 0.5,
                delay: index * 0.05,
                ease: 'back.out(1.7)'
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

            targetRotation.y += deltaX * 0.01;
            targetRotation.x += deltaY * 0.01;
            targetRotation.x = Math.max(-0.5, Math.min(0.5, targetRotation.x));

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

                targetRotation.y += deltaX * 0.01;
                targetRotation.x += deltaY * 0.01;
                targetRotation.x = Math.max(-0.5, Math.min(0.5, targetRotation.x));

                previousMousePosition = { x: touch.clientX, y: touch.clientY };
            }
        }
    }

    // 交差判定とツールチップ表示
    function checkIntersection(clientX, clientY) {
        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(markers);

        // リセット
        markers.forEach(marker => {
            if (!marker.userData.isHovered) return;
            marker.userData.isHovered = false;
            gsap.to(marker.scale, {
                x: 1,
                y: 1,
                z: 1,
                duration: 0.2
            });
            gsap.to(marker.material.color, {
                r: ((marker.userData.originalColor >> 16) & 255) / 255,
                g: ((marker.userData.originalColor >> 8) & 255) / 255,
                b: (marker.userData.originalColor & 255) / 255,
                duration: 0.2
            });
        });

        if (intersects.length > 0) {
            const marker = intersects[0].object;
            marker.userData.isHovered = true;

            // スケールアップ
            gsap.to(marker.scale, {
                x: 1.5,
                y: 1.5,
                z: 1.5,
                duration: 0.2
            });

            // 色変更
            gsap.to(marker.material.color, {
                r: 1,
                g: 0.8,
                b: 0.2,
                duration: 0.2
            });

            // ツールチップ表示
            const area = marker.userData.area;
            tooltip.innerHTML = `<strong>${area.name}</strong><br>${area.population}`;
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

        raycaster.setFromCamera(mouse, camera);
        const intersects = raycaster.intersectObjects(markers);

        if (intersects.length > 0) {
            const area = intersects[0].object.userData.area;

            // クリックアニメーション
            const marker = intersects[0].object;
            gsap.to(marker.scale, {
                x: 2,
                y: 2,
                z: 2,
                duration: 0.1,
                yoyo: true,
                repeat: 1,
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

            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(markers);

            if (intersects.length > 0) {
                const area = intersects[0].object.userData.area;
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
            x: 0,
            y: 0,
            duration: 0.5,
            ease: 'power2.out'
        });
        gsap.to(camera.position, {
            z: 15,
            duration: 0.5,
            ease: 'power2.out'
        });
    }

    // ズームイン
    function zoomIn() {
        gsap.to(camera.position, {
            z: Math.max(camera.position.z - 3, 8),
            duration: 0.3,
            ease: 'power2.out'
        });
    }

    // ズームアウト
    function zoomOut() {
        gsap.to(camera.position, {
            z: Math.min(camera.position.z + 3, 25),
            duration: 0.3,
            ease: 'power2.out'
        });
    }

    // アニメーションループ
    function animate() {
        requestAnimationFrame(animate);

        // 回転の補間
        currentRotation.x += (targetRotation.x - currentRotation.x) * 0.1;
        currentRotation.y += (targetRotation.y - currentRotation.y) * 0.1;

        // シーン全体を回転
        scene.rotation.x = currentRotation.x;
        scene.rotation.y = currentRotation.y;

        // マーカーのパルスアニメーション
        const time = Date.now() * 0.001;
        markers.forEach((marker, i) => {
            if (!marker.userData.isHovered) {
                marker.position.z = 0.5 + Math.sin(time + i * 0.5) * 0.05;
            }
        });

        renderer.render(scene, camera);
    }

    // 初期化実行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
