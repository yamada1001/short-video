<?php
/**
 * English Blog Detail Page
 */
define('CURRENT_LANG', 'en');
define('IS_ENGLISH', true);

$current_page = 'blog';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/translation.php';

// Get blog posts (use same data as Japanese version)
$posts = getPosts(BLOG_DATA_PATH);
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$post = getArticleBySlug($posts, $slug);

// If article not found
if (!$post) {
    header('HTTP/1.0 404 Not Found');
    echo 'Article not found.';
    exit;
}

// Load content file (from Japanese blog data directory)
if (isset($post['content']) && strpos($post['content'], '.html') !== false) {
    // Use BASE_PATH for reliable path resolution
    $content_file = BASE_PATH . '/blog/' . $post['content'];
    if (file_exists($content_file)) {
        $post['content'] = file_get_contents($content_file);
        // Apply mobile processing
        $post['content'] = processBlogContent($post['content']);
        // Translate content
        $post['content'] = translateContent($post['content'], $post['id'], $post['updatedAt']);
    } else {
        // Debug: show path information if file not found
        $post['content'] = '<p style="color:red;">Debug: File not found</p><p>BASE_PATH: ' . BASE_PATH . '</p><p>Content file: ' . $content_file . '</p>';
    }
}

// Translate post metadata (title, excerpt, category, tags)
$post = translatePost($post);

// Table of contents generation function
function generateToc(&$content) {
    $toc = [];
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="UTF-8">' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $xpath = new DOMXPath($dom);

    $headings = $xpath->query('//h2 | //h3');
    foreach ($headings as $index => $heading) {
        $id = 'heading-' . $index;
        $heading->setAttribute('id', $id);

        $toc[] = [
            'id' => $id,
            'text' => $heading->textContent,
            'level' => $heading->nodeName
        ];
    }

    $content = $dom->saveHTML();
    return $toc;
}

// Generate TOC
$toc = generateToc($post['content']);

// Head variables
$page_title = h($post['title']) . ' | Blog | Yojitsu';
$page_description = h($post['excerpt']);
$css_base_path = '/';
$additional_css = ['assets/css/pages/blog.css', 'assets/css/toc.css', 'assets/css/cookie-consent.css'];

// OGP tags
$ogp_tags = '    <meta property="og:title" content="' . h($post['title']) . '">
    <meta property="og:description" content="' . h($post['excerpt']) . '">
    <meta property="og:type" content="article">
    <meta property="og:url" content="' . SITE_URL . '/en/blog/detail.php?slug=' . urlencode($post['slug']) . '">';
