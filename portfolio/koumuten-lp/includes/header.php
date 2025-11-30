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

        <main class="cMain">
