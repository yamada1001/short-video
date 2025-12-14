<?php
/**
 * BNI Slide System V2 - Main Presenter Extended Slide (p.204~)
 * メインプレゼン拡張版スライド（PDFページ表示）
 */

require_once __DIR__ . '/../config.php';

// ページ番号を取得（デフォルト: 205、PDFは205から開始）
$pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 205;
$pdfPageIndex = $pageNumber - 205; // 0-indexed（205が1ページ目）

// データベース接続
$db = new PDO('sqlite:' . $db_path);

// 最新のプレゼンデータ取得
$stmt = $db->query("
    SELECT
        mp.*,
        m.name as member_name,
        m.company_name,
        m.category,
        m.photo_path
    FROM main_presenter mp
    LEFT JOIN members m ON mp.member_id = m.id
    ORDER BY mp.created_at DESC
    LIMIT 1
");
$presentation = $stmt->fetch(PDO::FETCH_ASSOC);

$imagePath = null;

if ($presentation && $presentation['presentation_type'] === 'extended' && !empty($presentation['pdf_path'])) {
    $pdfPath = __DIR__ . '/../' . $presentation['pdf_path'];

    // pdf_pathがディレクトリかファイルか判定
    if (is_dir($pdfPath)) {
        // ディレクトリの場合（ブラウザ側で変換された画像）
        $imageDir = $pdfPath;
    } else {
        // ファイルの場合（サーバー側で変換された画像）
        $imageDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');
    }

    if (is_dir($imageDir)) {
        $pdfImages = glob($imageDir . '/page-*.png');
        sort($pdfImages);

        // 指定されたページのインデックスが存在するか確認
        if (isset($pdfImages[$pdfPageIndex])) {
            $imagePath = str_replace(__DIR__ . '/../', '../', $pdfImages[$pdfPageIndex]);
        }
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メインプレゼン拡張版 (p.<?= $pageNumber ?>) | BNI Slide System V2</title>
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
            padding: 0;
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

        /* PDF Image */
        .pdf-image {
            max-width: 100%;
            max-height: 100vh;
            object-fit: contain;
            position: relative;
            z-index: 1;
        }

        /* No Data */
        .no-data {
            font-size: 48px;
            opacity: 0.7;
            text-align: center;
            z-index: 1;
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
    </style>
</head>
<body>
    <div class="slide-container">
        <?php if ($imagePath): ?>
            <img src="<?= htmlspecialchars($imagePath) ?>"
                 alt="PDF Page <?= $pdfPageIndex + 1 ?>"
                 class="pdf-image">
        <?php else: ?>
            <div class="no-data">
                PDFデータがありません
            </div>
        <?php endif; ?>
    </div>

    <div class="page-number">p.<?= $pageNumber ?></div>
</body>
</html>
