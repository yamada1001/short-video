<?php
/**
 * ChatGPT学習プラットフォーム - 設定ファイル
 *
 * このファイルはアプリケーション全体で使用される設定を管理します。
 * .envファイルから環境変数を読み込みます。
 */

// Composerオートローダー
require_once __DIR__ . '/../vendor/autoload.php';

// .envファイルを読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// エラー表示設定
if ($_ENV['APP_DEBUG'] === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// セッション設定
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1); // HTTPS環境のみ
session_start();

// データベース設定
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

// OpenAI API設定（非推奨 - Geminiに移行）
define('OPENAI_API_KEY', $_ENV['OPENAI_API_KEY'] ?? '');
define('OPENAI_MODEL', $_ENV['OPENAI_MODEL'] ?? 'gpt-3.5-turbo');

// Gemini API設定（無料枠: 1,500リクエスト/日）
define('GEMINI_API_KEY', $_ENV['GEMINI_API_KEY']);
define('GEMINI_MODEL', $_ENV['GEMINI_MODEL'] ?? 'gemini-1.5-flash');

// Google OAuth設定
define('GOOGLE_CLIENT_ID', $_ENV['GOOGLE_CLIENT_ID']);
define('GOOGLE_CLIENT_SECRET', $_ENV['GOOGLE_CLIENT_SECRET']);
define('GOOGLE_REDIRECT_URI', $_ENV['GOOGLE_REDIRECT_URI']);

// Stripe設定
define('STRIPE_PUBLISHABLE_KEY', $_ENV['STRIPE_PUBLISHABLE_KEY']);
define('STRIPE_SECRET_KEY', $_ENV['STRIPE_SECRET_KEY']);
define('STRIPE_WEBHOOK_SECRET', $_ENV['STRIPE_WEBHOOK_SECRET']);
define('STRIPE_PRICE_ID_MONTHLY', $_ENV['STRIPE_PRICE_ID_MONTHLY']);
define('STRIPE_PRICE_ID_YEARLY', $_ENV['STRIPE_PRICE_ID_YEARLY']);

// メール設定
define('MAIL_HOST', $_ENV['MAIL_HOST']);
define('MAIL_PORT', $_ENV['MAIL_PORT']);
define('MAIL_USERNAME', $_ENV['MAIL_USERNAME']);
define('MAIL_PASSWORD', $_ENV['MAIL_PASSWORD']);
define('MAIL_FROM_ADDRESS', $_ENV['MAIL_FROM_ADDRESS']);
define('MAIL_FROM_NAME', $_ENV['MAIL_FROM_NAME']);

// アプリケーション設定
define('APP_URL', $_ENV['APP_URL']);
define('APP_ENV', $_ENV['APP_ENV']);
define('APP_DEBUG', $_ENV['APP_DEBUG'] === 'true');

// API使用制限
define('API_LIMIT_FREE', (int)$_ENV['API_LIMIT_FREE']);
define('API_LIMIT_PREMIUM', (int)$_ENV['API_LIMIT_PREMIUM']);

// パス定義
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('INCLUDES_PATH', ROOT_PATH . '/includes');
define('API_PATH', ROOT_PATH . '/api');
define('ADMIN_PATH', ROOT_PATH . '/admin');

// CSRF対策用トークン生成関数
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF対策用トークン検証関数
function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// XSS対策用エスケープ関数
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// リダイレクト関数
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

// JSON レスポンス送信関数
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// エラーレスポンス送信関数
function errorResponse($message, $statusCode = 400) {
    jsonResponse(['error' => $message], $statusCode);
}

// 成功レスポンス送信関数
function successResponse($data = [], $message = 'Success') {
    jsonResponse(['success' => true, 'message' => $message, 'data' => $data]);
}
