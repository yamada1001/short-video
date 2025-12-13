<?php
/**
 * API: Load Visitor Introductions
 * ビジターご紹介一覧を読み込み
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

    $visitors = [];

    // 1. Get admin-managed visitor introductions
    $adminVisitors = dbQuery($db,
        "SELECT
            id,
            visitor_name,
            company,
            specialty,
            sponsor,
            attendant,
            display_order,
            'admin' as source,
            created_at
        FROM visitor_introductions
        WHERE week_date = ?
        ORDER BY display_order ASC, created_at ASC",
        [$weekDate]
    );

    if ($adminVisitors) {
        $visitors = array_merge($visitors, $adminVisitors);
    }

    // 2. Get survey-based visitors (read-only, cannot be deleted)
    $surveyVisitors = dbQuery($db,
        "SELECT
            v.id,
            v.visitor_name,
            v.visitor_company as company,
            v.visitor_industry as specialty,
            s.user_name as sponsor,
            s.user_name as attendant,
            0 as display_order,
            'survey' as source,
            v.created_at
        FROM visitors v
        JOIN survey_data s ON v.survey_data_id = s.id
        WHERE s.week_date = ?
            AND v.visitor_name IS NOT NULL
            AND v.visitor_name != ''
        ORDER BY v.created_at ASC",
        [$weekDate]
    );

    if ($surveyVisitors) {
        $visitors = array_merge($visitors, $surveyVisitors);
    }

    dbClose($db);

    echo json_encode([
        'success' => true,
        'visitors' => $visitors,
        'week_date' => $weekDate
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    error_log('[API LOAD VISITORS] Error: ' . $e->getMessage());
}
