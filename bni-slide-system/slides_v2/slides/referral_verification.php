<?php
/**
 * BNI Slide System V2 - Referral Verification (p.227)
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);
$targetFriday = getTargetFriday();

$stmt = $db->prepare("SELECT r.*, m1.name as from_name, m2.name as to_name FROM referral_verification r LEFT JOIN members m1 ON r.from_member_id = m1.id LEFT JOIN members m2 ON r.to_member_id = m2.id WHERE r.week_date = :week_date LIMIT 1");
$stmt->bindValue(':week_date', $targetFriday, PDO::PARAM_STR);
$stmt->execute();
$verification = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>リファーラル真正度 | BNI</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Hiragino Kaku Gothic ProN', sans-serif; background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); color: white; overflow: hidden; height: 100vh; display: flex; justify-content: center; align-items: center; }
        .slide-container { width: 100%; max-width: 1200px; padding: 60px; text-align: center; }
        .title { font-size: 72px; font-weight: 700; color: #C8102E; margin-bottom: 60px; text-shadow: 0 4px 10px rgba(200, 16, 46, 0.5); }
        .members-display { font-size: 64px; font-weight: 700; color: #fff; margin-bottom: 80px; }
        .arrow { color: #C8102E; margin: 0 30px; }
        .questions { display: flex; flex-direction: column; gap: 40px; margin-top: 40px; }
        .question { background: rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 40px; backdrop-filter: blur(10px); border: 2px solid rgba(200, 16, 46, 0.3); font-size: 36px; }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title"><i class="fas fa-check-circle"></i> リファーラル真正度</div>
        <?php if ($verification): ?>
            <div class="members-display">
                <?php echo htmlspecialchars($verification['from_name']); ?>
                <span class="arrow"><i class="fas fa-arrow-right"></i></span>
                <?php echo htmlspecialchars($verification['to_name']); ?>
            </div>
            <div class="questions">
                <div class="question">リファーラル先と連絡は取れましたか？</div>
                <div class="question">話は通じてましたか？</div>
                <div class="question">純粋にビジネスの機会となり得るものでしたか？</div>
            </div>
        <?php else: ?>
            <p style="font-size: 32px; color: #999;">データがありません</p>
        <?php endif; ?>
    </div>
</body>
</html>
