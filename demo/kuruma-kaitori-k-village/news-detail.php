<?php
/**
 * News Detail Page (お知らせ詳細ページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/data/news.php';
require_once __DIR__ . '/includes/functions.php';

// スラッグ取得
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// ニュース記事取得
$news_item = get_news_by_slug($slug);

// 記事が存在しない場合は404
if (!$news_item) {
    header('HTTP/1.0 404 Not Found');
    $page_title = 'お知らせが見つかりません';
} else {
    $page_title = $news_item['title'] . ' | お知らせ';
}

// 関連記事取得（同じカテゴリの記事3件）
$related_news = [];
if ($news_item) {
    $all_news = get_all_news();
    $related_news = array_filter($all_news, function($item) use ($news_item) {
        return $item['category'] === $news_item['category'] && $item['slug'] !== $news_item['slug'];
    });
    $related_news = array_slice($related_news, 0, 3);
}

// ヘッダー読み込み
$page = 'news';
require_once __DIR__ . '/includes/header.php';
?>

<?php if (!$news_item): ?>
<!-- 404 Page -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">
            <i class="fa-solid fa-exclamation-triangle"></i>
            お知らせが見つかりません
        </h1>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="error-content">
            <p>お探しのお知らせは見つかりませんでした。</p>
            <p>URLが間違っているか、記事が削除された可能性があります。</p>
            <div class="text-center mt-lg">
                <a href="<?php echo url('news'); ?>" class="btn btn--primary">
                    <i class="fa-solid fa-list"></i>
                    お知らせ一覧へ戻る
                </a>
            </div>
        </div>
    </div>
</section>

<?php else: ?>
<!-- News Detail Hero -->
<section class="news-detail-hero">
    <div class="container">
        <div class="news-detail-hero__meta">
            <div class="news-detail-hero__date">
                <i class="fa-solid fa-calendar"></i>
                <?php echo format_date($news_item['date']); ?>
            </div>
            <span class="badge <?php echo get_category_class($news_item['category']); ?>">
                <?php echo h($news_item['category']); ?>
            </span>
        </div>
        <h1 class="news-detail-hero__title"><?php echo h($news_item['title']); ?></h1>
    </div>
</section>

<!-- News Detail Content -->
<section class="section news-detail-section">
    <div class="container">
        <article class="news-detail">
            <div class="news-detail__content">
                <?php echo $news_item['content']; ?>
            </div>

            <!-- 戻るボタン -->
            <div class="news-detail__nav">
                <a href="<?php echo url('news'); ?>" class="btn btn--outline">
                    <i class="fa-solid fa-arrow-left"></i>
                    お知らせ一覧へ戻る
                </a>
            </div>
        </article>

        <!-- 関連記事 -->
        <?php if (!empty($related_news)): ?>
        <aside class="related-news">
            <h2 class="related-news__title">
                <i class="fa-solid fa-newspaper"></i>
                関連するお知らせ
            </h2>
            <div class="related-news__list">
                <?php foreach ($related_news as $item): ?>
                <article class="news-card">
                    <a href="<?php echo url('news-detail'); ?>?slug=<?php echo h($item['slug']); ?>" class="news-card__link">
                        <div class="news-card__header">
                            <div class="news-card__date">
                                <i class="fa-solid fa-calendar"></i>
                                <?php echo format_date($item['date']); ?>
                            </div>
                            <span class="badge <?php echo get_category_class($item['category']); ?>">
                                <?php echo h($item['category']); ?>
                            </span>
                        </div>
                        <h3 class="news-card__title"><?php echo h($item['title']); ?></h3>
                        <?php if (!empty($item['excerpt'])): ?>
                        <p class="news-card__excerpt"><?php echo h($item['excerpt']); ?></p>
                        <?php endif; ?>
                        <div class="news-card__footer">
                            <span class="news-card__read-more">
                                続きを読む
                                <i class="fa-solid fa-chevron-right"></i>
                            </span>
                        </div>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
        </aside>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- CTA Contact Section -->
<?php require_once __DIR__ . '/includes/cta.php'; ?>

<?php
// フッター読み込み
require_once __DIR__ . '/includes/footer.php';
?>
