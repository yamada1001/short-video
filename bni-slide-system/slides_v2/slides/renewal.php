<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更新メンバー | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
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

        .members-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            width: 100%;
            max-width: 1400px;
        }

        .member-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
        }

        .member-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            margin: 0 auto 25px;
            background: #ddd;
        }

        .member-name {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .member-company {
            font-size: 32px;
            opacity: 0.9;
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
        <div class="slide-title">更新メンバー</div>

        <div id="membersList" class="members-list"></div>

        <div class="navigation-hint">
            <i class="fas fa-keyboard"></i> F: フルスクリーン
        </div>

        <div class="page-number">p.98, p.229</div>
    </div>

    <script>
        const API_BASE = '../api/renewal_crud.php';

        document.addEventListener('DOMContentLoaded', () => {
            loadRenewalMembers();
            setupKeyboardShortcuts();
        });

        async function loadRenewalMembers() {
            const urlParams = new URLSearchParams(window.location.search);
            const weekDate = urlParams.get('date');

            if (!weekDate) {
                alert('日付が指定されていません');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}?action=get_by_date&week_date=${weekDate}`);
                const data = await response.json();

                if (data.success && data.renewal_members.length > 0) {
                    renderMembers(data.renewal_members);
                } else {
                    alert('更新メンバーが登録されていません');
                }
            } catch (error) {
                console.error('エラー:', error);
                alert('データの読み込み中にエラーが発生しました');
            }
        }

        function renderMembers(members) {
            const container = document.getElementById('membersList');
            container.innerHTML = '';

            members.forEach(member => {
                const card = document.createElement('div');
                card.className = 'member-card';
                card.innerHTML = `
                    ${member.photo_path ? `<img src="${member.photo_path}" class="member-photo" alt="${member.member_name}">` : '<div class="member-photo"></div>'}
                    <div class="member-name">${member.member_name}</div>
                    ${member.company_name ? `<div class="member-company">${member.company_name}</div>` : ''}
                `;
                container.appendChild(card);
            });
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
