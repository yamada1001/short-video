<?php
/**
 * 英語版サイトのエントリーポイント
 * 日本語ページを自動翻訳して表示
 */

// エラー表示（開発時）
// ini_set('display_errors', 1);
// error_reporting(E_ALL);

// 翻訳システムを読み込み
require_once __DIR__ . '/../includes/translator.php';
require_once __DIR__ . '/../includes/functions.php';

// リクエストされたページを取得
$requestedPage = $_GET['page'] ?? '';

// トップページの場合
if (empty($requestedPage) || $requestedPage === 'index.php') {
    $requestedPage = 'index.php';
}

// .phpがない場合は追加
if (!empty($requestedPage) && !str_ends_with($requestedPage, '.php') && strpos($requestedPage, '.') === false) {
    // ディレクトリの場合（例: blog, news）
    if (is_dir(__DIR__ . '/../' . $requestedPage)) {
        $requestedPage = $requestedPage . '/index.php';
    } else {
        $requestedPage = $requestedPage . '.php';
    }
}

// 日本語ページのパスを構築
$jaPagePath = __DIR__ . '/../' . $requestedPage;

// ページが存在しない場合は404
if (!file_exists($jaPagePath)) {
    header('HTTP/1.1 404 Not Found');
    include __DIR__ . '/../404.php';
    exit;
}

// 翻訳インスタンスを作成
$translator = new Translator();

// キャッシュをチェック
$cachedContent = $translator->getCache($requestedPage, 'en');

if ($cachedContent !== null) {
    // キャッシュがある場合はそれを出力
    echo $cachedContent;
    exit;
}

// キャッシュがない場合は翻訳を実行
try {
    // 日本語ページを取得（バッファリングを使用）
    ob_start();

    // ページ固有の変数を設定
    $isEnglishVersion = true;
    $currentLang = 'en';
    $langPrefix = '/en';

    // PHPページを実行
    include $jaPagePath;

    $jaContent = ob_get_clean();

    // HTMLを翻訳
    $enContent = translateHTMLContent($jaContent, $translator);

    // 言語メタタグとhreflangを追加
    $enContent = addLanguageTags($enContent, $requestedPage);

    // キャッシュに保存
    $translator->setCache($requestedPage, $enContent, 'en');

    // 出力
    echo $enContent;

} catch (Exception $e) {
    // エラーが発生した場合
    header('HTTP/1.1 500 Internal Server Error');
    echo '<h1>Translation Error</h1>';
    echo '<p>Sorry, we encountered an error while translating this page.</p>';
    // error_log('Translation error: ' . $e->getMessage());
}

/**
 * HTML全体を翻訳
 *
 * @param string $html 翻訳するHTML
 * @param Translator $translator 翻訳インスタンス
 * @return string 翻訳されたHTML
 */
function translateHTMLContent($html, $translator) {
    // DOMDocumentを使用してHTMLをパース
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);

    // UTF-8エンコーディングを指定してロード
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    // lang属性を変更
    $htmlTag = $dom->getElementsByTagName('html')->item(0);
    if ($htmlTag) {
        $htmlTag->setAttribute('lang', 'en');
    }

    // テキストノードを翻訳
    translateDOMNode($dom->documentElement, $translator);

    return $dom->saveHTML();
}

/**
 * DOMノードを再帰的に翻訳
 *
 * @param DOMNode $node
 * @param Translator $translator
 */
function translateDOMNode($node, $translator) {
    // スキップすべきタグ（コードブロック、スクリプトなど）
    $skipTags = ['script', 'style', 'code', 'pre'];

    if ($node->nodeType === XML_ELEMENT_NODE && in_array(strtolower($node->nodeName), $skipTags)) {
        return;
    }

    if ($node->nodeType === XML_TEXT_NODE) {
        $text = trim($node->nodeValue);
        if (!empty($text) && mb_strlen($text) > 1) {
            // 翻訳
            $translated = $translator->translate($text, 'en');
            $node->nodeValue = $translated;
        }
    } elseif ($node->hasChildNodes()) {
        foreach ($node->childNodes as $child) {
            translateDOMNode($child, $translator);
        }
    }

    // 属性を翻訳（title, alt, placeholder, aria-labelなど）
    if ($node->nodeType === XML_ELEMENT_NODE) {
        $attributes = ['title', 'alt', 'placeholder', 'aria-label', 'content'];
        foreach ($attributes as $attr) {
            if ($node->hasAttribute($attr)) {
                $value = $node->getAttribute($attr);
                if (!empty($value) && !str_starts_with($value, 'http') && !str_starts_with($value, '/')) {
                    $translated = $translator->translate($value, 'en');
                    $node->setAttribute($attr, $translated);
                }
            }
        }

        // リンクを英語版に変更
        if (strtolower($node->nodeName) === 'a' && $node->hasAttribute('href')) {
            $href = $node->getAttribute('href');
            // 内部リンクの場合
            if (!str_starts_with($href, 'http') && !str_starts_with($href, 'mailto:') && !str_starts_with($href, 'tel:')) {
                // /en/ プレフィックスを追加
                if (!str_starts_with($href, '/en/')) {
                    $node->setAttribute('href', '/en' . $href);
                }
            }
        }
    }
}

/**
 * 言語メタタグとhreflangを追加
 *
 * @param string $html HTML内容
 * @param string $pagePath ページパス
 * @return string 更新されたHTML
 */
function addLanguageTags($html, $pagePath) {
    // hreflangタグを追加
    $jaUrl = 'https://yojitu.com/' . ltrim($pagePath, '/');
    $enUrl = 'https://yojitu.com/en/' . ltrim($pagePath, '/');

    $hreflangTags = <<<EOD
    <link rel="alternate" hreflang="ja" href="{$jaUrl}">
    <link rel="alternate" hreflang="en" href="{$enUrl}">
    <link rel="alternate" hreflang="x-default" href="{$jaUrl}">
EOD;

    // </head>の前に挿入
    $html = str_replace('</head>', $hreflangTags . "\n</head>", $html);

    return $html;
}
