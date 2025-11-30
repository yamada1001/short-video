<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : '東京を拠点とする建築設計事務所。住宅から商業施設まで、空間に新たな価値を創造します。'; ?>">
    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?>STUDIO ARCHITECTS | 建築設計事務所</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body class="preload">
    <div class="wrapper">
        <header class="cHeader">
            <div class="cHeader__inner">
                <div class="cHeader__logo">
                    <a href="index.php" class="cHeader__logo_link">
                        <span class="cHeader__logo_text">STUDIO<br>ARCHITECTS</span>
                    </a>
                </div>

                <nav class="cHeader__nav">
                    <ul class="cHeader__nav_list">
                        <li class="cHeader__nav_item">
                            <a href="#projects" class="cHeader__nav_link">Projects</a>
                        </li>
                        <li class="cHeader__nav_item">
                            <a href="#about" class="cHeader__nav_link">About</a>
                        </li>
                        <li class="cHeader__nav_item">
                            <a href="#team" class="cHeader__nav_link">Team</a>
                        </li>
                        <li class="cHeader__nav_item">
                            <a href="#contact" class="cHeader__nav_link">Contact</a>
                        </li>
                        <li class="cHeader__nav_item cHeader__nav_item--lang">
                            <a href="#" class="cHeader__nav_link">JP</a>
                            <span class="cHeader__nav_separator">/</span>
                            <a href="#" class="cHeader__nav_link cHeader__nav_link--inactive">EN</a>
                        </li>
                    </ul>
                </nav>

                <button class="cHeader__menu js-menu-button" aria-label="メニュー">
                    <span class="cHeader__menu_line"></span>
                    <span class="cHeader__menu_line"></span>
                    <span class="cHeader__menu_line"></span>
                </button>
            </div>
        </header>

        <!-- モバイルメニュー -->
        <div class="cMobileMenu">
            <nav class="cMobileMenu__nav">
                <ul class="cMobileMenu__list">
                    <li class="cMobileMenu__item">
                        <a href="#projects" class="cMobileMenu__link">Projects</a>
                    </li>
                    <li class="cMobileMenu__item">
                        <a href="#about" class="cMobileMenu__link">About</a>
                    </li>
                    <li class="cMobileMenu__item">
                        <a href="#team" class="cMobileMenu__link">Team</a>
                    </li>
                    <li class="cMobileMenu__item">
                        <a href="#contact" class="cMobileMenu__link">Contact</a>
                    </li>
                    <li class="cMobileMenu__item cMobileMenu__item--lang">
                        <a href="#" class="cMobileMenu__link">JP</a>
                        <span class="cMobileMenu__separator">/</span>
                        <a href="#" class="cMobileMenu__link cMobileMenu__link--inactive">EN</a>
                    </li>
                </ul>
            </nav>
        </div>

        <main class="main">
