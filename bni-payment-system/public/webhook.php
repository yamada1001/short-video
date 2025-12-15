<?php
/**
 * Square Webhook受信エンドポイント
 * 決済完了通知を受け取り、DBに記録
 */

require_once __DIR__ . '/../config/config.php';

use BNI\WebhookHandler;
use BNI\Logger;

$handler = new WebhookHandler();

try {
    // リクエストボディ取得
    $body = file_get_contents('php://input');

    // 署名取得
    $signature = $_SERVER['HTTP_X_SQUARE_HMACSHA256_SIGNATURE'] ?? '';

    Logger::debug('Webhook request received', [
        'signature' => $signature,
        'body_length' => strlen($body)
    ], 'webhook');

    // 署名検証
    if (!$handler->verifySignature($signature, $body)) {
        Logger::warning('Webhook signature verification failed', [], 'webhook');
        $handler->sendResponse(401, ['error' => 'Invalid signature']);
    }

    // ペイロード解析
    $payload = json_decode($body, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        Logger::error('Webhook JSON parse error', [
            'error' => json_last_error_msg()
        ], 'webhook');
        $handler->sendResponse(400, ['error' => 'Invalid JSON']);
    }

    // Webhook処理
    $handler->handle($payload);

    // 成功レスポンス
    Logger::info('Webhook processed successfully', [
        'type' => $payload['type'] ?? 'unknown'
    ], 'webhook');

    $handler->sendResponse(200, ['status' => 'success']);

} catch (\Exception $e) {
    Logger::error('Webhook processing error', [
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ], 'webhook');

    $handler->sendResponse(500, ['error' => 'Internal server error']);
}
