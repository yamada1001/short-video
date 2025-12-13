<?php
/**
 * BNI Slide System - Load Speaker Rotation Data API
 * スピーカーローテーション表データ取得API
 * 指定週を含む前後4週間分のデータを返す
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

try {
    $weekDate = trim($_GET['week_date'] ?? '');

    if (empty($weekDate)) {
        // デフォルトで今週の金曜日を取得
        $weekDate = date('Y-m-d', strtotime('friday this week'));
    }

    $db = getDbConnection();

    // 指定週を含む前後の週を取得（前1週、当週、後3週の合計5週分）
    $weeks = [];
    $currentDate = new DateTime($weekDate);

    // 前1週
    $prevWeek = clone $currentDate;
    $prevWeek->modify('-7 days');
    $weeks[] = $prevWeek->format('Y-m-d');

    // 当週
    $weeks[] = $currentDate->format('Y-m-d');

    // 後3週
    for ($i = 1; $i <= 3; $i++) {
        $nextWeek = clone $currentDate;
        $nextWeek->modify('+' . ($i * 7) . ' days');
        $weeks[] = $nextWeek->format('Y-m-d');
    }

    // 各週のデータを取得
    $rotation = [];
    foreach ($weeks as $week) {
        $query = "SELECT * FROM weekly_presenters WHERE week_date = :week_date LIMIT 1";
        $data = dbQueryOne($db, $query, [':week_date' => $week]);

        $rotation[] = [
            'week_date' => $week,
            'date_display' => date('n/j', strtotime($week)), // "12/5" 形式
            'member_name' => $data['member_name'] ?? '',
            'topic' => $data['topic'] ?? '',
            'referral_target' => $data['referral_target'] ?? '',
            'is_current' => ($week === $weekDate)
        ];
    }

    dbClose($db);

    echo json_encode([
        'success' => true,
        'data' => $rotation,
        'current_week' => $weekDate
    ]);

} catch (Exception $e) {
    error_log('[API LOAD SPEAKER ROTATION] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
