<?php
/**
 * Create user account for 矢野義隆
 * 本番環境で実行してください
 */

require_once __DIR__ . '/includes/db.php';

echo "=== 矢野義隆さんのユーザー作成 ===\n\n";

$email = 'sousakuyasola3@gmail.com';
$name = '矢野義隆';
$password = '~F/=rZ!-nAy5';
$role = 'admin';

try {
    $db = getDbConnection();

    // Check if user already exists
    echo "1. ユーザーの存在確認...\n";
    $existingUser = dbQueryOne($db, "SELECT id, email, name FROM users WHERE email = :email", [':email' => $email]);

    if ($existingUser) {
        echo "   ⚠️  ユーザーは既に存在します\n";
        echo "   ID: " . $existingUser['id'] . "\n";
        echo "   Name: " . $existingUser['name'] . "\n";
        echo "   Email: " . $existingUser['email'] . "\n\n";

        // Update password for existing user
        echo "2. パスワードを更新します...\n";
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "UPDATE users SET password_hash = :password_hash WHERE email = :email";
        dbExecute($db, $query, [
            ':password_hash' => $passwordHash,
            ':email' => $email
        ]);

        echo "   ✅ パスワード更新完了\n\n";

    } else {
        echo "   ℹ️  ユーザーが見つかりません。新規作成します\n\n";

        // Create new user
        echo "2. 新規ユーザーを作成します...\n";
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (email, name, password_hash, role, is_active, created_at)
                  VALUES (:email, :name, :password_hash, :role, 1, datetime('now', 'localtime'))";

        dbExecute($db, $query, [
            ':email' => $email,
            ':name' => $name,
            ':password_hash' => $passwordHash,
            ':role' => $role
        ]);

        echo "   ✅ ユーザー作成完了\n\n";
    }

    // Verify the user
    echo "3. 作成/更新されたユーザーを確認...\n";
    $user = dbQueryOne($db, "SELECT id, email, name, role, is_active, password_hash FROM users WHERE email = :email", [':email' => $email]);

    if (!$user) {
        echo "   ❌ エラー: ユーザーが見つかりません\n";
        dbClose($db);
        exit;
    }

    echo "   ID: " . $user['id'] . "\n";
    echo "   Name: " . $user['name'] . "\n";
    echo "   Email: " . $user['email'] . "\n";
    echo "   Role: " . $user['role'] . "\n";
    echo "   Active: " . ($user['is_active'] ? 'Yes' : 'No') . "\n";
    echo "   Password Hash: " . substr($user['password_hash'], 0, 20) . "...\n\n";

    // Test password verification
    echo "4. パスワード認証テスト...\n";
    if (password_verify($password, $user['password_hash'])) {
        echo "   ✅ 認証成功！ログインできます！\n\n";
    } else {
        echo "   ❌ 認証失敗\n\n";
    }

    dbClose($db);

    echo "=== 完了 ===\n";
    echo "\nログイン情報:\n";
    echo "URL: https://yojitu.com/bni-slide-system/login.php\n";
    echo "Email: " . $email . "\n";
    echo "Password: " . $password . "\n";

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
    if (isset($db)) {
        dbClose($db);
    }
}
