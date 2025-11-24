<?php
/**
 * ブログ記事フォーマット検証スクリプト
 *
 * 使い方:
 * php validate-article.php data/article-XX-full.html
 *
 * または全記事をチェック:
 * php validate-article.php --all
 */

// 色付き出力用の定数
define('COLOR_RED', "\033[31m");
define('COLOR_GREEN', "\033[32m");
define('COLOR_YELLOW', "\033[33m");
define('COLOR_RESET', "\033[0m");

// エラーカウンター
$totalErrors = 0;
$totalWarnings = 0;

// 定義済みCSSクラスをキャッシュ
$definedCssClasses = null;

/**
 * CSSファイルから定義されているクラスを抽出
 */
function getDefinedCssClasses() {
    global $definedCssClasses;

    if ($definedCssClasses !== null) {
        return $definedCssClasses;
    }

    $cssFile = __DIR__ . '/../assets/css/pages/blog.css';
    if (!file_exists($cssFile)) {
        return [];
    }

    $content = file_get_contents($cssFile);
    preg_match_all('/\.([a-zA-Z0-9_-]+)\s*[{,:]/', $content, $matches);
    $definedCssClasses = array_unique($matches[1]);

    return $definedCssClasses;
}

/**
 * HTMLファイルから使用されているクラスを抽出
 */
function getUsedClasses($content) {
    preg_match_all('/class="([^"]+)"/', $content, $matches);

    $classes = [];
    foreach ($matches[1] as $classStr) {
        $classList = preg_split('/\s+/', trim($classStr));
        $classes = array_merge($classes, $classList);
    }

    return array_unique($classes);
}

/**
 * 記事ファイルを検証
 */
function validateArticle($filepath) {
    global $totalErrors, $totalWarnings;

    if (!file_exists($filepath)) {
        echo COLOR_RED . "✗ ファイルが見つかりません: $filepath" . COLOR_RESET . "\n";
        return false;
    }

    $content = file_get_contents($filepath);
    $errors = [];
    $warnings = [];

    echo "\n検証中: " . basename($filepath) . "\n";
    echo str_repeat("-", 60) . "\n";

    // 1. 不要なHTML構造タグのチェック
    if (preg_match('/<(!DOCTYPE|html|head|body)/i', $content)) {
        $errors[] = "<!DOCTYPE>, <html>, <head>, <body>タグが含まれています";
    }

    // 2. インラインスタイルのチェック
    if (preg_match('/<style/i', $content)) {
        $errors[] = "<style>タグ（インラインスタイル）が含まれています";
    }

    // 3. インラインJavaScriptのチェック
    if (preg_match('/<script(?! type="application\/ld\+json")/i', $content)) {
        $errors[] = "<script>タグ（インラインJavaScript）が含まれています";
    }

    // 4. H1タグのチェック
    if (preg_match('/<h1/i', $content)) {
        $errors[] = "<h1>タグが含まれています（detail.phpで自動表示されます）";
    }

    // 5. 目次のチェック
    if (preg_match('/<nav class="table-of-contents"/i', $content)) {
        $errors[] = "目次（table-of-contents）が含まれています（detail.phpで自動生成されます）";
    }

    // 6. article-metaのチェック
    if (preg_match('/<p class="article-meta"/i', $content)) {
        $warnings[] = "article-meta（日付・カテゴリ）が含まれています（detail.phpで表示されます）";
    }

    // 7. lead-textのチェック
    if (preg_match('/<div class="lead-text"/i', $content)) {
        $warnings[] = "lead-text（リード文）が含まれています（detail.phpでexcerptから表示されます）";
    }

    // 8. CTAセクションのチェック
    if (preg_match('/<div class="cta-section"/i', $content)) {
        $warnings[] = "cta-sectionが含まれています（PHPインクルードを使用してください）";
    }

    // 9. 正しい開始タグのチェック
    if (!preg_match('/^<article class="blog-article">/', trim($content))) {
        $errors[] = "記事は<article class=\"blog-article\">で始まる必要があります";
    }

    // 10. 正しい終了タグのチェック
    if (!preg_match('/<\/article>\s*$/', trim($content))) {
        $errors[] = "記事は</article>で終わる必要があります";
    }

    // 11. PHPインクルードの存在チェック（article-cta.php）
    if (!preg_match('/include.*article-cta\.php/i', $content)) {
        $warnings[] = "記事末尾にarticle-cta.phpのインクルードがありません";
    }

    // 12. btn-primaryの使用チェック
    if (preg_match('/<a[^>]*class=["\'][^"\']*btn\s+btn-primary/i', $content)) {
        $warnings[] = "記事内でbtn-primaryクラスが使用されています。CTAボタンはarticle-cta.phpのPHPインクルードを使用してください";
    }

    // 13. cta-boxやarticle-ctaセクションのチェック
    if (preg_match('/<(div|section) class=["\'][^"\']*cta-(box|section|buttons)/i', $content)) {
        $warnings[] = "古い形式のCTAセクション(cta-box/cta-section/cta-buttons)が含まれています。article-cta.phpのPHPインクルードを使用してください";
    }

    // 14. 未定義CSSクラスのチェック（警告のみ）
    $definedClasses = getDefinedCssClasses();
    $usedClasses = getUsedClasses($content);

    // Font Awesome、Bootstrap風クラスは除外
    $ignoreClasses = ['fas', 'fa', 'fab', 'far', 'fal', 'fad', 'container', 'row', 'col', 'blog-article'];
    $ignorePatterns = ['/^fa-/', '/^col-/', '/^d-/', '/^text-/', '/^bg-/', '/^border-/', '/^m[tblrxy]?-/', '/^p[tblrxy]?-/'];

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
        $warnings[] = "未定義のCSSクラスが使用されています: " . implode(', ', array_slice($undefinedClasses, 0, 10)) . (count($undefinedClasses) > 10 ? '...' : '');
    }

    // 15. テーブル数のチェック（警告のみ）
    $tableCount = substr_count($content, '<table');
    if ($tableCount > 5) {
        $warnings[] = "テーブルが多すぎます（{$tableCount}個）。スマホ表示に注意してください";
    }

    // 16. データ・統計の引用チェック（警告のみ）
    // 数値データのパターンを検出
    if (preg_match('/(\d+[\.%％]|[0-9０-９]+割|[0-9０-９]+人|[0-9０-９]+社|[0-9０-９]+件)/', $content)) {
        // 参考文献セクションの存在チェック（HTMLエンティティ対応）
        // &#21442;&#32771;&#25991;&#29486; = 参考文献
        if (!preg_match('/<h[2-4]>.*参考文献.*<\/h[2-4]>|<h4>.*参考文献.*<\/h4>/i', $content) &&
            !preg_match('/note-box.*参考文献/is', $content) &&
            !preg_match('/note-box.*&#21442;&#32771;&#25991;&#29486;/is', $content)) {
            $warnings[] = "数値データが含まれていますが、参考文献セクションが見つかりません。データの出典を明記してください";
        }

        // 外部リンク（出典URL）の存在チェック
        if (!preg_match('/<a[^>]+href=["\']https?:\/\/[^"\']+["\'][^>]*class=["\'][^"\']*external-link/i', $content) &&
            !preg_match('/<a[^>]+class=["\'][^"\']*external-link[^"\']*["\'][^>]+href=["\']https?:\/\//i', $content)) {
            $warnings[] = "数値データが含まれていますが、外部リンク（出典URL）が見つかりません。参考文献にURLを記載してください";
        }
    }

    // 結果表示
    if (empty($errors) && empty($warnings)) {
        echo COLOR_GREEN . "✓ 検証成功！記事フォーマットは正しいです。" . COLOR_RESET . "\n";
        return true;
    }

    if (!empty($errors)) {
        echo COLOR_RED . "\n【エラー】以下の問題を修正してください:\n" . COLOR_RESET;
        foreach ($errors as $error) {
            echo COLOR_RED . "  ✗ $error\n" . COLOR_RESET;
            $totalErrors++;
        }
    }

    if (!empty($warnings)) {
        echo COLOR_YELLOW . "\n【警告】以下の点を確認してください:\n" . COLOR_RESET;
        foreach ($warnings as $warning) {
            echo COLOR_YELLOW . "  ⚠ $warning\n" . COLOR_RESET;
            $totalWarnings++;
        }
    }

    return empty($errors);
}

