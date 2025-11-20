<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'Webサイト制作 - 10万円から始めるプロフェッショナルなWeb制作 | 余日（Yojitsu）';
$page_description = '10万円からのWebサイト制作。個人事業主から中小企業まで、目的に応じた最適なプランをご提供。余日（Yojitsu）';
$page_keywords = 'Web制作,ホームページ制作,格安,大分,余日,10万円,30万円,レスポンシブ';
$additional_css = ['assets/css/pages/web-production.css', 'assets/css/cookie-consent.css'];

$structured_data = '
    {
      "@context": "https://schema.org",
      "@type": "Service",
      "serviceType": "Webサイト制作",
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
';

$faq_structured_data = '
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "ホームページ制作の料金はいくらですか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "10万円プラン、30万円プラン、カスタムプランの3種類をご用意しています。10万円プランは個人事業主向けのシンプルなサイト、30万円プランはブログ機能やSEO対策込み、カスタムプランは本格的なシステム開発向けです。"
          }
        },
        {
          "@type": "Question",
          "name": "制作期間はどれくらいですか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "10万円プランで約2〜3週間、30万円プランで約1〜2ヶ月が目安です。カスタムプランは規模により異なります。お急ぎの場合はご相談ください。"
          }
        },
        {
          "@type": "Question",
          "name": "保守・運用は必須ですか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "はい、月額5,800円の保守契約は必須です。テキスト・画像の変更し放題、サーバー・ドメイン管理、セキュリティ対策、電話・メールサポートが含まれます。"
          }
        },
        {
          "@type": "Question",
          "name": "大分県以外でも対応可能ですか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "はい、全国対応可能です。オンラインでのヒアリング・打ち合わせで制作を進められます。"
          }
        },
        {
          "@type": "Question",
          "name": "スマホ対応していますか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "はい、全プランでレスポンシブデザイン（スマホ・タブレット対応）を標準で含んでいます。"
          }
        }
      ]
    }
';
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

            <!-- モバイル用タブ -->
            <div class="plan-tabs">
                <button class="plan-tab active" data-plan="plan-10" onclick="switchPlan('plan-10')">10万円</button>
                <button class="plan-tab" data-plan="plan-30" onclick="switchPlan('plan-30')">30万円</button>
                <button class="plan-tab" data-plan="plan-custom" onclick="switchPlan('plan-custom')">カスタム</button>
            </div>

            <div class="pricing-grid has-tabs">
                <!-- 10万円プラン -->
                <div class="pricing-card active" data-plan="plan-10">
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
                        <li><i class="fas fa-times-circle"></i> <span class="feature-disabled">ブログ機能は含まれません</span></li>
                        <li><i class="fas fa-times-circle"></i> <span class="feature-disabled">更新機能は含まれません</span></li>
                    </ul>
                    <div class="pricing-card__cta">
                        <a href="contact.php" class="btn btn-secondary btn--large">
                            <i class="fas fa-envelope"></i> お問い合わせ
                        </a>
                    </div>
                </div>

                <!-- 30万円プラン -->
                <div class="pricing-card pricing-card--featured" data-plan="plan-30">
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
                        <li><i class="fas fa-check-circle"></i> <span>軽量CMS or 静的サイト生成</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>Google Analytics設定・解説</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>詳細なSEO対策</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>SNS連携設定</span></li>
                        <li><i class="fas fa-check-circle"></i> <span>1ヶ月間の無料サポート</span></li>
                        <li><i class="fas fa-info-circle"></i> <span class="feature-note">※WordPress対応も可能です</span></li>
                    </ul>
                    <div class="pricing-card__cta">
                        <a href="contact.php" class="btn btn-primary btn--large">
                            <i class="fas fa-envelope"></i> お問い合わせ
                        </a>
                    </div>
                </div>

                <!-- カスタムプラン -->
                <div class="pricing-card" data-plan="plan-custom">
                    <div class="pricing-card__icon">
                        <i class="fas fa-crown"></i>
                    </div>
                    <h3 class="pricing-card__name">カスタムプラン</h3>
                    <div class="pricing-card__price">
                        お見積り
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
                        <a href="contact.php" class="btn btn-secondary btn--large">
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
                    <small class="story-content__note">（2ヶ月以上かかりましたが、笑）</small>
                </p>

                <h3><i class="fas fa-comments"></i> まずは相談だけでも大歓迎です</h3>
                <p>
                    全然、検討段階でも大丈夫です。<br>
                    話だけ聞いて、誰かに紹介してくれる人なんかも大歓迎です。
                </p>
                <div class="story-content__cta">
                    <a href="contact.php" class="btn btn-primary btn--large">
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

            <div class="highlight-box highlight-box--margin-top">
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
                <a href="https://migration.oita-creative.jp/lp2/" target="_blank" rel="noopener noreferrer" class="portfolio-item-wrapper">
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
                        </div>
                    </div>
                </a>

                <a href="https://migration.oita-creative.jp/2025/lp/" target="_blank" rel="noopener noreferrer" class="portfolio-item-wrapper">
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
                        </div>
                    </div>
                </a>

                <a href="/" class="portfolio-item-wrapper">
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
                        </div>
                    </div>
                </a>
            </div>

            <div class="portfolio-section__cta">
                <a href="contact.php" class="btn btn-primary btn--large">
                    <i class="fas fa-envelope"></i> あなたのプロジェクトについて相談する
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

    <!-- GSAP & ScrollTrigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script defer src="assets/js/app.js"></script>
    <script defer src="assets/js/web-production.js"></script>

    <script>
    function switchPlan(planId) {
        // タブの切り替え
        document.querySelectorAll('.plan-tab').forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.plan === planId) {
                tab.classList.add('active');
            }
        });
        // カードの切り替え
        document.querySelectorAll('.pricing-card[data-plan]').forEach(card => {
            card.classList.remove('active');
            if (card.dataset.plan === planId) {
                card.classList.add('active');
            }
        });
    }
    </script>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

</body>
</html>
