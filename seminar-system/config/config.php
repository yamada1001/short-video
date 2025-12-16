<?php
/**
 * セミナー管理システム - メイン設定ファイル
 */

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Composerオートローダー
require_once __DIR__ . '/../vendor/autoload.php';

// .env読み込み
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// エラーハンドリング
if (env('APP_DEBUG', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// データベース接続
use Seminar\Database;
$db = Database::getInstance();

// ヘルパー関数の読み込みは autoload で自動的に行われる

/**
 * 環境変数取得ヘルパー
 */
function env(string $key, $default = null) {
    $value = $_ENV[$key] ?? getenv($key);

    if ($value === false) {
        return $default;
    }

    // 真偽値の変換
    switch (strtolower($value)) {
        case 'true':
        case '(true)':
            return true;
        case 'false':
        case '(false)':
            return false;
        case 'empty':
        case '(empty)':
            return '';
        case 'null':
        case '(null)':
            return null;
    }

    return $value;
}

/**
 * HTMLエスケープ
 */
function h(?string $str): string {
    return $str !== null ? htmlspecialchars($str, ENT_QUOTES, 'UTF-8') : '';
}

/**
 * リダイレクト
 */
function redirect(string $path): void {
    $baseUrl = rtrim(env('APP_URL'), '/');
    header("Location: {$baseUrl}{$path}");
    exit;
}

/**
 * 現在の日時を取得（Y-m-d H:i:s形式）
 */
function now(): string {
    return date('Y-m-d H:i:s');
}

/**
 * 日付フォーマット
 */
function formatDate(?string $date, string $format = 'Y年m月d日'): string {
    if (!$date) return '';
    return date($format, strtotime($date));
}

/**
 * 日時フォーマット
 */
function formatDatetime(?string $datetime, string $format = 'Y年m月d日 H:i'): string {
    if (!$datetime) return '';
    return date($format, strtotime($datetime));
}
