<?php
/**
 * API: Delete Visitor Introduction
 * ビジターご紹介を削除
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

    if (!isset($data['id']) || !is_numeric($data['id'])) {
        throw new Exception('id パラメータが必要です');
    }

    $id = (int)$data['id'];

    // データベース接続
    $db = getDbConnection();

    // 削除実行
    dbExecute($db,
        "DELETE FROM visitor_introductions WHERE id = ?",
        [$id]
    );

    dbClose($db);

    echo json_encode([
        'success' => true,
        'message' => 'ビジター情報を削除しました'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    error_log('[API DELETE VISITOR] Error: ' . $e->getMessage());
}
