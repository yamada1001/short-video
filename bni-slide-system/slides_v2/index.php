<?php
/**
 * BNI Slide System V2 - スライドショー（PHPベース）
 * 管理画面で作成したデータが即座に反映されます
 */

// 日付パラメータ（デフォルト: 次の金曜日）
$targetDate = $_GET['date'] ?? null;
if (!$targetDate) {
    // 次の金曜日を自動計算
    $today = new DateTime();
    $dayOfWeek = (int)$today->format('N');
    if ($dayOfWeek == 5) {
        $targetDate = $today->format('Y-m-d');
    } elseif ($dayOfWeek < 5) {
        $daysUntilFriday = 5 - $dayOfWeek;
        $today->modify("+{$daysUntilFriday} days");
        $targetDate = $today->format('Y-m-d');
    } else {
        $daysUntilFriday = (7 - $dayOfWeek) + 5;
        $today->modify("+{$daysUntilFriday} days");
        $targetDate = $today->format('Y-m-d');
    }
}

// メインプレゼンのPDF枚数を取得
$mainPresenterPdfPages = 0;
try {
    $db_path = __DIR__ . '/data/bni_slide_system.db';
    $db = new PDO('sqlite:' . $db_path);
    $stmt = $db->query("SELECT pdf_path FROM main_presenter ORDER BY created_at DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['pdf_path']) {
        $pdfPath = __DIR__ . '/' . $row['pdf_path'];
        if (is_dir($pdfPath)) {
            $imageDir = $pdfPath;
        } else {
            $imageDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');
        }

        if (is_dir($imageDir)) {
            $pdfImages = glob($imageDir . '/page-*.png');
            $mainPresenterPdfPages = count($pdfImages);
        }
    }
} catch (Exception $e) {
    error_log("PDF page count error: " . $e->getMessage());
}

// PHPスライドのマッピング（ページ番号 => スライドファイル）
$phpSlides = [
    7 => 'seating.php',
    8 => 'main_presenter.php',
    9 => 'speaker_rotation.php?page=9',
    10 => 'speaker_rotation.php?page=10',
    11 => 'speaker_rotation.php?page=11',
    12 => 'speaker_rotation.php?page=12',
    13 => 'speaker_rotation.php?page=13',
    14 => 'speaker_rotation.php?page=14',
    15 => 'start_dash.php?page=15',
    19 => 'visitor_intro.php',
    86 => 'networking_slides.php',
    91 => 'referral_champion.php',
    92 => 'value_champion.php',
    93 => 'visitor_champion.php',
    94 => '1to1_champion.php',
    95 => 'ceu_champion.php',
    96 => 'all_champions.php',
    107 => 'start_dash.php?page=107',
    169 => 'visitor_self_intro.php',
    185 => 'recruiting_categories.php',
    188 => 'visitor_stats.php',
    189 => 'referral_stats.php',
    190 => 'sales_stats.php',
    194 => 'category_survey.php',
    199 => 'speaker_rotation.php?page=199',
    200 => 'speaker_rotation.php?page=200',
    201 => 'speaker_rotation.php?page=201',
    202 => 'speaker_rotation.php?page=202',
    203 => 'speaker_rotation.php?page=203',
    204 => 'main_presenter_204.php',
    213 => 'visitor_feedback.php',
    227 => 'referral_verification.php',
    235 => 'visitor_thanks.php',
    242 => 'qr_code.php',
    297 => 'speaker_rotation.php?page=297',
    298 => 'speaker_rotation.php?page=298',
    299 => 'speaker_rotation.php?page=299',
    300 => 'speaker_rotation.php?page=300',
    301 => 'speaker_rotation.php?page=301',
    302 => 'weekly_stats.php'
];

// メインプレゼンのPDFページを動的に追加（p.205~）
if ($mainPresenterPdfPages > 0) {
    for ($i = 0; $i < $mainPresenterPdfPages; $i++) {
        $pageNum = 205 + $i;
        $phpSlides[$pageNum] = "main_presenter_extended.php?page=$pageNum";
    }
}

// 総スライド数
$totalSlides = 309;

// スライドの表示/非表示設定を取得
$visibilityMap = [];
try {
    $db_path = __DIR__ . '/data/bni_slide_system.db';
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT slide_number, is_visible FROM slide_visibility WHERE week_date = :week_date");
    $stmt->bindValue(':week_date', $targetDate, PDO::PARAM_STR);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visibilityMap[$row['slide_number']] = $row['is_visible'] == 1;
    }
} catch (Exception $e) {
    // エラーの場合は全て表示
    error_log("Visibility check error: " . $e->getMessage());
}

