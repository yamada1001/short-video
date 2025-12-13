<?php
/**
 * API: Save Networking Learning Corner Presenter
 * ネットワーキング学習コーナーの担当者を保存
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
    if (empty($data['week_date'])) {
        throw new Exception('week_date は必須項目です');
    }

    if (empty($data['presenter_name'])) {
        throw new Exception('presenter_name は必須項目です');
    }

    $weekDate = $data['week_date'];
    $presenterName = trim($data['presenter_name']);

    // データベース接続
    $db = getDbConnection();

    // 既存データを削除（週ごとに1人のみのため）
    dbExecute($db,
        "DELETE FROM networking_learning_presenters WHERE week_date = ?",
        [$weekDate]
    );

    // 新規データを挿入
    dbExecute($db,
        "INSERT INTO networking_learning_presenters
        (week_date, presenter_name, created_at, updated_at)
        VALUES (?, ?, datetime('now', 'localtime'), datetime('now', 'localtime'))",
        [$weekDate, $presenterName]
    );

    $newId = $db->lastInsertRowID();

    dbClose($db);

    echo json_encode([
        'success' => true,
        'message' => '担当者を保存しました',
        'id' => $newId
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    error_log('[API SAVE NETWORKING LEARNING] Error: ' . $e->getMessage());
}
