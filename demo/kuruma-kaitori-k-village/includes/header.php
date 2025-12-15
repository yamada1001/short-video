<?php
/**
 * Header
 * くるま買取ケイヴィレッジ
 * ヘッダー・ナビゲーション
 */

// 現在のページを取得
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <!-- SEO: デモサイトのためnoindex設定 -->
    <meta name="robots" content="noindex, nofollow">

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

    <!-- LINE Seed JP Font -->
    <link href="https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_11-01@1.0/LINESeedJP_A_OTF_Rg.woff2" rel="preload" as="font" type="font/woff2" crossorigin>
    <style>
    @font-face {
        font-family: 'LINE Seed JP';
        src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_11-01@1.0/LINESeedJP_A_OTF_Rg.woff2') format('woff2');
        font-weight: 400;
        font-style: normal;
        font-display: swap;
    }
    @font-face {
        font-family: 'LINE Seed JP';
        src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_11-01@1.0/LINESeedJP_A_OTF_Bd.woff2') format('woff2');
        font-weight: 700;
        font-style: normal;
        font-display: swap;
    }
    @font-face {
        font-family: 'LINE Seed JP';
        src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_11-01@1.0/LINESeedJP_A_OTF_Eb.woff2') format('woff2');
        font-weight: 800;
        font-style: normal;
        font-display: swap;
    }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo asset('assets/css/reset.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/variables.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/common.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/components.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/decorations.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/header.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/footer.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/breadcrumb.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/cta-contact.css'); ?>">

    <?php if ($current_page === 'index'): ?>
    <link rel="stylesheet" href="<?php echo asset('assets/css/index.css'); ?>">
    <link rel="stylesheet" href="<?php echo asset('assets/css/inventory.css'); ?>">
    <?php elseif ($current_page === 'kaitori'): ?>
    <link rel="stylesheet" href="<?php echo asset('assets/css/kaitori.css'); ?>">
    <?php elseif ($current_page === 'lease'): ?>
    <link rel="stylesheet" href="<?php echo asset('assets/css/lease.css'); ?>">
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
                <img src="<?php echo asset('assets/images/logo.jpg'); ?>" alt="<?php echo SITE_NAME; ?>" class="header__logo-image">
            </a>

            <!-- ナビゲーション（PC） -->
            <nav class="header__nav">
                <ul class="header__nav-list">
                    <li class="header__nav-item">
                        <a href="<?php echo url('kaitori'); ?>" class="header__nav-link <?php echo is_current_page('kaitori'); ?>">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                            買取サービス
                        </a>
                    </li>
                    <li class="header__nav-item">
                        <a href="<?php echo url('lease'); ?>" class="header__nav-link <?php echo is_current_page('lease'); ?>">
                            <i class="fa-solid fa-file-contract"></i>
                            新車リース
                        </a>
                    </li>
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
                    <a href="<?php echo url('kaitori'); ?>" class="header__mobile-nav-link <?php echo is_current_page('kaitori'); ?>">
                        <span><i class="fa-solid fa-hand-holding-dollar"></i> 買取サービス</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
                <li class="header__mobile-nav-item">
                    <a href="<?php echo url('lease'); ?>" class="header__mobile-nav-link <?php echo is_current_page('lease'); ?>">
                        <span><i class="fa-solid fa-file-contract"></i> 新車リース</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
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
