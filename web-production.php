<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="10万円からのWebサイト制作。個人事業主から中小企業まで、目的に応じた最適なプランをご提供。余日（Yojitsu）">
    <meta name="keywords" content="Web制作,ホームページ制作,格安,大分,余日,10万円,30万円,レスポンシブ">
    <title>Webサイト制作 - 10万円から始めるプロフェッショナルなWeb制作 | 余日（Yojitsu）</title>

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

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Service",
      "serviceType": "Webサイト制作",
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
          "name": "10万円プラン",
          "price": "100000",
          "priceCurrency": "JPY",
          "description": "個人事業主の方や安く早く作りたい方向け"
        },
        {
          "@type": "Offer",
          "name": "30万円プラン",
          "price": "300000",
          "priceCurrency": "JPY",
          "description": "ブログ機能やWebからの集客を目指す方向け"
        },
        {
          "@type": "Offer",
          "name": "カスタムプラン",
          "priceRange": "500万円〜1200万円",
          "priceCurrency": "JPY",
          "description": "オリジナルデザイン・CMS開発など本格的なWeb制作"
        }
      ]
    }
    </script>

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

        .pricing-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-white);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing-xl);
            margin-top: var(--spacing-xl);
        }

        .pricing-card {
            background: var(--color-bg-white);
            border: 2px solid var(--color-border);
            border-radius: 12px;
            padding: var(--spacing-xl);
            transition: all 0.3s ease;
            position: relative;
        }

        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
            border-color: var(--color-natural-brown);
        }

        .pricing-card--featured {
            border-color: var(--color-natural-brown);
            border-width: 3px;
            box-shadow: 0 8px 24px rgba(139, 115, 85, 0.15);
        }

        .pricing-card__badge {
            position: absolute;
            top: -12px;
            right: var(--spacing-lg);
            background: var(--color-natural-brown);
            color: var(--color-bg-white);
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .pricing-card__icon {
            font-size: 48px;
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-md);
        }

        .pricing-card__name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
            color: var(--color-charcoal);
        }

        .pricing-card__price {
            font-size: 36px;
            font-weight: 700;
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-md);
        }

        .pricing-card__price-unit {
            font-size: 16px;
            color: var(--color-text-light);
            font-weight: 400;
        }

        .pricing-card__target {
            font-size: 14px;
            color: var(--color-text-light);
            margin-bottom: var(--spacing-lg);
            padding: var(--spacing-sm) 0;
            border-top: 1px solid var(--color-border);
            border-bottom: 1px solid var(--color-border);
        }

        .pricing-card__features {
            list-style: none;
            margin: var(--spacing-lg) 0;
        }

        .pricing-card__features li {
            padding: var(--spacing-sm) 0;
            color: var(--color-text);
            display: flex;
            align-items: start;
            gap: var(--spacing-sm);
        }

        .pricing-card__features li i {
            color: var(--color-natural-brown);
            margin-top: 4px;
            flex-shrink: 0;
        }

        .pricing-card__cta {
            margin-top: var(--spacing-lg);
        }

        .story-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-gray);
        }

        .story-section__title {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: var(--spacing-xl);
            color: var(--color-charcoal);
        }

        .story-section__title i {
            color: var(--color-natural-brown);
            margin-right: var(--spacing-sm);
        }

        .story-content {
            max-width: 800px;
            margin: 0 auto;
            background: var(--color-bg-white);
            padding: var(--spacing-xl);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .story-content h3 {
            font-size: 24px;
            font-weight: 700;
            margin: var(--spacing-xl) 0 var(--spacing-md) 0;
            color: var(--color-charcoal);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .story-content h3 i {
            color: var(--color-natural-brown);
        }

        .story-content p {
            line-height: 1.9;
            color: var(--color-text);
            margin-bottom: var(--spacing-md);
        }

        .team-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-white);
        }

        .team-section__title {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: var(--spacing-md);
            color: var(--color-charcoal);
        }

        .team-section__subtitle {
            text-align: center;
            color: var(--color-text-light);
            margin-bottom: var(--spacing-xl);
            font-size: 16px;
        }

        .team-roles {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-lg);
            max-width: 1000px;
            margin: 0 auto;
        }

        .team-role {
            background: var(--color-bg-gray);
            padding: var(--spacing-lg);
            border-radius: 8px;
            border-left: 4px solid var(--color-natural-brown);
        }

        .team-role__icon {
            font-size: 32px;
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-sm);
        }

        .team-role__name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
            color: var(--color-charcoal);
        }

        .team-role__description {
            font-size: 14px;
            color: var(--color-text-light);
            line-height: 1.7;
        }

        .portfolio-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-gray);
        }

        .portfolio-section__title {
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: var(--spacing-xl);
            color: var(--color-charcoal);
        }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing-xl);
            max-width: 1000px;
            margin: 0 auto;
        }

        .portfolio-item {
            background: var(--color-bg-white);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .portfolio-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .portfolio-item__image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--color-natural-brown), var(--color-charcoal));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-bg-white);
            font-size: 48px;
        }

        .portfolio-item__content {
            padding: var(--spacing-lg);
        }

        .portfolio-item__title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
            color: var(--color-charcoal);
        }

        .portfolio-item__description {
            font-size: 14px;
            color: var(--color-text-light);
            line-height: 1.7;
            margin-bottom: var(--spacing-md);
        }

        .portfolio-item__link {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-xs);
            color: var(--color-natural-brown);
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: gap 0.3s ease;
        }

        .portfolio-item__link:hover {
            gap: var(--spacing-sm);
        }

        .portfolio-item__link i {
            font-size: 12px;
        }

        .section__subtitle {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            margin: var(--spacing-xxl) 0 var(--spacing-md) 0;
            color: var(--color-charcoal);
        }

        .highlight-box {
            background: linear-gradient(135deg, rgba(139, 115, 85, 0.05), rgba(139, 115, 85, 0.1));
            border-left: 4px solid var(--color-natural-brown);
            padding: var(--spacing-lg);
            margin: var(--spacing-lg) 0;
            border-radius: 8px;
        }

        .highlight-box p {
            margin: 0;
            font-weight: 600;
            color: var(--color-charcoal);
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
                <i class="fas fa-laptop-code"></i> Webサイト制作
            </h1>
            <p class="page-header__description">
                10万円から始める、プロフェッショナルなWeb制作。<br>
                個人事業主から中小企業まで、目的に応じた最適なプランをご提供します。
            </p>
        </div>
    </section>

    <!-- 料金プラン -->
    <section class="pricing-section">
        <div class="container">
            <h2 class="section__subtitle">
                <i class="fas fa-tags"></i> 料金プラン
            </h2>

            <div class="highlight-box">
                <p><i class="fas fa-info-circle"></i> 予算が決まっていない方も、お気軽にご相談ください。最適なプランをご提案いたします。</p>
            </div>

            <div class="pricing-grid">
                <!-- 10万円プラン -->
                <div class="pricing-card">
                    <div class="pricing-card__icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3 class="pricing-card__name">10万円プラン</h3>
                    <div class="pricing-card__price">
                        ¥100,000<span class="pricing-card__price-unit">〜</span>
                    </div>
                    <div class="pricing-card__target">
                        <i class="fas fa-user"></i> 個人事業主の方や安く早く作りたい方向け
                    </div>
                    <ul class="pricing-card__features">
                        <li><i class="fas fa-check-circle"></i> <span>1〜5ページのシンプルなサイト</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>レスポンシブデザイン（スマホ対応）</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>基本的なSEO対策</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>お問い合わせフォーム</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>SSL証明書設定</span></li>
                        <li><i class="fas fa-times-circle" style="color: #ccc;"></i> <span style="color: #999;">ブログ機能は含まれません</span></li>
                        <li><i class="fas fa-times-circle" style="color: #ccc;"></i> <span style="color: #999;">更新機能は含まれません</span></li>
                    </ul>
                    <div class="pricing-card__cta">
                        <a href="contact.html" class="btn btn-secondary btn--large" style="width: 100%;">
                            <i class="fas fa-envelope"></i> お問い合わせ
                        </a>
                    </div>
                </div>

                <!-- 30万円プラン -->
                <div class="pricing-card pricing-card--featured">
                    <div class="pricing-card__badge">おすすめ</div>
                    <div class="pricing-card__icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="pricing-card__name">30万円プラン</h3>
                    <div class="pricing-card__price">
                        ¥300,000<span class="pricing-card__price-unit">〜</span>
                    </div>
                    <div class="pricing-card__target">
                        <i class="fas fa-users"></i> ブログ更新やWebからの集客を目指す方向け
                    </div>
                    <ul class="pricing-card__features">
                        <li><i class="fas fa-check-circle"></i> <span>10万円プランの全機能</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>ブログ・お知らせ機能</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>WordPress導入（または軽量CMS）</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>Google Analytics設定・解説</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>詳細なSEO対策</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>SNS連携設定</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>1ヶ月間の無料サポート</span></li>
                    </ul>
                    <div class="pricing-card__cta">
                        <a href="contact.html" class="btn btn-primary btn--large" style="width: 100%;">
                            <i class="fas fa-envelope"></i> お問い合わせ
                        </a>
                    </div>
                </div>

                <!-- カスタムプラン -->
                <div class="pricing-card">
                    <div class="pricing-card__icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="pricing-card__name">カスタムプラン</h3>
                    <div class="pricing-card__price">
                        ¥500万<span class="pricing-card__price-unit">〜1,200万</span>
                    </div>
                    <div class="pricing-card__target">
                        <i class="fas fa-building"></i> 本格的なWebシステム開発が必要な方向け
                    </div>
                    <ul class="pricing-card__features">
                        <li><i class="fas fa-check-circle"></i> <span>完全オリジナルデザイン</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>高度なコーディング</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>オリジナルCMS開発</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>会員機能・決済機能</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>複雑なデータベース設計</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>API連携・外部システム統合</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>継続的な保守・運用サポート</span></li>
                    </ul>
                    <div class="pricing-card__cta">
                        <a href="contact.html" class="btn btn-secondary btn--large" style="width: 100%;">
                            <i class="fas fa-envelope"></i> お見積もり依頼
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 料金プランの背景ストーリー -->
    <section class="story-section">
        <div class="container">
            <h2 class="story-section__title">
                <i class="fas fa-book-open"></i> 今回のプランの背景
            </h2>

            <div class="story-content">
                <p>
                    これまで前職を含め、カスタムプラン（500万円以上）の案件しか用意しておりませんでした。
                </p>

                <h3><i class="fas fa-lightbulb"></i> Webサイトは現代の「名刺」</h3>
                <p>
                    ただ、現代において、Webサイトというのは集客以上の役割を果たすことがあります。
                    感覚的に言うと、<strong>「名刺」に近い存在</strong>だと感じております。
                </p>

                <h3><i class="fas fa-question-circle"></i> ただ、それにしては高すぎません？</h3>
                <p>
                    制作現場にいる私でもそう思います。<br>
                    特に、私が現在個人事業主なので特に感じます。<strong>えっ、そんなかかんの？</strong>
                </p>
                <p>
                    もちろん、今は無料で制作できるツール（ドラッグアンドドロップ型）も存在しますが、意外に面倒で時間もかかりますし、
                    なんだかんだやっぱり多少こだわりがあってうまくできず、ストレスたまることってありますよね。
                </p>

                <h3><i class="fas fa-gift"></i> だから、作りました</h3>
                <p>
                    なので、とりあえず上記のようなプランを作ってみました。<br>
                    名刺代わりに持っておきたい方、まずは小さく始めたい方にピッタリです。
                </p>

                <h3><i class="fas fa-hand-holding-usd"></i> なぜ、この金額で可能なのか？</h3>
                <p>
                    一番高いカスタムプランを除いて、<strong>すべて私一人で対応している</strong>ためです。
                </p>
                <p>
                    本来Web制作は様々な人が関わって作業していくものです（後述）。
                    ただ、私は幸い前職で様々なことを学んだので、ある程度のレベルであれば一人で制作可能です。
                </p>
                <p>
                    実際、あなたが見ているこのサイトも私が一人で制作しております。<br>
                    <small style="color: var(--color-text-light);">（2ヶ月以上かかりましたが、笑）</small>
                </p>

                <h3><i class="fas fa-comments"></i> まずは相談だけでも大歓迎です</h3>
                <p>
                    全然、検討段階でも大丈夫です。<br>
                    話だけ聞いて、誰かに紹介してくれる人なんかも大歓迎です。
                </p>
                <div style="text-align: center; margin-top: var(--spacing-lg);">
                    <a href="contact.html" class="btn btn-primary btn--large">
                        <i class="fas fa-envelope"></i> お気軽にご相談ください
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Web制作に関わる人たち -->
    <section class="team-section">
        <div class="container">
            <h2 class="team-section__title">
                <i class="fas fa-users"></i> 一般的にWeb制作に関わる職種
            </h2>
            <p class="team-section__subtitle">
                通常、これだけの専門家がプロジェクトに関わります。当社の10万円・30万円プランでは、これらの役割を代表がワンストップで担当することでコストを削減しています。
            </p>

            <div class="team-roles">
                <div class="team-role">
                    <div class="team-role__icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="team-role__name">ディレクター</div>
                    <div class="team-role__description">
                        プロジェクト全体の進行管理、クライアントとの調整、スケジュール管理を担当
                    </div>
                </div>

                <div class="team-role">
                    <div class="team-role__icon">
                        <i class="fas fa-pencil-ruler"></i>
                    </div>
                    <div class="team-role__name">Webデザイナー</div>
                    <div class="team-role__description">
                        サイト全体のビジュアルデザイン、UI/UXデザイン、ワイヤーフレーム作成
                    </div>
                </div>

                <div class="team-role">
                    <div class="team-role__icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="team-role__name">フロントエンドエンジニア</div>
                    <div class="team-role__description">
                        HTML/CSS/JavaScriptを使った画面実装、レスポンシブ対応
                    </div>
                </div>

                <div class="team-role">
                    <div class="team-role__icon">
                        <i class="fas fa-server"></i>
                    </div>
                    <div class="team-role__name">バックエンドエンジニア</div>
                    <div class="team-role__description">
                        サーバーサイドプログラミング、データベース設計、API開発
                    </div>
                </div>

                <div class="team-role">
                    <div class="team-role__icon">
                        <i class="fas fa-pen-fancy"></i>
                    </div>
                    <div class="team-role__name">コピーライター</div>
                    <div class="team-role__description">
                        サイトの文章作成、キャッチコピー、SEOライティング
                    </div>
                </div>

                <div class="team-role">
                    <div class="team-role__icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="team-role__name">マーケター</div>
                    <div class="team-role__description">
                        アクセス解析、SEO戦略、コンバージョン最適化
                    </div>
                </div>

                <div class="team-role">
                    <div class="team-role__icon">
                        <i class="fas fa-camera"></i>
                    </div>
                    <div class="team-role__name">カメラマン</div>
                    <div class="team-role__description">
                        商品撮影、取材撮影、画像素材の制作
                    </div>
                </div>

                <div class="team-role">
                    <div class="team-role__icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <div class="team-role__name">テスター / QA</div>
                    <div class="team-role__description">
                        動作確認、バグチェック、品質管理
                    </div>
                </div>
            </div>

            <div class="highlight-box" style="margin-top: var(--spacing-xl);">
                <p>
                    <i class="fas fa-info-circle"></i>
                    カスタムプラン（500万円〜）では、これらの専門家がチームを組んで対応するため、より高度で大規模なプロジェクトにも対応可能です。
                </p>
            </div>
        </div>
    </section>

    <!-- 制作実績 -->
    <section class="portfolio-section">
        <div class="container">
            <h2 class="portfolio-section__title">
                <i class="fas fa-briefcase"></i> 制作実績
            </h2>

            <div class="portfolio-grid">
                <div class="portfolio-item">
                    <div class="portfolio-item__image">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div class="portfolio-item__content">
                        <h3 class="portfolio-item__title">
                            「このままでいいのかな」から抜け出した人たちが選んだ、次の一歩
                        </h3>
                        <p class="portfolio-item__description">
                            大分県のIT移住を促進するランディングページ。共感を生むキャッチコピーと、実際の移住者の声を掲載。
                        </p>
                        <a href="https://migration.oita-creative.jp/lp2/" target="_blank" rel="noopener noreferrer" class="portfolio-item__link">
                            <span>サイトを見る</span>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>

                <div class="portfolio-item">
                    <div class="portfolio-item__image">
                        <i class="fas fa-plane-departure"></i>
                    </div>
                    <div class="portfolio-item__content">
                        <h3 class="portfolio-item__title">
                            大分IT移住プロジェクト - 同じ働き方で、もっと豊かに
                        </h3>
                        <p class="portfolio-item__description">
                            リモートワーク時代の新しい働き方を提案。大分への移住を検討するITワーカー向けのプロモーションサイト。
                        </p>
                        <a href="https://migration.oita-creative.jp/2025/lp/" target="_blank" rel="noopener noreferrer" class="portfolio-item__link">
                            <span>サイトを見る</span>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>

                <div class="portfolio-item">
                    <div class="portfolio-item__image">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="portfolio-item__content">
                        <h3 class="portfolio-item__title">
                            余日（Yojitsu）コーポレートサイト
                        </h3>
                        <p class="portfolio-item__description">
                            今ご覧いただいているこのサイト。ブログ機能、お知らせ機能、お問い合わせフォームなど、ビジネスに必要な機能を網羅。
                        </p>
                        <a href="/" class="portfolio-item__link">
                            <span>トップページへ</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div style="text-align: center; margin-top: var(--spacing-xxl);">
                <a href="contact.html" class="btn btn-primary btn--large">
                    <i class="fas fa-envelope"></i> あなたのプロジェクトについて相談する
                </a>
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