// スライド一覧を生成（非表示のスライドは除外）
$slides = [];
for ($i = 1; $i <= $totalSlides; $i++) {
    // 表示/非表示チェック（デフォルトは表示）
    $isVisible = isset($visibilityMap[$i]) ? $visibilityMap[$i] : true;

    if (!$isVisible) {
        continue; // 非表示スライドはスキップ
    }

    if (isset($phpSlides[$i])) {
        // PHPスライド
        $slides[$i] = [
            'type' => 'php',
            'source' => 'slides/' . $phpSlides[$i] . (strpos($phpSlides[$i], '?') !== false ? '&' : '?') . 'date=' . $targetDate
        ];
    } else {
        // 画像スライド
        $slideNumber = str_pad($i, 3, '0', STR_PAD_LEFT);
        $slides[$i] = [
            'type' => 'image',
            'source' => '../assets/images/slides/production/slide_' . $slideNumber . '.png'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNI Slide System V2 - スライドショー</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #000;
            font-family: 'Hiragino Kaku Gothic ProN', sans-serif;
        }

        /* スライドコンテナ */
        .slide-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .slide {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            display: none;
        }

        .slide.active {
            display: block;
        }

        /* 画像スライド */
        .slide img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* PHPスライド（iframe） */
        .slide iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* ページ番号表示 */
        .slide-number-display {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #000;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 24px;
            font-weight: bold;
            z-index: 9999;
            box-shadow: 0 2px 10px rgba(0,0,0,0.5);
        }

        /* ナビゲーションコントロール */
        .navigation {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 9999;
            display: flex;
            gap: 10px;
        }

        .nav-button {
            background: rgba(200, 16, 46, 0.9);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .nav-button:hover {
            background: rgba(200, 16, 46, 1);
            transform: scale(1.05);
        }

        .nav-button:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        /* ローディング表示 */
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 24px;
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <?php foreach ($slides as $index => $slide): ?>
            <div class="slide" data-slide-index="<?php echo $index; ?>">
                <?php if ($slide['type'] === 'php'): ?>
                    <iframe src="<?php echo htmlspecialchars($slide['source']); ?>" loading="lazy"></iframe>
                <?php else: ?>
                    <img src="<?php echo htmlspecialchars($slide['source']); ?>" alt="Slide <?php echo $index; ?>" loading="lazy">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ページ番号表示 -->
    <div class="slide-number-display" id="slideNumber">1 / <?php echo $totalSlides; ?></div>

    <!-- ナビゲーション -->
    <div class="navigation">
        <button class="nav-button" id="prevBtn">◀ 前へ</button>
        <button class="nav-button" id="nextBtn">次へ ▶</button>
    </div>

    <script>
        // スライド管理
        const slides = document.querySelectorAll('.slide');
        const totalSlides = <?php echo $totalSlides; ?>;
        let currentSlide = 1;

        // スライド表示
        function showSlide(index) {
            // 範囲チェック
            if (index < 1) index = 1;
            if (index > totalSlides) index = totalSlides;

            currentSlide = index;

            // すべてのスライドを非表示
            slides.forEach(slide => slide.classList.remove('active'));

            // 指定スライドを表示
            const targetSlide = document.querySelector(`[data-slide-index="${index}"]`);
            if (targetSlide) {
                targetSlide.classList.add('active');
            }

            // ページ番号更新
            document.getElementById('slideNumber').textContent = `${currentSlide} / ${totalSlides}`;

            // ボタン状態更新
            document.getElementById('prevBtn').disabled = (currentSlide === 1);
            document.getElementById('nextBtn').disabled = (currentSlide === totalSlides);

            // URLハッシュ更新
            window.location.hash = currentSlide;
        }

        // 前へボタン
        document.getElementById('prevBtn').addEventListener('click', () => {
            showSlide(currentSlide - 1);
        });

        // 次へボタン
        document.getElementById('nextBtn').addEventListener('click', () => {
            showSlide(currentSlide + 1);
        });

        // キーボードナビゲーション
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                showSlide(currentSlide - 1);
            } else if (e.key === 'ArrowRight' || e.key === 'ArrowDown' || e.key === ' ') {
                e.preventDefault();
                showSlide(currentSlide + 1);
            } else if (e.key === 'Home') {
                showSlide(1);
            } else if (e.key === 'End') {
                showSlide(totalSlides);
            } else if (e.key === 'f' || e.key === 'F') {
                // フルスクリーン切り替え
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    document.exitFullscreen();
                }
            }
        });

        // 初期表示（URLハッシュから取得、なければ1ページ目）
        const initialSlide = parseInt(window.location.hash.substring(1)) || 1;
        showSlide(initialSlide);

        // ページ読み込み完了後にフルスクリーン推奨メッセージ
        window.addEventListener('load', () => {
            console.log('スライドショー操作方法:');
            console.log('- 矢印キー（←→）: スライド移動');
            console.log('- スペースキー: 次のスライドへ');
            console.log('- Fキー: フルスクリーン切り替え');
            console.log('- Homeキー: 最初のスライドへ');
            console.log('- Endキー: 最後のスライドへ');
        });
    </script>
</body>
</html>
