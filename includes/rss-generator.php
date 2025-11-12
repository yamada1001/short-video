<?php
/**
 * RSS配信スクリプト
 *
 * 使用方法:
 * 1. ブラウザでアクセス: https://yojitu.com/includes/rss-generator.php
 * 2. RSS URL: https://yojitu.com/rss.xml
 */

require_once __DIR__ . '/functions.php';

// RSSフィード生成（お知らせ＋ブログ統合）
function generateRSS() {
    $baseUrl = SITE_URL;
    $title = SITE_NAME . ' - 最新情報';
    $description = '余日（Yojitsu）のお知らせとブログの最新情報をお届けします。';
    $link = $baseUrl;

    // お知らせ記事取得
    $newsArticles = getArticles(NEWS_DATA_PATH);
    $newsItems = [];
    foreach ($newsArticles as $article) {
        $newsItems[] = [
            'title' => $article['title'],
            'link' => $baseUrl . '/news/detail.php?id=' . $article['id'],
            'description' => strip_tags(mb_substr($article['content'], 0, 200)) . '...',
            'pubDate' => date(DATE_RSS, strtotime($article['publishedAt'])),
            'category' => $article['category'],
            'guid' => $baseUrl . '/news/detail.php?id=' . $article['id']
        ];
    }

    // ブログ記事取得
    $blogPosts = getPosts(BLOG_DATA_PATH);
    $blogItems = [];
    foreach ($blogPosts as $post) {
        $blogItems[] = [
            'title' => $post['title'],
            'link' => $baseUrl . '/blog/detail.php?slug=' . urlencode($post['slug']),
            'description' => $post['excerpt'],
            'pubDate' => date(DATE_RSS, strtotime($post['publishedAt'])),
            'category' => $post['category'],
            'guid' => $baseUrl . '/blog/detail.php?slug=' . urlencode($post['slug'])
        ];
    }

    // 統合して日付順にソート
    $allItems = array_merge($newsItems, $blogItems);
    usort($allItems, function($a, $b) {
        return strtotime($b['pubDate']) - strtotime($a['pubDate']);
    });

    // 最新20件に制限
    $allItems = array_slice($allItems, 0, 20);

    // RSS XML生成
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
    $xml .= '  <channel>' . "\n";
    $xml .= '    <title>' . htmlspecialchars($title, ENT_XML1) . '</title>' . "\n";
    $xml .= '    <link>' . htmlspecialchars($link, ENT_XML1) . '</link>' . "\n";
    $xml .= '    <description>' . htmlspecialchars($description, ENT_XML1) . '</description>' . "\n";
    $xml .= '    <language>ja</language>' . "\n";
    $xml .= '    <lastBuildDate>' . date(DATE_RSS) . '</lastBuildDate>' . "\n";
    $xml .= '    <atom:link href="' . htmlspecialchars($baseUrl . '/rss.xml', ENT_XML1) . '" rel="self" type="application/rss+xml" />' . "\n";

    foreach ($allItems as $item) {
        $xml .= '    <item>' . "\n";
        $xml .= '      <title>' . htmlspecialchars($item['title'], ENT_XML1) . '</title>' . "\n";
        $xml .= '      <link>' . htmlspecialchars($item['link'], ENT_XML1) . '</link>' . "\n";
        $xml .= '      <description>' . htmlspecialchars($item['description'], ENT_XML1) . '</description>' . "\n";
        $xml .= '      <pubDate>' . $item['pubDate'] . '</pubDate>' . "\n";
        $xml .= '      <category>' . htmlspecialchars($item['category'], ENT_XML1) . '</category>' . "\n";
        $xml .= '      <guid isPermaLink="true">' . htmlspecialchars($item['guid'], ENT_XML1) . '</guid>' . "\n";
        $xml .= '    </item>' . "\n";
    }

    $xml .= '  </channel>' . "\n";
    $xml .= '</rss>';

    return $xml;
}

// RSSをファイルに保存
function saveRSS($xml) {
    $rssPath = BASE_PATH . '/rss.xml';

    $result = file_put_contents($rssPath, $xml);

    if ($result === false) {
        return ['success' => false, 'message' => 'RSSフィードの保存に失敗しました。'];
    }

    return [
        'success' => true,
        'message' => 'RSSフィードを正常に生成しました。',
        'path' => $rssPath,
        'url' => SITE_URL . '/rss.xml'
    ];
}

// 実行
try {
    $xml = generateRSS();
    $result = saveRSS($xml);

    // コマンドラインから実行された場合
    if (php_sapi_name() === 'cli') {
        echo $result['success'] ? "SUCCESS: " : "ERROR: ";
        echo $result['message'] . "\n";
        if ($result['success']) {
            echo "Path: " . $result['path'] . "\n";
            echo "URL: " . $result['url'] . "\n";
        }
        exit($result['success'] ? 0 : 1);
    }

    // ブラウザから実行された場合（Content-Typeを明示）
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    $error = [
        'success' => false,
        'message' => 'エラーが発生しました: ' . $e->getMessage()
    ];

    if (php_sapi_name() === 'cli') {
        echo "ERROR: " . $error['message'] . "\n";
        exit(1);
    } else {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($error, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
?>
