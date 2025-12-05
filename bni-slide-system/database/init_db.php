<?php
/**
 * BNI Slide System - Database Initialization Script
 * データベースを初期化し、スキーマを作成する
 *
 * 使い方:
 * php database/init_db.php [--force]
 *
 * --force: 既存のデータベースを削除して再作成
 */

require_once __DIR__ . '/../includes/db.php';

// CLI実行のみ許可
if (php_sapi_name() !== 'cli') {
    die('このスクリプトはコマンドラインからのみ実行できます');
}

$forceRecreate = in_array('--force', $argv);

echo "==============================================\n";
echo "BNI Slide System - データベース初期化\n";
echo "==============================================\n\n";

// データベースファイルのパス
$dbPath = DB_PATH;
$dbDir = dirname($dbPath);

// dataディレクトリが存在するか確認
if (!is_dir($dbDir)) {
    echo "エラー: dataディレクトリが存在しません: {$dbDir}\n";
    exit(1);
}

// 既存のデータベースファイルをチェック
if (file_exists($dbPath)) {
    if ($forceRecreate) {
        echo "既存のデータベースを削除します...\n";
        unlink($dbPath);
        echo "✓ 削除完了\n\n";
    } else {
        echo "警告: データベースファイルが既に存在します: {$dbPath}\n";
        echo "既存のデータベースを削除して再作成する場合は、--force オプションを使用してください。\n";
        echo "例: php database/init_db.php --force\n\n";
        exit(0);
    }
}

// スキーマファイルを読み込み
$schemaFile = __DIR__ . '/schema.sql';

if (!file_exists($schemaFile)) {
    echo "エラー: スキーマファイルが見つかりません: {$schemaFile}\n";
    exit(1);
}

echo "スキーマファイルを読み込み中...\n";
$schema = file_get_contents($schemaFile);

if ($schema === false) {
    echo "エラー: スキーマファイルの読み込みに失敗しました\n";
    exit(1);
}

echo "✓ スキーマファイル読み込み完了\n\n";

try {
    // データベース接続
    echo "データベースに接続中...\n";
    $db = getDbConnection();
    echo "✓ データベース接続成功\n\n";

    // スキーマを実行
    echo "テーブルを作成中...\n";
    $result = $db->exec($schema);

    if ($result === false) {
        throw new Exception("スキーマの実行に失敗しました: " . $db->lastErrorMsg());
    }

    echo "✓ テーブル作成完了\n\n";

    // 作成されたテーブルを確認
    echo "作成されたテーブル:\n";
    $tables = dbQuery($db, "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");

    foreach ($tables as $table) {
        echo "  - " . $table['name'] . "\n";
    }

    echo "\n";

    // インデックスを確認
    echo "作成されたインデックス:\n";
    $indexes = dbQuery($db, "SELECT name, tbl_name FROM sqlite_master WHERE type='index' AND name NOT LIKE 'sqlite_%' ORDER BY tbl_name, name");

    foreach ($indexes as $index) {
        echo "  - " . $index['name'] . " (on " . $index['tbl_name'] . ")\n";
    }

    echo "\n";

    // データベース接続を閉じる
    dbClose($db);

    echo "==============================================\n";
    echo "データベース初期化が完了しました\n";
    echo "データベースファイル: {$dbPath}\n";
    echo "==============================================\n";

} catch (Exception $e) {
    echo "\n";
    echo "エラー: " . $e->getMessage() . "\n";
    echo "\n";
    exit(1);
}
