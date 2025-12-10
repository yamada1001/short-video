<?php
/**
 * Fix password for 矢野義隆
 */

require_once __DIR__ . '/includes/db.php';

$email = 'sousakuyasola3@gmail.com';
$password = '~F/=rZ!-nAy5';

echo "パスワード: " . $password . "\n";

// Generate new hash
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
echo "新しいハッシュ: " . $passwordHash . "\n";

// Verify it works
if (password_verify($password, $passwordHash)) {
    echo "✅ ハッシュ生成成功\n\n";
} else {
    echo "❌ ハッシュ生成失敗\n";
    exit;
}

// Update database
$db = getDbConnection();

$query = "UPDATE users SET password_hash = :password_hash WHERE email = :email";
$result = dbExecute($db, $query, [
    ':password_hash' => $passwordHash,
    ':email' => $email
]);

echo "データベース更新完了\n\n";

// Verify update
$user = dbQueryOne($db, "SELECT password_hash FROM users WHERE email = :email", [':email' => $email]);
echo "保存されたハッシュ: " . $user['password_hash'] . "\n";

if (password_verify($password, $user['password_hash'])) {
    echo "✅ 認証成功！ログインできます！\n";
} else {
    echo "❌ 認証失敗\n";
}

dbClose($db);
