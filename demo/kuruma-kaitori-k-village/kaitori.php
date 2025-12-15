<?php
/**
 * Kaitori Page (車買取ページ)
 * くるま買取ケイヴィレッジ
 */

// 設定読み込み
require_once __DIR__ . '/data/config.php';
require_once __DIR__ . '/data/meta.php';
require_once __DIR__ . '/data/kaitori-data.php';
require_once __DIR__ . '/includes/functions.php';

// ヘッダー読み込み
require_once __DIR__ . '/includes/header.php';
?>

<!-- Kaitori Hero Section -->
<section class="kaitori-hero">
    <div class="kaitori-hero__background-pattern"></div>
    <div class="container kaitori-hero__container">
        <div class="kaitori-hero__content">
            <div class="kaitori-hero__badge">
                <i class="fa-solid fa-fire"></i>
                高価買取実施中
            </div>

            <h1 class="kaitori-hero__title">
                <span class="kaitori-hero__title-main">愛車を最高額で売却</span>
                <span class="kaitori-hero__title-sub">車買取サービス</span>
            </h1>

            <p class="kaitori-hero__lead">
                <strong class="kaitori-hero__lead-strong">35秒</strong>でカンタン入力<br>
                無料査定・出張査定対応・即日現金買取可能
            </p>

            <div class="kaitori-hero__stats">
                <div class="kaitori-hero__stat">
                    <div class="kaitori-hero__stat-number">30年+</div>
                    <div class="kaitori-hero__stat-label">買取実績</div>
                </div>
                <div class="kaitori-hero__stat">
                    <div class="kaitori-hero__stat-number">98%</div>
                    <div class="kaitori-hero__stat-label">顧客満足度</div>
                </div>
                <div class="kaitori-hero__stat">
                    <div class="kaitori-hero__stat-number">即日</div>
                    <div class="kaitori-hero__stat-label">現金買取</div>
                </div>
            </div>

            <div class="kaitori-hero__cta">
                <a href="<?php echo url('contact'); ?>" class="btn btn--primary btn--large">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>35秒で無料査定を申し込む</span>
                </a>
                <a href="tel:<?php echo PHONE_LINK; ?>" class="btn btn--outline-white btn--large">
                    <i class="fa-solid fa-phone"></i>
                    <span><?php echo PHONE; ?></span>
                </a>
            </div>

            <div class="kaitori-hero__features">
                <div class="kaitori-hero__feature">
                    <div class="kaitori-hero__feature-icon">
                        <i class="fa-solid fa-yen-sign"></i>
                    </div>
                    <div class="kaitori-hero__feature-text">
                        <strong>他社より高価買取</strong>
                        <span>最高額をご提示</span>
                    </div>
                </div>
                <div class="kaitori-hero__feature">
                    <div class="kaitori-hero__feature-icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <div class="kaitori-hero__feature-text">
                        <strong>出張査定無料</strong>
                        <span>ご自宅まで伺います</span>
                    </div>
                </div>
                <div class="kaitori-hero__feature">
                    <div class="kaitori-hero__feature-icon">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                    <div class="kaitori-hero__feature-text">
                        <strong>即日現金買取</strong>
                        <span>最短当日お支払い</span>
                    </div>
                </div>
                <div class="kaitori-hero__feature">
                    <div class="kaitori-hero__feature-icon">
                        <i class="fa-solid fa-file-contract"></i>
                    </div>
                    <div class="kaitori-hero__feature-text">
                        <strong>手続き簡単</strong>
                        <span>面倒な書類もサポート</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="kaitori-hero__wave">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path d="M0,64 C240,96 480,96 720,64 C960,32 1200,32 1440,64 L1440,120 L0,120 Z" fill="#f3f4f6"/>
        </svg>
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
                <span class="breadcrumb__current">車買取サービス</span>
            </li>
        </ul>
    </div>
</div>

<!-- Kaitori Strengths Section -->
<section class="section kaitori-strengths">
    <div class="container">
        <h2 class="section__title">高価買取のポイント</h2>
        <p class="section__lead">
            ケイヴィレッジが選ばれる理由
        </p>

        <div class="kaitori-strengths__grid">
            <?php foreach ($kaitori_strengths as $strength): ?>
            <div class="kaitori-strengths__item">
                <div class="kaitori-strengths__icon" style="background-color: <?php echo h($strength['color']); ?>;">
                    <i class="<?php echo h($strength['icon']); ?>"></i>
                </div>
                <h3 class="kaitori-strengths__title"><?php echo h($strength['title']); ?></h3>
                <p class="kaitori-strengths__description"><?php echo h($strength['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Kaitori Process Section -->
<section class="kaitori-process">
    <div class="container">
        <h2 class="section__title">買取の流れ</h2>
        <p class="section__lead">
            簡単5ステップで査定から買取まで
        </p>

        <div class="kaitori-process__steps">
            <?php foreach ($kaitori_process as $process): ?>
            <div class="kaitori-process__step">
                <div class="kaitori-process__step-number"><?php echo h($process['step']); ?></div>
                <div class="kaitori-process__step-icon">
                    <i class="<?php echo h($process['icon']); ?>"></i>
                </div>
                <h3 class="kaitori-process__step-title"><?php echo h($process['title']); ?></h3>
                <p class="kaitori-process__step-description"><?php echo h($process['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Kaitori Results Section -->
<section class="section kaitori-results">
    <div class="container">
        <h2 class="section__title">買取実績</h2>
        <p class="section__lead">
            最近の買取事例をご紹介
        </p>

        <div class="kaitori-results__grid">
            <?php foreach ($kaitori_results as $result): ?>
            <div class="kaitori-results__item">
                <div class="kaitori-results__car">
                    <?php echo h($result['maker']); ?> <?php echo h($result['model']); ?>
                </div>
                <div class="kaitori-results__details">
                    <span class="kaitori-results__detail"><?php echo h($result['year']); ?></span>
                    <span class="kaitori-results__detail"><?php echo h($result['mileage']); ?></span>
                </div>
                <div class="kaitori-results__price">
                    <?php echo h($result['price']); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Kaitori FAQ Section -->
<section class="kaitori-faq">
    <div class="container">
        <h2 class="section__title">よくある質問</h2>
        <p class="section__lead">
            車買取に関するご質問
        </p>

        <div class="kaitori-faq__list">
            <?php foreach ($kaitori_faq as $faq): ?>
            <div class="kaitori-faq__item">
                <div class="kaitori-faq__question">
                    <span class="kaitori-faq__q-icon">Q</span>
                    <span><?php echo h($faq['question']); ?></span>
                </div>
                <div class="kaitori-faq__answer">
                    <span class="kaitori-faq__a-icon">A</span>
                    <?php echo h($faq['answer']); ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Kaitori CTA Section -->
<section class="kaitori-cta">
    <div class="container">
        <h2 class="kaitori-cta__title">今すぐ無料査定を申し込む</h2>
        <p class="kaitori-cta__description">
            お電話またはWebフォームからお気軽にお問い合わせください
        </p>
        <div class="kaitori-cta__buttons">
            <a href="<?php echo url('contact'); ?>" class="btn btn--secondary btn--lg">
                <i class="fa-solid fa-envelope"></i>
                Webフォームで申し込む
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
