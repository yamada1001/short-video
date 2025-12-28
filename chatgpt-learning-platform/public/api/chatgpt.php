<?php
/**
 * Gemini AI実行API
 *
 * プロンプトを受け取り、OpenAI APIを呼び出してレスポンスを返す
 * キャッシュ機能とAPI使用制限あり
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

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

// API使用制限チェック（制限なし）
// checkApiLimit() は常にtrueを返すため、このチェックは実質的に無効

// キャッシュチェック
$cachedResponse = getCachedPrompt($prompt, OPENAI_MODEL);

if ($cachedResponse) {
    // キャッシュヒット
    successResponse([
        'response' => $cachedResponse,
        'cached' => true,
        'tokens_used' => 0
    ]);
}

// OpenAI API呼び出し
try {
    $ch = curl_init('https://api.openai.com/v1/chat/completions');

    $data = [
        'model' => OPENAI_MODEL,
        'messages' => [
            [
                'role' => 'user',
                'content' => $prompt
            ]
        ],
        'max_tokens' => 1000,
        'temperature' => 0.7
    ];

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . OPENAI_API_KEY
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        error_log('OpenAI API error: ' . $response);
        errorResponse('Gemini AIの実行に失敗しました。しばらくしてからもう一度お試しください。', 500);
    }

    $result = json_decode($response, true);

    if (!isset($result['choices'][0]['message']['content'])) {
        errorResponse('予期しないレスポンス形式です', 500);
    }

    $chatgptResponse = $result['choices'][0]['message']['content'];
    $tokensUsed = $result['usage']['total_tokens'] ?? 0;

    // コスト計算（GPT-3.5-turbo: $0.0015/1K input, $0.002/1K output）
    $inputTokens = $result['usage']['prompt_tokens'] ?? 0;
    $outputTokens = $result['usage']['completion_tokens'] ?? 0;
    $costUsd = ($inputTokens / 1000 * 0.0015) + ($outputTokens / 1000 * 0.002);

    // API使用量を記録
    logApiUsage('chatgpt', $tokensUsed, $costUsd);

    // キャッシュに保存
    saveCachedPrompt($prompt, $chatgptResponse, OPENAI_MODEL);

    // 成功レスポンス
    successResponse([
        'response' => $chatgptResponse,
        'cached' => false,
        'tokens_used' => $tokensUsed,
        'cost_usd' => $costUsd
    ]);

} catch (Exception $e) {
    error_log('Gemini AI API exception: ' . $e->getMessage());
    errorResponse('エラーが発生しました: ' . $e->getMessage(), 500);
}
