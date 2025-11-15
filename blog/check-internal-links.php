#!/usr/bin/env php
<?php
/**
 * ブログ記事の内部リンクチェッカー
 *
 * 使い方: php blog/check-internal-links.php
 */

require_once __DIR__ . '/../includes/functions.php';

echo "==========================================\n";
echo "ブログ記事の内部リンクチェッカー\n";
echo "==========================================\n\n";

// 記事データを読み込み
$posts = getPosts(BLOG_DATA_PATH);
echo "読み込んだ記事数: " . count($posts) . "\n\n";

// 記事ID→slugのマッピングを作成
$idToSlug = [];
foreach ($posts as $post) {
    $idToSlug[$post['id']] = $post['slug'];
}

// 問題のあるリンクを格納
$errors = [];
$warnings = [];

// 各記事ファイルをチェック
foreach ($posts as $post) {
    $contentFile = __DIR__ . '/' . $post['content'];

    if (!file_exists($contentFile)) {
        $warnings[] = "警告: {$post['title']} のコンテンツファイルが見つかりません: {$contentFile}";
        continue;
    }

    $content = file_get_contents($contentFile);

    // パターン1: ../blog/article-XX.html 形式のリンクをチェック
    if (preg_match_all('/href="\.\.\/blog\/article-(\d+)\.html"/', $content, $matches)) {
        foreach ($matches[1] as $articleId) {
            $errors[] = "エラー: {$post['title']} に誤ったリンク形式があります: ../blog/article-{$articleId}.html";
            if (isset($idToSlug[$articleId])) {
                $errors[] = "  → 正しくは: detail.php?slug={$idToSlug[$articleId]}";
            }
        }
    }

    // パターン2: article-XX.html 形式のリンクをチェック
    if (preg_match_all('/href="article-(\d+)\.html"/', $content, $matches)) {
        foreach ($matches[1] as $articleId) {
            $errors[] = "エラー: {$post['title']} に誤ったリンク形式があります: article-{$articleId}.html";
            if (isset($idToSlug[$articleId])) {
                $errors[] = "  → 正しくは: detail.php?slug={$idToSlug[$articleId]}";
            }
        }
    }

    // パターン3: detail.php?slug=XXX 形式のリンクをチェック（正しいリンク）
    if (preg_match_all('/href="detail\.php\?slug=([^"]+)"/', $content, $matches)) {
        foreach ($matches[1] as $slug) {
            // このslugが実際に存在するかチェック
            $found = false;
            foreach ($posts as $checkPost) {
                if ($checkPost['slug'] === $slug) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $errors[] = "エラー: {$post['title']} に存在しないslugへのリンクがあります: detail.php?slug={$slug}";
            }
        }
    }
}

// 結果を出力
echo "==========================================\n";
echo "チェック結果\n";
echo "==========================================\n\n";

if (count($errors) > 0) {
    echo "【エラー】 " . count($errors) . " 件\n";
    echo "--------------------\n";
    foreach ($errors as $error) {
        echo $error . "\n";
    }
    echo "\n";
}

if (count($warnings) > 0) {
    echo "【警告】 " . count($warnings) . " 件\n";
    echo "--------------------\n";
    foreach ($warnings as $warning) {
        echo $warning . "\n";
    }
    echo "\n";
}

if (count($errors) === 0 && count($warnings) === 0) {
    echo "✅ 問題は見つかりませんでした！\n\n";
    exit(0);
} else {
    echo "❌ 問題が見つかりました。上記を確認してください。\n\n";
    exit(1);
}
