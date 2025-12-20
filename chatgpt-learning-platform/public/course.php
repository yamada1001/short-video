<?php
/**
 * コース詳細ページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();
$courseId = $_GET['id'] ?? null;

if (!$courseId) {
    redirect(APP_URL . '/dashboard.php');
}

// コース情報を取得
$sql = "SELECT * FROM courses WHERE id = ?";
$course = db()->fetchOne($sql, [$courseId]);

if (!$course) {
    redirect(APP_URL . '/dashboard.php');
}

// アクセス権チェック
if (!canAccessCourse($courseId)) {
    $error = 'このコースはプレミアム会員限定です。';
}

// レッスン一覧を取得
$lessonsSql = "SELECT l.*,
               COALESCE(up.status, 'not_started') as progress_status,
               up.completed_at
               FROM lessons l
               LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = ?
               WHERE l.course_id = ?
               ORDER BY l.order_num";
$lessons = db()->fetchAll($lessonsSql, [$user['id'], $courseId]);

// 進捗率を計算
$totalLessons = count($lessons);
$completedLessons = 0;
foreach ($lessons as $lesson) {
    if ($lesson['progress_status'] === 'completed') {
        $completedLessons++;
    }
}
$progressPercent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($course['title']) ?> | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="course-detail">
        <div class="container">
            <!-- コースヘッダー -->
            <div class="course-header">
                <div class="breadcrumb">
                    <a href="<?= APP_URL ?>/dashboard.php">ダッシュボード</a>
                    <span class="separator">›</span>
                    <span><?= h($course['title']) ?></span>
                </div>

                <div class="course-header-content">
                    <div class="course-header-left">
                        <h1><?= h($course['title']) ?></h1>
                        <p class="course-description"><?= h($course['description']) ?></p>

                        <div class="course-meta">
                            <span class="difficulty difficulty-<?= h($course['difficulty']) ?>">
                                <?php
                                $difficultyLabel = [
                                    'beginner' => '初級',
                                    'intermediate' => '中級',
                                    'advanced' => '上級'
                                ];
                                echo $difficultyLabel[$course['difficulty']] ?? '不明';
                                ?>
                            </span>
                            <span class="lesson-count"><?= $totalLessons ?> レッスン</span>
                            <?php if ($course['is_free']): ?>
                                <span class="badge badge-free">無料</span>
                            <?php else: ?>
                                <span class="badge badge-premium">プレミアム</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="course-header-right">
                        <div class="progress-circle" data-progress="<?= $progressPercent ?>">
                            <svg width="120" height="120">
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#e0e0e0" stroke-width="8"/>
                                <circle cx="60" cy="60" r="54" fill="none" stroke="#4CAF50" stroke-width="8"
                                        stroke-dasharray="<?= 2 * pi() * 54 ?>"
                                        stroke-dashoffset="<?= 2 * pi() * 54 * (1 - $progressPercent / 100) ?>"
                                        transform="rotate(-90 60 60)"/>
                            </svg>
                            <div class="progress-text"><?= $progressPercent ?>%</div>
                        </div>
                        <p class="progress-label"><?= $completedLessons ?> / <?= $totalLessons ?> 完了</p>
                    </div>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?= h($error) ?>
                    <a href="<?= APP_URL ?>/subscribe.php" class="btn btn-sm btn-primary">プレミアムに登録</a>
                </div>
            <?php endif; ?>

            <!-- レッスン一覧 -->
            <div class="lessons-list">
                <h2>レッスン一覧</h2>

                <?php foreach ($lessons as $index => $lesson): ?>
                    <?php
                    $isLocked = isset($error);
                    $isCompleted = $lesson['progress_status'] === 'completed';
                    $isInProgress = $lesson['progress_status'] === 'in_progress';

                    // レッスンタイプのアイコン
                    $typeIcons = [
                        'slide' => '📖',
                        'editor' => '💻',
                        'quiz' => '✏️',
                        'assignment' => '📝'
                    ];
                    $typeLabels = [
                        'slide' => 'スライド',
                        'editor' => 'エディタ',
                        'quiz' => 'クイズ',
                        'assignment' => '課題'
                    ];
                    ?>
                    <div class="lesson-item <?= $isCompleted ? 'completed' : '' ?> <?= $isInProgress ? 'in-progress' : '' ?> <?= $isLocked ? 'locked' : '' ?>">
                        <div class="lesson-number"><?= $index + 1 ?></div>

                        <div class="lesson-content">
                            <div class="lesson-header">
                                <h3><?= h($lesson['title']) ?></h3>
                                <span class="lesson-type">
                                    <?= $typeIcons[$lesson['lesson_type']] ?? '📄' ?>
                                    <?= $typeLabels[$lesson['lesson_type']] ?? 'レッスン' ?>
                                </span>
                            </div>

                            <?php if ($lesson['description']): ?>
                                <p class="lesson-description"><?= h($lesson['description']) ?></p>
                            <?php endif; ?>

                            <div class="lesson-footer">
                                <?php if ($isCompleted): ?>
                                    <span class="status-badge status-completed">✓ 完了</span>
                                    <span class="completed-date">
                                        <?= date('Y/m/d H:i', strtotime($lesson['completed_at'])) ?>
                                    </span>
                                <?php elseif ($isInProgress): ?>
                                    <span class="status-badge status-in-progress">進行中</span>
                                <?php endif; ?>

                                <?php if (!$isLocked): ?>
                                    <a href="<?= APP_URL ?>/lesson.php?id=<?= $lesson['id'] ?>" class="btn btn-sm btn-primary">
                                        <?= $isCompleted ? '復習する' : ($isInProgress ? '続きから' : '始める') ?>
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-disabled" disabled>🔒 ロック</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
