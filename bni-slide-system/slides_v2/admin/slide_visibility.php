<?php require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>スライド表示/非表示管理 | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: #f5f5f5; color: #333; }
        .header { background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%); color: white; padding: 20px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header h1 { font-size: 24px; font-weight: 600; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .actions-bar { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; display: flex; justify-content: space-between; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.3s; }
        .btn-primary { background: #C8102E; color: white; }
        .btn-secondary { background: #6c757d; color: white; }
        .slide-list { background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden; }
        .slide-item { padding: 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .slide-item:last-child { border-bottom: none; }
        .slide-info { flex: 1; }
        .slide-name { font-size: 16px; font-weight: 600; color: #333; margin-bottom: 5px; }
        .slide-page { font-size: 14px; color: #666; }
        .toggle-switch { position: relative; display: inline-block; width: 60px; height: 34px; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 26px; width: 26px; left: 4px; bottom: 4px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: #C8102E; }
        input:checked + .slider:before { transform: translateX(26px); }
    </style>
</head>
<body>
    <div class="header">
        <h1><i class="fas fa-eye"></i> スライド表示/非表示管理</h1>
    </div>

    <div class="container">
        <div class="actions-bar">
            <button class="btn btn-secondary" onclick="location.href='index.php'"><i class="fas fa-arrow-left"></i> 戻る</button>
            <button class="btn btn-primary" onclick="saveAll()"><i class="fas fa-save"></i> すべて保存</button>
        </div>

        <div class="slide-list" id="slideList"></div>
    </div>

    <script>
        const slides = [
            {page: 1, name: 'タイトル'},
            {page: 74, name: 'ネットワーキング学習'},
            {page: 91, name: 'リファーラルチャンピオン'},
            {page: 92, name: 'バリューチャンピオン'},
            {page: 93, name: 'ビジターチャンピオン'},
            {page: 94, name: '1to1チャンピオン'},
            {page: 95, name: 'CEUチャンピオン'},
            {page: 96, name: '全チャンピオン'},
            {page: 184, name: 'ビジネスブレイクアウト'},
            {page: 185, name: '募集カテゴリ'},
            {page: 188, name: 'ビジター統計'},
            {page: 189, name: 'リファーラル統計'},
            {page: 190, name: '売上統計'},
            {page: 194, name: 'カテゴリアンケート'},
            {page: 227, name: 'リファーラル真正度'},
            {page: 242, name: 'QRコード'},
            {page: 302, name: '週次統計'}
        ];

        let visibilityData = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadVisibility();
        });

        async function loadVisibility() {
            try {
                const response = await fetch('../api/slide_visibility_crud.php?action=list');
                const data = await response.json();

                if (data.success) {
                    visibilityData = {};
                    data.visibility.forEach(v => {
                        visibilityData[v.slide_page] = v.is_visible == 1;
                    });
                }

                renderSlideList();
            } catch (error) {
                console.error('データ取得エラー:', error);
                renderSlideList();
            }
        }

        function renderSlideList() {
            const container = document.getElementById('slideList');
            container.innerHTML = slides.map(slide => {
                const isVisible = visibilityData[slide.page] !== undefined ? visibilityData[slide.page] : true;
                return `
                    <div class="slide-item">
                        <div class="slide-info">
                            <div class="slide-name">${slide.name}</div>
                            <div class="slide-page">ページ ${slide.page}</div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" data-page="${slide.page}" ${isVisible ? 'checked' : ''} onchange="toggleVisibility(${slide.page}, this.checked)">
                            <span class="slider"></span>
                        </label>
                    </div>
                `;
            }).join('');
        }

        function toggleVisibility(page, isVisible) {
            visibilityData[page] = isVisible;
        }

        async function saveAll() {
            const visibilityArray = slides.map(slide => ({
                slide_page: slide.page,
                slide_name: slide.name,
                is_visible: visibilityData[slide.page] !== undefined ? (visibilityData[slide.page] ? 1 : 0) : 1
            }));

            try {
                const response = await fetch('../api/slide_visibility_crud.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        action: 'save_all',
                        visibility: visibilityArray
                    })
                });

                const data = await response.json();
                if (data.success) {
                    alert('保存しました！');
                } else {
                    alert('エラー: ' + data.error);
                }
            } catch (error) {
                alert('通信エラー: ' + error);
            }
        }
    </script>
</body>
</html>
