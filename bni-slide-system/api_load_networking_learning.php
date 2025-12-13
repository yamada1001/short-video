<?php
/**
 * API: Load Networking Learning Corner Presenter
 * ネットワーキング学習コーナーの担当者を読み込み
 */

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json; charset=UTF-8');

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'ログインが必要です'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // パラメータ取得
    $weekDate = isset($_GET['week_date']) ? $_GET['week_date'] : null;

    if (!$weekDate) {
        throw new Exception('week_date パラメータが必要です');
    }

    // データベース接続
    $db = getDbConnection();

    // 担当者を取得
    $presenter = dbQueryOne($db,
        "SELECT * FROM networking_learning_presenters
        WHERE week_date = ?",
        [$weekDate]
    );

    dbClose($db);

    if ($presenter) {
        echo json_encode([
            'success' => true,
            'presenter' => $presenter,
            'week_date' => $weekDate
        ], JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'この週の担当者は設定されていません',
            'week_date' => $weekDate
        ], JSON_UNESCAPED_UNICODE);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    error_log('[API LOAD NETWORKING LEARNING] Error: ' . $e->getMessage());
}
