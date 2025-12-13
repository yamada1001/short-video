<?php
/**
 * BNI Slide System - Load Attendance Check Data API
 * 週ごとの出欠確認データを読み込むAPI
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/db.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'ログインが必要です'
    ]);
    exit;
}

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => '管理者権限が必要です'
    ]);
    exit;
}

try {
    $weekDate = trim($_GET['week_date'] ?? '');

    if (empty($weekDate)) {
        throw new Exception('週の日付が指定されていません');
    }

    $db = getDbConnection();

    $query = "SELECT * FROM attendance_check WHERE week_date = :week_date LIMIT 1";
    $data = dbQueryOne($db, $query, [':week_date' => $weekDate]);

    dbClose($db);

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (Exception $e) {
    error_log('[API LOAD ATTENDANCE] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
