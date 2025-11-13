<?php
/**
 * ブログ記事HTMLファイルからH1タグを削除するスクリプト
 * （detail.phpでページタイトルとしてH1を表示するため）
 */

$data_dir = __DIR__ . '/data';
$files = glob($data_dir . '/article-*-full.html');

foreach ($files as $file) {
    echo "Processing: " . basename($file) . "\n";

    $content = file_get_contents($file);
    $original_content = $content;

    // H1タグを削除（開始タグと終了タグを含む）
    $content = preg_replace('/<h1[^>]*>.*?<\/h1>/s', '', $content);

    if ($content !== $original_content) {
        file_put_contents($file, $content);
        echo "  ✓ H1 tag removed\n";
    } else {
        echo "  - No H1 tag found\n";
    }
}

echo "\nAll done!\n";
?>
