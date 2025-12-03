<!-- Services Overview Section -->
<section class="section services" data-section-number="01">
    <div class="container">
        <h2 class="section__title">サービス紹介</h2>
        <p class="section__lead">
            お車のことなら何でもお任せください
        </p>

        <!-- タブナビゲーション -->
        <div class="service-tabs">
            <div class="service-tabs__nav">
                <?php
                $services = get_all_services();
                $first = true;
                foreach ($services as $service):
                ?>
                <button
                    class="service-tabs__tab <?php echo $first ? 'active' : ''; ?>"
                    data-tab="<?php echo h($service['id']); ?>"
                    onclick="switchServiceTab('<?php echo h($service['id']); ?>')"
                >
                    <i class="<?php echo h($service['icon']); ?>"></i>
                    <span class="service-tabs__tab-name"><?php echo h($service['name']); ?></span>
                    <span class="service-tabs__tab-short"><?php echo h($service['short_name']); ?></span>
                </button>
                <?php
                $first = false;
                endforeach;
                ?>
            </div>

            <!-- タブコンテンツ -->
            <div class="service-tabs__content">
                <?php
                $first = true;
                foreach ($services as $service):
                ?>
                <div class="service-tabs__panel <?php echo $first ? 'active' : ''; ?>" id="tab-<?php echo h($service['id']); ?>">
                    <div class="service-detail">
                        <div class="service-detail__header">
                            <div class="service-detail__icon" style="background: <?php echo h($service['color']); ?>20; color: <?php echo h($service['color']); ?>;">
                                <i class="<?php echo h($service['icon']); ?>"></i>
                            </div>
                            <div>
                                <h3 class="service-detail__title"><?php echo h($service['name']); ?></h3>
                                <p class="service-detail__description"><?php echo h($service['description']); ?></p>
                            </div>
                        </div>

                        <?php if (!empty($service['features'])): ?>
                        <div class="service-detail__features">
                            <h4>特徴</h4>
                            <ul>
                                <?php foreach ($service['features'] as $feature): ?>
                                <li><?php echo h($feature); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <div class="service-detail__cta">
                            <a href="<?php echo url('contact'); ?>?service=<?php echo h($service['id']); ?>" class="btn btn--primary">
                                <i class="fa-solid fa-envelope"></i>
                                このサービスについて問い合わせる
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                $first = false;
                endforeach;
                ?>
            </div>
        </div>
    </div>
</section>
