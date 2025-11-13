<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';

// サービス情報を取得
$services_data = file_get_contents(__DIR__ . '/includes/data/services.json');
$services_json = json_decode($services_data, true);
$services = $services_json['services'] ?? [];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="余日（Yojitsu）のサービス一覧。Web制作・ショート動画制作など、大分県を拠点にデジタルマーケティングをトータルサポート。">
    <meta name="keywords" content="Web制作,ショート動画,動画制作,ホームページ制作,大分,デジタルマーケティング">
    <title>サービス | 余日（Yojitsu）</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/cta.css">
    <link rel="stylesheet" href="assets/css/pages/services.css">
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
            <h1 class="page-header__title">サービス</h1>
            <p class="page-header__description">
                デジタルマーケティングの専門知識で、<br>
                お客様のビジネス成長をトータルサポート
            </p>
        </div>
    </section>

    <!-- サービス選択タブ -->
    <section class="service-selector">
        <div class="container">
            <div class="service-tabs">
                <button class="service-tab service-tab--active" data-target="web">
                    <i class="fas fa-laptop-code"></i>
                    <span class="service-tab__title">Web制作</span>
                    <span class="service-tab__description">Webサイト制作</span>
                </button>
                <button class="service-tab" data-target="short-video">
                    <i class="fas fa-video"></i>
                    <span class="service-tab__title">ショート動画制作</span>
                    <span class="service-tab__description">SNS向け動画制作</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Web制作 -->
    <?php
    $web_service = array_filter($services, function($s) { return $s['id'] === 'web'; });
    $web_service = reset($web_service);
    if ($web_service):
    ?>
    <section class="service-detail" id="web">
        <div class="container">
            <div class="service-detail__container">
                <div class="service-detail__content animate">
                    <div class="service-detail__icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h2 class="service-detail__title"><?php echo h($web_service['name']); ?></h2>
                    <p class="service-detail__description">
                        <?php echo h($web_service['description']); ?>
                    </p>
                    <h3>制作可能なサイト</h3>
                    <ul class="service-detail__features">
                        <?php foreach ($web_service['features'] as $feature): ?>
                            <li><?php echo h($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <h3>標準機能</h3>
                    <ul class="service-detail__features">
                        <?php foreach ($web_service['standardFeatures'] as $feature): ?>
                            <li><?php echo h($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="pricing-table">
                        <?php foreach ($web_service['pricing'] as $price): ?>
                            <div class="pricing-table__row">
                                <div class="pricing-table__label"><?php echo h($price['name']); ?></div>
                                <div class="pricing-table__value"><?php echo h($price['price']); ?></div>
                            </div>
                        <?php endforeach; ?>
                        <div class="pricing-table__row">
                            <div class="pricing-table__label">制作期間</div>
                            <div class="pricing-table__value"><?php echo h($web_service['deliveryTime']); ?></div>
                        </div>
                    </div>
                </div>
    <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ショート動画制作 -->
    <?php
    $video_service = array_filter($services, function($s) { return $s['id'] === 'video'; });
    $video_service = reset($video_service);
    if ($video_service):
    ?>
    <section class="service-detail" id="short-video">
        <div class="container">
            <div class="service-detail__container">
                <div class="service-detail__content animate">
                    <div class="service-detail__icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h2 class="service-detail__title"><?php echo h($video_service['name']); ?></h2>
                    <p class="service-detail__description">
                        <?php echo h($video_service['description']); ?>
                    </p>
                    <h3>対応プラットフォーム</h3>
                    <ul class="service-detail__features">
                        <?php foreach ($video_service['platforms'] as $platform): ?>
                            <li><?php echo h($platform); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <h3>制作内容</h3>
                    <ul class="service-detail__features">
                        <?php foreach ($video_service['productionContent'] as $content): ?>
                            <li><?php echo h($content); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="pricing-table">
                        <?php foreach ($video_service['pricing'] as $price): ?>
                            <div class="pricing-table__row">
                                <div class="pricing-table__label"><?php echo h($price['name']); ?></div>
                                <div class="pricing-table__value"><?php echo h($price['price']); ?></div>
                            </div>
                        <?php endforeach; ?>
                        <div class="pricing-table__row">
                            <div class="pricing-table__label">納期</div>
                            <div class="pricing-table__value"><?php echo h($video_service['deliveryTime']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTAセクション -->
    <?php $cta_base_path = ''; include __DIR__ . '/includes/cta.php'; ?>

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
                    <a href="services.html#web" class="footer__link"><i class="fas fa-laptop-code"></i> Web制作</a>
                    <a href="services.html#short-video" class="footer__link"><i class="fas fa-video"></i> ショート動画制作</a>
                    <a href="services.html" class="footer__link" style="margin-top: 8px; opacity: 0.8;"><i class="fas fa-arrow-right"></i> サービス詳細</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">企業情報</h3>
                    <a href="about.html" class="footer__link"><i class="fas fa-info-circle"></i> 会社概要</a>
                    <a href="recruit.php" class="footer__link"><i class="fas fa-handshake"></i> 業務委託募集・交流</a>
                    <a href="blog/" class="footer__link"><i class="fas fa-blog"></i> ブログ</a>
                    <a href="news/" class="footer__link"><i class="fas fa-newspaper"></i> お知らせ</a>
                    <a href="contact.html" class="footer__link"><i class="fas fa-envelope"></i> お問い合わせ</a>
                    <a href="privacy.html" class="footer__link"><i class="fas fa-shield-alt"></i> プライバシーポリシー</a>
                    <a href="sitemap-page.php" class="footer__link"><i class="fas fa-sitemap"></i> サイトマップ</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">お問い合わせ</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 12px; line-height: 1.9;">
                        <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:08046929681" style="color: rgba(255, 255, 255, 0.9);">080-4692-9681</a><br>
                        <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:yamada@yojitu.com" style="color: rgba(255, 255, 255, 0.9);">yamada@yojitu.com</a><br>
                        <i class="fab fa-line" style="margin-right: 8px;"></i>LINE: <a href="https://line.me/ti/p/CTOCx9YKjk" style="color: rgba(255, 255, 255, 0.9);">お問い合わせ</a>
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>営業時間: 10時~22時<br>
                        <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>定休日: なし
                    </p>
                    <div style="margin-top: 16px;">
                        <a href="contact.html" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; font-size: 14px;">お問い合わせフォーム</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="assets/js/app.js"></script>
    <script defer src="assets/js/service-tabs.js"></script>
    <!-- Cookie同意バナー -->
    <div id="cookieConsent" class="cookie-consent">
        <div class="cookie-consent__container">
            <div class="cookie-consent__content">
                <p class="cookie-consent__text">
                    当サイトは、ウェブサイトにおけるお客様の利用状況を把握するためにCookieを使用しています。「同意する」をクリックすると、当サイトでのCookieの使用に同意することになります。
                    <a href="privacy.html" class="cookie-consent__link">プライバシーポリシー</a>
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
