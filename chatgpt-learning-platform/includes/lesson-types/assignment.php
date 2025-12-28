<?php
/**
 * 課題形式レッスン
 */
$assignment = $content['assignment'] ?? [];
$title = $assignment['title'] ?? '課題';
$description = $assignment['description'] ?? '';
$submissionType = $assignment['submission_type'] ?? 'text'; // text, file, url
?>

<div class="lesson-assignment">
    <div class="assignment-header">
        <h2><i class="fas fa-tasks"></i> <?= h($title) ?></h2>
        <?php if ($description): ?>
            <p class="assignment-description"><?= nl2br(h($description)) ?></p>
        <?php endif; ?>
    </div>

    <div class="assignment-content">
        <form id="assignmentForm" class="assignment-form">
            <?php if ($submissionType === 'text'): ?>
                <!-- テキスト提出 -->
                <div class="form-group">
                    <label for="assignmentText">回答を入力してください</label>
                    <textarea id="assignmentText"
                              name="submission_text"
                              class="form-control"
                              rows="10"
                              placeholder="ここに回答を入力してください"
                              required></textarea>
                </div>

            <?php elseif ($submissionType === 'file'): ?>
                <!-- ファイル提出 -->
                <div class="form-group">
                    <label for="assignmentFile">ファイルをアップロード</label>
                    <input type="file"
                           id="assignmentFile"
                           name="submission_file"
                           class="form-control"
                           required>
                    <small class="form-text text-muted">
                        対応ファイル: PDF, Word, Excel, PowerPoint, 画像ファイル（最大10MB）
                    </small>
                </div>

            <?php elseif ($submissionType === 'url'): ?>
                <!-- URL提出 -->
                <div class="form-group">
                    <label for="assignmentUrl">作成物のURLを入力してください</label>
                    <input type="url"
                           id="assignmentUrl"
                           name="submission_url"
                           class="form-control"
                           placeholder="https://example.com/your-work"
                           required>
                    <small class="form-text text-muted">
                        Google ドキュメント、GitHub、その他のURLを入力
                    </small>
                </div>
            <?php endif; ?>

            <div class="assignment-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-paper-plane"></i> 提出する
                </button>
            </div>
        </form>

        <!-- 提出済みメッセージ -->
        <div id="submissionSuccess" class="submission-success" style="display: none;">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h3>提出完了しました！</h3>
            <p>課題を提出しました。レビュー後、フィードバックをお送りします。</p>
        </div>
    </div>
</div>

<script>
document.getElementById('assignmentForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    formData.append('lesson_id', lessonId);

    try {
        // 課題提出API（今後実装予定）
        // const response = await fetch(`${appUrl}/public/api/submit-assignment.php`, {
        //     method: 'POST',
        //     body: formData
        // });

        // 仮実装: 成功メッセージを表示
        document.getElementById('assignmentForm').style.display = 'none';
        document.getElementById('submissionSuccess').style.display = 'block';

        // 完了ボタンを有効化
        document.getElementById('completeBtn').disabled = false;

    } catch (error) {
        alert('エラーが発生しました: ' + error.message);
    }
});
</script>
