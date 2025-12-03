<!-- CTA Contact Section -->
<section class="section cta-contact">
    <div class="container">
        <div class="cta-contact__inner">
            <div class="cta-contact__content">
                <h2 class="cta-contact__title">
                    お車のことなら、<br>
                    お気軽にご相談ください
                </h2>

                <p class="cta-contact__lead">
                    無料査定・お問い合わせは、お電話またはメールフォームにて受付中です。<br>
                    お客様のご要望に合わせて、最適なご提案をさせていただきます。
                </p>

                <div class="cta-contact__buttons">
                    <a href="tel:<?php echo format_phone(PHONE, 'tel'); ?>" class="cta-contact__phone">
                        <div class="cta-contact__phone-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="cta-contact__phone-content">
                            <div class="cta-contact__phone-label">お電話でのお問い合わせ</div>
                            <div class="cta-contact__phone-number"><?php echo PHONE; ?></div>
                            <div class="cta-contact__phone-hours"><?php echo BUSINESS_HOURS; ?> / <?php echo BUSINESS_DAYS; ?></div>
                        </div>
                    </a>

                    <a href="<?php echo url('contact'); ?>" class="btn btn--primary btn--large btn--block">
                        <i class="fa-solid fa-envelope"></i>
                        メールで問い合わせ
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
