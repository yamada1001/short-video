<?php
/**
 * ショート動画制作エリア詳細ページ
 */
$current_page = 'area';
require_once __DIR__ . '/../../includes/functions.php';

// エリアデータ読み込み
$areas_json = file_get_contents(__DIR__ . '/../data/areas.json');
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
$page_title = '【2万円〜】' . $area['name'] . 'のショート動画制作｜撮影・編集込み｜余日';
$page_description = $area['name'] . 'でショート動画制作をお探しなら余日へ。1本2万円から撮影・編集込み。' . $area['name'] . 'への出張費無料。TikTok・Instagram・YouTube対応。';
$page_keywords = $area['name'] . ',ショート動画,動画制作,TikTok,Instagram,YouTube,大分';

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

    <?php require_once __DIR__ . '/../../includes/favicon.php'; ?>

    <!-- OGP -->
    <meta property="og:title" content="<?php echo h($page_title); ?>">
    <meta property="og:description" content="<?php echo h($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>/area/video/?area=<?php echo urlencode($area['slug']); ?>">
    <meta property="og:site_name" content="余日（Yojitsu）">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/pages/area.css">
    <link rel="stylesheet" href="/assets/css/pages/area-video.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- 構造化データ -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "余日（Yojitsu）- <?php echo h($area['name']); ?>ショート動画制作",
        "description": "<?php echo h($page_description); ?>",
        "url": "<?php echo SITE_URL; ?>/area/video/?area=<?php echo urlencode($area['slug']); ?>",
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
        "priceRange": "¥20,000〜",
        "openingHours": "Mo-Su 10:00-22:00",
        "sameAs": [
            "https://line.me/ti/p/CTOCx9YKjk"
        ],
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "ショート動画制作サービス",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "基本プラン",
                        "description": "1本あたりの制作（撮影・編集込み）"
                    },
                    "price": "20000",
                    "priceCurrency": "JPY"
                },
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Service",
                        "name": "10本セット",
                        "description": "まとめて依頼で25%OFF"
                    },
                    "price": "150000",
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
                "name": "ショート動画制作",
                "item": "<?php echo SITE_URL; ?>/video-production.php"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "<?php echo h($area['name']); ?>",
                "item": "<?php echo SITE_URL; ?>/area/video/?area=<?php echo urlencode($area['slug']); ?>"
            }
        ]
    }
    </script>

    <!-- FAQPage 構造化データ -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "<?php echo h($area['name']); ?>でショート動画制作の料金はいくらですか？",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "1本2万円から制作可能です。撮影・編集込みの料金で、<?php echo h($area['name']); ?>への出張費は無料です。10本セットなら15万円（25%OFF）でさらにお得です。"
                }
            },
            {
                "@type": "Question",
                "name": "<?php echo h($area['name']); ?>への出張費はかかりますか？",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "いいえ、大分県内であれば出張費は無料です。<?php echo h($area['name']); ?>のお店や事務所に伺って撮影いたします。"
                }
            },
            {
                "@type": "Question",
                "name": "どのSNSプラットフォームに対応していますか？",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "TikTok、Instagram Reels、YouTube Shortsの全プラットフォームに対応しています。各プラットフォームに最適化した動画をお渡しします。"
                }
            },
            {
                "@type": "Question",
                "name": "撮影から納品までどれくらいかかりますか？",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "通常、撮影後1週間程度で初稿をお渡しします。修正対応を含めて2週間程度での納品が目安です。"
                }
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

    <?php include __DIR__ . '/../../includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="area-hero area-hero--video">
        <div class="container">
            <nav class="breadcrumb">
                <a href="/" class="breadcrumb__link">ホーム</a>
                <span class="breadcrumb__separator">/</span>
                <a href="/video-production.php" class="breadcrumb__link">ショート動画制作</a>
                <span class="breadcrumb__separator">/</span>
                <span class="breadcrumb__current"><?php echo h($area['name']); ?></span>
            </nav>
            <h1 class="area-hero__title">
                <span class="area-hero__location"><?php echo h($area['name']); ?>の</span>
                <span class="area-hero__service">ショート動画制作</span>
            </h1>
            <p class="area-hero__price">
                <span class="price-badge">2万円〜/本</span>
                撮影・編集込み
            </p>
            <p class="area-hero__highlight">
                <i class="fas fa-car"></i> <?php echo h($area['name']); ?>への出張費無料
            </p>
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
            <h2 class="section-title"><?php echo h($area['name']); ?>でショート動画制作なら余日</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-yen-sign"></i>
                    </div>
                    <h3 class="feature-card__title">1本2万円〜の格安料金</h3>
                    <p class="feature-card__text">撮影・編集込みでこの価格。<?php echo h($area['name']); ?>の中小企業・個人事業主様でも始めやすい料金設定です。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3 class="feature-card__title">大分県内は出張費無料</h3>
                    <p class="feature-card__text"><?php echo h($area['name']); ?>への撮影出張は無料。お店や事務所での撮影に伺います。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-card__title">全プラットフォーム対応</h3>
                    <p class="feature-card__text">TikTok・Instagram Reels・YouTube Shorts、各プラットフォームに最適化した動画を制作します。</p>
                </div>
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <i class="fas fa-magic"></i>
                    </div>
                    <h3 class="feature-card__title">テロップ・BGM・エフェクト込み</h3>
                    <p class="feature-card__text">視聴者の目を引くテロップ、BGM、エフェクトを追加。すぐに投稿できる状態でお渡しします。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Platform Section -->
    <section class="platform-section">
        <div class="container">
            <h2 class="section-title">対応プラットフォーム</h2>
            <div class="platforms-grid">
                <div class="platform-card">
                    <div class="platform-card__icon">
                        <i class="fab fa-tiktok"></i>
                    </div>
                    <h3 class="platform-card__name">TikTok</h3>
                    <p class="platform-card__desc">若年層へのリーチに最適</p>
                </div>
                <div class="platform-card">
                    <div class="platform-card__icon">
                        <i class="fab fa-instagram"></i>
                    </div>
                    <h3 class="platform-card__name">Instagram Reels</h3>
                    <p class="platform-card__desc">ビジュアル重視の層へ</p>
                </div>
                <div class="platform-card">
                    <div class="platform-card__icon">
                        <i class="fab fa-youtube"></i>
                    </div>
                    <h3 class="platform-card__name">YouTube Shorts</h3>
                    <p class="platform-card__desc">幅広い年齢層へリーチ</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Price Section -->
    <section class="area-price">
        <div class="container">
            <h2 class="section-title">料金プラン</h2>
            <p class="section-note"><i class="fas fa-info-circle"></i> <?php echo h($area['name']); ?>への出張費は無料です。まずはお気軽にご相談ください。</p>

            <!-- スマホ用タブ切り替え -->
            <div class="price-tabs">
                <button class="price-tab" data-plan="plan-basic" onclick="switchPlan('plan-basic')">基本</button>
                <button class="price-tab active" data-plan="plan-set" onclick="switchPlan('plan-set')">10本セット</button>
            </div>

            <div class="price-cards price-cards--two">
                <!-- 基本プラン -->
                <div class="price-card" data-plan="plan-basic">
                    <div class="price-card__header">
                        <h3 class="price-card__name">基本プラン</h3>
                        <div class="price-card__price">
                            <span class="price-card__amount">2万円</span>
                            <span class="price-card__unit">/本（税別）</span>
                        </div>
                    </div>
                    <p class="price-card__target"><i class="fas fa-video"></i> まずは1本試したい方向け</p>
                    <ul class="price-card__features">
                        <li><i class="fas fa-check"></i> 撮影（<?php echo h($area['name']); ?>出張無料）</li>
                        <li><i class="fas fa-check"></i> 編集・カット</li>
                        <li><i class="fas fa-check"></i> テロップ挿入</li>
                        <li><i class="fas fa-check"></i> BGM・効果音</li>
                        <li><i class="fas fa-check"></i> エフェクト追加</li>
                        <li><i class="fas fa-check"></i> 各プラットフォーム最適化</li>
                        <li><i class="fas fa-check"></i> 1回の修正対応</li>
                    </ul>
                </div>

                <!-- 10本セット -->
                <div class="price-card price-card--highlight" data-plan="plan-set">
                    <div class="price-card__badge">人気</div>
                    <div class="price-card__header">
                        <h3 class="price-card__name">10本セット</h3>
                        <div class="price-card__price">
                            <span class="price-card__amount">15万円</span>
                            <span class="price-card__unit">（税別）</span>
                        </div>
                        <div class="price-card__discount">25% OFF</div>
                    </div>
                    <p class="price-card__target"><i class="fas fa-layer-group"></i> 継続的に発信したい方向け</p>
                    <ul class="price-card__features">
                        <li><i class="fas fa-check"></i> 基本プランの全機能 × 10本</li>
                        <li><i class="fas fa-check"></i> まとめ撮影で効率的</li>
                        <li><i class="fas fa-check"></i> 投稿スケジュール提案</li>
                        <li><i class="fas fa-check"></i> コンテンツ企画サポート</li>
                        <li><i class="fas fa-check"></i> 各本2回まで修正対応</li>
                    </ul>
                    <p class="price-card__calc">1本あたり <strong>15,000円</strong></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Use Cases Section -->
    <section class="usecases-section">
        <div class="container">
            <h2 class="section-title"><?php echo h($area['name']); ?>でのショート動画活用例</h2>
            <div class="usecases-grid">
                <div class="usecase-card">
                    <div class="usecase-card__icon"><i class="fas fa-utensils"></i></div>
                    <h3 class="usecase-card__title">飲食店</h3>
                    <p class="usecase-card__text">料理の調理過程、店内の雰囲気、スタッフ紹介など</p>
                </div>
                <div class="usecase-card">
                    <div class="usecase-card__icon"><i class="fas fa-store"></i></div>
                    <h3 class="usecase-card__title">小売店</h3>
                    <p class="usecase-card__text">新商品紹介、使い方解説、お客様の声など</p>
                </div>
                <div class="usecase-card">
                    <div class="usecase-card__icon"><i class="fas fa-spa"></i></div>
                    <h3 class="usecase-card__title">美容・サロン</h3>
                    <p class="usecase-card__text">施術ビフォーアフター、技術紹介、店内ツアーなど</p>
                </div>
                <div class="usecase-card">
                    <div class="usecase-card__icon"><i class="fas fa-building"></i></div>
                    <h3 class="usecase-card__title">企業・事務所</h3>
                    <p class="usecase-card__text">サービス紹介、採用動画、社内の雰囲気紹介など</p>
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
                    <h3 class="flow-step__title">ヒアリング・企画</h3>
                    <p class="flow-step__text">目的やターゲットをお伺いし、動画の企画を作成します。</p>
                </div>
                <div class="flow-step">
                    <div class="flow-step__number">3</div>
                    <h3 class="flow-step__title">撮影</h3>
                    <p class="flow-step__text"><?php echo h($area['name']); ?>のお店・事務所に伺い撮影します（出張費無料）。</p>
                </div>
                <div class="flow-step">
                    <div class="flow-step__number">4</div>
                    <h3 class="flow-step__title">編集・確認</h3>
                    <p class="flow-step__text">編集後、確認いただき修正があれば対応します。</p>
                </div>
                <div class="flow-step">
                    <div class="flow-step__number">5</div>
                    <h3 class="flow-step__title">納品</h3>
                    <p class="flow-step__text">各プラットフォームに最適化した動画データをお渡しします。</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="area-cta">
        <div class="container">
            <h2 class="area-cta__title"><?php echo h($area['name']); ?>のショート動画制作はお任せください</h2>
            <p class="area-cta__text">
                <?php echo h($area['name']); ?>で事業を営む皆様の集客をショート動画で支援します。<br>
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
                <a href="/area/video/?area=<?php echo urlencode($other_area['slug']); ?>" class="area-link">
                    <?php echo h($other_area['name']); ?>
                </a>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <p class="areas-note">
                <a href="/video-production.php" class="areas-map-link">
                    <i class="fas fa-video"></i> ショート動画制作サービス詳細
                </a>
            </p>

            <!-- Web制作への内部リンク -->
            <div class="cross-service-link">
                <p><i class="fas fa-laptop-code"></i> <?php echo h($area['name']); ?>でホームページ制作もお探しですか？</p>
                <a href="/area/?area=<?php echo urlencode($area['slug']); ?>" class="btn btn-secondary">
                    <?php echo h($area['name']); ?>のホームページ制作を見る
                </a>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script defer src="/assets/js/app.js"></script>
    <script>
    function switchPlan(planId) {
        // タブの切り替え
        document.querySelectorAll('.price-tab').forEach(tab => {
            tab.classList.remove('active');
            if (tab.dataset.plan === planId) {
                tab.classList.add('active');
            }
        });
        // カードの切り替え
        document.querySelectorAll('.price-card[data-plan]').forEach(card => {
            card.classList.remove('active');
            if (card.dataset.plan === planId) {
                card.classList.add('active');
            }
        });
    }
    // 初期表示で10本セットをアクティブに
    document.addEventListener('DOMContentLoaded', function() {
        switchPlan('plan-set');
    });
    </script>
</body>
</html>
