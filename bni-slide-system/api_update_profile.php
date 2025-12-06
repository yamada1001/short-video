<?php
/**
 * BNI Slide System - Profile Update API (SQLite Version)
 * ユーザープロフィール更新処理
 * Updated: 2025-12-06 - SQLite対応版
 */

header('Content-Type: application/json; charset=utf-8');

// Load session auth and DB
require_once __DIR__ . '/includes/session_auth.php';
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
    // Get current logged-in user from session
    if (!isLoggedIn()) {
        throw new Exception('ログインしていません');
    }

    $currentUserEmail = $_SESSION['user_email'];

    // Get form data
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $category = trim($_POST['category'] ?? '');

    // Validate required fields
    if (empty($email) || empty($name) || empty($company) || empty($category)) {
        throw new Exception('必須項目が入力されていません');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('メールアドレスの形式が正しくありません');
    }

    $db = getDbConnection();

    // Get current user info
    $currentUser = dbQuery($db, "SELECT * FROM users WHERE email = :email", [':email' => $currentUserEmail]);
    if (empty($currentUser)) {
        dbClose($db);
        throw new Exception('ユーザー情報が見つかりません');
    }

    $oldName = $currentUser[0]['name'];
    $oldEmail = $currentUser[0]['email'];

    // Check if email is already used by another user
    if ($email !== $oldEmail) {
        $existingEmail = dbQuery($db, "SELECT id FROM users WHERE email = :email AND email != :old_email", [
            ':email' => $email,
            ':old_email' => $oldEmail
        ]);

        if (!empty($existingEmail)) {
            dbClose($db);
            throw new Exception('このメールアドレスは既に使用されています');
        }
    }

    // Check if name is already used by another user
    if ($name !== $oldName) {
        $existingName = dbQuery($db, "SELECT id FROM users WHERE name = :name AND email != :old_email", [
            ':name' => $name,
            ':old_email' => $oldEmail
        ]);

        if (!empty($existingName)) {
            dbClose($db);
            throw new Exception('この名前は既に使用されています');
        }
    }

    // Update user data
    $updateQuery = "
        UPDATE users SET
            email = :email,
            name = :name,
            phone = :phone,
            company = :company,
            category = :category,
            updated_at = datetime('now')
        WHERE email = :old_email
    ";

    $params = [
        ':email' => $email,
        ':name' => $name,
        ':phone' => $phone,
        ':company' => $company,
        ':category' => $category,
        ':old_email' => $oldEmail
    ];

    $result = dbExecute($db, $updateQuery, $params);

    if (!$result) {
        dbClose($db);
        throw new Exception('プロフィール情報の保存に失敗しました');
    }

    // Update session email if changed
    if ($email !== $oldEmail) {
        $_SESSION['user_email'] = $email;
    }

    dbClose($db);

    // Response
    echo json_encode([
        'success' => true,
        'message' => 'プロフィールを更新しました！'
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
?>
