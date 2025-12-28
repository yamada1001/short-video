<?php
/**
 * SMTP設定テストツール
 * 使用後は必ず削除してください
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 管理者のみアクセス可能にする簡易認証
$secret = $_GET['secret'] ?? '';
if ($secret !== 'test123') {
    die('Access denied');
}

echo '<pre>';
echo "=== SMTP設定テスト ===\n\n";

// 設定値の確認
echo "【設定値】\n";
echo "MAIL_HOST: " . (defined('MAIL_HOST') ? MAIL_HOST : '未定義') . "\n";
echo "MAIL_PORT: " . (defined('MAIL_PORT') ? MAIL_PORT : '未定義') . "\n";
echo "MAIL_USERNAME: " . (defined('MAIL_USERNAME') ? MAIL_USERNAME : '未定義') . "\n";
echo "MAIL_PASSWORD: " . (defined('MAIL_PASSWORD') ? (strlen(MAIL_PASSWORD) > 0 ? '設定済み（' . strlen(MAIL_PASSWORD) . '文字）' : '空') : '未定義') . "\n";
echo "MAIL_FROM_ADDRESS: " . (defined('MAIL_FROM_ADDRESS') ? MAIL_FROM_ADDRESS : '未定義') . "\n";
echo "MAIL_FROM_NAME: " . (defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : '未定義') . "\n\n";

// テストメール送信
$testEmail = $_GET['email'] ?? '';

if ($testEmail && filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
    echo "【テストメール送信】\n";
    echo "送信先: {$testEmail}\n\n";

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // SMTP設定
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USERNAME;
        $mail->Password = MAIL_PASSWORD;
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = MAIL_PORT;
        $mail->Timeout = 10;

        // デバッグ出力を有効化
        $mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {
            echo "DEBUG: $str\n";
        };

        // 送信者・受信者設定
        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        $mail->addAddress($testEmail);

        // 文字コード設定
        $mail->CharSet = 'UTF-8';

        // メール内容
        $mail->isHTML(true);
        $mail->Subject = 'SMTP設定テスト';
        $mail->Body = '<p>これはテストメールです。SMTP設定が正しく動作しています。</p>';

        $mail->send();
        echo "\n✅ 送信成功！\n";
    } catch (Exception $e) {
        echo "\n❌ 送信失敗\n";
        echo "エラー: " . $mail->ErrorInfo . "\n";
        echo "例外: " . $e->getMessage() . "\n";
    }
} else {
    echo "【使用方法】\n";
    echo "test-smtp.php?secret=test123&email=your@email.com\n";
    echo "でテストメールを送信できます。\n";
}

echo '</pre>';
