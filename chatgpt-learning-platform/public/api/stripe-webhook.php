<?php
/**
 * Stripe Webhook処理
 *
 * Stripeからのwebhookイベントを受け取り、サブスクリプション状態を更新する
 */
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Stripe設定
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// Webhookペイロードを取得
$payload = @file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

try {
    // Webhookの署名を検証
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sigHeader,
        STRIPE_WEBHOOK_SECRET
    );
} catch (\UnexpectedValueException $e) {
    // ペイロードが不正
    http_response_code(400);
    exit('Invalid payload');
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // 署名が不正
    http_response_code(400);
    exit('Invalid signature');
}

// イベントタイプごとに処理
switch ($event->type) {
    case 'checkout.session.completed':
        // チェックアウト完了
        $session = $event->data->object;
        handleCheckoutCompleted($session);
        break;

    case 'customer.subscription.created':
        // サブスクリプション作成
        $subscription = $event->data->object;
        handleSubscriptionCreated($subscription);
        break;

    case 'customer.subscription.updated':
        // サブスクリプション更新
        $subscription = $event->data->object;
        handleSubscriptionUpdated($subscription);
        break;

    case 'customer.subscription.deleted':
        // サブスクリプション削除（キャンセル）
        $subscription = $event->data->object;
        handleSubscriptionDeleted($subscription);
        break;

    case 'invoice.payment_succeeded':
        // 支払い成功
        $invoice = $event->data->object;
        handlePaymentSucceeded($invoice);
        break;

    case 'invoice.payment_failed':
        // 支払い失敗
        $invoice = $event->data->object;
        handlePaymentFailed($invoice);
        break;

    default:
        // その他のイベント（無視）
        error_log('Unhandled Stripe event: ' . $event->type);
}

http_response_code(200);
exit('OK');

/**
 * チェックアウト完了処理
 */
function handleCheckoutCompleted($session)
{
    $customerId = $session->customer;
    $subscriptionId = $session->subscription;

    // カスタマーIDからユーザーを検索
    $userSql = "SELECT * FROM users WHERE stripe_customer_id = ?";
    $user = db()->fetchOne($userSql, [$customerId]);

    if (!$user) {
        error_log('User not found for customer: ' . $customerId);
        return;
    }

    // サブスクリプション情報をDBに保存
    $insertSql = "INSERT INTO subscriptions (user_id, stripe_subscription_id, status, current_period_start, current_period_end)
                  VALUES (?, ?, 'active', FROM_UNIXTIME(?), FROM_UNIXTIME(?))
                  ON DUPLICATE KEY UPDATE
                  status = 'active',
                  current_period_start = FROM_UNIXTIME(?),
                  current_period_end = FROM_UNIXTIME(?)";

    // Stripeからサブスクリプション情報を取得
    try {
        $subscription = \Stripe\Subscription::retrieve($subscriptionId);
        $params = [
            $user['id'],
            $subscriptionId,
            $subscription->current_period_start,
            $subscription->current_period_end,
            $subscription->current_period_start,
            $subscription->current_period_end
        ];
        db()->execute($insertSql, $params);

        // ユーザーのサブスクリプション状態を更新
        $updateUserSql = "UPDATE users SET subscription_status = 'active' WHERE id = ?";
        db()->execute($updateUserSql, [$user['id']]);

    } catch (\Stripe\Exception\ApiErrorException $e) {
        error_log('Stripe API Error: ' . $e->getMessage());
    }
}

/**
 * サブスクリプション作成処理
 */
function handleSubscriptionCreated($subscription)
{
    $customerId = $subscription->customer;
    $subscriptionId = $subscription->id;

    $userSql = "SELECT * FROM users WHERE stripe_customer_id = ?";
    $user = db()->fetchOne($userSql, [$customerId]);

    if (!$user) {
        error_log('User not found for customer: ' . $customerId);
        return;
    }

    $insertSql = "INSERT INTO subscriptions (user_id, stripe_subscription_id, status, current_period_start, current_period_end)
                  VALUES (?, ?, ?, FROM_UNIXTIME(?), FROM_UNIXTIME(?))
                  ON DUPLICATE KEY UPDATE
                  status = ?,
                  current_period_start = FROM_UNIXTIME(?),
                  current_period_end = FROM_UNIXTIME(?)";

    $params = [
        $user['id'],
        $subscriptionId,
        $subscription->status,
        $subscription->current_period_start,
        $subscription->current_period_end,
        $subscription->status,
        $subscription->current_period_start,
        $subscription->current_period_end
    ];
    db()->execute($insertSql, $params);

    // ユーザーのサブスクリプション状態を更新
    $updateUserSql = "UPDATE users SET subscription_status = ? WHERE id = ?";
    db()->execute($updateUserSql, [$subscription->status, $user['id']]);
}

/**
 * サブスクリプション更新処理
 */
function handleSubscriptionUpdated($subscription)
{
    $subscriptionId = $subscription->id;

    $updateSql = "UPDATE subscriptions SET
                  status = ?,
                  current_period_start = FROM_UNIXTIME(?),
                  current_period_end = FROM_UNIXTIME(?)
                  WHERE stripe_subscription_id = ?";

    $params = [
        $subscription->status,
        $subscription->current_period_start,
        $subscription->current_period_end,
        $subscriptionId
    ];
    db()->execute($updateSql, $params);

    // ユーザーのサブスクリプション状態も更新
    $subSql = "SELECT user_id FROM subscriptions WHERE stripe_subscription_id = ?";
    $sub = db()->fetchOne($subSql, [$subscriptionId]);

    if ($sub) {
        $updateUserSql = "UPDATE users SET subscription_status = ? WHERE id = ?";
        db()->execute($updateUserSql, [$subscription->status, $sub['user_id']]);
    }
}

/**
 * サブスクリプション削除処理
 */
function handleSubscriptionDeleted($subscription)
{
    $subscriptionId = $subscription->id;

    $updateSql = "UPDATE subscriptions SET status = 'canceled' WHERE stripe_subscription_id = ?";
    db()->execute($updateSql, [$subscriptionId]);

    // ユーザーのサブスクリプション状態も更新
    $subSql = "SELECT user_id FROM subscriptions WHERE stripe_subscription_id = ?";
    $sub = db()->fetchOne($subSql, [$subscriptionId]);

    if ($sub) {
        $updateUserSql = "UPDATE users SET subscription_status = 'canceled' WHERE id = ?";
        db()->execute($updateUserSql, [$sub['user_id']]);
    }
}

/**
 * 支払い成功処理
 */
function handlePaymentSucceeded($invoice)
{
    // 支払い成功時の処理（必要に応じて実装）
    error_log('Payment succeeded for invoice: ' . $invoice->id);
}

/**
 * 支払い失敗処理
 */
function handlePaymentFailed($invoice)
{
    $customerId = $invoice->customer;

    $userSql = "SELECT * FROM users WHERE stripe_customer_id = ?";
    $user = db()->fetchOne($userSql, [$customerId]);

    if (!$user) {
        return;
    }

    // ユーザーのサブスクリプション状態を更新
    $updateUserSql = "UPDATE users SET subscription_status = 'past_due' WHERE id = ?";
    db()->execute($updateUserSql, [$user['id']]);

    // 必要に応じてメール通知などを実装
    error_log('Payment failed for user: ' . $user['id']);
}
