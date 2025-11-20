<?php
/**
 * ショート動画制作エリア一覧ページ
 */

// エリアパラメータがある場合は詳細ページを表示
if (isset($_GET['area']) && !empty($_GET['area'])) {
    require_once __DIR__ . '/detail.php';
    exit;
}

// 以下はエリア一覧ページ
$current_page = 'area';
require_once __DIR__ . '/../../includes/functions.php';

// エリアデータ読み込み
$areas_json = file_get_contents(__DIR__ . '/../data/areas.json');
$areas_data = json_decode($areas_json, true);

// 市と町村を分ける
$cities = array_filter($areas_data['areas'], function($area) {
    return $area['type'] === '市';
});
$towns = array_filter($areas_data['areas'], function($area) {
    return $area['type'] === '町' || $area['type'] === '村';
});

// ページ情報
$page_title = '【2万円〜】大分県ショート動画制作｜出張費無料｜余日';
$page_description = '大分県全域でショート動画制作に対応。1本2万円から撮影・編集込み。TikTok・Instagram・YouTube対応。県内出張費無料。';

// エリアページでは英語ボタンを非表示
$hide_lang_switch = true;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo h($page_description); ?>">
    <title><?php echo h($page_title); ?></title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->

    <?php require_once __DIR__ . '/../../includes/favicon.php'; ?>

    <!-- OGP -->
    <meta property="og:title" content="<?php echo h($page_title); ?>">
    <meta property="og:description" content="<?php echo h($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/area/video/">
    <meta property="og:site_name" content="余日（Yojitsu）">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/pages/area.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <style>
        .video-area-hero {
            background: var(--color-bg-gray);
            padding: var(--spacing-xxl) 0;
            text-align: center;
        }

        .video-area-hero__title {
            font-size: clamp(28px, 5vw, 42px);
            font-weight: 700;
            color: var(--color-charcoal);
            margin-bottom: var(--spacing-md);
        }

        .video-area-hero__title i {
            color: var(--color-natural-brown);
        }

        .video-area-hero__price {
            display: inline-block;
            background: var(--color-natural-brown);
            color: #fff;
            padding: var(--spacing-sm) var(--spacing-lg);
            border-radius: 50px;
            font-weight: 700;
            margin-bottom: var(--spacing-md);
        }

        .video-area-hero__description {
            color: var(--color-text);
            font-size: 16px;
            line-height: 1.8;
            max-width: 600px;
            margin: 0 auto var(--spacing-lg);
        }

        .video-area-list {
            padding: var(--spacing-xxl) 0;
            background: var(--color-bg-white);
        }

        .video-area-list__group {
            margin-bottom: var(--spacing-xl);
        }

        .video-area-list__group-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--color-charcoal);
            margin-bottom: var(--spacing-lg);
            padding-bottom: var(--spacing-sm);
            border-bottom: 2px solid var(--color-natural-brown);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .video-area-list__group-title i {
            color: var(--color-natural-brown);
        }

        .video-area-list__links {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: var(--spacing-sm);
        }

        .video-area-list__link {
            display: block;
            padding: var(--spacing-md);
            background: var(--color-bg-gray);
            border-radius: 8px;
            text-decoration: none;
            color: var(--color-text);
            font-size: 14px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .video-area-list__link:hover {
            background: var(--color-natural-brown);
            color: #fff;
            transform: translateY(-2px);
        }

        .video-area-cta {
            padding: var(--spacing-xxl) 0;
            background: var(--color-natural-brown);
            text-align: center;
            color: #fff;
        }

        .video-area-cta__title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: var(--spacing-md);
        }

        .video-area-cta__text {
            margin-bottom: var(--spacing-lg);
            opacity: 0.9;
        }

        .video-area-cta .btn-outline {
            border-color: #fff;
            color: #fff;
        }

        .video-area-cta .btn-outline:hover {
            background: #fff;
            color: var(--color-natural-brown);
        }
    </style>

    <!-- 構造化データ -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "大分県ショート動画制作対応エリア",
        "description": "<?php echo h($page_description); ?>",
        "url": "<?php echo SITE_URL; ?>/area/video/",
        "mainEntity": {
            "@type": "ItemList",
            "name": "大分県のショート動画制作対応エリア",
            "numberOfItems": <?php echo count($areas_data['areas']); ?>,
            "itemListElement": [
                <?php foreach ($areas_data['areas'] as $index => $area): ?>
                {
                    "@type": "ListItem",
                    "position": <?php echo $index + 1; ?>,
                    "name": "<?php echo h($area['name']); ?>",
                    "url": "<?php echo SITE_URL; ?>/area/video/?area=<?php echo urlencode($area['slug']); ?>"
                }<?php echo $index < count($areas_data['areas']) - 1 ? ',' : ''; ?>
                <?php endforeach; ?>
            ]
        }
    }
    </script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <!-- ヒーローセクション -->
    <section class="video-area-hero">
        <div class="container">
            <h1 class="video-area-hero__title">
                <i class="fas fa-video"></i> 大分県ショート動画制作
            </h1>
            <div class="video-area-hero__price">2万円〜/本</div>
            <p class="video-area-hero__description">
                大分県全18市町村でショート動画制作に対応しています。<br>
                撮影・編集込み、県内への出張費は<strong>無料</strong>です。
            </p>
            <a href="/contact.php" class="btn btn-primary btn-lg">
                <i class="fas fa-envelope"></i> 無料相談・お見積り
            </a>
        </div>
    </section>

    <!-- エリア一覧 -->
    <section class="video-area-list">
        <div class="container">
            <!-- 市 -->
            <div class="video-area-list__group">
                <h2 class="video-area-list__group-title">
                    <i class="fas fa-city"></i> 市（14市）
                </h2>
                <div class="video-area-list__links">
                    <?php foreach ($cities as $area): ?>
                    <a href="/area/video/?area=<?php echo urlencode($area['slug']); ?>" class="video-area-list__link">
                        <?php echo h($area['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- 町・村 -->
            <div class="video-area-list__group">
                <h2 class="video-area-list__group-title">
                    <i class="fas fa-map-marker-alt"></i> 町・村（4町村）
                </h2>
                <div class="video-area-list__links">
                    <?php foreach ($towns as $area): ?>
                    <a href="/area/video/?area=<?php echo urlencode($area['slug']); ?>" class="video-area-list__link">
                        <?php echo h($area['name']); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="video-area-cta">
        <div class="container">
            <h2 class="video-area-cta__title">ショート動画で集客を強化しませんか？</h2>
            <p class="video-area-cta__text">
                TikTok・Instagram・YouTubeに最適化した動画を制作します。<br>
                まずはお気軽にご相談ください。
            </p>
            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="/contact.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-envelope"></i> お問い合わせ
                </a>
                <a href="/video-production.php" class="btn btn-outline btn-lg">
                    <i class="fas fa-info-circle"></i> サービス詳細
                </a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script defer src="/assets/js/app.js"></script>
</body>
</html>
