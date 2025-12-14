<?php
/**
 * マイグレーション: substitutesテーブルにsubstitute_noカラムを追加
 * URL: https://yojitu.com/bni-slide-system/slides_v2/migrate_add_substitute_no.php
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "Migration: Add substitute_no to substitutes\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // カラムが既に存在するか確認
    $checkStmt = $db->query("PRAGMA table_info(substitutes)");
    $columns = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

    $hasSubstituteNo = false;
    foreach ($columns as $column) {
        if ($column['name'] === 'substitute_no') {
            $hasSubstituteNo = true;
            break;
        }
    }

    if ($hasSubstituteNo) {
        echo "✓ substitute_noカラムは既に存在します\n";
        echo "マイグレーションは不要です\n";
    } else {
        echo "[1] substitute_noカラムを追加中...\n";

        $db->exec("ALTER TABLE substitutes ADD COLUMN substitute_no INTEGER NOT NULL DEFAULT 1");

        echo "✓ substitute_noカラムを追加しました\n\n";

        // 確認
        echo "[2] スキーマ確認:\n";
        $schemaStmt = $db->query("PRAGMA table_info(substitutes)");
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
