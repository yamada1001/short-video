<?php
/**
 * 全記事を正しいフォーマットに修正
 */

$dataDir = __DIR__ . '/data';
$files = glob($dataDir . '/article-*-full.html');

foreach ($files as $file) {
    $filename = basename($file);
    echo "修正中: $filename\n";

    $content = file_get_contents($file);

    // 既存の<script>タグ（構造化データ）を削除
    $content = preg_replace('/<script[^>]*>.*?<\/script>/s', '', $content);

    // 既存の<article>タグを削除（もしあれば）
    $content = preg_replace('/<\/?article[^>]*>/i', '', $content);

    // 既存のCTAインクルードを削除（もしあれば）
    $content = preg_replace('/<\?php\s+include.*?article-cta\.php.*?\?>/s', '', $content);

    // 前後の空白を削除
    $content = trim($content);

    // 正しいフォーマットで再構築
    $newContent = '<article class="blog-article">' . "\n\n";
    $newContent .= $content . "\n\n";
    $newContent .= '<?php include __DIR__ . "/../includes/article-cta.php"; ?>' . "\n\n";
    $newContent .= '</article>';

    // ファイルに書き込み
    file_put_contents($file, $newContent);

    echo "✓ 修正完了: $filename\n\n";
}

echo "全ての記事を修正しました！\n";
