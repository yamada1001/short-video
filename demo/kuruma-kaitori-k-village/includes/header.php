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
                <svg class="header__logo-svg" viewBox="0 0 400 70" xmlns="http://www.w3.org/2000/svg">
                    <!-- 背景の円（装飾） -->
                    <circle cx="35" cy="35" r="32" fill="#2563eb" opacity="0.08"/>

                    <!-- 車のアイコン（モダン＆シンプル） -->
                    <g class="logo-car">
                        <!-- 車体本体 -->
                        <path d="M15,40 Q18,32 23,29 L28,29 L32,25 L42,25 L46,29 L51,29 Q56,32 59,40 L59,45 Q59,47 57,47 L55,47 Q55,49 53,49 L17,49 Q15,49 15,47 L13,47 Q11,47 11,45 L11,40 Z"
                              fill="#2563eb" stroke="#1e40af" stroke-width="1.5" stroke-linejoin="round"/>

                        <!-- 窓（フロント＋リア） -->
                        <path d="M28,30 L32,26 L38,26 L42,30 L40,34 L30,34 Z"
                              fill="#60a5fa" opacity="0.7" stroke="#2563eb" stroke-width="0.8"/>

                        <!-- ヘッドライト -->
                        <ellipse cx="54" cy="38" rx="2.5" ry="2" fill="#fbbf24"/>

                        <!-- タイヤ（前） -->
                        <g transform="translate(19, 45)">
                            <circle cx="0" cy="0" r="5" fill="#1e293b"/>
                            <circle cx="0" cy="0" r="3" fill="#334155" opacity="0.6"/>
                            <circle cx="0" cy="0" r="1.5" fill="#64748b"/>
                        </g>

                        <!-- タイヤ（後） -->
                        <g transform="translate(51, 45)">
                            <circle cx="0" cy="0" r="5" fill="#1e293b"/>
                            <circle cx="0" cy="0" r="3" fill="#334155" opacity="0.6"/>
                            <circle cx="0" cy="0" r="1.5" fill="#64748b"/>
                        </g>

                        <!-- ドアハンドル -->
                        <rect x="32" y="37" width="6" height="1.5" rx="0.5" fill="#1e40af" opacity="0.5"/>
                    </g>

                    <!-- ブランド名 -->
                    <g transform="translate(75, 0)">
                        <!-- メインタイトル -->
                        <text x="0" y="28" font-family="'Noto Sans JP', sans-serif" font-size="18" font-weight="900" fill="#1e293b" letter-spacing="0.5">
                            くるま買取ケイヴィレッジ
                        </text>

                        <!-- サブタイトル -->
                        <text x="0" y="45" font-family="'Noto Sans JP', sans-serif" font-size="11" font-weight="500" fill="#64748b">
                            大分市中判田の車買取・販売・車検
                        </text>
                    </g>
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
