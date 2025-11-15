<?php
/**
 * ブログ記事内リンク生成ヘルパー関数
 *
 * 記事作成時にこれらの関数を使用することで、
 * 正しいリンク形式を保証し、404エラーを防ぎます。
 */

require_once __DIR__ . '/../../includes/functions.php';

/**
 * ブログ記事へのリンクを生成
 *
 * @param int $articleId 記事ID
 * @param string $linkText リンクテキスト
 * @param array $posts 記事データ配列（省略時は自動取得）
 * @return string HTMLリンクタグ または エラーメッセージ
 *
 * 使用例:
 *   generateArticleLink(81, '大分でホームページ制作を依頼する前に知っておきたい5つのポイント')
 *   → <a href="detail.php?slug=homepage-request-oita-5-points">大分でホームページ制作を依頼する前に知っておきたい5つのポイント</a>
 */
function generateArticleLink($articleId, $linkText, $posts = null)
{
    if ($posts === null) {
        $posts = getPosts(BLOG_DATA_PATH);
    }

    // 記事IDからslugを取得
    $slug = null;
    foreach ($posts as $post) {
        if ($post['id'] == $articleId) {
            $slug = $post['slug'];
            break;
        }
    }

    if ($slug === null) {
        return "<!-- エラー: 記事ID {$articleId} が見つかりません -->";
    }

    return sprintf('<a href="detail.php?slug=%s">%s</a>', htmlspecialchars($slug), htmlspecialchars($linkText));
}

/**
 * slugから記事へのリンクを生成
 *
 * @param string $slug 記事のslug
 * @param string $linkText リンクテキスト
 * @param array $posts 記事データ配列（省略時は自動取得）
 * @return string HTMLリンクタグ または エラーメッセージ
 *
 * 使用例:
 *   generateArticleLinkBySlug('homepage-request-oita-5-points', '大分でホームページ制作を依頼する前に知っておきたい5つのポイント')
 */
function generateArticleLinkBySlug($slug, $linkText, $posts = null)
{
    if ($posts === null) {
        $posts = getPosts(BLOG_DATA_PATH);
    }

    // slugが存在するか確認
    $exists = false;
    foreach ($posts as $post) {
        if ($post['slug'] === $slug) {
            $exists = true;
            break;
        }
    }

    if (!$exists) {
        return "<!-- エラー: slug '{$slug}' が見つかりません -->";
    }

    return sprintf('<a href="detail.php?slug=%s">%s</a>', htmlspecialchars($slug), htmlspecialchars($linkText));
}

/**
 * 記事ID一覧とslugのマッピングを表示（デバッグ用）
 *
 * 使用例:
 *   showArticleIdSlugMapping()
 */
function showArticleIdSlugMapping()
{
    $posts = getPosts(BLOG_DATA_PATH);

    echo "記事ID → slug マッピング一覧\n";
    echo "==============================\n";

    foreach ($posts as $post) {
        printf("ID: %3d → slug: %s\n", $post['id'], $post['slug']);
        printf("        タイトル: %s\n\n", $post['title']);
    }
}

/**
 * 関連記事リンクボックスのHTMLを生成
 *
 * @param int $articleId 記事ID
 * @param array $posts 記事データ配列（省略時は自動取得）
 * @return string HTMLコード または エラーメッセージ
 *
 * 使用例:
 *   generateRelatedArticleBox(81)
 */
function generateRelatedArticleBox($articleId, $posts = null)
{
    if ($posts === null) {
        $posts = getPosts(BLOG_DATA_PATH);
    }

    // 記事情報を取得
    $article = null;
    foreach ($posts as $post) {
        if ($post['id'] == $articleId) {
            $article = $post;
            break;
        }
    }

    if ($article === null) {
        return "<!-- エラー: 記事ID {$articleId} が見つかりません -->";
    }

    $link = generateArticleLink($articleId, $article['title'], $posts);

    return <<<HTML
                <div class="link-box">
                    <p><i class="fas fa-arrow-right"></i> 関連記事：{$link}</p>
                </div>
HTML;
}

// コマンドラインから直接実行された場合はマッピング一覧を表示
if (php_sapi_name() === 'cli' && isset($argv[0]) && realpath($argv[0]) === __FILE__) {
    showArticleIdSlugMapping();
}
