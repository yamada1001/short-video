<?php
/**
 * 英語版サイトのエントリーポイント
 * 言語設定を行い、日本語版のPHPファイルを実行してHTML翻訳
 */

// 言語設定
define('CURRENT_LANG', 'en');
define('IS_ENGLISH', true);

// ルートディレクトリへの絶対パス
define('ROOT_PATH', dirname(__DIR__));

// 翻訳システムを読み込み
require_once ROOT_PATH . '/includes/i18n.php';

// リクエストされたページを取得
$requestedPage = $_GET['page'] ?? '';

// トップページの場合
if (empty($requestedPage) || $requestedPage === 'index.php' || $requestedPage === 'index') {
    $phpFile = ROOT_PATH . '/index.php';
} else {
    // .phpがない場合は追加
    if (!str_ends_with($requestedPage, '.php') && strpos($requestedPage, '.') === false) {
        // ディレクトリの場合（例: blog, news）
        if (is_dir(ROOT_PATH . '/' . $requestedPage)) {
            $phpFile = ROOT_PATH . '/' . $requestedPage . '/index.php';
        } else {
            $phpFile = ROOT_PATH . '/' . $requestedPage . '.php';
        }
    } else {
        $phpFile = ROOT_PATH . '/' . $requestedPage;
    }
}

// ページが存在しない場合は404
if (!file_exists($phpFile)) {
    header('HTTP/1.1 404 Not Found');
    include ROOT_PATH . '/404.php';
    exit;
}

// CWDをルートディレクトリに変更（相対パスが正しく動作するように）
chdir(ROOT_PATH);

// バッファリング開始
ob_start();

// 日本語版のPHPファイルを実行
include $phpFile;

// バッファの内容を取得
$html = ob_get_clean();

// HTML全体を翻訳
$html = translateHTML($html);

// 出力
echo $html;

/**
 * HTML全体を翻訳
 *
 * @param string $html 元のHTML
 * @return string 翻訳されたHTML
 */
function translateHTML($html) {
    global $translations;

    if (empty($translations)) {
        return $html;
    }

    // lang属性を変更
    $html = preg_replace('/<html[^>]*lang="ja"/', '<html lang="en"', $html);

    // 翻訳データの各キーを検索して置換
    // 長いキーから順に置換（部分一致を防ぐため）
    $sortedKeys = array_keys($translations);
    usort($sortedKeys, function($a, $b) {
        return mb_strlen($b) - mb_strlen($a);
    });

    foreach ($sortedKeys as $ja) {
        $en = $translations[$ja];

        // HTMLエンティティもエスケープして検索
        $jaEscaped = htmlspecialchars($ja, ENT_QUOTES, 'UTF-8');
        $enEscaped = htmlspecialchars($en, ENT_QUOTES, 'UTF-8');

        // 通常のテキスト置換
        $html = str_replace($ja, $en, $html);

        // HTMLエスケープされたテキスト置換
        $html = str_replace($jaEscaped, $enEscaped, $html);
    }

    // 内部リンクに /en プレフィックスを追加
    $html = addEnPrefixToLinks($html);

    // アセット（CSS、JS、画像）のパスを絶対パスに変換
    $html = fixAssetPaths($html);

    return $html;
}

/**
 * 内部リンクに /en プレフィックスを追加
 *
 * @param string $html HTML
 * @return string 修正されたHTML
 */
function addEnPrefixToLinks($html) {
    // <a href="..."> を検索
    $html = preg_replace_callback(
        '/<a\s+([^>]*)>/i',
        function($matches) {
            $fullTag = $matches[0];
            $attributes = $matches[1];

            // 言語切り替えリンク（nav__lang-link）はスキップ
            if (strpos($attributes, 'nav__lang-link') !== false) {
                return $fullTag;
            }

            // href属性を抽出
            if (!preg_match('/href=["\']([^"\']*)["\']/', $attributes, $hrefMatch)) {
                return $fullTag;
            }

            $url = $hrefMatch[1];

            // 外部リンク、mailto、tel、#アンカー、既に/en/で始まるリンクはスキップ
            if (str_starts_with($url, 'http') ||
                str_starts_with($url, 'mailto:') ||
                str_starts_with($url, 'tel:') ||
                str_starts_with($url, '#') ||
                str_starts_with($url, '/en/')) {
                return $fullTag;
            }

            // 絶対パスの場合
            if (str_starts_with($url, '/')) {
                $newUrl = '/en' . $url;
            }
            // 相対パスの場合
            else {
                $newUrl = '/en/' . $url;
            }

            // href属性を置換
            $newAttributes = preg_replace('/href=["\']([^"\']*)["\']/', 'href="' . $newUrl . '"', $attributes);
            return '<a ' . $newAttributes . '>';
        },
        $html
    );

    // <form action="..."> も同様に処理
    $html = preg_replace_callback(
        '/<form\s+([^>]*action=["\'])(\/[^"\']*|[^h\/][^"\']*)(["\'][^>]*)>/i',
        function($matches) {
            $before = $matches[1];
            $url = $matches[2];
            $after = $matches[3];

            if (str_starts_with($url, 'http') || str_starts_with($url, '/en/')) {
                return $matches[0];
            }

            if (str_starts_with($url, '/')) {
                $url = '/en' . $url;
            } else {
                $url = '/en/' . $url;
            }

            return '<form ' . $before . $url . $after . '>';
        },
        $html
    );

    return $html;
}

/**
 * アセット（CSS、JS、画像）のパスを絶対パスに変換
 *
 * @param string $html HTML
 * @return string 修正されたHTML
 */
function fixAssetPaths($html) {
    // <link href="assets/..."> → <link href="/assets/...">
    $html = preg_replace_callback(
        '/<link\s+([^>]*href=["\'])(assets\/[^"\']*)(["\'][^>]*)>/i',
        function($matches) {
            return '<link ' . $matches[1] . '/' . $matches[2] . $matches[3] . '>';
        },
        $html
    );

    // <script src="assets/..."> → <script src="/assets/...">
    $html = preg_replace_callback(
        '/<script\s+([^>]*src=["\'])(assets\/[^"\']*)(["\'][^>]*)>/i',
        function($matches) {
            return '<script ' . $matches[1] . '/' . $matches[2] . $matches[3] . '>';
        },
        $html
    );

    // <img src="assets/..."> → <img src="/assets/...">
    $html = preg_replace_callback(
        '/<img\s+([^>]*src=["\'])(assets\/[^"\']*)(["\'][^>]*)>/i',
        function($matches) {
            return '<img ' . $matches[1] . '/' . $matches[2] . $matches[3] . '>';
        },
        $html
    );

    // CSS内の background-image: url(assets/...) → background-image: url(/assets/...)
    $html = preg_replace(
        '/(url\(["\']?)(assets\/[^"\')]+)(["\']?\))/i',
        '$1/$2$3',
        $html
    );

    return $html;
}