// コマンドライン引数の処理
if ($argc < 2) {
    echo "使い方:\n";
    echo "  php validate-article.php data/article-XX-full.html\n";
    echo "  php validate-article.php --all (全記事を検証)\n";
    exit(1);
}

if ($argv[1] === '--all') {
    echo "\n" . COLOR_GREEN . "=== 全記事を検証します ===" . COLOR_RESET . "\n";

    $files = glob(__DIR__ . '/data/article-*-full.html');
    sort($files);

    $passCount = 0;
    $failCount = 0;

    foreach ($files as $file) {
        $result = validateArticle($file);
        if ($result) {
            $passCount++;
        } else {
            $failCount++;
        }
    }

    echo "\n" . str_repeat("=", 60) . "\n";
    echo "検証完了\n";
    echo "  成功: " . COLOR_GREEN . "$passCount" . COLOR_RESET . " 件\n";
    echo "  失敗: " . ($failCount > 0 ? COLOR_RED : COLOR_GREEN) . "$failCount" . COLOR_RESET . " 件\n";
    echo "  エラー合計: " . ($totalErrors > 0 ? COLOR_RED : COLOR_GREEN) . "$totalErrors" . COLOR_RESET . " 件\n";
    echo "  警告合計: " . ($totalWarnings > 0 ? COLOR_YELLOW : COLOR_GREEN) . "$totalWarnings" . COLOR_RESET . " 件\n";

    exit($failCount > 0 ? 1 : 0);
} else {
    $filepath = $argv[1];

    // 相対パスの場合は絶対パスに変換
    if (!file_exists($filepath) && file_exists(__DIR__ . '/' . $filepath)) {
        $filepath = __DIR__ . '/' . $filepath;
    }

    $result = validateArticle($filepath);

    exit($result ? 0 : 1);
}
