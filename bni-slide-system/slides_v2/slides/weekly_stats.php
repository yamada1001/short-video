<?php
/**
 * BNI Slide System V2 - Weekly Statistics (p.302)
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);
$targetFriday = getTargetFriday();

$stmt = $db->prepare("SELECT * FROM statistics WHERE week_date = :week_date AND type = 'weekly_stats'");
$stmt->bindValue(':week_date', $targetFriday, PDO::PARAM_STR);
$stmt->execute();
$stat = $stmt->fetch(PDO::FETCH_ASSOC);
$data = $stat ? json_decode($stat['value'], true) : [];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>週次統計 | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: white; overflow: hidden; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .slide-container { width: 100%; max-width: 1400px; padding: 60px; text-align: center; }
        .title { font-size: 72px; font-weight: 700; color: #C8102E; margin-bottom: 60px; text-shadow: 0 4px 10px rgba(200, 16, 46, 0.5); }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px; margin-top: 40px; }
        .stat-box { background: rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); border: 2px solid rgba(200, 16, 46, 0.3); }
        .stat-label { font-size: 28px; color: #fff; margin-bottom: 20px; opacity: 0.8; }
        .stat-value { font-size: 80px; font-weight: 700; color: #C8102E; }
        .stat-icon { font-size: 48px; color: #C8102E; margin-bottom: 15px; }
        .countdown { color: #FFD700; }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title"><i class="fas fa-calendar-week"></i> 週次統計</div>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
                <div class="stat-label">先週のビジター数</div>
                <div class="stat-value"><?php echo number_format($data['weekly_last_week_visitors'] ?? 0); ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
                <div class="stat-label">今週のビジター数</div>
                <div class="stat-value"><?php echo number_format($data['weekly_this_week_visitors'] ?? 0); ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-bullseye"></i></div>
                <div class="stat-label">150名までのカウントダウン</div>
                <div class="stat-value countdown"><?php echo number_format($data['weekly_countdown_150'] ?? 0); ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-icon"><i class="fas fa-flag-checkered"></i></div>
                <div class="stat-label">毎週の目標数</div>
                <div class="stat-value"><?php echo number_format($data['weekly_target'] ?? 0); ?></div>
            </div>
        </div>
    </div>
</body>
</html>
