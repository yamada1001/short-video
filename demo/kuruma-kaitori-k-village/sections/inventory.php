<!-- Inventory Section -->
<section class="section inventory-section" data-section-number="05">
    <div class="container">
        <h2 class="section__title">在庫車両</h2>
        <p class="section__lead">
            厳選した中古車を取り揃えております
        </p>

        <!-- Swiper -->
        <div class="swiper inventory-swiper">
            <div class="swiper-wrapper">
                <?php
                $inventory_items = get_inventory(4);
                foreach ($inventory_items as $item):
                ?>
                <div class="swiper-slide">
                    <article class="inventory-card">
                        <div class="inventory-card__image">
                            <img src="<?php echo h($item['image']); ?>" alt="<?php echo h($item['name']); ?>">
                            <span class="inventory-card__status"><?php echo h($item['status']); ?></span>
                        </div>

                        <div class="inventory-card__content">
                            <h3 class="inventory-card__name"><?php echo h($item['name']); ?></h3>

                            <div class="inventory-card__price">
                                <span class="inventory-card__price-label">支払総額</span>
                                <span class="inventory-card__price-value"><?php echo format_price($item['price_total']); ?></span>
                            </div>

                            <div class="inventory-card__specs">
                                <div class="inventory-card__spec">
                                    <span class="label">年式</span>
                                    <span class="value"><?php echo h($item['year_jp']); ?></span>
                                </div>
                                <div class="inventory-card__spec">
                                    <span class="label">走行</span>
                                    <span class="value"><?php echo h($item['mileage']); ?>万km</span>
                                </div>
                                <div class="inventory-card__spec">
                                    <span class="label">車検</span>
                                    <span class="value"><?php echo h($item['inspection']); ?></span>
                                </div>
                            </div>

                            <a href="<?php echo url('contact'); ?>?vehicle_id=<?php echo $item['id']; ?>" class="btn btn--primary btn--block btn--small">
                                <i class="fa-solid fa-envelope"></i>
                                お問い合わせ
                            </a>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- ナビゲーション -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

            <!-- ページネーション -->
            <div class="swiper-pagination"></div>
        </div>

        <div class="text-center mt-lg">
            <a href="<?php echo url('inventory'); ?>" class="btn btn--outline">
                <i class="fa-solid fa-car"></i>
                在庫一覧を見る
            </a>
        </div>
    </div>
</section>
