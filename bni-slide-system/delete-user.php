<?php
/**
 * Delete specific user from members.json
 */

header('Content-Type: text/plain; charset=utf-8');

$emailToDelete = 'yamada@yojitu.com';

echo "=== ユーザー削除スクリプト ===\n\n";
echo "削除対象: {$emailToDelete}\n\n";

// Load members.json
$membersFile = __DIR__ . '/data/members.json';
if (!file_exists($membersFile)) {
    die("✗ members.json が見つかりません\n");
}

$content = file_get_contents($membersFile);
$data = json_decode($content, true);

if (!$data || !isset($data['users'])) {
    die("✗ データの形式が不正です\n");
}

// Check if user exists
if (!isset($data['users'][$emailToDelete])) {
    die("✗ ユーザーが見つかりません\n");
}

$userName = $data['users'][$emailToDelete]['name'];
echo "削除するユーザー情報:\n";
print_r($data['users'][$emailToDelete]);
echo "\n";

// Remove from users
unset($data['users'][$emailToDelete]);
echo "✓ users から削除しました\n";

// Remove from members list
$memberIndex = array_search($userName, $data['members']);
if ($memberIndex !== false) {
    unset($data['members'][$memberIndex]);
    $data['members'] = array_values($data['members']); // Re-index array
    echo "✓ members リストから削除しました\n";
}

// Update timestamp
$data['updated_at'] = date('Y-m-d');

// Save updated members.json
$jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
if (file_put_contents($membersFile, $jsonContent) === false) {
    die("✗ ファイルの保存に失敗しました\n");
}

echo "✓ members.json を更新しました\n\n";

// Remove from .htpasswd (if exists)
$htpasswdFile = __DIR__ . '/.htpasswd';
if (file_exists($htpasswdFile)) {
    $htpasswdContent = file_get_contents($htpasswdFile);
    $lines = explode("\n", $htpasswdContent);
    $newLines = [];

    foreach ($lines as $line) {
        if (strpos($line, $emailToDelete . ':') !== 0) {
            $newLines[] = $line;
        }
    }

    file_put_contents($htpasswdFile, implode("\n", $newLines));
    echo "✓ .htpasswd から削除しました\n";
}

echo "\n=== 削除完了 ===\n";
echo "yamada@yojitu.com のアカウントを削除しました。\n";
echo "再度登録画面から登録してください。\n";
