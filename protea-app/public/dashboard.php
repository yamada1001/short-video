<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Protea\Database;

$db = Database::getInstance();
$pdo = $db->getPDO();

// ステータス別集計
$statusStats = $pdo->query("
    SELECT status, COUNT(*) as count
    FROM keywords
    GROUP BY status
")->fetchAll();

// 総件数
$totalKeywords = $pdo->query("SELECT COUNT(*) FROM keywords")->fetchColumn();
$totalArticles = $pdo->query("SELECT COUNT(*) FROM generated_articles")->fetchColumn();

// 最近の活動
$recentActivities = $pdo->query("
    SELECT id, keyword, status, updated_at
    FROM keywords
    ORDER BY updated_at DESC
    LIMIT 10
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード - Protea</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-md);
            margin-bottom: var(--spacing-lg);
        }

        .stat-box {
            background: white;
            padding: var(--spacing-lg);
            border-left: 4px solid var(--color-natural-brown);
            text-align: center;
        }

        .stat-box .icon {
            font-size: 48px;
            color: var(--color-natural-brown);
            margin-bottom: var(--spacing-sm);
        }

        .stat-box .value {
            font-size: 42px;
            font-weight: bold;
            color: var(--color-charcoal);
        }

        .stat-box .label {
            font-size: 14px;
            color: var(--color-text-light);
            margin-top: 8px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin: var(--spacing-md) 0;
        }

        .activity-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .activity-list li {
            padding: var(--spacing-sm);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-list li:last-child {
            border-bottom: none;
        }

        .activity-time {
            font-size: 13px;
            color: var(--color-text-light);
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Protea Webアプリ</h1>
            <p class="subtitle">ダッシュボード</p>
        </div>
    </header>

    <nav>
        <div class="container">
            <ul>
                <li><a href="index.php">キーワード一覧</a></li>
                <li><a href="upload.php">Excelアップロード</a></li>
                <li><a href="dashboard.php" class="active">ダッシュボード</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <!-- 統計サマリー -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="icon"><i class="fas fa-key"></i></div>
                <div class="value"><?= number_format($totalKeywords) ?></div>
                <div class="label">登録キーワード</div>
            </div>

            <div class="stat-box">
                <div class="icon"><i class="fas fa-file-alt"></i></div>
                <div class="value"><?= number_format($totalArticles) ?></div>
                <div class="label">生成記事</div>
            </div>

            <div class="stat-box">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <div class="value"><?= $totalArticles > 0 ? number_format(($totalArticles / max($totalKeywords, 1)) * 100, 1) : 0 ?>%</div>
                <div class="label">記事生成率</div>
            </div>
        </div>

        <!-- グラフ -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--spacing-lg);">
            <!-- ステータス別円グラフ -->
            <div class="card">
                <h3>ステータス別分布</h3>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- 最近の活動 -->
            <div class="card">
                <h3>最近の活動</h3>
                <ul class="activity-list">
                    <?php if (empty($recentActivities)): ?>
                        <li style="text-align: center; padding: var(--spacing-lg); color: var(--color-text-light);">
                            アクティビティがありません
                        </li>
                    <?php else: ?>
                        <?php foreach ($recentActivities as $activity): ?>
                            <li>
                                <div>
                                    <strong><?= htmlspecialchars($activity['keyword']) ?></strong>
                                    <span class="badge <?= ($activity['status'] === '公開済') ? 'badge-published' : (($activity['status'] === '記事完成') ? 'badge-completed' : 'badge-processing') ?>" style="margin-left: 8px; font-size: 11px;">
                                        <?= htmlspecialchars($activity['status']) ?>
                                    </span>
                                </div>
                                <span class="activity-time">
                                    <?= date('m/d H:i', strtotime($activity['updated_at'])) ?>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- アクションボタン -->
        <div class="card">
            <h3>クイックアクション</h3>
            <div style="display: flex; gap: var(--spacing-sm);">
                <a href="upload.php" class="btn">
                    <i class="fas fa-upload"></i> Excelアップロード
                </a>
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-list"></i> キーワード一覧
                </a>
            </div>
        </div>
    </div>

    <script>
        // ステータス別円グラフ
        const statusData = <?= json_encode($statusStats) ?>;
        const statusLabels = statusData.map(item => item.status);
        const statusCounts = statusData.map(item => item.count);

        const ctx = document.getElementById('statusChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusCounts,
                    backgroundColor: [
                        'rgba(139, 115, 85, 0.8)',  // natural-brown
                        'rgba(74, 74, 74, 0.8)',    // charcoal
                        'rgba(33, 150, 243, 0.8)',  // blue
                        'rgba(76, 175, 80, 0.8)',   // green
                        'rgba(255, 152, 0, 0.8)',   // orange
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</body>
</html>
