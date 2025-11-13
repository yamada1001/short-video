<?php
/**
 * ブログカテゴリフィルタ API
 * Ajax用エンドポイント
 */

header('Content-Type: application/json');

require_once __DIR__ . '/../includes/functions.php';

// カテゴリパラメータ取得
$category = isset($_GET['category']) ? $_GET['category'] : 'all';

// ブログ記事を取得
$all_posts = getPosts(BLOG_DATA_PATH);

// カテゴリフィルタ適用
if ($category !== 'all') {
    $filtered_posts = array_filter($all_posts, function($post) use ($category) {
        return isset($post['category']) && $post['category'] === $category;
    });
} else {
    $filtered_posts = $all_posts;
}

// 日付順にソート
usort($filtered_posts, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// 最新3件を取得
$latest_posts = array_slice($filtered_posts, 0, 3);

// JSONレスポンスを返す
$response = [];
foreach ($latest_posts as $post) {
    $date = new DateTime($post['publishedAt']);
    $response[] = [
        'slug' => $post['slug'],
        'title' => $post['title'],
        'excerpt' => $post['excerpt'],
        'category' => $post['category'],
        'date' => $date->format('Y.m.d')
    ];
}

echo json_encode([
    'success' => true,
    'posts' => $response
]);
