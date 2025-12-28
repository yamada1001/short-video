<?php
/**
 * 自分のフィードバック履歴取得API
 *
 * ログインユーザーが送信したフィードバック一覧を取得
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

// GETのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    errorResponse('Method not allowed', 405);
}

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    errorResponse('ログインが必要です', 401);
}

$user = getCurrentUser();

try {
    // フィードバック一覧を取得（新しい順）
    $sql = "SELECT
                uf.id,
                uf.lesson_id,
                l.title as lesson_title,
                c.title as course_title,
                uf.feedback_type,
                uf.message,
                uf.reply_message,
                uf.status,
                uf.created_at,
                uf.replied_at
            FROM user_feedback uf
            LEFT JOIN lessons l ON uf.lesson_id = l.id
            LEFT JOIN courses c ON l.course_id = c.id
            WHERE uf.user_id = ?
            ORDER BY uf.created_at DESC";

    $feedbacks = db()->fetchAll($sql, [$user['id']]);

    // フィードバックタイプのラベル変換
    $typeLabels = [
        'question' => '質問',
        'bug' => 'バグ報告',
        'request' => '要望',
        'other' => 'その他'
    ];

    // ステータスラベル変換
    $statusLabels = [
        'pending' => '未対応',
        'in_progress' => '対応中',
        'completed' => '完了'
    ];

    // ラベルを追加
    foreach ($feedbacks as &$feedback) {
        $feedback['type_label'] = $typeLabels[$feedback['feedback_type']] ?? $feedback['feedback_type'];
        $feedback['status_label'] = $statusLabels[$feedback['status']] ?? $feedback['status'];
    }

    successResponse([
        'feedbacks' => $feedbacks,
        'total_count' => count($feedbacks)
    ]);

} catch (Exception $e) {
    error_log("Feedback fetch error: " . $e->getMessage());
    errorResponse('フィードバック履歴の取得に失敗しました', 500);
}
