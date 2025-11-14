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
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/cta.css">
    <link rel="stylesheet" href="assets/css/cookie-consent.css">

    <!-- Font Awesome - Async load -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- Google Tag Manager - Async -->
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-T7NGQDC2"></script>

    <style>
        .page-header {
            background: linear-gradient(135deg, var(--color-natural-brown) 0%, var(--color-charcoal) 100%);
            padding: var(--spacing-xxl) 0;
            text-align: center;
            color: var(--color-bg-white);
        }

        .page-header__title {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: var(--spacing-md);
            letter-spacing: 0.05em;
        }

        .page-header__description {
            font-size: 18px;
            line-height: 1.8;
            opacity: 0.95;
            max-width: 800px;
            margin: 0 auto;
        }

        .services-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-white);
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: var(--spacing-xxl);
            max-width: 1000px;
            margin: 0 auto;
        }

        .service-card {
            background: var(--color-bg-white);
            border: 2px solid var(--color-border);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
            position: relative;
        }

        .service-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .service-card--web:hover {
            border-color: var(--color-natural-brown);
        }

        .service-card--video:hover {
            border-color: #E1306C;
        }

        .service-card__header {
            padding: var(--spacing-xxl);
            text-align: center;
            position: relative;
        }

        .service-card--web .service-card__header {
            background: linear-gradient(135deg, var(--color-natural-brown) 0%, var(--color-charcoal) 100%);
        }

        .service-card--video .service-card__header {
            background: linear-gradient(135deg, #E1306C 0%, #405DE6 50%, #5B51D8 100%);
        }

        .service-card__icon {
            font-size: 72px;
            color: var(--color-bg-white);
            margin-bottom: var(--spacing-md);
        }

        .service-card__title {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-bg-white);
            margin-bottom: var(--spacing-sm);
        }

        .service-card__subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.1em;
        }

        .service-card__content {
            padding: var(--spacing-xl);
        }

        .service-card__description {
            font-size: 15px;
            line-height: 1.9;
            color: var(--color-text);
            margin-bottom: var(--spacing-lg);
        }

        .service-card__features {
            list-style: none;
            margin-bottom: var(--spacing-xl);
        }

        .service-card__features li {
            padding: var(--spacing-sm) 0;
            display: flex;
            align-items: start;
            gap: var(--spacing-sm);
            font-size: 14px;
            color: var(--color-text);
        }

        .service-card__features li i {
            margin-top: 4px;
            flex-shrink: 0;
        }

        .service-card--web .service-card__features li i {
            color: var(--color-natural-brown);
        }

        .service-card--video .service-card__features li i {
            color: #E1306C;
        }

        .service-card__cta {
            text-align: center;
        }

        .service-card__link {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-md) var(--spacing-xl);
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .service-card--web .service-card__link {
            background: var(--color-natural-brown);
            color: var(--color-bg-white);
        }

        .service-card--web .service-card__link:hover {
            background: var(--color-charcoal);
            gap: var(--spacing-md);
        }

        .service-card--video .service-card__link {
            background: linear-gradient(45deg, #F58529, #E1306C);
            color: var(--color-bg-white);
        }

        .service-card--video .service-card__link:hover {
            background: linear-gradient(45deg, #E1306C, #5B51D8);
            gap: var(--spacing-md);
        }

        .compare-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-gray);
        }

        .compare-section__title {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: var(--spacing-xl);
            color: var(--color-charcoal);
        }

        .compare-table {
            max-width: 900px;
            margin: 0 auto;
            background: var(--color-bg-white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .compare-table__row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            border-bottom: 1px solid var(--color-border);
        }

        .compare-table__row:last-child {
            border-bottom: none;
        }

        .compare-table__cell {
            padding: var(--spacing-lg);
            text-align: center;
        }

        .compare-table__cell--header {
            background: var(--color-bg-gray);
            font-weight: 700;
            font-size: 16px;
            color: var(--color-charcoal);
        }

        .compare-table__cell--label {
            background: var(--color-bg-gray);
            font-weight: 600;
            text-align: left;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .compare-table__cell i {
            color: var(--color-natural-brown);
        }

        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: 1fr;
            }

            .compare-table__row {
                grid-template-columns: 1fr;
            }

            .compare-table__cell--header:first-child {
                display: none;
            }

            .compare-table__cell--header {
                background: var(--color-natural-brown);
                color: var(--color-bg-white);
            }
        }
    </style>
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
            <h1 class="page-header__title">
                <i class="fas fa-briefcase"></i> サービス
            </h1>
            <p class="page-header__description">
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

    <!-- サービス比較表 -->
    <section class="compare-section">
        <div class="container">
            <h2 class="compare-section__title">
                <i class="fas fa-balance-scale"></i> サービス比較
            </h2>

            <div class="compare-table">
                <div class="compare-table__row">
                    <div class="compare-table__cell compare-table__cell--header"></div>
                    <div class="compare-table__cell compare-table__cell--header">
                        <i class="fas fa-laptop-code"></i> Webサイト制作
                    </div>
                    <div class="compare-table__cell compare-table__cell--header">
                        <i class="fas fa-video"></i> ショート動画制作
                    </div>
                </div>

                <div class="compare-table__row">
                    <div class="compare-table__cell compare-table__cell--label">
                        <i class="fas fa-yen-sign"></i> 最低価格
                    </div>
                    <div class="compare-table__cell">10万円〜</div>
                    <div class="compare-table__cell">5,000円〜</div>
                </div>

                <div class="compare-table__row">
                    <div class="compare-table__cell compare-table__cell--label">
                        <i class="fas fa-clock"></i> 納期
                    </div>
                    <div class="compare-table__cell">2週間〜2ヶ月</div>
                    <div class="compare-table__cell">2〜5営業日</div>
                </div>

                <div class="compare-table__row">
                    <div class="compare-table__cell compare-table__cell--label">
                        <i class="fas fa-bullseye"></i> 最適な用途
                    </div>
                    <div class="compare-table__cell">会社の信頼性向上<br>長期的な集客</div>
                    <div class="compare-table__cell">認知度向上<br>SNSマーケティング</div>
                </div>

                <div class="compare-table__row">
                    <div class="compare-table__cell compare-table__cell--label">
                        <i class="fas fa-chart-line"></i> 効果の出方
                    </div>
                    <div class="compare-table__cell">じっくり資産化</div>
                    <div class="compare-table__cell">短期間でバズる可能性</div>
                </div>

                <div class="compare-table__row">
                    <div class="compare-table__cell compare-table__cell--label">
                        <i class="fas fa-tools"></i> 運用の手間
                    </div>
                    <div class="compare-table__cell">低い<br>（一度作れば長期利用）</div>
                    <div class="compare-table__cell">定期的な投稿が必要</div>
                </div>
            </div>

            <div style="text-align: center; margin-top: var(--spacing-xxl);">
                <p style="margin-bottom: var(--spacing-md); color: var(--color-text-light);">
                    <i class="fas fa-lightbulb"></i> <strong>おすすめの組み合わせ:</strong> Webサイトで信頼性を確保し、ショート動画で認知度を拡大
                </p>
            </div>
        </div>
    </section>

    <!-- CTAセクション -->
    <?php $cta_base_path = ''; include __DIR__ . '/includes/cta.php'; ?>

    <!-- フッター -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script defer src="assets/js/app.js"></script>

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
