<?php
$current_page = 'blog';
require_once __DIR__ . '/../includes/functions.php';

// ブログ記事取得（1回のみ）
$all_posts = getPosts(BLOG_DATA_PATH);

// 新しい順に並べ替え
usort($all_posts, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// 検索クエリ
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// カテゴリフィルター
$category = isset($_GET['category']) ? $_GET['category'] : '';
$posts = $all_posts;

// 検索フィルター（タイトル、要約、タグで検索）
if ($search) {
    $posts = array_filter($posts, function($post) use ($search) {
        $searchLower = mb_strtolower($search);
        $titleMatch = mb_strpos(mb_strtolower($post['title']), $searchLower) !== false;
        $excerptMatch = mb_strpos(mb_strtolower($post['excerpt']), $searchLower) !== false;
        $tagsMatch = false;
        if (!empty($post['tags'])) {
            foreach ($post['tags'] as $tag) {
                if (mb_strpos(mb_strtolower($tag), $searchLower) !== false) {
                    $tagsMatch = true;
                    break;
                }
            }
        }
        return $titleMatch || $excerptMatch || $tagsMatch;
    });
}

// カテゴリでさらに絞り込み
if ($category) {
    $posts = array_filter($posts, function($post) use ($category) {
        return $post['category'] === $category;
    });
}

// ページネーション
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pagination = getPagination(count($posts), BLOG_PER_PAGE, $page);
$paged_posts = array_slice($posts, $pagination['offset'], BLOG_PER_PAGE);

// 全カテゴリ取得
$categories = array_unique(array_column($all_posts, 'category'));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="余日（Yojitsu）のブログ。SEO、広告運用、Web制作に関する最新情報とノウハウをお届けします。">
    <title>ブログ | 余日（Yojitsu）</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/pages/blog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-header__title">ブログ</h1>
            <p class="page-header__description">
                デジタルマーケティングの最新情報と<br>
                実践的なノウハウをお届けします
            </p>
        </div>
    </section>

    <!-- 検索バー -->
    <section class="blog-search">
        <div class="container">
            <form action="index.php" method="get" class="search-form">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text"
                           name="search"
                           class="search-input"
                           placeholder="記事を検索..."
                           value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if ($search): ?>
                    <a href="index.php<?php echo $category ? '?category=' . urlencode($category) : ''; ?>" class="search-clear" title="検索をクリア">
                        <i class="fas fa-times"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php if ($category): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                    <span>検索</span>
                </button>
            </form>
            <?php if ($search): ?>
            <p class="search-result-info">
                「<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>」の検索結果: <?php echo count($posts); ?>件
            </p>
            <?php endif; ?>
        </div>
    </section>

    <!-- カテゴリフィルター -->
    <section class="blog-filter">
        <div class="container">
            <div class="filter-tabs">
                <a href="index.php" class="filter-tab <?php echo $category === '' ? 'filter-tab--active' : ''; ?>">すべて</a>
                <?php foreach ($categories as $cat): ?>
                <a href="index.php?category=<?php echo urlencode($cat); ?>"
                   class="filter-tab <?php echo $category === $cat ? 'filter-tab--active' : ''; ?>">
                    <?php echo h($cat); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ブログ一覧 -->
    <section class="blog-list">
        <div class="container">
            <?php if (empty($paged_posts)): ?>
                <p class="no-posts">記事が見つかりませんでした。</p>
            <?php else: ?>
                <div class="blog-grid">
                    <?php foreach ($paged_posts as $post): ?>
                    <article class="blog-card animate">
                        <a href="detail.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-card__link">
                            <?php if (!empty($post['thumbnail'])): ?>
                            <div class="blog-card__image">
                                <img src="<?php echo h($post['thumbnail']); ?>" alt="<?php echo h($post['title']); ?>">
                            </div>
                            <?php endif; ?>
                            <div class="blog-card__content">
                                <div class="blog-card__meta">
                                    <time class="blog-card__date" datetime="<?php echo h($post['publishedAt']); ?>">
                                        <?php echo formatDate($post['publishedAt']); ?>
                                    </time>
                                    <span class="blog-card__category"><?php echo h($post['category']); ?></span>
                                </div>
                                <h2 class="blog-card__title"><?php echo h($post['title']); ?></h2>
                                <p class="blog-card__excerpt"><?php echo h($post['excerpt']); ?></p>
                                <?php if (!empty($post['tags'])): ?>
                                <div class="blog-card__tags">
                                    <?php foreach ($post['tags'] as $tag): ?>
                                    <span class="tag">#<?php echo h($tag); ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </a>
                    </article>
                    <?php endforeach; ?>
                </div>

                <!-- ページネーション -->
                <?php if ($pagination['total_pages'] > 1): ?>
                <nav class="pagination">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>" class="pagination__link">前へ</a>
                    <?php endif; ?>

                    <span class="pagination__current">
                        <?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_pages']; ?>
                    </span>

                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>" class="pagination__link">次へ</a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

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
                    <a href="../web-production.php" class="footer__link"><i class="fas fa-laptop-code"></i> Web制作</a>
                    <a href="../video-production.php" class="footer__link"><i class="fas fa-video"></i> ショート動画制作</a>
                    <a href="../services.php" class="footer__link" style="margin-top: 8px; opacity: 0.8;"><i class="fas fa-arrow-right"></i> サービス詳細</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">企業情報</h3>
                    <a href="../about.html" class="footer__link"><i class="fas fa-info-circle"></i> 会社概要</a>
                    <a href="../recruit.php" class="footer__link"><i class="fas fa-handshake"></i> 業務委託募集・交流</a>
                    <a href="../blog/" class="footer__link"><i class="fas fa-blog"></i> ブログ</a>
                    <a href="../news/" class="footer__link"><i class="fas fa-newspaper"></i> お知らせ</a>
                    <a href="../contact.html" class="footer__link"><i class="fas fa-envelope"></i> お問い合わせ</a>
                    <a href="../privacy.html" class="footer__link"><i class="fas fa-shield-alt"></i> プライバシーポリシー</a>
                    <a href="../sitemap-page.php" class="footer__link"><i class="fas fa-sitemap"></i> サイトマップ</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">お問い合わせ</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 12px; line-height: 1.9;">
                        <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_TEL; ?></a><br>
                        <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_EMAIL; ?></a><br>
                        <i class="fab fa-line" style="margin-right: 8px;"></i>LINE: <a href="https://line.me/ti/p/CTOCx9YKjk" style="color: rgba(255, 255, 255, 0.9);">お問い合わせ</a>
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>営業時間: 10時~22時<br>
                        <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>定休日: なし
                    </p>
                    <div style="margin-top: 16px;">
                        <a href="../contact.html" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; font-size: 14px;">お問い合わせフォーム</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="../assets/js/app.js"></script>
</html>
