<?php
$current_page = 'contact';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'お問い合わせ | 余日（Yojitsu）';
$page_description = '余日（Yojitsu）へのお問い合わせはこちら。LINE、電話、メールフォームで受け付けております。';
$additional_css = [
    'assets/css/pages/contact.css',
    'assets/css/pages/contact-step.css',
    'assets/css/cookie-consent.css'
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- お問い合わせフォーム（ステップ形式） -->
    <section class="contact-step-form">
        <div class="container contact-step-form__container">
            <!-- プログレスバー -->
            <div class="contact-step-progress">
                <div class="contact-step-progress__bar">
                    <div class="contact-step-progress__fill" id="progressFill"></div>
                </div>
                <div class="contact-step-progress__labels">
                    <span class="contact-step-progress__label active" data-step="1">
                        <span class="contact-step-progress__num">1</span>
                        <span class="contact-step-progress__text">種別選択</span>
                    </span>
                    <span class="contact-step-progress__label" data-step="2">
                        <span class="contact-step-progress__num">2</span>
                        <span class="contact-step-progress__text">入力</span>
                    </span>
                    <span class="contact-step-progress__label" data-step="3">
                        <span class="contact-step-progress__num">3</span>
                        <span class="contact-step-progress__text">完了</span>
                    </span>
                </div>
            </div>

            <!-- Step 1: 問い合わせ種別選択 -->
            <div class="contact-step" id="step1" data-step="1">
                <h2 class="contact-step__title">お問い合わせ種別を選択してください</h2>
                <p class="contact-step__description">該当する項目をお選びください</p>

                <div class="contact-type-select-wrapper">
                    <div class="contact-type-select-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <select id="contactTypeSelect" class="contact-type-select">
                        <option value="">選択してください</option>
                        <option value="seo" data-icon="fa-search">SEO対策について</option>
                        <option value="ad" data-icon="fa-bullhorn">広告運用について</option>
                        <option value="web" data-icon="fa-laptop-code">Web制作について</option>
                        <option value="video" data-icon="fa-video">ショート動画制作について</option>
                        <option value="freelance" data-icon="fa-user-tie">業務委託について</option>
                        <option value="partnership" data-icon="fa-handshake">協業について</option>
                        <option value="quote" data-icon="fa-file-invoice-dollar">見積もり依頼</option>
                        <option value="sales" data-icon="fa-briefcase">営業のご連絡</option>
                        <option value="other" data-icon="fa-envelope">その他</option>
                    </select>
                    <button type="button" class="contact-type-next-btn" id="nextButton" disabled>
                        次へ進む
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>

            <!-- Step 2: 入力フォーム -->
            <div class="contact-step" id="step2" data-step="2" style="display: none;">
                <div class="contact-step__header">
                    <button type="button" class="contact-step__back" id="backButton">
                        <i class="fas fa-arrow-left"></i> 戻る
                    </button>
                    <h2 class="contact-step__title">お問い合わせ内容を入力</h2>
                    <div class="contact-step__selected-type">
                        <i class="fas fa-check-circle"></i>
                        <span id="selectedTypeText"></span>
                    </div>
                </div>

                <form action="includes/contact-form-step.php" method="POST" class="contact-step-form" id="contactForm">
                    <input type="hidden" name="type" id="contactType" value="">

                    <div class="form-group">
                        <label for="company" class="form-label">会社名・団体名</label>
                        <input type="text" id="company" name="company" class="form-input" placeholder="株式会社〇〇">
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label form-label--required">お名前</label>
                        <input type="text" id="name" name="name" class="form-input" required placeholder="山田 太郎">
                        <div class="form-error" id="nameError">お名前を入力してください</div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label form-label--required">メールアドレス</label>
                        <input type="email" id="email" name="email" class="form-input" required placeholder="example@example.com">
                        <div class="form-error" id="emailError">正しいメールアドレスを入力してください</div>
                    </div>

                    <div class="form-group">
                        <label for="tel" class="form-label">電話番号</label>
                        <input type="tel" id="tel" name="tel" class="form-input" placeholder="090-1234-5678">
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label form-label--required">お問い合わせ内容</label>
                        <textarea id="message" name="message" class="form-textarea" required placeholder="お問い合わせ内容をご記入ください"></textarea>
                        <p class="form-help">具体的な内容をご記入いただけますと、スムーズに対応できます。</p>
                        <div class="form-error" id="messageError">お問い合わせ内容を入力してください</div>
                    </div>

                    <div class="form-submit">
                        <button type="submit" class="btn btn-primary btn--large" id="submitButton">
                            <span class="btn-text">送信する</span>
                            <span class="btn-loader" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

    <script defer src="assets/js/app.js"></script>
    <script defer src="assets/js/contact-step-form.js"></script>

</body>
</html>
