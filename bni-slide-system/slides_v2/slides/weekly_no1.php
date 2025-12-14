<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>週間No.1 | BNI Slide System V2</title>
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

        .slide-title {
            font-size: 64px;
            font-weight: 700;
            margin-bottom: 80px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .awards-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
            width: 100%;
            max-width: 1600px;
        }

        .award-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
        }

        .award-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        .award-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 30px;
        }

        .award-member {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .award-count {
            font-size: 72px;
            font-weight: 700;
            margin-top: 20px;
            color: #FFD700;
        }

        .page-number {
            position: absolute;
            bottom: 40px;
            right: 60px;
            font-size: 36px;
            opacity: 0.8;
        }

        .navigation-hint {
            position: absolute;
            bottom: 40px;
            left: 60px;
            font-size: 18px;
            opacity: 0.7;
        }

        @media (max-width: 1200px) {
            .awards-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="slide-title">週間No.1</div>

        <div class="awards-container" id="awardsContainer"></div>

        <div class="navigation-hint">
            <i class="fas fa-keyboard"></i> F: フルスクリーン
        </div>

        <div class="page-number">p.28</div>
    </div>

    <script>
        const API_BASE = '../api/weekly_no1_crud.php';

        document.addEventListener('DOMContentLoaded', () => {
            loadWeeklyNo1();
            setupKeyboardShortcuts();
        });

        async function loadWeeklyNo1() {
            const urlParams = new URLSearchParams(window.location.search);
            const weekDate = urlParams.get('date');

            if (!weekDate) {
                alert('日付が指定されていません');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}?action=get&week_date=${weekDate}`);
                const data = await response.json();

                if (data.success && data.data) {
                    renderAwards(data.data);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('エラー:', error);
                alert('データの読み込み中にエラーが発生しました');
            }
        }

        function renderAwards(data) {
            const container = document.getElementById('awardsContainer');
            container.innerHTML = `
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-trophy"></i></div>
                    <div class="award-title">外部リファーラル<br>1位</div>
                    <div class="award-member">${data.external_referral_member_name || '-'}</div>
                    <div class="award-count">${data.external_referral_count || 0}件</div>
                </div>
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-users"></i></div>
                    <div class="award-title">ビジター招待<br>1位</div>
                    <div class="award-member">${data.visitor_invitation_member_name || '-'}</div>
                    <div class="award-count">${data.visitor_invitation_count || 0}件</div>
                </div>
                <div class="award-card">
                    <div class="award-icon"><i class="fas fa-handshake"></i></div>
                    <div class="award-title">1to1<br>1位</div>
                    <div class="award-member">${data.one_to_one_member_name || '-'}</div>
                    <div class="award-count">${data.one_to_one_count || 0}件</div>
                </div>
            `;
        }

        function showEmptyState() {
            document.getElementById('awardsContainer').innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; font-size: 32px;">
                    <i class="fas fa-inbox" style="font-size: 80px; margin-bottom: 30px; display: block;"></i>
                    <p>週間No.1のデータが登録されていません</p>
                </div>
            `;
        }

        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                if (e.key.toLowerCase() === 'f') {
                    toggleFullscreen();
                }
            });
        }

        function toggleFullscreen() {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
        }
    </script>
</body>
</html>
