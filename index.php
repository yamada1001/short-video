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
?>
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
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://www.googletagmanager.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <?php require_once __DIR__ . '/includes/favicon.php'; ?>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">

    <!-- Critical CSS - Inline for faster FCP -->
    <style>
        /* Critical styles for above-the-fold content */
        body{margin:0;font-family:'Noto Sans JP',-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;overflow-x:clip}
        .header{position:fixed;top:0;left:0;width:100%;z-index:1000;background-color:#fff;border-bottom:1px solid #e0e0e0;transition:transform .3s ease}
        .hero{min-height:100vh;display:flex;align-items:center;justify-content:center;position:relative;background-color:#f5f5f5}
    </style>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/top.css">

    <!-- 非クリティカルCSS - 遅延読み込み -->
    <link rel="stylesheet" href="assets/css/cookie-consent.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="assets/css/cookie-consent.css"></noscript>

    <!-- Font Awesome - Async load for non-blocking -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- Google Tag Manager - Deferred -->
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    </script>
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-T7NGQDC2"></script>

    <!-- 構造化データ -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "余日（Yojitsu）",
      "description": "デジタルマーケティング・Web制作会社",
      "url": "https://yojitu.com/",
      "telephone": "<?php echo CONTACT_TEL; ?>",
      "email": "<?php echo CONTACT_EMAIL; ?>",
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
                <path class="hero__arrow" d="M 900 150 L 1050 100 M 1040 110 L 1050 100 L 1035 105" stroke="#63584C" stroke-width="3" fill="none" opacity="0.4" stroke-linecap="round" stroke-linejoin="round"/>
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
                <a href="contact.php" class="btn btn-primary btn--large">
                    <span>お問い合わせ</span>
                    <i class="fas fa-arrow-right"></i>
                </a>
                <a href="services.php" class="btn btn-secondary btn--large">
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
                    <a href="web-production.php" class="btn btn-secondary">詳しく見る</a>
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
                    <a href="video-production.php" class="btn btn-secondary">詳しく見る</a>
                </div>
            </div>
        </div>
    </section>

    <!-- お知らせセクション -->
    <section class="section section--gray" id="news">
        <div class="container">
            <h2 class="section__title animate">お知らせ</h2>
            <div class="news-list">
                <?php foreach ($latest_news as $index => $news): ?>
                    <?php
                    $date = new DateTime($news['publishedAt']);
                    $formatted_date = $date->format('Y.m.d');
                    $is_new = (time() - strtotime($news['publishedAt'])) < (7 * 24 * 60 * 60); // 7日以内
                    ?>
                    <a href="news/detail.php?id=<?php echo $news['id']; ?>" class="news-item animate">
                        <span class="news-item__date"><?php echo h($formatted_date); ?></span>
                        <span class="news-item__category"><?php echo h($news['category']); ?></span>
                        <span class="news-item__title"><?php echo h($news['title']); ?></span>
                        <?php if ($is_new): ?>
                            <span class="news-item__badge">NEW</span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
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

            <!-- カテゴリフィルタ -->
            <div class="blog-category-filter animate" style="text-align: center; margin-bottom: 40px;">
                <button data-category="all" class="category-filter-btn active" style="display: inline-block; padding: 8px 20px; margin: 4px; border: 1px solid var(--color-natural-brown); border-radius: 20px; background-color: var(--color-natural-brown); color: #fff; cursor: pointer; transition: all 0.3s;">すべて</button>
                <?php foreach ($all_categories as $category): ?>
                    <button data-category="<?php echo h($category); ?>" class="category-filter-btn" style="display: inline-block; padding: 8px 20px; margin: 4px; border: 1px solid var(--color-natural-brown); border-radius: 20px; background-color: transparent; color: var(--color-natural-brown); cursor: pointer; transition: all 0.3s;"><?php echo h($category); ?></button>
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
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-xl animate">
                <a href="blog/" class="btn btn-secondary">ブログ一覧</a>
            </div>
        </div>
    </section>

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

    <!-- CTAセクション -->
    <?php
    $cta_base_path = '';
    $cta_show_info = true;
    include __DIR__ . '/includes/cta.php';
    ?>

    <!-- フッター -->
    <?php include __DIR__ . '/includes/footer.php'; ?>

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

    <!-- JavaScript - Deferred for better performance -->
    <script defer src="assets/js/app.js"></script>
</body>
</html>
