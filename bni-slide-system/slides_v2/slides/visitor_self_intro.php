<?php
/**
 * BNI Slide System V2 - Visitor Self Introduction Slide (p.169-180)
 * ビジター簡単自己紹介スライド（1人につき1ページ、23秒カウントダウン）
 */

require_once __DIR__ . '/../config.php';

// 対象の金曜日を取得
$date = getTargetFriday();

try {
    $db = new PDO('sqlite:' . $db_path);

    // 最新のビジター情報取得
    $stmt = $db->query("
        SELECT
            v.*,
            m.name as attend_member_name
        FROM visitors v
        LEFT JOIN members m ON v.attend_member_id = m.id
        WHERE v.week_date = (SELECT MAX(week_date) FROM visitors)
        ORDER BY v.visitor_no ASC
    ");

    $visitors = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visitors[] = $row;
    }

} catch (Exception $e) {
    die('データベースエラー: ' . $e->getMessage());
}

if (count($visitors) === 0) {
    die('この日付のビジターは登録されていません');
}

// 現在のビジターインデックス
$currentIndex = isset($_GET['index']) ? (int)$_GET['index'] : 0;
$totalVisitors = count($visitors);

if ($currentIndex >= $totalVisitors) {
    $currentIndex = 0;
}

$currentVisitor = $visitors[$currentIndex];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ビジター自己紹介 (p.<?= 169 + $currentIndex ?>) | BNI Slide System V2</title>

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
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .slide-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 80px;
            position: relative;
            text-align: center;
        }

        .slide-title {
            font-size: 42px;
            font-weight: 600;
            margin-bottom: 40px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .visitor-name {
            font-size: 64px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 3px 3px 6px rgba(0,0,0,0.3);
        }

        .visitor-company {
            font-size: 40px;
            font-weight: 400;
            margin-bottom: 50px;
            opacity: 0.95;
        }

        .content-section {
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 30px 50px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            max-width: 1200px;
            width: 100%;
        }

        .content-label {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 15px;
            opacity: 0.9;
            border-bottom: 2px solid rgba(255,255,255,0.5);
            padding-bottom: 10px;
        }

        .content-text {
            font-size: 28px;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .timer {
            font-size: 96px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            margin-top: 40px;
            letter-spacing: 8px;
            text-shadow: 4px 4px 8px rgba(0,0,0,0.3);
        }

        .timer.warning {
            color: #ffeb3b;
            animation: pulse 1s infinite;
        }

        .timer.danger {
            color: #ff5252;
            animation: blink 0.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes blink {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.5; }
        }

        .controls {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
        }

        .btn {
            padding: 12px 30px;
            border: 2px solid white;
            background: rgba(255,255,255,0.2);
            color: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 500;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }

        .btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .btn:active {
            transform: translateY(0);
        }

        .page-indicator {
            position: absolute;
            bottom: 30px;
            right: 40px;
            font-size: 24px;
            font-weight: 500;
            opacity: 0.9;
        }

        .nav-hint {
            position: absolute;
            bottom: 30px;
            left: 40px;
            font-size: 18px;
            opacity: 0.7;
        }

        /* フェードインアニメーション */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .slide-container > * {
            animation: fadeIn 0.6s ease;
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <h1 class="slide-title">ビジター 簡単自己紹介</h1>

        <div class="visitor-name"><?= htmlspecialchars($currentVisitor['name']) ?> 様</div>
        <?php if (!empty($currentVisitor['company_name'])): ?>
        <div class="visitor-company"><?= htmlspecialchars($currentVisitor['company_name']) ?></div>
        <?php endif; ?>

        <?php if (!empty($currentVisitor['job_description'])): ?>
        <div class="content-section">
            <div class="content-label">お仕事内容</div>
            <div class="content-text"><?= htmlspecialchars($currentVisitor['job_description']) ?></div>
        </div>
        <?php endif; ?>

        <?php if (!empty($currentVisitor['referral_request'])): ?>
        <div class="content-section">
            <div class="content-label">ご紹介してほしい方・職業</div>
            <div class="content-text"><?= htmlspecialchars($currentVisitor['referral_request']) ?></div>
        </div>
        <?php endif; ?>

        <div class="timer" id="timer">0:23</div>

        <div class="controls">
            <button class="btn" id="startBtn" onclick="startTimer()">スタート</button>
            <button class="btn" id="pauseBtn" onclick="pauseTimer()" style="display:none;">停止</button>
            <button class="btn" onclick="resetTimer()">リセット</button>
        </div>

        <?php if ($totalVisitors > 1): ?>
        <div class="nav-hint">
            [←][→] ビジター切り替え | [F] フルスクリーン | [Space] タイマー開始/停止
        </div>
        <div class="page-indicator">
            <?= $currentIndex + 1 ?> / <?= $totalVisitors ?>
        </div>
        <?php else: ?>
        <div class="nav-hint">
            [F] フルスクリーン | [Space] タイマー開始/停止
        </div>
        <?php endif; ?>
    </div>

    <script>
        const TIMER_SECONDS = 23;
        let timeLeft = TIMER_SECONDS;
        let timerInterval = null;
        let isRunning = false;

        const timerElement = document.getElementById('timer');
        const startBtn = document.getElementById('startBtn');
        const pauseBtn = document.getElementById('pauseBtn');

        const totalVisitors = <?= $totalVisitors ?>;
        let currentIndex = <?= $currentIndex ?>;

        // タイマー表示更新
        function updateTimerDisplay() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            // 警告表示
            timerElement.classList.remove('warning', 'danger');
            if (timeLeft <= 5 && timeLeft > 0) {
                timerElement.classList.add('danger');
            } else if (timeLeft <= 10) {
                timerElement.classList.add('warning');
            }
        }

        // タイマースタート
        function startTimer() {
            if (isRunning) return;

            isRunning = true;
            startBtn.style.display = 'none';
            pauseBtn.style.display = 'inline-block';

            timerInterval = setInterval(() => {
                timeLeft--;
                updateTimerDisplay();

                if (timeLeft <= 0) {
                    pauseTimer();
                    // タイマー終了時の処理
                    playSound();
                }
            }, 1000);
        }

        // タイマー停止
        function pauseTimer() {
            if (!isRunning) return;

            isRunning = false;
            clearInterval(timerInterval);
            startBtn.style.display = 'inline-block';
            pauseBtn.style.display = 'none';
        }

        // タイマーリセット
        function resetTimer() {
            pauseTimer();
            timeLeft = TIMER_SECONDS;
            updateTimerDisplay();
        }

        // 終了音（簡易版）
        function playSound() {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);

            oscillator.frequency.value = 800;
            gainNode.gain.value = 0.3;

            oscillator.start();
            oscillator.stop(audioContext.currentTime + 0.2);
        }

        // キーボードショートカット
        document.addEventListener('keydown', (e) => {
            if (e.key === ' ' || e.key === 'Spacebar') {
                e.preventDefault();
                if (isRunning) {
                    pauseTimer();
                } else {
                    startTimer();
                }
            } else if (e.key === 'r' || e.key === 'R') {
                resetTimer();
            } else if (e.key === 'ArrowRight' && totalVisitors > 1) {
                // 次のビジター
                const nextIndex = (currentIndex + 1) % totalVisitors;
                window.location.href = `?index=${nextIndex}`;
            } else if (e.key === 'ArrowLeft' && totalVisitors > 1) {
                // 前のビジター
                const prevIndex = (currentIndex - 1 + totalVisitors) % totalVisitors;
                window.location.href = `?index=${prevIndex}`;
            } else if (e.key === 'f' || e.key === 'F') {
                // フルスクリーン切り替え
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            }
        });

        // 初期表示とタイマー自動スタート
        updateTimerDisplay();
        startTimer();

        // 自動フルスクリーン（ユーザーインタラクション後）
        document.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            }
        }, { once: true });
    </script>
</body>
</html>
