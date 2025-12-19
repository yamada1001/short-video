<?php
/**
 * Gemini AI学習プラットフォーム - 共通関数
 *
 * アプリケーション全体で使用される共通関数
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/**
 * ユーザー情報を取得
 */
function getCurrentUser() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $sql = "SELECT * FROM users WHERE id = ?";
    return db()->fetchOne($sql, [$_SESSION['user_id']]);
}

/**
 * ログインチェック
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        redirect(APP_URL . '/login.php');
    }
}

/**
 * 管理者チェック
 */
function requireAdmin() {
    requireLogin();
    $user = getCurrentUser();
    if ($user['email'] !== 'admin@example.com') { // 仮実装
        redirect(APP_URL . '/dashboard.php');
    }
}

/**
 * サブスクリプションチェック
 */
function hasActiveSubscription() {
    $user = getCurrentUser();
    if (!$user) return false;

    return $user['subscription_status'] === 'active';
}

/**
 * コースアクセス権チェック
 */
function canAccessCourse($courseId) {
    // コース情報を取得
    $sql = "SELECT is_free FROM courses WHERE id = ?";
    $course = db()->fetchOne($sql, [$courseId]);

    if (!$course) return false;

    // 無料コースはログインユーザー全員アクセス可
    if ($course['is_free']) return true;

    // 有料コースは有料会員のみ
    return hasActiveSubscription();
}

/**
 * 1日のAPI使用回数チェック
 */
function checkApiLimit() {
    $user = getCurrentUser();
    if (!$user) return false;

    // 今日の使用回数を取得
    $sql = "SELECT COUNT(*) as count FROM api_usage
            WHERE user_id = ? AND DATE(created_at) = CURDATE()";
    $result = db()->fetchOne($sql, [$user['id']]);
    $todayCount = $result['count'] ?? 0;

    // 制限値を取得
    $limit = hasActiveSubscription() ? API_LIMIT_PREMIUM : API_LIMIT_FREE;

    return $todayCount < $limit;
}

/**
 * API使用量を記録
 */
function logApiUsage($endpoint, $tokensUsed, $costUsd) {
    $user = getCurrentUser();
    if (!$user) return false;

    $sql = "INSERT INTO api_usage (user_id, endpoint, tokens_used, cost_usd)
            VALUES (?, ?, ?, ?)";
    return db()->execute($sql, [$user['id'], $endpoint, $tokensUsed, $costUsd]);
}

/**
 * プロンプトキャッシュを取得
 */
function getCachedPrompt($promptText, $model) {
    $hash = hash('sha256', $promptText . $model);

    $sql = "SELECT response_text FROM prompt_cache
            WHERE prompt_hash = ? AND model = ?";
    $result = db()->fetchOne($sql, [$hash, $model]);

    if ($result) {
        // ヒットカウントと最終使用日を更新
        $updateSql = "UPDATE prompt_cache
                     SET hit_count = hit_count + 1, last_used_at = NOW()
                     WHERE prompt_hash = ?";
        db()->execute($updateSql, [$hash]);

        return $result['response_text'];
    }

    return null;
}

/**
 * プロンプトキャッシュを保存
 */
function saveCachedPrompt($promptText, $responseText, $model) {
    $hash = hash('sha256', $promptText . $model);

    $sql = "INSERT INTO prompt_cache (prompt_hash, prompt_text, response_text, model)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            response_text = VALUES(response_text),
            last_used_at = NOW()";

    return db()->execute($sql, [$hash, $promptText, $responseText, $model]);
}

/**
 * 進捗を更新
 */
function updateProgress($lessonId, $status = 'completed') {
    $user = getCurrentUser();
    if (!$user) return false;

    $sql = "INSERT INTO user_progress (user_id, lesson_id, status, completed_at)
            VALUES (?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            status = VALUES(status),
            completed_at = VALUES(completed_at)";

    $completedAt = ($status === 'completed') ? date('Y-m-d H:i:s') : null;

    return db()->execute($sql, [$user['id'], $lessonId, $status, $completedAt]);
}

/**
 * コースの進捗率を取得
 */
function getCourseProgress($courseId) {
    $user = getCurrentUser();
    if (!$user) return 0;

    // コースの総レッスン数を取得
    $totalSql = "SELECT COUNT(*) as total FROM lessons WHERE course_id = ?";
    $totalResult = db()->fetchOne($totalSql, [$courseId]);
    $total = $totalResult['total'] ?? 0;

    if ($total === 0) return 0;

    // 完了したレッスン数を取得
    $completedSql = "SELECT COUNT(*) as completed FROM user_progress up
                     JOIN lessons l ON up.lesson_id = l.id
                     WHERE l.course_id = ? AND up.user_id = ? AND up.status = 'completed'";
    $completedResult = db()->fetchOne($completedSql, [$courseId, $user['id']]);
    $completed = $completedResult['completed'] ?? 0;

    return round(($completed / $total) * 100);
}

/**
 * メール送信（PHPMailer）
 */
function sendEmail($to, $subject, $body) {
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // SMTP設定
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;

        // 送信者・受信者設定
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        $mail->addAddress($to);

        // 文字コード設定
        $mail->CharSet = 'UTF-8';

        // メール内容
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mail error: ' . $mail->ErrorInfo);
        return false;
    }
}

/**
 * パスワード再発行トークン生成
 */
function generatePasswordResetToken($userId) {
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $sql = "INSERT INTO password_reset_tokens (user_id, token, expires_at)
            VALUES (?, ?, ?)";

    if (db()->execute($sql, [$userId, $token, $expiresAt])) {
        return $token;
    }

    return false;
}

/**
 * パスワード再発行トークン検証
 */
function verifyPasswordResetToken($token) {
    $sql = "SELECT * FROM password_reset_tokens
            WHERE token = ? AND used = 0 AND expires_at > NOW()";

    return db()->fetchOne($sql, [$token]);
}

/**
 * パスワード再発行トークンを使用済みにする
 */
function markTokenAsUsed($token) {
    $sql = "UPDATE password_reset_tokens SET used = 1 WHERE token = ?";
    return db()->execute($sql, [$token]);
}
