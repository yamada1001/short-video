<?php
/**
 * BNI Slide System V2 - Sales Statistics (p.190)
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);
$targetFriday = getTargetFriday();

$stmt = $db->prepare("SELECT * FROM statistics WHERE week_date = :week_date AND type = 'sales_stats'");
$stmt->bindValue(':week_date', $targetFriday, PDO::PARAM_STR);
$stmt->execute();
$stat = $stmt->fetch(PDO::FETCH_ASSOC);
$data = $stat ? json_decode($stat['value'], true) : [];
$growthRate = floatval($data['sales_growth_rate'] ?? 0);
$isPositive = $growthRate >= 0;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>売上統計 | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: white; overflow: hidden; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .slide-container { width: 100%; max-width: 1400px; padding: 60px; text-align: center; }
        .title { font-size: 72px; font-weight: 700; color: #C8102E; margin-bottom: 30px; text-shadow: 0 4px 10px rgba(200, 16, 46, 0.5); }
        .date-display { font-size: 36px; color: #fff; margin-bottom: 60px; opacity: 0.8; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; margin-top: 40px; }
        .stat-box { background: rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 50px; backdrop-filter: blur(10px); border: 2px solid rgba(200, 16, 46, 0.3); }
        .stat-label { font-size: 32px; color: #fff; margin-bottom: 30px; opacity: 0.8; }
        .stat-value { font-size: 90px; font-weight: 700; color: #C8102E; }
        .stat-icon { font-size: 56px; color: #C8102E; margin-bottom: 20px; }
        .positive { color: #28a745; }
        .negative { color: #dc3545; }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title"><i class="fas fa-dollar-sign"></i> 売上統計</div>
        <div class="date-display"><?php echo htmlspecialchars($data['sales_date'] ?? date('Y-m-d')); ?></div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-label">期間までの売上</div>
                <div class="stat-value">¥<?php echo number_format($data['sales_total'] ?? 0); ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                <div class="stat-label">前期間との伸び率</div>
                <div class="stat-value <?php echo $isPositive ? 'positive' : 'negative'; ?>">
                    <?php echo $isPositive ? '+' : ''; ?><?php echo number_format($growthRate, 1); ?>%
                </div>
            </div>
        </div>
    </div>
</body>
</html>
