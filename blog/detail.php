<?php
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
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo h($post['excerpt']); ?>">
    <title><?php echo h($post['title']); ?> | ブログ | 余日（Yojitsu）</title>

    <!-- OGP -->
    <meta property="og:title" content="<?php echo h($post['title']); ?>">
    <meta property="og:description" content="<?php echo h($post['excerpt']); ?>">
    <meta property="og:type" content="article">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/blog/detail.php?slug=<?php echo urlencode($post['slug']); ?>">
    <?php if (!empty($post['thumbnail'])): ?>
    <meta property="og:image" content="<?php echo h($post['thumbnail']); ?>">
    <?php endif; ?>

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

    <!-- ブログ記事 -->
    <article class="blog-article">
        <div class="container container--narrow">
            <!-- パンくずリスト -->
            <nav class="breadcrumb">
                <a href="../index.html" class="breadcrumb__link">ホーム</a>
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

            <!-- 一覧に戻る -->
            <div class="article-back">
                <a href="index.php" class="btn btn-secondary">ブログ一覧に戻る</a>
            </div>
        </div>
    </article>

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
                    <a href="../services.html#seo" class="footer__link"><i class="fas fa-search"></i> SEO対策</a>
                    <a href="../services.html#ads" class="footer__link"><i class="fas fa-bullhorn"></i> 広告運用</a>
                    <a href="../services.html#web" class="footer__link"><i class="fas fa-laptop-code"></i> Web制作</a>
                    <a href="../services.html#short-video" class="footer__link"><i class="fas fa-video"></i> ショート動画制作</a>
                    <a href="../services.html" class="footer__link" style="margin-top: 8px; opacity: 0.8;"><i class="fas fa-arrow-right"></i> サービス詳細</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">企業情報</h3>
                    <a href="../about.html" class="footer__link"><i class="fas fa-info-circle"></i> 会社概要</a>
                    <a href="../recruit.html" class="footer__link"><i class="fas fa-handshake"></i> 業務委託募集・交流</a>
                    <a href="../blog/" class="footer__link"><i class="fas fa-blog"></i> ブログ</a>
                    <a href="../news/" class="footer__link"><i class="fas fa-newspaper"></i> お知らせ</a>
                    <a href="../contact.html" class="footer__link"><i class="fas fa-envelope"></i> お問い合わせ</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">お問い合わせ</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 12px; line-height: 1.9;">
                        <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:08046929681" style="color: rgba(255, 255, 255, 0.9);">080-4692-9681</a><br>
                        <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:yamada@yojitu.com" style="color: rgba(255, 255, 255, 0.9);">yamada@yojitu.com</a><br>
                        <i class="fab fa-line" style="margin-right: 8px;"></i>LINE: <a href="https://line.me/ti/p/CTOCx9YKjk" style="color: rgba(255, 255, 255, 0.9);">お問い合わせ</a>
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>営業時間: 起きてから寝るまで<br>
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

    <script src="../assets/js/fontawesome-init.js"></script>
    <script src="../assets/js/nav.js"></script>
    <script src="../assets/js/common.js"></script>
</body>
</html>
