<!-- Hero Section -->
<section class="hero">
    <div class="hero__background">
        <img src="https://lh3.googleusercontent.com/gps-cs-s/AG0ilSwfJZeJbu5T37bt6g5WaOeuar19YAoFXV3SZU_eY9POZ3g4r5WiA4VFoVfdg_4luqLbtd4LAiwPdi2COmKsJeujocyo2-fp6_rglWjZf3j3n9U-fuTt5BiufPBc5t3mm6cupxqMljma65EJ=s680-w680-h510-rw" alt="くるま買取ケイヴィレッジ 店舗外観" loading="eager" class="hero__background-image">
        <div class="hero__overlay"></div>
    </div>

    <div class="hero__container container">
        <div class="hero__content">
            <div class="hero__badge">
                <i class="fa-solid fa-award"></i>
                大分市で30年以上の実績
            </div>

            <h1 class="hero__title">
                <span class="hero__title-main">愛車を高く売るなら</span>
                <span class="hero__title-highlight">ケイヴィレッジ</span>
            </h1>

            <p class="hero__lead">
                <strong class="hero__lead-strong">90秒</strong>でカンタン無料査定<br>
                買取・販売・車検・整備まで、すべてお任せください
            </p>

            <div class="hero__cta">
                <a href="<?php echo url('kaitori'); ?>" class="btn btn--primary btn--large btn--hero">
                    <i class="fa-solid fa-dollar-sign"></i>
                    <span>
                        <span class="btn--hero__label">無料査定はこちら</span>
                        <span class="btn--hero__sub">90秒で完了</span>
                    </span>
                </a>
                <a href="tel:<?php echo PHONE_NUMBER; ?>" class="btn btn--secondary btn--large btn--hero">
                    <i class="fa-solid fa-phone"></i>
                    <span>
                        <span class="btn--hero__label"><?php echo format_phone(PHONE_NUMBER); ?></span>
                        <span class="btn--hero__sub">受付時間: <?php echo BUSINESS_HOURS; ?></span>
                    </span>
                </a>
            </div>

            <div class="hero__features">
                <div class="hero__feature">
                    <div class="hero__feature-icon">
                        <i class="fa-solid fa-yen-sign"></i>
                    </div>
                    <div class="hero__feature-text">
                        <strong>高価買取</strong>
                        <span>他社に負けない査定額</span>
                    </div>
                </div>
                <div class="hero__feature">
                    <div class="hero__feature-icon">
                        <i class="fa-solid fa-stopwatch"></i>
                    </div>
                    <div class="hero__feature-text">
                        <strong>即日対応</strong>
                        <span>最短当日でお支払い</span>
                    </div>
                </div>
                <div class="hero__feature">
                    <div class="hero__feature-icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <div class="hero__feature-text">
                        <strong>出張査定</strong>
                        <span>ご自宅まで無料でお伺い</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero__wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path d="M0,64 C240,96 480,96 720,64 C960,32 1200,32 1440,64 L1440,120 L0,120 Z" fill="white"/>
        </svg>
    </div>
</section>
