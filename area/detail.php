<?php
/**
 * エリア詳細ページ
 */
$current_page = 'area';
require_once __DIR__ . '/../includes/functions.php';

// エリアデータ読み込み
$areas_json = file_get_contents(__DIR__ . '/data/areas.json');
$areas_data = json_decode($areas_json, true);

// URLからスラッグ取得
$slug = isset($_GET['area']) ? $_GET['area'] : '';

// エリア検索
$area = null;
foreach ($areas_data['areas'] as $a) {
    if ($a['slug'] === $slug) {
        $area = $a;
        break;
    }
}

// エリアが見つからない場合
if (!$area) {
    header('HTTP/1.0 404 Not Found');
    echo 'エリアが見つかりません。';
    exit;
}

// ページ情報
$page_title = '【10万円〜】' . $area['name'] . 'のホームページ制作｜格安・高品質｜余日';
$page_description = $area['name'] . 'でホームページ制作をお探しなら余日へ。10万円からの格安料金で高品質なWebサイトを制作。' . $area['description'];
$page_keywords = implode(',', $area['keywords']);

// エリアページでは英語ボタンを非表示
$hide_lang_switch = true;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo h($page_description); ?>">
    <meta name="keywords" content="<?php echo h($page_keywords); ?>">
    <title><?php echo h($page_title); ?></title>

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

    <?php require_once __DIR__ . '/../includes/favicon.php'; ?>

    <!-- OGP -->
    <meta property="og:title" content="<?php echo h($page_title); ?>">
    <meta property="og:description" content="<?php echo h($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/area/?area=<?php echo urlencode($area['slug']); ?>">
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
        "@type": "LocalBusiness",
        "name": "余日（Yojitsu）- <?php echo h($area['name']); ?>対応",
        "description": "<?php echo h($page_description); ?>",
        "url": "<?php echo SITE_URL; ?>/area/?area=<?php echo urlencode($area['slug']); ?>",
        "telephone": "<?php echo CONTACT_TEL; ?>",
        "email": "<?php echo CONTACT_EMAIL; ?>",
        "address": {
            "@type": "PostalAddress",
            "addressRegion": "大分県",
            "addressCountry": "JP"
        },
        "areaServed": {
            "@type": "City",
            "name": "<?php echo h($area['name']); ?>",
            "addressRegion": "大分県"
        },
        "priceRange": "¥100,000〜",
        "openingHours": "Mo-Su 10:00-22:00",
        "sameAs": [
            "https://line.me/ti/p/CTOCx9YKjk"
        ],
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "ホームページ制作サービス",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "ホームページ制作",
                        "description": "<?php echo h($area['name']); ?>の企業・店舗向けホームページ制作"
                    },
                    "price": "100000",
                    "priceCurrency": "JPY"
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "保守・運用サポート",
                        "description": "月額制の保守・更新サービス"
                    },
                    "price": "5800",
                    "priceCurrency": "JPY"
                }
            ]
        }
    }
    </script>

    <!-- BreadcrumbList 構造化データ -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {
                "@type": "ListItem",
                "position": 1,
                "name": "ホーム",
                "item": "<?php echo SITE_URL; ?>/"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "対応エリア",
                "item": "<?php echo SITE_URL; ?>/area/"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "<?php echo h($area['name']); ?>",
                "item": "<?php echo SITE_URL; ?>/area/?area=<?php echo urlencode($area['slug']); ?>"
            }
        ]
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
    <section class="area-hero">
        <div class="container">
            <nav class="breadcrumb">
                <a href="/" class="breadcrumb__link">ホーム</a>
                <span class="breadcrumb__separator">/</span>
                <a href="/area/" class="breadcrumb__link">対応エリア</a>
                <span class="breadcrumb__separator">/</span>
                <span class="breadcrumb__current"><?php echo h($area['name']); ?></span>
            </nav>
            <h1 class="area-hero__title">
                <span class="area-hero__location"><?php echo h($area['name']); ?>の</span>
                <span class="area-hero__service">ホームページ制作</span>
            </h1>
            <p class="area-hero__price">
                <span class="price-badge">10万円〜</span>
                格安・高品質なWebサイト制作
            </p>
            <p class="area-hero__description"><?php echo h($area['description']); ?></p>
            <div class="area-hero__cta">
                <a href="/contact.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-envelope"></i> 無料相談・お見積り
                </a>
                <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-phone"></i> <?php echo CONTACT_TEL; ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="area-features">
        <div class="container">
            <h2 class="section-title"><?php echo h($area['name']); ?>でホームページ制作なら余日</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-yen-sign"></i>
                    </div>
                    <h3 class="feature-card__title">10万円からの格安料金</h3>
                    <p class="feature-card__text">初期費用を抑えた料金設定。<?php echo h($area['name']); ?>の中小企業・個人事業主様でも導入しやすい価格です。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-card__title">スマホ対応・SEO最適化</h3>
                    <p class="feature-card__text">「<?php echo h($area['name']); ?> + サービス名」で検索上位を狙えるSEO対策済みのホームページを制作します。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3 class="feature-card__title">月額5,800円で更新し放題</h3>
                    <p class="feature-card__text">テキスト変更、画像差し替え、ページ追加など、毎月の更新作業を定額で対応いたします。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="feature-card__title">地域密着サポート</h3>
                    <p class="feature-card__text">大分県内だからこそできる迅速な対応。<?php echo h($area['name']); ?>の事業者様に寄り添ったサポートを提供します。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Area Info Section -->
    <section class="area-info">
        <div class="container">
            <h2 class="section-title"><?php echo h($area['name']); ?>の特徴</h2>
            <div class="area-info__content">
                <div class="area-info__details">
                    <table class="area-info__table">
                        <tr>
                            <th>エリア名</th>
                            <td><?php echo h($area['name']); ?>（<?php echo h($area['nameKana']); ?>）</td>
                        </tr>
                        <tr>
                            <th>人口</th>
                            <td><?php echo h($area['population']); ?></td>
                        </tr>
                        <tr>
                            <th>地域の特徴</th>
                            <td>
                                <ul class="area-features-list">
                                    <?php foreach ($area['features'] as $feature): ?>
                                    <li><?php echo h($feature); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="area-info__needs">
                    <h3><?php echo h($area['name']); ?>で多いホームページ制作のご依頼</h3>
                    <ul class="needs-list">
                        <li><i class="fas fa-check"></i> 新規開業・創業時のホームページ作成</li>
                        <li><i class="fas fa-check"></i> 既存サイトのリニューアル・スマホ対応</li>
                        <li><i class="fas fa-check"></i> 集客・問い合わせ増加を目的としたLP制作</li>
                        <li><i class="fas fa-check"></i> 求人・採用ページの作成</li>
                        <li><i class="fas fa-check"></i> ECサイト・ネットショップ構築</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Price Section -->
    <section class="area-price">
        <div class="container">
            <h2 class="section-title">料金プラン</h2>
            <div class="price-cards">
                <div class="price-card">
                    <div class="price-card__header">
                        <h3 class="price-card__name">スタンダードプラン</h3>
                        <div class="price-card__price">
                            <span class="price-card__amount">10万円</span>
                            <span class="price-card__unit">〜（税別）</span>
                        </div>
                    </div>
                    <ul class="price-card__features">
                        <li><i class="fas fa-check"></i> トップページ + 下層5ページ</li>
                        <li><i class="fas fa-check"></i> スマホ・タブレット対応</li>
                        <li><i class="fas fa-check"></i> お問い合わせフォーム</li>
                        <li><i class="fas fa-check"></i> SEO基本設定</li>
                        <li><i class="fas fa-check"></i> Googleマップ埋め込み</li>
                        <li><i class="fas fa-check"></i> SNSリンク設置</li>
                    </ul>
                    <p class="price-card__note">※制作期間：約2〜4週間</p>
                </div>
                <div class="price-card price-card--highlight">
                    <div class="price-card__badge">人気</div>
                    <div class="price-card__header">
                        <h3 class="price-card__name">保守・運用プラン</h3>
                        <div class="price-card__price">
                            <span class="price-card__amount">5,800円</span>
                            <span class="price-card__unit">/月（税別）</span>
                        </div>
                    </div>
                    <ul class="price-card__features">
                        <li><i class="fas fa-check"></i> テキスト・画像の変更し放題</li>
                        <li><i class="fas fa-check"></i> ページ追加・修正対応</li>
                        <li><i class="fas fa-check"></i> サーバー・ドメイン管理</li>
                        <li><i class="fas fa-check"></i> セキュリティ対策</li>
                        <li><i class="fas fa-check"></i> アクセス解析レポート</li>
                        <li><i class="fas fa-check"></i> 電話・メールサポート</li>
                    </ul>
                    <p class="price-card__note">※最低契約期間：6ヶ月</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Flow Section -->
    <section class="area-flow">
        <div class="container">
            <h2 class="section-title">制作の流れ</h2>
            <div class="flow-steps">
                <div class="flow-step">
                    <div class="flow-step__number">1</div>
                    <h3 class="flow-step__title">お問い合わせ</h3>
                    <p class="flow-step__text">フォーム・電話・LINEからお気軽にご連絡ください。</p>
                </div>
                <div class="flow-step">
                    <div class="flow-step__number">2</div>
                    <h3 class="flow-step__title">ヒアリング</h3>
                    <p class="flow-step__text">ご要望や目的をお伺いし、最適なプランをご提案します。</p>
                </div>
                <div class="flow-step">
                    <div class="flow-step__number">3</div>
                    <h3 class="flow-step__title">お見積り・ご契約</h3>
                    <p class="flow-step__text">内容と料金にご納得いただけましたらご契約となります。</p>
                </div>
                <div class="flow-step">
                    <div class="flow-step__number">4</div>
                    <h3 class="flow-step__title">デザイン・制作</h3>
                    <p class="flow-step__text">デザイン案をご確認いただきながら制作を進めます。</p>
                </div>
                <div class="flow-step">
                    <div class="flow-step__number">5</div>
                    <h3 class="flow-step__title">公開・運用開始</h3>
                    <p class="flow-step__text">最終確認後、公開。保守プランで継続サポートいたします。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="area-cta">
        <div class="container">
            <h2 class="area-cta__title"><?php echo h($area['name']); ?>のホームページ制作はお任せください</h2>
            <p class="area-cta__text">
                <?php echo h($area['name']); ?>で事業を営む皆様のビジネスをホームページで支援します。<br>
                まずはお気軽にご相談ください。お見積りは無料です。
            </p>
            <div class="area-cta__buttons">
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

    <!-- Other Areas -->
    <section class="other-areas">
        <div class="container">
            <h2 class="section-title">大分県内の対応エリア</h2>
            <div class="areas-list">
                <?php foreach ($areas_data['areas'] as $other_area): ?>
                <?php if ($other_area['slug'] !== $area['slug']): ?>
                <a href="/area/?area=<?php echo urlencode($other_area['slug']); ?>" class="area-link">
                    <?php echo h($other_area['name']); ?>
                </a>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <p class="areas-note">
                <a href="/area/" class="areas-map-link">
                    <i class="fas fa-map-marked-alt"></i> エリアマップで見る
                </a>
            </p>
        </div>
    </section>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script defer src="/assets/js/app.js"></script>
</body>
</html>
