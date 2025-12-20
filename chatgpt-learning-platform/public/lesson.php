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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($lesson['title']) ?> | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body class="lesson-page">
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

            <button id="completeBtn" class="btn btn-success btn-block">
                ✓ 完了にする
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

    <script>
        // レッスンID
        const lessonId = <?= $lessonId ?>;
        const appUrl = '<?= APP_URL ?>';
        const courseId = <?= $lesson['course_id'] ?>;
    </script>
    <script src="<?= APP_URL ?>/assets/js/lesson.js"></script>
</body>
</html>
