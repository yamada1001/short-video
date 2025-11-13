<!-- CTAセクション -->
<?php
$cta_base_path = isset($cta_base_path) ? $cta_base_path : '';
?>
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2 class="cta-section__title animate fade-in">お問い合わせ</h2>
            <p class="cta-section__description animate fade-in">
                デジタルマーケティングのご相談は、<br>お気軽にお問い合わせください            </p>
            <div class="cta-buttons animate fade-in">
                <a href="<?php echo $cta_base_path; ?>contact.html" class="btn btn-primary btn--large">
                    <i class="fas fa-envelope"></i> お問い合わせフォーム
                </a>
                <a href="https://line.me/ti/p/CTOCx9YKjk" class="btn btn-secondary btn--large" target="_blank" rel="noopener noreferrer">
                    <i class="fab fa-line"></i> LINEで相談
                <span class="external-icon"> <i class="fas fa-external-link-alt" style="font-size: 0.8em; margin-left: 4px; opacity: 0.6;"></i></span></a>
            </div>
                        <div class="cta-info animate fade-in">
                <div class="cta-info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <span class="cta-info-label">お電話でのお問い合わせ</span>
                        <a href="tel:08046929681" class="cta-info-value" target="_blank" rel="noopener noreferrer">080-4692-9681<span class="external-icon"> <i class="fas fa-external-link-alt" style="font-size: 0.8em; margin-left: 4px; opacity: 0.6;"></i></span></a>
                    </div>
                </div>
                <div class="cta-info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <span class="cta-info-label">メールでのお問い合わせ</span>
<a href="mailto:yamada@yojitu.com" class="cta-info-value" target="_blank" rel="noopener noreferrer">yamada@yojitu.com<span class="external-icon"> <i class="fas fa-external-link-alt" style="font-size: 0.8em; margin-left: 4px; opacity: 0.6;"></i></span></a>
                    </div>
                </div>
                <div class="cta-info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <span class="cta-info-label">営業時間</span>
                        <span class="cta-info-value">10時~22時（定休日なし）</span>
                    </div>
                </div>
            </div>
                    </div>
    </div>
</section>
