<?php
/**
 * English Blog List Page
 */
define('CURRENT_LANG', 'en');
define('IS_ENGLISH', true);

$current_page = 'blog';
require_once __DIR__ . '/../../includes/functions.php';

// Get blog posts (use same data as Japanese version)
$all_posts = getPosts(BLOG_DATA_PATH);

// Sort by newest first
usort($all_posts, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// Search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Category filter
$category = isset($_GET['category']) ? $_GET['category'] : '';
$posts = $all_posts;

// Search filter
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

// Category filter
if ($category) {
    $posts = array_filter($posts, function($post) use ($category) {
        return $post['category'] === $category;
    });
}

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pagination = getPagination(count($posts), BLOG_PER_PAGE, $page);
$paged_posts = array_slice($posts, $pagination['offset'], BLOG_PER_PAGE);

// Get all categories
$categories = array_unique(array_column($all_posts, 'category'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Yojitsu Blog - Latest information and practical know-how on SEO, advertising, and web development.">
    <title>Blog | Yojitsu</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->
    <?php require_once __DIR__ . '/../../includes/favicon.php'; ?>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">
    <link rel="stylesheet" href="../../assets/css/base.css">
    <link rel="stylesheet" href="../../assets/css/pages/blog.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-header__title">Blog</h1>
            <p class="page-header__description">
                Latest information and practical know-how<br>
                on digital marketing
            </p>
        </div>
    </section>

    <!-- Search Bar -->
    <section class="blog-search">
        <div class="container">
            <form action="index.php" method="get" class="search-form">
                <div class="search-input-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text"
                           name="search"
                           class="search-input"
                           placeholder="Search articles..."
                           value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if ($search): ?>
                    <a href="index.php<?php echo $category ? '?category=' . urlencode($category) : ''; ?>" class="search-clear" title="Clear search">
                        <i class="fas fa-times"></i>
                    </a>
                    <?php endif; ?>
                </div>
                <?php if ($category): ?>
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                    <span>Search</span>
                </button>
            </form>
            <?php if ($search): ?>
            <p class="search-result-info">
                Search results for "<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>": <?php echo count($posts); ?> articles
            </p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Category Filter -->
    <section class="blog-filter">
        <div class="container">
            <div class="filter-tabs">
                <a href="index.php" class="filter-tab <?php echo $category === '' ? 'filter-tab--active' : ''; ?>">All</a>
                <?php foreach ($categories as $cat): ?>
                <a href="index.php?category=<?php echo urlencode($cat); ?>"
                   class="filter-tab <?php echo $category === $cat ? 'filter-tab--active' : ''; ?>">
                    <?php echo h($cat); ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Blog List -->
    <section class="blog-list">
        <div class="container">
            <?php if (empty($paged_posts)): ?>
                <p class="no-posts">No articles found.</p>
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

                <!-- Pagination -->
                <?php if ($pagination['total_pages'] > 1): ?>
                <nav class="pagination">
                    <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?php echo $pagination['current_page'] - 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>" class="pagination__link">Previous</a>
                    <?php endif; ?>

                    <span class="pagination__current">
                        <?php echo $pagination['current_page']; ?> / <?php echo $pagination['total_pages']; ?>
                    </span>

                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?php echo $pagination['current_page'] + 1; ?><?php echo $category ? '&category=' . urlencode($category) : ''; ?>" class="pagination__link">Next</a>
                    <?php endif; ?>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h3 class="footer__section-title">Yojitsu</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 16px; line-height: 1.9;">
                        A digital marketing company based in Oita Prefecture, providing SEO, advertising, web development, and short video production services.
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-building" style="margin-right: 8px;"></i>Business Name: Yojitsu<br>
                        <i class="fas fa-file-invoice" style="margin-right: 8px;"></i>Registration: T9810094141774<br>
                        <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>Established: May 14, 2025
                    </p>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">Services</h3>
                    <a href="../../web-production.php" class="footer__link"><i class="fas fa-laptop-code"></i> Web Development</a>
                    <a href="../../video-production.php" class="footer__link"><i class="fas fa-video"></i> Video Production</a>
                    <a href="../../services.php" class="footer__link" style="margin-top: 8px; opacity: 0.8;"><i class="fas fa-arrow-right"></i> Service Details</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">Company</h3>
                    <a href="../../about.php" class="footer__link"><i class="fas fa-info-circle"></i> About Us</a>
                    <a href="../../recruit.php" class="footer__link"><i class="fas fa-handshake"></i> Recruitment</a>
                    <a href="./" class="footer__link"><i class="fas fa-blog"></i> Blog</a>
                    <a href="../../news/" class="footer__link"><i class="fas fa-newspaper"></i> News</a>
                    <a href="../../contact.php" class="footer__link"><i class="fas fa-envelope"></i> Contact</a>
                    <a href="../../privacy.php" class="footer__link"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
                    <a href="../../sitemap-page.php" class="footer__link"><i class="fas fa-sitemap"></i> Sitemap</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">Contact</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 12px; line-height: 1.9;">
                        <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_TEL; ?></a><br>
                        <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_EMAIL; ?></a><br>
                        <i class="fab fa-line" style="margin-right: 8px;"></i>LINE: <a href="https://line.me/ti/p/CTOCx9YKjk" style="color: rgba(255, 255, 255, 0.9);">Contact</a>
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>Hours: 10:00-22:00<br>
                        <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>Open: Every day
                    </p>
                    <div style="margin-top: 16px;">
                        <a href="../../contact.php" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; font-size: 14px;">Contact Form</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 Yojitsu. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="../../assets/js/app.js"></script>
</html>
