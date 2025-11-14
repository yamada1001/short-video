<?php
$current_page = 'about';
require_once __DIR__ . '/includes/functions.php';

// 会社情報を取得
$company_data = file_get_contents(__DIR__ . '/includes/data/company.json');
$company_json = json_decode($company_data, true);
$company = $company_json['company'] ?? [];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="余日（Yojitsu）の会社概要。大分県を拠点に、デジタルマーケティングで地域企業を支援しています。">
    <title>会社概要 | 余日（Yojitsu）</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">

    <link rel="icon" type="image/svg+xml" href="favicon.svg">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/cta.css">
    <link rel="stylesheet" href="assets/css/pages/about.css">
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

    <!-- 会社情報 -->
    <section class="company-info">
        <div class="container">
            <div class="company-info__grid">
                <div class="company-info__image animate">
                    <i class="fas fa-building"></i>
                </div>
                <div class="company-info__content animate">
                    <h2 class="section__title" style="text-align: left;">余日について</h2>
                    <p class="company-info__description">
                        余日（Yojitsu）は、大分県を拠点としたデジタルマーケティング・Web制作会社です。「余日」という名前には、日々の業務の中で生まれる余白の時間を大切にし、その時間でクリエイティブな発想を生み出すという想いが込められています。
                    </p>
                    <p class="company-info__description">
                        SEO対策、広告運用、Web制作、ショート動画制作など、デジタルマーケティング全般にわたるサービスを提供。特に大分県内の企業様を中心に、地域に根ざしたマーケティング支援を行っています。
                    </p>
                    <p class="company-info__description">
                        Web制作会社での3年間の経験を活かし、マーケティング視点を持ったWebサイト制作と、データに基づいた広告運用で、お客様のビジネス成長をサポートします。
                    </p>
                </div>
            </div>

            <!-- 会社データ -->
            <table class="company-table animate">
                <tr>
                    <th>屋号</th>
                    <td><?php echo h($company['name']); ?></td>
                </tr>
                <tr>
                    <th>代表</th>
                    <td><?php echo h($company['representative']); ?></td>
                </tr>
                <tr>
                    <th>拠点</th>
                    <td><?php echo h($company['location']); ?>（オンライン対応可）</td>
                </tr>
                <tr>
                    <th>設立</th>
                    <td><?php echo h($company['foundedJp']); ?>（<?php echo h($company['founded']); ?>）</td>
                </tr>
                <tr>
                    <th>登録番号</th>
                    <td>適格請求書発行事業者<br><?php echo h($company['taxId']); ?></td>
                </tr>
                <tr>
                    <th>事業内容</th>
                    <td>
                        <?php foreach ($company['services'] as $service): ?>
                            <?php echo h($service); ?><br>
                        <?php endforeach; ?>
                    </td>
                </tr>
                <tr>
                    <th>電話番号</th>
                    <td><?php echo CONTACT_TEL; ?></td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td><?php echo CONTACT_EMAIL; ?></td>
                </tr>
            </table>
        </div>
    </section>

    <!-- バリュー -->
    <section class="values">
        <div class="container">
            <h2 class="section__title animate">私たちの価値観</h2>
            <div class="values__grid">
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="value-item__title">顧客第一</h3>
                    <p class="value-item__description">
                        お客様の成功が私たちの成功。常にお客様の立場に立ち、最善の提案を行います。
                    </p>
                </div>
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="value-item__title">データドリブン</h3>
                    <p class="value-item__description">
                        感覚ではなくデータに基づいた戦略立案。確実な成果を追求します。
                    </p>
                </div>
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-sync"></i>
                    </div>
                    <h3 class="value-item__title">継続的改善</h3>
                    <p class="value-item__description">
                        常に最新のトレンドをキャッチアップし、より良いサービスを提供し続けます。
                    </p>
                </div>
                <div class="value-item animate">
                    <div class="value-item__icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="value-item__title">地域貢献</h3>
                    <p class="value-item__description">
                        大分県の企業様と共に成長し、地域経済の発展に寄与します。
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- 代表プロフィール -->
    <section class="profile">
        <div class="container profile__container">
            <h2 class="section__title animate">代表プロフィール</h2>
            <div class="profile__card animate">
                <div class="profile__header">
                    <div class="profile__image">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile__info">
                        <h3 class="profile__name">山田 蓮</h3>
                        <p class="profile__title">代表 / デジタルマーケター</p>
                    </div>
                </div>
                <div class="profile__description">
                    <p>Web制作会社にて、マーケティング・Web制作業務を3年間担当。SEO・SNS・広告運用代行を中心に、多数の企業様のデジタルマーケティングを支援。</p>
                    <p style="margin-top: 16px;">独立後は外部CMOやプロジェクトマネージャー、動画制作、Web制作を行いながら、大分県を拠点にデジタルマーケティング事業を展開。</p>
                    <p style="margin-top: 16px;">データに基づいた戦略立案と、クリエイティブな発想を掛け合わせた提案を得意としています。</p>
                    <div class="certifications">
                        <h4 class="certifications__title">保有資格</h4>
                        <div class="certifications__grid">
                            <div class="certification-card">
                                <img src="https://api.accredible.com/v1/frontend/credential_website_embed_image/certificate/166225387" alt="Google広告認定資格" class="certification-card__image">
                                <p class="certification-card__label">Google広告認定資格</p>
                            </div>
                            <div class="certification-card">
                                <img src="https://api.accredible.com/v1/frontend/credential_website_embed_image/certificate/166239443" alt="Google Analytics認定資格" class="certification-card__image">
                                <p class="certification-card__label">Google Analytics認定資格</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTAセクション -->
    <?php $cta_base_path = ''; include __DIR__ . '/includes/cta.php'; ?>

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
                    <a href="web-production.php" class="footer__link"><i class="fas fa-laptop-code"></i> Web制作</a>
                    <a href="video-production.php" class="footer__link"><i class="fas fa-video"></i> ショート動画制作</a>
                    <a href="services.php" class="footer__link" style="margin-top: 8px; opacity: 0.8;"><i class="fas fa-arrow-right"></i> サービス詳細</a>
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
                        <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_TEL; ?></a><br>
                        <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_EMAIL; ?></a><br>
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
