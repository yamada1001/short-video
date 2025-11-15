<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TikTok、Instagram Reels、YouTube Shortsに最適化したショート動画制作。企画から撮影、編集まで一貫サポート。余日（Yojitsu）">
    <meta name="keywords" content="ショート動画,TikTok,Instagram Reels,YouTube Shorts,動画制作,大分,余日">
    <title>ショート動画制作 - SNSで成果を出す動画マーケティング | 余日（Yojitsu）</title>

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
    <link rel="stylesheet" href="assets/css/pages/video-production.css">

    <!-- Font Awesome - Async load -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- Google Tag Manager - Async -->
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-T7NGQDC2"></script>

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Service",
      "serviceType": "ショート動画制作",
      "provider": {
        "@type": "LocalBusiness",
        "name": "余日（Yojitsu）",
        "telephone": "<?php echo CONTACT_TEL; ?>",
        "email": "<?php echo CONTACT_EMAIL; ?>",
        "address": {
          "@type": "PostalAddress",
          "addressRegion": "大分県",
          "addressCountry": "JP"
        }
      },
      "areaServed": {
        "@type": "Country",
        "name": "日本"
      },
      "offers": [
        {
          "@type": "Offer",
          "name": "基本プラン",
          "price": "20000",
          "priceCurrency": "JPY",
          "description": "15〜60秒の動画1本"
        },
        {
          "@type": "Offer",
          "name": "10本セット",
          "price": "150000",
          "priceCurrency": "JPY",
          "description": "15〜60秒の動画10本（25%OFF）"
        },
        {
          "@type": "Offer",
          "name": "企画案作成のみ",
          "price": "5000",
          "priceCurrency": "JPY",
          "description": "動画コンセプト・構成案の提案"
        }
      ]
    }
    </script>
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
            <span class="page-hero__label">Video Production</span>
            <h1 class="page-hero__title">
                <i class="fas fa-video"></i> ショート動画制作
            </h1>
            <p class="page-hero__description">
                TikTok、Instagram Reels、YouTube Shortsで成果を出す。<br>
                企画から撮影、編集まで一貫してサポートします。
            </p>
        </div>
    </section>

    <!-- 対応プラットフォーム -->
    <section class="platforms-section">
        <div class="container">
            <h2 class="section__subtitle">
                <i class="fas fa-mobile-alt"></i> 対応プラットフォーム
            </h2>

            <div class="platforms-grid">
                <div class="platform-card platform-card--tiktok">
                    <div class="platform-card__icon">
                        <i class="fab fa-tiktok"></i>
                    </div>
                    <h3 class="platform-card__name">TikTok</h3>
                    <p class="platform-card__description">
                        15秒〜3分の縦型動画。Z世代を中心に爆発的な人気。トレンドを活かした企画で認知度アップ。
                    </p>
                </div>

                <div class="platform-card platform-card--instagram">
                    <div class="platform-card__icon">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <h3 class="platform-card__name">Instagram Reels</h3>
                    <p class="platform-card__description">
                        15秒〜90秒の縦型動画。ビジュアル重視のプラットフォーム。ブランディングに最適。
                    </p>
                </div>

                <div class="platform-card platform-card--youtube">
                    <div class="platform-card__icon">
                        <i class="fab fa-youtube"></i>
                    </div>
                    <h3 class="platform-card__name">YouTube Shorts</h3>
                    <p class="platform-card__description">
                        60秒以内の縦型動画。世界最大の動画プラットフォーム。長期的な資産として蓄積。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- なぜショート動画が必要か -->
    <section class="stats-section">
        <div class="container">
            <h2 class="section__subtitle" style="color: var(--color-bg-white);">
                <i class="fas fa-chart-line"></i> なぜ今、ショート動画なのか
            </h2>

            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-item__number">89%</div>
                    <div class="stat-item__label">ユーザーが動画を見て購入を決定</div>
                </div>

                <div class="stat-item">
                    <div class="stat-item__number">2倍</div>
                    <div class="stat-item__label">テキストの2倍記憶に残る</div>
                </div>

                <div class="stat-item">
                    <div class="stat-item__number">60秒</div>
                    <div class="stat-item__label">最後まで視聴される最適な長さ</div>
                </div>

                <div class="stat-item">
                    <div class="stat-item__number">3.5億</div>
                    <div class="stat-item__label">TikTok国内月間アクティブユーザー</div>
                </div>
            </div>
        </div>
    </section>

    <!-- 料金プラン -->
    <section class="pricing-section">
        <div class="container">
            <h2 class="section__subtitle">
                <i class="fas fa-tags"></i> 料金プラン
            </h2>

            <div class="highlight-box">
                <p><i class="fas fa-info-circle"></i> 初めての方は、まず企画案作成のみのプランで動画コンセプトを固めるのがおすすめです。</p>
            </div>

            <div class="pricing-grid">
                <!-- 基本プラン -->
                <div class="pricing-card">
                    <div class="pricing-card__icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3 class="pricing-card__name">基本プラン</h3>
                    <div class="pricing-card__price">
                        ¥20,000<span class="pricing-card__price-unit">/1本</span>
                    </div>
                    <div class="pricing-card__description">
                        15〜60秒の動画1本
                    </div>
                    <ul class="pricing-card__features">
                        <li><i class="fas fa-check-circle"></i> <span>企画・構成案作成</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>撮影（必要に応じて）</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>動画編集</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>テロップ・BGM挿入</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>2回まで修正対応</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>納期: 3〜5営業日</span></li>
                    </ul>
                    <div class="pricing-card__cta">
                        <a href="contact.php" class="btn btn-secondary btn--large" style="width: 100%;">
                            <i class="fas fa-envelope"></i> お問い合わせ
                        </a>
                    </div>
                </div>

                <!-- 10本セット -->
                <div class="pricing-card pricing-card--featured">
                    <div class="pricing-card__badge">25% OFF</div>
                    <div class="pricing-card__icon">
                        <i class="fas fa-film"></i>
                    </div>
                    <h3 class="pricing-card__name">10本セット</h3>
                    <div class="pricing-card__price">
                        ¥150,000<span class="pricing-card__price-unit">/10本</span>
                    </div>
                    <div class="pricing-card__description">
                        15〜60秒の動画10本（1本あたり15,000円）
                    </div>
                    <ul class="pricing-card__features">
                        <li><i class="fas fa-check-circle"></i> <span>基本プランの全機能</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>統一感のあるシリーズ企画</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>投稿スケジュール提案</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>効果測定レポート（初回のみ）</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>3ヶ月間の分割納品対応</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>優先対応</span></li>
                    </ul>
                    <div class="pricing-card__cta">
                        <a href="contact.php" class="btn btn-primary btn--large" style="width: 100%;">
                            <i class="fas fa-envelope"></i> お問い合わせ
                        </a>
                    </div>
                </div>

                <!-- 企画案作成のみ -->
                <div class="pricing-card">
                    <div class="pricing-card__icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="pricing-card__name">企画案作成のみ</h3>
                    <div class="pricing-card__price">
                        ¥5,000<span class="pricing-card__price-unit"></span>
                    </div>
                    <div class="pricing-card__description">
                        動画コンセプト・構成案の提案
                    </div>
                    <ul class="pricing-card__features">
                        <li><i class="fas fa-check-circle"></i> <span>ターゲット分析</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>動画コンセプト設計</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>構成案（絵コンテ）作成</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>推奨ハッシュタグ提案</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>投稿タイミング提案</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>納期: 2営業日</span></li>
                    </ul>
                    <div class="pricing-card__cta">
                        <a href="contact.php" class="btn btn-secondary btn--large" style="width: 100%;">
                            <i class="fas fa-envelope"></i> お問い合わせ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 制作フロー -->
    <section class="process-section">
        <div class="container">
            <h2 class="section__subtitle">
                <i class="fas fa-tasks"></i> 制作フロー
            </h2>

            <div class="process-timeline">
                <div class="process-step">
                    <div class="process-step__number">1</div>
                    <h3 class="process-step__title">
                        <i class="fas fa-comments"></i> ヒアリング
                    </h3>
                    <p class="process-step__description">
                        目的、ターゲット、訴求したいポイントなどを詳しくお伺いします。競合調査も実施し、差別化ポイントを明確にします。
                    </p>
                </div>

                <div class="process-step">
                    <div class="process-step__number">2</div>
                    <h3 class="process-step__title">
                        <i class="fas fa-pencil-alt"></i> 企画・構成案作成
                    </h3>
                    <p class="process-step__description">
                        ヒアリング内容をもとに、動画のコンセプトと構成案（絵コンテ）を作成。テキスト、BGM、エフェクトの方向性を決定します。
                    </p>
                </div>

                <div class="process-step">
                    <div class="process-step__number">3</div>
                    <h3 class="process-step__title">
                        <i class="fas fa-camera"></i> 撮影
                    </h3>
                    <p class="process-step__description">
                        必要に応じて撮影を行います。お客様ご自身で撮影いただいた素材の編集も可能です。撮影代行の場合は別途お見積りいたします。
                    </p>
                </div>

                <div class="process-step">
                    <div class="process-step__number">4</div>
                    <h3 class="process-step__title">
                        <i class="fas fa-cut"></i> 編集
                    </h3>
                    <p class="process-step__description">
                        カット編集、テロップ挿入、BGM選定、カラーグレーディングなど、プラットフォームに最適化した編集を行います。
                    </p>
                </div>

                <div class="process-step">
                    <div class="process-step__number">5</div>
                    <h3 class="process-step__title">
                        <i class="fas fa-check-double"></i> 修正・納品
                    </h3>
                    <p class="process-step__description">
                        初稿をご確認いただき、修正があれば対応（2回まで無料）。完成したら各プラットフォームに最適な形式で納品します。
                    </p>
                </div>

                <div class="process-step">
                    <div class="process-step__number">6</div>
                    <h3 class="process-step__title">
                        <i class="fas fa-chart-bar"></i> 効果測定（オプション）
                    </h3>
                    <p class="process-step__description">
                        投稿後の視聴回数、いいね数、コメント、シェア数などを分析し、次回以降の改善提案を行います（10本セットに含む）。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ショート動画制作のメリット -->
    <section class="benefits-section">
        <div class="container">
            <h2 class="section__subtitle">
                <i class="fas fa-thumbs-up"></i> ショート動画制作のメリット
            </h2>

            <div class="benefits-grid">
                <div class="benefit-card">
                    <div class="benefit-card__icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3 class="benefit-card__title">圧倒的な視認性</h3>
                    <p class="benefit-card__description">
                        テキストの60,000倍の情報量。わずか数秒で商品・サービスの魅力を伝えられます。
                    </p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-card__icon">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <h3 class="benefit-card__title">拡散力の高さ</h3>
                    <p class="benefit-card__description">
                        アルゴリズムによる自動拡散で、フォロワーが少なくてもバズる可能性。シェアされやすい形式です。
                    </p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-card__icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="benefit-card__title">スマホ時代に最適</h3>
                    <p class="benefit-card__description">
                        縦型フルスクリーン表示で没入感が高く、移動中やスキマ時間に視聴されやすい形式です。
                    </p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-card__icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="benefit-card__title">短時間で制作可能</h3>
                    <p class="benefit-card__description">
                        長尺動画と比べて企画・撮影・編集の工数が少なく、スピーディーに配信開始できます。
                    </p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-card__icon">
                        <i class="fas fa-yen-sign"></i>
                    </div>
                    <h3 class="benefit-card__title">コストパフォーマンス</h3>
                    <p class="benefit-card__description">
                        低予算で始められ、広告費をかけずとも自然にリーチを獲得できる可能性があります。
                    </p>
                </div>

                <div class="benefit-card">
                    <div class="benefit-card__icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="benefit-card__title">効果測定がしやすい</h3>
                    <p class="benefit-card__description">
                        視聴回数、視聴完了率、エンゲージメント率など、詳細なデータを元に改善できます。
                    </p>
                </div>
            </div>

            <div style="text-align: center; margin-top: var(--spacing-xxl);">
                <p style="margin-bottom: var(--spacing-md); color: var(--color-text-light);">
                    <i class="fas fa-link"></i> 他のサービスと組み合わせてさらに効果的に
                </p>
                <a href="web-production.php" class="btn btn-secondary" style="margin-right: var(--spacing-md);">
                    <i class="fas fa-laptop-code"></i> Webサイト制作を見る
                </a>
                <a href="services.php" class="btn btn-secondary">
                    <i class="fas fa-list"></i> 全サービス一覧
                </a>
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

    <!-- GSAP Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <!-- Page Scripts -->
    <script defer src="assets/js/app.js"></script>
    <script defer src="assets/js/video-production.js"></script>

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
