<?php
/**
 * 学習進捗ページ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();

// 全体の進捗統計を取得
$totalLessonsSql = "SELECT COUNT(DISTINCT l.id) as total
                    FROM lessons l
                    JOIN courses c ON l.course_id = c.id";
$totalLessonsResult = db()->fetchOne($totalLessonsSql);
$totalLessons = $totalLessonsResult['total'] ?? 0;

$completedLessonsCountSql = "SELECT COUNT(DISTINCT up.lesson_id) as completed
                        FROM user_progress up
                        JOIN lessons l ON up.lesson_id = l.id
                        JOIN courses c ON l.course_id = c.id
                        WHERE up.user_id = ? AND up.status = 'completed'";
$completedLessonsCountResult = db()->fetchOne($completedLessonsCountSql, [$user['id']]);
$completedLessonsCount = $completedLessonsCountResult['completed'] ?? 0;

$inProgressLessonsCountSql = "SELECT COUNT(DISTINCT up.lesson_id) as in_progress
                         FROM user_progress up
                         JOIN lessons l ON up.lesson_id = l.id
                         JOIN courses c ON l.course_id = c.id
                         WHERE up.user_id = ? AND up.status = 'in_progress'";
$inProgressLessonsCountResult = db()->fetchOne($inProgressLessonsCountSql, [$user['id']]);
$inProgressLessonsCount = $inProgressLessonsCountResult['in_progress'] ?? 0;

// 全体の進捗率を計算
$overallProgress = $totalLessons > 0 ? round(($completedLessonsCount / $totalLessons) * 100) : 0;

// 完了済みレッスン一覧を取得
$completedLessonsSql = "SELECT l.*, c.title as course_title, c.id as course_id, up.completed_at, up.updated_at
                        FROM user_progress up
                        JOIN lessons l ON up.lesson_id = l.id
                        JOIN courses c ON l.course_id = c.id
                        WHERE up.user_id = ? AND up.status = 'completed'
                        ORDER BY up.completed_at DESC";
$completedLessons = db()->fetchAll($completedLessonsSql, [$user['id']]);

// 進行中レッスン一覧を取得
$inProgressLessonsSql = "SELECT l.*, c.title as course_title, c.id as course_id, up.updated_at
                         FROM user_progress up
                         JOIN lessons l ON up.lesson_id = l.id
                         JOIN courses c ON l.course_id = c.id
                         WHERE up.user_id = ? AND up.status = 'in_progress'
                         ORDER BY up.updated_at DESC";
$inProgressLessons = db()->fetchAll($inProgressLessonsSql, [$user['id']]);

// コース別進捗を取得
$courseProgressSql = "SELECT
                        c.id,
                        c.title,
                        c.thumbnail_url,
                        c.is_free,
                        COUNT(DISTINCT l.id) as total_lessons,
                        COUNT(DISTINCT CASE WHEN up.status = 'completed' THEN up.lesson_id END) as completed_lessons,
                        COUNT(DISTINCT CASE WHEN up.status = 'in_progress' THEN up.lesson_id END) as in_progress_lessons
                      FROM courses c
                      LEFT JOIN lessons l ON c.id = l.course_id
                      LEFT JOIN user_progress up ON l.id = up.lesson_id AND up.user_id = ?
                      GROUP BY c.id
                      HAVING COUNT(DISTINCT CASE WHEN up.status = 'completed' OR up.status = 'in_progress' THEN up.lesson_id END) > 0
                      ORDER BY completed_lessons DESC, c.order_num";
$courseProgress = db()->fetchAll($courseProgressSql, [$user['id']]);
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
    <title>学習進捗 | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T7NGQDC2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="progress-page">
        <div class="container">
            <!-- ページヘッダー -->
            <div class="progress-page__header">
                <h1 class="progress-page__title">学習進捗</h1>
                <p class="progress-page__subtitle">あなたの学習の歩みを確認しましょう</p>
            </div>

            <!-- 全体統計カード -->
            <section class="progress-stats">
                <div class="progress-stats__grid">
                    <div class="stat-card">
                        <div class="stat-card__value"><?= $completedLessonsCount ?></div>
                        <div class="stat-card__label">完了済みレッスン</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__value"><?= $inProgressLessonsCount ?></div>
                        <div class="stat-card__label">進行中レッスン</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-card__value"><?= $totalLessons ?></div>
                        <div class="stat-card__label">利用可能レッスン</div>
                    </div>
                    <div class="stat-card stat-card--highlight">
                        <div class="stat-card__value"><?= $overallProgress ?>%</div>
                        <div class="stat-card__label">全体進捗率</div>
                    </div>
                </div>
            </section>

            <!-- 全体進捗バー -->
            <section class="overall-progress">
                <h2 class="overall-progress__title">全体の進捗</h2>
                <div class="progress-bar progress-bar--large">
                    <div class="progress-bar__fill" style="width: <?= $overallProgress ?>%">
                        <span class="progress-bar__label"><?= $overallProgress ?>%</span>
                    </div>
                </div>
                <p class="overall-progress__text">
                    全<?= $totalLessons ?>レッスン中 <?= $completedLessons ?>レッスン完了
                </p>
            </section>

            <!-- コース別進捗 -->
            <?php if (!empty($courseProgress)): ?>
            <section class="progress-section">
                <h2 class="progress-section__title">コース別進捗</h2>
                <div class="course-progress-list">
                    <?php foreach ($courseProgress as $course): ?>
                        <?php
                        $courseProgressPercent = $course['total_lessons'] > 0
                            ? round(($course['completed_lessons'] / $course['total_lessons']) * 100)
                            : 0;
                        ?>
                        <div class="course-progress-card">
                            <div class="course-progress-card__header">
                                <div class="course-progress-card__info">
                                    <h3 class="course-progress-card__title">
                                        <a href="<?= APP_URL ?>/course.php?id=<?= $course['id'] ?>">
                                            <?= h($course['title']) ?>
                                        </a>
                                    </h3>
                                    <?php if (!$course['is_free']): ?>
                                        <span class="badge badge-premium">プレミアム</span>
                                    <?php endif; ?>
                                </div>
                                <div class="course-progress-card__stats">
                                    <span class="course-progress-card__stat">
                                        完了: <?= $course['completed_lessons'] ?>/<?= $course['total_lessons'] ?>
                                    </span>
                                </div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-bar__fill" style="width: <?= $courseProgressPercent ?>%"></div>
                            </div>
                            <p class="course-progress-card__percent"><?= $courseProgressPercent ?>% 完了</p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- 進行中レッスン -->
            <?php if (!empty($inProgressLessons)): ?>
            <section class="progress-section">
                <h2 class="progress-section__title">進行中のレッスン (<?= count($inProgressLessons) ?>)</h2>
                <div class="lesson-list">
                    <?php foreach ($inProgressLessons as $lesson): ?>
                        <div class="lesson-item">
                            <div class="lesson-item__header">
                                <div class="lesson-item__info">
                                    <h4 class="lesson-item__title">
                                        <a href="<?= APP_URL ?>/lesson.php?id=<?= $lesson['id'] ?>">
                                            <?= h($lesson['title']) ?>
                                        </a>
                                    </h4>
                                    <p class="lesson-item__meta">
                                        <span class="lesson-item__course"><?= h($lesson['course_title']) ?></span>
                                        <span class="lesson-item__separator">•</span>
                                        <span class="lesson-item__type">
                                            <?php
                                            $typeLabels = [
                                                'slide' => 'スライド',
                                                'editor' => 'コードエディタ',
                                                'quiz' => 'クイズ',
                                                'assignment' => '課題'
                                            ];
                                            echo $typeLabels[$lesson['lesson_type']] ?? $lesson['lesson_type'];
                                            ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="lesson-item__status">
                                    <span class="status-badge status-badge--in-progress">進行中</span>
                                    <span class="lesson-item__date"><?= date('Y/m/d', strtotime($lesson['updated_at'])) ?></span>
                                </div>
                            </div>
                            <div class="lesson-item__action">
                                <a href="<?= APP_URL ?>/lesson.php?id=<?= $lesson['id'] ?>" class="btn btn-sm btn-primary">
                                    続きから学習
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- 完了済みレッスン -->
            <?php if (!empty($completedLessons)): ?>
            <section class="progress-section">
                <h2 class="progress-section__title">完了済みレッスン (<?= count($completedLessons) ?>)</h2>
                <div class="lesson-list">
                    <?php foreach ($completedLessons as $lesson): ?>
                        <div class="lesson-item lesson-item--completed">
                            <div class="lesson-item__header">
                                <div class="lesson-item__info">
                                    <h4 class="lesson-item__title">
                                        <a href="<?= APP_URL ?>/lesson.php?id=<?= $lesson['id'] ?>">
                                            <?= h($lesson['title']) ?>
                                        </a>
                                    </h4>
                                    <p class="lesson-item__meta">
                                        <span class="lesson-item__course"><?= h($lesson['course_title']) ?></span>
                                        <span class="lesson-item__separator">•</span>
                                        <span class="lesson-item__type">
                                            <?php
                                            $typeLabels = [
                                                'slide' => 'スライド',
                                                'editor' => 'コードエディタ',
                                                'quiz' => 'クイズ',
                                                'assignment' => '課題'
                                            ];
                                            echo $typeLabels[$lesson['lesson_type']] ?? $lesson['lesson_type'];
                                            ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="lesson-item__status">
                                    <span class="status-badge status-badge--completed">完了</span>
                                    <span class="lesson-item__date"><?= date('Y/m/d', strtotime($lesson['completed_at'])) ?></span>
                                </div>
                            </div>
                            <div class="lesson-item__action">
                                <a href="<?= APP_URL ?>/lesson.php?id=<?= $lesson['id'] ?>" class="btn btn-sm btn-outline">
                                    復習する
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

            <!-- 学習なしメッセージ -->
            <?php if (empty($inProgressLessons) && empty($completedLessons)): ?>
            <section class="empty-state">
                <div class="empty-state__content">
                    <div class="empty-state__icon">📚</div>
                    <h2 class="empty-state__title">まだ学習を始めていません</h2>
                    <p class="empty-state__text">コースを選んで学習を始めましょう！</p>
                    <a href="<?= APP_URL ?>/dashboard.php" class="btn btn-primary">
                        コース一覧を見る
                    </a>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
