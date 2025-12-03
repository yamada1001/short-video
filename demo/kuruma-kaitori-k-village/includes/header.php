<?php
/**
 * Header
 * くるま買取ケイヴィレッジ
 * ヘッダー・ナビゲーション
 */

// 現在のページを取得
$current_page = basename($_SERVER['PHP_SELF'], '.php');

// 開発中: キャッシュ無効化
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <!-- 開発中: キャッシュ無効化 -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <!-- SEO -->
    <title><?php echo h(get_meta($current_page, 'title')); ?></title>
    <meta name="description" content="<?php echo h(get_meta($current_page, 'description')); ?>">
    <meta name="keywords" content="<?php echo h(get_meta($current_page, 'keywords')); ?>">

    <!-- OGP -->
    <?php output_ogp($current_page); ?>

    <!-- Favicon -->
    <link rel="icon" href="<?php echo asset('assets/images/favicon.ico'); ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/reset.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/variables.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/common.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/components.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/header.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/breadcrumb.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/cta-contact.css'); ?>">

    <?php if ($current_page === 'index'): ?>
    <link rel="stylesheet" href="<?php echo asset('assets/css/index.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/inventory.css'); ?>">
    <?php elseif ($current_page === 'about'): ?>
    <link rel="stylesheet" href="<?php echo asset('assets/css/about.css'); ?>">
    <?php elseif ($current_page === 'contact'): ?>
    <link rel="stylesheet" href="<?php echo asset('assets/css/contact.css'); ?>">
    <?php elseif ($current_page === 'news' || $current_page === 'news-detail'): ?>
    <link rel="stylesheet" href="<?php echo asset('assets/css/news.css'); ?>">
    <?php elseif ($current_page === 'privacy' || $current_page === 'tokushoho' || $current_page === 'sitemap'): ?>
    <link rel="stylesheet" href="<?php echo asset('assets/css/policy.css'); ?>">
    <?php endif; ?>

    <!-- 構造化データ -->
    <?php echo get_structured_data(); ?>
</head>
<body>
    <header class="header">
        <div class="header__container">
            <!-- ロゴ -->
            <a href="<?php echo url(); ?>" class="header__logo">
                <svg class="header__logo-svg" viewBox="0 0 380 80" xmlns="http://www.w3.org/2000/svg">
                    <!-- 車のシルエット -->
                    <g class="logo-car">
                        <!-- 車体 -->
                        <path d="M10,45 L15,35 L25,30 L50,30 L55,35 L60,45 Z" fill="#2563eb" opacity="0.9"/>
                        <!-- 窓 -->
                        <path d="M20,35 L28,32 L40,32 L48,35 L45,40 L25,40 Z" fill="#60a5fa" opacity="0.6"/>
                        <!-- タイヤ -->
                        <circle cx="22" cy="48" r="6" fill="#1e293b" stroke="#475569" stroke-width="1.5"/>
                        <circle cx="48" cy="48" r="6" fill="#1e293b" stroke="#475569" stroke-width="1.5"/>
                        <circle cx="22" cy="48" r="2.5" fill="#64748b"/>
                        <circle cx="48" cy="48" r="2.5" fill="#64748b"/>
                        <!-- ヘッドライト -->
                        <circle cx="57" cy="40" r="2" fill="#fbbf24" opacity="0.8"/>
                    </g>

                    <!-- テキスト: くるま買取 -->
                    <text x="75" y="35" font-family="'Noto Sans JP', sans-serif" font-size="20" font-weight="900" fill="#1e293b">
                        くるま買取
                    </text>

                    <!-- テキスト: ケイヴィレッジ -->
                    <text x="75" y="55" font-family="'Noto Sans JP', sans-serif" font-size="18" font-weight="700" fill="#2563eb">
                        ケイヴィレッジ
                    </text>

                    <!-- サブテキスト -->
                    <text x="240" y="45" font-family="'Noto Sans JP', sans-serif" font-size="11" font-weight="500" fill="#64748b">
                        大分市中判田の車買取・販売・車検
                    </text>
                </svg>
            </a>

            <!-- ナビゲーション（PC） -->
            <nav class="header__nav">
                <ul class="header__nav-list">
                    <li class="header__nav-item">
                        <a href="<?php echo url('about'); ?>" class="header__nav-link <?php echo is_current_page('about'); ?>">
                            <i class="fa-solid fa-building"></i>
                            会社概要
                        </a>
                    </li>
                    <li class="header__nav-item">
                        <a href="<?php echo url('news'); ?>" class="header__nav-link <?php echo is_current_page('news'); ?>">
                            <i class="fa-solid fa-bullhorn"></i>
                            お知らせ
                        </a>
                    </li>
                    <li class="header__nav-item">
                        <a href="<?php echo url('contact'); ?>" class="header__nav-link <?php echo is_current_page('contact'); ?>">
                            <i class="fa-solid fa-envelope"></i>
                            お問い合わせ
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- ハンバーガーメニュー（SP） -->
            <button class="header__hamburger" id="hamburger" aria-label="メニュー">
                <span class="header__hamburger-line"></span>
                <span class="header__hamburger-line"></span>
                <span class="header__hamburger-line"></span>
            </button>
        </div>

        <!-- モバイルメニュー（SP） -->
        <div class="header__mobile-menu" id="mobile-menu">
            <ul class="header__mobile-nav-list">
                <li class="header__mobile-nav-item">
                    <a href="<?php echo url('about'); ?>" class="header__mobile-nav-link <?php echo is_current_page('about'); ?>">
                        <span><i class="fa-solid fa-building"></i> 会社概要</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
                <li class="header__mobile-nav-item">
                    <a href="<?php echo url('news'); ?>" class="header__mobile-nav-link <?php echo is_current_page('news'); ?>">
                        <span><i class="fa-solid fa-bullhorn"></i> お知らせ</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
                <li class="header__mobile-nav-item">
                    <a href="<?php echo url('contact'); ?>" class="header__mobile-nav-link <?php echo is_current_page('contact'); ?>">
                        <span><i class="fa-solid fa-envelope"></i> お問い合わせ</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            </ul>

            <!-- モバイルCTA -->
            <div class="header__mobile-cta">
                <a href="tel:<?php echo format_phone(PHONE, 'tel'); ?>" class="btn btn--secondary btn--block">
                    <i class="fa-solid fa-phone"></i>
                    電話で問い合わせ
                </a>
                <a href="<?php echo url('contact'); ?>" class="btn btn--outline btn--block">
                    <i class="fa-solid fa-envelope"></i>
                    メールで問い合わせ
                </a>
            </div>
        </div>
    </header>

    <main>
