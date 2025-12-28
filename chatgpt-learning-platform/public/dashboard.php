<?php
/**
 * ダッシュボード
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// ログインチェック
requireLogin();

$user = getCurrentUser();

// コース一覧を取得
$sql = "SELECT * FROM courses ORDER BY order_num";
$courses = db()->fetchAll($sql);

// 推薦コースを取得
$recommendedCourseIds = getRecommendedCourses($user['id']);
$recommendedCourses = [];
if (!empty($recommendedCourseIds)) {
    $placeholders = implode(',', array_fill(0, count($recommendedCourseIds), '?'));
    $recommendedSql = "SELECT * FROM courses WHERE id IN ($placeholders) ORDER BY FIELD(id, $placeholders)";
    $params = array_merge($recommendedCourseIds, $recommendedCourseIds);
    $recommendedCourses = db()->fetchAll($recommendedSql, $params);
}

// 最近の進捗を取得
$recentProgressSql = "SELECT l.*, c.title as course_title, up.status, up.updated_at
                     FROM user_progress up
                     JOIN lessons l ON up.lesson_id = l.id
                     JOIN courses c ON l.course_id = c.id
                     WHERE up.user_id = ?
                     ORDER BY up.updated_at DESC
                     LIMIT 5";
$recentProgress = db()->fetchAll($recentProgressSql, [$user['id']]);

// ゲーミフィケーション統計を取得
$userStats = [
    'level' => $user['level'] ?? 1,
    'total_points' => $user['total_points'] ?? 0,
    'current_streak' => $user['current_streak'] ?? 0,
    'longest_streak' => $user['longest_streak'] ?? 0,
];

// 獲得バッジ数を取得
$badgeCountSql = "SELECT COUNT(*) as count FROM user_badges WHERE user_id = ?";
$badgeCountResult = db()->fetchOne($badgeCountSql, [$user['id']]);
$userStats['badge_count'] = $badgeCountResult['count'] ?? 0;

// 過去30日分のストリークデータを取得
$streakDataSql = "SELECT activity_date FROM user_streaks
                 WHERE user_id = ?
                 AND activity_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 ORDER BY activity_date DESC";
$streakData = db()->fetchAll($streakDataSql, [$user['id']]);
$streakDates = is_array($streakData) ? array_column($streakData, 'activity_date') : [];
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
    <title>ダッシュボード | Gemini AI学習プラットフォーム</title>
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

    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>こんにちは、<?= h($user['name']) ?>さん！</h1>
                <p class="dashboard-subtitle">
                    今日のAPI残り: <?= API_LIMIT_FREE - checkApiLimit() ?>回
                </p>
            </div>

            <!-- アンケート未回答の場合のバナー -->
            <?php if (empty($user['survey_completed_at'])): ?>
                <div class="survey-banner">
                    <div class="survey-banner-content">
                        <div class="survey-banner-icon"><i class="fas fa-clipboard-list"></i></div>
                        <div class="survey-banner-text">
                            <h3>学習目的診断アンケート</h3>
                            <p>あなたに最適なコースをおすすめするため、簡単なアンケートにご協力ください（約3分）</p>
                        </div>
                        <a href="<?= APP_URL ?>/survey.php" class="btn btn-primary">アンケートに答える</a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- ゲーミフィケーション統計 -->
            <section class="gamification-stats">
                <div class="stats-grid">
                    <!-- レベル -->
                    <div class="stat-card stat-level">
                        <div class="stat-icon"><i class="fas fa-bullseye"></i></div>
                        <div class="stat-content">
                            <div class="stat-label">レベル</div>
                            <div class="stat-value">Lv.<?= $userStats['level'] ?></div>
                        </div>
                    </div>

                    <!-- ポイント -->
                    <div class="stat-card stat-points">
                        <div class="stat-icon"><i class="fas fa-star"></i></div>
                        <div class="stat-content">
                            <div class="stat-label">獲得ポイント</div>
                            <div class="stat-value"><?= number_format($userStats['total_points']) ?></div>
                        </div>
                    </div>

                    <!-- ストリーク -->
                    <div class="stat-card stat-streak">
                        <div class="stat-icon"><i class="fas fa-fire"></i></div>
                        <div class="stat-content">
                            <div class="stat-label">連続学習</div>
                            <div class="stat-value"><?= $userStats['current_streak'] ?>日</div>
                            <div class="stat-sub">最長: <?= $userStats['longest_streak'] ?>日</div>
                        </div>
                    </div>

                    <!-- バッジ -->
                    <div class="stat-card stat-badges">
                        <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                        <div class="stat-content">
                            <div class="stat-label">獲得バッジ</div>
                            <div class="stat-value"><?= $userStats['badge_count'] ?></div>
                        </div>
                    </div>
                </div>

                <!-- ストリークカレンダー -->
                <div class="streak-calendar">
                    <h3 class="streak-calendar-title">
                        <span class="streak-icon">📅</span>
                        学習カレンダー（過去30日）
                    </h3>
                    <div class="calendar-grid">
                        <?php
                        // 過去30日分のカレンダーを生成
                        for ($i = 29; $i >= 0; $i--) {
                            $date = date('Y-m-d', strtotime("-{$i} days"));
                            $dayOfWeek = date('w', strtotime($date));
                            $dayLabel = date('j', strtotime($date));
                            $isActive = in_array($date, $streakDates);
                            $isToday = $date === date('Y-m-d');

                            $classes = ['calendar-day'];
                            if ($isActive) $classes[] = 'active';
                            if ($isToday) $classes[] = 'today';

                            echo '<div class="' . implode(' ', $classes) . '" title="' . date('Y/m/d', strtotime($date)) . '">';
                            echo '<span class="day-label">' . $dayLabel . '</span>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <div class="calendar-legend">
                        <div class="legend-item">
                            <div class="legend-dot legend-active"></div>
                            <span>学習した日</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot legend-today"></div>
                            <span>今日</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- あなたにおすすめのコース -->
            <?php if (!empty($recommendedCourses) && !empty($user['survey_completed_at'])): ?>
                <section class="dashboard-section recommended-courses">
                    <div class="section-header">
                        <h2><i class="fas fa-sparkles"></i> あなたにおすすめのコース</h2>
                        <p class="section-subtitle">学習目的に基づいて最適なコースをピックアップしました</p>
                    </div>
                    <div class="course-grid">
                        <?php foreach ($recommendedCourses as $course): ?>
                            <?php
                            $canAccess = canAccessCourse($course['id']);
                            $progress = getCourseProgress($course['id']);
                            ?>
                            <div class="course-card <?= !$canAccess ? 'locked' : '' ?> recommended">
                                <div class="recommended-badge">おすすめ</div>
                                <img src="<?= h($course['thumbnail_url']) ?>" alt="<?= h($course['title']) ?>" class="course-thumbnail">
                                <div class="course-info">
                                    <h3><?= h($course['title']) ?></h3>
                                    <p><?= h($course['description']) ?></p>
                                    <div class="course-meta">
                                        <span class="difficulty difficulty-<?= h($course['difficulty']) ?>">
                                            <?= $course['difficulty'] === 'beginner' ? '初級' : ($course['difficulty'] === 'intermediate' ? '中級' : '上級') ?>
                                        </span>
                                        <?php if ($course['is_free']): ?>
                                            <span class="badge badge-free">無料</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" data-progress="<?= $progress ?>"></div>
                                    </div>
                                    <p class="progress-text"><?= $progress ?>% 完了</p>
                                    <a href="<?= APP_URL ?>/course.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-outline">コースを見る</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <section class="dashboard-section">
                <h2>コース一覧</h2>
                <div class="course-grid">
                    <?php foreach ($courses as $course): ?>
                        <?php
                        $canAccess = canAccessCourse($course['id']);
                        $progress = getCourseProgress($course['id']);
                        ?>
                        <div class="course-card <?= !$canAccess ? 'locked' : '' ?>">
                            <img src="<?= h($course['thumbnail_url']) ?>" alt="<?= h($course['title']) ?>" class="course-thumbnail">
                            <div class="course-info">
                                <h3><?= h($course['title']) ?></h3>
                                <p><?= h($course['description']) ?></p>
                                <div class="course-meta">
                                    <span class="difficulty difficulty-<?= h($course['difficulty']) ?>">
                                        <?= $course['difficulty'] === 'beginner' ? '初級' : ($course['difficulty'] === 'intermediate' ? '中級' : '上級') ?>
                                    </span>
                                    <?php if ($course['is_free']): ?>
                                        <span class="badge badge-free">無料</span>
                                    <?php endif; ?>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" data-progress="<?= $progress ?>"></div>
                                </div>
                                <p class="progress-text"><?= $progress ?>% 完了</p>
                                <a href="<?= APP_URL ?>/course.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-outline">コースを見る</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <?php if (!empty($recentProgress)): ?>
                <section class="dashboard-section">
                    <h2>最近の学習</h2>
                    <div class="recent-progress">
                        <?php foreach ($recentProgress as $item): ?>
                            <div class="progress-item">
                                <div class="progress-item-info">
                                    <h4><?= h($item['title']) ?></h4>
                                    <p class="text-muted"><?= h($item['course_title']) ?></p>
                                </div>
                                <div class="progress-item-status">
                                    <span class="status-badge status-<?= h($item['status']) ?>">
                                        <?= $item['status'] === 'completed' ? '✓ 完了' : '進行中' ?>
                                    </span>
                                    <span class="text-muted"><?= date('m/d H:i', strtotime($item['updated_at'])) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>

    <?php include __DIR__ . '/../includes/footer.php'; ?>
    <script src="<?= APP_URL ?>/assets/js/dashboard.js"></script>
</body>
</html>
