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
        .filter-bar { background: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .filter-bar input { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }
        .page-info { background: #f8f9fa; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-size: 14px; color: #666; }
        .page-info strong { color: #333; font-size: 18px; }
    </style>
</head>
<body>
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1><i class="fas fa-eye"></i> スライド表示/非表示管理</h1>
            </div>
            <a href="index.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.3s; border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-home"></i> 管理画面トップへ
            </a>
        </div>
    </div>

    <div class="container">
        <div class="actions-bar">
            <button class="btn btn-secondary" onclick="location.href='index.php'"><i class="fas fa-arrow-left"></i> 戻る</button>
            <button class="btn btn-primary" onclick="saveAll()"><i class="fas fa-save"></i> すべて保存</button>
        </div>

        <div class="page-info">
            全 <strong id="totalPages">0</strong> ページ | 表示中: <strong id="visibleCount">0</strong> ページ | 非表示: <strong id="hiddenCount">0</strong> ページ
        </div>

        <div class="filter-bar">
            <input type="text" id="filterInput" placeholder="ページ番号または名前で検索..." onkeyup="filterSlides()">
        </div>

        <div class="slide-list" id="slideList"></div>
    </div>

    <script>
        // スライド総数をindex.phpから動的に取得
        let TOTAL_PAGES = 309; // デフォルト値
        let slides = [];

        // ページ名のマッピング（特定のページにのみ名前を付ける）
        const pageNames = {
            1: 'タイトル',
            7: '座席表',
            8: 'メインプレゼン(1)',
            15: 'スタートダッシュ(1)',
            19: 'ビジター紹介',
            74: 'ネットワーキング学習',
            86: 'ネットワーキングスライド',
            91: 'リファーラルチャンピオン',
            92: 'バリューチャンピオン',
            93: 'ビジターチャンピオン',
            94: '1to1チャンピオン',
            95: 'CEUチャンピオン',
            96: '全チャンピオン',
            107: 'スタートダッシュ(2)',
            184: 'ビジネスブレイクアウト',
            185: '募集カテゴリ',
            188: 'ビジター統計',
            189: 'リファーラル統計',
            190: '売上統計',
            194: 'カテゴリアンケート',
            204: 'メインプレゼン(2)',
            227: 'リファーラル真正度',
            235: 'ビジター感謝',
            242: 'QRコード',
            302: '週次統計'
        };

        let visibilityData = {};

        document.addEventListener('DOMContentLoaded', async function() {
            // index.phpから総ページ数を動的に取得
            await detectTotalPages();
            loadVisibility();
        });

        async function detectTotalPages() {
            try {
                const response = await fetch('../index.php');
                const html = await response.text();

                // class="slide"の出現回数をカウント
                const matches = html.match(/class="slide"/g);
                if (matches && matches.length > 0) {
                    TOTAL_PAGES = matches.length;
                    console.log(`検出されたスライド総数: ${TOTAL_PAGES}ページ`);
                }

                // スライドリストを生成
                slides = [];
                for (let i = 1; i <= TOTAL_PAGES; i++) {
                    slides.push({
                        page: i,
                        name: pageNames[i] || `ページ ${i}`
                    });
                }
            } catch (error) {
                console.error('ページ数検出エラー:', error);
                // エラー時はデフォルト値を使用
                slides = [];
                for (let i = 1; i <= TOTAL_PAGES; i++) {
                    slides.push({
                        page: i,
                        name: pageNames[i] || `ページ ${i}`
                    });
                }
            }
        }

        async function loadVisibility() {
            try {
                const response = await fetch('../api/slide_visibility_crud.php?action=list');
                const data = await response.json();

                if (data.success) {
                    visibilityData = {};
                    data.visibility.forEach(v => {
                        visibilityData[v.slide_number] = v.is_visible == 1;
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
                    <div class="slide-item" data-page="${slide.page}" data-name="${slide.name.toLowerCase()}">
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
            updateStats();
        }

        function toggleVisibility(page, isVisible) {
            visibilityData[page] = isVisible;
            updateStats();
        }

        function filterSlides() {
            const filterValue = document.getElementById('filterInput').value.toLowerCase();
            const items = document.querySelectorAll('.slide-item');

            items.forEach(item => {
                const page = item.getAttribute('data-page');
                const name = item.getAttribute('data-name');

                if (page.includes(filterValue) || name.includes(filterValue)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function updateStats() {
            let visibleCount = 0;
            let hiddenCount = 0;

            slides.forEach(slide => {
                const isVisible = visibilityData[slide.page] !== undefined ? visibilityData[slide.page] : true;
                if (isVisible) {
                    visibleCount++;
                } else {
                    hiddenCount++;
                }
            });

            document.getElementById('totalPages').textContent = TOTAL_PAGES;
            document.getElementById('visibleCount').textContent = visibleCount;
            document.getElementById('hiddenCount').textContent = hiddenCount;
        }

        async function saveAll() {
            const visibilityArray = slides.map(slide => ({
                slide_number: slide.page,
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
                    loadVisibility(); // リロードして最新の状態を取得
                } else {
                    alert('エラー: ' + (data.error || '不明なエラー'));
                }
            } catch (error) {
                alert('通信エラー: ' + error.message);
            }
        }
    </script>
</body>
</html>
