<?php
/**
 * BNI Slide System - Audit Logger
 * 監査ログ記録機能
 */

/**
 * 監査ログを記録
 *
 * @param string $action アクション（'create', 'update', 'delete'）
 * @param string $target 対象（'survey_data', 'user', 'member'）
 * @param array $data 変更内容
 * @param string $userEmail ユーザーのメールアドレス
 * @param string $userName ユーザーの名前
 * @return bool 成功/失敗
 */
function writeAuditLog($action, $target, $data, $userEmail, $userName) {
    $logFile = __DIR__ . '/../data/audit_log.json';

    // ログファイルが存在しない場合は作成
    if (!file_exists($logFile)) {
        $initialData = ['logs' => []];
        file_put_contents($logFile, json_encode($initialData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    // 既存のログを読み込み
    $content = file_get_contents($logFile);
    $logData = json_decode($content, true);

    if (!$logData || !isset($logData['logs'])) {
        $logData = ['logs' => []];
    }

    // 新しいログエントリを作成
    $logEntry = [
        'id' => uniqid('log_', true),
        'timestamp' => date('Y-m-d H:i:s'),
        'action' => $action,
        'target' => $target,
        'user_email' => $userEmail,
        'user_name' => $userName,
        'data' => $data,
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];

    // ログを追加（新しいものが先頭）
    array_unshift($logData['logs'], $logEntry);

    // ログが1000件を超えたら古いものを削除
    if (count($logData['logs']) > 1000) {
        $logData['logs'] = array_slice($logData['logs'], 0, 1000);
    }

    // ログファイルに保存
    $jsonContent = json_encode($logData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    $result = file_put_contents($logFile, $jsonContent);

    return $result !== false;
}

/**
 * 監査ログを取得
 *
 * @param int $limit 取得件数（デフォルト: 100）
 * @param int $offset オフセット（デフォルト: 0）
 * @param string $filterAction アクションでフィルタ（オプション）
 * @param string $filterUser ユーザーでフィルタ（オプション）
 * @return array ログエントリの配列
 */
function getAuditLogs($limit = 100, $offset = 0, $filterAction = null, $filterUser = null) {
    $logFile = __DIR__ . '/../data/audit_log.json';

    if (!file_exists($logFile)) {
        return [];
    }

    $content = file_get_contents($logFile);
    $logData = json_decode($content, true);

    if (!$logData || !isset($logData['logs'])) {
        return [];
    }

    $logs = $logData['logs'];

    // フィルタリング
    if ($filterAction) {
        $logs = array_filter($logs, function($log) use ($filterAction) {
            return $log['action'] === $filterAction;
        });
    }

    if ($filterUser) {
        $logs = array_filter($logs, function($log) use ($filterUser) {
            return $log['user_email'] === $filterUser || $log['user_name'] === $filterUser;
        });
    }

    // ページネーション
    $logs = array_slice($logs, $offset, $limit);

    return $logs;
}

/**
 * 監査ログの総件数を取得
 *
 * @return int ログ件数
 */
function getAuditLogCount() {
    $logFile = __DIR__ . '/../data/audit_log.json';

    if (!file_exists($logFile)) {
        return 0;
    }

    $content = file_get_contents($logFile);
    $logData = json_decode($content, true);

    if (!$logData || !isset($logData['logs'])) {
        return 0;
    }

    return count($logData['logs']);
}

/**
 * Write audit log to SQLite database
 *
 * @param string $action アクション（'create', 'update', 'delete'）
 * @param string $target 対象（'survey_data', 'user', etc.）
 * @param array $data 変更内容
 * @param string $userEmail ユーザーメールアドレス
 * @param string $userName ユーザー名
 * @return bool 成功/失敗
 */
function writeAuditLogToDb($action, $target, $data, $userEmail, $userName) {
    require_once __DIR__ . '/db.php';

    try {
        $db = getDbConnection();

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
            ':action' => $action,
            ':target' => $target,
            ':user_email' => $userEmail,
            ':user_name' => $userName,
            ':data' => json_encode($data, JSON_UNESCAPED_UNICODE),
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            ':created_at' => date('Y-m-d H:i:s')
        ];

        dbExecute($db, $query, $params);
        dbClose($db);

        return true;
    } catch (Exception $e) {
        error_log("Audit log write failed: " . $e->getMessage());
        return false;
    }
}
