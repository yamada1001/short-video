<?php
/**
 * Debug: Check if user exists in members.json and .htpasswd
 */

header('Content-Type: text/plain; charset=utf-8');

$email = 'yamada@yojitu.com';

echo "=== ユーザー登録確認デバッグ ===\n\n";
echo "確認対象: {$email}\n\n";

// Check members.json
echo "--- data/members.json の確認 ---\n";
$membersFile = __DIR__ . '/data/members.json';
if (file_exists($membersFile)) {
    $content = file_get_contents($membersFile);
    $data = json_decode($content, true);

    if (isset($data['users'][$email])) {
        echo "✓ ユーザーが存在します\n";
        echo "登録情報:\n";
        print_r($data['users'][$email]);
    } else {
        echo "✗ ユーザーが見つかりません\n";
        echo "登録されているユーザー一覧:\n";
        foreach (array_keys($data['users']) as $userEmail) {
            echo "  - {$userEmail}\n";
        }
    }
} else {
    echo "✗ members.json が見つかりません\n";
}

echo "\n--- .htpasswd の確認 ---\n";
$htpasswdFile = __DIR__ . '/.htpasswd';
if (file_exists($htpasswdFile)) {
    $htpasswdContent = file_get_contents($htpasswdFile);
    $lines = explode("\n", $htpasswdContent);

    $found = false;
    foreach ($lines as $line) {
        if (strpos($line, $email . ':') === 0) {
            echo "✓ .htpasswd にエントリが存在します\n";
            echo "エントリ: " . substr($line, 0, 50) . "...\n";
            $found = true;
            break;
        }
    }

    if (!$found) {
        echo "✗ .htpasswd にエントリが見つかりません\n";
        echo "登録されているユーザー:\n";
        foreach ($lines as $line) {
            if (trim($line)) {
                $parts = explode(':', $line);
                echo "  - {$parts[0]}\n";
            }
        }
    }
} else {
    echo "✗ .htpasswd が見つかりません\n";
}

echo "\n--- ログイン認証テスト ---\n";
require_once __DIR__ . '/includes/session_auth.php';

$password = 'WSiMlJcIqw';
$result = loginUser($email, $password);

if ($result['success']) {
    echo "✓ ログイン認証成功\n";
} else {
    echo "✗ ログイン認証失敗\n";
    echo "エラー: {$result['message']}\n";
}

echo "\n=== デバッグ終了 ===\n";
