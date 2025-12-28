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

// 設定チェックのみ（メール送信はスキップ）
echo "【診断】\n";

// プレースホルダーチェック
$issues = [];

if (MAIL_HOST === 'smtp.example.com' || MAIL_HOST === '') {
    $issues[] = '❌ MAIL_HOST がプレースホルダーです';
}

if (MAIL_USERNAME === 'noreply@example.com' || MAIL_USERNAME === '') {
    $issues[] = '❌ MAIL_USERNAME がプレースホルダーです';
}

if (MAIL_PASSWORD === 'your_password_here' || MAIL_PASSWORD === '') {
    $issues[] = '❌ MAIL_PASSWORD がプレースホルダーです';
}

if (MAIL_FROM_ADDRESS === 'noreply@example.com' || MAIL_FROM_ADDRESS === '') {
    $issues[] = '❌ MAIL_FROM_ADDRESS がプレースホルダーです';
}

if (empty($issues)) {
    echo "✅ すべての設定値が入力されています\n\n";
    echo "【次のステップ】\n";
    echo "test-smtp.php?secret=test123&email=yamada@yojitu.com&send=1\n";
    echo "でテストメール送信ができます。\n";
} else {
    echo "以下の設定値を.envファイルで修正してください：\n\n";
    foreach ($issues as $issue) {
        echo $issue . "\n";
    }
    echo "\n修正方法：\n";
    echo "1. Xserverファイルマネージャーで .env を開く\n";
    echo "2. 上記の項目を実際の値に変更\n";
    echo "3. このページを再読み込み\n";
}

// send=1 パラメータがある場合のみメール送信
$testEmail = $_GET['email'] ?? '';
$send = $_GET['send'] ?? '';

if ($send === '1' && $testEmail && filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
    echo "\n\n【テストメール送信】\n";
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
        $mail->Timeout = 5;

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
        echo "\n✅ 送信成功！メールボックスを確認してください。\n";
    } catch (Exception $e) {
        echo "\n❌ 送信失敗\n";
        echo "エラー: " . $mail->ErrorInfo . "\n";
        echo "例外: " . $e->getMessage() . "\n";
    }
}

echo '</pre>';
