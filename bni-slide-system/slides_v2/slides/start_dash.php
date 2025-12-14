<?php
/**
 * BNI Slide System V2 - Start Dash Presenter Slide (p.15 / p.107)
 * スタートダッシュプレゼンスライド表示
 */

// パラメータ取得
$weekDate = $_GET['date'] ?? date('Y-m-d');
$pageNumber = $_GET['page'] ?? 15;

// データベース接続
$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';
$db = new SQLite3($dbPath);

// プレゼンデータ取得
$stmt = $db->prepare("
    SELECT
        sd.*,
        m.name as member_name,
        m.company_name,
        m.photo_path
    FROM start_dash_presenter sd
    LEFT JOIN members m ON sd.member_id = m.id
    WHERE sd.week_date = :week_date AND sd.page_number = :page_number
");
$stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
$stmt->bindValue(':page_number', $pageNumber, SQLITE3_INTEGER);
$result = $stmt->execute();
$presenter = $result->fetchArray(SQLITE3_ASSOC);

if (!$presenter) {
    // データがない場合
    $presenter = [
        'member_name' => '',
        'company_name' => '',
        'photo_path' => '',
    ];
    $hasData = false;
} else {
    $hasData = true;
}

$db->close();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スタートダッシュプレゼン (p.<?= $pageNumber ?>) | BNI Slide System V2</title>

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

        /* Full Screen Container */
        .slide-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px;
            position: relative;
        }

        /* Background Pattern */
        .slide-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.03)"/></svg>');
            background-size: 100px 100px;
            z-index: 0;
        }

        /* Content */
        .slide-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 1200px;
            width: 100%;
        }

        /* Photo */
        .member-photo {
            width: 280px;
            height: 280px;
            border-radius: 50%;
            object-fit: cover;
            border: 8px solid white;
            margin: 0 auto 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
            background: #fff;
        }

        /* Name */
        .member-name {
            font-size: 72px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
        }

        /* Company */
        .member-company {
            font-size: 44px;
            font-weight: 400;
            opacity: 0.95;
            line-height: 1.4;
            margin-bottom: 50px;
        }

        /* Timer Section */
        .timer-section {
            margin-top: 50px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
        }

        .timer-display {
            font-size: 120px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            letter-spacing: 8px;
            text-shadow: 2px 2px 12px rgba(0,0,0,0.3);
            min-width: 400px;
            text-align: center;
        }

        .timer-display.warning {
            animation: pulse 1s ease-in-out infinite;
        }

        .timer-display.finished {
            color: #ff4444;
            animation: blink 0.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Timer Controls */
        .timer-controls {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .timer-btn {
            padding: 18px 40px;
            border: 3px solid white;
            border-radius: 50px;
            background: rgba(255,255,255,0.2);
            color: white;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            backdrop-filter: blur(10px);
        }

        .timer-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .timer-btn:active {
            transform: translateY(0);
        }

        .timer-btn.start {
            background: rgba(40,167,69,0.9);
            border-color: rgba(40,167,69,1);
        }

        .timer-btn.start:hover {
            background: rgba(40,167,69,1);
        }

        .timer-btn.pause {
            background: rgba(255,193,7,0.9);
            border-color: rgba(255,193,7,1);
        }

        .timer-btn.pause:hover {
            background: rgba(255,193,7,1);
        }

        .timer-btn.reset {
            background: rgba(108,117,125,0.9);
            border-color: rgba(108,117,125,1);
        }

        .timer-btn.reset:hover {
            background: rgba(108,117,125,1);
        }

        /* No Data */
        .no-data {
            font-size: 48px;
            opacity: 0.7;
            text-align: center;
        }

        /* Page Number */
        .page-number {
            position: fixed;
            bottom: 30px;
            right: 40px;
            font-size: 28px;
            font-weight: 500;
            opacity: 0.8;
            z-index: 10;
        }

        /* Keyboard Hint */
        .keyboard-hint {
            position: fixed;
            bottom: 30px;
            left: 40px;
            font-size: 16px;
            opacity: 0.6;
            z-index: 10;
        }

        /* Hidden */
        .hidden {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <?php if ($hasData): ?>
            <div class="slide-content">
                <?php if ($presenter['photo_path']): ?>
                    <img src="<?= htmlspecialchars($presenter['photo_path']) ?>"
                         alt="<?= htmlspecialchars($presenter['member_name']) ?>"
                         class="member-photo">
                <?php else: ?>
                    <div class="member-photo"></div>
                <?php endif; ?>

                <div class="member-name">
                    <?= htmlspecialchars($presenter['member_name']) ?>
                </div>

                <?php if ($presenter['company_name']): ?>
                    <div class="member-company">
                        <?= htmlspecialchars($presenter['company_name']) ?>
                    </div>
                <?php endif; ?>

                <!-- Timer Section -->
                <div class="timer-section">
                    <div class="timer-display" id="timerDisplay">2:00</div>

                    <div class="timer-controls">
                        <button class="timer-btn start" id="startBtn" onclick="startTimer()">
                            <i class="fas fa-play"></i>
                            <span>スタート</span>
                        </button>
                        <button class="timer-btn pause hidden" id="pauseBtn" onclick="pauseTimer()">
                            <i class="fas fa-pause"></i>
                            <span>一時停止</span>
                        </button>
                        <button class="timer-btn reset" id="resetBtn" onclick="resetTimer()">
                            <i class="fas fa-redo"></i>
                            <span>リセット</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="no-data">
                スタートダッシュプレゼンデータが登録されていません
            </div>
        <?php endif; ?>
    </div>

    <div class="page-number">p.<?= $pageNumber ?></div>
    <div class="keyboard-hint">
        <i class="fas fa-keyboard"></i> Space: スタート/一時停止 | R: リセット
    </div>

    <!-- Audio for timer end -->
    <audio id="timerSound" preload="auto">
        <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIGWi77eeeTRAMUKfj8LZjHAY4ktfyz3osBSh+zO/ekkALGGW57OihUhELPpji8LdjHQU2jNXx0H4pBSV8yO7gmEILF2K17OykVRIMP5vk8LdkHgU7lNny0oMtBSl/y+7goUILGWe87OqmVRMMQp/m8bhlHgU9ltvz1IYuBSuCzu7iokQLGmi97OynVhUNRKDo8rpmHgU/mt3014cwBSyE0O7jpEQMGWq+7O2oVxUORqPp87ppHwVBnN/02IgyBS2G0u/kpUQNGmy/7O+qWhYPSKXq9LtrIAVDnuD03IoxBS6I1O/mplMNHG3B7fGrWxcPSqjr9b1sIQVFoOL03YwzBjCJ1vDnp1QOH2/D7vKtXRgQTKns9cBtIgVHouP14I40BzGM2PDpqVUOIG/E7/OvYBkRTqzu9sFuIwZIpOX24Y02BzKO2fHrq1YPIHHF8PSxYhsRUK7v98JwJAdKpuf35I44BzSQ2/HsrVcQJHLG8PW0ZBwSUrDx+MRxJQdMqOj45o85BzWS3fLur1gRJnTH8fa2ZR0TU7Ly+cZzJghNqun56JA6CDeU3/Lws1kSKHXI8ve4Zx4UVbPz+sh0JghPq+r66pE7CjiW4fPysl0TKnbJ8/m6aCAVVrT0+8l1JwlQrem77JI8CzqY4/T0s18UK3fK9Pm8aiEWV7X1/Mp3KAlRru385JM9CzyZ5fX2tWIVLXjL9fq+ayIXWLb2/Ot4KQpSr/D95ZQ+DD2b5vb3t2QWLnnM9vu/bCMYW7j3/e56KQpUsO/+5pU/DT+d5/j5uGUXMHrO9/zAbSQZXLn4/vB7KwtVse8AAABAbSQaN3vP+f3CbiYaXrr5//J8LABWsv0A56A/D0Kf6vr6u2cYMnzR+v7DcCUaX7v6APR9LQxXs/8B6KFAEESh6/z8vGgZNH3S+//EcSYbYLz6APZ+LQ1Ytf8C6aJBEUWi7P7+vWkaNn7T/ADFciccYr37AfmALg1ZtvAB6qNCEken7gABwGocN3/V/AHGcygeZL79AgCBLw1auPEE66VDEkmr8AIDwmocOIDW/QHHdCkfZL/+BAGCMAxbufIM7KVEEkqs8QIEw2sdOYHX/QHIdSkfZsD+BQGDMQxbuvMN7aZFFEmr8AIDxGwdO4PY/gLJdSogZ8D+BQGEMg1cvPQO76dGFUqt8QIExG0fPYTZ/wPLdykhaML/BwKFMw1dvfUQ8KlHFkyu8gQFxW0fPoXb/wTMeCojaMT/BwKGNQ1evvcP8apIFkyu8gQGxm4gP4fc/wXOeSolasXACQKHNg5gwPkR86xJF0+w8wUGx28gQYjdAAXPeiomacb/CQKINg5gwfsS9K1KGFGx9QUHyG8hQ4nfAAXQeyonbMf/CwOJOA9iw/wT9a9LGFKy9QYHyXAhRYrfAQXRfSsob8j/DAOKOg9jxP4U9rBLGVOy9QYIynEhRYvgAQbSfywocsn/DQOLPQ9kxf8V97FMGVO09gcJy3IiRoziAQfSgC4qc8n/DgSMPRBlyAAX+LJNGVS09gcJzHMjR43jAgfTgS4rc8sADwSNPxBmyQEX+bNNG1W19wgKzXQjSI7kAgfUgi0sdMsAEASOPhBnyQIY+rROGlW29wgLznUkSY/kAwjVgy4sdswAEASPPxBnygMY+7VPHUAAA0AAAAA=" type="audio/wav">
    </audio>

    <script>
        const TIMER_DURATION = 120; // 2分 = 120秒
        let timeRemaining = TIMER_DURATION;
        let timerInterval = null;
        let isRunning = false;
        let isPaused = false;

        const timerDisplay = document.getElementById('timerDisplay');
        const startBtn = document.getElementById('startBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const resetBtn = document.getElementById('resetBtn');
        const timerSound = document.getElementById('timerSound');

        // タイマー表示更新
        function updateDisplay() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            const display = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            timerDisplay.textContent = display;

            // 警告表示（30秒以下）
            if (timeRemaining <= 30 && timeRemaining > 0) {
                timerDisplay.classList.add('warning');
            } else {
                timerDisplay.classList.remove('warning');
            }

            // 終了表示
            if (timeRemaining === 0) {
                timerDisplay.classList.add('finished');
            } else {
                timerDisplay.classList.remove('finished');
            }
        }

        // タイマー開始
        function startTimer() {
            if (isRunning) return;

            isRunning = true;
            isPaused = false;
            startBtn.classList.add('hidden');
            pauseBtn.classList.remove('hidden');

            timerInterval = setInterval(() => {
                if (timeRemaining > 0) {
                    timeRemaining--;
                    updateDisplay();

                    // 0になったら停止＆音を鳴らす
                    if (timeRemaining === 0) {
                        pauseTimer();
                        playSound();
                    }
                }
            }, 1000);
        }

        // タイマー一時停止
        function pauseTimer() {
            if (!isRunning) return;

            isRunning = false;
            isPaused = true;
            clearInterval(timerInterval);

            startBtn.classList.remove('hidden');
            pauseBtn.classList.add('hidden');
        }

        // タイマーリセット
        function resetTimer() {
            isRunning = false;
            isPaused = false;
            clearInterval(timerInterval);
            timeRemaining = TIMER_DURATION;
            updateDisplay();

            startBtn.classList.remove('hidden');
            pauseBtn.classList.add('hidden');
        }

        // 音を鳴らす
        function playSound() {
            try {
                timerSound.currentTime = 0;
                timerSound.play().catch(e => {
                    console.log('音声再生エラー（ユーザー操作が必要な場合があります）:', e);
                });
            } catch (e) {
                console.log('音声再生エラー:', e);
            }
        }

        // キーボードショートカット
        document.addEventListener('keydown', (e) => {
            // スペースキー: スタート/一時停止
            if (e.code === 'Space') {
                e.preventDefault();
                if (isRunning) {
                    pauseTimer();
                } else {
                    startTimer();
                }
            }
            // Rキー: リセット
            else if (e.code === 'KeyR') {
                e.preventDefault();
                resetTimer();
            }
        });

        // 初期表示
        updateDisplay();
    </script>
</body>
</html>
