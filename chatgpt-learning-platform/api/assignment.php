<?php
/**
 * 課題提出API
 *
 * ユーザーが作成したプロンプトを実行して課題として提出
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

$user = getCurrentUser();

// JSONリクエストを取得
$input = json_decode(file_get_contents('php://input'), true);
$lessonId = $input['lesson_id'] ?? null;
$prompt = $input['prompt'] ?? '';

// バリデーション
if (!$lessonId) {
    errorResponse('レッスンIDが必要です');
}

if (empty($prompt)) {
    errorResponse('プロンプトを入力してください');
}

// レッスン情報を取得
$lessonSql = "SELECT * FROM lessons WHERE id = ? AND lesson_type = 'assignment'";
$lesson = db()->fetchOne($lessonSql, [$lessonId]);

if (!$lesson) {
    errorResponse('課題が見つかりません', 404);
}

// API使用制限チェック
if (!checkApiLimit()) {
    $limit = hasActiveSubscription() ? API_LIMIT_PREMIUM : API_LIMIT_FREE;
    errorResponse("本日のAPI使用回数の上限（{$limit}回）に達しました", 429);
}

// ChatGPTを実行
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
        errorResponse('ChatGPTの実行に失敗しました', 500);
    }

    $result = json_decode($response, true);
    $chatgptResponse = $result['choices'][0]['message']['content'] ?? '';
    $tokensUsed = $result['usage']['total_tokens'] ?? 0;

    // コスト計算
    $inputTokens = $result['usage']['prompt_tokens'] ?? 0;
    $outputTokens = $result['usage']['completion_tokens'] ?? 0;
    $costUsd = ($inputTokens / 1000 * 0.0015) + ($outputTokens / 1000 * 0.002);

    // API使用量を記録
    logApiUsage('assignment', $tokensUsed, $costUsd);

    // 課題を保存
    $saveSql = "INSERT INTO assignments (user_id, lesson_id, submitted_prompt, chatgpt_response, status)
                VALUES (?, ?, ?, ?, 'submitted')";

    if (db()->execute($saveSql, [$user['id'], $lessonId, $prompt, $chatgptResponse])) {
        $assignmentId = db()->lastInsertId();

        // 進捗を更新（提出済み = 進行中）
        updateProgress($lessonId, 'in_progress');

        // TODO: 自動採点機能を実装
        // ChatGPTを使って自動採点することも可能
        // 現在は管理者が手動で採点する想定

        successResponse([
            'assignment_id' => $assignmentId,
            'message' => '課題を提出しました。採点をお待ちください。'
        ]);
    } else {
        errorResponse('課題の保存に失敗しました', 500);
    }

} catch (Exception $e) {
    error_log('Assignment API exception: ' . $e->getMessage());
    errorResponse('エラーが発生しました', 500);
}
