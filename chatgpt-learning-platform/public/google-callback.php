<?php
/**
 * Google OAuth コールバック処理
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../vendor/autoload.php';

// すでにログイン済みの場合はダッシュボードへ
if (isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

// 認証コードを取得
$code = $_GET['code'] ?? '';

if (!$code) {
    $_SESSION['error_message'] = 'Google認証に失敗しました。';
    header('Location: ' . APP_URL . '/login.php');
    exit;
}

try {
    // Google Clientの設定
    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);

    // 認証コードをアクセストークンに交換
    $token = $client->fetchAccessTokenWithAuthCode($code);

    if (isset($token['error'])) {
        throw new Exception('トークンの取得に失敗しました: ' . $token['error']);
    }

    $client->setAccessToken($token);

    // ユーザー情報を取得
    $oauth = new Google_Service_Oauth2($client);
    $googleUser = $oauth->userinfo->get();

    $googleId = $googleUser->id;
    $email = $googleUser->email;
    $name = $googleUser->name;

    // DBでユーザーを検索
    $userSql = "SELECT * FROM users WHERE google_id = ? OR (email = ? AND oauth_provider = 'google')";
    $user = db()->fetchOne($userSql, [$googleId, $email]);

    if ($user) {
        // 既存ユーザー: Google IDを更新（まだない場合）
        if (!$user['google_id']) {
            $updateSql = "UPDATE users SET google_id = ?, oauth_provider = 'google' WHERE id = ?";
            db()->execute($updateSql, [$googleId, $user['id']]);
        }

        // ログイン
        $_SESSION['user_id'] = $user['id'];
    } else {
        // 新規ユーザー: 作成
        $insertSql = "INSERT INTO users (email, name, oauth_provider, google_id)
                      VALUES (?, ?, 'google', ?)";
        db()->execute($insertSql, [$email, $name, $googleId]);

        // ログイン
        $newUserId = db()->lastInsertId();
        $_SESSION['user_id'] = $newUserId;

        // ウェルカムメール送信
        sendWelcomeEmail($email, $name);
    }

    // ダッシュボードへリダイレクト
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;

} catch (Exception $e) {
    error_log('Google OAuth Error: ' . $e->getMessage());
    $_SESSION['error_message'] = 'Google認証に失敗しました。もう一度お試しください。';
    header('Location: ' . APP_URL . '/login.php');
    exit;
}
