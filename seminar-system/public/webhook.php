<?php
/**
 * Square Webhook
 * 決済完了通知を受け取り、参加者ステータスを更新
 */
require_once __DIR__ . '/../config/config.php';

use Seminar\Attendee;
use Seminar\SquareClient;

// ログ記録用ヘルパー
function logWebhook(string $message, array $context = []) {
    Logger::info('[Webhook] ' . $message, $context);
}

// リクエストボディ取得
$body = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_SQUARE_HMACSHA256_SIGNATURE'] ?? '';

// 署名検証
$squareClient = new SquareClient();
if (!$squareClient->verifyWebhookSignature($body, $signature)) {
    logWebhook('Invalid webhook signature');
    http_response_code(403);
    echo json_encode(['error' => 'Invalid signature']);
    exit;
}

// JSONデコード
$data = json_decode($body, true);

if (!$data) {
    logWebhook('Invalid JSON payload');
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

logWebhook('Webhook received', ['type' => $data['type'] ?? 'unknown']);

// イベントタイプ確認
$eventType = $data['type'] ?? '';

if ($eventType !== 'payment.created' && $eventType !== 'payment.updated') {
    logWebhook('Unhandled event type', ['type' => $eventType]);
    http_response_code(200);
    echo json_encode(['status' => 'ignored']);
    exit;
}

// Paymentデータ取得
$paymentData = $data['data']['object']['payment'] ?? null;

if (!$paymentData) {
    logWebhook('No payment data found');
    http_response_code(400);
    echo json_encode(['error' => 'No payment data']);
    exit;
}

$paymentId = $paymentData['id'] ?? '';
$paymentStatus = $paymentData['status'] ?? '';
$note = $paymentData['note'] ?? '';

logWebhook('Processing payment', [
    'payment_id' => $paymentId,
    'status' => $paymentStatus,
    'note' => $note
]);

// ステータスがCOMPLETED以外は無視
if ($paymentStatus !== 'COMPLETED') {
    logWebhook('Payment not completed', ['status' => $paymentStatus]);
    http_response_code(200);
    echo json_encode(['status' => 'ignored']);
    exit;
}

// payment_noteからattendee_idを抽出
// フォーマット: "attendee_id:123,seminar_id:456"
preg_match('/attendee_id:(\d+)/', $note, $matches);

if (empty($matches[1])) {
    logWebhook('Attendee ID not found in note', ['note' => $note]);
    http_response_code(400);
    echo json_encode(['error' => 'Attendee ID not found']);
    exit;
}

$attendeeId = (int)$matches[1];

// 参加者情報取得
$attendee = Attendee::getById($attendeeId);

if (!$attendee) {
    logWebhook('Attendee not found', ['attendee_id' => $attendeeId]);
    http_response_code(404);
    echo json_encode(['error' => 'Attendee not found']);
    exit;
}

// 既に支払済みの場合はスキップ
if (in_array($attendee['status'], ['paid', 'attended'])) {
    logWebhook('Attendee already paid', [
        'attendee_id' => $attendeeId,
        'status' => $attendee['status']
    ]);
    http_response_code(200);
    echo json_encode(['status' => 'already_paid']);
    exit;
}

// ステータス更新
try {
    Attendee::updateStatus($attendeeId, 'paid');
    Attendee::updatePaymentId($attendeeId, $paymentId);

    logWebhook('Payment processed successfully', [
        'attendee_id' => $attendeeId,
        'payment_id' => $paymentId,
        'seminar_id' => $attendee['seminar_id']
    ]);

    http_response_code(200);
    echo json_encode([
        'status' => 'success',
        'attendee_id' => $attendeeId,
        'payment_id' => $paymentId
    ]);

} catch (\Exception $e) {
    logWebhook('Failed to update attendee status', [
        'attendee_id' => $attendeeId,
        'error' => $e->getMessage()
    ]);

    http_response_code(500);
    echo json_encode(['error' => 'Failed to update status']);
}
