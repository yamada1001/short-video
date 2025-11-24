#!/usr/bin/env php
<?php
/**
 * 全ブログ記事の潜在的な問題をチェック
 * - 未定義のCSSクラスの使用
 * - テーブルが多すぎる記事（スマホで表示崩れの可能性）
 */

// CSSファイルから定義されているクラスを抽出
function getDefinedCssClasses($cssFile) {
    $content = file_get_contents($cssFile);
    preg_match_all('/\.([a-zA-Z0-9_-]+)\s*[{,:]/', $content, $matches);
    return array_unique($matches[1]);
}

// HTMLファイルから使用されているクラスを抽出
function getUsedClasses($htmlFile) {
    $content = file_get_contents($htmlFile);
    preg_match_all('/class="([^"]+)"/', $content, $matches);

    $classes = [];
    foreach ($matches[1] as $classStr) {
        $classList = preg_split('/\s+/', trim($classStr));
        $classes = array_merge($classes, $classList);
    }

    return array_unique($classes);
}

// 記事の問題点をチェック
function checkArticle($articleFile, $definedClasses) {
    $content = file_get_contents($articleFile);
    $articleNum = preg_match('/article-(\d+)-full\.html/', $articleFile, $m) ? $m[1] : '?';

    $issues = [];

    // 1. テーブル数をチェック
    $tableCount = substr_count($content, '<table');
    if ($tableCount > 3) {
        $issues[] = "テーブル数が多い: {$tableCount}個（スマホ表示に注意）";
    }

    // 2. 未定義のCSSクラスをチェック
    $usedClasses = getUsedClasses($articleFile);

    // Font Awesome、共通クラスは除外
    $ignoreClasses = ['fas', 'fa', 'fab', 'far', 'fal', 'fad', 'container', 'row', 'col'];
    $ignorePatterns = ['/^fa-/', '/^col-/', '/^d-/', '/^text-/', '/^bg-/', '/^border-/', '/^m-/', '/^p-/', '/^mt-/', '/^mb-/', '/^ml-/', '/^mr-/'];

    $undefinedClasses = [];
    foreach ($usedClasses as $class) {
        if (in_array($class, $ignoreClasses)) continue;

        $shouldIgnore = false;
        foreach ($ignorePatterns as $pattern) {
            if (preg_match($pattern, $class)) {
                $shouldIgnore = true;
                break;
            }
        }
        if ($shouldIgnore) continue;

        if (!in_array($class, $definedClasses)) {
            $undefinedClasses[] = $class;
        }
    }

    if (!empty($undefinedClasses)) {
        $issues[] = "未定義のCSSクラス: " . implode(', ', $undefinedClasses);
    }

    // 3. 固定幅の要素をチェック
    if (preg_match('/width:\s*\d+px/', $content)) {
        $issues[] = "固定幅(px)の指定あり（レスポンシブに注意）";
    }

    return [
        'articleNum' => $articleNum,
        'issues' => $issues
    ];
}

// メイン処理
echo "ブログ記事の問題チェックを開始...\n\n";

$cssFile = __DIR__ . '/../assets/css/pages/blog.css';
$definedClasses = getDefinedCssClasses($cssFile);

echo "定義済みCSSクラス数: " . count($definedClasses) . "\n\n";

$articleFiles = glob(__DIR__ . '/data/article-*-full.html');
sort($articleFiles, SORT_NATURAL);

$problemArticles = [];

foreach ($articleFiles as $file) {
    $result = checkArticle($file, $definedClasses);
    if (!empty($result['issues'])) {
        $problemArticles[] = $result;
    }
}

if (empty($problemArticles)) {
    echo "✓ 問題は見つかりませんでした。\n";
    exit(0);
} else {
    echo "❌ 以下の記事に問題が見つかりました:\n\n";
    foreach ($problemArticles as $problem) {
        echo "【記事 {$problem['articleNum']}】\n";
        foreach ($problem['issues'] as $issue) {
            echo "  - $issue\n";
        }
        echo "\n";
    }
    echo "合計: " . count($problemArticles) . " 件の記事に問題があります。\n";
    exit(1);
}
