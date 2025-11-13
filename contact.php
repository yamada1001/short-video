<?php $current_page = 'contact'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="余日（Yojitsu）へのお問い合わせはこちら。LINE、電話、メールフォームで受け付けております。">
    <title>お問い合わせ | 余日（Yojitsu）</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/pages/contact.css">
    <link rel="stylesheet" href="assets/css/cookie-consent.css">

    <!-- Font Awesome - Async load -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <!-- Google Tag Manager - Async -->
    <script async src="https://www.googletagmanager.com/gtm.js?id=GTM-T7NGQDC2"></script>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- お問い合わせ方法 -->
    <section class="contact-methods">
        <div class="container">
            <h2 class="section__title animate fade-in">お問い合わせ方法</h2>

            <!-- アコーディオン形式 -->
            <div class="contact-accordion">
                <!-- LINE -->
                <div class="accordion-item animate">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-header__content">
                            <i class="fab fa-line" style="color: #06C755; font-size: 24px; margin-right: 12px;"></i>
                            <span class="accordion-title">LINE</span>
                        </div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </button>
                    <div class="accordion-body">
                        <p class="accordion-text">
                            お気軽にLINEでご連絡ください。<br>
                            ご返信は24時間以内に行います。
                        </p>
                        <a href="https://line.me/ti/p/CTOCx9YKjk" class="btn btn-primary" target="_blank" rel="noopener noreferrer" style="margin-top: 16px; display: inline-block;">
                            <i class="fab fa-line"></i> LINEで相談する
                        </a>
                    </div>
                </div>

                <!-- 電話 -->
                <div class="accordion-item animate">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-header__content">
                            <i class="fas fa-phone" style="color: var(--color-natural-brown); font-size: 24px; margin-right: 12px;"></i>
                            <span class="accordion-title">電話</span>
                        </div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </button>
                    <div class="accordion-body">
                        <p class="accordion-text">
                            <strong style="font-size: 18px; color: var(--color-natural-brown);">080-4692-9681</strong><br>
                            対応時間：9:00〜21:00（年中無休）
                        </p>
                        <a href="tel:08046929681" class="btn btn-primary" style="margin-top: 16px; display: inline-block;">
                            <i class="fas fa-phone"></i> 電話をかける
                        </a>
                    </div>
                </div>

                <!-- メール -->
                <div class="accordion-item animate">
                    <button class="accordion-header" onclick="toggleAccordion(this)">
                        <div class="accordion-header__content">
                            <i class="fas fa-envelope" style="color: var(--color-natural-brown); font-size: 24px; margin-right: 12px;"></i>
                            <span class="accordion-title">メール</span>
                        </div>
                        <i class="fas fa-chevron-down accordion-icon"></i>
                    </button>
                    <div class="accordion-body">
                        <p class="accordion-text">
                            <strong style="font-size: 16px; color: var(--color-natural-brown);">yamada@yojitu.com</strong><br>
                            24時間受付中
                        </p>
                        <a href="mailto:yamada@yojitu.com" class="btn btn-primary" style="margin-top: 16px; display: inline-block;">
                            <i class="fas fa-envelope"></i> メールを送る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function toggleAccordion(button) {
        const item = button.parentElement;
        const body = item.querySelector('.accordion-body');
        const icon = button.querySelector('.accordion-icon');

        // 他のアコーディオンを閉じる
        document.querySelectorAll('.accordion-item').forEach(function(otherItem) {
            if (otherItem !== item) {
                otherItem.classList.remove('active');
                otherItem.querySelector('.accordion-body').style.maxHeight = null;
                otherItem.querySelector('.accordion-icon').style.transform = 'rotate(0deg)';
            }
        });

        // クリックされたアコーディオンを開閉
        item.classList.toggle('active');
        if (item.classList.contains('active')) {
            body.style.maxHeight = body.scrollHeight + 'px';
            icon.style.transform = 'rotate(180deg)';
        } else {
            body.style.maxHeight = null;
            icon.style.transform = 'rotate(0deg)';
        }
    }
    </script>

    <style>
    .contact-accordion {
        max-width: 800px;
        margin: 0 auto;
    }

    .accordion-item {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        margin-bottom: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .accordion-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .accordion-header {
        width: 100%;
        padding: 20px 24px;
        background: none;
        border: none;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .accordion-header:hover {
        background-color: #f5f5f5;
    }

    .accordion-header__content {
        display: flex;
        align-items: center;
    }

    .accordion-title {
        font-size: 18px;
        font-weight: 500;
        color: var(--color-text);
    }

    .accordion-icon {
        font-size: 14px;
        color: var(--color-natural-brown);
        transition: transform 0.3s;
    }

    .accordion-body {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
        padding: 0 24px;
    }

    .accordion-item.active .accordion-body {
        padding: 0 24px 24px;
    }

    .accordion-text {
        color: var(--color-text);
        line-height: 1.8;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .accordion-header {
            padding: 16px 20px;
        }

        .accordion-title {
            font-size: 16px;
        }

        .accordion-item.active .accordion-body {
            padding: 0 20px 20px;
        }
    }
    </style>

    <!-- お問い合わせフォーム -->
    <section class="contact-form">
        <div class="container contact-form__container">
            <h2 class="section__title animate">メールフォーム</h2>
            <p class="contact-form__intro animate">
                以下のフォームからもお問い合わせいただけます。<br>
                内容を確認後、担当者よりご連絡させていただきます。
            </p>

            <form action="includes/contact-form.php" method="POST" class="form animate" id="contactForm">
                <div class="form-group">
                    <label for="company" class="form-label">会社名・団体名</label>
                    <input type="text" id="company" name="company" class="form-input" placeholder="株式会社〇〇">
                </div>

                <div class="form-group">
                    <label for="name" class="form-label form-label--required">お名前</label>
                    <input type="text" id="name" name="name" class="form-input" required placeholder="山田 太郎">
                    <div class="form-error" id="nameError">お名前を入力してください</div>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label form-label--required">メールアドレス</label>
                    <input type="email" id="email" name="email" class="form-input" required placeholder="example@example.com">
                    <div class="form-error" id="emailError">正しいメールアドレスを入力してください</div>
                </div>

                <div class="form-group">
                    <label for="tel" class="form-label">電話番号</label>
                    <input type="tel" id="tel" name="tel" class="form-input" placeholder="090-1234-5678">
                </div>

                <div class="form-group">
                    <label for="subject" class="form-label form-label--required">お問い合わせ種別</label>
                    <select id="subject" name="subject" class="form-select" required>
                        <option value="">選択してください</option>
                        <option value="SEO対策について">SEO対策について</option>
                        <option value="広告運用について">広告運用について</option>
                        <option value="Web制作について">Web制作について</option>
                        <option value="ショート動画制作について">ショート動画制作について</option>
                        <option value="業務委託・協業について">業務委託・協業について</option>
                        <option value="見積もり依頼">見積もり依頼</option>
                        <option value="営業のご連絡">営業のご連絡</option>
                        <option value="その他">その他</option>
                    </select>
                    <div class="form-error" id="subjectError">お問い合わせ種別を選択してください</div>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label form-label--required">お問い合わせ内容</label>
                    <textarea id="message" name="message" class="form-textarea" required placeholder="お問い合わせ内容をご記入ください"></textarea>
                    <p class="form-help">具体的な内容をご記入いただけますと、スムーズに対応できます。</p>
                    <div class="form-error" id="messageError">お問い合わせ内容を入力してください</div>
                </div>

                <div class="form-submit">
                    <button type="submit" class="btn btn-primary btn--large">送信する</button>
                </div>
            </form>
        </div>
    </section>

    <!-- フッター -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h3 class="footer__section-title">余日（Yojitsu）</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 16px; line-height: 1.9;">
                        大分県を拠点に、SEO・広告運用・Web制作・ショート動画制作を提供するデジタルマーケティング会社です。
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-building" style="margin-right: 8px;"></i>屋号: 余日（Yojitsu）<br>
                        <i class="fas fa-file-invoice" style="margin-right: 8px;"></i>登録番号: T9810094141774<br>
                        <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>設立: 令和7年5月14日
                    </p>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">サービス</h3>
                    <a href="services.html#seo" class="footer__link"><i class="fas fa-search"></i> SEO対策</a>
                    <a href="services.html#ads" class="footer__link"><i class="fas fa-bullhorn"></i> 広告運用</a>
                    <a href="services.html#web" class="footer__link"><i class="fas fa-laptop-code"></i> Web制作</a>
                    <a href="services.html#short-video" class="footer__link"><i class="fas fa-video"></i> ショート動画制作</a>
                    <a href="services.html" class="footer__link" style="margin-top: 8px; opacity: 0.8;"><i class="fas fa-arrow-right"></i> サービス詳細</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">企業情報</h3>
                    <a href="about.html" class="footer__link"><i class="fas fa-info-circle"></i> 会社概要</a>
                    <a href="recruit.php" class="footer__link"><i class="fas fa-handshake"></i> 業務委託募集・交流</a>
                    <a href="blog/" class="footer__link"><i class="fas fa-blog"></i> ブログ</a>
                    <a href="news/" class="footer__link"><i class="fas fa-newspaper"></i> お知らせ</a>
                    <a href="contact.html" class="footer__link"><i class="fas fa-envelope"></i> お問い合わせ</a>
                    <a href="privacy.html" class="footer__link"><i class="fas fa-shield-alt"></i> プライバシーポリシー</a>
                    <a href="sitemap-page.php" class="footer__link"><i class="fas fa-sitemap"></i> サイトマップ</a>
                </div>
                <div class="footer__section">
                    <h3 class="footer__section-title">お問い合わせ</h3>
                    <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 12px; line-height: 1.9;">
                        <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:08046929681" style="color: rgba(255, 255, 255, 0.9);">080-4692-9681</a><br>
                        <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:yamada@yojitu.com" style="color: rgba(255, 255, 255, 0.9);">yamada@yojitu.com</a><br>
                        <i class="fab fa-line" style="margin-right: 8px;"></i>LINE: <a href="https://line.me/ti/p/CTOCx9YKjk" style="color: rgba(255, 255, 255, 0.9);">お問い合わせ</a>
                    </p>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                        <i class="fas fa-clock" style="margin-right: 8px;"></i>営業時間: 10時~22時<br>
                        <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>定休日: なし
                    </p>
                    <div style="margin-top: 16px;">
                        <a href="contact.html" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; font-size: 14px;">お問い合わせフォーム</a>
                    </div>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="assets/js/app.js"></script>
    <script defer src="assets/js/form-validation.js"></script>
    <!-- Cookie同意バナー -->
    <div id="cookieConsent" class="cookie-consent">
        <div class="cookie-consent__container">
            <div class="cookie-consent__content">
                <p class="cookie-consent__text">
                    当サイトは、ウェブサイトにおけるお客様の利用状況を把握するためにCookieを使用しています。「同意する」をクリックすると、当サイトでのCookieの使用に同意することになります。
                    <a href="privacy.html" class="cookie-consent__link">プライバシーポリシー</a>
                </p>
            </div>
            <div class="cookie-consent__actions">
                <button id="acceptCookies" class="cookie-consent__button cookie-consent__button--accept">同意する</button>
                <button id="declineCookies" class="cookie-consent__button cookie-consent__button--decline">拒否する</button>
            </div>
        </div>
    </div>

</body>
</html>
