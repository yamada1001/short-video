<?php
/**
 * BNI Slide System - Profile Update API
 * ユーザープロフィール更新処理
 */

header('Content-Type: application/json; charset=utf-8');

// Load session auth
require_once __DIR__ . '/includes/session_auth.php';

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

    $currentUsername = $_SESSION['user_email'];

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

    // Validate required fields
    if (empty($lastName) || empty($firstName) || empty($lastNameKana) || empty($firstNameKana) ||
        empty($email) || empty($company) || empty($category)) {
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

    // Check if user exists
    if (!isset($data['users'][$currentUsername])) {
        throw new Exception('ユーザー情報が見つかりません');
    }

    $oldName = $data['users'][$currentUsername]['name'];
    $oldEmail = $data['users'][$currentUsername]['email'];

    // Check if email is already used by another user
    if ($email !== $oldEmail) {
        foreach ($data['users'] as $username => $user) {
            if ($username !== $currentUsername && $user['email'] === $email) {
                throw new Exception('このメールアドレスは既に使用されています');
            }
        }
    }

    // Check if name is already used by another user
    if ($name !== $oldName) {
        foreach ($data['users'] as $username => $user) {
            if ($username !== $currentUsername && $user['name'] === $name) {
                throw new Exception('この名前は既に使用されています');
            }
        }
    }

    // Update user data
    $data['users'][$currentUsername]['name'] = $name;
    $data['users'][$currentUsername]['last_name'] = $lastName;
    $data['users'][$currentUsername]['first_name'] = $firstName;
    $data['users'][$currentUsername]['last_name_kana'] = $lastNameKana;
    $data['users'][$currentUsername]['first_name_kana'] = $firstNameKana;
    $data['users'][$currentUsername]['name_kana'] = $nameKana;
    $data['users'][$currentUsername]['email'] = $email;
    $data['users'][$currentUsername]['phone'] = $phone;
    $data['users'][$currentUsername]['company'] = $company;
    $data['users'][$currentUsername]['category'] = $category;
    $data['users'][$currentUsername]['updated_at'] = date('Y-m-d H:i:s');

    // Update members list if name changed
    if ($name !== $oldName) {
        $memberIndex = array_search($oldName, $data['members']);
        if ($memberIndex !== false) {
            $data['members'][$memberIndex] = $name;
        }
    }

    // Update timestamp
    $data['updated_at'] = date('Y-m-d');

    // Save updated members.json
    $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents($membersFile, $jsonContent) === false) {
        throw new Exception('プロフィール情報の保存に失敗しました');
    }

    // Response
    $message = 'プロフィールを更新しました！';

    echo json_encode([
        'success' => true,
        'message' => $message
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
