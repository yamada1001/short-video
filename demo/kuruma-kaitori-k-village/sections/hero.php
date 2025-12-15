<!-- Hero Section -->
<section class="hero">
    <div class="hero__background">
        <img src="https://lh3.googleusercontent.com/gps-cs-s/AG0ilSwfJZeJbu5T37bt6g5WaOeuar19YAoFXV3SZU_eY9POZ3g4r5WiA4VFoVfdg_4luqLbtd4LAiwPdi2COmKsJeujocyo2-fp6_rglWjZf3j3n9U-fuTt5BiufPBc5t3mm6cupxqMljma65EJ=s680-w680-h510-rw" alt="くるま買取ケイヴィレッジ 店舗外観" loading="eager" class="hero__background-image">
        <div class="hero__overlay"></div>
    </div>

    <div class="hero__container container">
        <div class="hero__content">
            <div class="hero__left">
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
                    <a href="tel:<?php echo PHONE_LINK; ?>" class="btn btn--secondary btn--large btn--hero">
                        <i class="fa-solid fa-phone"></i>
                        <span>
                            <span class="btn--hero__label"><?php echo PHONE; ?></span>
                            <span class="btn--hero__sub">受付時間: <?php echo BUSINESS_HOURS; ?></span>
                        </span>
                    </a>
                </div>

                <!-- 実績数値カード -->
                <div class="hero__stats">
                    <div class="hero__stat-card">
                        <div class="hero__stat-number">30年+</div>
                        <div class="hero__stat-label">買取実績</div>
                    </div>
                    <div class="hero__stat-card">
                        <div class="hero__stat-number">98%</div>
                        <div class="hero__stat-label">顧客満足度</div>
                    </div>
                    <div class="hero__stat-card">
                        <div class="hero__stat-number">即日</div>
                        <div class="hero__stat-label">現金買取</div>
                    </div>
                </div>
            </div>

            <!-- YouTube動画 -->
            <div class="hero__right">
                <div class="hero__video">
                    <div class="hero__video-wrapper">
                        <iframe
                            src="https://www.youtube.com/embed/kCmPeHw6xAU?si=VIlE1_Mqx6KsR817"
                            title="くるま買取ケイヴィレッジ 店舗紹介"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    </div>
                    <div class="hero__video-badge">
                        <i class="fa-solid fa-play-circle"></i>
                        店舗紹介動画
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
