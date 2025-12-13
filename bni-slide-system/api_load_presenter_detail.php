<?php
/**
 * BNI Slide System - Load Presenter Detail Data API
 * メインプレゼンター詳細データ取得API
 * プレゼンター情報とユーザー詳細を結合して返す
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

    // プレゼンター情報を取得
    $query = "
        SELECT
            wp.*,
            u.name as user_name,
            u.company as user_company,
            u.category as user_category
        FROM weekly_presenters wp
        LEFT JOIN users u ON wp.member_id = u.id
        WHERE wp.week_date = :week_date
        LIMIT 1
    ";
    $data = dbQueryOne($db, $query, [':week_date' => $weekDate]);

    dbClose($db);

    // プレゼンター名の強調部分を自動検出（赤文字にする文字）
    $highlightChar = '';
    if ($data && !empty($data['member_name'])) {
        // 名前から特定の文字を強調（例: 「健太」→「健」を赤に）
        // デフォルトでは最初の1文字を赤にする（カスタマイズ可能）
        $name = $data['member_name'];
        if (mb_strlen($name) > 0) {
            // 名前の2文字目を赤にする（一般的に名前の中の1文字）
            $highlightChar = mb_substr($name, mb_strlen($name) > 2 ? 1 : 0, 1);
        }
    }

    echo json_encode([
        'success' => true,
        'data' => $data,
        'highlight_char' => $highlightChar
    ]);

} catch (Exception $e) {
    error_log('[API LOAD PRESENTER DETAIL] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
