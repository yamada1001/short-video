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
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $newPassword = $_POST['new_password'] ?? '';

    // Validate required fields
    if (empty($name) || empty($email) || empty($company) || empty($category)) {
        throw new Exception('必須項目が入力されていません');
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('メールアドレスの形式が正しくありません');
    }

    // Validate password length (if changing)
    if (!empty($newPassword) && strlen($newPassword) < 6) {
        throw new Exception('パスワードは6文字以上で設定してください');
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

    // Update password in .htpasswd if provided
    if (!empty($newPassword)) {
        $htpasswdHash = generateHtpasswdHash($currentUsername, $newPassword);
        if (!$htpasswdHash) {
            throw new Exception('パスワードのハッシュ化に失敗しました');
        }

        // Read .htpasswd
        $htpasswdFile = __DIR__ . '/.htpasswd';
        $htpasswdContent = file_get_contents($htpasswdFile);
        if ($htpasswdContent === false) {
            throw new Exception('認証ファイルの読み込みに失敗しました');
        }

        // Update password hash
        $lines = explode("\n", $htpasswdContent);
        $updated = false;
        foreach ($lines as &$line) {
            if (strpos($line, $currentUsername . ':') === 0) {
                $line = "$currentUsername:$htpasswdHash";
                $updated = true;
                break;
            }
        }

        if (!$updated) {
            throw new Exception('認証情報の更新に失敗しました');
        }

        // Save updated .htpasswd
        $newHtpasswdContent = implode("\n", $lines);
        if (file_put_contents($htpasswdFile, $newHtpasswdContent, LOCK_EX) === false) {
            throw new Exception('認証情報の保存に失敗しました');
        }
    }

    // Response
    $message = 'プロフィールを更新しました！';
    if (!empty($newPassword)) {
        $message .= '次回ログイン時から新しいパスワードが有効になります。';
    }

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
