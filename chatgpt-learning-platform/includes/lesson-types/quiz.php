<?php
/**
 * クイズ形式レッスン
 */
$questions = $content['questions'] ?? [];
$passingScore = $content['passing_score'] ?? 80; // 合格ライン（%）
?>

<div class="lesson-quiz">
    <div class="quiz-header">
        <h2>✏️ クイズ</h2>
        <p>以下の問題に答えてください。合格ライン: <?= $passingScore ?>%</p>
    </div>

    <form id="quizForm" class="quiz-form">
        <?php foreach ($questions as $index => $question): ?>
            <div class="quiz-question" data-question="<?= $index ?>">
                <div class="question-header">
                    <span class="question-number">問題 <?= $index + 1 ?></span>
                </div>

                <h3 class="question-text"><?= h($question['question']) ?></h3>

                <?php if ($question['type'] === 'multiple'): ?>
                    <!-- 選択式 -->
                    <div class="question-options">
                        <?php foreach ($question['options'] as $optIndex => $option): ?>
                            <label class="option-label">
                                <input type="checkbox" name="question_<?= $index ?>[]" value="<?= $optIndex ?>">
                                <span class="option-text"><?= h($option) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>

                <?php elseif ($question['type'] === 'text'): ?>
                    <!-- 記述式 -->
                    <textarea name="question_<?= $index ?>" class="question-textarea" placeholder="回答を入力してください" rows="4"></textarea>

                <?php endif; ?>

                <div class="question-result" id="result_<?= $index ?>" style="display: none;"></div>
            </div>
        <?php endforeach; ?>

        <div class="quiz-actions">
            <button type="submit" class="btn btn-primary btn-lg">答え合わせ</button>
        </div>
    </form>

    <!-- 結果表示 -->
    <div id="quizResult" class="quiz-result" style="display: none;">
        <div class="result-summary">
            <h2 id="resultTitle"></h2>
            <div class="result-score">
                <div class="score-circle">
                    <span id="scorePercent" class="score-number"></span>%
                </div>
                <p id="scoreText"></p>
            </div>
        </div>

        <div class="result-actions">
            <button id="retryBtn" class="btn btn-outline">もう一度挑戦</button>
            <button id="reviewBtn" class="btn btn-primary">解説を見る</button>
        </div>
    </div>
</div>

<script>
const quizData = <?= json_encode($questions) ?>;
const passingScore = <?= $passingScore ?>;

document.getElementById('quizForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const answers = [];

    // 回答を収集
    quizData.forEach((question, index) => {
        if (question.type === 'multiple') {
            const selected = formData.getAll(`question_${index}[]`).map(Number);
            answers.push(selected);
        } else {
            answers.push(formData.get(`question_${index}`));
        }
    });

    try {
        // クイズAPIに送信
        const response = await fetch(`${appUrl}/api/quiz.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                lesson_id: lessonId,
                answers: answers
            })
        });

        const data = await response.json();

        if (data.error) {
            throw new Error(data.error);
        }

        // 結果を表示
        displayResults(data);

    } catch (error) {
        alert('エラーが発生しました: ' + error.message);
    }
});

// 結果表示
function displayResults(data) {
    const { score, max_score, passed, results } = data;
    const percent = Math.round((score / max_score) * 100);

    // 各問題の正誤を表示
    results.forEach((result, index) => {
        const resultDiv = document.getElementById(`result_${index}`);
        resultDiv.style.display = 'block';

        if (result.correct) {
            resultDiv.className = 'question-result correct';
            resultDiv.innerHTML = '<span class="result-icon">✓</span> 正解！';
        } else {
            resultDiv.className = 'question-result incorrect';
            resultDiv.innerHTML = `
                <span class="result-icon">✗</span> 不正解
                ${result.explanation ? `<p class="explanation">${escapeHtml(result.explanation)}</p>` : ''}
            `;
        }
    });

    // 総合結果を表示
    const quizResult = document.getElementById('quizResult');
    const resultTitle = document.getElementById('resultTitle');
    const scorePercent = document.getElementById('scorePercent');
    const scoreText = document.getElementById('scoreText');

    quizResult.style.display = 'block';
    scorePercent.textContent = percent;

    if (passed) {
        resultTitle.textContent = '🎉 合格です！';
        scoreText.textContent = `${score} / ${max_score} 問正解`;
        quizResult.className = 'quiz-result passed';
    } else {
        resultTitle.textContent = '😢 不合格...';
        scoreText.textContent = `${score} / ${max_score} 問正解 （合格ラインには ${Math.ceil((passingScore / 100) * max_score) - score} 問足りません）`;
        quizResult.className = 'quiz-result failed';
    }

    // フォームを非表示
    document.getElementById('quizForm').style.display = 'none';

    // 完了ボタンを有効化（合格時のみ）
    if (passed) {
        document.getElementById('completeBtn').disabled = false;
    }
}

// もう一度挑戦
document.getElementById('retryBtn').addEventListener('click', () => {
    document.getElementById('quizForm').reset();
    document.getElementById('quizForm').style.display = 'block';
    document.getElementById('quizResult').style.display = 'none';

    // 各問題の結果を非表示
    document.querySelectorAll('.question-result').forEach(div => {
        div.style.display = 'none';
    });
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/\n/g, '<br>');
}
</script>
