<!-- Services Overview Section -->
<section class="section services" data-section-number="01">
    <div class="container">
        <h2 class="section__title">サービス紹介</h2>
        <p class="section__lead">
            お車のことなら何でもお任せください
        </p>

        <div class="services-grid">
            <?php
            $services = get_all_services();
            foreach ($services as $service):
            ?>
            <div class="service-card-compact" data-service-id="<?php echo h($service['id']); ?>">
                <div class="service-card-compact__icon" style="background: <?php echo h($service['color']); ?>20; color: <?php echo h($service['color']); ?>;">
                    <i class="<?php echo h($service['icon']); ?>"></i>
                </div>
                <h3 class="service-card-compact__title"><?php echo h($service['name']); ?></h3>
                <p class="service-card-compact__summary"><?php echo h($service['description']); ?></p>
                <button class="service-card-compact__btn" onclick="openServiceModal('<?php echo h($service['id']); ?>')">
                    詳しく見る
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Service Modal -->
<div class="service-modal" id="service-modal">
    <div class="service-modal__overlay" onclick="closeServiceModal()"></div>
    <div class="service-modal__content">
        <button class="service-modal__close" onclick="closeServiceModal()">
            <i class="fa-solid fa-times"></i>
        </button>

        <div class="service-modal__body">
            <?php foreach ($services as $service): ?>
            <div class="service-modal__item" id="modal-<?php echo h($service['id']); ?>">
                <div class="service-modal__header">
                    <div class="service-modal__icon" style="background: <?php echo h($service['color']); ?>20; color: <?php echo h($service['color']); ?>;">
                        <i class="<?php echo h($service['icon']); ?>"></i>
                    </div>
                    <h3 class="service-modal__title"><?php echo h($service['name']); ?></h3>
                </div>

                <p class="service-modal__description">
                    <?php echo h($service['description']); ?>
                </p>

                <?php if (!empty($service['features'])): ?>
                <div class="service-modal__features">
                    <h4>特徴</h4>
                    <ul>
                        <?php foreach ($service['features'] as $feature): ?>
                        <li><?php echo h($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <div class="service-modal__cta">
                    <a href="<?php echo url('contact'); ?>?service=<?php echo h($service['id']); ?>" class="btn btn--primary">
                        <i class="fa-solid fa-envelope"></i>
                        このサービスについて問い合わせる
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
