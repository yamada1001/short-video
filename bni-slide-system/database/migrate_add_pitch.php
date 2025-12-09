<?php
/**
 * BNI Slide System - Migration: Add Pitch Fields
 * ピッチ機能のためのカラム追加マイグレーション
 *
 * 実行方法:
 * php database/migrate_add_pitch.php
 */

require_once __DIR__ . '/../includes/db.php';

echo "=== BNI Slide System - Pitch Feature Migration ===\n\n";

try {
    $db = getDbConnection();

    echo "データベース接続成功\n";
    echo "マイグレーション開始...\n\n";

    // Check if columns already exist
    $result = $db->query("PRAGMA table_info(survey_data)");
    $columns = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $row['name'];
    }

    echo "既存のカラム: " . implode(', ', $columns) . "\n\n";

    // Check if migration is needed
    if (in_array('is_pitch_presenter', $columns)) {
        echo "⚠️  is_pitch_presenter カラムは既に存在します。マイグレーションをスキップします。\n";
        dbClose($db);
        exit(0);
    }

    // Add pitch-related columns
    echo "カラムを追加中...\n";

    // 1. is_pitch_presenter
    $db->exec("ALTER TABLE survey_data ADD COLUMN is_pitch_presenter INTEGER DEFAULT 0");
    echo "✓ is_pitch_presenter カラムを追加しました\n";

    // 2. pitch_file_path
    $db->exec("ALTER TABLE survey_data ADD COLUMN pitch_file_path TEXT");
    echo "✓ pitch_file_path カラムを追加しました\n";

    // 3. pitch_file_original_name
    $db->exec("ALTER TABLE survey_data ADD COLUMN pitch_file_original_name TEXT");
    echo "✓ pitch_file_original_name カラムを追加しました\n";

    // 4. pitch_file_type
    $db->exec("ALTER TABLE survey_data ADD COLUMN pitch_file_type TEXT");
    echo "✓ pitch_file_type カラムを追加しました\n";

    echo "\n";

    // Verify migration
    $result = $db->query("PRAGMA table_info(survey_data)");
    $newColumns = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $newColumns[] = $row['name'];
    }

    echo "マイグレーション後のカラム: " . implode(', ', $newColumns) . "\n\n";

    // Success message
    echo "✅ マイグレーション完了！\n";
    echo "\n";
    echo "追加されたカラム:\n";
    echo "  - is_pitch_presenter (INTEGER, DEFAULT 0)\n";
    echo "  - pitch_file_path (TEXT)\n";
    echo "  - pitch_file_original_name (TEXT)\n";
    echo "  - pitch_file_type (TEXT)\n";

    dbClose($db);

} catch (Exception $e) {
    echo "❌ エラーが発生しました: " . $e->getMessage() . "\n";
    exit(1);
}
