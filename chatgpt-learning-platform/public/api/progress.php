<?php
/**
 * 進捗更新API
 *
 * レッスンの進捗状態を更新する
 */

// 出力バッファリング開始（すべての出力を捕捉）
ob_start();

// APIレスポンスなのでエラー出力を無効化（JSONレスポンスを保証）
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

// Fatal Errorも捕捉するシャットダウンハンドラー
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_clean(); // バッファをクリア
        error_log('API Fatal Error: ' . $error['message'] . ' in ' . $error['file'] . ':' . $error['line']);
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'サーバーエラーが発生しました'], JSON_UNESCAPED_UNICODE);
        exit;
    }
});

// 例外ハンドラー
set_exception_handler(function($e) {
    ob_clean(); // バッファをクリア
    error_log('API Exception: ' . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'サーバーエラーが発生しました'], JSON_UNESCAPED_UNICODE);
    exit;
});

// エラーハンドラー（Warning/Noticeを捕捉）
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // エラーをログに記録
    error_log("API Error [$errno]: $errstr in $errfile:$errline");
    // エラー抑制演算子(@)が使われている場合はスキップ
    if (error_reporting() === 0) {
        return false;
    }
    return true; // エラーを記録したが、処理は継続
});

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// POSTのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ob_clean();
    errorResponse('Method not allowed', 405);
}

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    ob_clean();
    errorResponse('ログインが必要です', 401);
}

$user = getCurrentUser();

// JSONリクエストを取得
$input = json_decode(file_get_contents('php://input'), true);
$lessonId = $input['lesson_id'] ?? null;
$status = $input['status'] ?? 'completed'; // not_started, in_progress, completed

// バリデーション
if (!$lessonId) {
    ob_clean();
    errorResponse('レッスンIDが必要です');
}

$validStatuses = ['not_started', 'in_progress', 'completed'];
if (!in_array($status, $validStatuses)) {
    ob_clean();
    errorResponse('無効なステータスです');
}

// レッスンの存在チェック
$lessonSql = "SELECT * FROM lessons WHERE id = ?";
$lesson = db()->fetchOne($lessonSql, [$lessonId]);

if (!$lesson) {
    ob_clean();
    errorResponse('レッスンが見つかりません', 404);
}

// 進捗を更新
if (updateProgress($lessonId, $status)) {
    // バッファをクリアして、クリーンなJSONだけを出力
    ob_clean();
    successResponse([
        'lesson_id' => $lessonId,
        'status' => $status,
        'message' => '進捗を更新しました'
    ]);
} else {
    ob_clean();
    errorResponse('進捗の更新に失敗しました', 500);
}
