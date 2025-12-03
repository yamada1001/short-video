<?php
/**
 * Index Page (トップページ)
 * くるま買取ケイヴィレッジ
 */

// デバッグ用：エラーログをファイルに出力
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');
error_reporting(E_ALL);

// デバッグ情報を記録
file_put_contents(__DIR__ . '/debug.log', "[" . date('Y-m-d H:i:s') . "] index.php started\n", FILE_APPEND);
file_put_contents(__DIR__ . '/debug.log', "PHP Version: " . phpversion() . "\n", FILE_APPEND);
file_put_contents(__DIR__ . '/debug.log', "__DIR__: " . __DIR__ . "\n", FILE_APPEND);

try {
    // 設定読み込み
    file_put_contents(__DIR__ . '/debug.log', "Loading config.php...\n", FILE_APPEND);
    require_once __DIR__ . '/data/config.php';

    file_put_contents(__DIR__ . '/debug.log', "Loading meta.php...\n", FILE_APPEND);
    require_once __DIR__ . '/data/meta.php';

    file_put_contents(__DIR__ . '/debug.log', "Loading services.php...\n", FILE_APPEND);
    require_once __DIR__ . '/data/services.php';

    file_put_contents(__DIR__ . '/debug.log', "Loading news.php...\n", FILE_APPEND);
    require_once __DIR__ . '/data/news.php';

    file_put_contents(__DIR__ . '/debug.log', "Loading functions.php...\n", FILE_APPEND);
    require_once __DIR__ . '/includes/functions.php';

    // ヘッダー読み込み
    file_put_contents(__DIR__ . '/debug.log', "Loading header.php...\n", FILE_APPEND);
    require_once __DIR__ . '/includes/header.php';

    file_put_contents(__DIR__ . '/debug.log', "All files loaded successfully!\n", FILE_APPEND);
} catch (Exception $e) {
    file_put_contents(__DIR__ . '/debug.log', "ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
    file_put_contents(__DIR__ . '/debug.log', "Stack trace: " . $e->getTraceAsString() . "\n", FILE_APPEND);
    die("エラーが発生しました。debug.logを確認してください。");
}
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
