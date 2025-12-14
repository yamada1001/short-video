<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ハッピーバースデー | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
            color: white;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>');
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

        .birthday-icon {
            font-size: 120px;
            margin-bottom: 30px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .slide-title {
            font-size: 80px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
        }

        .slide-subtitle {
            font-size: 48px;
            font-weight: 400;
            opacity: 0.95;
        }

        .birthday-members {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 40px;
            width: 100%;
            max-width: 1400px;
        }

        .member-card {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 3px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .member-card:hover {
            transform: translateY(-5px) scale(1.05);
            background: rgba(255, 255, 255, 0.35);
        }

        .member-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            margin: 0 auto 25px;
            background: #ddd;
        }

        .member-name {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .member-company {
            font-size: 28px;
            font-weight: 400;
            opacity: 0.9;
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
            font-size: 36px;
            opacity: 0.9;
        }

        .empty-state i {
            font-size: 80px;
            margin-bottom: 30px;
            display: block;
        }

        @media (max-width: 1200px) {
            .slide-title {
                font-size: 56px;
            }

            .slide-subtitle {
                font-size: 32px;
            }

            .birthday-members {
                grid-template-columns: 1fr;
            }

            .member-name {
                font-size: 36px;
            }

            .member-company {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="slide-header">
            <div class="birthday-icon">
                <i class="fas fa-birthday-cake"></i>
            </div>
            <div class="slide-title">Happy Birthday!</div>
            <div class="slide-subtitle">お誕生日おめでとうございます</div>
        </div>

        <div id="birthdayMembers" class="birthday-members"></div>

        <div class="navigation-hint">
            <i class="fas fa-keyboard"></i> F: フルスクリーン
        </div>

        <div class="page-number">p.31</div>
    </div>

    <script>
        const MEMBERS_API = '../api/members_crud.php';

        document.addEventListener('DOMContentLoaded', () => {
            loadBirthdayMembers();
            setupKeyboardShortcuts();
        });

        async function loadBirthdayMembers() {
            try {
                const response = await fetch(`${MEMBERS_API}?action=list`);
                const data = await response.json();

                if (data.success) {
                    const birthdayMembers = filterBirthdayMembers(data.members);
                    if (birthdayMembers.length > 0) {
                        renderBirthdayMembers(birthdayMembers);
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

        function filterBirthdayMembers(members) {
            const today = new Date();
            const targetYear = today.getFullYear();

            // 当日の誕生日メンバーを抽出
            return members.filter(member => {
                if (!member.birthday || member.is_active != 1) return false;

                // 誕生日をYYYY-MM-DD形式でパース
                const birthday = new Date(member.birthday);
                const birthdayThisYear = new Date(targetYear, birthday.getMonth(), birthday.getDate());

                // 開催日と同じ月日かチェック
                return birthdayThisYear.getMonth() === today.getMonth() &&
                       birthdayThisYear.getDate() === today.getDate();
            });
        }

        function renderBirthdayMembers(members) {
            const container = document.getElementById('birthdayMembers');
            container.innerHTML = '';

            members.forEach(member => {
                const card = document.createElement('div');
                card.className = 'member-card';
                card.innerHTML = `
                    ${member.photo_path ? `<img src="${member.photo_path}" class="member-photo" alt="${member.name}">` : '<div class="member-photo"></div>'}
                    <div class="member-name">${member.name}</div>
                    ${member.company_name ? `<div class="member-company">${member.company_name}</div>` : ''}
                `;
                container.appendChild(card);
            });
        }

        function showEmptyState() {
            document.getElementById('birthdayMembers').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-calendar-day"></i>
                    <p>本日、誕生日のメンバーはいません</p>
                </div>
            `;
        }

        function showError(message) {
            document.getElementById('birthdayMembers').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>${message}</p>
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
