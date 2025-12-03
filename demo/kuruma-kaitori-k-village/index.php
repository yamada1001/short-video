<?php
/**
 * Index Page (トップページ)
 * くるま買取ケイヴィレッジ
 */

// エラー表示（開発用）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/data/services.php';
require_once __DIR__ . '/data/news.php';
require_once __DIR__ . '/includes/functions.php';

// ヘッダー読み込み
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<?php require_once __DIR__ . '/sections/hero.php'; ?>

<!-- Services Overview Section -->
<?php require_once __DIR__ . '/sections/services-overview.php'; ?>

<!-- Strengths Section -->
<?php require_once __DIR__ . '/sections/strengths.php'; ?>

<!-- News Section -->
<section class="section news-section">
    <div class="container">
        <h2 class="section__title">お知らせ</h2>
        <p class="section__lead">
            最新のお知らせやキャンペーン情報をご覧いただけます
        </p>

        <div class="news-list">
            <?php
            $latest_news = get_latest_news(3);
            foreach ($latest_news as $item):
            ?>
            <article class="news-item">
                <a href="<?php echo url('news-detail'); ?>?slug=<?php echo h($item['slug']); ?>" class="news-item__link">
                    <div class="news-item__date">
                        <i class="fa-solid fa-calendar"></i>
                        <?php echo format_date($item['date']); ?>
                    </div>
                    <div class="news-item__category">
                        <span class="badge <?php echo get_category_class($item['category']); ?>">
                            <?php echo h($item['category']); ?>
                        </span>
                    </div>
                    <h3 class="news-item__title"><?php echo h($item['title']); ?></h3>
                    <div class="news-item__arrow">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-lg">
            <a href="<?php echo url('news'); ?>" class="btn btn--outline">
                <i class="fa-solid fa-list"></i>
                お知らせ一覧を見る
            </a>
        </div>
    </div>
</section>

<!-- CTA Contact Section -->
<?php require_once __DIR__ . '/sections/cta-contact.php'; ?>

<!-- Company Info Section -->
<?php require_once __DIR__ . '/sections/company-info.php'; ?>

<?php
// フッター読み込み
require_once __DIR__ . '/includes/footer.php';
?>
