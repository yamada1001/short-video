<?php
/**
 * レッスン詳細ページ
 * 4つのタイプに対応: slide, editor, quiz, assignment
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();
$lessonId = $_GET['id'] ?? null;

if (!$lessonId) {
    redirect(APP_URL . '/dashboard.php');
}

// レッスン情報を取得
$sql = "SELECT l.*, c.id as course_id, c.title as course_title
        FROM lessons l
        JOIN courses c ON l.course_id = c.id
        WHERE l.id = ?";
$lesson = db()->fetchOne($sql, [$lessonId]);

if (!$lesson) {
    redirect(APP_URL . '/dashboard.php');
}

// コースへのアクセス権チェック
if (!canAccessCourse($lesson['course_id'])) {
    redirect(APP_URL . '/course.php?id=' . $lesson['course_id']);
}

// 進捗を「進行中」に更新（初回のみ）
$progressSql = "SELECT status FROM user_progress WHERE user_id = ? AND lesson_id = ?";
$progress = db()->fetchOne($progressSql, [$user['id'], $lessonId]);

if (!$progress) {
    updateProgress($lessonId, 'in_progress');
}

// レッスンコンテンツをJSONデコード
$content = json_decode($lesson['content_json'], true);

// 次のレッスン・前のレッスンを取得
$nextLessonSql = "SELECT id FROM lessons WHERE course_id = ? AND order_num > ? ORDER BY order_num LIMIT 1";
$nextLesson = db()->fetchOne($nextLessonSql, [$lesson['course_id'], $lesson['order_num']]);

$prevLessonSql = "SELECT id FROM lessons WHERE course_id = ? AND order_num < ? ORDER BY order_num DESC LIMIT 1";
$prevLesson = db()->fetchOne($prevLessonSql, [$lesson['course_id'], $lesson['order_num']]);
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
    <title><?= h($lesson['title']) ?> | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body class="lesson-page">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="lesson-layout">
        <!-- サイドバー -->
        <aside class="lesson-sidebar">
            <div class="sidebar-header">
                <a href="<?= APP_URL ?>/course.php?id=<?= $lesson['course_id'] ?>" class="back-link">
                    ← コースに戻る
                </a>
                <h3><?= h($lesson['course_title']) ?></h3>
            </div>

            <div class="sidebar-lesson-info">
                <h2><?= h($lesson['title']) ?></h2>
                <?php if ($lesson['description']): ?>
                    <p class="lesson-desc"><?= h($lesson['description']) ?></p>
                <?php endif; ?>
            </div>

            <!-- ナビゲーション -->
            <div class="lesson-navigation">
                <?php if ($prevLesson): ?>
                    <a href="<?= APP_URL ?>/lesson.php?id=<?= $prevLesson['id'] ?>" class="nav-btn nav-prev">
                        ← 前のレッスン
                    </a>
                <?php endif; ?>

                <?php if ($nextLesson): ?>
                    <a href="<?= APP_URL ?>/lesson.php?id=<?= $nextLesson['id'] ?>" class="nav-btn nav-next">
                        次のレッスン →
                    </a>
                <?php endif; ?>
            </div>

            <!-- ヘルプボタン -->
            <button id="helpBtn" class="btn btn-help btn-block">
                <i class="fas fa-question-circle"></i> わからないことがあれば質問
            </button>

            <button id="completeBtn" class="btn btn-success btn-block">
                <i class="fas fa-check"></i> 完了にする
            </button>
        </aside>

        <!-- メインコンテンツ -->
        <main class="lesson-main">
            <?php if ($lesson['lesson_type'] === 'slide'): ?>
                <!-- スライド形式 -->
                <?php include __DIR__ . '/../includes/lesson-types/slide.php'; ?>

            <?php elseif ($lesson['lesson_type'] === 'editor'): ?>
                <!-- エディタ形式 -->
                <?php include __DIR__ . '/../includes/lesson-types/editor.php'; ?>

            <?php elseif ($lesson['lesson_type'] === 'quiz'): ?>
                <!-- クイズ形式 -->
                <?php include __DIR__ . '/../includes/lesson-types/quiz.php'; ?>

            <?php elseif ($lesson['lesson_type'] === 'assignment'): ?>
                <!-- 課題形式 -->
                <?php include __DIR__ . '/../includes/lesson-types/assignment.php'; ?>

            <?php endif; ?>
        </main>
    </div>

    <!-- フィードバックモーダル -->
    <div id="feedbackModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-question-circle"></i> ヘルプ・フィードバック</h2>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <p class="modal-description">
                    わからないことがあれば、お気軽にご質問ください。<br>
                    運営チームが確認次第、返信いたします（通常1〜3営業日）。
                </p>

                <form id="feedbackForm">
                    <div class="form-group">
                        <label for="feedbackType">フィードバックの種類</label>
                        <select id="feedbackType" name="feedback_type" class="form-control" required>
                            <option value="question">質問（わからないことがある）</option>
                            <option value="bug">バグ報告（エラーが発生した）</option>
                            <option value="request">要望（こんな機能がほしい）</option>
                            <option value="other">その他</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="feedbackMessage">メッセージ（5文字以上）</label>
                        <textarea id="feedbackMessage"
                                  name="message"
                                  class="form-control"
                                  rows="6"
                                  placeholder="できるだけ具体的に記入してください。&#10;&#10;例:&#10;・◯◯の部分がわかりません&#10;・◯◯を実行したら「エラー」と表示されました&#10;・◯◯の機能があると便利だと思います"
                                  required></textarea>
                        <div class="char-count">
                            <span id="charCount">0</span> / 5文字以上
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelBtn">キャンセル</button>
                        <button type="submit" class="btn btn-primary" id="submitFeedbackBtn">
                            送信する
                        </button>
                    </div>
                </form>

                <div class="feedback-link">
                    <a href="<?= APP_URL ?>/my-feedbacks.php"><i class="fas fa-inbox"></i> 過去のフィードバックを見る</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // レッスン設定をグローバルに設定
        window.lessonConfig = {
            lessonId: <?= $lessonId ?>,
            appUrl: '<?= APP_URL ?>',
            courseId: <?= $lesson['course_id'] ?>
        };
    </script>

    <!-- フィードバックモーダル制御 -->
    <script>
        // モーダル要素
        const modal = document.getElementById('feedbackModal');
        const helpBtn = document.getElementById('helpBtn');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const feedbackForm = document.getElementById('feedbackForm');
        const feedbackMessage = document.getElementById('feedbackMessage');
        const charCount = document.getElementById('charCount');

        // モーダルを開く
        helpBtn.addEventListener('click', function() {
            modal.style.display = 'flex';
            feedbackMessage.focus();
        });

        // モーダルを閉じる
        function closeModalFunc() {
            modal.style.display = 'none';
            feedbackForm.reset();
            charCount.textContent = '0';
        }

        closeModal.addEventListener('click', closeModalFunc);
        cancelBtn.addEventListener('click', closeModalFunc);

        // モーダル外クリックで閉じる
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModalFunc();
            }
        });

        // ESCキーで閉じる
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModalFunc();
            }
        });

        // 文字数カウント
        feedbackMessage.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // フィードバック送信
        feedbackForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const message = feedbackMessage.value.trim();
            if (message.length < 5) {
                alert('メッセージを5文字以上入力してください');
                return;
            }

            const feedbackType = document.getElementById('feedbackType').value;
            const submitBtn = document.getElementById('submitFeedbackBtn');

            // ボタンを無効化
            submitBtn.disabled = true;
            submitBtn.textContent = '送信中...';

            try {
                const response = await fetch(window.lessonConfig.appUrl + '/api/submit-feedback.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        lesson_id: window.lessonConfig.lessonId,
                        feedback_type: feedbackType,
                        message: message
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(result.message || 'フィードバックを送信しました！');
                    closeModalFunc();
                } else {
                    alert('エラー: ' + (result.message || 'フィードバックの送信に失敗しました'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = '送信する';
                }
            } catch (error) {
                alert('通信エラーが発生しました: ' + error.message);
                submitBtn.disabled = false;
                submitBtn.textContent = '送信する';
            }
        });
    </script>

    <script>
        // lesson.jsで使用する変数を定義
        const appUrl = '<?= APP_URL ?>';
        const lessonId = <?= $lessonId ?>;
        const courseId = <?= $lesson['course_id'] ?>;
    </script>
    <script src="<?= APP_URL ?>/assets/js/lesson.js"></script>
</body>
</html>
