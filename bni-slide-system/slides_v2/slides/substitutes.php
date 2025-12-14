<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>代理出席メンバー | BNI Slide System V2</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            color: white;
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: 100px 100px;
        }

        .slide-container {
            position: relative;
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
        }

        .slide-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .slide-title {
            font-size: 64px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .slide-subtitle {
            font-size: 32px;
            font-weight: 400;
            opacity: 0.95;
        }

        .substitutes-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 40px;
            width: 100%;
            max-width: 1400px;
        }

        .substitute-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .substitute-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }

        .substitute-no {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            display: inline-block;
        }

        .substitute-company {
            font-size: 36px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .substitute-name {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-number {
            position: absolute;
            bottom: 40px;
            right: 60px;
            font-size: 36px;
            font-weight: 500;
            opacity: 0.8;
        }

        .navigation-hint {
            position: absolute;
            bottom: 40px;
            left: 60px;
            font-size: 18px;
            opacity: 0.7;
        }

        .empty-state {
            text-align: center;
            font-size: 32px;
            opacity: 0.8;
        }

        .empty-state i {
            font-size: 80px;
            margin-bottom: 30px;
            display: block;
        }

        /* レスポンシブ */
        @media (max-width: 1200px) {
            .slide-title {
                font-size: 48px;
            }

            .slide-subtitle {
                font-size: 24px;
            }

            .substitutes-list {
                grid-template-columns: 1fr;
            }

            .substitute-name {
                font-size: 36px;
            }

            .substitute-company {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="slide-header">
            <div class="slide-title">代理出席メンバー</div>
            <div class="slide-subtitle">Substitute Members</div>
        </div>

        <div id="substitutesList" class="substitutes-list">
            <!-- JavaScriptで動的に生成 -->
        </div>

        <div class="navigation-hint">
            <i class="fas fa-keyboard"></i> F: フルスクリーン
        </div>

        <div class="page-number">p.22-24</div>
    </div>

    <script>
        const API_BASE = '../api/substitutes_crud.php';
        let substitutes = [];

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            loadSubstitutes();
            setupKeyboardShortcuts();
        });

        // 代理出席者データ取得
        async function loadSubstitutes() {
            try {
                const response = await fetch(`${API_BASE}?action=get_latest`);
                const data = await response.json();

                if (data.success) {
                    substitutes = data.substitutes;
                    if (substitutes.length > 0) {
                        renderSubstitutes();
                    } else {
                        showEmptyState();
                    }
                } else {
                    showError('データの読み込みに失敗しました');
                }
            } catch (error) {
                console.error('エラー:', error);
                showError('データの読み込み中にエラーが発生しました');
            }
        }

        // 代理出席者表示
        function renderSubstitutes() {
            const container = document.getElementById('substitutesList');
            container.innerHTML = '';

            substitutes.forEach((substitute, index) => {
                const card = document.createElement('div');
                card.className = 'substitute-card';
                card.innerHTML = `
                    <div class="substitute-no">No.${substitute.substitute_no}</div>
                    <div class="substitute-company">${substitute.company_name}</div>
                    <div class="substitute-name">${substitute.name}</div>
                `;
                container.appendChild(card);
            });
        }

        // 空の状態を表示
        function showEmptyState() {
            const container = document.getElementById('substitutesList');
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>代理出席者が登録されていません</p>
                </div>
            `;
        }

        // エラー表示
        function showError(message) {
            const container = document.getElementById('substitutesList');
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${message}</p>
                </div>
            `;
        }

        // キーボードショートカット設定
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                switch(e.key.toLowerCase()) {
                    case 'f':
                        toggleFullscreen();
                        break;
                }
            });
        }

        // フルスクリーン切り替え
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error('フルスクリーンエラー:', err);
                });
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }
    </script>
</body>
</html>
