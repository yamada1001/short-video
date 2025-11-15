<?php
/**
 * お問い合わせフォーム処理
 */

require_once __DIR__ . '/functions.php';

// POST送信のみ受付
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact.html');
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
    $error_message = implode('<br>', $errors);
    echo "<!DOCTYPE html>
<html lang='ja'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>エラー | お問い合わせ</title>
    <link rel="stylesheet" href="../assets/css/base.css">
</head>
<body>
    <div class='container' style='padding: 60px 24px; text-align: center;'>
        <h1 style='color: #e74c3c; margin-bottom: 24px;'>エラーが発生しました</h1>
        <p style='margin-bottom: 40px; color: #666;'>{$error_message}</p>
        <a href='../contact.html' class='btn btn-primary'>戻る</a>
    </div>
</body>
</html>";
    exit;
}

// メール送信
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

$headers = "From: " . FROM_EMAIL . "\r\n";
$headers .= "Reply-To: " . $email . "\r\n";

// 管理者へ送信
$result = mb_send_mail($to, $mail_subject, $mail_body, $headers);

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

$reply_headers = "From: " . FROM_EMAIL . "\r\n";

mb_send_mail($email, $reply_subject, $reply_body, $reply_headers);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了 | お問い合わせ | 余日（Yojitsu）</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <link rel="icon" type="image/svg+xml" href="../favicon.svg">

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500&display=swap">

    <!-- Font Awesome - Async load -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>

    <link rel="stylesheet" href="../assets/css/base.css">
    <link rel="stylesheet" href="../assets/css/pages/contact.css">
</head>
<body>
    <!-- ヘッダー -->
    <header class="header" id="header">
        <div class="container header__container">
            <a href="../index.php" class="header__logo">余日</a>
            <nav class="nav">
                <ul class="nav__list" id="navList">
                    <li><a href="../index.html#services" class="nav__link">サービス</a></li>
                    <li><a href="../news/" class="nav__link">お知らせ</a></li>
                    <li><a href="../about.php" class="nav__link">会社概要</a></li>
                    <li><a href="../contact.php" class="nav__link">お問い合わせ</a></li>
                </ul>
            </nav>
        </div>
    </header>

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
            <a href="../index.php" class="btn btn-primary btn--large">トップページへ戻る</a>
        </div>
    </section>

    <!-- フッター -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h3 class="footer__section-title">余日（Yojitsu）</h3>
                    <p style="color: rgba(255, 255, 255, 0.8);">
                        大分県を拠点としたデジタルマーケティング・Web制作会社
                    </p>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script defer src="../assets/js/app.js"></script>
</body>
</html>
