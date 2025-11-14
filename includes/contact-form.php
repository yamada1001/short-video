<?php
/**
 * お問い合わせフォーム処理
 */

require_once __DIR__ . '/functions.php';

// POST送信のみ受付
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.php');
    exit;
}

// 入力データ取得
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$tel = isset($_POST['tel']) ? trim($_POST['tel']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// バリデーション
$errors = [];

if (empty($name)) {
    $errors[] = 'お名前を入力してください。';
}

if (empty($email)) {
    $errors[] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '正しいメールアドレスを入力してください。';
}

if (empty($subject)) {
    $errors[] = 'お問い合わせ種別を選択してください。';
}

if (empty($message)) {
    $errors[] = 'お問い合わせ内容を入力してください。';
}

// エラーがある場合
if (!empty($errors)) {
    $current_page = 'contact';
    $error_message = implode('<br>', $errors);
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>エラー | お問い合わせ | 余日（Yojitsu）</title>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
        <!-- End Google Tag Manager -->
        <link rel="icon" type="image/svg+xml" href="../favicon.svg">
        <link rel="stylesheet" href="../assets/css/base.css">
    </head>
    <body>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        <?php include __DIR__ . '/header.php'; ?>

        <div class='container' style='padding: 120px 24px 60px; text-align: center; min-height: 60vh;'>
            <h1 style='color: var(--color-natural-brown); margin-bottom: 24px;'>エラーが発生しました</h1>
            <p style='margin-bottom: 40px; color: var(--color-text-light);'><?php echo $error_message; ?></p>
            <a href='../contact.php' class='btn btn-primary'>戻る</a>
        </div>

        <?php include __DIR__ . '/footer.php'; ?>
        <script src="../assets/js/fontawesome-init.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// メール送信の設定
mb_language("uni");
mb_internal_encoding("UTF-8");

// 管理者へのメール
$to = ADMIN_EMAIL;
$mail_subject = '【お問い合わせ】' . $subject;

$mail_body = "お問い合わせがありました。\n\n";
$mail_body .= "■ 会社名・団体名\n" . $company . "\n\n";
$mail_body .= "■ お名前\n" . $name . "\n\n";
$mail_body .= "■ メールアドレス\n" . $email . "\n\n";
$mail_body .= "■ 電話番号\n" . $tel . "\n\n";
$mail_body .= "■ お問い合わせ種別\n" . $subject . "\n\n";
$mail_body .= "■ お問い合わせ内容\n" . $message . "\n\n";
$mail_body .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n";

$headers = "From: " . ADMIN_EMAIL . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

// 管理者へ送信
$admin_result = mb_send_mail($to, $mail_subject, $mail_body, $headers);

// メール送信失敗時のログ記録（デバッグ用）
if (!$admin_result) {
    error_log("Contact form: Failed to send admin email to " . $to);
    error_log("Subject: " . $mail_subject);
    error_log("From: " . FROM_EMAIL);
}

// 自動返信メール
$reply_subject = 'お問い合わせありがとうございます - 余日（Yojitsu）';
$reply_body = "{$name} 様\n\n";
$reply_body .= "この度は、余日（Yojitsu）にお問い合わせいただき、誠にありがとうございます。\n\n";
$reply_body .= "以下の内容でお問い合わせを受け付けました。\n";
$reply_body .= "担当者より2営業日以内にご連絡させていただきます。\n\n";
$reply_body .= "────────────────────\n\n";
$reply_body .= "■ お問い合わせ種別\n" . $subject . "\n\n";
$reply_body .= "■ お問い合わせ内容\n" . $message . "\n\n";
$reply_body .= "────────────────────\n\n";
$reply_body .= "※このメールは自動送信されています。\n";
$reply_body .= "※お心当たりのない場合は、お手数ですがこのメールを破棄してください。\n\n";
$reply_body .= "余日（Yojitsu）\n";
$reply_body .= "Email: " . SITE_EMAIL . "\n";
$reply_body .= "Tel: " . SITE_TEL . "\n";
$reply_body .= "URL: " . SITE_URL . "\n";

$reply_headers = "From: " . ADMIN_EMAIL . "\r\n";
$reply_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$reply_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

$user_result = mb_send_mail($email, $reply_subject, $reply_body, $reply_headers);

// ユーザーへのメール送信失敗時のログ記録
if (!$user_result) {
    error_log("Contact form: Failed to send user email to " . $email);
}

$current_page = 'contact';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了 | お問い合わせ | 余日（Yojitsu）</title>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->
    <link rel="icon" type="image/svg+xml" href="../favicon.svg">
    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/pages/contact.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/header.php'; ?>

    <!-- 送信完了メッセージ -->
    <section class="form-success">
        <div class="container">
            <div class="form-success__icon">
                <i class="fas fa-check"></i>
            </div>
            <h1 class="form-success__title">送信完了しました</h1>
            <p class="form-success__message">
                お問い合わせいただきありがとうございます。<br>
                内容を確認後、担当者より2営業日以内にご連絡させていただきます。<br><br>
                自動返信メールをお送りしておりますので、ご確認ください。<br>
                メールが届かない場合は、迷惑メールフォルダをご確認いただくか、<br>
                お手数ですが再度お問い合わせください。
            </p>
            <a href="../index.html" class="btn btn-primary btn--large">トップページへ戻る</a>
        </div>
    </section>

    <?php include __DIR__ . '/footer.php'; ?>

    <script src="../assets/js/fontawesome-init.js"></script>
</body>
</html>
