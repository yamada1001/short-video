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
                    <i class="fas fa-building" style="margin-right: 8px;"></i>屋号: <?php echo COMPANY_NAME; ?><br>
                    <i class="fas fa-file-invoice" style="margin-right: 8px;"></i>登録番号: <?php echo COMPANY_TAX_ID; ?><br>
                    <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i>設立: <?php echo COMPANY_FOUNDED; ?>
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
                <a href="about.php" class="footer__link"><i class="fas fa-info-circle"></i> 会社概要</a>
                <a href="recruit.php" class="footer__link"><i class="fas fa-handshake"></i> 業務委託募集・交流</a>
                <a href="blog/" class="footer__link"><i class="fas fa-blog"></i> ブログ</a>
                <a href="news/" class="footer__link"><i class="fas fa-newspaper"></i> お知らせ</a>
                <a href="contact.php" class="footer__link"><i class="fas fa-envelope"></i> お問い合わせ</a>
                <a href="privacy.php" class="footer__link"><i class="fas fa-shield-alt"></i> プライバシーポリシー</a>
                <a href="sitemap-page.php" class="footer__link"><i class="fas fa-sitemap"></i> サイトマップ</a>
            </div>
            <div class="footer__section">
                <h3 class="footer__section-title">お問い合わせ</h3>
                <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 12px; line-height: 1.9;">
                    <i class="fas fa-phone" style="margin-right: 8px;"></i>Tel: <a href="tel:<?php echo CONTACT_TEL_LINK; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_TEL; ?></a><br>
                    <i class="fas fa-envelope" style="margin-right: 8px;"></i>Email: <a href="mailto:<?php echo CONTACT_EMAIL; ?>" style="color: rgba(255, 255, 255, 0.9);"><?php echo CONTACT_EMAIL; ?></a><br>
                    <i class="fab fa-line" style="margin-right: 8px;"></i>LINE: <a href="<?php echo CONTACT_LINE_URL; ?>" style="color: rgba(255, 255, 255, 0.9);">お問い合わせ</a>
                </p>
                <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px; line-height: 1.8;">
                    <i class="fas fa-clock" style="margin-right: 8px;"></i>営業時間: <?php echo BUSINESS_HOURS; ?><br>
                    <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>定休日: <?php echo BUSINESS_DAYS; ?>
                </p>
                <div style="margin-top: 16px;">
                    <a href="contact.php" class="btn btn-primary" style="display: inline-block; padding: 12px 24px; font-size: 14px;">お問い合わせフォーム</a>
                </div>
            </div>
        </div>
        <div class="footer__bottom">
            <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
        </div>
    </div>
</footer>
