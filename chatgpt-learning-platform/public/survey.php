<?php
/**
 * アンケートページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();

// 既に回答済みかチェック
if ($user['survey_completed_at']) {
    // 回答済みの場合は編集モード
    $isEdit = true;
} else {
    $isEdit = false;
}

// アンケート質問を取得
$questionsSql = "SELECT * FROM survey_questions ORDER BY display_order";
$questions = db()->fetchAll($questionsSql);

// 既存の回答を取得（編集モードの場合）
$userAnswers = [];
if ($isEdit) {
    $answersSql = "SELECT question_id, answer_value FROM user_survey_responses WHERE user_id = ?";
    $answers = db()->fetchAll($answersSql, [$user['id']]);
    foreach ($answers as $answer) {
        $userAnswers[$answer['question_id']] = $answer['answer_value'];
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-T7NGQDC2');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>学習目的診断アンケート | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="survey-page">
        <div class="container">
            <div class="survey-header">
                <h1><?= $isEdit ? 'アンケート回答を編集' : '学習目的診断アンケート' ?></h1>
                <p class="survey-description">
                    <?php if ($isEdit): ?>
                        あなたの回答内容を確認・変更できます。
                    <?php else: ?>
                        あなたに最適な学習コースをおすすめするため、簡単なアンケートにご協力ください。<br>
                        所要時間: 約3分
                    <?php endif; ?>
                </p>
            </div>

            <form id="surveyForm" class="survey-form">
                <?php foreach ($questions as $question): ?>
                    <?php
                    $options = json_decode($question['options'], true);
                    $currentAnswer = $userAnswers[$question['id']] ?? null;
                    
                    // JSON文字列の場合はデコード（複数選択の場合）
                    if ($currentAnswer && $question['question_type'] === 'multiple') {
                        $currentAnswerArray = json_decode($currentAnswer, true) ?: [];
                    }
                    ?>
                    
                    <div class="survey-question">
                        <div class="question-header">
                            <span class="question-number">Q<?= $question['display_order'] ?></span>
                            <h3 class="question-text"><?= h($question['question_text']) ?></h3>
                            <?php if ($question['question_type'] === 'text' && strpos($question['question_text'], '任意') !== false): ?>
                                <span class="optional-badge">任意</span>
                            <?php endif; ?>
                        </div>

                        <div class="question-body">
                            <?php if ($question['question_type'] === 'single'): ?>
                                <!-- 単一選択 -->
                                <?php foreach ($options as $index => $option): ?>
                                    <label class="radio-option">
                                        <input type="radio" 
                                               name="q_<?= $question['id'] ?>" 
                                               value="<?= h($option) ?>"
                                               <?= ($currentAnswer === $option) ? 'checked' : '' ?>>
                                        <span class="radio-label"><?= h($option) ?></span>
                                    </label>
                                <?php endforeach; ?>

                            <?php elseif ($question['question_type'] === 'multiple'): ?>
                                <!-- 複数選択 -->
                                <?php foreach ($options as $index => $option): ?>
                                    <label class="checkbox-option">
                                        <input type="checkbox" 
                                               name="q_<?= $question['id'] ?>[]" 
                                               value="<?= h($option) ?>"
                                               <?= (isset($currentAnswerArray) && in_array($option, $currentAnswerArray)) ? 'checked' : '' ?>>
                                        <span class="checkbox-label"><?= h($option) ?></span>
                                    </label>
                                <?php endforeach; ?>

                            <?php elseif ($question['question_type'] === 'text'): ?>
                                <!-- テキスト入力 -->
                                <textarea name="q_<?= $question['id'] ?>" 
                                          class="text-input" 
                                          rows="4" 
                                          placeholder="自由に記入してください（任意）"><?= h($currentAnswer ?? '') ?></textarea>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="survey-footer">
                    <button type="submit" class="btn btn-primary btn-large">
                        <?= $isEdit ? '回答を更新する' : '回答を送信する' ?>
                    </button>
                    <p class="survey-note">
                        ※ 回答内容はあなたに最適なコースの推薦に活用されます。<br>
                        ※ 後からいつでも回答内容を変更できます。
                    </p>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script>
        // アンケートフォーム送信処理
        document.getElementById('surveyForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const answers = {};

            // フォームデータを整形
            for (const [key, value] of formData.entries()) {
                const questionId = key.replace('q_', '').replace('[]', '');
                
                if (key.endsWith('[]')) {
                    // 複数選択の場合
                    if (!answers[questionId]) {
                        answers[questionId] = [];
                    }
                    answers[questionId].push(value);
                } else {
                    // 単一選択 or テキスト
                    answers[questionId] = value;
                }
            }

            // 複数選択の回答をJSON文字列に変換
            for (const questionId in answers) {
                if (Array.isArray(answers[questionId])) {
                    answers[questionId] = JSON.stringify(answers[questionId]);
                }
            }

            try {
                const response = await fetch('<?= APP_URL ?>/api/save-survey.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ answers })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message || '回答を保存しました！');
                    window.location.href = '<?= APP_URL ?>/dashboard.php';
                } else {
                    alert('エラー: ' + (result.message || '回答の保存に失敗しました'));
                }
            } catch (error) {
                alert('通信エラーが発生しました: ' + error.message);
            }
        });
    </script>
</body>
</html>
