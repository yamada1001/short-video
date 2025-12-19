<?php
/**
 * 課題形式レッスン
 */
$task = $content['task'] ?? '課題を完成させてください。';
$criteria = $content['criteria'] ?? '';
$hints = $content['hints'] ?? [];

// 既に提出済みの課題を取得
$submittedSql = "SELECT * FROM assignments WHERE user_id = ? AND lesson_id = ? ORDER BY created_at DESC LIMIT 1";
$submitted = db()->fetchOne($submittedSql, [$user['id'], $lessonId]);
?>

<div class="lesson-assignment">
    <!-- 課題説明 -->
    <div class="assignment-description">
        <h2>📝 課題</h2>
        <p><?= nl2br(h($task)) ?></p>

        <?php if ($criteria): ?>
            <div class="criteria-box">
                <h4>📊 評価基準</h4>
                <p><?= nl2br(h($criteria)) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($hints)): ?>
            <div class="hints-box">
                <h4>💡 ヒント</h4>
                <ul>
                    <?php foreach ($hints as $hint): ?>
                        <li><?= h($hint) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($submitted): ?>
        <!-- 提出済み課題の表示 -->
        <div class="submitted-assignment">
            <h3>✓ 提出済み</h3>
            <p class="submitted-date">提出日時: <?= date('Y年m月d日 H:i', strtotime($submitted['created_at'])) ?></p>

            <div class="submitted-content">
                <h4>提出したプロンプト:</h4>
                <pre class="submitted-prompt"><?= h($submitted['submitted_prompt']) ?></pre>

                <?php if ($submitted['chatgpt_response']): ?>
                    <h4>Gemini AIの応答:</h4>
                    <div class="submitted-response"><?= nl2br(h($submitted['chatgpt_response'])) ?></div>
                <?php endif; ?>

                <?php if ($submitted['status'] === 'graded'): ?>
                    <div class="assignment-grade <?= $submitted['score'] >= 70 ? 'passed' : 'failed' ?>">
                        <h4>採点結果</h4>
                        <div class="grade-score"><?= $submitted['score'] ?>点 / 100点</div>
                        <?php if ($submitted['feedback']): ?>
                            <div class="grade-feedback">
                                <h5>フィードバック:</h5>
                                <p><?= nl2br(h($submitted['feedback'])) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="assignment-pending">
                        <p>⏳ 採点待ちです...</p>
                    </div>
                <?php endif; ?>
            </div>

            <button id="resubmitBtn" class="btn btn-outline">再提出する</button>
        </div>
    <?php endif; ?>

    <!-- 課題提出フォーム -->
    <div id="assignmentForm" class="assignment-form" style="<?= $submitted ? 'display: none;' : '' ?>">
        <h3>課題の提出</h3>
        <p>Gemini AIに送信するプロンプトを作成してください。実行結果と共に提出されます。</p>

        <div class="form-group">
            <label for="assignmentPrompt">プロンプト</label>
            <textarea id="assignmentPrompt" class="assignment-textarea" rows="8" placeholder="課題に沿ったプロンプトを入力してください..."></textarea>
            <div class="char-count">
                <span id="assignmentCharCount">0</span> 文字
            </div>
        </div>

        <div class="form-actions">
            <button id="testPromptBtn" class="btn btn-outline">
                <span class="btn-icon">▶</span>
                テスト実行
            </button>
            <button id="submitAssignmentBtn" class="btn btn-primary">
                提出する
            </button>
        </div>

        <!-- テスト実行結果 -->
        <div id="testOutput" class="test-output" style="display: none;">
            <h4>テスト実行結果</h4>
            <div id="testResult" class="test-result"></div>
        </div>
    </div>
</div>

<script>
const assignmentPrompt = document.getElementById('assignmentPrompt');
const assignmentCharCount = document.getElementById('assignmentCharCount');
const testPromptBtn = document.getElementById('testPromptBtn');
const submitAssignmentBtn = document.getElementById('submitAssignmentBtn');
const testOutput = document.getElementById('testOutput');
const testResult = document.getElementById('testResult');
const assignmentForm = document.getElementById('assignmentForm');
const resubmitBtn = document.getElementById('resubmitBtn');

let lastTestResponse = null;

// 文字数カウント
if (assignmentPrompt) {
    assignmentPrompt.addEventListener('input', () => {
        assignmentCharCount.textContent = assignmentPrompt.value.length;
    });
}

// 再提出ボタン
if (resubmitBtn) {
    resubmitBtn.addEventListener('click', () => {
        document.querySelector('.submitted-assignment').style.display = 'none';
        assignmentForm.style.display = 'block';
    });
}

// テスト実行
if (testPromptBtn) {
    testPromptBtn.addEventListener('click', async () => {
        const prompt = assignmentPrompt.value.trim();

        if (!prompt) {
            alert('プロンプトを入力してください');
            return;
        }

        testPromptBtn.disabled = true;
        testResult.innerHTML = '<p class="loading">実行中...</p>';
        testOutput.style.display = 'block';

        try {
            const response = await fetch(`${appUrl}/api/chatgpt.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    prompt: prompt,
                    lesson_id: lessonId
                })
            });

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            lastTestResponse = data.response;

            testResult.innerHTML = `
                <div class="test-success">
                    <p><strong>Gemini AIの応答:</strong></p>
                    <div class="response-text">${escapeHtml(data.response)}</div>
                    <p class="text-muted">トークン数: ${data.tokens_used}</p>
                </div>
            `;

        } catch (error) {
            testResult.innerHTML = `
                <div class="test-error">
                    <p><strong>エラー:</strong> ${escapeHtml(error.message)}</p>
                </div>
            `;
        } finally {
            testPromptBtn.disabled = false;
        }
    });
}

// 課題提出
if (submitAssignmentBtn) {
    submitAssignmentBtn.addEventListener('click', async () => {
        const prompt = assignmentPrompt.value.trim();

        if (!prompt) {
            alert('プロンプトを入力してください');
            return;
        }

        if (!confirm('この内容で提出しますか？提出後は採点されます。')) {
            return;
        }

        submitAssignmentBtn.disabled = true;

        try {
            const response = await fetch(`${appUrl}/api/assignment.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    lesson_id: lessonId,
                    prompt: prompt
                })
            });

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            alert('課題を提出しました！採点をお待ちください。');
            location.reload();

        } catch (error) {
            alert('提出に失敗しました: ' + error.message);
            submitAssignmentBtn.disabled = false;
        }
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/\n/g, '<br>');
}
</script>
