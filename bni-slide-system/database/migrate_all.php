<?php
/**
 * BNI Slide System - Master Migration Script
 * 全てのデータ移行を一括で実行する
 *
 * 使い方:
 * php database/migrate_all.php [--force]
 *
 * --force: 既存のデータベースを削除して再作成
 */

// CLI実行のみ許可
if (php_sapi_name() !== 'cli') {
    die('このスクリプトはコマンドラインからのみ実行できます');
}

$forceRecreate = in_array('--force', $argv);

echo "==============================================\n";
echo "BNI Slide System - 完全移行\n";
echo "==============================================\n\n";

if ($forceRecreate) {
    echo "警告: 既存のデータベースを削除して再作成します\n\n";
} else {
    echo "既存のデータベースがある場合は、データを追加します\n";
    echo "削除して再作成する場合は、--force オプションを使用してください\n\n";
}

// 確認メッセージ
echo "この操作を続行しますか？ [y/N]: ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
fclose($handle);

if (strtolower($line) !== 'y') {
    echo "キャンセルされました\n";
    exit(0);
}

echo "\n";

// ステップ1: データベース初期化
echo "==============================================\n";
echo "ステップ1/4: データベース初期化\n";
echo "==============================================\n\n";

$initCommand = "php " . __DIR__ . "/init_db.php";
if ($forceRecreate) {
    $initCommand .= " --force";
}

system($initCommand, $initResult);

if ($initResult !== 0) {
    echo "\nエラー: データベース初期化に失敗しました\n";
    exit(1);
}

echo "\n";

// ステップ2: ユーザーデータ移行
echo "==============================================\n";
echo "ステップ2/4: ユーザーデータ移行\n";
echo "==============================================\n\n";

$userCommand = "php " . __DIR__ . "/migrate_users.php";
system($userCommand, $userResult);

if ($userResult !== 0) {
    echo "\nエラー: ユーザーデータ移行に失敗しました\n";
    exit(1);
}

echo "\n";

// ステップ3: CSVデータ移行
echo "==============================================\n";
echo "ステップ3/4: CSVデータ移行\n";
echo "==============================================\n\n";

$csvCommand = "php " . __DIR__ . "/migrate_csv_to_sqlite.php";
system($csvCommand, $csvResult);

if ($csvResult !== 0) {
    echo "\nエラー: CSVデータ移行に失敗しました\n";
    exit(1);
}

echo "\n";

// ステップ4: 監査ログ移行
echo "==============================================\n";
echo "ステップ4/4: 監査ログ移行\n";
echo "==============================================\n\n";

$auditCommand = "php " . __DIR__ . "/migrate_audit_logs.php";
system($auditCommand, $auditResult);

if ($auditResult !== 0) {
    echo "\nエラー: 監査ログ移行に失敗しました\n";
    exit(1);
}

echo "\n";

// 完了
echo "==============================================\n";
echo "全ての移行が完了しました！\n";
echo "==============================================\n\n";

echo "次のステップ:\n";
echo "1. データベースの内容を確認\n";
echo "2. APIファイルをSQLite対応に書き換え\n";
echo "3. テスト・検証\n\n";
