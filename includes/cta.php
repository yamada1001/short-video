<!-- CTAセクション -->
<?php
// デフォルト設定
$cta_title = isset($cta_title) ? $cta_title : 'お問い合わせ';
$cta_description = isset($cta_description) ? $cta_description : 'デジタルマーケティングのご相談は、<br>お気軽にお問い合わせください';
$cta_base_path = isset($cta_base_path) ? $cta_base_path : '';
$cta_show_info = isset($cta_show_info) ? $cta_show_info : false;
$cta_animate = isset($cta_animate) ? $cta_animate : true;
$animate_class = $cta_animate ? ' animate' : '';
?>
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-section__title<?php echo $animate_class; ?>"><?php echo $cta_title; ?></h2>
            <p class="cta-section__description<?php echo $animate_class; ?>">
                <?php echo $cta_description; ?>
            </p>
            <div class="cta-buttons<?php echo $animate_class; ?>">
                <a href="<?php echo $cta_base_path; ?>contact.html" class="btn btn-primary btn--large">
                    <i class="fas fa-envelope"></i> お問い合わせフォーム
                </a>
                <a href="https://line.me/ti/p/CTOCx9YKjk" class="btn btn-secondary btn--large" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-line"></i> LINEで相談
                </a>
            </div>
            <?php if ($cta_show_info): ?>
            <div class="cta-info<?php echo $animate_class; ?>">
                <div class="cta-info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <span class="cta-info-label">お電話でのお問い合わせ</span>
                        <a href="tel:08046929681" class="cta-info-value">080-4692-9681</a>
                    </div>
                </div>
                <div class="cta-info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <span class="cta-info-label">メールでのお問い合わせ</span>
                        <a href="mailto:yamada@yojitu.com" class="cta-info-value">yamada@yojitu.com</a>
                    </div>
                </div>
                <div class="cta-info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <span class="cta-info-label">営業時間</span>
                        <span class="cta-info-value">10時〜22時（定休日なし）</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
