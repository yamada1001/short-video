<?php
/**
 * BNI Slide System - User Registration API
 * 新規ユーザー登録処理
 */

header('Content-Type: application/json; charset=utf-8');

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => '不正なリクエストです'
    ]);
    exit;
}

try {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Use email as username for .htpasswd
    $username = $email;

    // Auto-generate password (10 characters: alphanumeric)
    $password = generateRandomPassword(10);

    // Validate required fields
    if (empty($name) || empty($email)) {
        throw new Exception('必須項目が入力されていません');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('メールアドレスの形式が正しくありません');
    }

    // Load members.json
    $membersFile = __DIR__ . '/data/members.json';
    if (!file_exists($membersFile)) {
        throw new Exception('データファイルが見つかりません');
    }

    $content = file_get_contents($membersFile);
    if ($content === false) {
        throw new Exception('データファイルの読み込みに失敗しました');
    }

    $data = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('データファイルの形式が不正です');
    }

    // Check if email already exists (email is now the username)
    if (isset($data['users'][$email])) {
        throw new Exception('このメールアドレスは既に登録されています');
    }

    // Check if name already exists
    if (in_array($name, $data['members'])) {
        throw new Exception('この名前は既に登録されています');
    }

    // Generate password hash for .htpasswd
    $htpasswdHash = generateHtpasswdHash($username, $password);
    if (!$htpasswdHash) {
        throw new Exception('パスワードのハッシュ化に失敗しました');
    }

    // Add new user to data (using email as key)
    $data['users'][$email] = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'htpasswd_user' => $email,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    // Add name to members list
    $data['members'][] = $name;

    // Update timestamp
    $data['updated_at'] = date('Y-m-d');

    // Save updated members.json
    $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents($membersFile, $jsonContent) === false) {
        throw new Exception('ユーザー情報の保存に失敗しました');
    }

    // Update .htpasswd
    $htpasswdFile = __DIR__ . '/.htpasswd';
    $htpasswdEntry = "$username:$htpasswdHash\n";

    if (file_put_contents($htpasswdFile, $htpasswdEntry, FILE_APPEND | LOCK_EX) === false) {
        // Rollback: remove user from members.json
        unset($data['users'][$email]);
        $data['members'] = array_values(array_diff($data['members'], [$name]));
        file_put_contents($membersFile, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        throw new Exception('認証情報の保存に失敗しました');
    }

    // Send welcome email with password
    sendWelcomeEmail($name, $email, $username, $password);

    // Response
    echo json_encode([
        'success' => true,
        'message' => "登録が完了しました！ログイン情報をメールで送信しました。",
        'username' => $username
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

/**
 * Generate htpasswd hash (APR1-MD5)
 */
function generateHtpasswdHash($username, $password) {
    // Use exec to call htpasswd command
    $command = sprintf(
        'htpasswd -nbm %s %s 2>&1',
        escapeshellarg($username),
        escapeshellarg($password)
    );

    $output = shell_exec($command);

    if ($output === null) {
        return false;
    }

    // Extract hash from output (format: username:hash)
    $parts = explode(':', trim($output), 2);
    if (count($parts) !== 2) {
        return false;
    }

    return $parts[1];
}

/**
 * Generate random password
 */
function generateRandomPassword($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    $charactersLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, $charactersLength - 1)];
    }

    return $password;
}

/**
 * Send welcome email to new user
 */
function sendWelcomeEmail($name, $email, $username, $password) {
    $to = $email;
    $subject = '[BNI] アカウント登録完了のお知らせ';

    $message = '<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; line-height: 1.8; color: #333; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .header { background-color: #CF2030; color: white; padding: 30px 20px; text-align: center; }
    .content { background-color: #ffffff; padding: 30px; }
    .info-box { background-color: #f9f9f9; padding: 20px; margin: 20px 0; border-left: 4px solid #CF2030; }
    .footer { text-align: center; color: #999; font-size: 13px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>BNI Slide System へようこそ！</h2>
    </div>
    <div class="content">
      <p>' . htmlspecialchars($name) . ' 様</p>
      <p>BNI週次アンケートシステムへのご登録ありがとうございます。</p>

      <div class="info-box">
        <h3>ログイン情報</h3>
        <p><strong>URL:</strong> <a href="https://yojitu.com/bni-slide-system/">https://yojitu.com/bni-slide-system/</a></p>
        <p><strong>ログインID（メールアドレス）:</strong> ' . htmlspecialchars($email) . '</p>
        <p><strong>パスワード:</strong> <span style="font-family: monospace; background-color: #FFF3CD; padding: 4px 8px; border-radius: 4px; font-size: 16px; font-weight: bold;">' . htmlspecialchars($password) . '</span></p>
      </div>

      <p style="color: #D9534F; font-weight: bold;">⚠️ このパスワードは初回ログイン用です。セキュリティのため、ログイン後すぐに変更することをお勧めします。</p>

      <p>上記のログインID（メールアドレス）とパスワードでログインし、週次アンケートにご回答ください。</p>
      <p>プロフィール情報（メールアドレス・電話番号・パスワード）はログイン後に変更できます。</p>

      <div class="footer">
        <p>このメールは自動送信されています。</p>
        <p>BNI Slide System</p>
        <p>Givers Gain®</p>
      </div>
    </div>
  </div>
</body>
</html>';

    $headers = [
        'From: BNI Slide System <noreply@yojitu.com>',
        'Content-Type: text/html; charset=UTF-8',
        'X-Mailer: PHP/' . phpversion()
    ];

    // Send email
    @mail($to, $subject, $message, implode("\r\n", $headers));
}
