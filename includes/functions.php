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

// ブログ記事データ取得
function getPosts($file_path) {
    if (!file_exists($file_path)) {
        return [];
    }
    $json = file_get_contents($file_path);
    $data = json_decode($json, true);
    return isset($data['posts']) ? $data['posts'] : [];
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

// slugから記事取得
function getArticleBySlug($articles, $slug) {
    foreach ($articles as $article) {
        if ($article['slug'] == $slug) {
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

/**
 * ブログコンテンツをモバイル対応処理
 * <pre><code>、<table>などの要素を自動検出してレスポンシブ対応のラッパーを追加
 *
 * @param string $content HTMLコンテンツ
 * @return string 処理済みHTMLコンテンツ
 */
function processBlogContent($content) {
    if (empty($content)) {
        return $content;
    }

    // DOMDocumentで安全にHTML処理
    $dom = new DOMDocument('1.0', 'UTF-8');

    // エラー抑制とHTML5対応
    libxml_use_internal_errors(true);

    // メタタグを追加してUTF-8を明示
    $dom->loadHTML('<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    // 1. <pre><code>ブロックの処理
    $preElements = $xpath->query('//pre[code]');
    foreach ($preElements as $pre) {
        // 既にラッパーdivで囲まれていないかチェック
        if ($pre->parentNode->nodeName !== 'div' ||
            !$pre->parentNode->hasAttribute('style') ||
            strpos($pre->parentNode->getAttribute('style'), 'overflow-x') === false) {

            // ラッパーdivを作成
            $wrapper = $dom->createElement('div');
            $wrapper->setAttribute('style', 'overflow-x: auto; max-width: 100%;');

            // preにスタイルを追加
            $existingStyle = $pre->hasAttribute('style') ? $pre->getAttribute('style') : '';
            $newStyle = 'white-space: pre-wrap; word-wrap: break-word; overflow-wrap: break-word;';
            $pre->setAttribute('style', $existingStyle ? $existingStyle . ' ' . $newStyle : $newStyle);

            // preの親要素に挿入
            $pre->parentNode->insertBefore($wrapper, $pre);
            $wrapper->appendChild($pre);
        }
    }

    // 2. <table>要素の処理
    $tableElements = $xpath->query('//table');
    foreach ($tableElements as $table) {
        // 既にラッパーdivで囲まれていないかチェック
        if ($table->parentNode->nodeName !== 'div' ||
            !$table->parentNode->hasAttribute('class') ||
            strpos($table->parentNode->getAttribute('class'), 'table-responsive') === false) {

            // ラッパーdivを作成
            $wrapper = $dom->createElement('div');
            $wrapper->setAttribute('class', 'table-responsive');
            $wrapper->setAttribute('style', 'overflow-x: auto; -webkit-overflow-scrolling: touch;');

            // tableの親要素に挿入
            $table->parentNode->insertBefore($wrapper, $table);
            $wrapper->appendChild($table);
        }
    }

    // HTML出力
    $output = $dom->saveHTML();

    // <?xml encoding="UTF-8">を除去（複数パターン対応）
    $output = str_replace('<?xml encoding="UTF-8">', '', $output);
    $output = preg_replace('/<\?xml[^?]*\?>\s*/', '', $output);

    return $output;
}
?>
