<!-- CTA Contact Section -->
<section class="section cta-contact">
    <div class="container">
        <div class="cta-contact__inner">
            <!-- 装飾要素 -->
            <div class="cta-contact__decoration cta-contact__decoration--top-left"></div>
            <div class="cta-contact__decoration cta-contact__decoration--bottom-right"></div>

            <div class="cta-contact__content">
                <div class="cta-contact__icon">
                    <i class="fa-solid fa-comments"></i>
                </div>

                <h2 class="cta-contact__title">
                    お車のことなら、<br class="pc-only">
                    お気軽にご相談ください
                </h2>

                <p class="cta-contact__lead">
                    無料査定・お問い合わせは、お電話またはメールフォームにて受付中です。<br>
                    お客様のご要望に合わせて、最適なご提案をさせていただきます。
                </p>

                <div class="cta-contact__methods">
                    <!-- 電話 -->
                    <a href="tel:<?php echo format_phone(PHONE, 'tel'); ?>" class="cta-method cta-method--phone">
                        <div class="cta-method__icon-wrapper">
                            <div class="cta-method__icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="cta-method__pulse"></div>
                        </div>
                        <div class="cta-method__content">
                            <div class="cta-method__label">お電話でのお問い合わせ</div>
                            <div class="cta-method__value"><?php echo PHONE; ?></div>
                            <div class="cta-method__info">
                                <i class="fa-solid fa-clock"></i>
                                <?php echo BUSINESS_HOURS; ?> / <?php echo BUSINESS_DAYS; ?>
                            </div>
                        </div>
                    </a>

                    <!-- メール -->
                    <a href="<?php echo url('contact'); ?>" class="cta-method cta-method--email">
                        <div class="cta-method__icon-wrapper">
                            <div class="cta-method__icon">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                        </div>
                        <div class="cta-method__content">
                            <div class="cta-method__label">メールフォームでのお問い合わせ</div>
                            <div class="cta-method__value">24時間受付中</div>
                            <div class="cta-method__info">
                                <i class="fa-solid fa-arrow-right"></i>
                                フォームへ進む
                            </div>
                        </div>
                    </a>
                </div>

                <!-- 追加の特徴 -->
                <div class="cta-contact__features">
                    <div class="cta-feature">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>出張査定対応</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>査定無料</span>
                    </div>
                    <div class="cta-feature">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>即日対応可能</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
