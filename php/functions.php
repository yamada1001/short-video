<?php
/**
 * 共通関数
 */

require_once __DIR__ . '/config.php';

// HTMLエスケープ
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// 記事データ取得
function getArticles($file_path) {
    if (!file_exists($file_path)) {
        return [];
    }
    $json = file_get_contents($file_path);
    $data = json_decode($json, true);
    return isset($data['articles']) ? $data['articles'] : [];
}

// IDから記事取得
function getArticleById($articles, $id) {
    foreach ($articles as $article) {
        if ($article['id'] == $id) {
            return $article;
        }
    }
    return null;
}

// 日付フォーマット
function formatDate($datetime, $format = 'Y.m.d') {
    try {
        $dt = new DateTime($datetime);
        return $dt->format($format);
    } catch (Exception $e) {
        return '';
    }
}

// NEWバッジ判定
function isNew($datetime, $days = NEW_BADGE_DAYS) {
    try {
        $dt = new DateTime($datetime);
        $now = new DateTime();
        $diff = $now->diff($dt);
        return $diff->days <= $days && $diff->invert == 0;
    } catch (Exception $e) {
        return false;
    }
}

// ページネーション
function getPagination($total, $per_page, $current_page = 1) {
    $total_pages = ceil($total / $per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $per_page;

    return [
        'total' => $total,
        'per_page' => $per_page,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'has_prev' => $current_page > 1,
        'has_next' => $current_page < $total_pages
    ];
}

// CSRFトークン生成
function generateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

// CSRFトークン検証
function verifyCsrfToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION[CSRF_TOKEN_NAME]) &&
           hash_equals($_SESSION[CSRF_TOKEN_NAME], $token);
}
?>
