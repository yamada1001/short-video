<?php
/**
 * データベース完全リセット
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "データベース完全リセット\n";
echo "========================================\n\n";

// 既存のDBファイルを削除
if (file_exists($db_path)) {
    unlink($db_path);
    echo "[1] 既存のデータベースファイルを削除しました\n\n";
}

// 新しいDBファイルを作成
$db = new PDO('sqlite:' . $db_path);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "[2] 新しいデータベースファイルを作成しました\n\n";

// スキーマを読み込み
$schemaPath = __DIR__ . '/schema.sql';
if (!file_exists($schemaPath)) {
    die("❌ schema.sqlが見つかりません\n");
}

$schema = file_get_contents($schemaPath);

echo "[3] スキーマを読み込みました (" . strlen($schema) . " bytes)\n\n";

// スキーマを実行
try {
    $db->exec($schema);
    echo "[4] スキーマを実行しました\n\n";
} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n\n";
}

// テーブル一覧を確認
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);

echo "[5] 作成されたテーブル (" . count($tables) . "個):\n";
foreach ($tables as $table) {
    echo "  - $table\n";
}

echo "\n========================================\n";
echo "完了\n";
echo "========================================\n";
