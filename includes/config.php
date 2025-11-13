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

// 連絡先情報（詳細）
define('CONTACT_TEL', '080-4692-9681');
define('CONTACT_TEL_LINK', '08046929681');
define('CONTACT_EMAIL', 'yamada@yojitu.com');
define('CONTACT_LINE_URL', 'https://line.me/ti/p/CTOCx9YKjk');

// 営業情報
define('BUSINESS_HOURS', '10時~22時');
define('BUSINESS_DAYS', 'なし');

// 会社情報
define('COMPANY_NAME', '余日（Yojitsu）');
define('COMPANY_REPRESENTATIVE', '山田 蓮');
define('COMPANY_FOUNDED', '2025.05.14');
define('COMPANY_TAX_ID', 'T9810094141774');
define('COMPANY_LOCATION', '大分県');

// Google Tag Manager
define('GTM_ID', 'GTM-T7NGQDC2');

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
