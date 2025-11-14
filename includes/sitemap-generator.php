<?php
/**
 * Sitemap自動生成スクリプト
 *
 * 使用方法:
 * 1. ブラウザでアクセス: https://yojitu.com/includes/sitemap-generator.php
 * 2. または、cronで定期実行: 0 0 * * * php /path/to/sitemap-generator.php
 */

require_once __DIR__ . '/functions.php';

// サイトマップ生成
function generateSitemap() {
    $baseUrl = SITE_URL;

    // 固定ページのURL
    $staticPages = [
        ['loc' => $baseUrl . '/', 'priority' => '1.0', 'changefreq' => 'daily'],
        ['loc' => $baseUrl . '/about.html', 'priority' => '0.8', 'changefreq' => 'monthly'],
        ['loc' => $baseUrl . '/services.php', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['loc' => $baseUrl . '/web-production.php', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['loc' => $baseUrl . '/video-production.php', 'priority' => '0.9', 'changefreq' => 'weekly'],
        ['loc' => $baseUrl . '/contact.html', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['loc' => $baseUrl . '/news/', 'priority' => '0.8', 'changefreq' => 'daily'],
        ['loc' => $baseUrl . '/blog/', 'priority' => '0.8', 'changefreq' => 'daily'],
    ];

    // お知らせ記事のURL
    $newsArticles = getArticles(NEWS_DATA_PATH);
    $newsUrls = [];
    foreach ($newsArticles as $article) {
        $newsUrls[] = [
            'loc' => $baseUrl . '/news/detail.php?id=' . $article['id'],
            'lastmod' => formatDate($article['updatedAt'], 'Y-m-d'),
            'priority' => '0.6',
            'changefreq' => 'weekly'
        ];
    }

    // ブログ記事のURL
    $blogPosts = getPosts(BLOG_DATA_PATH);
    $blogUrls = [];
    foreach ($blogPosts as $post) {
        $blogUrls[] = [
            'loc' => $baseUrl . '/blog/detail.php?slug=' . urlencode($post['slug']),
            'lastmod' => formatDate($post['updatedAt'], 'Y-m-d'),
            'priority' => '0.6',
            'changefreq' => 'weekly'
        ];
    }

    // すべてのURLを統合
    $allUrls = array_merge($staticPages, $newsUrls, $blogUrls);

    // XML生成
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    foreach ($allUrls as $url) {
        $xml .= '  <url>' . "\n";
        $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . '</loc>' . "\n";

        if (isset($url['lastmod'])) {
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
        }

        if (isset($url['changefreq'])) {
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
        }

        if (isset($url['priority'])) {
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
        }

        $xml .= '  </url>' . "\n";
    }

    $xml .= '</urlset>';

    return $xml;
}

// サイトマップをファイルに保存
function saveSitemap($xml) {
    $sitemapPath = BASE_PATH . '/sitemap.xml';

    $result = file_put_contents($sitemapPath, $xml);

    if ($result === false) {
        return ['success' => false, 'message' => 'サイトマップの保存に失敗しました。'];
    }

    return [
        'success' => true,
        'message' => 'サイトマップを正常に生成しました。',
        'path' => $sitemapPath,
        'url' => SITE_URL . '/sitemap.xml'
    ];
}

// 実行
try {
    $xml = generateSitemap();
    $result = saveSitemap($xml);

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

    // ブラウザから実行された場合
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
