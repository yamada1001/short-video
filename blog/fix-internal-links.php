#!/usr/bin/env php
<?php
/**
 * ブログ記事の内部リンク自動修正スクリプト
 *
 * 使い方: php blog/fix-internal-links.php [--dry-run]
 */

require_once __DIR__ . '/../includes/functions.php';

$dryRun = in_array('--dry-run', $argv);

echo "==========================================\n";
echo "ブログ記事の内部リンク自動修正\n";
echo "==========================================\n\n";

if ($dryRun) {
    echo "【ドライランモード】 実際の変更は行いません\n\n";
}

// 記事データを読み込み
$posts = getPosts(BLOG_DATA_PATH);
echo "読み込んだ記事数: " . count($posts) . "\n\n";

// slug一覧を作成
$validSlugs = [];
$idToSlug = [];
foreach ($posts as $post) {
    $validSlugs[$post['slug']] = true;
    $idToSlug[$post['id']] = $post['slug'];
}

$totalFixed = 0;
$filesModified = 0;

// 各記事ファイルを処理
foreach ($posts as $post) {
    $contentFile = __DIR__ . '/' . $post['content'];

    if (!file_exists($contentFile)) {
        continue;
    }

    $content = file_get_contents($contentFile);
    $originalContent = $content;
    $fixes = [];

    // パターン1: article-XX-full.html 形式を修正
    $content = preg_replace_callback('/href="article-(\d+)-full\.html"/', function($matches) use ($idToSlug, &$fixes) {
        $id = (int)$matches[1];
        if (isset($idToSlug[$id])) {
            $fixes[] = "article-{$id}-full.html → detail.php?slug={$idToSlug[$id]}";
            return 'href="detail.php?slug=' . $idToSlug[$id] . '"';
        }
        return $matches[0];
    }, $content);

    // パターン2: article-XX.html 形式を修正
    $content = preg_replace_callback('/href="article-(\d+)\.html"/', function($matches) use ($idToSlug, &$fixes) {
        $id = (int)$matches[1];
        if (isset($idToSlug[$id])) {
            $fixes[] = "article-{$id}.html → detail.php?slug={$idToSlug[$id]}";
            return 'href="detail.php?slug=' . $idToSlug[$id] . '"';
        }
        return $matches[0];
    }, $content);

    // パターン3: ../blog/article-XX.html 形式を修正
    $content = preg_replace_callback('/href="\.\.\/blog\/article-(\d+)\.html"/', function($matches) use ($idToSlug, &$fixes) {
        $id = (int)$matches[1];
        if (isset($idToSlug[$id])) {
            $fixes[] = "../blog/article-{$id}.html → detail.php?slug={$idToSlug[$id]}";
            return 'href="detail.php?slug=' . $idToSlug[$id] . '"';
        }
        return $matches[0];
    }, $content);

    // パターン4: slug.html 形式を修正（外部リンクを除く）
    $content = preg_replace_callback('/href="([a-z0-9-]+)\.html"/', function($matches) use ($validSlugs, &$fixes) {
        $slug = $matches[1];
        // 有効なslugかチェック
        if (isset($validSlugs[$slug])) {
            $fixes[] = "{$slug}.html → detail.php?slug={$slug}";
            return 'href="detail.php?slug=' . $slug . '"';
        }
        return $matches[0];
    }, $content);

    // パターン5: /contact.html を /contact.php に修正
    if (strpos($content, 'href="/contact.html"') !== false) {
        $content = str_replace('href="/contact.html"', 'href="/contact.php"', $content);
        $fixes[] = "/contact.html → /contact.php";
    }

    // 変更があれば保存
    if ($content !== $originalContent) {
        $filesModified++;
        $totalFixed += count($fixes);

        echo "修正: {$post['title']}\n";
        foreach ($fixes as $fix) {
            echo "  - {$fix}\n";
        }
        echo "\n";

        if (!$dryRun) {
            file_put_contents($contentFile, $content);
        }
    }
}

echo "==========================================\n";
echo "修正結果\n";
echo "==========================================\n\n";
echo "修正したファイル数: {$filesModified}\n";
echo "修正したリンク数: {$totalFixed}\n\n";

if ($dryRun && $totalFixed > 0) {
    echo "実際に修正するには --dry-run オプションを外して実行してください。\n\n";
}

if ($totalFixed === 0) {
    echo "修正が必要なリンクは見つかりませんでした。\n\n";
}
