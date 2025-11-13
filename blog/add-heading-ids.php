<?php
/**
 * ブログ記事HTMLファイルの見出しにID属性を追加するスクリプト
 */

$data_dir = __DIR__ . '/data';
$files = glob($data_dir . '/article-*-full.html');

foreach ($files as $file) {
    echo "Processing: " . basename($file) . "\n";

    $content = file_get_contents($file);

    // DOMDocumentで解析
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $xpath = new DOMXPath($dom);

    // h2, h3タグを取得
    $headings = $xpath->query('//h2 | //h3');
    $modified = false;

    foreach ($headings as $index => $heading) {
        // すでにID属性がある場合はスキップ
        if (!$heading->hasAttribute('id')) {
            $id = 'heading-' . $index;
            $heading->setAttribute('id', $id);
            $modified = true;
        }
    }

    if ($modified) {
        // UTF-8のBOMなしで保存
        $new_content = $dom->saveHTML();

        // 不要な<?xml encoding="UTF-8">タグを除去
        $new_content = str_replace('<?xml encoding="UTF-8">', '', $new_content);

        file_put_contents($file, $new_content);
        echo "  ✓ Updated\n";
    } else {
        echo "  - Already has IDs, skipped\n";
    }
}

echo "\nAll done!\n";
?>
