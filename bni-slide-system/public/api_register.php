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
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($username) || empty($password)) {
        throw new Exception('必須項目が入力されていません');
    }

    // Validate username format (alphanumeric, hyphen, underscore only)
    if (!preg_match('/^[a-zA-Z0-9_-]{3,}$/', $username)) {
        throw new Exception('ユーザー名は半角英数字、ハイフン、アンダースコアのみ使用可能です（3文字以上）');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('メールアドレスの形式が正しくありません');
    }

    // Validate password length
    if (strlen($password) < 6) {
        throw new Exception('パスワードは6文字以上で設定してください');
    }

    // Load members.json
    $membersFile = __DIR__ . '/../data/members.json';
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

    // Check if username already exists
    if (isset($data['users'][$username])) {
        throw new Exception('このユーザー名は既に使用されています');
    }

    // Check if email already exists
    foreach ($data['users'] as $user) {
        if ($user['email'] === $email) {
            throw new Exception('このメールアドレスは既に登録されています');
        }
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

    // Add new user to data
    $data['users'][$username] = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'htpasswd_user' => $username,
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
    $htpasswdFile = __DIR__ . '/../.htpasswd';
    $htpasswdEntry = "$username:$htpasswdHash\n";

    if (file_put_contents($htpasswdFile, $htpasswdEntry, FILE_APPEND | LOCK_EX) === false) {
        // Rollback: remove user from members.json
        unset($data['users'][$username]);
        $data['members'] = array_values(array_diff($data['members'], [$name]));
        file_put_contents($membersFile, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        throw new Exception('認証情報の保存に失敗しました');
    }

    // Send welcome email (optional)
    sendWelcomeEmail($name, $email, $username);

    // Response
    echo json_encode([
        'success' => true,
        'message' => "登録が完了しました！ユーザー名「{$username}」でログインできます。",
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
 * Send welcome email to new user
 */
function sendWelcomeEmail($name, $email, $username) {
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
        <p><strong>ユーザー名:</strong> ' . htmlspecialchars($username) . '</p>
        <p><strong>URL:</strong> <a href="https://yojitu.com/bni-slide-system/">https://yojitu.com/bni-slide-system/</a></p>
      </div>

      <p>設定したパスワードでログインし、週次アンケートにご回答ください。</p>
      <p>プロフィール情報（メールアドレス・電話番号）はログイン後に変更できます。</p>

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
