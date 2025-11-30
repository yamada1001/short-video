<?php
// LeadFlow - SaaS CRM LP
http_response_code(200);

// メタデータ
$page_title = 'LeadFlow - 営業チームの生産性を最大化するCRM';
$page_description = 'LeadFlow - 営業チームの生産性を最大化するCRMツール。直感的なUI、簡単な導入、充実したサポートで営業活動を加速します。';
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

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- ヘッダー -->
    <header class="header">
        <div class="container">
            <div class="header__inner">
                <div class="header__logo">
                    <span class="logo">LeadFlow</span>
                </div>
                <nav class="header__nav">
                    <a href="#features" class="nav-link">特徴</a>
                    <a href="#functions" class="nav-link">機能</a>
                    <a href="#pricing" class="nav-link">料金</a>
                    <a href="#faq" class="nav-link">FAQ</a>
                    <a href="#cta" class="btn btn--primary btn--small">無料で始める</a>
                </nav>
                <button class="header__menu-btn" aria-label="メニュー">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- ヒーローセクション -->
    <section class="hero" id="hero">
        <div class="hero__background"></div>
        <div class="container">
            <div class="hero__content">
                <div class="hero__text">
                    <h1 class="hero__title">
                        営業チームの
                        <span class="gradient-text">生産性を最大化</span>
                    </h1>
                    <p class="hero__subtitle">
                        LeadFlowは、営業活動のすべてを一元管理。<br>
                        直感的なUIで、チーム全員がすぐに使いこなせます。
                    </p>
                    <div class="hero__cta">
                        <a href="#cta" class="btn btn--primary btn--large">
                            無料で始める
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M7.5 15L12.5 10L7.5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <a href="#demo" class="btn btn--secondary btn--large">デモを見る</a>
                    </div>
                    <div class="hero__social-proof">
                        <div class="social-proof__item">
                            <span class="social-proof__number">10,000+</span>
                            <span class="social-proof__label">導入企業</span>
                        </div>
                        <div class="social-proof__item">
                            <span class="social-proof__number">98%</span>
                            <span class="social-proof__label">満足度</span>
                        </div>
                    </div>
                </div>
                <div class="hero__visual">
                    <div class="mockup">
                        <div class="mockup__window">
                            <div class="mockup__header">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <div class="mockup__content">
                                <!-- モックアップのイメージ -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 特徴セクション -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">LeadFlowが選ばれる理由</h2>
                <p class="section-subtitle">営業チームの課題を解決する、充実した機能とサポート</p>
            </div>
            <div class="features__grid">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <rect width="48" height="48" rx="12" fill="url(#gradient1)"/>
                            <path d="M24 16L28 20L24 24L20 20L24 16Z" stroke="white" stroke-width="2"/>
                            <path d="M16 24L20 28L16 32L12 28L16 24Z" stroke="white" stroke-width="2"/>
                            <path d="M32 24L36 28L32 32L28 28L32 24Z" stroke="white" stroke-width="2"/>
                            <defs>
                                <linearGradient id="gradient1" x1="0" y1="0" x2="48" y2="48">
                                    <stop stop-color="#8B5CF6"/>
                                    <stop offset="1" stop-color="#EC4899"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">直感的なUI</h3>
                    <p class="feature-card__desc">複雑な操作は一切不要。営業メンバーが初日から使いこなせるシンプルな設計。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <rect width="48" height="48" rx="12" fill="url(#gradient2)"/>
                            <circle cx="24" cy="24" r="8" stroke="white" stroke-width="2"/>
                            <path d="M24 16V12M24 36V32M32 24H36M12 24H16" stroke="white" stroke-width="2"/>
                            <defs>
                                <linearGradient id="gradient2" x1="0" y1="0" x2="48" y2="48">
                                    <stop stop-color="#06B6D4"/>
                                    <stop offset="1" stop-color="#3B82F6"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">スピード導入</h3>
                    <p class="feature-card__desc">最短3日で導入完了。既存のツールからのデータ移行も簡単に行えます。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <rect width="48" height="48" rx="12" fill="url(#gradient3)"/>
                            <path d="M16 28L22 22L26 26L32 20" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <path d="M28 20H32V24" stroke="white" stroke-width="2" stroke-linecap="round"/>
                            <defs>
                                <linearGradient id="gradient3" x1="0" y1="0" x2="48" y2="48">
                                    <stop stop-color="#10B981"/>
                                    <stop offset="1" stop-color="#059669"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">売上向上を実感</h3>
                    <p class="feature-card__desc">導入企業の平均売上は30%向上。リアルタイム分析で改善ポイントがすぐわかる。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                            <rect width="48" height="48" rx="12" fill="url(#gradient4)"/>
                            <circle cx="20" cy="20" r="4" stroke="white" stroke-width="2"/>
                            <circle cx="28" cy="28" r="4" stroke="white" stroke-width="2"/>
                            <path d="M24 20L24 28" stroke="white" stroke-width="2"/>
                            <defs>
                                <linearGradient id="gradient4" x1="0" y1="0" x2="48" y2="48">
                                    <stop stop-color="#F59E0B"/>
                                    <stop offset="1" stop-color="#EF4444"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <h3 class="feature-card__title">充実サポート</h3>
                    <p class="feature-card__desc">専任のカスタマーサクセスが導入から運用まで徹底サポート。安心してご利用いただけます。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 主要機能セクション -->
    <section class="functions" id="functions">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">主要機能</h2>
                <p class="section-subtitle">営業活動を加速させる、充実の機能群</p>
            </div>
            <div class="function-list">
                <div class="function-item">
                    <div class="function-item__visual">
                        <div class="function-screenshot function-screenshot--1"></div>
                    </div>
                    <div class="function-item__content">
                        <h3 class="function-item__title">リード管理</h3>
                        <p class="function-item__desc">すべてのリード情報を一元管理。自動スコアリングで優先度の高い見込み客を逃しません。ドラッグ&ドロップの簡単操作で、営業フェーズの移動もスムーズに。</p>
                        <ul class="function-item__features">
                            <li>自動スコアリング</li>
                            <li>カスタマイズ可能なパイプライン</li>
                            <li>活動履歴の自動記録</li>
                        </ul>
                    </div>
                </div>
                <div class="function-item function-item--reverse">
                    <div class="function-item__visual">
                        <div class="function-screenshot function-screenshot--2"></div>
                    </div>
                    <div class="function-item__content">
                        <h3 class="function-item__title">レポート&分析</h3>
                        <p class="function-item__desc">売上予測、成約率、活動量など、重要な指標をリアルタイムで可視化。データに基づいた意思決定で、チーム全体の生産性を向上させます。</p>
                        <ul class="function-item__features">
                            <li>カスタマイズ可能なダッシュボード</li>
                            <li>売上予測レポート</li>
                            <li>個人・チーム別パフォーマンス分析</li>
                        </ul>
                    </div>
                </div>
                <div class="function-item">
                    <div class="function-item__visual">
                        <div class="function-screenshot function-screenshot--3"></div>
                    </div>
                    <div class="function-item__content">
                        <h3 class="function-item__title">タスク自動化</h3>
                        <p class="function-item__desc">フォローアップメールの自動送信、リマインダー設定、タスクの自動割り当てなど、営業活動の効率化を実現。重要な商談に集中できます。</p>
                        <ul class="function-item__features">
                            <li>メール自動送信</li>
                            <li>タスクの自動生成</li>
                            <li>Slack/Teams連携</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 数字で見る実績 -->
    <section class="stats">
        <div class="container">
            <div class="stats__grid">
                <div class="stat-card">
                    <div class="stat-card__number">10,000+</div>
                    <div class="stat-card__label">導入企業数</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__number">30%</div>
                    <div class="stat-card__label">平均売上向上率</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__number">98%</div>
                    <div class="stat-card__label">顧客満足度</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card__number">3日</div>
                    <div class="stat-card__label">平均導入期間</div>
                </div>
            </div>
        </div>
    </section>

    <!-- お客様の声 -->
    <section class="testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">お客様の声</h2>
                <p class="section-subtitle">LeadFlowを導入した企業様からの声</p>
            </div>
            <div class="testimonials__grid">
                <div class="testimonial-card">
                    <div class="testimonial-card__rating">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="testimonial-card__text">
                        「導入前は案件管理がExcelで大変でしたが、LeadFlowに切り替えてからチーム全体の生産性が劇的に向上しました。営業メンバーからも使いやすいと好評です。」
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">T</div>
                        <div class="testimonial-card__info">
                            <div class="testimonial-card__name">田中 太郎</div>
                            <div class="testimonial-card__company">株式会社テックソリューション / 営業部長</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-card__rating">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="testimonial-card__text">
                        「レポート機能が本当に便利です。売上予測の精度が上がり、経営判断のスピードが格段に速くなりました。サポートも迅速で安心して使えています。」
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">S</div>
                        <div class="testimonial-card__info">
                            <div class="testimonial-card__name">佐藤 花子</div>
                            <div class="testimonial-card__company">マーケティングラボ株式会社 / 代表取締役</div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-card">
                    <div class="testimonial-card__rating">
                        <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
                    </div>
                    <p class="testimonial-card__text">
                        「以前は他社のCRMを使っていましたが、複雑すぎて営業メンバーが使いこなせませんでした。LeadFlowはシンプルで誰でもすぐに使えるのが最大の魅力です。」
                    </p>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">Y</div>
                        <div class="testimonial-card__info">
                            <div class="testimonial-card__name">山田 一郎</div>
                            <div class="testimonial-card__company">グローバルセールス株式会社 / セールスマネージャー</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 料金プラン -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">料金プラン</h2>
                <p class="section-subtitle">チームの規模に合わせて選べるプラン</p>
            </div>
            <div class="pricing__grid">
                <div class="pricing-card">
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">スターター</h3>
                        <p class="pricing-card__desc">小規模チーム向け</p>
                    </div>
                    <div class="pricing-card__price">
                        <span class="price-amount">¥9,800</span>
                        <span class="price-unit">/月</span>
                    </div>
                    <ul class="pricing-card__features">
                        <li>ユーザー数: 最大5名</li>
                        <li>リード管理</li>
                        <li>基本レポート</li>
                        <li>メールサポート</li>
                        <li>データ保存: 1年</li>
                    </ul>
                    <a href="#cta" class="btn btn--outline">始める</a>
                </div>
                <div class="pricing-card pricing-card--featured">
                    <div class="pricing-card__badge">人気</div>
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">プロフェッショナル</h3>
                        <p class="pricing-card__desc">成長企業向け</p>
                    </div>
                    <div class="pricing-card__price">
                        <span class="price-amount">¥24,800</span>
                        <span class="price-unit">/月</span>
                    </div>
                    <ul class="pricing-card__features">
                        <li>ユーザー数: 最大20名</li>
                        <li>リード管理 + 自動スコアリング</li>
                        <li>高度なレポート&分析</li>
                        <li>タスク自動化</li>
                        <li>チャットサポート</li>
                        <li>データ保存: 無制限</li>
                        <li>API連携</li>
                    </ul>
                    <a href="#cta" class="btn btn--primary">始める</a>
                </div>
                <div class="pricing-card">
                    <div class="pricing-card__header">
                        <h3 class="pricing-card__name">エンタープライズ</h3>
                        <p class="pricing-card__desc">大規模組織向け</p>
                    </div>
                    <div class="pricing-card__price">
                        <span class="price-amount">お問い合わせ</span>
                    </div>
                    <ul class="pricing-card__features">
                        <li>ユーザー数: 無制限</li>
                        <li>すべてのプロ機能</li>
                        <li>専任カスタマーサクセス</li>
                        <li>カスタマイズ対応</li>
                        <li>オンプレミス対応</li>
                        <li>SLA保証</li>
                        <li>優先サポート</li>
                    </ul>
                    <a href="#cta" class="btn btn--outline">相談する</a>
                </div>
            </div>
            <p class="pricing__note">すべてのプランで14日間の無料トライアルをご利用いただけます</p>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq" id="faq">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">よくある質問</h2>
                <p class="section-subtitle">LeadFlowについてのご質問</p>
            </div>
            <div class="faq__list">
                <div class="faq-item">
                    <button class="faq-item__question">
                        <span>導入にはどのくらいの時間がかかりますか？</span>
                        <svg class="faq-item__icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <div class="faq-item__answer">
                        <p>通常、3〜5営業日で導入が完了します。既存データの移行が必要な場合は、データ量に応じて1〜2週間程度かかる場合がございます。専任のカスタマーサクセスがサポートいたしますので、ご安心ください。</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-item__question">
                        <span>無料トライアル期間中に解約はできますか？</span>
                        <svg class="faq-item__icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <div class="faq-item__answer">
                        <p>はい、可能です。無料トライアル期間中はいつでも解約でき、料金は一切発生しません。トライアル終了後も、月単位での契約となりますので、いつでも解約いただけます。</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-item__question">
                        <span>既存のツールからデータを移行できますか？</span>
                        <svg class="faq-item__icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <div class="faq-item__answer">
                        <p>はい、可能です。Salesforce、HubSpot、Zoho CRMなど、主要なCRMツールからのデータ移行に対応しています。CSVファイルでのインポートも可能です。移行作業はカスタマーサクセスがサポートいたします。</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-item__question">
                        <span>モバイルアプリはありますか？</span>
                        <svg class="faq-item__icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <div class="faq-item__answer">
                        <p>iOS・Android両方のネイティブアプリをご提供しています。外出先でもリード情報の確認・更新、タスク管理が可能です。プッシュ通知機能もあり、重要な案件を逃しません。</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-item__question">
                        <span>セキュリティ対策はどうなっていますか？</span>
                        <svg class="faq-item__icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <div class="faq-item__answer">
                        <p>すべてのデータは256ビットSSL暗号化により保護されています。また、ISO 27001認証を取得しており、定期的なセキュリティ監査を実施しています。データは国内のデータセンターで安全に管理されています。</p>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-item__question">
                        <span>サポート体制について教えてください</span>
                        <svg class="faq-item__icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <div class="faq-item__answer">
                        <p>プランに応じて、メール・チャット・電話でのサポートをご提供しています。プロフェッショナルプラン以上では、専任のカスタマーサクセスが定期的にフォローアップいたします。平日9:00〜18:00の対応となります。</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 最終CTA -->
    <section class="cta" id="cta">
        <div class="cta__background"></div>
        <div class="container">
            <div class="cta__content">
                <h2 class="cta__title">今すぐLeadFlowを始めよう</h2>
                <p class="cta__subtitle">14日間の無料トライアルで、すべての機能をお試しいただけます</p>
                <form class="cta__form">
                    <input type="email" class="cta__input" placeholder="メールアドレスを入力" required>
                    <button type="submit" class="btn btn--primary btn--large">無料で始める</button>
                </form>
                <p class="cta__note">クレジットカード登録不要 / いつでもキャンセル可能</p>
            </div>
        </div>
    </section>

    <!-- フッター -->
    <footer class="footer">
        <div class="container">
            <div class="footer__inner">
                <div class="footer__brand">
                    <div class="footer__logo">LeadFlow</div>
                    <p class="footer__tagline">営業チームの生産性を最大化</p>
                </div>
                <div class="footer__links">
                    <div class="footer__column">
                        <h4 class="footer__heading">製品</h4>
                        <a href="#features">特徴</a>
                        <a href="#functions">機能</a>
                        <a href="#pricing">料金</a>
                    </div>
                    <div class="footer__column">
                        <h4 class="footer__heading">サポート</h4>
                        <a href="#faq">FAQ</a>
                        <a href="#">ヘルプセンター</a>
                        <a href="#">お問い合わせ</a>
                    </div>
                    <div class="footer__column">
                        <h4 class="footer__heading">会社情報</h4>
                        <a href="#">会社概要</a>
                        <a href="#">プライバシーポリシー</a>
                        <a href="#">利用規約</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2024 LeadFlow. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
</body>
</html>
