<?php
/**
 * Google OAuth認証開始
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../vendor/autoload.php';

// すでにログイン済みの場合はダッシュボードへ
if (isset($_SESSION['user_id'])) {
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

// Google Clientの設定
$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope('email');
$client->addScope('profile');

// 認証URLを生成してリダイレクト
$authUrl = $client->createAuthUrl();
header('Location: ' . $authUrl);
exit;
