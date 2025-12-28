<?php
/**
 * アカウント削除API
 * GDPR準拠：ユーザーの全データを完全削除
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/functions.php';

// エラー表示を無効化（JSONレスポンスを保証）
ini_set('display_errors', '0');

// グローバル例外ハンドラー
set_exception_handler(function($e) {
    error_log('Delete account error: ' . $e->getMessage());
    header('Content-Type: application/json');
    echo json_encode([
        'success' => false,
        'message' => 'アカウント削除中にエラーが発生しました。'
    ]);
    exit;
});

// JSONレスポンスヘッダー
header('Content-Type: application/json');

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'ログインしてください。'
    ]);
    exit;
}

$userId = $_SESSION['user_id'];

// POSTリクエストのみ受付
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => '不正なリクエストです。'
    ]);
    exit;
}

// JSON入力を取得
$input = json_decode(file_get_contents('php://input'), true);

// CSRF対策
if (!verifyCsrfToken($input['csrf_token'] ?? '')) {
    echo json_encode([
        'success' => false,
        'message' => '不正なリクエストです。'
    ]);
    exit;
}

// 確認テキストのチェック
if (($input['confirmation'] ?? '') !== 'DELETE') {
    echo json_encode([
        'success' => false,
        'message' => '確認テキストが正しくありません。'
    ]);
    exit;
}

try {
    // トランザクション開始
    db()->beginTransaction();

    // 1. user_progress（学習進捗）を削除
    $deleteSql = "DELETE FROM user_progress WHERE user_id = ?";
    db()->execute($deleteSql, [$userId]);

    // 2. user_survey_responses（アンケート回答）を削除
    $deleteSql = "DELETE FROM user_survey_responses WHERE user_id = ?";
    db()->execute($deleteSql, [$userId]);

    // 3. user_streaks（ストリーク）を削除
    $deleteSql = "DELETE FROM user_streaks WHERE user_id = ?";
    db()->execute($deleteSql, [$userId]);

    // 4. gamification_badges（バッジ）を削除
    $deleteSql = "DELETE FROM gamification_badges WHERE user_id = ?";
    db()->execute($deleteSql, [$userId]);

    // 5. user_feedbacks（フィードバック）を削除
    $deleteSql = "DELETE FROM user_feedbacks WHERE user_id = ?";
    db()->execute($deleteSql, [$userId]);

    // 6. users（ユーザー本体）を削除
    $deleteSql = "DELETE FROM users WHERE id = ?";
    db()->execute($deleteSql, [$userId]);

    // コミット
    db()->commit();

    // セッション破棄
    session_destroy();

    echo json_encode([
        'success' => true,
        'message' => 'アカウントを削除しました。ご利用ありがとうございました。'
    ]);

} catch (Exception $e) {
    // ロールバック
    db()->rollback();

    error_log('Delete account error: ' . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'アカウント削除中にエラーが発生しました。時間をおいて再度お試しください。'
    ]);
}
