<?php
/**
 * Sitemap Page (サイトマップページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/data/services.php';
require_once __DIR__ . '/includes/functions.php';

// ヘッダー読み込み
$page = 'sitemap';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Sitemap Hero Section -->
<section class="page-hero">
    <div class="container">
        <h1 class="page-hero__title">
            <i class="fa-solid fa-sitemap"></i>
            サイトマップ
        </h1>
        <p class="page-hero__lead">
            当サイトのページ一覧
        </p>
    </div>
</section>

<!-- Sitemap Content Section -->
<section class="section sitemap-section">
    <div class="container">
        <div class="sitemap-content">

            <!-- メインページ -->
            <div class="sitemap-group">
                <h2 class="sitemap-group__title">
                    <i class="fa-solid fa-house"></i>
                    メインページ
                </h2>
                <ul class="sitemap-list">
                    <li class="sitemap-list__item">
                        <a href="<?php echo url('index'); ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                            トップページ
                        </a>
                    </li>
                    <li class="sitemap-list__item">
                        <a href="<?php echo url('about'); ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                            会社概要
                        </a>
                    </li>
                    <li class="sitemap-list__item">
                        <a href="<?php echo url('contact'); ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                            お問い合わせ
                        </a>
                    </li>
                </ul>
            </div>

            <!-- サービス -->
            <div class="sitemap-group">
                <h2 class="sitemap-group__title">
                    <i class="fa-solid fa-car"></i>
                    サービス
                </h2>
                <ul class="sitemap-list">
                    <?php foreach ($services as $service): ?>
                    <li class="sitemap-list__item">
                        <a href="<?php echo url('index'); ?>#service-<?php echo h($service['id']); ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                            <?php echo h($service['name']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- お知らせ -->
            <div class="sitemap-group">
                <h2 class="sitemap-group__title">
                    <i class="fa-solid fa-bullhorn"></i>
                    お知らせ
                </h2>
                <ul class="sitemap-list">
                    <li class="sitemap-list__item">
                        <a href="<?php echo url('news'); ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                            お知らせ一覧
                        </a>
                    </li>
                </ul>
            </div>

            <!-- 法的ページ -->
            <div class="sitemap-group">
                <h2 class="sitemap-group__title">
                    <i class="fa-solid fa-file-lines"></i>
                    法的情報
                </h2>
                <ul class="sitemap-list">
                    <li class="sitemap-list__item">
                        <a href="<?php echo url('privacy'); ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                            プライバシーポリシー
                        </a>
                    </li>
                    <li class="sitemap-list__item">
                        <a href="<?php echo url('tokushoho'); ?>">
                            <i class="fa-solid fa-chevron-right"></i>
                            特定商取引法に基づく表記
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</section>

<!-- CTA Contact Section -->
<?php require_once __DIR__ . '/sections/cta-contact.php'; ?>

<?php
// フッター読み込み
require_once __DIR__ . '/includes/footer.php';
?>
