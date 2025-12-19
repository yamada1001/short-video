<?php
/**
 * Gemini実行API
 *
 * プロンプトを受け取り、Google Gemini APIを呼び出してレスポンスを返す
 * キャッシュ機能とAPI使用制限あり
 * 無料枠: 1,500リクエスト/日（Gemini 1.5 Flash）
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\GenerationConfig;
use Gemini\Enums\ModelType;

// POSTのみ許可
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Method not allowed', 405);
}

// ログインチェック
if (!isset($_SESSION['user_id'])) {
    errorResponse('ログインが必要です', 401);
}

// JSONリクエストを取得
$input = json_decode(file_get_contents('php://input'), true);
$prompt = $input['prompt'] ?? '';
$lessonId = $input['lesson_id'] ?? null;

// バリデーション
if (empty($prompt)) {
    errorResponse('プロンプトを入力してください');
}

if (strlen($prompt) > 2000) {
    errorResponse('プロンプトは2000文字以内で入力してください');
}

// API使用制限チェック
if (!checkApiLimit()) {
    $user = getCurrentUser();
    $limit = hasActiveSubscription() ? API_LIMIT_PREMIUM : API_LIMIT_FREE;
    errorResponse("本日のAPI使用回数の上限（{$limit}回）に達しました。明日またお試しください。", 429);
}

// キャッシュチェック
$cachedResponse = getCachedPrompt($prompt, GEMINI_MODEL);

if ($cachedResponse) {
    // キャッシュヒット
    successResponse([
        'response' => $cachedResponse,
        'cached' => true,
        'tokens_used' => 0
    ]);
}

// Gemini API呼び出し
try {
    // cURL経由で直接Gemini APIを呼び出し（シンプルな実装）
    $apiUrl = 'https://generativelanguage.googleapis.com/v1beta/models/' . GEMINI_MODEL . ':generateContent';
    $apiUrl .= '?key=' . GEMINI_API_KEY;

    $data = [
        'contents' => [
            [
                'parts' => [
                    ['text' => $prompt]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'maxOutputTokens' => 1000,
            'topP' => 0.95,
        ]
    ];

    $ch = curl_init($apiUrl);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // エラーハンドリング
    if ($httpCode === 429) {
        errorResponse('Gemini APIの無料枠（1,500リクエスト/日）を超過しました。明日0時（UTC）にリセットされます。', 429);
    }

    if ($httpCode !== 200) {
        error_log('Gemini API error: HTTP ' . $httpCode . ' - ' . $response);
        errorResponse('Geminiの実行に失敗しました。しばらくしてからもう一度お試しください。', 500);
    }

    $result = json_decode($response, true);

    // レスポンス検証
    if (!isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        error_log('Unexpected Gemini response: ' . json_encode($result));
        errorResponse('予期しないレスポンス形式です', 500);
    }

    $geminiResponse = $result['candidates'][0]['content']['parts'][0]['text'];

    // トークン使用量取得（GeminiはusageMetadataで返す）
    $tokensUsed = 0;
    if (isset($result['usageMetadata'])) {
        $tokensUsed = $result['usageMetadata']['totalTokenCount'] ?? 0;
    }

    // コスト計算（無料枠内なら$0、超過しても課金されない）
    $costUsd = 0.0; // Gemini無料枠は課金なし

    // API使用量を記録
    logApiUsage('gemini', $tokensUsed, $costUsd);

    // キャッシュに保存
    saveCachedPrompt($prompt, $geminiResponse, GEMINI_MODEL);

    // 成功レスポンス
    successResponse([
        'response' => $geminiResponse,
        'cached' => false,
        'tokens_used' => $tokensUsed,
        'cost_usd' => $costUsd,
        'model' => GEMINI_MODEL
    ]);

} catch (Exception $e) {
    error_log('Gemini API exception: ' . $e->getMessage());
    errorResponse('エラーが発生しました: ' . $e->getMessage(), 500);
}
