<?php
namespace Seminar;

use Square\SquareClient as Square;
use Square\Environment;
use Square\Models\Money;
use Square\Models\CreatePaymentLinkRequest;
use Square\Models\QuickPay;
use Square\Models\CheckoutOptions;

/**
 * Square APIクライアント
 * Payment Links作成
 */
class SquareClient {
    private $client;
    private $locationId;

    public function __construct() {
        // 環境設定（sandbox or production）
        $environment = env('SQUARE_ENVIRONMENT') === 'production'
            ? Environment::PRODUCTION
            : Environment::SANDBOX;

        // Square クライアント初期化
        $this->client = new Square([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => $environment,
        ]);

        $this->locationId = env('SQUARE_LOCATION_ID');
    }

    /**
     * Payment Link作成
     *
     * @param int $attendeeId 参加者ID
     * @param int $amount 金額（円）
     * @return object Payment Link オブジェクト
     * @throws \Exception
     */
    public function createPaymentLink(int $attendeeId, int $amount): object {
        // 参加者取得
        $attendee = Attendee::getById($attendeeId);
        if (!$attendee) {
            throw new \Exception('参加者が見つかりません');
        }

        // セミナー情報取得
        $seminar = Seminar::getById($attendee['seminar_id']);
        if (!$seminar) {
            throw new \Exception('セミナーが見つかりません');
        }

        // 金額設定（Square APIは最小通貨単位で指定、JPYは円）
        $money = new Money();
        $money->setAmount($amount);
        $money->setCurrency('JPY');

        // QuickPay設定
        $quickPay = new QuickPay(
            $seminar['title'] . ' - ' . $attendee['name'],
            $money,
            $this->locationId
        );

        // Checkout オプション設定
        $checkoutOptions = new CheckoutOptions();
        $checkoutOptions->setRedirectUrl(env('APP_URL') . '/public/payment-complete.php?attendee_id=' . $attendeeId);

        // Payment Link リクエスト作成
        $request = new CreatePaymentLinkRequest();
        $request->setQuickPay($quickPay);
        $request->setCheckoutOptions($checkoutOptions);

        // メタデータとして attendee_id と seminar_id を payment_note に含める
        $request->setPaymentNote("attendee_id:{$attendeeId},seminar_id:{$seminar['id']}");

        try {
            // API呼び出し
            $response = $this->client->getCheckoutApi()->createPaymentLink($request);

            if ($response->isSuccess()) {
                $paymentLink = $response->getResult()->getPaymentLink();

                Logger::info('Payment link created', [
                    'attendee_id' => $attendeeId,
                    'seminar_id' => $seminar['id'],
                    'amount' => $amount,
                    'link_id' => $paymentLink->getId()
                ]);

                return $paymentLink;
            } else {
                $errors = $response->getErrors();
                $errorMessages = array_map(function($error) {
                    return $error->getDetail();
                }, $errors);

                Logger::error('Payment link creation failed', [
                    'attendee_id' => $attendeeId,
                    'errors' => $errorMessages
                ]);

                throw new \Exception('決済リンクの作成に失敗しました: ' . implode(', ', $errorMessages));
            }
        } catch (\Exception $e) {
            Logger::error('Payment link creation exception', [
                'attendee_id' => $attendeeId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Payment取得（Webhook検証用）
     *
     * @param string $paymentId
     * @return object|null
     */
    public function getPayment(string $paymentId): ?object {
        try {
            $response = $this->client->getPaymentsApi()->getPayment($paymentId);

            if ($response->isSuccess()) {
                return $response->getResult()->getPayment();
            }

            return null;
        } catch (\Exception $e) {
            Logger::error('Get payment failed', [
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Webhook署名検証
     *
     * @param string $body リクエストボディ
     * @param string $signature リクエストヘッダーの署名
     * @return bool
     */
    public function verifyWebhookSignature(string $body, string $signature): bool {
        $signatureKey = env('SQUARE_WEBHOOK_SIGNATURE_KEY');
        $hash = base64_encode(hash_hmac('sha256', env('SQUARE_WEBHOOK_URL') . $body, $signatureKey, true));

        return hash_equals($hash, $signature);
    }
}
