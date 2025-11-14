<?php
require_once __DIR__ . '/../includes/functions.php';

// 記事データ取得
$articles = getArticles(NEWS_DATA_PATH);

// 新しい順に並べ替え
usort($articles, function($a, $b) {
    return strtotime($b['publishedAt']) - strtotime($a['publishedAt']);
});

// ページネーション
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$pagination = getPagination(count($articles), NEWS_PER_PAGE, $page);
$paged_articles = array_slice($articles, $pagination['offset'], NEWS_PER_PAGE);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="余日（Yojitsu）のお知らせ一覧ページです。最新情報をお届けします。">
    <title>お知らせ一覧 | 余日（Yojitsu）</title>

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

    <!-- ページヘッダー -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-header__title">お知らせ</h1>
            <p class="page-header__description">最新情報をお届けします</p>
        </div>
    </section>

    <!-- お知らせ一覧 -->
    <section class="news-list-page">
        <div class="container news-list-container">
            <?php foreach ($paged_articles as $article): ?>
            <a href="detail.php?id=<?php echo h($article['id']); ?>" class="news-list-item">
                <div>
                    <div class="news-list-item__meta">
                        <span class="news-list-item__date"><?php echo h(formatDate($article['publishedAt'])); ?></span>
                        <span class="news-list-item__category"><?php echo h($article['category']); ?></span>
                        <?php if (isNew($article['publishedAt'])): ?>
                        <span class="news-list-item__badge">NEW</span>
                        <?php endif; ?>
                    </div>
                    <h2 class="news-list-item__title"><?php echo h($article['title']); ?></h2>
                </div>
            </a>
            <?php endforeach; ?>

            <!-- ページネーション -->
            <?php if ($pagination['total_pages'] > 1): ?>
            <div class="pagination">
                <?php if ($pagination['has_prev']): ?>
                <a href="?page=<?php echo $pagination['current_page'] - 1; ?>" class="pagination__button">前へ</a>
                <?php else: ?>
                <span class="pagination__button pagination__button--disabled">前へ</span>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <?php if ($i == $pagination['current_page']): ?>
                    <span class="pagination__button pagination__button--active"><?php echo $i; ?></span>
                    <?php else: ?>
                    <a href="?page=<?php echo $i; ?>" class="pagination__button"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($pagination['has_next']): ?>
                <a href="?page=<?php echo $pagination['current_page'] + 1; ?>" class="pagination__button">次へ</a>
                <?php else: ?>
                <span class="pagination__button pagination__button--disabled">次へ</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- フッター -->
    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="../assets/js/fontawesome-init.js"></script>
    <script defer src="../assets/js/app.js"></script>
</html>
