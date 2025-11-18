<?php
$current_page = 'contact';
require_once __DIR__ . '/includes/functions.php';

// Head用の変数設定
$page_title = 'お問い合わせ | 余日（Yojitsu）';
$page_description = '余日（Yojitsu）へのお問い合わせはこちら。LINE、電話、メールフォームで受け付けております。';
$additional_css = ['assets/css/pages/contact.css', 'assets/css/cookie-consent.css'];
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

    <!-- お問い合わせフォーム -->
    <section class="contact-form">
        <div class="container contact-form__container">
            <h2 class="section__title animate">メールフォーム</h2>
            <p class="contact-form__intro animate">
                以下のフォームからもお問い合わせいただけます。<br>
                内容を確認後、担当者よりご連絡させていただきます。
            </p>

            <form action="includes/contact-form.php" method="POST" class="form animate" id="contactForm">
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
                    <label for="subject" class="form-label form-label--required">お問い合わせ種別</label>
                    <select id="subject" name="subject" class="form-select" required>
                        <option value="">選択してください</option>
                        <option value="SEO対策について">SEO対策について</option>
                        <option value="広告運用について">広告運用について</option>
                        <option value="Web制作について">Web制作について</option>
                        <option value="ショート動画制作について">ショート動画制作について</option>
                        <option value="業務委託・協業について">業務委託・協業について</option>
                        <option value="見積もり依頼">見積もり依頼</option>
                        <option value="営業のご連絡">営業のご連絡</option>
                        <option value="その他">その他</option>
                    </select>
                    <div class="form-error" id="subjectError">お問い合わせ種別を選択してください</div>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label form-label--required">お問い合わせ内容</label>
                    <textarea id="message" name="message" class="form-textarea" required placeholder="お問い合わせ内容をご記入ください"></textarea>
                    <p class="form-help">具体的な内容をご記入いただけますと、スムーズに対応できます。</p>
                    <div class="form-error" id="messageError">お問い合わせ内容を入力してください</div>
                </div>

                <div class="form-submit">
                    <button type="submit" class="btn btn-primary btn--large">送信する</button>
                </div>
            </form>
        </div>
    </section>

    <?php include __DIR__ . '/includes/footer.php'; ?>

    <?php include __DIR__ . '/includes/cookie-consent.php'; ?>

    <script defer src="assets/js/app.js"></script>
    <script defer src="assets/js/form-validation.js"></script>

</body>
</html>
