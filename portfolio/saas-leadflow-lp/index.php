<?php
// LeadFlow - Awwwards-style Ultra Rich LP
http_response_code(200);

// メタデータ
$page_title = 'LeadFlow - 営業チームの生産性を最大化するCRM';
$page_description = '次世代CRMプラットフォーム。AI駆動の営業支援ツールで、チームのパフォーマンスを最大化します。';
$gtm_id = 'GTM-T7NGQDC2';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <title><?php echo htmlspecialchars($page_title); ?></title>

    <!-- Google Tag Manager -->
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    </script>
    <script defer src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($gtm_id); ?>"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- External Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/studio-freight/lenis@1.0.19/bundled/lenis.min.js"></script>

    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Loading Screen -->
    <div class="loader" id="loader">
        <div class="loader__content">
            <div class="loader__logo">LeadFlow</div>
            <div class="loader__progress">
                <div class="loader__progress-bar" id="loaderProgress"></div>
            </div>
            <div class="loader__text">読み込み中...</div>
        </div>
    </div>

    <!-- Custom Cursor -->
    <div class="cursor" id="cursor"></div>
    <div class="cursor-follower" id="cursorFollower"></div>

    <!-- Three.js Background Canvas -->
    <canvas id="webgl"></canvas>

    <!-- Main Content -->
    <div class="main-wrapper" id="mainWrapper">

        <!-- Navigation -->
        <nav class="nav">
            <div class="nav__logo">LeadFlow</div>
            <div class="nav__links">
                <a href="#features" class="nav__link">機能</a>
                <a href="#demo" class="nav__link">デモ</a>
                <a href="#pricing" class="nav__link">料金</a>
                <a href="#contact" class="nav__link nav__link--cta">無料で始める</a>
            </div>
            <button class="nav__burger" id="navBurger">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>

        <!-- Hero Section -->
        <section class="section hero" id="hero">
            <div class="hero__content">
                <h1 class="hero__title">
                    <span class="hero__title-line">営業チームの</span>
                    <span class="hero__title-line">生産性を</span>
                    <span class="hero__title-line hero__title-line--gradient">最大化</span>
                </h1>
                <p class="hero__subtitle">
                    AI駆動の次世代CRMプラットフォーム<br>
                    卓越したパフォーマンスを求めるチームのために
                </p>
                <div class="hero__cta">
                    <button class="btn btn--primary">
                        <span>無料トライアル開始</span>
                        <svg width="20" height="20" viewBox="0 0 20 20">
                            <path d="M7 3L14 10L7 17" stroke="currentColor" stroke-width="2" fill="none"/>
                        </svg>
                    </button>
                    <button class="btn btn--secondary">デモを見る</button>
                </div>
                <div class="hero__scroll">
                    <span>スクロールして詳しく見る</span>
                    <div class="hero__scroll-icon"></div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="section stats" id="stats">
            <div class="stats__grid">
                <div class="stat-item">
                    <div class="stat-item__number" data-count="10000">0</div>
                    <div class="stat-item__label">導入企業数</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item__number" data-count="98">0</div>
                    <div class="stat-item__label">顧客満足度</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item__number" data-count="45">0</div>
                    <div class="stat-item__label">導入国数</div>
                </div>
                <div class="stat-item">
                    <div class="stat-item__number" data-count="24">0</div>
                    <div class="stat-item__label">24時間サポート</div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="section features" id="features">
            <div class="section-header">
                <h2 class="section-title">強力な機能</h2>
                <p class="section-subtitle">営業活動を加速させるすべての機能</p>
            </div>
            <div class="features__grid">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48">
                            <rect width="48" height="48" rx="12" fill="url(#grad1)"/>
                            <path d="M16 24L22 30L32 20" stroke="white" stroke-width="3" fill="none"/>
                            <defs>
                                <linearGradient id="grad1" x1="0" y1="0" x2="48" y2="48">
                                    <stop offset="0%" stop-color="#667eea"/>
                                    <stop offset="100%" stop-color="#764ba2"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">AI駆動のインサイト</h3>
                    <p class="feature-card__desc">機械学習アルゴリズムが顧客行動を予測し、営業パイプラインを自動で最適化します。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48">
                            <rect width="48" height="48" rx="12" fill="url(#grad2)"/>
                            <circle cx="24" cy="24" r="8" stroke="white" stroke-width="3" fill="none"/>
                            <defs>
                                <linearGradient id="grad2" x1="0" y1="0" x2="48" y2="48">
                                    <stop offset="0%" stop-color="#f093fb"/>
                                    <stop offset="100%" stop-color="#f5576c"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">リアルタイム分析</h3>
                    <p class="feature-card__desc">美しいダッシュボードと即座の通知で、チームのパフォーマンスをモニタリング。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48">
                            <rect width="48" height="48" rx="12" fill="url(#grad3)"/>
                            <path d="M14 24H34M24 14V34" stroke="white" stroke-width="3"/>
                            <defs>
                                <linearGradient id="grad3" x1="0" y1="0" x2="48" y2="48">
                                    <stop offset="0%" stop-color="#4facfe"/>
                                    <stop offset="100%" stop-color="#00f2fe"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">シームレスな連携</h3>
                    <p class="feature-card__desc">1000以上のアプリやツールと連携可能。開発者向けAPIファースト設計。</p>
                </div>
            </div>
        </section>

        <!-- Demo Section -->
        <section class="section demo" id="demo">
            <div class="demo__content">
                <div class="demo__text">
                    <h2 class="demo__title">実際に体験する</h2>
                    <p class="demo__description">
                        インタラクティブなデモでLeadFlowのパワーを体験<br>
                        登録不要ですぐにお試しいただけます
                    </p>
                    <button class="btn btn--primary">デモを起動</button>
                </div>
                <div class="demo__visual">
                    <div class="demo__screen">
                        <!-- Placeholder for demo visual -->
                        <div class="demo__screen-inner"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section class="section pricing" id="pricing">
            <div class="section-header">
                <h2 class="section-title">シンプルな料金体系</h2>
                <p class="section-subtitle">チームに最適なプランをお選びください</p>
            </div>
            <div class="pricing__grid">
                <div class="pricing-card">
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">スターター</h3>
                        <div class="pricing-card__price">
                            <span class="price-currency">¥</span>
                            <span class="price-amount">9,800</span>
                            <span class="price-period">/月</span>
                        </div>
                    </div>
                    <ul class="pricing-card__features">
                        <li>最大5ユーザー</li>
                        <li>基本CRM機能</li>
                        <li>メールサポート</li>
                        <li>1GBストレージ</li>
                    </ul>
                    <button class="btn btn--outline">今すぐ始める</button>
                </div>
                <div class="pricing-card pricing-card--featured">
                    <div class="pricing-card__badge">人気</div>
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">プロフェッショナル</h3>
                        <div class="pricing-card__price">
                            <span class="price-currency">¥</span>
                            <span class="price-amount">24,800</span>
                            <span class="price-period">/月</span>
                        </div>
                    </div>
                    <ul class="pricing-card__features">
                        <li>最大20ユーザー</li>
                        <li>高度な分析機能</li>
                        <li>優先サポート</li>
                        <li>50GBストレージ</li>
                        <li>API利用</li>
                    </ul>
                    <button class="btn btn--primary">今すぐ始める</button>
                </div>
                <div class="pricing-card">
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">エンタープライズ</h3>
                        <div class="pricing-card__price">
                            <span class="price-amount">要相談</span>
                        </div>
                    </div>
                    <ul class="pricing-card__features">
                        <li>無制限ユーザー</li>
                        <li>カスタム連携</li>
                        <li>専任サポート</li>
                        <li>無制限ストレージ</li>
                        <li>SLA保証</li>
                    </ul>
                    <button class="btn btn--outline">営業に問い合わせ</button>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="section cta" id="contact">
            <div class="cta__content">
                <h2 class="cta__title">営業を変革する準備はできていますか？</h2>
                <p class="cta__subtitle">14日間の無料トライアルを今すぐ開始。クレジットカード不要です。</p>
                <form class="cta__form">
                    <input type="email" class="cta__input" placeholder="メールアドレスを入力" required>
                    <button type="submit" class="btn btn--primary">無料トライアル開始</button>
                </form>
            </div>
        </section>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer__content">
                <div class="footer__brand">
                    <div class="footer__logo">LeadFlow</div>
                    <p class="footer__tagline">次世代CRMプラットフォーム</p>
                </div>
                <div class="footer__links">
                    <div class="footer__column">
                        <h4>製品</h4>
                        <a href="#features">機能</a>
                        <a href="#pricing">料金</a>
                        <a href="#demo">デモ</a>
                    </div>
                    <div class="footer__column">
                        <h4>会社</h4>
                        <a href="#">会社概要</a>
                        <a href="#">ブログ</a>
                        <a href="#">採用情報</a>
                    </div>
                    <div class="footer__column">
                        <h4>サポート</h4>
                        <a href="#">ヘルプセンター</a>
                        <a href="#">お問い合わせ</a>
                        <a href="#">API ドキュメント</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2024 LeadFlow. All rights reserved.</p>
            </div>
        </footer>

    </div>

    <script src="assets/js/awwwards.js"></script>
</body>
</html>
