<?php
/**
 * News List Page (お知らせ一覧ページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/data/news.php';
require_once __DIR__ . '/includes/functions.php';

// ページネーション設定
$per_page = 9;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

// カテゴリフィルター
$filter_category = isset($_GET['category']) ? trim($_GET['category']) : '';

// ニュースデータ取得
$all_news = get_all_news();

// カテゴリフィルタリング
if ($filter_category) {
    $filtered_news = array_filter($all_news, function($item) use ($filter_category) {
        return $item['category'] === $filter_category;
    });
    $all_news = array_values($filtered_news);
}

// 総ページ数計算
$total_items = count($all_news);
$total_pages = ceil($total_items / $per_page);

// ページネーション用のニュース取得
$offset = ($current_page - 1) * $per_page;
$news_items = array_slice($all_news, $offset, $per_page);

// カテゴリ一覧
$categories = ['お知らせ', 'キャンペーン', 'イベント', '新着車両'];

// ヘッダー読み込み
$page = 'news';
require_once __DIR__ . '/includes/header.php';
?>

<!-- News Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">
            <i class="fa-solid fa-newspaper"></i>
            お知らせ
        </h1>
        <p class="page-hero__lead">
            最新のお知らせやキャンペーン情報をお届けします
        </p>
    </div>
</section>

<!-- News List Section -->
<section class="section news-list-section">
    <div class="container">

        <!-- カテゴリフィルター -->
        <div class="category-filter">
            <a href="<?php echo url('news'); ?>" class="category-filter__item <?php echo empty($filter_category) ? 'is-active' : ''; ?>">
                <i class="fa-solid fa-border-all"></i>
                すべて
            </a>
            <?php foreach ($categories as $cat): ?>
            <a href="<?php echo url('news'); ?>?category=<?php echo urlencode($cat); ?>" class="category-filter__item <?php echo $filter_category === $cat ? 'is-active' : ''; ?>">
                <i class="fa-solid fa-tag"></i>
                <?php echo h($cat); ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($news_items)): ?>
        <!-- お知らせがない場合 -->
        <div class="news-empty">
            <i class="fa-solid fa-inbox"></i>
            <p>お知らせはありません</p>
        </div>

        <?php else: ?>
        <!-- お知らせ一覧 -->
        <div class="news-grid">
            <?php foreach ($news_items as $item): ?>
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

        <!-- ページネーション -->
        <?php if ($total_pages > 1): ?>
        <nav class="pagination" aria-label="ページネーション">
            <ul class="pagination__list">
                <?php if ($current_page > 1): ?>
                <li class="pagination__item">
                    <a href="<?php echo url('news'); ?>?page=<?php echo $current_page - 1; ?><?php echo $filter_category ? '&category=' . urlencode($filter_category) : ''; ?>" class="pagination__link">
                        <i class="fa-solid fa-chevron-left"></i>
                        前へ
                    </a>
                </li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if ($i === 1 || $i === $total_pages || abs($i - $current_page) <= 2): ?>
                    <li class="pagination__item">
                        <a href="<?php echo url('news'); ?>?page=<?php echo $i; ?><?php echo $filter_category ? '&category=' . urlencode($filter_category) : ''; ?>" class="pagination__link <?php echo $i === $current_page ? 'is-active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php elseif (abs($i - $current_page) === 3): ?>
                    <li class="pagination__item pagination__item--ellipsis">
                        <span>...</span>
                    </li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                <li class="pagination__item">
                    <a href="<?php echo url('news'); ?>?page=<?php echo $current_page + 1; ?><?php echo $filter_category ? '&category=' . urlencode($filter_category) : ''; ?>" class="pagination__link">
                        次へ
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
        <?php endif; ?>
        <?php endif; ?>

    </div>
</section>

<!-- CTA Contact Section -->
<?php require_once __DIR__ . '/includes/cta.php'; ?>

<?php
// フッター読み込み
require_once __DIR__ . '/includes/footer.php';
?>
