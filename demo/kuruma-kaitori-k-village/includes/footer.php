    </main>

    <footer class="footer">
        <div class="footer__container">
            <div class="footer__content">
                <!-- 会社情報 -->
                <div class="footer__company">
                    <div class="footer__logo">
                        <i class="fa-solid fa-car footer__logo-icon"></i>
                        <?php echo COMPANY_NAME; ?>
                    </div>

                    <p class="footer__description">
                        <?php echo SITE_DESCRIPTION; ?>
                    </p>

                    <address class="footer__address">
                        <?php echo POSTAL_CODE; ?><br>
                        <?php echo ADDRESS; ?>
                    </address>

                    <div class="footer__contact">
                        <div class="footer__contact-item">
                            <i class="fa-solid fa-phone footer__contact-icon"></i>
                            <a href="tel:<?php echo format_phone(PHONE, 'tel'); ?>" class="footer__contact-link">
                                <?php echo PHONE; ?>
                            </a>
                        </div>
                        <div class="footer__contact-item">
                            <i class="fa-solid fa-envelope footer__contact-icon"></i>
                            <a href="mailto:<?php echo EMAIL; ?>" class="footer__contact-link">
                                <?php echo EMAIL; ?>
                            </a>
                        </div>
                        <div class="footer__contact-item">
                            <i class="fa-solid fa-id-card footer__contact-icon"></i>
                            <?php echo LICENSE_NAME; ?>：<?php echo LICENSE_NUMBER; ?>
                        </div>
                    </div>
                </div>

                <!-- リンク -->
                <div class="footer__links">
                    <h3 class="footer__links-title">サイトマップ</h3>
                    <ul class="footer__links-list">
                        <li class="footer__links-item">
                            <a href="<?php echo url(); ?>" class="footer__links-link">トップページ</a>
                        </li>
                        <li class="footer__links-item">
                            <a href="<?php echo url('about'); ?>" class="footer__links-link">会社概要</a>
                        </li>
                        <li class="footer__links-item">
                            <a href="<?php echo url('news'); ?>" class="footer__links-link">お知らせ</a>
                        </li>
                        <li class="footer__links-item">
                            <a href="<?php echo url('contact'); ?>" class="footer__links-link">お問い合わせ</a>
                        </li>
                        <li class="footer__links-item">
                            <a href="<?php echo url('privacy'); ?>" class="footer__links-link">プライバシーポリシー</a>
                        </li>
                        <li class="footer__links-item">
                            <a href="<?php echo url('sitemap'); ?>" class="footer__links-link">サイトマップ</a>
                        </li>
                        <li class="footer__links-item">
                            <a href="<?php echo url('tokushoho'); ?>" class="footer__links-link">特定商取引法に基づく表記</a>
                        </li>
                    </ul>
                </div>

                <!-- 営業時間 -->
                <div class="footer__hours">
                    <h3 class="footer__hours-title">営業時間</h3>
                    <div class="footer__hours-list">
                        <div><i class="fa-solid fa-clock"></i> <?php echo BUSINESS_HOURS; ?></div>
                        <div><i class="fa-solid fa-calendar-days"></i> <?php echo BUSINESS_DAYS; ?></div>
                        <div><i class="fa-solid fa-circle-xmark"></i> <?php echo HOLIDAY; ?></div>
                    </div>
                </div>
            </div>

            <!-- コピーライト -->
            <div class="footer__bottom">
                <div class="footer__copyright">
                    <i class="fa-regular fa-copyright footer__copyright-icon"></i>
                    <?php echo date('Y'); ?> <?php echo COMPANY_NAME; ?>. All Rights Reserved.
                </div>

                <!-- トップへ戻る -->
                <a href="#" class="footer__back-to-top" id="back-to-top">
                    <i class="fa-solid fa-arrow-up"></i>
                </a>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?php echo asset('assets/js/common.js'); ?>"></script>
    <script src="<?php echo asset('assets/js/modern-effects.js'); ?>"></script>
</body>
</html>
