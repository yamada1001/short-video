<?php
/**
 * BNI Slide System - Send Password Reset Email API
 * パスワードリセットメールを送信
 */

header('Content-Type: application/json; charset=utf-8');

// POSTメソッドのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'POSTメソッドのみ許可されています']);
    exit;
}

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

// メンバーデータを読み込み
$membersFile = __DIR__ . '/data/members.json';

if (!file_exists($membersFile)) {
    echo json_encode(['success' => false, 'message' => 'ユーザーデータが見つかりません']);
    exit;
}

$content = file_get_contents($membersFile);
$data = json_decode($content, true);

if (!$data || !isset($data['users'])) {
    echo json_encode(['success' => false, 'message' => 'ユーザーデータの読み込みに失敗しました']);
    exit;
}

// メールアドレスが登録されているか確認
$userFound = false;
$username = '';

foreach ($data['users'] as $uname => $user) {
    if ($user['email'] === $email) {
        $userFound = true;
        $username = $uname;
        break;
    }
}

if (!$userFound) {
    // セキュリティ上、メールアドレスが登録されていない場合でも同じメッセージを返す
    echo json_encode([
        'success' => true,
        'message' => 'リセットメールを送信しました。メールをご確認ください。'
    ]);
    exit;
}

// リセットトークンを生成（32文字のランダム文字列）
$token = bin2hex(random_bytes(16));

// トークンの有効期限を設定（24時間後）
$expiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

// トークンをユーザーデータに保存
$data['users'][$username]['reset_token'] = $token;
$data['users'][$username]['reset_token_expires'] = $expiresAt;
$data['users'][$username]['updated_at'] = date('Y-m-d H:i:s');

// データを保存
$jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
if (file_put_contents($membersFile, $jsonContent) === false) {
    echo json_encode(['success' => false, 'message' => 'トークンの保存に失敗しました']);
    exit;
}

// リセットURLを生成
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$resetUrl = $protocol . '://' . $host . '/bni-slide-system/reset-password.php?token=' . $token;

// メール本文を作成
$subject = 'BNI Slide System - パスワードリセットのご案内';
$message = "
BNI Slide System

{$data['users'][$username]['name']} 様

パスワードリセットのリクエストを受け付けました。

以下のリンクをクリックして、新しいパスワードを設定してください：

{$resetUrl}

このリンクは24時間有効です。
有効期限: {$expiresAt}

※このメールに心当たりがない場合は、このメールを無視してください。

---
BNI Slide System
";

// メールヘッダー
$headers = "From: noreply@yojitu.com\r\n";
$headers .= "Reply-To: noreply@yojitu.com\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// メールを送信
$mailSent = mail($email, $subject, $message, $headers);

if ($mailSent) {
    echo json_encode([
        'success' => true,
        'message' => 'リセットメールを送信しました。メールをご確認ください。'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'メールの送信に失敗しました。しばらくしてから再度お試しください。'
    ]);
}
