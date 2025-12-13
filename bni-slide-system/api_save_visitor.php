<?php
/**
 * API: Save Visitor Introduction
 * ビジターご紹介を保存
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

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => '管理者権限が必要です'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // POSTデータを取得
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSONデータが不正です');
    }

    // 必須フィールドのバリデーション
    $required_fields = ['week_date', 'visitor_name', 'sponsor', 'attendant'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            throw new Exception("{$field} は必須項目です");
        }
    }

    $weekDate = $data['week_date'];
    $visitorName = trim($data['visitor_name']);
    $company = isset($data['company']) ? trim($data['company']) : null;
    $specialty = isset($data['specialty']) ? trim($data['specialty']) : null;
    $sponsor = trim($data['sponsor']);
    $attendant = trim($data['attendant']);

    // データベース接続
    $db = getDbConnection();

    // display_order を取得（同じ週の最大値 + 1）
    $result = dbQueryOne($db,
        "SELECT COALESCE(MAX(display_order), -1) + 1 as next_order FROM visitor_introductions WHERE week_date = ?",
        [$weekDate]
    );
    $displayOrder = $result['next_order'];

    // データ挿入
    dbExecute($db,
        "INSERT INTO visitor_introductions
        (week_date, visitor_name, company, specialty, sponsor, attendant, display_order, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, datetime('now', 'localtime'), datetime('now', 'localtime'))",
        [$weekDate, $visitorName, $company, $specialty, $sponsor, $attendant, $displayOrder]
    );

    $newId = $db->lastInsertRowID();

    dbClose($db);

    echo json_encode([
        'success' => true,
        'message' => 'ビジター情報を保存しました',
        'id' => $newId
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    error_log('[API SAVE VISITOR] Error: ' . $e->getMessage());
}
