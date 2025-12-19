<?php
/**
 * エディタ形式レッスン（ChatGPT実行）
 */
$instructions = $content['instructions'] ?? 'プロンプトを入力してChatGPTを実行してみましょう。';
$hint = $content['hint'] ?? '';
$examplePrompt = $content['example'] ?? '';
?>

<div class="lesson-editor">
    <!-- 説明セクション -->
    <div class="editor-instructions">
        <h2>📚 やること</h2>
        <p><?= nl2br(h($instructions)) ?></p>

        <?php if ($hint): ?>
            <div class="hint-box">
                <h4>💡 ヒント</h4>
                <p><?= nl2br(h($hint)) ?></p>
            </div>
        <?php endif; ?>

        <?php if ($examplePrompt): ?>
            <div class="example-box">
                <h4>📝 プロンプト例</h4>
                <pre><code><?= h($examplePrompt) ?></code></pre>
                <button id="useExampleBtn" class="btn btn-sm btn-outline">この例を使う</button>
            </div>
        <?php endif; ?>
    </div>

    <!-- エディタセクション -->
    <div class="editor-workspace">
        <div class="editor-panel">
            <div class="panel-header">
                <h3>💬 プロンプト入力</h3>
                <div class="editor-stats">
                    <span id="charCount">0</span> 文字
                </div>
            </div>
            <textarea id="promptEditor" class="prompt-editor" placeholder="ここにプロンプトを入力してください..."></textarea>
            <div class="editor-actions">
                <button id="runPromptBtn" class="btn btn-primary btn-lg">
                    <span class="btn-icon">▶</span>
                    実行する
                </button>
                <button id="clearPromptBtn" class="btn btn-outline">クリア</button>
            </div>
        </div>

        <div class="output-panel">
            <div class="panel-header">
                <h3>🤖 ChatGPTの応答</h3>
                <div id="loadingIndicator" class="loading-indicator" style="display: none;">
                    <span class="spinner"></span> 実行中...
                </div>
            </div>
            <div id="outputArea" class="output-area">
                <div class="output-placeholder">
                    プロンプトを実行すると、ここにChatGPTの応答が表示されます。
                </div>
            </div>
        </div>
    </div>

    <!-- 実行履歴 -->
    <div class="execution-history">
        <h3>📜 実行履歴</h3>
        <div id="historyList" class="history-list">
            <p class="text-muted">まだ実行履歴がありません</p>
        </div>
    </div>
</div>

<script>
// エディタ機能
const promptEditor = document.getElementById('promptEditor');
const charCount = document.getElementById('charCount');
const runPromptBtn = document.getElementById('runPromptBtn');
const clearPromptBtn = document.getElementById('clearPromptBtn');
const useExampleBtn = document.getElementById('useExampleBtn');
const outputArea = document.getElementById('outputArea');
const loadingIndicator = document.getElementById('loadingIndicator');
const historyList = document.getElementById('historyList');

let executionHistory = [];

// 文字数カウント
promptEditor.addEventListener('input', () => {
    charCount.textContent = promptEditor.value.length;
});

// 例を使う
if (useExampleBtn) {
    useExampleBtn.addEventListener('click', () => {
        promptEditor.value = <?= json_encode($examplePrompt) ?>;
        charCount.textContent = promptEditor.value.length;
        promptEditor.focus();
    });
}

// クリア
clearPromptBtn.addEventListener('click', () => {
    if (confirm('入力内容をクリアしますか？')) {
        promptEditor.value = '';
        charCount.textContent = 0;
    }
});

// プロンプト実行
runPromptBtn.addEventListener('click', async () => {
    const prompt = promptEditor.value.trim();

    if (!prompt) {
        alert('プロンプトを入力してください');
        return;
    }

    // UI更新
    runPromptBtn.disabled = true;
    loadingIndicator.style.display = 'flex';
    outputArea.innerHTML = '<div class="output-placeholder">実行中...</div>';

    try {
        // Gemini API呼び出し（無料枠: 1,500リクエスト/日）
        const response = await fetch(`${appUrl}/api/gemini.php`, {
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

        // 応答を表示
        outputArea.innerHTML = `
            <div class="output-content">
                <div class="output-meta">
                    <span>モデル: ${data.model || 'gemini-1.5-flash'}</span>
                    <span>トークン数: ${data.tokens_used || 0}</span>
                    ${data.cached ? '<span class="badge badge-info">キャッシュ</span>' : ''}
                </div>
                <div class="output-text">${escapeHtml(data.response)}</div>
            </div>
        `;

        // 履歴に追加
        addToHistory(prompt, data.response, data.tokens_used, data.cached);

    } catch (error) {
        outputArea.innerHTML = `
            <div class="output-error">
                <h4>エラーが発生しました</h4>
                <p>${escapeHtml(error.message)}</p>
            </div>
        `;
    } finally {
        runPromptBtn.disabled = false;
        loadingIndicator.style.display = 'none';
    }
});

// 履歴に追加
function addToHistory(prompt, response, tokens, cached) {
    const historyItem = {
        prompt,
        response,
        tokens,
        cached,
        timestamp: new Date()
    };

    executionHistory.unshift(historyItem);

    // 履歴表示を更新
    renderHistory();
}

// 履歴レンダリング
function renderHistory() {
    if (executionHistory.length === 0) {
        historyList.innerHTML = '<p class="text-muted">まだ実行履歴がありません</p>';
        return;
    }

    historyList.innerHTML = executionHistory.map((item, index) => `
        <div class="history-item">
            <div class="history-header">
                <span class="history-time">${formatTime(item.timestamp)}</span>
                ${item.cached ? '<span class="badge badge-info">キャッシュ</span>' : ''}
                <span class="history-tokens">${item.tokens}トークン</span>
            </div>
            <div class="history-prompt">
                <strong>プロンプト:</strong> ${escapeHtml(item.prompt.substring(0, 100))}${item.prompt.length > 100 ? '...' : ''}
            </div>
            <button class="btn btn-sm btn-outline" onclick="restoreHistory(${index})">復元</button>
        </div>
    `).join('');
}

// 履歴を復元
function restoreHistory(index) {
    const item = executionHistory[index];
    promptEditor.value = item.prompt;
    charCount.textContent = item.prompt.length;
    outputArea.innerHTML = `
        <div class="output-content">
            <div class="output-meta">
                <span>トークン数: ${item.tokens}</span>
                <span class="badge badge-info">履歴から復元</span>
            </div>
            <div class="output-text">${escapeHtml(item.response)}</div>
        </div>
    `;
}

// ヘルパー関数
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/\n/g, '<br>');
}

function formatTime(date) {
    return date.toLocaleTimeString('ja-JP', { hour: '2-digit', minute: '2-digit' });
}
</script>
