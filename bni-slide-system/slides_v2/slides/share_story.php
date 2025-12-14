<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>シェアストーリー | BNI Slide System V2</title>
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
            margin-bottom: 60px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .member-photo {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            object-fit: cover;
            border: 6px solid white;
            margin-bottom: 40px;
            background: #ddd;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .member-name {
            font-size: 72px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
        }

        .member-company {
            font-size: 42px;
            font-weight: 400;
            opacity: 0.95;
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
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="slide-title">シェアストーリー</div>

        <div id="memberPhoto" class="member-photo"></div>
        <div id="memberName" class="member-name"></div>
        <div id="memberCompany" class="member-company"></div>

        <div class="navigation-hint">
            <i class="fas fa-keyboard"></i> F: フルスクリーン
        </div>

        <div class="page-number">p.72</div>
    </div>

    <script>
        const API_BASE = '../api/share_story_crud.php';

        document.addEventListener('DOMContentLoaded', () => {
            loadShareStory();
            setupKeyboardShortcuts();
        });

        async function loadShareStory() {
            try {
                const response = await fetch(`${API_BASE}?action=get_latest`);
                const data = await response.json();

                if (data.success && data.data) {
                    showMember(data.data);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('エラー:', error);
                alert('データの読み込み中にエラーが発生しました');
            }
        }

        function showEmptyState() {
            document.getElementById('memberName').textContent = 'データがありません';
            document.getElementById('memberCompany').textContent = '';
        }

        function showMember(member) {
            const photoEl = document.getElementById('memberPhoto');
            if (member.photo_path) {
                photoEl.style.backgroundImage = `url(${member.photo_path})`;
                photoEl.style.backgroundSize = 'cover';
                photoEl.style.backgroundPosition = 'center';
            }

            document.getElementById('memberName').textContent = member.member_name || '';
            document.getElementById('memberCompany').textContent = member.company_name || '';
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
