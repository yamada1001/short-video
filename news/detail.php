<?php
require_once __DIR__ . '/../includes/functions.php';

// 記事データ取得
$articles = getArticles(NEWS_DATA_PATH);
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$article = getArticleById($articles, $id);

// 記事が見つからない場合
if (!$article) {
    header('HTTP/1.0 404 Not Found');
    echo '記事が見つかりません。';
    exit;
}

// 前後の記事を取得
usort($articles, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

$current_index = array_search($article, $articles);
$prev_article = isset($articles[$current_index + 1]) ? $articles[$current_index + 1] : null;
$next_article = isset($articles[$current_index - 1]) ? $articles[$current_index - 1] : null;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo h(strip_tags(mb_substr($article['content'], 0, 120))); ?>">
    <title><?php echo h($article['title']); ?> | お知らせ | 余日（Yojitsu）</title>
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="stylesheet" href="../assets/css/reset.css">
    <link rel="stylesheet" href="../assets/css/common.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/news.css">
</head>
<body>
    <!-- ヘッダー -->
    <header class="header" id="header">
        <div class="container header__container">
            <a href="../index.html" class="header__logo">余日</a>
            <nav class="nav">
                <ul class="nav__list" id="navList">
                    <li><a href="../index.html#services" class="nav__link">サービス</a></li>
                    <li><a href="../news/" class="nav__link nav__link--active">お知らせ</a></li>
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

    <!-- お知らせ詳細 -->
    <section class="news-detail">
        <div class="container news-detail-container">
            <!-- ブレッドクラム -->
            <nav class="breadcrumb">
                <ul class="breadcrumb__list">
                    <li class="breadcrumb__item"><a href="../index.html">ホーム</a></li>
                    <li class="breadcrumb__item"><a href="../news/">お知らせ</a></li>
                    <li class="breadcrumb__item"><?php echo h($article['title']); ?></li>
                </ul>
            </nav>

            <!-- 記事ヘッダー -->
            <header class="news-detail__header">
                <div class="news-detail__meta">
                    <span class="news-detail__date"><?php echo h(formatDate($article['publishedAt'])); ?></span>
                    <span class="news-detail__category"><?php echo h($article['category']); ?></span>
                </div>
                <h1 class="news-detail__title"><?php echo h($article['title']); ?></h1>
                <?php if ($article['publishedAt'] !== $article['updatedAt']): ?>
                <p class="news-detail__updated">更新日：<?php echo h(formatDate($article['updatedAt'])); ?></p>
                <?php endif; ?>
            </header>

            <!-- 記事本文 -->
            <div class="news-detail__content">
                <?php echo $article['content']; ?>
            </div>

            <!-- 前後の記事ナビゲーション -->
            <nav class="news-nav">
                <?php if ($prev_article): ?>
                <a href="detail.php?id=<?php echo h($prev_article['id']); ?>" class="news-nav__button news-nav__button--prev">
                    <div>
                        <span class="news-nav__label">← 前の記事</span>
                        <span class="news-nav__title"><?php echo h($prev_article['title']); ?></span>
                    </div>
                </a>
                <?php endif; ?>

                <?php if ($next_article): ?>
                <a href="detail.php?id=<?php echo h($next_article['id']); ?>" class="news-nav__button news-nav__button--next">
                    <div>
                        <span class="news-nav__label">次の記事 →</span>
                        <span class="news-nav__title"><?php echo h($next_article['title']); ?></span>
                    </div>
                </a>
                <?php endif; ?>
            </nav>

            <!-- 一覧へ戻るボタン -->
            <div class="text-center" style="margin-top: 60px;">
                <a href="../news/" class="btn btn-secondary">お知らせ一覧へ戻る</a>
            </div>
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
