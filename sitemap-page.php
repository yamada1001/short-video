<?php
$current_page = '';
require_once __DIR__ . '/includes/functions.php';

// ブログ記事を取得
$posts = getPosts(BLOG_DATA_PATH);

// カテゴリごとに記事を分類
$categories = [];
foreach ($posts as $post) {
    $category = $post['category'];
    if (!isset($categories[$category])) {
        $categories[$category] = [];
    }
    $categories[$category][] = $post;
}

// 各カテゴリ内で新しい順にソート
foreach ($categories as &$category_posts) {
    usort($category_posts, function($a, $b) {
        return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
    });
}

// Head用の変数設定
$page_title = 'サイトマップ | 余日（Yojitsu）';
$page_description = '余日（Yojitsu）のサイトマップ。全ページへのリンクを掲載しています。';
$additional_css = ['assets/css/pages/sitemap.css', 'assets/css/cookie-consent.css'];
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
            <h1 class="page-header__title">サイトマップ</h1>
            <p class="page-header__description">
                当サイト内の全ページへのリンク集です
            </p>
        </div>
    </section>

    <!-- サイトマップコンテンツ -->
    <section class="sitemap-content">
        <div class="container">
            <div class="sitemap-grid">
                <!-- メインページ -->
                <div class="sitemap-section">
                    <h2 class="sitemap-section__title">
                        <i class="fas fa-home"></i> メインページ
                    </h2>
                    <ul class="sitemap-list">
                        <li><a href="index.php" class="sitemap-link">トップページ</a></li>
                        <li><a href="services.php" class="sitemap-link">サービス</a></li>
                        <li><a href="about.php" class="sitemap-link">会社概要</a></li>
                        <li><a href="contact.php" class="sitemap-link">お問い合わせ</a></li>
                    </ul>
                </div>

                <!-- ブログ -->
                <div class="sitemap-section">
                    <h2 class="sitemap-section__title">
                        <i class="fas fa-blog"></i> ブログ
                    </h2>
                    <ul class="sitemap-list">
                        <li><a href="blog/" class="sitemap-link">ブログトップ</a></li>
                    </ul>

                    <?php foreach ($categories as $category_name => $category_posts): ?>
                    <h3 class="sitemap-category"><?php echo h($category_name); ?></h3>
                    <ul class="sitemap-list sitemap-list--nested">
                        <?php foreach (array_slice($category_posts, 0, 10) as $post): ?>
                        <li>
                            <a href="blog/detail.php?slug=<?php echo urlencode($post['slug']); ?>" class="sitemap-link sitemap-link--small">
                                <?php echo h($post['title']); ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <?php if (count($category_posts) > 10): ?>
                        <li class="sitemap-more">
                            <a href="blog/?category=<?php echo urlencode($category_name); ?>" class="sitemap-link sitemap-link--more">
                                <?php echo $category_name; ?>の記事をもっと見る（<?php echo count($category_posts); ?>件）
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                    <?php endforeach; ?>
                </div>

                <!-- その他 -->
                <div class="sitemap-section">
                    <h2 class="sitemap-section__title">
                        <i class="fas fa-file-alt"></i> その他
                    </h2>
                    <ul class="sitemap-list">
                        <li><a href="privacy.php" class="sitemap-link">プライバシーポリシー</a></li>
                        <li><a href="sitemap-page.php" class="sitemap-link">サイトマップ</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

    <script defer src="assets/js/app.js"></script>

</body>
</html>
