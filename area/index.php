<?php
/**
 * エリアページ
 * - ?area=xxx の場合：詳細ページを表示
 * - パラメータなしの場合：3Dマップを表示
 */

// エリアパラメータがある場合は詳細ページを表示
if (isset($_GET['area']) && !empty($_GET['area'])) {
    require_once __DIR__ . '/detail.php';
    exit;
}

// 以下は3Dマップページ
$current_page = 'area';
require_once __DIR__ . '/../includes/functions.php';

// エリアデータ読み込み
$areas_json = file_get_contents(__DIR__ . '/data/areas.json');
$areas_data = json_decode($areas_json, true);

// ページ情報
$page_title = '【10万円〜】大分県対応エリア｜ホームページ制作｜余日';
$page_description = '大分県全域でホームページ制作に対応。大分市、別府市、中津市、日田市など全18市町村をカバー。10万円からの格安料金で高品質なWebサイトを制作します。';

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

    <?php require_once __DIR__ . '/../includes/favicon.php'; ?>

    <!-- OGP -->
    <meta property="og:title" content="<?php echo h($page_title); ?>">
    <meta property="og:description" content="<?php echo h($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/area/">
    <meta property="og:site_name" content="余日（Yojitsu）">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/pages/area.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- 構造化データ -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebPage",
        "name": "大分県対応エリア",
        "description": "<?php echo h($page_description); ?>",
        "url": "<?php echo SITE_URL; ?>/area/",
        "mainEntity": {
            "@type": "ItemList",
            "name": "大分県のホームページ制作対応エリア",
            "numberOfItems": <?php echo count($areas_data['areas']); ?>,
            "itemListElement": [
                <?php foreach ($areas_data['areas'] as $index => $area): ?>
                {
                    "@type": "ListItem",
                    "position": <?php echo $index + 1; ?>,
                    "name": "<?php echo h($area['name']); ?>",
                    "url": "<?php echo SITE_URL; ?>/area/?area=<?php echo urlencode($area['slug']); ?>"
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

    <?php include __DIR__ . '/../includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="map-hero">
        <div class="container">
            <h1 class="map-hero__title">
                <span class="map-hero__sub">大分県全域対応</span>
                ホームページ制作
            </h1>
            <p class="map-hero__price">
                <span class="price-badge">10万円〜</span>
                格安・高品質なWebサイト制作
            </p>
            <p class="map-hero__description">
                大分県内全18市町村でホームページ制作に対応しています。<br>
                下のエリア一覧からお選びください。
            </p>
        </div>
    </section>

    <!-- Area List Section -->
    <section class="area-list-section">
        <div class="container">
            <h2 class="section-title">対応エリア一覧</h2>

            <div class="area-category">
                <h3 class="area-category__title"><i class="fas fa-city"></i> 市（14市）</h3>
                <div class="area-grid">
                    <?php foreach ($areas_data['areas'] as $area): ?>
                    <?php if ($area['type'] === '市'): ?>
                    <a href="/area/?area=<?php echo urlencode($area['slug']); ?>" class="area-card">
                        <h4 class="area-card__name"><?php echo h($area['name']); ?></h4>
                        <p class="area-card__population"><?php echo h($area['population']); ?></p>
                        <span class="area-card__arrow"><i class="fas fa-chevron-right"></i></span>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="area-category">
                <h3 class="area-category__title"><i class="fas fa-map-marker-alt"></i> 町・村（4町村）</h3>
                <div class="area-grid">
                    <?php foreach ($areas_data['areas'] as $area): ?>
                    <?php if ($area['type'] === '町' || $area['type'] === '村'): ?>
                    <a href="/area/?area=<?php echo urlencode($area['slug']); ?>" class="area-card">
                        <h4 class="area-card__name"><?php echo h($area['name']); ?></h4>
                        <p class="area-card__population"><?php echo h($area['population']); ?></p>
                        <span class="area-card__arrow"><i class="fas fa-chevron-right"></i></span>
                    </a>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="map-features">
        <div class="container">
            <h2 class="section-title">余日が選ばれる理由</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-yen-sign"></i>
                    </div>
                    <h3 class="feature-card__title">10万円からの格安料金</h3>
                    <p class="feature-card__text">大手制作会社の半額以下。中小企業・個人事業主様でも導入しやすい価格設定です。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3 class="feature-card__title">月額5,800円で更新し放題</h3>
                    <p class="feature-card__text">テキスト・画像の変更、ページ追加など毎月の更新作業を定額で対応いたします。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="feature-card__title">地域SEOに強い</h3>
                    <p class="feature-card__text">「地域名 + サービス」での検索上位表示を狙えるSEO対策を標準で実施します。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-card__title">大分県内だから安心</h3>
                    <p class="feature-card__text">地元企業として迅速な対応が可能。お客様に寄り添ったサポートを提供します。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="map-cta">
        <div class="container">
            <h2 class="map-cta__title">大分県のホームページ制作はお任せください</h2>
            <p class="map-cta__text">
                まずはお気軽にご相談ください。お見積りは無料です。<br>
                お電話・LINE・メールにて対応いたします。
            </p>
            <div class="map-cta__buttons">
                <a href="/contact.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-envelope"></i> 無料相談・お見積り
                </a>
                <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" class="btn btn-outline btn-lg">
                    <i class="fas fa-phone"></i> 電話で相談
                </a>
                <a href="<?php echo CONTACT_LINE_URL; ?>" class="btn btn-line btn-lg" target="_blank">
                    <i class="fab fa-line"></i> LINEで相談
                </a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script defer src="/assets/js/app.js"></script>
</body>
</html>
