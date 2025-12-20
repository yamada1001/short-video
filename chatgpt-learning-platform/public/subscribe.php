<?php
/**
 * サブスクリプション申し込みページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../vendor/autoload.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();

// すでにプレミアム会員の場合
if (hasActiveSubscription()) {
    $_SESSION['info_message'] = 'すでにプレミアム会員です。';
    header('Location: ' . APP_URL . '/dashboard.php');
    exit;
}

// プランを取得（monthly or yearly）
$plan = $_GET['plan'] ?? 'monthly';
$validPlans = ['monthly', 'yearly'];
if (!in_array($plan, $validPlans)) {
    $plan = 'monthly';
}

// Stripe設定
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// プラン情報
$planInfo = [
    'monthly' => [
        'name' => '月額プラン',
        'price' => '980円/月',
        'price_id' => STRIPE_PRICE_ID_MONTHLY
    ],
    'yearly' => [
        'name' => '年額プラン',
        'price' => '9,800円/年',
        'price_id' => STRIPE_PRICE_ID_YEARLY
    ]
];

$selectedPlan = $planInfo[$plan];

// Stripe Checkoutセッションを作成
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken($_POST['csrf_token']);

    try {
        // Stripeカスタマーを作成または取得
        if ($user['stripe_customer_id']) {
            $customerId = $user['stripe_customer_id'];
        } else {
            $customer = \Stripe\Customer::create([
                'email' => $user['email'],
                'name' => $user['name'],
                'metadata' => [
                    'user_id' => $user['id']
                ]
            ]);
            $customerId = $customer->id;

            // DBにStripeカスタマーIDを保存
            $updateSql = "UPDATE users SET stripe_customer_id = ? WHERE id = ?";
            db()->execute($updateSql, [$customerId, $user['id']]);
        }

        // Checkout Sessionを作成
        $session = \Stripe\Checkout\Session::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $selectedPlan['price_id'],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => APP_URL . '/subscription-success.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => APP_URL . '/subscribe.php?plan=' . $plan,
            'metadata' => [
                'user_id' => $user['id']
            ]
        ]);

        // Stripe Checkoutにリダイレクト
        header('Location: ' . $session->url);
        exit;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        $error = 'エラーが発生しました: ' . $e->getMessage();
        error_log('Stripe Error: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プレミアム会員登録 | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/custom-style.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <div class="container" style="max-width: 800px; margin: 60px auto; padding: 0 20px;">
        <div class="page-header" style="text-align: center; margin-bottom: 40px;">
            <h1>プレミアム会員登録</h1>
            <p>すべてのコースにアクセスして、Gemini AIを使いこなそう</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?= h($error) ?>
            </div>
        <?php endif; ?>

        <div style="background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.07); margin-bottom: 30px;">
            <h2 style="font-size: 24px; margin-bottom: 20px; text-align: center;">
                <?= h($selectedPlan['name']) ?>
            </h2>
            <div style="text-align: center; margin-bottom: 30px;">
                <span style="font-size: 36px; font-weight: 700; color: var(--primary);">
                    <?= h($selectedPlan['price']) ?>
                </span>
            </div>

            <h3 style="font-size: 18px; margin-bottom: 16px;">プレミアム会員の特典</h3>
            <ul style="list-style: none; padding: 0; margin-bottom: 30px;">
                <li style="padding: 12px 0; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center;">
                    <span style="color: var(--success); margin-right: 12px; font-size: 20px;">✓</span>
                    すべてのコースにアクセス可能
                </li>
                <li style="padding: 12px 0; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center;">
                    <span style="color: var(--success); margin-right: 12px; font-size: 20px;">✓</span>
                    Gemini AI実行回数が1日100回に増加
                </li>
                <li style="padding: 12px 0; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center;">
                    <span style="color: var(--success); margin-right: 12px; font-size: 20px;">✓</span>
                    新コースへの優先アクセス
                </li>
                <li style="padding: 12px 0; border-bottom: 1px solid var(--gray-200); display: flex; align-items: center;">
                    <span style="color: var(--success); margin-right: 12px; font-size: 20px;">✓</span>
                    課題の詳細フィードバック
                </li>
            </ul>

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <button type="submit" class="btn btn-primary btn-block" style="font-size: 18px; padding: 16px;">
                    <?= h($selectedPlan['name']) ?>に申し込む
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="<?= APP_URL ?>/subscribe.php?plan=<?= $plan === 'monthly' ? 'yearly' : 'monthly' ?>" class="text-link">
                    <?= $plan === 'monthly' ? '年額プラン' : '月額プラン' ?>を見る
                </a>
            </div>
        </div>

        <div style="text-align: center; color: var(--text-muted); font-size: 14px;">
            <p>お支払いはStripeで安全に処理されます。<br>いつでもキャンセル可能です。</p>
        </div>
    </div>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
