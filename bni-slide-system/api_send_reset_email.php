<?php
/**
 * BNI Slide System - Send Password Reset Email API (SQLite Version)
 * パスワードリセットメールを送信
 * Updated: 2025-12-06 - SQLite対応版
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

// POSTメソッドのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'POSTメソッドのみ許可されています']);
    exit;
}

// CSRF protection
requireCSRFToken();

// メールアドレスを取得
$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'メールアドレスを入力してください']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => '有効なメールアドレスを入力してください']);
    exit;
}

try {
    $db = getDbConnection();

    // メールアドレスが登録されているか確認
    $user = dbQuery($db, "SELECT id, name, email FROM users WHERE email = :email", [':email' => $email]);

    if (empty($user)) {
        dbClose($db);
        // セキュリティ上、メールアドレスが登録されていない場合でも同じメッセージを返す
        echo json_encode([
            'success' => true,
            'message' => 'リセットメールを送信しました。メールをご確認ください。'
        ]);
        exit;
    }

    $user = $user[0];

    // リセットトークンを生成（32文字のランダム文字列）
    $token = bin2hex(random_bytes(16));

    // トークンの有効期限を設定（24時間後）
    $expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

    // トークンをデータベースに保存（reset_tokenとreset_token_expiresカラムが必要）
    $updateQuery = "
        UPDATE users SET
            reset_token = :token,
            reset_token_expires = :expires,
            updated_at = datetime('now')
        WHERE email = :email
    ";

    $result = dbExecute($db, $updateQuery, [
        ':token' => $token,
        ':expires' => $expiresAt,
        ':email' => $email
    ]);

    dbClose($db);

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'トークンの保存に失敗しました']);
        exit;
    }

    // リセットURLを生成
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    // HTTPヘッダーインジェクション対策: HTTP_HOSTを検証
    $allowedHosts = ['yojitu.com', 'www.yojitu.com', 'localhost'];
    $host = $_SERVER['HTTP_HOST'] ?? '';
    if (!in_array($host, $allowedHosts, true)) {
        $host = 'yojitu.com'; // デフォルトホスト
    }

    $resetUrl = $protocol . '://' . $host . '/bni-slide-system/reset-password.php?token=' . $token;

    // メール本文を作成
    $subject = 'BNI Slide System - パスワードリセットのご案内';
    $message = "
BNI Slide System

{$user['name']} 様

パスワードリセットのリクエストを受け付けました。

以下のリンクをクリックして、新しいパスワードを設定してください：

{$resetUrl}

このリンクは24時間有効です。
有効期限: {$expiresAt}

※このメールに心当たりがない場合は、このメールを無視してください。

---
BNI Slide System
";

    // メールヘッダー（Xserver対応：実在するメールアドレスを使用）
    $fromEmail = 'yamada@yojitu.com'; // 実在するメールアドレス
    $headers = "From: BNI Slide System <{$fromEmail}>\r\n";
    $headers .= "Reply-To: {$fromEmail}\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    // デバッグログ
    error_log('[PASSWORD RESET] Sending email to: ' . $email);
    error_log('[PASSWORD RESET] Token: ' . $token);
    error_log('[PASSWORD RESET] Reset URL: ' . $resetUrl);

    // メールを送信
    $mailSent = @mail($email, $subject, $message, $headers);

    // エラーログに結果を記録
    if ($mailSent) {
        error_log('[PASSWORD RESET] Mail sent successfully to: ' . $email);
    } else {
        $lastError = error_get_last();
        error_log('[PASSWORD RESET] Mail failed to: ' . $email);
        error_log('[PASSWORD RESET] Last error: ' . print_r($lastError, true));
    }

    if ($mailSent) {
        echo json_encode([
            'success' => true,
            'message' => 'リセットメールを送信しました。メールをご確認ください。'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'メールの送信に失敗しました。管理者にお問い合わせください。'
        ]);
    }

} catch (Exception $e) {
    error_log('[API SEND RESET EMAIL] Error: ' . $e->getMessage());
    if (isset($db)) {
        dbClose($db);
    }

    echo json_encode([
        'success' => false,
        'message' => 'パスワードリセットメールの送信中にエラーが発生しました'
    ]);
}
?>
