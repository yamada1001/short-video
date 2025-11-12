<?php
/**
 * Sitemap自動生成スクリプト
 * 実行: php generate-sitemap.php
 */

require_once __DIR__ . '/includes/functions.php';

// 基本URL
$baseUrl = 'https://yojitu.com';

// 現在の日付
$today = date('Y-m-d');

// sitemap用のURL配列
$urls = [];

// 静的ページ
$staticPages = [
    ['url' => '/index.html', 'priority' => '1.0', 'changefreq' => 'weekly'],
    ['url' => '/services.html', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['url' => '/about.html', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['url' => '/contact.html', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['url' => '/recruit.html', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['url' => '/blog/', 'priority' => '0.8', 'changefreq' => 'daily'],
    ['url' => '/news/', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['url' => '/privacy.html', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ['url' => '/tokushoho.html', 'priority' => '0.3', 'changefreq' => 'yearly'],
];

foreach ($staticPages as $page) {
    $urls[] = [
        'loc' => $baseUrl . $page['url'],
        'lastmod' => $today,
        'changefreq' => $page['changefreq'],
        'priority' => $page['priority']
    ];
}

// ブログ記事を追加
$blogPosts = getPosts(BLOG_DATA_PATH);
foreach ($blogPosts as $post) {
    $urls[] = [
        'loc' => $baseUrl . '/blog/detail.php?slug=' . urlencode($post['slug']),
        'lastmod' => date('Y-m-d', strtotime($post['updatedAt'])),
        'changefreq' => 'monthly',
        'priority' => '0.6'
    ];
}

// お知らせ記事を追加
$newsPosts = getPosts(NEWS_DATA_PATH);
foreach ($newsPosts as $post) {
    $urls[] = [
        'loc' => $baseUrl . '/news/detail.php?slug=' . urlencode($post['slug']),
        'lastmod' => date('Y-m-d', strtotime($post['updatedAt'])),
        'changefreq' => 'monthly',
        'priority' => '0.5'
    ];
}

// XML生成
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($urls as $url) {
    $xml .= "    <url>\n";
    $xml .= "        <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
    $xml .= "        <lastmod>" . $url['lastmod'] . "</lastmod>\n";
    $xml .= "        <changefreq>" . $url['changefreq'] . "</changefreq>\n";
    $xml .= "        <priority>" . $url['priority'] . "</priority>\n";
    $xml .= "    </url>\n";
}

$xml .= '</urlset>';

// sitemap.xmlに書き込み
$sitemapPath = __DIR__ . '/sitemap.xml';
file_put_contents($sitemapPath, $xml);

echo "Sitemap generated successfully!\n";
echo "Total URLs: " . count($urls) . "\n";
echo "Location: " . $sitemapPath . "\n";
