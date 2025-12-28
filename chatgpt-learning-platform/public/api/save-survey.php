<?php
/**
 * アンケート回答保存API
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
$answers = $input['answers'] ?? null;

// バリデーション
if (!$answers || !is_array($answers)) {
    errorResponse('回答データが不正です');
}

try {
    // トランザクション開始
    db()->execute("START TRANSACTION");

    // 既存の回答を削除（編集モード対応）
    $deleteSql = "DELETE FROM user_survey_responses WHERE user_id = ?";
    db()->execute($deleteSql, [$user['id']]);

    // 新しい回答を保存
    $insertSql = "INSERT INTO user_survey_responses (user_id, question_id, answer_value) 
                 VALUES (?, ?, ?)";

    foreach ($answers as $questionId => $answerValue) {
        // 空の回答はスキップ
        if (empty($answerValue)) {
            continue;
        }

        db()->execute($insertSql, [$user['id'], $questionId, $answerValue]);
    }

    // usersテーブルのsurvey_completed_atを更新
    $updateUserSql = "UPDATE users SET survey_completed_at = NOW() WHERE id = ?";
    db()->execute($updateUserSql, [$user['id']]);

    // コミット
    db()->execute("COMMIT");

    successResponse([
        'message' => 'アンケート回答を保存しました！'
    ]);

} catch (Exception $e) {
    // ロールバック
    db()->execute("ROLLBACK");
    error_log("Survey save error: " . $e->getMessage());
    errorResponse('回答の保存に失敗しました', 500);
}
