<?php
/**
 * フィードバック送信API
 *
 * ユーザーからのフィードバック（質問・バグ報告・要望）を保存
 */

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

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
$feedbackType = $input['feedback_type'] ?? 'question';
$message = $input['message'] ?? null;

// バリデーション
if (empty($message) || strlen(trim($message)) < 5) {
    errorResponse('フィードバック内容を5文字以上入力してください');
}

// フィードバックタイプのバリデーション
$validTypes = ['question', 'bug', 'request', 'other'];
if (!in_array($feedbackType, $validTypes)) {
    errorResponse('無効なフィードバックタイプです');
}

// レッスンIDのバリデーション（指定されている場合）
if ($lessonId !== null && !is_numeric($lessonId)) {
    errorResponse('無効なレッスンIDです');
}

try {
    // トランザクション開始
    db()->execute("START TRANSACTION");

    // フィードバックを保存
    $insertSql = "INSERT INTO user_feedback (user_id, lesson_id, feedback_type, message, status)
                 VALUES (?, ?, ?, ?, 'pending')";

    db()->execute($insertSql, [
        $user['id'],
        $lessonId,
        $feedbackType,
        trim($message)
    ]);

    // 挿入されたIDを取得
    $feedbackId = db()->getPdo()->lastInsertId();

    // コミット
    db()->execute("COMMIT");

    successResponse([
        'message' => 'フィードバックを送信しました！担当者が確認次第、返信いたします。',
        'feedback_id' => $feedbackId
    ]);

} catch (Exception $e) {
    // ロールバック
    db()->execute("ROLLBACK");
    error_log("Feedback submission error: " . $e->getMessage());
    errorResponse('フィードバックの送信に失敗しました', 500);
}
