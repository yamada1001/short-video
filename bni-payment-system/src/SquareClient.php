<?php
namespace BNI;

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
     * @param int $memberId
     * @param int $amount 金額（円）
     * @return object Payment Link オブジェクト
     * @throws \Exception
     */
    public function createPaymentLink(int $memberId, int $amount): object {
        // メンバー取得
        $member = Member::getById($memberId);
        if (!$member) {
            throw new \Exception('メンバーが見つかりません');
        }

        // 今週の火曜日
        $weekOf = getCurrentWeek();

        // 既に支払い済みかチェック
        if (Payment::exists($memberId, $weekOf)) {
            throw new \Exception('今週の会費は既にお支払い済みです');
        }

        // 金額設定（Square APIは最小通貨単位で指定、JPYは円）
        $money = new Money();
        $money->setAmount($amount);
        $money->setCurrency('JPY');

        // QuickPay設定
        $quickPay = new QuickPay(
            'BNI定例会費 - ' . $member['name'] . ' - ' . getWeekLabel($weekOf),
            $money,
            $this->locationId
        );

        // Checkout オプション設定
        $checkoutOptions = new CheckoutOptions();
        $checkoutOptions->setRedirectUrl(env('APP_URL') . '/public/thank-you.php');

        // Payment Link リクエスト作成
        $request = new CreatePaymentLinkRequest();
        $request->setQuickPay($quickPay);
        $request->setCheckoutOptions($checkoutOptions);

        // メタデータとして member_id と week_of を payment_note に含める
        $request->setPaymentNote("member_id:{$memberId},week_of:{$weekOf}");

        try {
            // API呼び出し
            $response = $this->client->getCheckoutApi()->createPaymentLink($request);

            if ($response->isSuccess()) {
                $paymentLink = $response->getResult()->getPaymentLink();

                Logger::info('Payment link created', [
                    'member_id' => $memberId,
                    'week_of' => $weekOf,
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
                    'member_id' => $memberId,
                    'errors' => $errorMessages
                ]);

                throw new \Exception('決済リンクの作成に失敗しました: ' . implode(', ', $errorMessages));
            }
        } catch (\Exception $e) {
            Logger::error('Payment link creation exception', [
                'member_id' => $memberId,
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
}
