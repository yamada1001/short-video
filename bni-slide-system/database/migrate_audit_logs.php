<?php
/**
 * BNI Slide System - Audit Log Migration Script
 * audit_log.jsonからaudit_logsテーブルへデータを移行する
 *
 * 使い方:
 * php database/migrate_audit_logs.php
 */

require_once __DIR__ . '/../includes/db.php';

// CLI実行のみ許可
if (php_sapi_name() !== 'cli') {
    die('このスクリプトはコマンドラインからのみ実行できます');
}

echo "==============================================\n";
echo "BNI Slide System - 監査ログ移行\n";
echo "==============================================\n\n";

// audit_log.jsonを読み込み
$auditFile = __DIR__ . '/../data/audit_log.json';

if (!file_exists($auditFile)) {
    echo "警告: audit_log.jsonが見つかりませんでした\n";
    echo "スキップします\n";
    exit(0);
}

echo "audit_log.jsonを読み込み中...\n";
$content = file_get_contents($auditFile);
$data = json_decode($content, true);

if (!$data || !isset($data['logs'])) {
    echo "エラー: audit_log.jsonの読み込みに失敗しました\n";
    exit(1);
}

$logs = $data['logs'];
$logCount = count($logs);

echo "✓ {$logCount}件のログを検出\n\n";

if ($logCount === 0) {
    echo "ログが0件です。スキップします\n";
    exit(0);
}

try {
    // データベース接続
    echo "データベースに接続中...\n";
    $db = getDbConnection();
    echo "✓ データベース接続成功\n\n";

    // トランザクション開始
    dbBeginTransaction($db);

    echo "監査ログを移行中...\n";

    $insertedCount = 0;
    $errorCount = 0;

    foreach ($logs as $log) {
        try {
            $query = "INSERT INTO audit_logs (
                action,
                target,
                user_email,
                user_name,
                data,
                ip_address,
                user_agent,
                created_at
            ) VALUES (
                :action,
                :target,
                :user_email,
                :user_name,
                :data,
                :ip_address,
                :user_agent,
                :created_at
            )";

            $params = [
                ':action' => $log['action'] ?? 'unknown',
                ':target' => $log['target'] ?? 'unknown',
                ':user_email' => $log['user_email'] ?? '',
                ':user_name' => $log['user_name'] ?? '',
                ':data' => json_encode($log['data'] ?? [], JSON_UNESCAPED_UNICODE),
                ':ip_address' => $log['ip_address'] ?? null,
                ':user_agent' => $log['user_agent'] ?? null,
                ':created_at' => $log['timestamp'] ?? date('Y-m-d H:i:s')
            ];

            dbExecute($db, $query, $params);

            $insertedCount++;

            if ($insertedCount % 100 === 0) {
                echo "  - {$insertedCount}件処理済み...\n";
            }

        } catch (Exception $e) {
            echo "  ✗ エラー: " . $e->getMessage() . "\n";
            $errorCount++;
        }
    }

    // トランザクションをコミット
    dbCommit($db);

    echo "\n";
    echo "==============================================\n";
    echo "監査ログ移行が完了しました\n";
    echo "==============================================\n";
    echo "挿入: {$insertedCount}件\n";
    echo "エラー: {$errorCount}件\n";
    echo "==============================================\n";

    // データベース接続を閉じる
    dbClose($db);

} catch (Exception $e) {
    // エラーが発生した場合はロールバック
    if (isset($db)) {
        dbRollback($db);
        dbClose($db);
    }

    echo "\n";
    echo "エラー: " . $e->getMessage() . "\n";
    echo "トランザクションをロールバックしました\n";
    echo "\n";
    exit(1);
}
