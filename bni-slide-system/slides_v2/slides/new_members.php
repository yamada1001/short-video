<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新入会メンバー | BNI Slide System V2</title>
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
            transition: all 0.3s ease;
        }

        .member-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
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
            font-size: 32px;
            opacity: 0.8;
        }

        .empty-state i {
            font-size: 80px;
            margin-bottom: 30px;
            display: block;
        }

        @media (max-width: 1200px) {
            .slide-title {
                font-size: 48px;
            }

            .slide-subtitle {
                font-size: 24px;
            }

            .members-list {
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
            <div class="slide-title">新入会メンバー</div>
            <div class="slide-subtitle">New Members</div>
        </div>

        <div id="membersList" class="members-list"></div>

        <div class="navigation-hint">
            <i class="fas fa-keyboard"></i> F: フルスクリーン
        </div>

        <div class="page-number">p.25-27, p.100-102</div>
    </div>

    <script>
        const API_BASE = '../api/new_members_crud.php';

        document.addEventListener('DOMContentLoaded', () => {
            loadNewMembers();
            setupKeyboardShortcuts();
        });

        async function loadNewMembers() {
            const urlParams = new URLSearchParams(window.location.search);
            const weekDate = urlParams.get('date');

            if (!weekDate) {
                showError('日付が指定されていません');
                return;
            }

            try {
                const response = await fetch(`${API_BASE}?action=get_by_date&week_date=${weekDate}`);
                const data = await response.json();

                if (data.success) {
                    if (data.new_members.length > 0) {
                        renderMembers(data.new_members);
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

        function showEmptyState() {
            document.getElementById('membersList').innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>新入会メンバーが登録されていません</p>
                </div>
            `;
        }

        function showError(message) {
            document.getElementById('membersList').innerHTML = `
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
