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

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/news.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <!-- ヘッダー -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- お知らせ詳細 -->
    <section class="news-detail">
        <div class="container news-detail-container">
            <!-- パンくずリスト -->
            <nav class="breadcrumb">
                <a href="../index.html" class="breadcrumb__link">ホーム</a>
                <span class="breadcrumb__separator">/</span>
                <a href="../news/" class="breadcrumb__link">お知らせ</a>
                <span class="breadcrumb__separator">/</span>
                <span class="breadcrumb__current"><?php echo h($article['title']); ?></span>
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
    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="../assets/js/fontawesome-init.js"></script>
    <script defer src="../assets/js/app.js"></script>
</html>
