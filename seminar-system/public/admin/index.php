<?php
/**
 * 管理画面ダッシュボード
 */
require_once __DIR__ . '/../../config/config.php';

use Seminar\Attendee;
use Seminar\Seminar;

$currentPage = 'dashboard';
$pageTitle = 'ダッシュボード';

// フラッシュメッセージ取得
$flash = getFlash();

// 全体統計
$db = \Seminar\Database::getInstance();

// 総セミナー数
$totalSeminars = (int)$db->fetch('SELECT COUNT(*) as count FROM seminars')['count'];

// 総参加者数
$totalAttendees = (int)$db->fetch('SELECT COUNT(*) as count FROM attendees')['count'];

// 今月の売上（支払済み + 出席済み）
$thisMonthRevenue = (int)$db->fetch(
    "SELECT COALESCE(SUM(s.price), 0) as total
     FROM attendees a
     JOIN seminars s ON a.seminar_id = s.id
     WHERE a.status IN ('paid', 'attended')
     AND DATE_FORMAT(a.applied_at, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')"
)['total'];

// 出席率（出席済み / (出席済み + 欠席)）
$attendanceData = $db->fetch(
    "SELECT
        COUNT(CASE WHEN status = 'attended' THEN 1 END) as attended,
        COUNT(CASE WHEN status IN ('attended', 'absent') THEN 1 END) as total
     FROM attendees"
);
$attendanceRate = $attendanceData['total'] > 0
    ? round(($attendanceData['attended'] / $attendanceData['total']) * 100, 1)
    : 0;

// ステータス別参加者数
$statusCounts = $db->query(
    "SELECT
        status,
        COUNT(*) as count
     FROM attendees
     GROUP BY status"
);

$statusData = [
    'applied' => 0,
    'paid' => 0,
    'attended' => 0,
    'absent' => 0
];

foreach ($statusCounts as $row) {
    $statusData[$row['status']] = (int)$row['count'];
}

// 直近のセミナー（今日以降、開始日時が近い順）
$upcomingSeminars = $db->query(
    "SELECT * FROM seminars
     WHERE start_datetime >= NOW()
     ORDER BY start_datetime ASC
     LIMIT 5"
);

// 最近の申込者（最新10件）
$recentAttendees = $db->query(
    "SELECT
        a.*,
        s.title as seminar_title,
        s.start_datetime
     FROM attendees a
     JOIN seminars s ON a.seminar_id = s.id
     ORDER BY a.applied_at DESC
     LIMIT 10"
);

// 月別申込数（過去6ヶ月）
$monthlyData = $db->query(
    "SELECT
        DATE_FORMAT(applied_at, '%Y-%m') as month,
        COUNT(*) as count
     FROM attendees
     WHERE applied_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
     GROUP BY DATE_FORMAT(applied_at, '%Y-%m')
     ORDER BY month ASC"
);

$months = [];
$counts = [];
foreach ($monthlyData as $row) {
    $months[] = $row['month'];
    $counts[] = (int)$row['count'];
}

// 6ヶ月分のラベルを生成（データがない月も含める）
$monthLabels = [];
$monthlyCounts = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-{$i} months"));
    $monthLabels[] = date('Y年n月', strtotime("-{$i} months"));

    $index = array_search($month, $months);
    $monthlyCounts[] = $index !== false ? $counts[$index] : 0;
}

// ヘッダー読み込み
include __DIR__ . '/includes/header.php';
?>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
    margin-bottom: 40px;
}

.stat-card {
    background: white;
    padding: 24px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.stat-label {
    font-size: 13px;
    color: #888;
    margin-bottom: 8px;
    font-weight: 400;
}

.stat-value {
    font-size: 32px;
    font-weight: 500;
    color: #333;
    line-height: 1.2;
}

.stat-unit {
    font-size: 16px;
    color: #666;
    margin-left: 4px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    margin-bottom: 32px;
}

.dashboard-section {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 24px;
}

.dashboard-section h3 {
    font-size: 16px;
    font-weight: 500;
    margin-bottom: 20px;
    color: #333;
    border-bottom: 1px solid #e8e8e8;
    padding-bottom: 12px;
}

.seminar-list {
    list-style: none;
}

.seminar-item {
    padding: 12px 0;
    border-bottom: 1px solid #f5f5f5;
}

.seminar-item:last-child {
    border-bottom: none;
}

.seminar-item-title {
    font-weight: 500;
    color: #333;
    font-size: 14px;
    margin-bottom: 4px;
}

.seminar-item-meta {
    font-size: 13px;
    color: #888;
}

.attendee-list {
    list-style: none;
}

.attendee-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f5f5f5;
    font-size: 14px;
}

