<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スピーカーローテーション | BNI Slide System V2</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
        }

        .slide {
            width: 100vw;
            height: 100vh;
            background: linear-gradient(135deg, #C8102E 0%, #a00a24 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .slide::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: 100px 100px;
        }

        .slide-content {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1400px;
        }

        .slide-title {
            font-size: 64px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 60px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
            letter-spacing: 4px;
        }

        .rotation-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }

        .rotation-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .rotation-table thead {
            background: #2c3e50;
            color: white;
        }

        .rotation-table thead th {
            padding: 24px 20px;
            text-align: left;
            font-size: 24px;
            font-weight: 600;
            border-right: 1px solid rgba(255,255,255,0.1);
        }

        .rotation-table thead th:last-child {
            border-right: none;
        }

        .rotation-table tbody tr {
            border-bottom: 2px solid #ecf0f1;
        }

        .rotation-table tbody tr:last-child {
            border-bottom: none;
        }

        .rotation-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .rotation-table tbody td {
            padding: 20px;
            font-size: 20px;
            color: #2c3e50;
            vertical-align: middle;
        }

        .rotation-table tbody td:first-child {
            font-weight: 600;
            color: #C8102E;
            font-size: 22px;
        }

        .rotation-table tbody td:nth-child(2) {
            font-weight: 500;
            color: #34495e;
        }

        .rotation-table tbody td:last-child {
            color: #555;
            line-height: 1.5;
        }

        .page-number {
            position: absolute;
            bottom: 30px;
            right: 40px;
            color: white;
            font-size: 24px;
            font-weight: 500;
            opacity: 0.8;
            z-index: 2;
        }

        /* ローディング表示 */
        .loading {
            text-align: center;
            padding: 100px;
        }

        .loading i {
            font-size: 64px;
            margin-bottom: 30px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* エラー表示 */
        .error {
            text-align: center;
            padding: 100px;
        }

        .error i {
            font-size: 64px;
            margin-bottom: 30px;
            color: #fff;
        }

        .error p {
            font-size: 24px;
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="slide">
        <div class="slide-content" id="slideContent">
            <div class="loading">
                <i class="fas fa-spinner"></i>
                <p>データを読み込んでいます...</p>
            </div>
        </div>
        <div class="page-number">p.9-14, p.199-203, p.297-301</div>
    </div>

    <script>
        const API_BASE = '../api/speaker_rotation_crud.php';

        // ページ読み込み時
        document.addEventListener('DOMContentLoaded', () => {
            loadSlideData();
        });

        // スライドデータ読み込み
        async function loadSlideData() {
            try {
                const response = await fetch(API_BASE + '?action=get_slide_data');
                const data = await response.json();

                if (data.success && data.weeks) {
                    renderSlide(data.weeks);
                } else {
                    showError('データが取得できませんでした');
                }
            } catch (error) {
                console.error('データ読み込みエラー:', error);
                showError('データの読み込みに失敗しました');
            }
        }

        // スライド表示
        function renderSlide(weeks) {
            const slideContent = document.getElementById('slideContent');

            // テーブル行作成
            let tableRows = '';
            weeks.forEach(week => {
                const date = formatDate(week.rotation_date);
                const memberName = week.member_name || '-';
                const referralTarget = week.referral_target || '-';

                tableRows += `
                    <tr>
                        <td>${date}</td>
                        <td>${memberName}</td>
                        <td>${referralTarget}</td>
                    </tr>
                `;
            });

            slideContent.innerHTML = `
                <div class="slide-title">スピーカーローテーション</div>
                <div class="rotation-table">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 20%;">日程</th>
                                <th style="width: 25%;">メインプレゼン</th>
                                <th style="width: 55%;">ご紹介してほしい人</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${tableRows}
                        </tbody>
                    </table>
                </div>
            `;
        }

        // 日付フォーマット（YYYY-MM-DD → MM/DD）
        function formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            const month = date.getMonth() + 1;
            const day = date.getDate();
            return `${month}/${day}`;
        }

        // エラー表示
        function showError(message) {
            const slideContent = document.getElementById('slideContent');
            slideContent.innerHTML = `
                <div class="error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${message}</p>
                </div>
            `;
        }

        // キーボードショートカット（フルスクリーン）
        document.addEventListener('keydown', (e) => {
            if (e.key === 'f' || e.key === 'F') {
                toggleFullscreen();
            } else if (e.key === 'r' || e.key === 'R') {
                loadSlideData(); // リロード
            }
        });

        // フルスクリーン切り替え
        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }
    </script>
</body>
</html>
