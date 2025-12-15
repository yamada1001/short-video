<?php
/**
 * Lease Page (新車リースページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/data/lease-data.php';
require_once __DIR__ . '/includes/functions.php';

// ヘッダー読み込み
require_once __DIR__ . '/includes/header.php';
?>

<!-- Lease Hero Section -->
<section class="lease-hero">
    <div class="container lease-hero__container">
        <h1 class="lease-hero__title">新車リースサービス</h1>
        <p class="lease-hero__lead">
            頭金不要、月々定額で新車に乗れる。<br>
            車検・メンテナンス込みで安心のカーリース
        </p>

        <div class="lease-hero__features">
            <div class="lease-hero__feature">
                <i class="fa-solid fa-circle-dollar-to-slot"></i>
                <div class="lease-hero__feature-title">頭金0円</div>
            </div>
            <div class="lease-hero__feature">
                <i class="fa-solid fa-calendar-days"></i>
                <div class="lease-hero__feature-title">月々定額</div>
            </div>
            <div class="lease-hero__feature">
                <i class="fa-solid fa-wrench"></i>
                <div class="lease-hero__feature-title">メンテナンス込み</div>
            </div>
            <div class="lease-hero__feature">
                <i class="fa-solid fa-cars"></i>
                <div class="lease-hero__feature-title">全メーカー対応</div>
            </div>
        </div>

        <div style="display: flex; gap: var(--spacing-md); flex-wrap: wrap;">
            <a href="<?php echo url('contact'); ?>" class="btn btn--secondary btn--lg">
                <i class="fa-solid fa-file-contract"></i>
                リースのご相談
            </a>
            <a href="tel:<?php echo format_phone(PHONE, 'tel'); ?>" class="btn btn--outline btn--lg" style="color: white; border-color: white;">
                <i class="fa-solid fa-phone"></i>
                <?php echo format_phone(PHONE); ?>
            </a>
        </div>
    </div>
</section>

<!-- Breadcrumb -->
<div class="breadcrumb">
    <div class="container">
        <ul class="breadcrumb__list">
            <li class="breadcrumb__item">
                <a href="<?php echo url(); ?>" class="breadcrumb__link">
                    <i class="fa-solid fa-house"></i>
                    トップ
                </a>
            </li>
            <li class="breadcrumb__item">
                <span class="breadcrumb__current">新車リースサービス</span>
            </li>
        </ul>
    </div>
</div>

<!-- Lease Benefits Section -->
<section class="section lease-benefits">
    <div class="container">
        <h2 class="section__title">リースのメリット</h2>
        <p class="section__lead">
            新車リースが選ばれる理由
        </p>

        <div class="lease-benefits__grid">
            <?php foreach ($lease_benefits as $benefit): ?>
            <div class="lease-benefits__item">
                <div class="lease-benefits__icon" style="background-color: <?php echo h($benefit['color']); ?>;">
                    <i class="<?php echo h($benefit['icon']); ?>"></i>
                </div>
                <h3 class="lease-benefits__title"><?php echo h($benefit['title']); ?></h3>
                <p class="lease-benefits__description"><?php echo h($benefit['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Lease Plans Section -->
<section class="lease-plans">
    <div class="container">
        <h2 class="section__title">リースプラン</h2>
        <p class="section__lead">
            お客様のライフスタイルに合わせたプラン
        </p>

        <div class="lease-plans__grid">
            <?php foreach ($lease_plans as $plan): ?>
            <div class="lease-plans__item <?php echo isset($plan['badge']) ? 'lease-plans__item--popular' : ''; ?>">
                <?php if (isset($plan['badge'])): ?>
                <div class="lease-plans__badge"><?php echo h($plan['badge']); ?></div>
                <?php endif; ?>

                <h3 class="lease-plans__name"><?php echo h($plan['name']); ?></h3>
                <p class="lease-plans__period">契約期間：<?php echo h($plan['period']); ?></p>
                <div class="lease-plans__price"><?php echo h($plan['price_from']); ?></div>
                <p class="lease-plans__recommend"><?php echo h($plan['recommend']); ?></p>

                <ul class="lease-plans__features">
                    <?php foreach ($plan['features'] as $feature): ?>
                    <li class="lease-plans__feature"><?php echo h($feature); ?></li>
                    <?php endforeach; ?>
                </ul>

                <a href="<?php echo url('contact'); ?>" class="btn btn--primary lease-plans__cta">
                    <i class="fa-solid fa-envelope"></i>
                    このプランで相談する
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Lease Vehicles Section -->
<section class="section lease-vehicles">
    <div class="container">
        <h2 class="section__title">対象車種</h2>
        <p class="section__lead">
            国産車全メーカーの新車からお選びいただけます
        </p>

        <div class="lease-vehicles__grid">
            <?php foreach ($lease_vehicles as $category): ?>
            <div class="lease-vehicles__category">
                <h3 class="lease-vehicles__category-title">
                    <i class="fa-solid fa-car"></i>
                    <?php echo h($category['category']); ?>
                </h3>
                <ul class="lease-vehicles__list">
                    <?php foreach ($category['models'] as $model): ?>
                    <li class="lease-vehicles__item"><?php echo h($model); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-lg">
            <p style="color: var(--text-secondary);">
                ※掲載されていない車種もお取り扱い可能です。お気軽にご相談ください。
            </p>
        </div>
    </div>
</section>

<!-- Lease Process Section -->
<section class="lease-process">
    <div class="container">
        <h2 class="section__title">リースの流れ</h2>
        <p class="section__lead">
            ご相談から納車まで5ステップ
        </p>

        <div class="lease-process__steps">
            <?php foreach ($lease_process as $process): ?>
            <div class="lease-process__step">
                <div class="lease-process__step-number"><?php echo h($process['step']); ?></div>
                <div class="lease-process__step-icon">
                    <i class="<?php echo h($process['icon']); ?>"></i>
                </div>
                <h3 class="lease-process__step-title"><?php echo h($process['title']); ?></h3>
                <p class="lease-process__step-description"><?php echo h($process['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Lease FAQ Section -->
<section class="lease-faq">
    <div class="container">
        <h2 class="section__title">よくある質問</h2>
        <p class="section__lead">
            新車リースに関するご質問
        </p>

        <div class="lease-faq__list">
            <?php foreach ($lease_faq as $faq): ?>
            <div class="lease-faq__item">
                <div class="lease-faq__question">
                    <span class="lease-faq__q-icon">Q</span>
                    <span><?php echo h($faq['question']); ?></span>
                </div>
                <div class="lease-faq__answer">
                    <span class="lease-faq__a-icon">A</span>
                    <?php echo h($faq['answer']); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Lease CTA Section -->
<section class="lease-cta">
    <div class="container">
        <h2 class="lease-cta__title">新車リースのご相談はこちら</h2>
        <p class="lease-cta__description">
            お客様に最適なプランをご提案いたします
        </p>
        <div class="lease-cta__buttons">
            <a href="<?php echo url('contact'); ?>" class="btn btn--secondary btn--lg">
                <i class="fa-solid fa-envelope"></i>
                Webフォームで相談する
            </a>
            <a href="tel:<?php echo format_phone(PHONE, 'tel'); ?>" class="btn btn--outline btn--lg" style="color: white; border-color: white;">
                <i class="fa-solid fa-phone"></i>
                電話で問い合わせる
            </a>
        </div>
    </div>
</section>

<?php
// フッター読み込み
require_once __DIR__ . '/includes/footer.php';
?>
