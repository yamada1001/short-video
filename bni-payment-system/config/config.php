<?php
/**
 * アプリケーション設定ファイル
 * 環境変数読み込み、タイムゾーン設定、エラーハンドリング
 */

// Composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// ヘルパー関数読み込み
require_once __DIR__ . '/../src/helpers.php';

// 環境変数読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// エラーハンドリング
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
}

// セッション設定
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Lax');

// セキュリティヘッダー
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');

// 文字エンコーディング
mb_internal_encoding('UTF-8');
