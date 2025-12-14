<?php
/**
 * スライド総数のデバッグ
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "スライド総数デバッグ\n";
echo "========================================\n\n";

// 対象日付
$targetDate = $_GET['date'] ?? null;
if (!$targetDate) {
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

echo "[1] 対象日付: $targetDate\n\n";

// メインプレゼンのPDF枚数を取得
$mainPresenterPdfPages = 0;
$networkingPdfPages = 0;

try {
    $db_path = __DIR__ . '/data/bni_slide_system.db';
    $db = new PDO('sqlite:' . $db_path);

    // メインプレゼンPDFのページ数
    echo "[2] メインプレゼンPDF確認:\n";
    $stmt = $db->query("SELECT pdf_path FROM main_presenter ORDER BY created_at DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['pdf_path']) {
        echo "  - PDFパス: {$row['pdf_path']}\n";

        $pdfPath = __DIR__ . '/' . $row['pdf_path'];
        echo "  - 絶対パス: $pdfPath\n";
        echo "  - ディレクトリ存在: " . (is_dir($pdfPath) ? 'Yes' : 'No') . "\n";

        if (is_dir($pdfPath)) {
            $imageDir = $pdfPath;
        } else {
            $imageDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');
        }

        echo "  - 画像ディレクトリ: $imageDir\n";
        echo "  - 画像ディレクトリ存在: " . (is_dir($imageDir) ? 'Yes' : 'No') . "\n";

        if (is_dir($imageDir)) {
            $pdfImages = glob($imageDir . '/page-*.png');
            $mainPresenterPdfPages = count($pdfImages);
            echo "  - PDFページ数: $mainPresenterPdfPages\n";
            echo "  - 画像ファイル:\n";
            foreach (array_slice($pdfImages, 0, 5) as $img) {
                echo "    * " . basename($img) . "\n";
            }
            if (count($pdfImages) > 5) {
                echo "    ... 他 " . (count($pdfImages) - 5) . " 件\n";
            }
        } else {
            echo "  - PDFページ数: 0 (画像ディレクトリなし)\n";
        }
    } else {
        echo "  - PDFなし\n";
    }
    echo "\n";

    // ネットワーキング学習PDFのページ数
    echo "[3] ネットワーキング学習PDF確認:\n";
    $stmt = $db->query("SELECT pdf_path FROM networking_learning WHERE week_date = '$targetDate' ORDER BY created_at DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['pdf_path']) {
        echo "  - PDFパス: {$row['pdf_path']}\n";

        $pdfPath = __DIR__ . '/' . $row['pdf_path'];
        if (is_dir($pdfPath)) {
            $imageDir = $pdfPath;
        } else {
            $imageDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');
        }

        if (is_dir($imageDir)) {
            $pdfImages = glob($imageDir . '/page-*.png');
            $networkingPdfPages = count($pdfImages);
            echo "  - PDFページ数: $networkingPdfPages\n";
        } else {
            echo "  - PDFページ数: 0 (画像ディレクトリなし)\n";
        }
    } else {
        echo "  - PDFなし\n";
    }
    echo "\n";

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n\n";
}

// 総スライド数計算
$totalSlides = 309;

echo "[4] スライド総数計算:\n";
echo "  - 基本スライド数: 309\n";

// ネットワーキング学習PDFの追加ページ数
if ($networkingPdfPages > 1) {
    $networkingExtraPages = $networkingPdfPages - 1;
    $totalSlides += $networkingExtraPages;
    echo "  - ネットワーキングPDF追加: +$networkingExtraPages (合計: $totalSlides)\n";
} else {
    echo "  - ネットワーキングPDF追加: +0\n";
}

// メインプレゼンPDFの追加ページ数
if ($mainPresenterPdfPages > 8) {
    $mainPresenterExtraPages = $mainPresenterPdfPages - 8;
    $totalSlides += $mainPresenterExtraPages;
    echo "  - メインプレゼンPDF追加: +$mainPresenterExtraPages (合計: $totalSlides)\n";
} else {
    echo "  - メインプレゼンPDF追加: +0 (8ページ以内)\n";
}

echo "\n[5] 最終結果:\n";
echo "  - 総スライド数: $totalSlides ページ\n";

echo "\n========================================\n";
echo "完了\n";
echo "========================================\n";