if (!empty($post['thumbnail'])) {
    $ogp_tags .= '
    <meta property="og:image" content="' . h($post['thumbnail']) . '">';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once __DIR__ . '/../../includes/head.php'; ?>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <!-- Blog Article -->
    <article class="blog-article">
        <div class="container">
            <div class="article-layout">
                <!-- Main Content -->
                <div class="article-main">
                    <!-- Breadcrumb -->
                    <nav class="breadcrumb">
                        <a href="../index.php" class="breadcrumb__link">Home</a>
                        <span class="breadcrumb__separator">/</span>
                        <a href="index.php" class="breadcrumb__link">Blog</a>
                        <span class="breadcrumb__separator">/</span>
                        <span class="breadcrumb__current"><?php echo h($post['title']); ?></span>
                    </nav>

                    <!-- Article Header -->
                    <header class="article-header">
                <div class="article-meta">
                    <time class="article-date" datetime="<?php echo h($post['publishedAt']); ?>">
                        <?php echo date('F j, Y', strtotime($post['publishedAt'])); ?>
                    </time>
                    <span class="article-category"><?php echo h($post['category']); ?></span>
                </div>
                <h1 class="article-title"><?php echo h($post['title']); ?></h1>
                <?php if (!empty($post['tags'])): ?>
                <div class="article-tags">
                    <?php foreach ($post['tags'] as $tag): ?>
                    <span class="tag">#<?php echo h($tag); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </header>

            <!-- Lead Text -->
            <?php if (!empty($post['excerpt'])): ?>
            <div class="article-lead">
                <p><?php echo h($post['excerpt']); ?></p>
            </div>
            <?php endif; ?>

            <!-- Thumbnail -->
            <?php if (!empty($post['thumbnail'])): ?>
            <div class="article-thumbnail">
                <img src="<?php echo h($post['thumbnail']); ?>" alt="<?php echo h($post['title']); ?>">
            </div>
            <?php endif; ?>

            <!-- Article Content -->
            <div class="article-content">
                <?php echo $post['content']; ?>
            </div>

            <!-- Article Footer -->
            <footer class="article-footer">
                <p class="article-author">Author: <?php echo h($post['author']); ?></p>
                <?php if ($post['updatedAt'] !== $post['publishedAt']): ?>
                <p class="article-updated">
                    Last updated: <?php echo date('F j, Y', strtotime($post['updatedAt'])); ?>
                </p>
                <?php endif; ?>
            </footer>

            <!-- Related Articles -->
            <?php
            // Get related articles (3 from same category, excluding current)
            $related_posts = array_filter($posts, function($p) use ($post) {
                return $p['category'] === $post['category'] && $p['id'] !== $post['id'];
            });

            // Sort by newest
            usort($related_posts, function($a, $b) {
                return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
            });

            // Max 3 articles
            $related_posts = array_slice($related_posts, 0, 3);

            // Translate related posts
            $related_posts = array_map('translatePost', $related_posts);

            if (!empty($related_posts)):
            ?>
            <section class="related-articles">
                <h2 class="related-articles__title">Related Articles</h2>
                <div class="related-articles__grid">
                    <?php foreach ($related_posts as $related): ?>
                    <article class="related-article-card">
                        <a href="detail.php?slug=<?php echo urlencode($related['slug']); ?>" class="related-article-card__link">
                            <div class="related-article-card__meta">
                                <time class="related-article-card__date" datetime="<?php echo h($related['publishedAt']); ?>">
                                    <?php echo date('Y.m.d', strtotime($related['publishedAt'])); ?>
                                </time>
                                <span class="related-article-card__category"><?php echo h($related['category']); ?></span>
                            </div>
                            <h3 class="related-article-card__title"><?php echo h($related['title']); ?></h3>
                            <?php if (!empty($related['excerpt'])): ?>
                            <p class="related-article-card__excerpt"><?php echo h($related['excerpt']); ?></p>
                            <?php endif; ?>
                        </a>
                    </article>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

                    <!-- Back to List -->
                    <div class="article-back">
                        <a href="index.php" class="btn btn-secondary">Back to Blog List</a>
                    </div>
                </div>

                <!-- TOC Sidebar (PC) -->
                <aside class="article-toc-sidebar" id="tocSidebar">
                    <div class="toc-container">
                        <h2 class="toc-title"><i class="fas fa-list"></i> Table of Contents</h2>
                        <nav class="toc-nav">
                            <?php foreach ($toc as $item): ?>
                                <a href="#<?php echo $item['id']; ?>" class="toc-link toc-link--<?php echo $item['level']; ?>" data-target="<?php echo $item['id']; ?>">
                                    <?php echo h($item['text']); ?>
                                </a>
                            <?php endforeach; ?>
                        </nav>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    <!-- TOC Button (Mobile) -->
    <button class="toc-button" id="tocButton" aria-label="Open table of contents">
        <i class="fas fa-list"></i>
        <span>TOC</span>
    </button>

    <!-- TOC Modal (Mobile) -->
    <div class="toc-modal" id="tocModal">
        <div class="toc-modal__overlay" id="tocModalOverlay"></div>
        <div class="toc-modal__content">
            <div class="toc-modal__header">
                <h2 class="toc-modal__title"><i class="fas fa-list"></i> Table of Contents</h2>
                <button class="toc-modal__close" id="tocModalClose" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="toc-modal__nav">
                <?php foreach ($toc as $item): ?>
                    <a href="#<?php echo $item['id']; ?>" class="toc-modal__link toc-link--<?php echo $item['level']; ?>" data-target="<?php echo $item['id']; ?>">
                        <?php echo h($item['text']); ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>

    <?php
    $footer_base_path = '/';
    include __DIR__ . '/../../includes/footer.php';
    ?>

    <?php include __DIR__ . '/../../includes/cookie-consent.php'; ?>

    <script defer src="/assets/js/app.js"></script>
    <script defer src="/assets/js/toc.js"></script>
</body>
</html>
