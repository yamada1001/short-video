<?php
/**
 * BNI Slide System V2 - Networking Learning Slides
 * ネットワーキング学習スライド（86枚目以降に挿入）
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);

// 対象の金曜日を取得
$targetFriday = getTargetFriday();

// IDまたは日付でデータ取得
$id = $_GET['id'] ?? null;
$weekDate = $_GET['week_date'] ?? $targetFriday;

if ($id) {
    $stmt = $db->prepare("SELECT * FROM networking_learning WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
} else {
    $stmt = $db->prepare("SELECT * FROM networking_learning WHERE week_date = :week_date ORDER BY id DESC LIMIT 1");
    $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
}

$stmt->execute();
$networkingData = $stmt->fetch(PDO::FETCH_ASSOC);

$imagePaths = [];
if ($networkingData && $networkingData['image_paths']) {
    $imagePaths = json_decode($networkingData['image_paths'], true);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ネットワーキング学習 | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: #000;
            overflow: hidden;
        }

        .slide-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .slide-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .slide-navigation {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(200, 16, 46, 0.9);
            padding: 15px 30px;
            border-radius: 30px;
            display: flex;
            gap: 20px;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.5);
        }

        .nav-btn {
            background: white;
            color: #C8102E;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .nav-btn:hover:not(:disabled) {
            background: #f0f0f0;
            transform: scale(1.1);
        }

        .nav-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        .page-indicator {
            color: white;
            font-weight: 600;
            font-size: 16px;
            min-width: 80px;
            text-align: center;
        }

        .no-data {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .no-data i {
            font-size: 80px;
            color: #C8102E;
            margin-bottom: 30px;
        }

        .no-data h2 {
            font-size: 32px;
            margin-bottom: 15px;
        }

        .no-data p {
            font-size: 18px;
            color: #999;
        }

        .loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 24px;
            text-align: center;
        }

        .loading i {
            font-size: 48px;
            color: #C8102E;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php if (empty($imagePaths)): ?>
        <div class="no-data">
            <i class="fas fa-file-pdf"></i>
            <h2>ネットワーキング学習資料がありません</h2>
            <p>管理画面からPDFをアップロードしてください</p>
        </div>
    <?php else: ?>
        <div class="slide-container">
            <img id="slideImage" class="slide-image" src="" alt="ネットワーキング学習スライド">
        </div>

        <div class="slide-navigation">
            <button class="nav-btn" id="prevBtn" onclick="previousSlide()">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="page-indicator">
                <span id="currentPage">1</span> / <span id="totalPages"><?php echo count($imagePaths); ?></span>
            </div>
            <button class="nav-btn" id="nextBtn" onclick="nextSlide()">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    <?php endif; ?>

    <script>
        const imagePaths = <?php echo json_encode($imagePaths); ?>;
        let currentIndex = 0;

        function showSlide(index) {
            if (imagePaths.length === 0) return;

            currentIndex = index;
            const basePath = '../';
            document.getElementById('slideImage').src = basePath + imagePaths[currentIndex];
            document.getElementById('currentPage').textContent = currentIndex + 1;

            // ボタンの有効/無効を切り替え
            document.getElementById('prevBtn').disabled = currentIndex === 0;
            document.getElementById('nextBtn').disabled = currentIndex === imagePaths.length - 1;
        }

        function nextSlide() {
            if (currentIndex < imagePaths.length - 1) {
                showSlide(currentIndex + 1);
            }
        }

        function previousSlide() {
            if (currentIndex > 0) {
                showSlide(currentIndex - 1);
            }
        }

        // キーボードショートカット
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowRight' || e.key === 'n' || e.key === 'N') {
                nextSlide();
            } else if (e.key === 'ArrowLeft' || e.key === 'p' || e.key === 'P') {
                previousSlide();
            } else if (e.key === 'Home') {
                showSlide(0);
            } else if (e.key === 'End') {
                showSlide(imagePaths.length - 1);
            }
        });

        // 初期表示
        if (imagePaths.length > 0) {
            showSlide(0);
        }
    </script>
</body>
</html>
