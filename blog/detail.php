<?php
$current_page = 'blog';
require_once __DIR__ . '/../includes/functions.php';

// ブログ記事取得
$posts = getPosts(BLOG_DATA_PATH);
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$post = getArticleBySlug($posts, $slug);

// 記事が見つからない場合
if (!$post) {
    header('HTTP/1.0 404 Not Found');
    echo '記事が見つかりません。';
    exit;
}

// コンテンツファイルの読み込み
if (isset($post['content']) && strpos($post['content'], '.html') !== false) {
    $content_file = __DIR__ . '/' . $post['content'];
    if (file_exists($content_file)) {
        $post['content'] = file_get_contents($content_file);
        // モバイル対応処理を適用
        $post['content'] = processBlogContent($post['content']);
    }
}

// 目次生成関数
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

// 目次を生成（参照渡しでコンテンツにIDを追加）
$toc = generateToc($post['content']);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo h($post['excerpt']); ?>">
    <title><?php echo h($post['title']); ?> | ブログ | 余日（Yojitsu）</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->

    <!-- OGP -->
    <meta property="og:title" content="<?php echo h($post['title']); ?>">
    <meta property="og:description" content="<?php echo h($post['excerpt']); ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/blog/detail.php?slug=<?php echo urlencode($post['slug']); ?>">
    <?php if (!empty($post['thumbnail'])): ?>
    <meta property="og:image" content="<?php echo h($post['thumbnail']); ?>">
    <?php endif; ?>

    <?php require_once __DIR__ . '/../includes/favicon.php'; ?>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/pages/blog.css">
    <link rel="stylesheet" href="../assets/css/toc.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- ブログ記事 -->
    <article class="blog-article">
        <div class="container">
            <div class="article-layout">
                <!-- メインコンテンツ -->
                <div class="article-main">
                    <!-- パンくずリスト -->
                    <nav class="breadcrumb">
                        <a href="../index.php" class="breadcrumb__link">ホーム</a>
                        <span class="breadcrumb__separator">/</span>
                        <a href="index.php" class="breadcrumb__link">ブログ</a>
                        <span class="breadcrumb__separator">/</span>
                        <span class="breadcrumb__current"><?php echo h($post['title']); ?></span>
                    </nav>

                    <!-- 記事ヘッダー -->
                    <header class="article-header">
                <div class="article-meta">
                    <time class="article-date" datetime="<?php echo h($post['publishedAt']); ?>">
                        <?php echo formatDate($post['publishedAt'], 'Y年m月d日'); ?>
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

            <!-- サムネイル画像 -->
            <?php if (!empty($post['thumbnail'])): ?>
            <div class="article-thumbnail">
                <img src="<?php echo h($post['thumbnail']); ?>" alt="<?php echo h($post['title']); ?>">
            </div>
            <?php endif; ?>

            <!-- 記事本文 -->
            <div class="article-content">
                <?php echo $post['content']; ?>
            </div>

            <!-- 記事フッター -->
            <footer class="article-footer">
                <p class="article-author">執筆: <?php echo h($post['author']); ?></p>
                <?php if ($post['updatedAt'] !== $post['publishedAt']): ?>
                <p class="article-updated">
                    最終更新: <?php echo formatDate($post['updatedAt'], 'Y年m月d日'); ?>
                </p>
                <?php endif; ?>
            </footer>

            <!-- 関連記事 -->
            <?php
            // 関連記事の取得（同じカテゴリから3記事、現在の記事を除く）
            $related_posts = array_filter($posts, function($p) use ($post) {
                return $p['category'] === $post['category'] && $p['id'] !== $post['id'];
            });

            // 新しい順にソート
            usort($related_posts, function($a, $b) {
                return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
            });

            // 最大3件
            $related_posts = array_slice($related_posts, 0, 3);

            if (!empty($related_posts)):
            ?>
            <section class="related-articles">
                <h2 class="related-articles__title">関連記事</h2>
                <div class="related-articles__grid">
                    <?php foreach ($related_posts as $related): ?>
                    <article class="related-article-card">
                        <a href="detail.php?slug=<?php echo urlencode($related['slug']); ?>" class="related-article-card__link">
                            <div class="related-article-card__meta">
                                <time class="related-article-card__date" datetime="<?php echo h($related['publishedAt']); ?>">
                                    <?php echo formatDate($related['publishedAt'], 'Y.m.d'); ?>
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

                    <!-- 一覧に戻る -->
                    <div class="article-back">
                        <a href="index.php" class="btn btn-secondary">ブログ一覧に戻る</a>
                    </div>
                </div>

                <!-- 目次サイドバー（PC版） -->
                <aside class="article-toc-sidebar" id="tocSidebar">
                    <div class="toc-container">
                        <h2 class="toc-title"><i class="fas fa-list"></i> 目次</h2>
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

    <!-- 目次ボタン（SP版） -->
    <button class="toc-button" id="tocButton" aria-label="目次を開く">
        <i class="fas fa-list"></i>
        <span>目次</span>
    </button>

    <!-- 目次モーダル（SP版） -->
    <div class="toc-modal" id="tocModal">
        <div class="toc-modal__overlay" id="tocModalOverlay"></div>
        <div class="toc-modal__content">
            <div class="toc-modal__header">
                <h2 class="toc-modal__title"><i class="fas fa-list"></i> 目次</h2>
                <button class="toc-modal__close" id="tocModalClose" aria-label="閉じる">
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
                    <a href="../about.php" class="footer__link"><i class="fas fa-info-circle"></i> 会社概要</a>
                    <a href="../recruit.php" class="footer__link"><i class="fas fa-handshake"></i> 業務委託募集・交流</a>
                    <a href="../blog/" class="footer__link"><i class="fas fa-blog"></i> ブログ</a>
                    <a href="../news/" class="footer__link"><i class="fas fa-newspaper"></i> お知らせ</a>
                    <a href="../contact.php" class="footer__link"><i class="fas fa-envelope"></i> お問い合わせ</a>
                    <a href="../privacy.php" class="footer__link"><i class="fas fa-shield-alt"></i> プライバシーポリシー</a>
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
                        <a href="../contact.php" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; font-size: 14px;">お問い合わせフォーム</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="../assets/js/app.js"></script>
    <script defer src="../assets/js/toc.js"></script>
</body>
</html>
