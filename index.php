<?php
$current_page = 'home';
require_once __DIR__ . '/includes/functions.php';

// お知らせを取得（最新3件）
$news_data = file_get_contents(__DIR__ . '/news/data/articles.json');
$news_json = json_decode($news_data, true);
$all_news = $news_json['articles'] ?? [];
usort($all_news, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});
$latest_news = array_slice($all_news, 0, 3);

// ブログ記事を取得（カテゴリフィルタ対応）
$all_posts = getPosts(BLOG_DATA_PATH);
$selected_category = isset($_GET['category']) ? $_GET['category'] : 'all';

// カテゴリフィルタ適用
if ($selected_category !== 'all') {
    $filtered_posts = array_filter($all_posts, function($post) use ($selected_category) {
        return isset($post['category']) && $post['category'] === $selected_category;
    });
} else {
    $filtered_posts = $all_posts;
}

// 日付順にソート
usort($filtered_posts, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// 最新3件を取得
$latest_posts = array_slice($filtered_posts, 0, 3);

// 全カテゴリを取得（重複なし）
$all_categories = array_unique(array_map(function($post) {
    return $post['category'] ?? '';
}, $all_posts));
$all_categories = array_filter($all_categories); // 空の値を除外
sort($all_categories);

// Head用の変数設定
$page_title = 'ホームページ制作・Web制作｜余日（Yojitsu）';
$page_description = 'ホームページ制作・Web制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。個人事業主・中小企業向けのプロフェッショナルなWeb制作。全国対応。';
$page_keywords = 'ホームページ制作,Web制作,AI,システム開発,中小企業,個人事業主,余日,Yojitsu';
$additional_css = [
    'assets/css/loading.css',
    'assets/css/pages/top.css',
    'assets/css/pages/hero-v2.css'
];

$ogp_tags = <<<'EOD'
    <meta property="og:title" content="ホームページ制作・Web制作｜余日（Yojitsu）">
    <meta property="og:description" content="ホームページ制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。全国対応。">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://yojitu.com/">
    <meta property="og:image" content="https://yojitu.com/assets/images/ogp.jpg">
    <meta property="og:locale" content="ja_JP">
    <meta name="twitter:card" content="summary_large_image">
EOD;

$inline_styles = <<<'EOD'
        /* Critical styles for above-the-fold content */
        body{margin:0;font-family:'Noto Sans JP',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;overflow-x:clip}
        .header{position:fixed;top:0;left:0;width:100%;z-index:1000;background-color:#fff;border-bottom:1px solid #e0e0e0;transition:transform .3s ease}
        .hero{min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;background-color:#f5f5f5}
EOD;

$structured_data = '
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "余日（Yojitsu）",
      "description": "ホームページ制作・Web制作会社。AI活用で1週間で初稿提出、充実のサポート体制。全国対応。個人事業主・中小企業向けのプロフェッショナルなWeb制作。",
      "url": "https://yojitu.com/",
      "telephone": "' . CONTACT_TEL . '",
      "email": "' . CONTACT_EMAIL . '",
      "foundingDate": "2025-05-14",
      "taxID": "T9810094141774",
      "address": {
        "@type": "PostalAddress",
        "addressRegion": "日本",
        "addressCountry": "JP"
      },
      "serviceType": ["ホームページ制作", "Webサイト制作", "ショート動画制作"],
      "keywords": ["ホームページ制作", "Web制作", "AI", "システム開発", "中小企業", "個人事業主"]
    }
';

$faq_structured_data = '
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [
        {
          "@type": "Question",
          "name": "余日（Yojitsu）はどんなサービスを提供していますか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "全国対応で、Web制作（ホームページ制作）、ショート動画制作、システム開発を提供しています。お客様のご要望に応じて最適なプランをご提案いたします。"
          }
        },
        {
          "@type": "Question",
          "name": "全国対応していますか？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "はい、全国対応可能です。Web制作・システム開発はオンラインで完結でき、ショート動画制作も全国対応しております。"
          }
        },
        {
          "@type": "Question",
          "name": "料金体系を教えてください",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "お客様のご予算・ご要望に応じて最適なプランをご提案いたします。お見積りは無料ですので、お気軽にお問い合わせください。"
          }
        },
        {
          "@type": "Question",
          "name": "お問い合わせ方法は？",
          "acceptedAnswer": {
            "@type": "Answer",
            "text": "電話（080-4692-9681）、メール（yamada@yojitu.com）、LINE、お問い合わせフォームからご連絡いただけます。営業時間は10:00〜22:00、年中無休です。"
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

    <!-- 非クリティカルCSS - 遅延読み込み -->
    <link rel="stylesheet" href="assets/css/cookie-consent.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/cookie-consent.css"></noscript>

    <!-- Google Tag Manager - Deferred -->
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    </script>
    <script defer src="https://www.googletagmanager.com/gtag/js?id=GTM-T7NGQDC2"></script>

    <!-- Preload critical resources -->
    <link rel="preload" href="assets/css/base.css" as="style">
    <link rel="preload" href="assets/js/app.js" as="script">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
</head>
<body>
    <!-- ローディングアニメーション -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-content">
            <div class="loader-logo">Yojitsu</div>
            <div class="loader-spinner">
                <div class="loader-dot"></div>
                <div class="loader-dot"></div>
                <div class="loader-dot"></div>
            </div>
            <div class="loader-text">Loading...</div>
            <div class="loader-progress">
                <div class="loader-progress-bar"></div>
            </div>
        </div>
    </div>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- ヒーローセクション -->
    <section class="hero-v2" data-scroll-section>
        <!-- 背景スライダー（絶対配置・独立） -->
        <div class="hero-v2__slider">
            <div class="swiper hero-swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="hero-slide" style="background: linear-gradient(135deg, rgba(139, 117, 97, 0.05) 0%, rgba(139, 117, 97, 0.1) 100%);"></div>
                    </div>
                    <div class="swiper-slide">
                        <div class="hero-slide" style="background: linear-gradient(135deg, rgba(66, 133, 112, 0.05) 0%, rgba(66, 133, 112, 0.1) 100%);"></div>
                    </div>
                    <div class="swiper-slide">
                        <div class="hero-slide" style="background: linear-gradient(135deg, rgba(139, 117, 97, 0.08) 0%, rgba(66, 133, 112, 0.08) 100%);"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 背景装飾 -->
        <div class="hero-v2__bg" data-scroll data-scroll-speed="-2">
            <div class="hero-v2__shape hero-v2__shape--1"></div>
            <div class="hero-v2__shape hero-v2__shape--2"></div>
            <div class="hero-v2__shape hero-v2__shape--3"></div>
            <!-- パーティクル追加 -->
            <div class="hero-v2__particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
        </div>

        <div class="hero-v2__container">
            <!-- メインコンテンツ -->
            <div class="hero-v2__content">
                <div class="hero-v2__label" data-hero-label>
                    <span class="hero-v2__label-num">01</span>
                    <span class="hero-v2__label-text">Digital Marketing</span>
                    <span class="hero-v2__label-line"></span>
                </div>

                <h1 class="hero-v2__title">
                    <span class="hero-v2__title-line" data-hero-title-1>
                        <span class="hero-v2__title-word">本質に</span>
                        <span class="hero-v2__title-word">向き合う</span>
                    </span>
                    <span class="hero-v2__title-line" data-hero-title-2>
                        <span class="hero-v2__title-word">余裕を、</span>
                    </span>
                    <span class="hero-v2__title-line" data-hero-title-3>
                        <span class="hero-v2__title-word">デジタルで</span>
                        <span class="hero-v2__title-word">創る</span>
                    </span>
                </h1>

                <p class="hero-v2__text" data-hero-text>
                    ここから始まる、新しいビジネスの形
                </p>

                <div class="hero-v2__buttons" data-hero-buttons>
                    <a href="contact.php" class="hero-v2__btn hero-v2__btn--primary">
                        <span class="hero-v2__btn-text">お問い合わせ</span>
                        <span class="hero-v2__btn-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M4 10h12M12 6l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </a>
                    <a href="services.php" class="hero-v2__btn hero-v2__btn--secondary">
                        <span class="hero-v2__btn-text">サービス詳細</span>
                    </a>
                </div>
            </div>

            <!-- サイドメタ情報 -->
            <div class="hero-v2__meta">
                <div class="hero-v2__meta-item" data-hero-meta-1>
                    <span class="hero-v2__meta-num">01</span>
                    <span class="hero-v2__meta-text">Web制作</span>
                    <div class="hero-v2__meta-line"></div>
                </div>
                <div class="hero-v2__meta-item" data-hero-meta-2>
                    <span class="hero-v2__meta-num">02</span>
                    <span class="hero-v2__meta-text">動画制作</span>
                    <div class="hero-v2__meta-line"></div>
                </div>
                <div class="hero-v2__meta-item" data-hero-meta-3>
                    <span class="hero-v2__meta-num">03</span>
                    <span class="hero-v2__meta-text">マーケティング</span>
                    <div class="hero-v2__meta-line"></div>
                </div>
            </div>
        </div>

        <!-- スクロールヒント -->
        <div class="hero-v2__scroll" data-hero-scroll>
            <span>Scroll</span>
            <div class="hero-v2__scroll-line"></div>
        </div>
    </section>

    <!-- サービスセクション -->
    <section class="section services-section" id="services">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label animate">Services</span>
                <h2 class="section__title" data-split-text>サービス</h2>
                <p class="section__description animate">
                    デジタルマーケティングの専門知識で、<br>
                    お客様のビジネス成長をトータルサポート
                </p>
            </div>
            <div class="services-grid">
                <div class="service-card animate">
                    <div class="service-card__number">01</div>
                    <div class="service-card__icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3 class="service-card__title">Web制作</h3>
                    <p class="service-card__description">
                        コーポレートサイト・LP・採用サイトの制作。レスポンシブ対応、SEO最適化を標準実装。
                    </p>
                    <div class="service-card__footer">
                        <a href="web-production.php" class="service-card__link">
                            詳しく見る
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="service-card animate">
                    <div class="service-card__number">02</div>
                    <div class="service-card__icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3 class="service-card__title">ショート動画制作</h3>
                    <p class="service-card__description">
                        TikTok・Instagram・YouTubeショート向け。企画から編集まで、SNS映えする動画を制作。
                    </p>
                    <div class="service-card__footer">
                        <a href="video-production.php" class="service-card__link">
                            詳しく見る
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- お知らせセクション -->
    <section class="section section--gray news-section" id="news">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label animate">News</span>
                <h2 class="section__title animate">お知らせ</h2>
            </div>
            <div class="news-grid">
                <?php foreach ($latest_news as $index => $news): ?>
                    <?php
                    $date = new DateTime($news['publishedAt']);
                    $formatted_date = $date->format('Y.m.d');
                    $is_new = (time() - strtotime($news['publishedAt'])) < (7 * 24 * 60 * 60); // 7日以内
                    ?>
                    <a href="news/detail.php?id=<?php echo $news['id']; ?>" class="news-card animate">
                        <div class="news-card__header">
                            <span class="news-card__date"><?php echo h($formatted_date); ?></span>
                            <?php if ($is_new): ?>
                                <span class="news-card__badge">NEW</span>
                            <?php endif; ?>
                        </div>
                        <span class="news-card__category"><?php echo h($news['category']); ?></span>
                        <h3 class="news-card__title"><?php echo h($news['title']); ?></h3>
                        <div class="news-card__arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-xl animate">
                <a href="news/" class="btn btn-secondary">お知らせ一覧</a>
            </div>
        </div>
    </section>

    <!-- ブログセクション -->
    <section class="section blog-section" id="blog">
        <div class="container">
            <div class="section-header">
                <span class="section-header__label animate">Blog</span>
                <h2 class="section__title" data-split-text>ブログ</h2>
                <p class="section__description animate">
                    デジタルマーケティングの最新情報と<br>
                    実践的なノウハウをお届けします
                </p>
            </div>

            <!-- カテゴリフィルタ -->
            <div class="blog-category-filter animate">
                <button data-category="all" class="category-filter-btn active">すべて</button>
                <?php foreach ($all_categories as $category): ?>
                    <button data-category="<?php echo h($category); ?>" class="category-filter-btn"><?php echo h($category); ?></button>
                <?php endforeach; ?>
            </div>

            <div class="blog-preview-grid" id="blogPreviewGrid">
                <?php foreach ($latest_posts as $post): ?>
                    <?php
                    $date = new DateTime($post['publishedAt']);
                    $formatted_date = $date->format('Y.m.d');
                    ?>
                    <a href="blog/detail.php?slug=<?php echo h($post['slug']); ?>" class="blog-preview-card animate">
                        <div class="blog-preview-card__meta">
                            <span class="blog-preview-card__date"><?php echo h($formatted_date); ?></span>
                            <span class="blog-preview-card__category"><?php echo h($post['category']); ?></span>
                        </div>
                        <h3 class="blog-preview-card__title"><?php echo h($post['title']); ?></h3>
                        <p class="blog-preview-card__excerpt"><?php echo h($post['excerpt']); ?></p>
                        <div class="blog-preview-card__arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-xl animate">
                <a href="blog/" class="btn btn-secondary">ブログ一覧</a>
            </div>
        </div>
    </section>

    <!-- エリアセクション -->
    <?php include __DIR__ . '/includes/area-section.php'; ?>

    <script>
    // ブログカテゴリフィルタ（Ajax）
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.category-filter-btn');
        const blogGrid = document.getElementById('blogPreviewGrid');

        filterButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const category = this.dataset.category;

                // アクティブボタンの切り替え
                filterButtons.forEach(function(btn) {
                    btn.classList.remove('active');
                    btn.style.backgroundColor = 'transparent';
                    btn.style.color = 'var(--color-natural-brown)';
                });
                this.classList.add('active');
                this.style.backgroundColor = 'var(--color-natural-brown)';
                this.style.color = '#fff';

                // Ajaxリクエスト
                fetch('/api/blog-filter.php?category=' + encodeURIComponent(category))
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            // グリッドを更新
                            blogGrid.innerHTML = '';
                            data.posts.forEach(function(post) {
                                const card = document.createElement('a');
                                card.href = 'blog/detail.php?slug=' + post.slug;
                                card.className = 'blog-preview-card';
                                card.style.opacity = '1'; // 即座に表示
                                card.innerHTML =
                                    '<div class="blog-preview-card__meta">' +
                                        '<span class="blog-preview-card__date">' + post.date + '</span>' +
                                        '<span class="blog-preview-card__category">' + post.category + '</span>' +
                                    '</div>' +
                                    '<h3 class="blog-preview-card__title">' + post.title + '</h3>' +
                                    '<p class="blog-preview-card__excerpt">' + post.excerpt + '</p>';
                                blogGrid.appendChild(card);
                            });
                        }
                    })
                    .catch(function(error) {
                        console.error('Error:', error);
                    });
            });
        });
    });
    </script>

    <!-- FAQセクション -->
    <section class="faq-section">
        <div class="container">
            <h2 class="section-title">
                <i class="fas fa-question-circle"></i> よくあるご質問
            </h2>
            <p class="section-subtitle">お客様からよくいただくご質問をまとめました</p>

            <div class="faq-list">
                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-q-icon">Q.</span>
                        <span class="faq-q-text">余日（Yojitsu）はどんなサービスを提供していますか？</span>
                        <i class="fas fa-chevron-down faq-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        <span class="faq-a-icon">A.</span>
                        <p>全国対応で、Web制作（ホームページ制作）、ショート動画制作、システム開発を提供しています。お客様のご要望に応じて最適なプランをご提案いたします。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-q-icon">Q.</span>
                        <span class="faq-q-text">全国対応していますか？</span>
                        <i class="fas fa-chevron-down faq-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        <span class="faq-a-icon">A.</span>
                        <p>はい、全国対応可能です。Web制作・システム開発はオンラインで完結でき、ショート動画制作も全国対応しております。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-q-icon">Q.</span>
                        <span class="faq-q-text">料金体系を教えてください</span>
                        <i class="fas fa-chevron-down faq-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        <span class="faq-a-icon">A.</span>
                        <p>お客様のご予算・ご要望に応じて最適なプランをご提案いたします。お見積りは無料ですので、お気軽にお問い合わせください。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-q-icon">Q.</span>
                        <span class="faq-q-text">お問い合わせ方法は？</span>
                        <i class="fas fa-chevron-down faq-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        <span class="faq-a-icon">A.</span>
                        <p>電話（080-4692-9681）、メール（yamada@yojitu.com）、LINE、お問い合わせフォームからご連絡いただけます。営業時間は10:00〜22:00、年中無休です。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-q-icon">Q.</span>
                        <span class="faq-q-text">制作期間はどれくらいですか？</span>
                        <i class="fas fa-chevron-down faq-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        <span class="faq-a-icon">A.</span>
                        <p>プランや規模により異なりますが、約1週間で初稿を提出できます。繁忙期を除き、ほとんどの案件が1ヶ月以内に納品可能です。AI活用により、従来より大幅にスピードアップしています。</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        <span class="faq-q-icon">Q.</span>
                        <span class="faq-q-text">なぜこの価格で提供できるのですか？</span>
                        <i class="fas fa-chevron-down faq-arrow"></i>
                    </button>
                    <div class="faq-answer">
                        <span class="faq-a-icon">A.</span>
                        <p>代表一人で対応しているため人件費を抑えられること、またAIを活用して制作工程を大幅に短縮していることが理由です。品質を落とさずコストダウンを実現しています。</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function toggleFaq(button) {
        const faqItem = button.parentElement;
        const isActive = faqItem.classList.contains('active');

        // すべてのFAQを閉じる
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('active');
        });

        // クリックされたFAQを開く（既に開いていた場合は閉じる）
        if (!isActive) {
            faqItem.classList.add('active');
        }
    }
    </script>

    <!-- CTAセクション -->
    <?php
    $cta_base_path = '';
    $cta_show_info = true;
    include __DIR__ . '/includes/cta.php';
    ?>

    <!-- フッター -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

    <!-- JavaScript - Deferred for better performance -->

    <!-- GSAP plugins for hero animations (core GSAP loaded in head.php) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" integrity="sha512-onMTRKJBKz8M1TnqqDuGBlowlH0ohFzMXYRNebz+yOcc5TQr/zAKsthzhuv0hiyUKEiQEQXEynnXCvNTOk50dg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js" integrity="sha512-1PKqXBz2ju2JcAerHKL0ldg0PT/1vr3LghYAtc59+9xy8e19QEtaNUyt1gprouyWnpOPqNJjL4gXMRMEpHYyLQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Hero section animations -->
    <script defer src="assets/js/hero-animations.js"></script>

    <!-- Rich Animations (Split Text, GPU Acceleration, 60fps) -->
    <script defer src="assets/js/rich-animations.js"></script>

    <!-- Rich interactions (safe version) -->
    <script defer src="assets/js/rich-interactions-safe.js"></script>

    <!-- Common app scripts -->
    <script defer src="assets/js/app.js"></script>
</body>
</html>
