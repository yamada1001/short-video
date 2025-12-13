<?php
/**
 * Migration: Add pdf_page_count column to networking_learning_presenters table
 * ネットワーキング学習コーナーにPDFページ数カラムを追加
 */

// HTML mode for browser access
if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>マイグレーション実行</title>';
    echo '<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}pre{background:white;padding:20px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.1);}</style>';
    echo '</head><body><pre>';
    ob_start();
}

require_once __DIR__ . '/../includes/db.php';

try {
    echo "=== ネットワーキング学習コーナーPDFページ数カラム追加マイグレーション開始 ===\n\n";

    $db = getDbConnection();

    // Check if column already exists
    $tableInfo = $db->query("PRAGMA table_info(networking_learning_presenters)");
    $columns = [];
    while ($row = $tableInfo->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $row['name'];
    }

    if (in_array('pdf_page_count', $columns)) {
        echo "✓ pdf_page_count カラムは既に存在します\n";
    } else {
        echo "+ pdf_page_count カラムを追加中...\n";
        $db->exec("ALTER TABLE networking_learning_presenters ADD COLUMN pdf_page_count INTEGER DEFAULT 0");
        echo "✓ pdf_page_count カラムを追加しました\n";
    }

    if (in_array('pdf_image_paths', $columns)) {
        echo "✓ pdf_image_paths カラムは既に存在します\n";
    } else {
        echo "+ pdf_image_paths カラムを追加中...\n";
        $db->exec("ALTER TABLE networking_learning_presenters ADD COLUMN pdf_image_paths TEXT");
        echo "✓ pdf_image_paths カラムを追加しました（JSON配列を保存）\n";
    }

    dbClose($db);

    echo "\n=== マイグレーション完了 ===\n";

} catch (Exception $e) {
    echo "\n❌ エラー: " . $e->getMessage() . "\n";
    if (isset($db)) {
        dbClose($db);
    }
    exit(1);
}

// Close HTML for browser mode
if (php_sapi_name() !== 'cli') {
    $output = ob_get_clean();
    echo htmlspecialchars($output);
    echo '</pre></body></html>';
}
