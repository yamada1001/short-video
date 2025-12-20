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

// 最近の進捗を取得
$recentProgressSql = "SELECT l.*, c.title as course_title, up.status, up.updated_at
                     FROM user_progress up
                     JOIN lessons l ON up.lesson_id = l.id
                     JOIN courses c ON l.course_id = c.id
                     WHERE up.user_id = ?
                     ORDER BY up.updated_at DESC
                     LIMIT 5";
$recentProgress = db()->fetchAll($recentProgressSql, [$user['id']]);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード | Gemini AI学習プラットフォーム</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/progate-v2.css">
</head>
<body>
    <?php include __DIR__ . '/../includes/header.php'; ?>

    <main class="dashboard">
        <div class="container">
            <div class="dashboard-header">
                <h1>こんにちは、<?= h($user['name']) ?>さん！</h1>
                <p class="dashboard-subtitle">
                    <?php if (hasActiveSubscription()): ?>
                        プレミアム会員 | 今日のAPI残り: <?= API_LIMIT_PREMIUM - checkApiLimit() ?>回
                    <?php else: ?>
                        無料会員 | 今日のAPI残り: <?= API_LIMIT_FREE - checkApiLimit() ?>回
                    <?php endif; ?>
                </p>
            </div>

            <?php if (!hasActiveSubscription()): ?>
                <div class="upgrade-banner">
                    <h3>プレミアムプランで全コースにアクセス！</h3>
                    <p>月額980円で全コース見放題 + API呼び出し100回/日</p>
                    <a href="<?= APP_URL ?>/subscribe.php" class="btn btn-primary">今すぐアップグレード</a>
                </div>
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
                                    <?php else: ?>
                                        <span class="badge badge-premium">プレミアム</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($canAccess): ?>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                                    </div>
                                    <p class="progress-text"><?= $progress ?>% 完了</p>
                                    <a href="<?= APP_URL ?>/course.php?id=<?= $course['id'] ?>" class="btn btn-sm btn-outline">コースを見る</a>
                                <?php else: ?>
                                    <p class="locked-message">🔒 プレミアム会員限定</p>
                                <?php endif; ?>
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
</body>
</html>
