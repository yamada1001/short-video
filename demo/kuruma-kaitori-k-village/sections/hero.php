<!-- Hero Section -->
<section class="hero">
    <div class="hero__container container">
        <div class="hero__content">
            <h1 class="hero__title">
                大分で車のことなら、<br>
                <span class="hero__title-highlight">ケイヴィレッジへ</span>
            </h1>

            <p class="hero__lead">
                買取・販売・車検・整備まで、<br class="sp-only">
                すべてお任せください
            </p>

            <div class="hero__features">
                <div class="hero__feature">
                    <i class="fa-solid fa-check-circle"></i>
                    高価買取実施中
                </div>
                <div class="hero__feature">
                    <i class="fa-solid fa-check-circle"></i>
                    無料査定対応
                </div>
                <div class="hero__feature">
                    <i class="fa-solid fa-check-circle"></i>
                    出張査定OK
                </div>
            </div>

            <div class="hero__cta">
                <a href="<?php echo url('contact'); ?>" class="btn btn--primary btn--large">
                    <i class="fa-solid fa-envelope"></i>
                    無料査定を申し込む
                </a>
                <a href="tel:<?php echo format_phone(PHONE, 'tel'); ?>" class="btn btn--secondary btn--large">
                    <i class="fa-solid fa-phone"></i>
                    電話で相談する
                </a>
            </div>
        </div>

        <div class="hero__image">
            <img src="<?php echo asset('assets/images/hero/car-main.jpg'); ?>" alt="車のイメージ" loading="eager">
        </div>
    </div>
</section>
