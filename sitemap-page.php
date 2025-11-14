<?php
$current_page = '';
require_once __DIR__ . '/includes/functions.php';

// ブログ記事を取得
$posts = getPosts(BLOG_DATA_PATH);

// カテゴリごとに記事を分類
$categories = [];
foreach ($posts as $post) {
    $category = $post['category'];
    if (!isset($categories[$category])) {
        $categories[$category] = [];
    }
    $categories[$category][] = $post;
}

// 各カテゴリ内で新しい順にソート
foreach ($categories as &$category_posts) {
    usort($category_posts, function($a, $b) {
        return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
    });
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="余日（Yojitsu）のサイトマップ。全ページへのリンクを掲載しています。">
    <title>サイトマップ | 余日（Yojitsu）</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/sitemap.css">
    <link rel="stylesheet" href="assets/css/cookie-consent.css">

    <!-- Font Awesome - Async load -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- Google Tag Manager - Async -->
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-T7NGQDC2"></script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-header__title">サイトマップ</h1>
            <p class="page-header__description">
                当サイト内の全ページへのリンク集です
            </p>
        </div>
    </section>

    <!-- サイトマップコンテンツ -->
    <section class="sitemap-content">
        <div class="container">
            <div class="sitemap-grid">
                <!-- メインページ -->
                <div class="sitemap-section">
                    <h2 class="sitemap-section__title">
                        <i class="fas fa-home"></i> メインページ
                    </h2>
                    <ul class="sitemap-list">
                        <li><a href="index.php" class="sitemap-link">トップページ</a></li>
                        <li><a href="services.php" class="sitemap-link">サービス</a></li>
                        <li><a href="about.php" class="sitemap-link">会社概要</a></li>
                        <li><a href="contact.php" class="sitemap-link">お問い合わせ</a></li>
                    </ul>
                </div>

                <!-- ブログ -->
                <div class="sitemap-section">
                    <h2 class="sitemap-section__title">
                        <i class="fas fa-blog"></i> ブログ
                    </h2>
                    <ul class="sitemap-list">
                        <li><a href="blog/" class="sitemap-link">ブログトップ</a></li>
                    </ul>

                    <?php foreach ($categories as $category_name => $category_posts): ?>
                    <h3 class="sitemap-category"><?php echo h($category_name); ?></h3>
                    <ul class="sitemap-list sitemap-list--nested">
                        <?php foreach (array_slice($category_posts, 0, 10) as $post): ?>
                        <li>
                            <a href="blog/detail.php?slug=<?php echo urlencode($post['slug']); ?>" class="sitemap-link sitemap-link--small">
                                <?php echo h($post['title']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <?php if (count($category_posts) > 10): ?>
                        <li class="sitemap-more">
                            <a href="blog/?category=<?php echo urlencode($category_name); ?>" class="sitemap-link sitemap-link--more">
                                <?php echo $category_name; ?>の記事をもっと見る（<?php echo count($category_posts); ?>件）
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <?php endforeach; ?>
                </div>

                <!-- その他 -->
                <div class="sitemap-section">
                    <h2 class="sitemap-section__title">
                        <i class="fas fa-file-alt"></i> その他
                    </h2>
                    <ul class="sitemap-list">
                        <li><a href="privacy.php" class="sitemap-link">プライバシーポリシー</a></li>
                        <li><a href="sitemap-page.php" class="sitemap-link">サイトマップ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- フッター -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h3 class="footer__section-title">余日（Yojitsu）</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 16px; line-height: 1.9;">
                        大分県を拠点に、SEO・広告運用・Web制作・ショート動画制作を提供するデジタルマーケティング会社です。
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-building" style="margin-right: 8px;"></i>屋号: 余日（Yojitsu）<br>
                        <i class="fas fa-file-invoice" style="margin-right: 8px;"></i>登録番号: T9810094141774<br>
                        <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>設立: 令和7年5月14日
                    </p>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">サービス</h3>
                    <a href="services.php#seo" class="footer__link"><i class="fas fa-search"></i> SEO対策</a>
                    <a href="services.php#ads" class="footer__link"><i class="fas fa-bullhorn"></i> 広告運用</a>
                    <a href="services.php#web" class="footer__link"><i class="fas fa-laptop-code"></i> Web制作</a>
                    <a href="services.php#short-video" class="footer__link"><i class="fas fa-video"></i> ショート動画制作</a>
                    <a href="services.php" class="footer__link" style="margin-top: 8px; opacity: 0.8;"><i class="fas fa-arrow-right"></i> サービス詳細</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">企業情報</h3>
                    <a href="about.php" class="footer__link"><i class="fas fa-info-circle"></i> 会社概要</a>
                    <a href="recruit.php" class="footer__link"><i class="fas fa-handshake"></i> 業務委託募集・交流</a>
                    <a href="blog/" class="footer__link"><i class="fas fa-blog"></i> ブログ</a>
                    <a href="news/" class="footer__link"><i class="fas fa-newspaper"></i> お知らせ</a>
                    <a href="contact.php" class="footer__link"><i class="fas fa-envelope"></i> お問い合わせ</a>
                    <a href="privacy.php" class="footer__link"><i class="fas fa-shield-alt"></i> プライバシーポリシー</a>
                    <a href="sitemap-page.php" class="footer__link"><i class="fas fa-sitemap"></i> サイトマップ</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">お問い合わせ</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 12px; line-height: 1.9;">
                        <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_TEL; ?></a><br>
                        <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_EMAIL; ?></a><br>
                        <i class="fab fa-line" style="margin-right: 8px;"></i>LINE: <a href="https://line.me/ti/p/CTOCx9YKjk" style="color: rgba(255, 255, 255, 0.9);">お問い合わせ</a>
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>営業時間: 10時~22時<br>
                        <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>定休日: なし
                    </p>
                    <div style="margin-top: 16px;">
                        <a href="contact.php" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; font-size: 14px;">お問い合わせフォーム</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="assets/js/app.js"></script>
    <!-- Cookie同意バナー -->
    <div id="cookieConsent" class="cookie-consent">
        <div class="cookie-consent__container">
            <div class="cookie-consent__content">
                <p class="cookie-consent__text">
                    当サイトは、ウェブサイトにおけるお客様の利用状況を把握するためにCookieを使用しています。「同意する」をクリックすると、当サイトでのCookieの使用に同意することになります。
                    <a href="privacy.php" class="cookie-consent__link">プライバシーポリシー</a>
                </p>
            </div>
            <div class="cookie-consent__actions">
                <button id="acceptCookies" class="cookie-consent__button cookie-consent__button--accept">同意する</button>
                <button id="declineCookies" class="cookie-consent__button cookie-consent__button--decline">拒否する</button>
            </div>
        </div>
    </div>

</body>
</html>
