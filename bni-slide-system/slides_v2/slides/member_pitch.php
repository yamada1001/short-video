<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メンバーピッチ | BNI Slide System V2</title>
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
            width: 100vw;
            height: 100vh;
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

        .member-photo {
            width: 200px;
            height: 200px;
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
            margin-bottom: 50px;
        }

        .timer {
            font-size: 120px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            letter-spacing: 8px;
            margin-top: 30px;
            text-shadow: 4px 4px 8px rgba(0,0,0,0.3);
        }

        .timer.warning {
            color: #FFD700;
            animation: pulse-warning 1s ease-in-out infinite;
        }

        .timer.danger {
            color: #FF4444;
            animation: pulse-danger 0.5s ease-in-out infinite;
        }

        @keyframes pulse-warning {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        @keyframes pulse-danger {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        .timer-controls {
            position: absolute;
            top: 40px;
            right: 60px;
            display: flex;
            gap: 15px;
        }

        .timer-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid white;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .timer-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .timer-btn.active {
            background: rgba(255, 255, 255, 0.4);
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

        .slide-progress {
            position: absolute;
            top: 40px;
            left: 60px;
            font-size: 24px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.2);
            padding: 12px 24px;
            border-radius: 8px;
        }

        @media (max-width: 1200px) {
            .member-name {
                font-size: 56px;
            }

            .member-company {
                font-size: 32px;
            }

            .timer {
                font-size: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="timer-controls">
            <button class="timer-btn" onclick="startTimer()">
                <i class="fas fa-play"></i> スタート
            </button>
            <button class="timer-btn" onclick="pauseTimer()">
                <i class="fas fa-pause"></i> 停止
            </button>
            <button class="timer-btn" onclick="resetTimer()">
                <i class="fas fa-redo"></i> リセット
            </button>
        </div>

        <div class="slide-progress" id="slideProgress">1 / 1</div>

        <div id="memberPhoto" class="member-photo"></div>
        <div id="memberName" class="member-name"></div>
        <div id="memberCompany" class="member-company"></div>
        <div id="timer" class="timer">0:33</div>

        <div class="navigation-hint">
            <i class="fas fa-keyboard"></i> F: フルスクリーン | ←→: ナビゲーション | Space: スタート/停止
        </div>

        <div class="page-number" id="pageNumber">p.112-166</div>
    </div>

    <script>
        const API_BASE = '../api/member_pitch_crud.php';
        let members = [];
        let currentIndex = 0;
        let timerInterval = null;
        let remainingTime = 33; // 33秒

        document.addEventListener('DOMContentLoaded', () => {
            loadMembers();
            setupKeyboardShortcuts();
        });

        async function loadMembers() {
            try {
                const response = await fetch(`${API_BASE}?action=get_latest`);
                const data = await response.json();

                if (data.success) {
                    // 不参加メンバーを除外
                    members = data.members.filter(m => m.is_absent == 0);
                    if (members.length > 0) {
                        showMember(0);
                    } else {
                        alert('表示するメンバーがいません');
                    }
                } else {
                    alert('データの読み込みに失敗しました');
                }
            } catch (error) {
                console.error('エラー:', error);
                alert('データの読み込み中にエラーが発生しました');
            }
        }

        function showMember(index) {
            if (index < 0 || index >= members.length) return;

            currentIndex = index;
            const member = members[index];

            // メンバー情報表示
            const photoEl = document.getElementById('memberPhoto');
            if (member.photo_path) {
                photoEl.style.backgroundImage = `url(${member.photo_path})`;
                photoEl.style.backgroundSize = 'cover';
                photoEl.style.backgroundPosition = 'center';
            } else {
                photoEl.style.backgroundImage = 'none';
            }

            document.getElementById('memberName').textContent = member.name;
            document.getElementById('memberCompany').textContent = member.company_name || '';
            document.getElementById('slideProgress').textContent = `${index + 1} / ${members.length}`;
            document.getElementById('pageNumber').textContent = `p.${112 + index}`;

            // タイマーリセット
            resetTimer();
        }

        function startTimer() {
            if (timerInterval) return; // 既に実行中

            timerInterval = setInterval(() => {
                remainingTime--;
                updateTimerDisplay();

                if (remainingTime <= 0) {
                    pauseTimer();
                    // 自動的に次のメンバーへ
                    if (currentIndex < members.length - 1) {
                        setTimeout(() => nextMember(), 1000);
                    }
                }
            }, 1000);
        }

        function pauseTimer() {
            if (timerInterval) {
                clearInterval(timerInterval);
                timerInterval = null;
            }
        }

        function resetTimer() {
            pauseTimer();
            remainingTime = 33;
            updateTimerDisplay();
        }

        function updateTimerDisplay() {
            const minutes = Math.floor(remainingTime / 60);
            const seconds = remainingTime % 60;
            const timerEl = document.getElementById('timer');
            timerEl.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            // 色の変更
            timerEl.classList.remove('warning', 'danger');
            if (remainingTime <= 5) {
                timerEl.classList.add('danger');
            } else if (remainingTime <= 10) {
                timerEl.classList.add('warning');
            }
        }

        function nextMember() {
            if (currentIndex < members.length - 1) {
                showMember(currentIndex + 1);
            }
        }

        function prevMember() {
            if (currentIndex > 0) {
                showMember(currentIndex - 1);
            }
        }

        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                switch(e.key.toLowerCase()) {
                    case 'f':
                        toggleFullscreen();
                        break;
                    case 'arrowright':
                        pauseTimer();
                        nextMember();
                        break;
                    case 'arrowleft':
                        pauseTimer();
                        prevMember();
                        break;
                    case ' ':
                        e.preventDefault();
                        if (timerInterval) {
                            pauseTimer();
                        } else {
                            startTimer();
                        }
                        break;
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
