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
                <!-- Heroicons: truck (solid) -->
                <svg class="header__logo-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25zM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 116 0h3a.75.75 0 00.75-.75V15z" />
                    <path d="M8.25 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0zM15.75 6.75a.75.75 0 00-.75.75v11.25c0 .087.015.17.042.248a3 3 0 015.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 00-3.732-10.104 1.837 1.837 0 00-1.47-.725H15.75z" />
                    <path d="M19.5 19.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
                </svg>
                <div class="header__logo-text">
                    <span class="header__logo-main"><?php echo SITE_NAME; ?></span>
                    <span class="header__logo-sub">大分市中判田の車買取・販売・車検</span>
                </div>
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
