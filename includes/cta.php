<!-- CTAセクション -->
<?php
// config.phpを読み込む（未読み込みの場合のみ）
if (!defined('CONTACT_EMAIL')) {
    require_once __DIR__ . '/config.php';
}
$cta_base_path = isset($cta_base_path) ? $cta_base_path : '';
?>
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <div class="section-header section-header--center">
                <span class="section-header__label animate">Contact</span>
                <h2 class="cta-section__title animate">お問い合わせ</h2>
                <p class="cta-section__description animate">
                    デジタルマーケティングのご相談は、<br>お気軽にお問い合わせください
                </p>
            </div>
            <div class="cta-buttons animate">
                <a href="<?php echo $cta_base_path; ?>contact.php" class="cta-btn cta-btn--primary">
                    <span class="cta-btn__icon"><i class="fas fa-envelope"></i></span>
                    <span class="cta-btn__text">お問い合わせフォーム</span>
                    <span class="cta-btn__arrow"><i class="fas fa-arrow-right"></i></span>
                </a>
                <a href="<?php echo defined('CONTACT_LINE_URL') ? CONTACT_LINE_URL : 'https://line.me/ti/p/CTOCx9YKjk'; ?>" class="cta-btn cta-btn--secondary" target="_blank" rel="noopener noreferrer">
                    <span class="cta-btn__icon"><i class="fab fa-line"></i></span>
                    <span class="cta-btn__text">LINEで相談</span>
                    <span class="cta-btn__arrow"><i class="fas fa-arrow-right"></i></span>
                </a>
            </div>
            <div class="cta-info-grid animate">
                <div class="cta-info-card">
                    <div class="cta-info-card__icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <span class="cta-info-card__label">お電話</span>
                    <a href="tel:<?php echo defined('CONTACT_TEL_LINK') ? CONTACT_TEL_LINK : '08046929681'; ?>" class="cta-info-card__value" target="_blank" rel="noopener noreferrer">
                        <?php echo defined('CONTACT_TEL') ? CONTACT_TEL : '080-4692-9681'; ?>
                    </a>
                </div>
                <div class="cta-info-card">
                    <div class="cta-info-card__icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <span class="cta-info-card__label">メール</span>
                    <a href="mailto:<?php echo defined('CONTACT_EMAIL') ? CONTACT_EMAIL : 'yamada@yojitu.com'; ?>" class="cta-info-card__value" target="_blank" rel="noopener noreferrer">
                        <?php echo defined('CONTACT_EMAIL') ? CONTACT_EMAIL : 'yamada@yojitu.com'; ?>
                    </a>
                </div>
                <div class="cta-info-card">
                    <div class="cta-info-card__icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="cta-info-card__label">営業時間</span>
                    <span class="cta-info-card__value">10時~22時（定休日なし）</span>
                </div>
            </div>
        </div>
    </div>
</section>
