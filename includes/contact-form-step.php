<?php
/**
 * ステップフォーム処理（CV計測対応）
 */

require_once __DIR__ . '/functions.php';

// POST送信のみ受付
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../contact-new.php');
    exit;
}

// 入力データ取得
$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$company = isset($_POST['company']) ? trim($_POST['company']) : '';
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$tel = isset($_POST['tel']) ? trim($_POST['tel']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// バリデーション
$errors = [];

if (empty($type)) {
    $errors[] = 'お問い合わせ種別が選択されていません。';
}

if (empty($name)) {
    $errors[] = 'お名前を入力してください。';
}

if (empty($email)) {
    $errors[] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = '正しいメールアドレスを入力してください。';
}

if (empty($message)) {
    $errors[] = 'お問い合わせ内容を入力してください。';
}

// エラーがある場合
if (!empty($errors)) {
    $current_page = 'contact';
    $error_message = implode('<br>', $errors);
    showErrorPage($error_message);
    exit;
}

// 一意のCV IDを生成
$cv_id = generateCVID();

// 種別マッピング
$typeMapping = [
    'seo' => 'SEO対策について',
    'ad' => '広告運用について',
    'web' => 'Web制作について',
    'video' => 'ショート動画制作について',
    'freelance' => '業務委託・協業について',
    'quote' => '見積もり依頼',
    'sales' => '営業のご連絡',
    'other' => 'その他'
];

$typeLabel = isset($typeMapping[$type]) ? $typeMapping[$type] : $type;

// データ保存
$contactData = [
    'cv_id' => $cv_id,
    'type' => $type,
    'type_label' => $typeLabel,
    'company' => $company,
    'name' => $name,
    'email' => $email,
    'tel' => $tel,
    'message' => $message,
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
    'submitted_at' => date('Y-m-d H:i:s'),
    'timestamp' => time()
];

saveContactData($contactData);

// メール送信
sendAdminEmail($contactData);
sendUserEmail($contactData);

// サンクスページへリダイレクト
header('Location: ../contact/thanks.php?type=' . urlencode($type) . '&cv_id=' . urlencode($cv_id));
exit;

/**
 * CV ID生成（一意）
 */
function generateCVID() {
    return uniqid('cv_', true) . '_' . bin2hex(random_bytes(8));
}

/**
 * データ保存（JSON形式）
 */
function saveContactData($data) {
    $dataDir = dirname(__DIR__) . '/data/contacts';

    // ディレクトリがなければ作成
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }

    // 年月ごとにファイル分割
    $yearMonth = date('Y-m');
    $filename = $dataDir . '/contacts_' . $yearMonth . '.json';

    // 既存データ読み込み
    $contacts = [];
    if (file_exists($filename)) {
        $jsonData = file_get_contents($filename);
        $contacts = json_decode($jsonData, true) ?? [];
    }

    // 新規データ追加
    $contacts[] = $data;

    // 保存
    file_put_contents($filename, json_encode($contacts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // パーミッション設定
    chmod($filename, 0644);
}

/**
 * 管理者へメール送信
 */
function sendAdminEmail($data) {
    mb_language("uni");
    mb_internal_encoding("UTF-8");

    $to = ADMIN_EMAIL;
    $subject = '【' . $data['type_label'] . '】お問い合わせ（CV ID: ' . $data['cv_id'] . '）';

    $body = "新しいお問い合わせがありました。\n\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━\n";
    $body .= "■ CV ID\n" . $data['cv_id'] . "\n\n";
    $body .= "■ お問い合わせ種別\n" . $data['type_label'] . "\n\n";
    $body .= "■ 会社名・団体名\n" . ($data['company'] ?: '（未記入）') . "\n\n";
    $body .= "■ お名前\n" . $data['name'] . "\n\n";
    $body .= "■ メールアドレス\n" . $data['email'] . "\n\n";
    $body .= "■ 電話番号\n" . ($data['tel'] ?: '（未記入）') . "\n\n";
    $body .= "■ お問い合わせ内容\n" . $data['message'] . "\n\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $body .= "送信日時: " . $data['submitted_at'] . "\n";
    $body .= "IPアドレス: " . $data['ip_address'] . "\n";

    $headers = "From: " . ADMIN_EMAIL . "\r\n";
    $headers .= "Reply-To: " . $data['email'] . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mb_send_mail($to, $subject, $body, $headers);
}

/**
 * ユーザーへ自動返信メール
 */
function sendUserEmail($data) {
    mb_language("uni");
    mb_internal_encoding("UTF-8");

    $subject = 'お問い合わせありがとうございます - 余日（Yojitsu）';

    $body = "{$data['name']} 様\n\n";
    $body .= "この度は、余日（Yojitsu）にお問い合わせいただき、誠にありがとうございます。\n\n";
    $body .= "以下の内容でお問い合わせを受け付けました。\n";
    $body .= "担当者より2営業日以内にご連絡させていただきます。\n\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $body .= "■ お問い合わせ種別\n" . $data['type_label'] . "\n\n";
    $body .= "■ お問い合わせ内容\n" . $data['message'] . "\n\n";
    $body .= "■ 受付番号\n" . $data['cv_id'] . "\n\n";
    $body .= "━━━━━━━━━━━━━━━━━━━━\n\n";
    $body .= "※このメールは自動送信されています。\n";
    $body .= "※お心当たりのない場合は、お手数ですがこのメールを破棄してください。\n\n";
    $body .= "余日（Yojitsu）\n";
    $body .= "Email: " . SITE_EMAIL . "\n";
    $body .= "Tel: " . SITE_TEL . "\n";
    $body .= "URL: " . SITE_URL . "\n";

    $headers = "From: " . ADMIN_EMAIL . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    mb_send_mail($data['email'], $subject, $body, $headers);
}

/**
 * エラーページ表示
 */
function showErrorPage($message) {
    global $current_page;
    $current_page = 'contact';
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>エラー | お問い合わせ | 余日（Yojitsu）</title>
        <link rel="stylesheet" href="../assets/css/base.css">
    </head>
    <body>
        <?php include __DIR__ . '/header.php'; ?>
        <div class='container' style='padding: 120px 24px 60px; text-align: center; min-height: 60vh;'>
            <h1 style='color: var(--color-natural-brown); margin-bottom: 24px;'>エラーが発生しました</h1>
            <p style='margin-bottom: 40px; color: var(--color-text-light);'><?php echo $message; ?></p>
            <a href='../contact-new.php' class='btn btn-primary'>戻る</a>
        </div>
        <?php include __DIR__ . '/footer.php'; ?>
    </body>
    </html>
    <?php
}
