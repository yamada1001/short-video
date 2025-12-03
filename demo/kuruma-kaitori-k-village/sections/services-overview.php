<!-- Services Overview Section -->
<section class="section services">
    <div class="container">
        <h2 class="section__title">サービス紹介</h2>
        <p class="section__lead">
            お車のことなら何でもお任せください。<br>
            買取から販売、車検、整備まで幅広く対応しております。
        </p>

        <div class="grid grid--3">
            <?php
            $services = get_all_services();
            foreach ($services as $service):
            ?>
            <div class="card service-card">
                <div class="card__icon" style="background: <?php echo h($service['color']); ?>20; color: <?php echo h($service['color']); ?>;">
                    <i class="<?php echo h($service['icon']); ?>"></i>
                </div>

                <h3 class="card__title"><?php echo h($service['name']); ?></h3>

                <p class="card__description">
                    <?php echo h($service['description']); ?>
                </p>

                <?php if (!empty($service['features'])): ?>
                <ul class="card__list">
                    <?php foreach ($service['features'] as $feature): ?>
                    <li class="card__list-item"><?php echo h($feature); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <a href="<?php echo url('contact'); ?>?service=<?php echo h($service['id']); ?>" class="btn btn--outline btn--small btn--block">
                    <i class="fa-solid fa-arrow-right"></i>
                    お問い合わせ
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
