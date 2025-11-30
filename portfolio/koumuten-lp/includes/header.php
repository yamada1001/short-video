<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">

    <title><?php echo isset($page_title) ? $page_title . ' | ' : ''; ?>株式会社〇〇工務店 | 大阪 | 注文住宅・リフォーム</title>
    <meta name="description" content="<?php echo isset($page_description) ? $page_description : '大阪で創業50年の実績。お客様一人ひとりに寄り添った家づくりを心がけています。注文住宅、リフォーム、リノベーションまで、安心してお任せください。'; ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Work+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body class="preload">
    <div class="cContainer">
        <header class="cHeader">
            <div class="cHeader__inner">
                <div class="cHeader__leftWrapper">
                    <a href="index.php" class="cHeader__logo cHover__opacity">
                        <span class="cHeader__logo_text">
                            〇〇工務店
                        </span>
                    </a>

                    <div class="cHeader__info md">
                        <div class="cHeader__info_address">
                            5-11-6, Example, Osaka Shi,
                        </div>

                        <div class="cHeader__info_contact">
                            <a href="tel:0612345678" class="cHeader__info_contact--tel">
                                T. 06-1234-5678
                            </a>

                            <span class="cHeader__info_contact--hour">
                                (9:00AM—6:00PM)
                            </span>
                        </div>
                    </div>
                </div>

                <div class="cHeader__contact cHover__opacity js-menu-contact">
                    <a href="#contact" class="cHeader__contact_link">
                        <span class="cHeader__contact_text">お問い合わせ</span>
                    </a>
                </div>

                <button class="cHeader__button js-menu-button" aria-label="メニューを開く">
                    <span class="visuallyHidden">メニューを開く</span>
                    <span class="cHeader__button_line"></span>
                    <span class="cHeader__button_line"></span>
                    <span class="cHeader__button_line"></span>
                </button>
            </div>
        </header>

        <!-- リッチなメニュー -->
        <div class="cMenu">
            <div class="cMenu__inner">
                <div class="cMenu__inner_bg">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=80" alt="">
                </div>

                <div class="cMenu__content">
                    <!-- ヘキサゴン配置 -->
                    <div class="cMenu__wrapper">
                        <div class="cMenu__hexagon_sp">
                            <div class="cMenu__main">
                                <div class="cMenu__main_inner">
                                    <div class="cMenu__main_item cMenu__main_item--en">MENU</div>

                                    <div class="cMenu__main_item cMenu__main_item--link">
                                        <a href="#about" class="cHover__underline">私たちについて</a>
                                    </div>
                                    <div class="cMenu__main_item cMenu__main_item--link">
                                        <a href="#works" class="cHover__underline">施工事例</a>
                                    </div>
                                    <div class="cMenu__main_item cMenu__main_item--link">
                                        <a href="#contact" class="cHover__underline">お問い合わせ</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="cMenu__hexagon_sp">
                            <div class="cMenu__sub">
                                <div class="cMenu__sub_inner">
                                    <a href="#" class="cMenu__sub_link cHover__underline">新築</a>
                                    <a href="#" class="cMenu__sub_link cHover__underline">リフォーム</a>
                                    <a href="#" class="cMenu__sub_link cHover__underline">リノベーション</a>
                                    <a href="#" class="cMenu__sub_link cHover__underline">造作家具</a>
                                </div>
                            </div>
                        </div>

                        <div class="cMenu__hexagon_sp md">
                            <div style="background-image: url('https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?w=600&q=80'); background-size: cover; background-position: center; width: 100%; height: 100%;"></div>
                        </div>

                        <div class="cMenu__hexagon md"></div>
                        <div class="cMenu__hexagon md"></div>
                    </div>
                </div>

                <div class="cMenu__info">
                    <div class="cMenu__info_inner">
                        <div class="cMenu__info_wrapper">
                            <div class="cMenu__info_underline">
                                <a href="tel:0612345678">T. 06-1234-5678</a>
                            </div>
                            <div class="cMenu__info_name">株式会社〇〇工務店</div>
                            <div class="cMenu__info_address">
                                〒550-0000<br>
                                大阪府大阪市〇〇区〇〇 5-11-6
                            </div>
                            <div class="cMenu__info_map">
                                <a href="https://maps.google.com" target="_blank" rel="noopener noreferrer" class="cHover__underline">Google Map</a>
                            </div>
                            <div class="cMenu__info_number">
                                <a href="tel:0612345678">TEL: 06-1234-5678</a>
                                <span>/</span>
                                <span>FAX: 06-1234-5679</span>
                            </div>
                            <div class="cMenu__info_hour">
                                <div class="hour">9:00AM—6:00PM</div>
                                <div class="holiday">定休日：水曜日・第2第4日曜日</div>
                            </div>
                        </div>

                        <div class="cMenu__sns">
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="cHover__opacity">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="2" y="2" width="20" height="20" rx="5" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="2"/>
                                    <circle cx="18" cy="6" r="1" fill="currentColor"/>
                                </svg>
                            </a>
                            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="cHover__opacity">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- オープニングアニメーション -->
        <div class="lOpening">
            <div class="lOpening__inner">
                <div class="lOpening__logo">
                    <span class="lOpening__logo_text">〇〇工務店</span>
                </div>
            </div>
        </div>

        <main class="cMain">
