<?php
/**
 * 人気記事セクション
 *
 * 使い方:
 * <?php include __DIR__ . '/includes/popular-articles.php'; ?>
 */

// popular-articles.jsonを読み込み
$popular_articles_file = __DIR__ . '/../data/popular-articles.json';
$popular_articles_data = null;

if (file_exists($popular_articles_file)) {
    $popular_articles_data = json_decode(file_get_contents($popular_articles_file), true);
}

// データがない場合は表示しない
if (!$popular_articles_data || empty($popular_articles_data['articles'])) {
    return;
}

// posts.jsonから記事の詳細情報を取得
require_once __DIR__ . '/../../includes/functions.php';
$all_posts = getPosts(BLOG_DATA_PATH);

// 人気記事のIDリスト
$popular_article_ids = array_column($popular_articles_data['articles'], 'id');

// IDに基づいて記事を取得（最大5件）
$popular_posts = [];
foreach ($popular_article_ids as $id) {
    foreach ($all_posts as $post) {
        if ($post['id'] === $id) {
            $popular_posts[] = $post;
            break;
        }
    }
    if (count($popular_posts) >= 5) {
        break;
    }
}

// 表示する記事がない場合は何もしない
if (empty($popular_posts)) {
    return;
}
?>

<section class="popular-articles">
    <div class="popular-articles__header">
        <h2 class="popular-articles__title">人気の記事</h2>
        <p class="popular-articles__period">
            <?php echo htmlspecialchars($popular_articles_data['period'], ENT_QUOTES, 'UTF-8'); ?>のデータ
        </p>
    </div>
    <div class="popular-articles__grid">
        <?php foreach ($popular_posts as $index => $popular): ?>
        <article class="popular-article-card">
            <a href="detail.php?slug=<?php echo urlencode($popular['slug']); ?>" class="popular-article-card__link">
                <div class="popular-article-card__rank">
                    <span class="rank-badge">
                        <?php echo $index + 1; ?>
                    </span>
                </div>
                <div class="popular-article-card__content">
                    <div class="popular-article-card__meta">
                        <time class="popular-article-card__date" datetime="<?php echo h($popular['publishedAt']); ?>">
                            <?php echo formatDate($popular['publishedAt'], 'Y.m.d'); ?>
                        </time>
                        <span class="popular-article-card__category"><?php echo h($popular['category']); ?></span>
                    </div>
                    <h3 class="popular-article-card__title"><?php echo h($popular['title']); ?></h3>
                    <?php if (!empty($popular['excerpt'])): ?>
                    <p class="popular-article-card__excerpt"><?php echo h($popular['excerpt']); ?></p>
                    <?php endif; ?>
                </div>
            </a>
        </article>
        <?php endforeach; ?>
    </div>
</section>
