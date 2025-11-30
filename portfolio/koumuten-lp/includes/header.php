<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?>山田工務店 | 大阪 | 想いをかたちに</title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : '大阪で創業50年。想いをかたちに。家族が笑顔になる、あたたかい家づくりを心がけています。注文住宅、リフォーム、リノベーションまで、安心してお任せください。'; ?>">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700;900&family=Noto+Serif+JP:wght@300;400;500;600;700;900&family=Playfair+Display:wght@400;500;600;700;900&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body class="preload">
    <!-- オープニングアニメーション -->
    <div class="lOpening">
        <div class="lOpening__inner">
            <div class="lOpening__hexagon"></div>
            <div class="lOpening__logo">
                <div class="lOpening__logo_main">山田工務店</div>
                <div class="lOpening__logo_sub">YAMADA KOUMUTEN</div>
            </div>
        </div>
    </div>

    <div class="cContainer">
        <!-- ヘッダー -->
        <header class="cHeader">
            <div class="cHeader__inner">
                <div class="cHeader__logo">
                    <a href="index.php" class="cHeader__logo_link">
                        <span class="cHeader__logo_main">山田工務店</span>
                        <span class="cHeader__logo_sub">YAMADA KOUMUTEN</span>
                    </a>
                </div>

                <nav class="cHeader__nav md">
                    <a href="#about" class="cHeader__nav_link">想い</a>
                    <a href="#works" class="cHeader__nav_link">施工事例</a>
                    <a href="#voice" class="cHeader__nav_link">お客様の声</a>
                    <a href="#flow" class="cHeader__nav_link">家づくりの流れ</a>
                </nav>

                <div class="cHeader__contact md">
                    <a href="#contact" class="cHeader__contact_link">
                        <span class="cHeader__contact_icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 8L10.89 13.26C11.5 13.67 12.5 13.67 13.11 13.26L21 8M5 19H19C20.1 19 21 18.1 21 17V7C21 5.9 20.1 5 19 5H5C3.9 5 3 5.9 3 7V17C3 18.1 3.9 19 5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="cHeader__contact_text">お問い合わせ</span>
                    </a>
                </div>

                <button class="cHeader__hamburger js-menu-toggle" aria-label="メニューを開く">
                    <span class="cHeader__hamburger_line"></span>
                    <span class="cHeader__hamburger_line"></span>
                    <span class="cHeader__hamburger_line"></span>
                </button>
            </div>
        </header>

        <!-- グローバルメニュー -->
        <div class="cMenu">
            <div class="cMenu__bg"></div>
            <div class="cMenu__inner">
                <div class="cMenu__content">
                    <nav class="cMenu__nav">
                        <div class="cMenu__nav_section">
                            <div class="cMenu__nav_label">NAVIGATION</div>
                            <a href="#about" class="cMenu__nav_link js-menu-link">
                                <span class="cMenu__nav_link_number">01</span>
                                <span class="cMenu__nav_link_text">想い</span>
                                <span class="cMenu__nav_link_en">PHILOSOPHY</span>
                            </a>
                            <a href="#works" class="cMenu__nav_link js-menu-link">
                                <span class="cMenu__nav_link_number">02</span>
                                <span class="cMenu__nav_link_text">施工事例</span>
                                <span class="cMenu__nav_link_en">WORKS</span>
                            </a>
                            <a href="#voice" class="cMenu__nav_link js-menu-link">
                                <span class="cMenu__nav_link_number">03</span>
                                <span class="cMenu__nav_link_text">お客様の声</span>
                                <span class="cMenu__nav_link_en">VOICE</span>
                            </a>
                            <a href="#flow" class="cMenu__nav_link js-menu-link">
                                <span class="cMenu__nav_link_number">04</span>
                                <span class="cMenu__nav_link_text">家づくりの流れ</span>
                                <span class="cMenu__nav_link_en">FLOW</span>
                            </a>
                            <a href="#contact" class="cMenu__nav_link js-menu-link">
                                <span class="cMenu__nav_link_number">05</span>
                                <span class="cMenu__nav_link_text">お問い合わせ</span>
                                <span class="cMenu__nav_link_en">CONTACT</span>
                            </a>
                        </div>
                    </nav>

                    <div class="cMenu__info">
                        <div class="cMenu__info_section">
                            <div class="cMenu__info_label">CONTACT</div>
                            <div class="cMenu__info_item">
                                <div class="cMenu__info_tel">
                                    <a href="tel:0612345678">06-1234-5678</a>
                                </div>
                                <div class="cMenu__info_time">受付時間：9:00〜18:00（水曜定休）</div>
                            </div>
                            <div class="cMenu__info_item">
                                <div class="cMenu__info_address">
                                    〒550-0000<br>
                                    大阪府大阪市西区北堀江 1-2-3<br>
                                    山田ビル 2F
                                </div>
                            </div>
                        </div>

                        <div class="cMenu__info_section">
                            <div class="cMenu__info_label">SOCIAL</div>
                            <div class="cMenu__sns">
                                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="cMenu__sns_link">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                                        <circle cx="18" cy="6" r="1.5" fill="currentColor"/>
                                    </svg>
                                    <span>Instagram</span>
                                </a>
                                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="cMenu__sns_link">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <span>Facebook</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="cMenu__decoration">
                    <div class="cMenu__decoration_hexagon cMenu__decoration_hexagon--1"></div>
                    <div class="cMenu__decoration_hexagon cMenu__decoration_hexagon--2"></div>
                    <div class="cMenu__decoration_hexagon cMenu__decoration_hexagon--3"></div>
                </div>
            </div>
        </div>

        <main class="cMain">
