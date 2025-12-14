<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ビジネスブレイクアウト | BNI Slide System V2</title>
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
            margin: 0;
            padding: 0;
        }

        .slide-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 80px;
            text-align: center;
        }

        .title {
            font-size: 96px;
            font-weight: 700;
            margin-bottom: 40px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .subtitle {
            font-size: 48px;
            margin-bottom: 80px;
            opacity: 0.9;
        }

        .timer-display {
            font-size: 200px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            text-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            margin-bottom: 60px;
            animation: pulse 1s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .timer-display.warning {
            color: #FFD700;
            animation: warning 0.5s ease-in-out infinite;
        }

        @keyframes warning {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.6;
            }
        }

        .timer-display.danger {
            color: #FF4444;
            animation: danger 0.3s ease-in-out infinite;
        }

        @keyframes danger {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.1);
            }
        }

        .controls {
            display: flex;
            gap: 30px;
            justify-content: center;
            margin-top: 40px;
        }

        .btn {
            padding: 20px 40px;
            font-size: 24px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            background: white;
            color: #C8102E;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn i {
            margin-right: 10px;
        }

        .info-text {
            font-size: 32px;
            margin-top: 40px;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title">
            <i class="fas fa-comments"></i> ビジネスブレイクアウト
        </div>

        <div class="subtitle">Business Breakout</div>

        <div id="timerDisplay" class="timer-display">5:00</div>

        <div class="info-text">残り時間</div>

        <div class="controls">
            <button class="btn" id="startBtn" onclick="startTimer()">
                <i class="fas fa-play"></i> スタート
            </button>
            <button class="btn" id="pauseBtn" onclick="pauseTimer()" style="display: none;">
                <i class="fas fa-pause"></i> 一時停止
            </button>
            <button class="btn" onclick="resetTimer()">
                <i class="fas fa-redo"></i> リセット
            </button>
        </div>
    </div>

    <script>
        let totalSeconds = 5 * 60; // 5分
        let remainingSeconds = totalSeconds;
        let timerInterval = null;
        let isRunning = false;

        function updateDisplay() {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            const display = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            const timerElement = document.getElementById('timerDisplay');
            timerElement.textContent = display;

            // 色とアニメーションの変更
            timerElement.classList.remove('warning', 'danger');
            if (remainingSeconds <= 60) {
                timerElement.classList.add('danger');
            } else if (remainingSeconds <= 120) {
                timerElement.classList.add('warning');
            }

            // 時間切れ
            if (remainingSeconds <= 0) {
                pauseTimer();
                playSound();
            }
        }

        function startTimer() {
            if (isRunning) return;

            isRunning = true;
            document.getElementById('startBtn').style.display = 'none';
            document.getElementById('pauseBtn').style.display = 'inline-block';

            timerInterval = setInterval(() => {
                remainingSeconds--;
                updateDisplay();

                if (remainingSeconds <= 0) {
                    clearInterval(timerInterval);
                    isRunning = false;
                }
            }, 1000);
        }

        function pauseTimer() {
            if (!isRunning) return;

            isRunning = false;
            clearInterval(timerInterval);
            document.getElementById('startBtn').style.display = 'inline-block';
            document.getElementById('pauseBtn').style.display = 'none';
        }

        function resetTimer() {
            pauseTimer();
            remainingSeconds = totalSeconds;
            updateDisplay();
        }

        function playSound() {
            // ブラウザのビープ音（実装可能であれば）
            if (window.AudioContext || window.webkitAudioContext) {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);

                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.5);
            }
        }

        // キーボードショートカット
        document.addEventListener('keydown', function(e) {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                if (isRunning) {
                    pauseTimer();
                } else {
                    startTimer();
                }
            } else if (e.key === 'r' || e.key === 'R') {
                resetTimer();
            }
        });

        // 初期表示
        updateDisplay();
    </script>
</body>
</html>
