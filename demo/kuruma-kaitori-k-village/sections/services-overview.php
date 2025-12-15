<!-- Services Overview Section -->
<section class="section services" data-section-number="01">
    <div class="services__background"></div>
    <div class="container">
        <div class="section__header">
            <h2 class="section__title">
                <span class="section__title-en">SERVICES</span>
                サービス紹介
            </h2>
            <p class="section__lead">
                お車のことなら何でもお任せください
            </p>
        </div>

        <div class="services__grid">
            <?php
            $services = get_all_services();
            foreach ($services as $service):
            ?>
            <div class="service-card" style="--service-color: <?php echo h($service['color']); ?>">
                <div class="service-card__icon-wrapper">
                    <div class="service-card__icon">
                        <i class="<?php echo h($service['icon']); ?>"></i>
                    </div>
                    <?php if ($service['id'] === 'kaitori'): ?>
                    <div class="service-card__badge">人気No.1</div>
                    <?php endif; ?>
                </div>

                <h3 class="service-card__title"><?php echo h($service['name']); ?></h3>
                <p class="service-card__description"><?php echo h($service['description']); ?></p>

                <?php if (!empty($service['features'])): ?>
                <ul class="service-card__features">
                    <?php
                    $count = 0;
                    foreach ($service['features'] as $feature):
                        if ($count >= 3) break;
                    ?>
                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        <?php echo h($feature); ?>
                    </li>
                    <?php
                        $count++;
                    endforeach;
                    ?>
                </ul>
                <?php endif; ?>

                <div class="service-card__cta">
                    <?php if ($service['id'] === 'kaitori'): ?>
                    <a href="<?php echo url('kaitori'); ?>" class="btn btn--primary btn--block">
                        <i class="fa-solid fa-arrow-right"></i>
                        詳しく見る
                    </a>
                    <?php elseif ($service['id'] === 'lease'): ?>
                    <a href="<?php echo url('lease'); ?>" class="btn btn--primary btn--block">
                        <i class="fa-solid fa-arrow-right"></i>
                        詳しく見る
                    </a>
                    <?php else: ?>
                    <a href="<?php echo url('contact'); ?>?service=<?php echo h($service['id']); ?>" class="btn btn--outline btn--block">
                        <i class="fa-solid fa-envelope"></i>
                        問い合わせる
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
