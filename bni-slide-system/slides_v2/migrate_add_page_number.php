<?php
/**
 * マイグレーション: start_dash_presenterテーブルにpage_numberカラムを追加
 * URL: https://yojitu.com/bni-slide-system/slides_v2/migrate_add_page_number.php
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "Migration: Add page_number to start_dash_presenter\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // カラムが既に存在するか確認
    $checkStmt = $db->query("PRAGMA table_info(start_dash_presenter)");
    $columns = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

    $hasPageNumber = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'page_number') {
            $hasPageNumber = true;
            break;
        }
    }

    if ($hasPageNumber) {
        echo "✓ page_numberカラムは既に存在します\n";
        echo "マイグレーションは不要です\n";
    } else {
        echo "[1] page_numberカラムを追加中...\n";

        $db->exec("ALTER TABLE start_dash_presenter ADD COLUMN page_number INTEGER NOT NULL DEFAULT 15");

        echo "✓ page_numberカラムを追加しました\n\n";

        // 確認
        echo "[2] スキーマ確認:\n";
        $schemaStmt = $db->query("PRAGMA table_info(start_dash_presenter)");
        $newColumns = $schemaStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($newColumns as $col) {
            echo "  - {$col['name']} ({$col['type']})\n";
        }

        echo "\n✅ マイグレーション完了\n";
    }

    echo "\n========================================\n";
    echo "完了\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
    echo "\n詳細:\n";
    echo $e->getTraceAsString() . "\n";
}