.attendee-item:last-child {
    border-bottom: none;
}

.attendee-name {
    font-weight: 500;
    color: #333;
}

.attendee-time {
    font-size: 12px;
    color: #888;
}

.chart-container {
    position: relative;
    height: 300px;
    margin-top: 20px;
}

.full-width-section {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 24px;
    margin-bottom: 32px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #999;
    font-size: 14px;
}

@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .stat-value {
        font-size: 28px;
    }

    .chart-container {
        height: 250px;
    }
}
</style>

<!-- 統計カード -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">総セミナー数</div>
        <div class="stat-value"><?php echo $totalSeminars; ?><span class="stat-unit">件</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">総参加者数</div>
        <div class="stat-value"><?php echo $totalAttendees; ?><span class="stat-unit">名</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">今月の売上</div>
        <div class="stat-value"><?php echo number_format($thisMonthRevenue); ?><span class="stat-unit">円</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">出席率</div>
        <div class="stat-value"><?php echo $attendanceRate; ?><span class="stat-unit">%</span></div>
    </div>
</div>

<!-- 月別申込推移 -->
<div class="full-width-section">
    <h3>月別申込推移（過去6ヶ月）</h3>
    <div class="chart-container">
        <canvas id="monthlyChart"></canvas>
    </div>
</div>

<!-- グリッド -->
<div class="dashboard-grid">
    <!-- 直近のセミナー -->
    <div class="dashboard-section">
        <h3>直近のセミナー</h3>
        <?php if (empty($upcomingSeminars)): ?>
            <div class="empty-state">開催予定のセミナーはありません</div>
        <?php else: ?>
            <ul class="seminar-list">
                <?php foreach ($upcomingSeminars as $sem): ?>
                    <li class="seminar-item">
                        <div class="seminar-item-title"><?php echo h($sem['title']); ?></div>
                        <div class="seminar-item-meta">
                            <?php echo formatDatetime($sem['start_datetime'], 'Y/m/d H:i'); ?> -
                            <?php echo h($sem['venue']) ?: '会場未定'; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <!-- ステータス別参加者数 -->
    <div class="dashboard-section">
        <h3>ステータス別参加者数</h3>
        <div class="chart-container">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- 最近の申込者 -->
<div class="full-width-section">
    <h3>最近の申込者</h3>
    <?php if (empty($recentAttendees)): ?>
        <div class="empty-state">申込者がいません</div>
    <?php else: ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>セミナー</th>
                    <th>ステータス</th>
                    <th>申込日時</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recentAttendees as $att): ?>
                    <tr>
                        <td><?php echo h($att['name']); ?></td>
                        <td>
                            <?php echo h($att['seminar_title']); ?><br>
                            <span class="text-muted"><?php echo formatDatetime($att['start_datetime'], 'Y/m/d H:i'); ?></span>
                        </td>
                        <td>
                            <span class="badge <?php echo getStatusBadgeClass($att['status']); ?>">
                                <?php echo getStatusLabel($att['status']); ?>
                            </span>
                        </td>
                        <td class="text-muted"><?php echo formatDatetime($att['applied_at'], 'Y/m/d H:i'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// 月別申込推移グラフ
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($monthLabels); ?>,
        datasets: [{
            label: '申込数',
            data: <?php echo json_encode($monthlyCounts); ?>,
            borderColor: '#3498db',
            backgroundColor: 'rgba(52, 152, 219, 0.1)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// ステータス別参加者数グラフ
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['申込済', '支払済', '出席済', '欠席'],
        datasets: [{
            data: [
                <?php echo $statusData['applied']; ?>,
                <?php echo $statusData['paid']; ?>,
                <?php echo $statusData['attended']; ?>,
                <?php echo $statusData['absent']; ?>
            ],
            backgroundColor: [
                '#3498db',
                '#9b59b6',
                '#2ecc71',
                '#e74c3c'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 13
                    }
                }
            }
        }
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
