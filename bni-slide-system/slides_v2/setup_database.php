<?php
/**
 * データベースセットアップスクリプト
 * 本番サーバーで1回だけ実行してください
 * URL: https://yojitu.com/bni-slide-system/slides_v2/setup_database.php
 */

require_once __DIR__ . '/config.php';

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Database Setup</title>";
echo "<style>body{font-family:sans-serif;max-width:800px;margin:50px auto;padding:20px;}";
echo ".success{color:green;}.error{color:red;}.info{color:blue;}pre{background:#f5f5f5;padding:15px;}</style></head><body>";
echo "<h1>BNI Slide System V2 - Database Setup</h1>";

// データベースファイルの存在確認
$dbDir = __DIR__ . '/data';
$dbFile = $dbDir . '/bni_slide_system.db';

if (!is_dir($dbDir)) {
    mkdir($dbDir, 0775, true);
    echo "<p class='info'>✓ データディレクトリを作成しました</p>";
}

// 既存DBのバックアップ
if (file_exists($dbFile) && filesize($dbFile) > 0) {
    $backupFile = $dbDir . '/bni_slide_system_backup_' . date('YmdHis') . '.db';
    copy($dbFile, $backupFile);
    echo "<p class='info'>✓ 既存DBをバックアップしました: " . basename($backupFile) . "</p>";
}

try {
    // データベース接続
    $db = new PDO('sqlite:' . $dbFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2>スキーマ作成中...</h2>";

    // スキーマSQLを読み込み
    $schemaFile = dirname(__DIR__) . '/database/schema_v2.sql';

    if (!file_exists($schemaFile)) {
        throw new Exception("スキーマファイルが見つかりません: $schemaFile");
    }

    $schemaSql = file_get_contents($schemaFile);
    $db->exec($schemaSql);

    echo "<p class='success'>✓ スキーマを作成しました</p>";

    // 初期メンバーデータを投入
    echo "<h2>初期データ投入中...</h2>";

    $initialDataFile = dirname(__DIR__) . '/database/initial_members_v2.sql';

    if (file_exists($initialDataFile)) {
        $initialDataSql = file_get_contents($initialDataFile);
        $db->exec($initialDataSql);
        echo "<p class='success'>✓ 初期メンバーデータ（48名）を投入しました</p>";
    }

    // テーブル一覧を表示
    echo "<h2>作成されたテーブル:</h2><pre>";
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $table) {
        $count = $db->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "- $table ($count レコード)\n";
    }
    echo "</pre>";

    // パーミッション設定
    chmod($dbFile, 0664);
    chmod($dbDir, 0775);

    echo "<p class='success'>✓ パーミッションを設定しました (DB: 664, DIR: 775)</p>";

    echo "<h2 class='success'>セットアップ完了！</h2>";
    echo "<p>データベースサイズ: " . round(filesize($dbFile) / 1024, 2) . " KB</p>";
    echo "<p><strong>重要:</strong> セキュリティのため、このファイルを削除してください。</p>";
    echo "<p><a href='admin/index.php'>管理画面へ →</a></p>";

} catch (Exception $e) {
    echo "<p class='error'>✗ エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</body></html>";
