<?php
// 言語設定の確認
$footerIsEnglish = defined('IS_ENGLISH') && IS_ENGLISH;
$footerLangPrefix = $footerIsEnglish ? '/en' : '';

// 言語別テキスト
if ($footerIsEnglish) {
    $footerTexts = [
        'company_name' => 'Yojitsu',
        'company_desc' => 'A digital marketing company based in Oita Prefecture, providing SEO, advertising, web development, and short video production services.',
        'business_name' => 'Business Name',
        'registration' => 'Registration',
        'established' => 'Established',
        'services' => 'Services',
        'web_production' => 'Web Development',
        'video_production' => 'Video Production',
        'area' => 'Service Areas',
        'service_details' => 'Service Details',
        'company_info' => 'Company',
        'about' => 'About Us',
        'recruit' => 'Recruitment',
        'blog' => 'Blog',
        'news' => 'News',
        'contact' => 'Contact',
        'privacy' => 'Privacy Policy',
        'sitemap' => 'Sitemap',
        'contact_section' => 'Contact',
        'line_contact' => 'Contact',
        'hours' => 'Hours',
        'open' => 'Open',
        'contact_form' => 'Contact Form',
        'copyright' => '&copy; 2025 Yojitsu. All rights reserved.'
    ];
} else {
    $footerTexts = [
        'company_name' => '余日（Yojitsu）',
        'company_desc' => '大分県を拠点に、SEO・広告運用・Web制作・ショート動画制作を提供するデジタルマーケティング会社です。',
        'business_name' => '屋号',
        'registration' => '登録番号',
        'established' => '設立',
        'services' => 'サービス',
        'web_production' => 'Web制作',
        'video_production' => 'ショート動画制作',
        'area' => '対応エリア',
        'service_details' => 'サービス詳細',
        'company_info' => '企業情報',
        'about' => '会社概要',
        'recruit' => '業務委託募集・交流',
        'blog' => 'ブログ',
        'news' => 'お知らせ',
        'contact' => 'お問い合わせ',
        'privacy' => 'プライバシーポリシー',
        'sitemap' => 'サイトマップ',
        'contact_section' => 'お問い合わせ',
        'line_contact' => 'お問い合わせ',
        'hours' => '営業時間',
        'open' => '定休日',
        'contact_form' => 'お問い合わせフォーム',
        'copyright' => '&copy; 2025 余日（Yojitsu）. All rights reserved.'
    ];
}
?>
<!-- フッター -->
<footer class="footer">
    <div class="container">
        <div class="footer__content">
            <div class="footer__section">
                <h3 class="footer__section-title"><?php echo $footerTexts['company_name']; ?></h3>
                <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 16px; line-height: 1.9;">
                    <?php echo $footerTexts['company_desc']; ?>
                </p>
                <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                    <i class="fas fa-building" style="margin-right: 8px;"></i><?php echo $footerTexts['business_name']; ?>: <?php echo COMPANY_NAME; ?><br>
                    <i class="fas fa-file-invoice" style="margin-right: 8px;"></i><?php echo $footerTexts['registration']; ?>: <?php echo COMPANY_TAX_ID; ?><br>
                    <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i><?php echo $footerTexts['established']; ?>: <?php echo COMPANY_FOUNDED; ?>
                </p>
            </div>
            <div class="footer__section">
                <h3 class="footer__section-title"><?php echo $footerTexts['services']; ?></h3>
                <a href="<?php echo $footerLangPrefix; ?>/web-production.php" class="footer__link"><i class="fas fa-laptop-code"></i> <?php echo $footerTexts['web_production']; ?></a>
                <a href="<?php echo $footerLangPrefix; ?>/video-production.php" class="footer__link"><i class="fas fa-video"></i> <?php echo $footerTexts['video_production']; ?></a>
                <?php if (!$footerIsEnglish): ?>
                <a href="/area/" class="footer__link"><i class="fas fa-map-marker-alt"></i> <?php echo $footerTexts['area']; ?>（Web制作）</a>
                <a href="/area/video/" class="footer__link"><i class="fas fa-map-marker-alt"></i> <?php echo $footerTexts['area']; ?>（動画）</a>
                <?php endif; ?>
                <a href="<?php echo $footerLangPrefix; ?>/services.php" class="footer__link" style="margin-top: 8px; opacity: 0.8;"><i class="fas fa-arrow-right"></i> <?php echo $footerTexts['service_details']; ?></a>
            </div>
            <div class="footer__section">
                <h3 class="footer__section-title"><?php echo $footerTexts['company_info']; ?></h3>
                <a href="<?php echo $footerLangPrefix; ?>/about.php" class="footer__link"><i class="fas fa-info-circle"></i> <?php echo $footerTexts['about']; ?></a>
                <a href="<?php echo $footerLangPrefix; ?>/recruit.php" class="footer__link"><i class="fas fa-handshake"></i> <?php echo $footerTexts['recruit']; ?></a>
                <a href="<?php echo $footerLangPrefix; ?>/blog/" class="footer__link"><i class="fas fa-blog"></i> <?php echo $footerTexts['blog']; ?></a>
                <a href="<?php echo $footerLangPrefix; ?>/news/" class="footer__link"><i class="fas fa-newspaper"></i> <?php echo $footerTexts['news']; ?></a>
                <a href="<?php echo $footerLangPrefix; ?>/contact.php" class="footer__link"><i class="fas fa-envelope"></i> <?php echo $footerTexts['contact']; ?></a>
                <a href="<?php echo $footerLangPrefix; ?>/privacy.php" class="footer__link"><i class="fas fa-shield-alt"></i> <?php echo $footerTexts['privacy']; ?></a>
                <a href="<?php echo $footerLangPrefix; ?>/sitemap-page.php" class="footer__link"><i class="fas fa-sitemap"></i> <?php echo $footerTexts['sitemap']; ?></a>
            </div>
            <div class="footer__section">
                <h3 class="footer__section-title"><?php echo $footerTexts['contact_section']; ?></h3>
                <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 12px; line-height: 1.9;">
                    <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_TEL; ?></a><br>
                    <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_EMAIL; ?></a><br>
                    <i class="fab fa-line" style="margin-right: 8px;"></i>LINE: <a href="<?php echo CONTACT_LINE_URL; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo $footerTexts['line_contact']; ?></a><br>
                    <i class="fab fa-google" style="margin-right: 8px;"></i>Google: <a href="https://share.google/a1I8CoPFkxa4plijz" target="_blank" rel="noopener" style="color: rgba(255, 255, 255, 0.9);">ビジネスプロフィール</a>
                </p>
                <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                    <i class="fas fa-clock" style="margin-right: 8px;"></i><?php echo $footerTexts['hours']; ?>: <?php echo BUSINESS_HOURS; ?><br>
                    <i class="fas fa-calendar-check" style="margin-right: 8px;"></i><?php echo $footerTexts['open']; ?>: <?php echo BUSINESS_DAYS; ?>
                </p>
                <div style="margin-top: 16px;">
                    <a href="<?php echo $footerLangPrefix; ?>/contact.php" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; font-size: 14px;"><?php echo $footerTexts['contact_form']; ?></a>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <p><?php echo $footerTexts['copyright']; ?></p>
        </div>
    </div>
</footer>
