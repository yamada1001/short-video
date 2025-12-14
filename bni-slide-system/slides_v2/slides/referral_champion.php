<?php
/**
 * BNI Slide System V2 - Referral Champion Slide (p.91)
 * リファーラルチャンピオン
 */

$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';
$db = new SQLite3($dbPath);

require_once __DIR__ . '/../includes/getTargetFriday.php';
$targetFriday = getTargetFriday();

$stmt = $db->prepare("
    SELECT c.*, m.name as member_name, m.photo_path
    FROM champions c
    LEFT JOIN members m ON c.member_id = m.id
    WHERE c.week_date = :week_date AND c.type = 'referral'
    ORDER BY c.rank, c.count DESC, c.id
");
$stmt->bindValue(':week_date', $targetFriday, SQLITE3_TEXT);
$result = $stmt->execute();

$champions = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $champions[] = $row;
}

$db->close();

// ランクごとにグループ化
$grouped = [1 => [], 2 => [], 3 => []];
foreach ($champions as $champion) {
    $grouped[$champion['rank']][] = $champion;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>リファーラルチャンピオン | BNI Slide System V2</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            color: white;
            overflow: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .slide-container {
            width: 100%;
            max-width: 1400px;
            padding: 60px;
            text-align: center;
        }

        .title {
            font-size: 72px;
            font-weight: 700;
            color: #C8102E;
            margin-bottom: 20px;
            text-shadow: 0 4px 10px rgba(200, 16, 46, 0.5);
        }

        .subtitle {
            font-size: 36px;
            color: #fff;
            margin-bottom: 60px;
            opacity: 0.9;
        }

        .champions-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 40px;
        }

        .rank-section {
            flex: 1;
            max-width: 400px;
        }

        .rank-label {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .rank-1 .rank-label {
            color: #FFD700;
            animation: goldGlow 2s ease-in-out infinite;
        }

        .rank-2 .rank-label {
            color: #C0C0C0;
        }

        .rank-3 .rank-label {
            color: #CD7F32;
        }

        @keyframes goldGlow {
            0%, 100% {
                text-shadow: 0 0 20px #FFD700, 0 0 40px #FFD700;
            }
            50% {
                text-shadow: 0 0 30px #FFD700, 0 0 60px #FFD700, 0 0 80px #FFD700;
            }
        }

        .champion-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s;
        }

        .rank-1 .champion-card {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2) 0%, rgba(255, 215, 0, 0.05) 100%);
            border: 3px solid #FFD700;
            box-shadow: 0 10px 40px rgba(255, 215, 0, 0.3);
        }

        .champion-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 4px solid #C8102E;
        }

        .rank-1 .champion-photo {
            width: 200px;
            height: 200px;
            border: 5px solid #FFD700;
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.5);
            animation: photoGlow 2s ease-in-out infinite;
        }

        @keyframes photoGlow {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .champion-name {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #fff;
        }

        .rank-1 .champion-name {
            font-size: 42px;
            color: #FFD700;
        }

        .champion-count {
            font-size: 48px;
            font-weight: 700;
            color: #C8102E;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .rank-1 .champion-count {
            font-size: 64px;
            color: #FFD700;
        }

        .icon {
            font-size: 40px;
            color: #C8102E;
        }

        .rank-1 .icon {
            font-size: 56px;
            color: #FFD700;
        }

        .no-data {
            font-size: 32px;
            color: #999;
            padding: 60px;
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title">
            <i class="fas fa-handshake"></i> Referral Champion
        </div>
        <div class="subtitle">リファーラルチャンピオン</div>

        <?php if (empty($champions)): ?>
            <div class="no-data">
                <i class="fas fa-trophy"></i><br>
                データが登録されていません
            </div>
        <?php else: ?>
            <div class="champions-container">
                <?php foreach ([1, 2, 3] as $rank): ?>
                    <?php if (!empty($grouped[$rank])): ?>
                        <div class="rank-section rank-<?php echo $rank; ?>">
                            <div class="rank-label">
                                <?php echo $rank === 1 ? '🥇 1st' : ($rank === 2 ? '🥈 2nd' : '🥉 3rd'); ?>
                            </div>

                            <?php foreach ($grouped[$rank] as $champion): ?>
                                <div class="champion-card">
                                    <?php if ($champion['photo_path'] && $rank === 1): ?>
                                        <img src="../<?php echo htmlspecialchars($champion['photo_path']); ?>"
                                             alt="<?php echo htmlspecialchars($champion['member_name']); ?>"
                                             class="champion-photo">
                                    <?php endif; ?>

                                    <div class="champion-name">
                                        <?php echo htmlspecialchars($champion['member_name']); ?>
                                    </div>

                                    <div class="champion-count">
                                        <i class="fas fa-handshake icon"></i>
                                        <span><?php echo number_format($champion['count']); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
