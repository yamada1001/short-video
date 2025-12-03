<!-- Inventory Section -->
<section class="section inventory-section" data-section-number="05">
    <div class="container">
        <h2 class="section__title">在庫車両</h2>
        <p class="section__lead">
            厳選した中古車を取り揃えております
        </p>

        <div class="inventory-grid">
            <?php
            $inventory_items = get_inventory(4);
            foreach ($inventory_items as $item):
            ?>
            <article class="inventory-item">
                <div class="inventory-item__image">
                    <img src="<?php echo h($item['image']); ?>" alt="<?php echo h($item['name']); ?>">
                    <span class="inventory-item__status"><?php echo h($item['status']); ?></span>
                </div>

                <div class="inventory-item__content">
                    <h3 class="inventory-item__name"><?php echo h($item['name']); ?></h3>

                    <div class="inventory-item__price">
                        <div class="inventory-item__price-main">
                            <span class="inventory-item__price-label">支払総額（税込）</span>
                            <span class="inventory-item__price-value"><?php echo format_price($item['price_total']); ?></span>
                        </div>
                        <div class="inventory-item__price-sub">
                            （諸費用<?php echo format_price($item['price_fees']); ?>含む）
                        </div>
                        <div class="inventory-item__price-vehicle">
                            <span class="inventory-item__price-vehicle-label">車両本体価格（税込）</span>
                            <span class="inventory-item__price-vehicle-value"><?php echo format_price($item['price_vehicle']); ?></span>
                        </div>
                    </div>

                    <dl class="inventory-item__specs">
                        <div class="inventory-item__spec">
                            <dt>年式</dt>
                            <dd><?php echo h($item['year']); ?> (<?php echo h($item['year_jp']); ?>)</dd>
                        </div>
                        <div class="inventory-item__spec">
                            <dt>走行距離</dt>
                            <dd><?php echo h($item['mileage']); ?>万km</dd>
                        </div>
                        <div class="inventory-item__spec">
                            <dt>修復歴</dt>
                            <dd><?php echo $item['repair_history'] ? 'あり' : 'なし'; ?></dd>
                        </div>
                        <div class="inventory-item__spec">
                            <dt>法定整備</dt>
                            <dd><?php echo h($item['maintenance']); ?></dd>
                        </div>
                        <div class="inventory-item__spec">
                            <dt>車検有無</dt>
                            <dd><?php echo h($item['inspection']); ?></dd>
                        </div>
                        <div class="inventory-item__spec">
                            <dt>地域</dt>
                            <dd><?php echo h($item['location']); ?></dd>
                        </div>
                        <div class="inventory-item__spec">
                            <dt>保証</dt>
                            <dd><?php echo h($item['warranty']); ?></dd>
                        </div>
                    </dl>

                    <div class="inventory-item__actions">
                        <a href="<?php echo url('contact'); ?>?vehicle_id=<?php echo $item['id']; ?>" class="btn btn--primary">
                            <i class="fa-solid fa-envelope"></i>
                            お問い合わせ
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-lg">
            <a href="<?php echo url('inventory'); ?>" class="btn btn--outline">
                <i class="fa-solid fa-car"></i>
                在庫一覧を見る
            </a>
        </div>
    </div>
</section>
