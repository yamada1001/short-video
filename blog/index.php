<?php
require_once __DIR__ . '/../includes/functions.php';

// ブログ記事取得
$posts = getPosts(BLOG_DATA_PATH);

// 新しい順に並べ替え
usort($posts, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// カテゴリフィルター
$category = isset($_GET['category']) ? $_GET['category'] : '';
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
$all_posts = getPosts(BLOG_DATA_PATH);
$categories = array_unique(array_column($all_posts, 'category'));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="余日（Yojitsu）のブログ。SEO、広告運用、Web制作に関する最新情報とノウハウをお届けします。">
    <title>ブログ | 余日（Yojitsu）</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/pages/blog.css">
</head>
<body>
    <!-- ヘッダー -->
    <header class="header" id="header">
        <div class="container header__container">
            <a href="../index.html" class="header__logo">余日</a>
            <nav class="nav">
                <ul class="nav__list" id="navList">
                    <li><a href="../index.html#services" class="nav__link">サービス</a></li>
                    <li><a href="../news/" class="nav__link">お知らせ</a></li>
                    <li><a href="../blog/" class="nav__link nav__link--active">ブログ</a></li>
                    <li><a href="../about.html" class="nav__link">会社概要</a></li>
                    <li><a href="../contact.html" class="nav__link">お問い合わせ</a></li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <span class="hamburger__line"></span>
                    <span class="hamburger__line"></span>
                    <span class="hamburger__line"></span>
                </div>
            </nav>
        </div>
    </header>

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
                    <p style="color: rgba(255, 255, 255, 0.8);">
                        大分県を拠点としたデジタルマーケティング・Web制作会社
                    </p>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">サービス</h3>
                    <a href="../services.html#seo" class="footer__link">SEO対策</a>
                    <a href="../services.html#ads" class="footer__link">広告運用</a>
                    <a href="../services.html#web" class="footer__link">Web制作</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">企業情報</h3>
                    <a href="../about.html" class="footer__link">会社概要</a>
                    <a href="../news/" class="footer__link">お知らせ</a>
                    <a href="../blog/" class="footer__link">ブログ</a>
                    <a href="../contact.html" class="footer__link">お問い合わせ</a>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/fontawesome-init.js"></script>
    <script src="../assets/js/nav.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html>
