<?php
/**
 * データベース内容確認スクリプト
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "データベース内容確認\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // メインプレゼンター
    echo "[1] メインプレゼンター\n";
    $stmt = $db->query("SELECT COUNT(*) as count FROM main_presenter");
    $count = $stmt->fetchColumn();
    echo "  件数: {$count}\n";

    if ($count > 0) {
        $stmt = $db->query("SELECT pdf_path, created_at FROM main_presenter ORDER BY created_at DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "  最新PDF: {$row['pdf_path']}\n";
        echo "  登録日時: {$row['created_at']}\n";

        // PDFページ数を確認
        if ($row['pdf_path']) {
            $pdfPath = __DIR__ . '/' . $row['pdf_path'];
            if (is_dir($pdfPath)) {
                $imageDir = $pdfPath;
            } else {
                $imageDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');
            }

            if (is_dir($imageDir)) {
                $pdfImages = glob($imageDir . '/page-*.png');
                echo "  PDFページ数: " . count($pdfImages) . "\n";
            } else {
                echo "  画像ディレクトリ: 存在しません\n";
            }
        }
    }
    echo "\n";

    // ネットワーキング学習
    echo "[2] ネットワーキング学習PDF\n";
    $stmt = $db->query("SELECT COUNT(*) as count FROM networking_learning");
    $count = $stmt->fetchColumn();
    echo "  件数: {$count}\n";

    if ($count > 0) {
        $stmt = $db->query("SELECT pdf_path, week_date, created_at FROM networking_learning ORDER BY created_at DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "  最新PDF: {$row['pdf_path']}\n";
        echo "  対象週: {$row['week_date']}\n";
        echo "  登録日時: {$row['created_at']}\n";

        // PDFページ数を確認
        if ($row['pdf_path']) {
            $pdfPath = __DIR__ . '/' . $row['pdf_path'];
            if (is_dir($pdfPath)) {
                $imageDir = $pdfPath;
            } else {
                $imageDir = dirname($pdfPath) . '/images_' . basename($pdfPath, '.pdf');
            }

            if (is_dir($imageDir)) {
                $pdfImages = glob($imageDir . '/page-*.png');
                echo "  PDFページ数: " . count($pdfImages) . "\n";
            } else {
                echo "  画像ディレクトリ: 存在しません\n";
            }
        }
    }
    echo "\n";

    // ビジター
    echo "[3] ビジター\n";
    $stmt = $db->query("SELECT COUNT(*) as count FROM visitors");
    $count = $stmt->fetchColumn();
    echo "  総件数: {$count}\n";

    if ($count > 0) {
        $stmt = $db->query("SELECT week_date, COUNT(*) as count FROM visitors GROUP BY week_date ORDER BY week_date DESC LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "  最新週: {$row['week_date']}\n";
        echo "  ビジター数: {$row['count']}名\n";
    }
    echo "\n";

    // 代理出席
    echo "[4] 代理出席\n";
    $stmt = $db->query("SELECT COUNT(*) as count FROM substitutes");
    $count = $stmt->fetchColumn();
    echo "  件数: {$count}\n\n";

    // 新メンバー
    echo "[5] 新メンバー\n";
    $stmt = $db->query("SELECT COUNT(*) as count FROM new_members");
    $count = $stmt->fetchColumn();
    echo "  件数: {$count}\n\n";

    // 週間No.1
    echo "[6] 週間No.1\n";
    $stmt = $db->query("SELECT COUNT(*) as count FROM weekly_no1");
    $count = $stmt->fetchColumn();
    echo "  件数: {$count}\n\n";

    echo "========================================\n";
    echo "完了\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
    echo "\n詳細:\n";
    echo $e->getTraceAsString() . "\n";
}
