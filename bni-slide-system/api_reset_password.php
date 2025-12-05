<?php
/**
 * BNI Slide System - Reset Password API
 * パスワードをリセット
 */

header('Content-Type: application/json; charset=utf-8');

// POSTメソッドのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'POSTメソッドのみ許可されています']);
    exit;
}

// 入力を取得
$token = trim($_POST['token'] ?? '');
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

// バリデーション
if (empty($token)) {
    echo json_encode(['success' => false, 'message' => 'トークンが指定されていません']);
    exit;
}

if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'パスワードを入力してください']);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'パスワードは8文字以上で入力してください']);
    exit;
}

if ($password !== $passwordConfirm) {
    echo json_encode(['success' => false, 'message' => 'パスワードが一致しません']);
    exit;
}

// メンバーデータを読み込み
$membersFile = __DIR__ . '/data/members.json';

if (!file_exists($membersFile)) {
    echo json_encode(['success' => false, 'message' => 'ユーザーデータが見つかりません']);
    exit;
}

$content = file_get_contents($membersFile);
$data = json_decode($content, true);

if (!$data || !isset($data['users'])) {
    echo json_encode(['success' => false, 'message' => 'ユーザーデータの読み込みに失敗しました']);
    exit;
}

// トークンが有効なユーザーを検索
$userFound = false;
$username = '';

foreach ($data['users'] as $uname => $user) {
    if (isset($user['reset_token']) && $user['reset_token'] === $token) {
        // トークンの有効期限をチェック
        if (isset($user['reset_token_expires'])) {
            $expiresAt = strtotime($user['reset_token_expires']);
            $now = time();

            if ($now > $expiresAt) {
                echo json_encode(['success' => false, 'message' => 'リセットリンクの有効期限が切れています。もう一度パスワードリセットをリクエストしてください。']);
                exit;
            }
        }

        $userFound = true;
        $username = $uname;
        break;
    }
}

if (!$userFound) {
    echo json_encode(['success' => false, 'message' => '無効なリセットリンクです。トークンが見つかりません。']);
    exit;
}

// パスワードをハッシュ化
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// パスワードを更新し、トークンを削除（password_hashキーに保存）
$data['users'][$username]['password_hash'] = $hashedPassword;
unset($data['users'][$username]['reset_token']);
unset($data['users'][$username]['reset_token_expires']);
$data['users'][$username]['updated_at'] = date('Y-m-d H:i:s');

// データを保存
$jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
if (file_put_contents($membersFile, $jsonContent) === false) {
    echo json_encode(['success' => false, 'message' => 'パスワードの保存に失敗しました']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => '新しいパスワードでログインできます'
]);
