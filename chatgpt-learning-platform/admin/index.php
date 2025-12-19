<?php
/**
 * 管理画面トップ
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// 管理者チェック
requireAdmin();

// 統計情報を取得
$totalUsersSql = "SELECT COUNT(*) as count FROM users";
$totalUsers = db()->fetchOne($totalUsersSql)['count'];

$totalCoursesSql = "SELECT COUNT(*) as count FROM courses";
$totalCourses = db()->fetchOne($totalCoursesSql)['count'];

$totalLessonsSql = "SELECT COUNT(*) as count FROM lessons";
$totalLessons = db()->fetchOne($totalLessonsSql)['count'];

$premiumUsersSql = "SELECT COUNT(*) as count FROM users WHERE subscription_status = 'active'";
$premiumUsers = db()->fetchOne($premiumUsersSql)['count'];

// 今日のAPI使用量
$todayApiSql = "SELECT SUM(tokens_used) as total_tokens, SUM(cost_usd) as total_cost
                FROM api_usage
                WHERE DATE(created_at) = CURDATE()";
$todayApi = db()->fetchOne($todayApiSql);

// 最近のユーザー
$recentUsersSql = "SELECT * FROM users ORDER BY created_at DESC LIMIT 10";
$recentUsers = db()->fetchAll($recentUsersSql);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面 | ChatGPT学習プラットフォーム</title>
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        .admin-sidebar {
            width: 250px;
            background: var(--gray-900);
            color: white;
            padding: 30px 20px;
        }
        .admin-sidebar h2 {
            font-size: 18px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--gray-700);
        }
        .admin-nav {
            list-style: none;
        }
        .admin-nav li {
            margin-bottom: 8px;
        }
        .admin-nav a {
            display: block;
            padding: 12px 16px;
            color: var(--gray-400);
            border-radius: var(--radius-md);
            transition: var(--transition);
        }
        .admin-nav a:hover, .admin-nav a.active {
            background: var(--gray-800);
            color: white;
        }
        .admin-main {
            flex: 1;
            padding: 40px;
            background: var(--bg-page);
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 24px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
        }
        .stat-label {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--primary);
        }
        .admin-section {
            background: white;
            padding: 30px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }
        .admin-section h2 {
            font-size: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--gray-200);
        }
        th {
            background: var(--bg-page);
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="admin-sidebar">
            <h2>管理メニュー</h2>
            <ul class="admin-nav">
                <li><a href="<?= APP_URL ?>/admin/index.php" class="active">ダッシュボード</a></li>
                <li><a href="<?= APP_URL ?>/admin/courses.php">コース管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/lessons.php">レッスン管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/users.php">ユーザー管理</a></li>
                <li><a href="<?= APP_URL ?>/admin/assignments.php">課題管理</a></li>
                <li><a href="<?= APP_URL ?>/dashboard.php">サイトに戻る</a></li>
                <li><a href="<?= APP_URL ?>/logout.php">ログアウト</a></li>
            </ul>
        </aside>

        <main class="admin-main">
            <h1>管理画面ダッシュボード</h1>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">総ユーザー数</div>
                    <div class="stat-value"><?= number_format($totalUsers) ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">プレミアム会員</div>
                    <div class="stat-value"><?= number_format($premiumUsers) ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">総コース数</div>
                    <div class="stat-value"><?= number_format($totalCourses) ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">総レッスン数</div>
                    <div class="stat-value"><?= number_format($totalLessons) ?></div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">今日のAPI使用量</div>
                    <div class="stat-value"><?= number_format($todayApi['total_tokens'] ?? 0) ?></div>
                    <div class="stat-label">トークン</div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">今日のAPIコスト</div>
                    <div class="stat-value">$<?= number_format($todayApi['total_cost'] ?? 0, 2) ?></div>
                </div>
            </div>

            <div class="admin-section">
                <h2>最近登録されたユーザー</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>名前</th>
                            <th>メール</th>
                            <th>認証方法</th>
                            <th>会員タイプ</th>
                            <th>登録日</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentUsers as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= h($user['name']) ?></td>
                                <td><?= h($user['email']) ?></td>
                                <td><?= $user['oauth_provider'] === 'google' ? 'Google' : 'メール' ?></td>
                                <td>
                                    <?php if ($user['subscription_status'] === 'active'): ?>
                                        <span class="badge badge-premium">プレミアム</span>
                                    <?php else: ?>
                                        <span class="badge badge-free">無料</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('Y/m/d H:i', strtotime($user['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
