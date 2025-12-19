<?php
/**
 * クイズAPI
 *
 * ユーザーのクイズ回答を採点して結果を返す
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
$userAnswers = $input['answers'] ?? [];

// バリデーション
if (!$lessonId) {
    errorResponse('レッスンIDが必要です');
}

// レッスン情報を取得
$lessonSql = "SELECT * FROM lessons WHERE id = ? AND lesson_type = 'quiz'";
$lesson = db()->fetchOne($lessonSql, [$lessonId]);

if (!$lesson) {
    errorResponse('クイズが見つかりません', 404);
}

// クイズデータを取得
$quizData = json_decode($lesson['content_json'], true);
$questions = $quizData['questions'] ?? [];
$passingScore = $quizData['passing_score'] ?? 80;

// 採点
$score = 0;
$maxScore = count($questions);
$results = [];

foreach ($questions as $index => $question) {
    $userAnswer = $userAnswers[$index] ?? null;
    $correctAnswer = $question['correct'] ?? [];
    $isCorrect = false;

    if ($question['type'] === 'multiple') {
        // 選択式: 配列を比較
        sort($userAnswer);
        sort($correctAnswer);
        $isCorrect = $userAnswer == $correctAnswer;

    } elseif ($question['type'] === 'text') {
        // 記述式: Gemini AIで採点
        // TODO: Gemini AIによる記述式採点を実装
        // 現在は仮実装として、キーワード一致で判定
        if (isset($question['keywords'])) {
            $keywords = $question['keywords'];
            $isCorrect = true;
            foreach ($keywords as $keyword) {
                if (stripos($userAnswer, $keyword) === false) {
                    $isCorrect = false;
                    break;
                }
            }
        }
    }

    if ($isCorrect) {
        $score++;
    }

    $results[] = [
        'correct' => $isCorrect,
        'explanation' => $question['explanation'] ?? null
    ];
}

// 合格判定
$scorePercent = ($score / $maxScore) * 100;
$passed = $scorePercent >= $passingScore;

// クイズ結果を保存
$saveSql = "INSERT INTO quiz_results (user_id, lesson_id, score, max_score, answers_json, passed)
            VALUES (?, ?, ?, ?, ?, ?)";
db()->execute($saveSql, [
    $user['id'],
    $lessonId,
    $score,
    $maxScore,
    json_encode($userAnswers),
    $passed
]);

// 合格していたら進捗を「完了」に更新
if ($passed) {
    updateProgress($lessonId, 'completed');
}

// 結果を返す
successResponse([
    'score' => $score,
    'max_score' => $maxScore,
    'score_percent' => round($scorePercent, 1),
    'passed' => $passed,
    'passing_score' => $passingScore,
    'results' => $results
]);
