<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'ショート動画制作 - SNSで成果を出す動画マーケティング | 余日（Yojitsu）';
$page_description = 'TikTok、Instagram Reels、YouTube Shortsに最適化したショート動画制作。企画から撮影、編集まで一貫サポート。余日（Yojitsu）';
$page_keywords = 'ショート動画,TikTok,Instagram Reels,YouTube Shorts,動画制作,大分,余日';
$additional_css = ['assets/css/cookie-consent.css'];

$structured_data = '
    {
      "@context": "https://schema.org",
      "@type": "Service",
      "serviceType": "ショート動画制作",
      "provider": {
        "@type": "LocalBusiness",
        "name": "余日（Yojitsu）",
        "telephone": "' . CONTACT_TEL . '",
        "email": "' . CONTACT_EMAIL . '",
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
        }
      ]
    }
';

$inline_styles = <<<'EOD'
        .page-header {
            background: var(--color-off-white);
            padding: var(--spacing-xxl) 0;
            text-align: center;
            border-bottom: 1px solid var(--color-light-gray);
        }

        .page-header__title {
            font-size: 36px;
            font-weight: 500;
            margin-bottom: var(--spacing-md);
            letter-spacing: 0.08em;
            color: var(--color-charcoal);
        }

        .page-header__title i {
            color: var(--color-natural-brown);
        }

        .page-header__description {
            font-size: 18px;
            line-height: 1.8;
            color: var(--color-text-light);
            max-width: 800px;
            margin: 0 auto;
        }

        .platforms-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-white);
        }

        .platforms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-xl);
            margin-top: var(--spacing-xl);
        }

        .platform-card {
            background: var(--color-bg-white);
            border: 2px solid var(--color-border);
            border-radius: 12px;
            padding: var(--spacing-xl);
            text-align: center;
            transition: all 0.3s ease;
        }

        .platform-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .platform-card:hover {
            border-color: var(--color-natural-brown);
        }

        .platform-card__icon {
            font-size: 64px;
            margin-bottom: var(--spacing-md);
            color: var(--color-natural-brown);
        }

        .platform-card__name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
            color: var(--color-charcoal);
        }

        .platform-card__description {
            font-size: 14px;
            color: var(--color-text-light);
            line-height: 1.7;
        }

        .pricing-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-gray);
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
            box-shadow: 0 12px 40px rgba(139, 115, 85, 0.15);
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

        .pricing-card__description {
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

        .process-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-white);
        }

        .process-timeline {
            max-width: 800px;
            margin: var(--spacing-xl) auto 0;
            position: relative;
        }

        .process-timeline::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--color-natural-brown);
        }

        .process-step {
            position: relative;
            padding-left: 80px;
            margin-bottom: var(--spacing-xl);
        }

        .process-step__number {
            position: absolute;
            left: 0;
            width: 60px;
            height: 60px;
            background: var(--color-natural-brown);
            color: var(--color-bg-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
        }

        .process-step__title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
            color: var(--color-charcoal);
        }

        .process-step__description {
            font-size: 14px;
            color: var(--color-text-light);
            line-height: 1.7;
        }

        .benefits-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-gray);
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--spacing-lg);
            margin-top: var(--spacing-xl);
        }

        .benefit-card {
            background: var(--color-bg-white);
            padding: var(--spacing-lg);
            border-radius: 8px;
            border-left: 4px solid var(--color-natural-brown);
        }

        .benefit-card__icon {
            font-size: 32px;
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-sm);
        }

        .benefit-card__title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: var(--spacing-sm);
            color: var(--color-charcoal);
        }

        .benefit-card__description {
            font-size: 14px;
            color: var(--color-text-light);
            line-height: 1.7;
        }

        .section__subtitle {
            text-align: center;
            font-size: 28px;
            font-weight: 700;
            margin: var(--spacing-xxl) 0 var(--spacing-md) 0;
            color: var(--color-charcoal);
        }

        .highlight-box {
            background: var(--color-off-white);
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

        .stats-section {
            padding: var(--spacing-xxl) 0;
            background: var(--color-natural-brown);
            color: var(--color-bg-white);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--spacing-xl);
            max-width: 900px;
            margin: var(--spacing-xl) auto 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item__number {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: var(--spacing-xs);
        }

        .stat-item__label {
            font-size: 14px;
            opacity: 0.9;
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
                <i class="fas fa-video"></i> ショート動画制作
            </h1>
            <p class="page-header__description">
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
                <p><i class="fas fa-info-circle"></i> 継続的な発信には10本セットがおすすめです。1本投稿するだけでは効果測定・改善が難しいため、複数本での運用を推奨しています。</p>
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

    <script defer src="assets/js/app.js"></script>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

</body>
</html>
