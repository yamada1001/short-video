<?php
/**
 * スライド画像生成テスト
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "スライド画像生成テスト\n";
echo "========================================\n\n";

// データベース接続
$db = new PDO('sqlite:' . $db_path);

// 最新のプレゼンデータ取得
$stmt = $db->query("
    SELECT * FROM main_presenter
    ORDER BY created_at DESC
    LIMIT 1
");
$presentation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$presentation) {
    echo "❌ プレゼンデータが見つかりません\n";
    exit;
}

echo "プレゼンデータ:\n";
echo "  ID: " . $presentation['id'] . "\n";
echo "  タイプ: " . $presentation['presentation_type'] . "\n";
echo "  PDFパス: " . $presentation['pdf_path'] . "\n\n";

// p.8 生成
echo "[1] p.8 アイキャッチ生成中...\n";
generateSlideImage('main_presenter.php', 8);
echo "✓ p.8 生成完了\n\n";

if ($presentation['presentation_type'] === 'extended') {
    // p.204 生成
    echo "[2] p.204 アイキャッチ生成中...\n";
    generateSlideImage('main_presenter_204.php', 204);
    echo "✓ p.204 生成完了\n\n";

    // PDF画像枚数を確認
    if ($presentation['pdf_path']) {
        $pdfPath = __DIR__ . '/' . $presentation['pdf_path'];

        if (is_dir($pdfPath)) {
            $imageDir = $pdfPath;
        } else {
            $imageDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');
        }

        if (is_dir($imageDir)) {
            $pdfImages = glob($imageDir . '/page-*.png');
            sort($pdfImages);
            $imageCount = count($pdfImages);

            echo "[3] PDF画像: {$imageCount}枚\n";
            echo "  ディレクトリ: {$imageDir}\n\n";

            // 各PDFページのスライド生成
            for ($i = 0; $i < $imageCount; $i++) {
                $pageNum = 205 + $i;
                echo "  [3-" . ($i+1) . "] p.{$pageNum} 生成中...\n";
                generateSlideImage("main_presenter_extended.php?page=$pageNum", $pageNum);
                echo "  ✓ p.{$pageNum} 生成完了\n";
            }

            echo "\n✅ 全てのスライド生成完了\n";
            echo "  合計: " . (2 + $imageCount) . " 枚\n";
        } else {
            echo "❌ PDF画像ディレクトリが見つかりません: {$imageDir}\n";
        }
    } else {
        echo "⚠ PDFパスが設定されていません\n";
    }
} else {
    echo "⚠ シンプル版のため、p.204以降は生成しません\n";
}

echo "\n========================================\n";
echo "完了\n";
echo "========================================\n";
