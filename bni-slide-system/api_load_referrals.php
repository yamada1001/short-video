<?php
/**
 * BNI Slide System - Load Referral Data API
 * リファーラル金額データを読み込むAPI
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
    $weekDate = $_GET['week'] ?? '';

    if (empty($weekDate)) {
        throw new Exception('週が指定されていません');
    }

    // Load referral data from database
    $db = getDbConnection();

    $query = "SELECT * FROM referrals_weekly WHERE week_date = :week_date LIMIT 1";
    $result = dbQueryOne($db, $query, [':week_date' => $weekDate]);

    dbClose($db);

    if ($result) {
        echo json_encode([
            'success' => true,
            'data' => [
                'week_date' => $result['week_date'],
                'total_amount' => intval($result['total_amount']),
                'notes' => $result['notes'] ?? '',
                'updated_at' => $result['updated_at'] ?? null
            ]
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'data' => null,
            'message' => 'データがありません'
        ]);
    }

} catch (Exception $e) {
    error_log('[API LOAD REFERRALS] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
