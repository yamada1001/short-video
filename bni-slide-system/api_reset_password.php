<?php
/**
 * BNI Slide System - Reset Password API (SQLite Version)
 * パスワードをリセット
 * Updated: 2025-12-06 - SQLite対応版
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

// POSTメソッドのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'POSTメソッドのみ許可されています']);
    exit;
}

// CSRF protection
requireCSRFToken();

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

try {
    $db = getDbConnection();

    // トークンが有効なユーザーを検索
    $user = dbQuery($db, "
        SELECT id, email, reset_token, reset_token_expires
        FROM users
        WHERE reset_token = :token
    ", [':token' => $token]);

    if (empty($user)) {
        dbClose($db);
        echo json_encode(['success' => false, 'message' => '無効なリセットリンクです。トークンが見つかりません。']);
        exit;
    }

    $user = $user[0];

    // トークンの有効期限をチェック
    if (!empty($user['reset_token_expires'])) {
        $expiresAt = strtotime($user['reset_token_expires']);
        $now = time();

        if ($now > $expiresAt) {
            dbClose($db);
            echo json_encode(['success' => false, 'message' => 'リセットリンクの有効期限が切れています。もう一度パスワードリセットをリクエストしてください。']);
            exit;
        }
    }

    // パスワードをハッシュ化
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // パスワードを更新し、トークンを削除
    $updateQuery = "
        UPDATE users SET
            password_hash = :password_hash,
            reset_token = NULL,
            reset_token_expires = NULL,
            updated_at = datetime('now')
        WHERE id = :id
    ";

    $result = dbExecute($db, $updateQuery, [
        ':password_hash' => $hashedPassword,
        ':id' => $user['id']
    ]);

    dbClose($db);

    if (!$result) {
        echo json_encode(['success' => false, 'message' => 'パスワードの保存に失敗しました']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'パスワードが更新されました。新しいパスワードでログインできます。'
    ]);

} catch (Exception $e) {
    error_log('[API RESET PASSWORD] Error: ' . $e->getMessage());
    if (isset($db)) {
        dbClose($db);
    }

    echo json_encode([
        'success' => false,
        'message' => 'パスワードのリセット中にエラーが発生しました'
    ]);
}
?>
