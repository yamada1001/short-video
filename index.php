<?php $current_page = 'home'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="大分県を拠点に、Web制作・ショート動画制作を提供する余日（Yojitsu）。デジタルマーケティングで地域企業の成長を支援します。">
    <meta name="keywords" content="大分,Web制作,ショート動画,動画制作,ホームページ制作,余日,Yojitsu">
    <title>余日（Yojitsu） - 大分のデジタルマーケティング</title>

    <!-- OGP -->
    <meta property="og:title" content="余日（Yojitsu） - 大分のデジタルマーケティング">
    <meta property="og:description" content="大分県を拠点に、Web制作・ショート動画制作を提供。地域企業のデジタル化を支援します。">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://yojitu.com/">
    <meta property="og:image" content="https://yojitu.com/assets/images/ogp.jpg">
    <meta property="og:locale" content="ja_JP">
    <meta name="twitter:card" content="summary_large_image">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="favicon.svg">

    <!-- Google Fonts - Optimized with display=swap -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">

    <!-- Critical CSS - Inline for faster FCP -->
    <style>
        /* Critical styles for above-the-fold content */
        :root{--color-natural-brown:#8B7355;--color-charcoal:#4A4A4A;--color-beige:#E5DDD5;--color-bg:#F5F3F0;--spacing-sm:16px;--spacing-md:24px;--spacing-lg:40px;--spacing-xl:60px;--transition-base:0.35s ease-in-out}
        *{box-sizing:border-box}
        body{margin:0;font-family:'Noto Sans JP',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;overflow-x:clip;color:#4A4A4A;line-height:1.8;background-color:var(--color-bg)}
        .container{max-width:1200px;margin:0 auto;padding:0 32px}
        .header{position:fixed;top:0;left:0;width:100%;z-index:1000;background-color:#fff;border-bottom:1px solid #e0e0e0;transition:transform .3s ease;padding:20px 0}
        .header__container{display:flex;justify-content:space-between;align-items:center;max-width:1200px;margin:0 auto;padding:0 32px}
        .header__logo{font-size:20px;font-weight:500;color:var(--color-charcoal);text-decoration:none;letter-spacing:0.1em}
        .hero{min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;background:linear-gradient(135deg,#faf9f7 0%,#f5f3f0 100%);position:relative;overflow:hidden;padding-top:80px}
        .hero__background{position:absolute;top:0;left:0;width:100%;height:100%;z-index:0;pointer-events:none}
        .hero__content{position:relative;z-index:10;max-width:800px;padding:var(--spacing-md)}
        .hero__label{font-size:12px;letter-spacing:0.2em;color:var(--color-natural-brown);text-transform:uppercase;margin-bottom:var(--spacing-md);font-weight:500}
        .hero__description{font-size:clamp(24px,5vw,42px);font-weight:500;line-height:1.6;letter-spacing:0.05em;color:var(--color-charcoal);margin:0 0 var(--spacing-lg)}
        .hero__cta{display:flex;gap:var(--spacing-sm);justify-content:center;flex-wrap:wrap}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:16px 32px;border:1px solid var(--color-natural-brown);background-color:transparent;color:var(--color-natural-brown);text-decoration:none;transition:all var(--transition-base);font-weight:500;letter-spacing:0.05em;cursor:pointer}
        .btn:hover{background-color:var(--color-beige)}
        .btn-primary{background-color:var(--color-natural-brown);color:#fff}
        .btn-primary:hover{background-color:var(--color-charcoal)}
        .btn-secondary{background-color:transparent;color:var(--color-natural-brown)}
        .btn--large{padding:18px 40px;font-size:16px}
        @media(max-width:768px){
            .container,.header__container{padding:0 16px}
            .hero{padding-top:60px}
            .hero__content{padding:16px}
            .hero__cta{flex-direction:column;align-items:center}
            .btn--large{padding:14px 28px;font-size:14px;width:100%;max-width:300px;justify-content:center}
        }
    </style>

    <!-- CSS - Optimized loading -->
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/components.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="assets/css/cta.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="assets/css/pages/top.css" media="print" onload="this.media='all'">
    <link rel="stylesheet" href="assets/css/cookie-consent.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="assets/css/components.css">
        <link rel="stylesheet" href="assets/css/cta.css">
        <link rel="stylesheet" href="assets/css/pages/top.css">
        <link rel="stylesheet" href="assets/css/cookie-consent.css">
    </noscript>

    <!-- Font Awesome - Deferred loading -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'" crossorigin="anonymous">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous"></noscript>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->

    <!-- 構造化データ -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "余日（Yojitsu）",
      "description": "デジタルマーケティング・Web制作会社",
      "url": "https://yojitu.com/",
      "telephone": "080-4692-9681",
      "email": "yamada@yojitu.com",
      "foundingDate": "2025-05-14",
      "taxID": "T9810094141774",
      "address": {
        "@type": "PostalAddress",
        "addressRegion": "大分県",
        "addressCountry": "JP"
      },
      "areaServed": {
        "@type": "Country",
        "name": "日本"
      },
      "priceRange": "¥¥",
      "serviceType": ["Webサイト制作", "ショート動画制作"]
    }
    </script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- ヒーローセクション -->
    <section class="hero">
        <div class="hero__background">
            <svg class="hero__svg" viewBox="0 0 1200 800" xmlns="http://www.w3.org/2000/svg">
                <!-- グラデーション定義 -->
                <defs>
                    <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:rgba(99,88,76,0.1);stop-opacity:1" />
                        <stop offset="100%" style="stop-color:rgba(99,88,76,0.05);stop-opacity:1" />
                    </linearGradient>
                </defs>

                <!-- 動的な波形 -->
                <path class="hero__wave hero__wave--1" d="M0,400 Q300,300 600,400 T1200,400 L1200,800 L0,800 Z" fill="url(#grad1)" opacity="0.3"/>
                <path class="hero__wave hero__wave--2" d="M0,450 Q300,350 600,450 T1200,450 L1200,800 L0,800 Z" fill="url(#grad1)" opacity="0.2"/>

                <!-- データポイント（マーケティング風） -->
                <circle class="hero__data-point hero__data-point--1" cx="200" cy="300" r="8" fill="#63584C" opacity="0.6"/>
                <circle class="hero__data-point hero__data-point--2" cx="400" cy="250" r="6" fill="#63584C" opacity="0.5"/>
                <circle class="hero__data-point hero__data-point--3" cx="600" cy="200" r="10" fill="#63584C" opacity="0.7"/>
                <circle class="hero__data-point hero__data-point--4" cx="800" cy="280" r="7" fill="#63584C" opacity="0.6"/>
                <circle class="hero__data-point hero__data-point--5" cx="1000" cy="320" r="9" fill="#63584C" opacity="0.5"/>

                <!-- 接続線（データフロー） -->
                <polyline class="hero__line" points="200,300 400,250 600,200 800,280 1000,320" fill="none" stroke="#63584C" stroke-width="2" opacity="0.3"/>

                <!-- グラフ風の棒 -->
                <rect class="hero__bar hero__bar--1" x="100" y="500" width="40" height="200" fill="#63584C" opacity="0.15"/>
                <rect class="hero__bar hero__bar--2" x="180" y="450" width="40" height="250" fill="#63584C" opacity="0.15"/>
                <rect class="hero__bar hero__bar--3" x="260" y="400" width="40" height="300" fill="#63584C" opacity="0.15"/>

                <!-- 矢印（成長を示す） -->
                <path class="hero__arrow" d="M 900 150 L 1050 100 L 1040 120 M 1050 100 L 1030 110" stroke="#63584C" stroke-width="3" fill="none" opacity="0.4"/>
            </svg>

            <div class="hero__particles">
                <div class="hero__particle"></div>
                <div class="hero__particle"></div>
                <div class="hero__particle"></div>
                <div class="hero__particle"></div>
                <div class="hero__particle"></div>
            </div>
        </div>

        <div class="hero__content animate">
            <div class="hero__label">
                <span>Digital Marketing</span>
            </div>
            <p class="hero__description">
                Web制作・ショート動画で<br class="sp-only">
                企業の成長をサポート
            </p>
            <div class="hero__cta">
                <a href="contact.html" class="btn btn-primary btn--large">
                    <span>お問い合わせ</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="services.html" class="btn btn-secondary btn--large">
                    <span>サービス詳細</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="hero__scroll">
            <span>Scroll</span>
            <div class="hero__scroll-line"></div>
        </div>
    </section>

    <!-- サービスセクション -->
    <section class="section" id="services">
        <div class="container">
            <h2 class="section__title animate">サービス</h2>
            <p class="section__description animate">
                デジタルマーケティングの専門知識で、<br>
                お客様のビジネス成長をトータルサポート
            </p>
            <div class="services-grid">
                <div class="service-card animate">
                    <div class="service-card__icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3 class="service-card__title">Web制作</h3>
                    <p class="service-card__description">
                        コーポレートサイト・LP・採用サイトの制作。レスポンシブ対応、SEO最適化を標準実装。
                    </p>
                    <p class="service-card__price">300,000円〜</p>
                    <a href="services.html#web" class="btn btn-secondary">詳しく見る</a>
                </div>
                <div class="service-card animate">
                    <div class="service-card__icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3 class="service-card__title">ショート動画制作</h3>
                    <p class="service-card__description">
                        TikTok・Instagram・YouTubeショート向け。企画から編集まで、SNS映えする動画を制作。
                    </p>
                    <p class="service-card__price">1本 20,000円〜</p>
                    <a href="services.html#short-video" class="btn btn-secondary">詳しく見る</a>
                </div>
            </div>
        </div>
    </section>

    <!-- お知らせセクション -->
    <section class="section section--gray" id="news">
        <div class="container">
            <h2 class="section__title animate">お知らせ</h2>
            <div class="news-list">
                <a href="news/detail.php?id=1" class="news-item animate">
                    <span class="news-item__date">2025.11.12</span>
                    <span class="news-item__category">お知らせ</span>
                    <span class="news-item__title">Webサイトをリニューアルしました</span>
                    <span class="news-item__badge">NEW</span>
                </a>
                <a href="news/detail.php?id=2" class="news-item animate">
                    <span class="news-item__date">2025.11.01</span>
                    <span class="news-item__category">サービス</span>
                    <span class="news-item__title">ショート動画制作サービスを開始しました</span>
                </a>
                <a href="news/detail.php?id=3" class="news-item animate">
                    <span class="news-item__date">2025.10.15</span>
                    <span class="news-item__category">実績</span>
                    <span class="news-item__title">大分県内企業様のSEO対策で検索順位1位を獲得</span>
                </a>
            </div>
            <div class="text-center mt-xl animate">
                <a href="news/" class="btn btn-secondary">お知らせ一覧</a>
            </div>
        </div>
    </section>

    <!-- ブログセクション -->
    <section class="section" id="blog">
        <div class="container">
            <h2 class="section__title animate">ブログ</h2>
            <p class="section__description animate">
                デジタルマーケティングの最新情報と<br>
                実践的なノウハウをお届けします
            </p>
            <div class="blog-preview-grid">
                <?php
                // posts.jsonを読み込み
                $postsJson = file_get_contents(__DIR__ . '/blog/data/posts.json');
                $postsData = json_decode($postsJson, true);
                $posts = $postsData['posts'] ?? [];

                // 公開日でソート（新しい順）
                usort($posts, function($a, $b) {
                    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
                });

                // 最新3記事を取得
                $latestPosts = array_slice($posts, 0, 3);

                foreach ($latestPosts as $post):
                    $date = date('Y.m.d', strtotime($post['publishedAt']));
                ?>
                <a href="blog/detail.php?slug=<?php echo htmlspecialchars($post['slug'], ENT_QUOTES, 'UTF-8'); ?>" class="blog-preview-card animate">
                    <div class="blog-preview-card__meta">
                        <span class="blog-preview-card__date"><?php echo htmlspecialchars($date, ENT_QUOTES, 'UTF-8'); ?></span>
                        <span class="blog-preview-card__category"><?php echo htmlspecialchars($post['category'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <h3 class="blog-preview-card__title"><?php echo htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p class="blog-preview-card__excerpt"><?php echo htmlspecialchars($post['excerpt'], ENT_QUOTES, 'UTF-8'); ?></p>
                </a>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-xl animate">
                <a href="blog/" class="btn btn-secondary">ブログ一覧</a>
            </div>
        </div>
    </section>

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

    <!-- JavaScript - Single unified file for better performance -->
    <script defer src="assets/js/main.js"></script>
</body>
</html>
