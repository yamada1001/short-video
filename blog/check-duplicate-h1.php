<?php
/**
 * ブログ記事HTMLファイルのH1タグが複数ある記事を検出するスクリプト
 */

$data_dir = __DIR__ . '/data';
$files = glob($data_dir . '/article-*-full.html');

$duplicates = [];

foreach ($files as $file) {
    $content = file_get_contents($file);

    // H1タグの数をカウント
    preg_match_all('/<h1[^>]*>/', $content, $matches);
    $h1_count = count($matches[0]);

    if ($h1_count > 1) {
        // H1タグの内容を抽出
        preg_match_all('/<h1[^>]*>(.*?)<\/h1>/s', $content, $h1_matches);
        $h1_texts = array_map(function($text) {
            return html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }, $h1_matches[1]);

        $duplicates[] = [
            'file' => basename($file),
            'count' => $h1_count,
            'titles' => $h1_texts
        ];
    } elseif ($h1_count == 0) {
        $duplicates[] = [
            'file' => basename($file),
            'count' => 0,
            'titles' => []
        ];
    }
}

if (empty($duplicates)) {
    echo "✓ All articles have exactly one H1 tag.\n";
} else {
    echo "Found " . count($duplicates) . " articles with H1 issues:\n\n";
    foreach ($duplicates as $item) {
        echo "File: " . $item['file'] . "\n";
        echo "H1 count: " . $item['count'] . "\n";
        if (!empty($item['titles'])) {
            echo "Titles:\n";
            foreach ($item['titles'] as $i => $title) {
                echo "  " . ($i + 1) . ". " . $title . "\n";
            }
        }
        echo "\n";
    }
}
?>
