<!-- Company Info Section -->
<section class="section company-info" data-section-number="04">
    <div class="container">
        <h2 class="section__title">会社情報・アクセス</h2>

        <div class="company-info__content">
            <!-- 会社情報 -->
            <div class="company-info__details">
                <dl class="company-info__list">
                    <div class="company-info__item">
                        <dt class="company-info__term">
                            <i class="fa-solid fa-building"></i>
                            屋号
                        </dt>
                        <dd class="company-info__description"><?php echo COMPANY_NAME; ?></dd>
                    </div>

                    <div class="company-info__item">
                        <dt class="company-info__term">
                            <i class="fa-solid fa-user"></i>
                            責任者
                        </dt>
                        <dd class="company-info__description"><?php echo COMPANY_OWNER; ?></dd>
                    </div>

                    <div class="company-info__item">
                        <dt class="company-info__term">
                            <i class="fa-solid fa-location-dot"></i>
                            所在地
                        </dt>
                        <dd class="company-info__description">
                            <?php echo POSTAL_CODE; ?><br>
                            <?php echo ADDRESS; ?>
                        </dd>
                    </div>

                    <div class="company-info__item">
                        <dt class="company-info__term">
                            <i class="fa-solid fa-phone"></i>
                            電話番号
                        </dt>
                        <dd class="company-info__description">
                            <a href="tel:<?php echo format_phone(PHONE, 'tel'); ?>"><?php echo PHONE; ?></a>
                        </dd>
                    </div>

                    <div class="company-info__item">
                        <dt class="company-info__term">
                            <i class="fa-solid fa-clock"></i>
                            営業時間
                        </dt>
                        <dd class="company-info__description">
                            <?php echo BUSINESS_HOURS; ?><br>
                            <?php echo BUSINESS_DAYS; ?><br>
                            <span class="company-info__holiday">定休日：<?php echo HOLIDAY; ?></span>
                        </dd>
                    </div>

                    <div class="company-info__item">
                        <dt class="company-info__term">
                            <i class="fa-solid fa-id-card"></i>
                            <?php echo LICENSE_NAME; ?>
                        </dt>
                        <dd class="company-info__description"><?php echo LICENSE_NUMBER; ?></dd>
                    </div>
                </dl>

                <div class="company-info__actions">
                    <a href="<?php echo url('about'); ?>" class="btn btn--outline">
                        <i class="fa-solid fa-arrow-right"></i>
                        会社概要を詳しく見る
                    </a>
                </div>
            </div>

            <!-- 地図 -->
            <div class="company-info__map">
                <iframe
                    src="<?php echo GOOGLE_MAP_EMBED; ?>"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</section>
