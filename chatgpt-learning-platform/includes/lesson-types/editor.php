<?php
/**
 * エディタ形式レッスン
 */
$editor = $content['editor'] ?? [];
$title = $editor['title'] ?? 'コードエディタ';
$instructions = $editor['instructions'] ?? '';
$language = $editor['language'] ?? 'javascript';
$initialCode = $editor['initial_code'] ?? '';
$expectedOutput = $editor['expected_output'] ?? '';
?>

<div class="lesson-editor">
    <div class="editor-header">
        <h2><i class="fas fa-code"></i> <?= h($title) ?></h2>
        <?php if ($instructions): ?>
            <div class="editor-instructions">
                <?= nl2br(h($instructions)) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="editor-workspace">
        <div class="editor-container">
            <div class="editor-toolbar">
                <span class="editor-label"><i class="fas fa-file-code"></i> コードエディタ (<?= h($language) ?>)</span>
                <button id="runCode" class="btn btn-success btn-sm">
                    <i class="fas fa-play"></i> 実行
                </button>
            </div>
            <textarea id="codeEditor" class="code-editor"><?= h($initialCode) ?></textarea>
        </div>

        <div class="output-container">
            <div class="output-toolbar">
                <span class="output-label"><i class="fas fa-terminal"></i> 実行結果</span>
                <button id="clearOutput" class="btn btn-secondary btn-sm">
                    <i class="fas fa-trash"></i> クリア
                </button>
            </div>
            <pre id="codeOutput" class="code-output"></pre>
        </div>
    </div>

    <?php if ($expectedOutput): ?>
        <div class="expected-output">
            <h4><i class="fas fa-check-double"></i> 期待される出力</h4>
            <pre><?= h($expectedOutput) ?></pre>
        </div>
    <?php endif; ?>

    <div class="editor-actions">
        <button id="checkAnswer" class="btn btn-primary btn-lg">
            <i class="fas fa-check"></i> 答え合わせ
        </button>
    </div>
</div>

<style>
.lesson-editor {
    max-width: 100%;
}

.editor-workspace {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin: 20px 0;
}

@media (max-width: 768px) {
    .editor-workspace {
        grid-template-columns: 1fr;
    }
}

.editor-container,
.output-container {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.editor-toolbar,
.output-toolbar {
    background: #f5f5f5;
    padding: 10px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #ddd;
}

.code-editor {
    width: 100%;
    min-height: 400px;
    padding: 15px;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    border: none;
    resize: vertical;
}

.code-output {
    min-height: 400px;
    padding: 15px;
    margin: 0;
    font-family: 'Courier New', monospace;
    font-size: 14px;
    background: #1e1e1e;
    color: #d4d4d4;
    overflow-x: auto;
}

.expected-output {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin: 20px 0;
}

.expected-output pre {
    background: white;
    padding: 15px;
    border-radius: 4px;
    margin-top: 10px;
}
</style>

<script>
// コード実行機能（簡易版）
document.getElementById('runCode').addEventListener('click', () => {
    const code = document.getElementById('codeEditor').value;
    const output = document.getElementById('codeOutput');

    try {
        // JavaScriptのコードを実行（注意: eval使用は本番環境では推奨されません）
        // 本番環境ではサーバーサイドでサンドボックス実行を推奨
        const originalLog = console.log;
        let capturedOutput = [];

        console.log = function(...args) {
            capturedOutput.push(args.map(arg =>
                typeof arg === 'object' ? JSON.stringify(arg, null, 2) : String(arg)
            ).join(' '));
            originalLog.apply(console, args);
        };

        eval(code);

        console.log = originalLog;

        output.textContent = capturedOutput.length > 0
            ? capturedOutput.join('\n')
            : '実行完了（出力なし）';

    } catch (error) {
        output.textContent = `エラー: ${error.message}`;
    }
});

// 出力クリア
document.getElementById('clearOutput').addEventListener('click', () => {
    document.getElementById('codeOutput').textContent = '';
});

// 答え合わせ
document.getElementById('checkAnswer').addEventListener('click', () => {
    const code = document.getElementById('codeEditor').value;
    const output = document.getElementById('codeOutput').textContent;

    // 簡易チェック（実際にはAPIで検証すべき）
    <?php if ($expectedOutput): ?>
    const expected = <?= json_encode($expectedOutput) ?>;
    if (output.trim() === expected.trim()) {
        alert('正解です！期待通りの出力が得られました。');
        document.getElementById('completeBtn').disabled = false;
    } else {
        alert('出力が期待と異なります。もう一度試してみてください。');
    }
    <?php else: ?>
    alert('コードを確認しました。完了ボタンを押して次に進めます。');
    document.getElementById('completeBtn').disabled = false;
    <?php endif; ?>
});
</script>
