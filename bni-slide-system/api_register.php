<?php
/**
 * BNI Slide System - User Registration API (SQLite Version)
 * 新規ユーザー登録処理
 * Updated: 2025-12-06 - SQLite対応版
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/db.php';

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
    $lastName = trim($_POST['last_name'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    $lastNameKana = trim($_POST['last_name_kana'] ?? '');
    $firstNameKana = trim($_POST['first_name_kana'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $category = trim($_POST['category'] ?? '');

    // Combine last name and first name
    $name = $lastName . $firstName;
    $nameKana = $lastNameKana . $firstNameKana;

    // Auto-generate password (10 characters: alphanumeric)
    $password = generateRandomPassword(10);

    // Validate required fields
    if (empty($lastName) || empty($firstName) || empty($lastNameKana) || empty($firstNameKana) ||
        empty($email) || empty($company) || empty($category)) {
        throw new Exception('必須項目が入力されていません');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('メールアドレスの形式が正しくありません');
    }

    $db = getDbConnection();

    // Check if email already exists
    $existingUser = dbQuery($db, "SELECT id FROM users WHERE email = :email", [':email' => $email]);
    if (!empty($existingUser)) {
        dbClose($db);
        throw new Exception('このメールアドレスは既に登録されています');
    }

    // Check if name already exists
    $existingName = dbQuery($db, "SELECT id FROM users WHERE name = :name", [':name' => $name]);
    if (!empty($existingName)) {
        dbClose($db);
        throw new Exception('この名前は既に登録されています');
    }

    // Generate password hash using PHP's password_hash()
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    if (!$passwordHash) {
        dbClose($db);
        throw new Exception('パスワードのハッシュ化に失敗しました');
    }

    // Insert new user into database
    $insertQuery = "
        INSERT INTO users (
            email, name, phone, company, category,
            password_hash, is_active, role,
            created_at, updated_at
        ) VALUES (
            :email, :name, :phone, :company, :category,
            :password_hash, 1, 'member',
            datetime('now'), datetime('now')
        )
    ";

    $params = [
        ':email' => $email,
        ':name' => $name,
        ':phone' => $phone,
        ':company' => $company,
        ':category' => $category,
        ':password_hash' => $passwordHash
    ];

    $result = dbExecute($db, $insertQuery, $params);

    if (!$result) {
        dbClose($db);
        throw new Exception('ユーザー情報の保存に失敗しました');
    }

    dbClose($db);

    // Send welcome email with password
    sendWelcomeEmail($name, $email, $email, $password);

    // Response
    echo json_encode([
        'success' => true,
        'message' => "登録が完了しました！ログイン情報をメールで送信しました。",
        'username' => $email
    ]);

} catch (Exception $e) {
    if (isset($db)) {
        dbClose($db);
    }

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
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
?>
