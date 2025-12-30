#!/usr/bin/env php
<?php
/**
 * データベース初期化スクリプト
 * Usage: php database/init_db.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Protea\Database;

echo "=== Protea データベース初期化 ===\n\n";

try {
    // データベース接続（自動的にスキーマが作成される）
    $db = Database::getInstance();
    $pdo = $db->getPDO();

    echo "データベース接続成功\n";

    // テーブル一覧を取得
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "\n作成されたテーブル:\n";
    foreach ($tables as $table) {
        echo "  - {$table}\n";

        // 各テーブルのレコード数を表示
        $count = $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
        echo "    (レコード数: {$count})\n";
    }

    echo "\nデータベース初期化完了！\n";

} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "\n";
    exit(1);
}
