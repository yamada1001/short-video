<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'サービス | 余日（Yojitsu）';
$page_description = '余日（Yojitsu）のサービス一覧。Web制作・ショート動画制作など、大分県を拠点にデジタルマーケティングをトータルサポート。';
$page_keywords = 'Web制作,ショート動画,動画制作,ホームページ制作,大分,デジタルマーケティング';
$additional_css = ['assets/css/cookie-consent.css'];

$inline_styles = <<<'EOD'
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
            color: var(--color-bg-white);
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
            border-color: var(--color-natural-brown);
        }

        .service-card__header {
            padding: var(--spacing-xxl);
            text-align: center;
            position: relative;
        }

        .service-card--web .service-card__header {
            background: var(--color-natural-brown);
        }

        .service-card--video .service-card__header {
            background: var(--color-charcoal);
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
            color: var(--color-natural-brown);
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
            background: var(--color-charcoal);
            color: var(--color-bg-white);
        }

        .service-card--video .service-card__link:hover {
            background: var(--color-natural-brown);
            gap: var(--spacing-md);
        }

        /* タブ切り替え */
        .service-tabs {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .service-tab {
            padding: 12px 24px;
            border: 1px solid var(--color-natural-brown);
            background: transparent;
            color: var(--color-natural-brown);
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .service-tab.active {
            background: var(--color-natural-brown);
            color: #fff;
        }

        /* カード切り替え */
        .services-grid.has-tabs .service-card {
            display: none;
        }

        .services-grid.has-tabs .service-card.active {
            display: block;
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
EOD;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
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
            <!-- モバイル用タブ -->
            <div class="service-tabs">
                <button class="service-tab active" data-service="web" onclick="switchService('web')">Web制作</button>
                <button class="service-tab" data-service="video" onclick="switchService('video')">ショート動画</button>
            </div>

            <div class="services-grid has-tabs">
                <!-- Webサイト制作 -->
                <div class="service-card service-card--web active" data-service="web">
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
                <div class="service-card service-card--video" data-service="video">
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
    <?php
    $cta_base_path = '';
    $cta_show_info = true;
    include __DIR__ . '/includes/cta.php';
    ?>

    <!-- フッター -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <script defer src="assets/js/app.js"></script>

    <script>
    function switchService(serviceId) {
        // タブの切り替え
        document.querySelectorAll('.service-tab').forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.service === serviceId) {
                tab.classList.add('active');
            }
        });
        // カードの切り替え
        document.querySelectorAll('.service-card[data-service]').forEach(card => {
            card.classList.remove('active');
            if (card.dataset.service === serviceId) {
                card.classList.add('active');
            }
        });
    }
    </script>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

</body>
</html>
