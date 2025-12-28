<?php
/**
 * 進捗更新API
 *
 * レッスンの進捗状態を更新する
 */

// APIレスポンスなのでエラー出力を無効化（JSONレスポンスを保証）
ini_set('display_errors', '0');
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// グローバルエラーハンドラー（予期しないエラーをキャッチ）
set_exception_handler(function($e) {
    error_log('API Exception: ' . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'サーバーエラーが発生しました'], JSON_UNESCAPED_UNICODE);
    exit;
});

// POSTのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Method not allowed', 405);
}

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    errorResponse('ログインが必要です', 401);
}

$user = getCurrentUser();

// JSONリクエストを取得
$input = json_decode(file_get_contents('php://input'), true);
$lessonId = $input['lesson_id'] ?? null;
$status = $input['status'] ?? 'completed'; // not_started, in_progress, completed

// バリデーション
if (!$lessonId) {
    errorResponse('レッスンIDが必要です');
}

$validStatuses = ['not_started', 'in_progress', 'completed'];
if (!in_array($status, $validStatuses)) {
    errorResponse('無効なステータスです');
}

// レッスンの存在チェック
$lessonSql = "SELECT * FROM lessons WHERE id = ?";
$lesson = db()->fetchOne($lessonSql, [$lessonId]);

if (!$lesson) {
    errorResponse('レッスンが見つかりません', 404);
}

// 進捗を更新
if (updateProgress($lessonId, $status)) {
    successResponse([
        'lesson_id' => $lessonId,
        'status' => $status,
        'message' => '進捗を更新しました'
    ]);
} else {
    errorResponse('進捗の更新に失敗しました', 500);
}
