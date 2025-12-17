<?php
/**
 * システム開発見積もり依頼 API
 * system-development.phpページからのフォーム送信を処理
 */

require_once __DIR__ . '/includes/functions.php';

header('Content-Type: application/json; charset=UTF-8');

// POST送信のみ受付
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method Not Allowed'
    ]);
    exit;
}

// JSONデータ取得
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => '不正なリクエストです。'
    ]);
    exit;
}

// 入力データ取得
$email = isset($data['email']) ? trim($data['email']) : '';
$message = isset($data['message']) ? trim($data['message']) : '';

// バリデーション
$errors = [];

if (empty($email)) {
    $errors[] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '正しいメールアドレスを入力してください。';
}

if (empty($message)) {
    $errors[] = 'やりたいことを入力してください。';
}

// エラーがある場合
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => implode(' ', $errors)
    ]);
    exit;
}

// メール送信の設定
mb_language("uni");
mb_internal_encoding("UTF-8");

// 管理者へのメール
$to = ADMIN_EMAIL;
$mail_subject = '【システム開発見積依頼】';

$mail_body = "システム開発の見積もり依頼がありました。\n\n";
$mail_body .= "■ メールアドレス\n" . $email . "\n\n";
$mail_body .= "■ やりたいこと\n" . $message . "\n\n";
$mail_body .= "────────────────────\n\n";
$mail_body .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n";
$mail_body .= "送信元: system-development.php\n";
$mail_body .= "IPアドレス: " . $_SERVER['REMOTE_ADDR'] . "\n";

$headers = "From: " . ADMIN_EMAIL . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// 管理者へ送信
$admin_result = mb_send_mail($to, $mail_subject, $mail_body, $headers);

// メール送信失敗時のログ記録
if (!$admin_result) {
    error_log("System Inquiry API: Failed to send admin email to " . $to);
    error_log("Email: " . $email);
    error_log("Message: " . $message);

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'メール送信に失敗しました。時間をおいて再度お試しください。'
    ]);
    exit;
}

// 成功レスポンス
http_response_code(200);
echo json_encode([
    'success' => true,
    'message' => '送信完了しました。ご連絡をお待ちください。'
]);
