<?php
/**
 * Test login for 矢野義隆
 */

require_once __DIR__ . '/includes/db.php';

$email = 'sousakuyasola3@gmail.com';
$password = '~F/=rZ!-nAy5';

$db = getDbConnection();

$query = "SELECT id, email, name, password_hash, role, is_active FROM users WHERE email = :email";
$user = dbQueryOne($db, $query, [':email' => $email]);

if (!$user) {
    echo "❌ ユーザーが見つかりません\n";
    exit;
}

echo "✅ ユーザーが見つかりました\n";
echo "ID: " . $user['id'] . "\n";
echo "Name: " . $user['name'] . "\n";
echo "Email: " . $user['email'] . "\n";
echo "Role: " . $user['role'] . "\n";
echo "Active: " . $user['is_active'] . "\n";
echo "Password Hash: " . $user['password_hash'] . "\n\n";

// Test password verification
if (password_verify($password, $user['password_hash'])) {
    echo "✅ パスワード認証成功！\n";
} else {
    echo "❌ パスワード認証失敗！\n";
    echo "入力パスワード: " . $password . "\n";

    // Try creating new hash
    $newHash = password_hash($password, PASSWORD_DEFAULT);
    echo "\n新しいハッシュ: " . $newHash . "\n";

    if (password_verify($password, $newHash)) {
        echo "✅ 新しいハッシュでは認証成功\n";
    }
}

dbClose($db);
