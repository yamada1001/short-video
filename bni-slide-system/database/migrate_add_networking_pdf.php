<?php
/**
 * Migration: Add PDF file path to networking_learning_presenters table
 * ネットワーキング学習コーナーにPDFファイルパスカラムを追加
 */

require_once __DIR__ . '/../includes/db.php';

try {
    echo "=== ネットワーキング学習コーナーPDFカラム追加マイグレーション開始 ===\n\n";

    $db = getDbConnection();

    // Check if column already exists
    $tableInfo = $db->query("PRAGMA table_info(networking_learning_presenters)");
    $columns = [];
    while ($row = $tableInfo->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $row['name'];
    }

    if (in_array('pdf_file_path', $columns)) {
        echo "✓ pdf_file_path カラムは既に存在します\n";
    } else {
        echo "+ pdf_file_path カラムを追加中...\n";
        $db->exec("ALTER TABLE networking_learning_presenters ADD COLUMN pdf_file_path TEXT");
        echo "✓ pdf_file_path カラムを追加しました\n";
    }

    if (in_array('pdf_file_original_name', $columns)) {
        echo "✓ pdf_file_original_name カラムは既に存在します\n";
    } else {
        echo "+ pdf_file_original_name カラムを追加中...\n";
        $db->exec("ALTER TABLE networking_learning_presenters ADD COLUMN pdf_file_original_name TEXT");
        echo "✓ pdf_file_original_name カラムを追加しました\n";
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
