<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <?php require_once __DIR__ . '/includes/favicon.php'; ?>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/cta.css">
    <link rel="stylesheet" href="assets/css/cookie-consent.css">
    <link rel="stylesheet" href="assets/css/pages/services.css">

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

    <!-- ページヒーロー -->
    <section class="page-hero">
        <div class="page-hero__bg">
            <div class="page-hero__shape page-hero__shape--1"></div>
            <div class="page-hero__shape page-hero__shape--2"></div>
        </div>
        <div class="page-hero__container">
            <span class="page-hero__label">Our Services</span>
            <h1 class="page-hero__title">
                <i class="fas fa-briefcase"></i> サービス
            </h1>
            <p class="page-hero__description">
                デジタルマーケティングの専門知識で、<br>
                お客様のビジネス成長をトータルサポート
            </p>
        </div>
    </section>

    <!-- サービス一覧 -->
    <section class="services-section">
        <div class="container">
            <div class="services-grid">
                <!-- Webサイト制作 -->
                <div class="service-card service-card--web">
                    <div class="service-card__header">
                        <div class="service-card__icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h2 class="service-card__title">Webサイト制作</h2>
                        <p class="service-card__subtitle">WEB PRODUCTION</p>
                    </div>
                    <div class="service-card__content">
                        <p class="service-card__description">
                            10万円から始める、プロフェッショナルなWeb制作。個人事業主から中小企業まで、目的に応じた最適なプランをご提供します。
                        </p>
                        <ul class="service-card__features">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>10万円プラン - シンプルで早い</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>30万円プラン - ブログ機能付き</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>カスタムプラン - 本格的な開発</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>レスポンシブデザイン標準装備</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>SEO対策・アクセス解析設定</span>
                            </li>
                        </ul>
                        <div class="service-card__cta">
                            <a href="web-production.php" class="service-card__link">
                                <span>詳しく見る</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ショート動画制作 -->
                <div class="service-card service-card--video">
                    <div class="service-card__header">
                        <div class="service-card__icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <h2 class="service-card__title">ショート動画制作</h2>
                        <p class="service-card__subtitle">SHORT VIDEO PRODUCTION</p>
                    </div>
                    <div class="service-card__content">
                        <p class="service-card__description">
                            TikTok、Instagram Reels、YouTube Shortsに最適化したショート動画を制作。企画から撮影、編集まで一貫してサポートします。
                        </p>
                        <ul class="service-card__features">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>基本プラン - 2万円/1本</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>10本セット - 15万円（25%OFF）</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>企画案作成のみ - 5千円</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>TikTok/Instagram/YouTube対応</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>テロップ・BGM・エフェクト込み</span>
                            </li>
                        </ul>
                        <div class="service-card__cta">
                            <a href="video-production.php" class="service-card__link">
                                <span>詳しく見る</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTAセクション -->
    <?php $cta_base_path = ''; include __DIR__ . '/includes/cta.php'; ?>

    <!-- フッター -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <!-- GSAP Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <!-- Page Scripts -->
    <script defer src="assets/js/app.js"></script>
    <script defer src="assets/js/services.js"></script>

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
