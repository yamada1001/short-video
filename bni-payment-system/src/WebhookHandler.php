<?php
namespace BNI;

/**
 * Webhook処理クラス
 * Square Webhook受信・署名検証・処理
 */
class WebhookHandler {
    /**
     * Webhook署名検証
     *
     * @param string $signature リクエストヘッダーの署名
     * @param string $body リクエストボディ
     * @return bool
     */
    public function verifySignature(string $signature, string $body): bool {
        $signatureKey = env('SQUARE_WEBHOOK_SIGNATURE_KEY');

        if (empty($signatureKey)) {
            Logger::error('Webhook signature key not configured');
            return false;
        }

        // HMAC-SHA256で署名生成
        $url = env('APP_URL') . '/webhook.php';
        $payload = $url . $body;

        $expectedSignature = base64_encode(
            hash_hmac('sha256', $payload, $signatureKey, true)
        );

        // タイミングセーフな比較
        $isValid = hash_equals($expectedSignature, $signature);

        if (!$isValid) {
            Logger::warning('Webhook signature verification failed', [
                'expected' => $expectedSignature,
                'received' => $signature
            ]);
        }

        return $isValid;
    }

    /**
     * Webhook処理
     *
     * @param array $payload
     * @return void
     */
    public function handle(array $payload): void {
        $eventType = $payload['type'] ?? '';

        Logger::info('Webhook received', [
            'type' => $eventType,
            'merchant_id' => $payload['merchant_id'] ?? null
        ]);

        switch ($eventType) {
            case 'payment.created':
            case 'payment.updated':
                $this->handlePaymentCreated($payload['data']['object'] ?? []);
                break;

            default:
                Logger::info('Unhandled webhook event type', ['type' => $eventType]);
        }
    }

    /**
     * 支払い完了処理
     *
     * @param array $payment
     * @return void
     */
    private function handlePaymentCreated(array $payment): void {
        try {
            // payment_noteからmember_id, week_ofを抽出
            $note = $payment['note'] ?? '';

            // 正規表現でパース: "member_id:1,week_of:2025-12-17"
            if (!preg_match('/member_id:(\d+),week_of:(\d{4}-\d{2}-\d{2})/', $note, $matches)) {
                Logger::error('Invalid payment note format', ['note' => $note]);
                return;
            }

            $memberId = (int)$matches[1];
            $weekOf = $matches[2];
            $squarePaymentId = $payment['id'] ?? '';
            $amount = $payment['amount_money']['amount'] ?? 0;
            $paidAt = $payment['created_at'] ?? date('Y-m-d H:i:s');

            // メンバー存在確認
            $member = Member::getById($memberId);
            if (!$member) {
                Logger::error('Member not found', ['member_id' => $memberId]);
                return;
            }

            // 重複チェック
            if (Payment::exists($memberId, $weekOf)) {
                Logger::info('Payment already exists', [
                    'member_id' => $memberId,
                    'week_of' => $weekOf
                ]);
                return;
            }

            // 支払い記録作成
            $paymentId = Payment::create([
                'member_id' => $memberId,
                'amount' => $amount,
                'week_of' => $weekOf,
                'square_payment_id' => $squarePaymentId,
                'paid_at' => $paidAt,
            ]);

            Logger::info('Payment recorded successfully', [
                'payment_id' => $paymentId,
                'member_id' => $memberId,
                'member_name' => $member['name'],
                'week_of' => $weekOf,
                'amount' => $amount
            ]);

        } catch (\Exception $e) {
            Logger::error('Payment creation failed in webhook', [
                'error' => $e->getMessage(),
                'payment' => $payment
            ]);
        }
    }

    /**
     * Webhookレスポンス送信
     *
     * @param int $statusCode
     * @param array $data
     * @return void
     */
    public function sendResponse(int $statusCode, array $data = []): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
