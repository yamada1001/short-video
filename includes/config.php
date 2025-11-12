<?php
/**
 * 設定ファイル
 */

// エラー表示（本番環境では0に）
ini_set('display_errors', 1);
error_reporting(E_ALL);

// タイムゾーン
date_default_timezone_set('Asia/Tokyo');

// サイト基本情報
define('SITE_NAME', '余日(Yojitsu)');
define('SITE_URL', 'https://yojitu.com');
define('SITE_EMAIL', 'yamada@yojitu.com');
define('SITE_TEL', '080-4692-9681');

// パス設定
define('BASE_PATH', dirname(__DIR__));
define('NEWS_DATA_PATH', BASE_PATH . '/news/data/articles.json');
define('BLOG_DATA_PATH', BASE_PATH . '/blog/data/posts.json');

// メール設定
define('ADMIN_EMAIL', 'yamada@yojitu.com');
define('FROM_EMAIL', 'noreply@yojitu.com');

// 記事設定
define('NEWS_PER_PAGE', 10);
define('BLOG_PER_PAGE', 12);
define('NEW_BADGE_DAYS', 7);

// セキュリティ
define('CSRF_TOKEN_NAME', 'csrf_token');
?>
