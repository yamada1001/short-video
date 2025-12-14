<?php
/**
 * BNI Slide System V2 - All Champions Slide (p.96)
 * 全チャンピオン一覧
 */

require_once __DIR__ . '/../config.php';

$db = new PDO('sqlite:' . $db_path);

$targetFriday = getTargetFriday();

$stmt = $db->prepare("
    SELECT c.*, m.name as member_name, m.photo_path
    FROM champions c
    LEFT JOIN members m ON c.member_id = m.id
    WHERE c.week_date = :week_date AND c.rank = 1
    ORDER BY CASE c.type
        WHEN 'referral' THEN 1
        WHEN 'value' THEN 2
        WHEN 'visitor' THEN 3
        WHEN '1to1' THEN 4
        WHEN 'ceu' THEN 5
    END
");
$stmt->bindValue(':week_date', $targetFriday, PDO::PARAM_STR);
$stmt->execute();

$allChampions = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $allChampions[] = $row;
}

$championTypes = [
    'referral' => ['name' => 'Referral', 'jp' => 'リファーラル', 'icon' => 'handshake'],
    'value' => ['name' => 'Value', 'jp' => 'バリュー', 'icon' => 'dollar-sign'],
    'visitor' => ['name' => 'Visitor', 'jp' => 'ビジター', 'icon' => 'users'],
    '1to1' => ['name' => '1to1', 'jp' => '1to1', 'icon' => 'comments'],
    'ceu' => ['name' => 'CEU', 'jp' => 'CEU', 'icon' => 'graduation-cap']
];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>全チャンピオン | BNI Slide System V2</title>
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
            max-width: 1600px;
            padding: 60px;
            text-align: center;
        }

        .title {
            font-size: 72px;
            font-weight: 700;
            color: #FFD700;
            margin-bottom: 20px;
            text-shadow: 0 4px 10px rgba(255, 215, 0, 0.5);
            animation: titleGlow 2s ease-in-out infinite;
        }

        @keyframes titleGlow {
            0%, 100% {
                text-shadow: 0 0 20px #FFD700, 0 0 40px #FFD700;
            }
            50% {
                text-shadow: 0 0 30px #FFD700, 0 0 60px #FFD700, 0 0 80px #FFD700;
            }
        }

        .subtitle {
            font-size: 36px;
            color: #fff;
            margin-bottom: 60px;
            opacity: 0.9;
        }

        .champions-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .champion-card {
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.2) 0%, rgba(255, 215, 0, 0.05) 100%);
            border: 3px solid #FFD700;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(255, 215, 0, 0.3);
            transition: all 0.3s;
        }

        .champion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(255, 215, 0, 0.5);
        }

        .champion-type {
            font-size: 18px;
            font-weight: 600;
            color: #FFD700;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .champion-type-jp {
            font-size: 14px;
            color: #fff;
            opacity: 0.8;
        }

        .champion-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            border: 3px solid #FFD700;
            box-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
        }

        .champion-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #fff;
        }

        .champion-count {
            font-size: 32px;
            font-weight: 700;
            color: #FFD700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .icon {
            font-size: 28px;
        }

        .no-data {
            font-size: 32px;
            color: #999;
            padding: 60px;
            grid-column: 1 / -1;
        }

        .medal {
            font-size: 42px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="title">
            <i class="fas fa-trophy"></i> All Champions
        </div>
        <div class="subtitle">全チャンピオン（1位のみ）</div>

        <?php if (empty($allChampions)): ?>
            <div class="champions-grid">
                <div class="no-data">
                    <i class="fas fa-trophy"></i><br>
                    データが登録されていません
                </div>
            </div>
        <?php else: ?>
            <div class="champions-grid">
                <?php foreach ($allChampions as $champion): ?>
                    <?php $typeInfo = $championTypes[$champion['type']] ?? ['name' => 'Unknown', 'jp' => '不明', 'icon' => 'trophy']; ?>
                    <div class="champion-card">
                        <div class="medal">🥇</div>

                        <div class="champion-type">
                            <i class="fas fa-<?php echo $typeInfo['icon']; ?>"></i>
                            <?php echo $typeInfo['name']; ?>
                        </div>

                        <div class="champion-type-jp">
                            <?php echo $typeInfo['jp']; ?>チャンピオン
                        </div>

                        <?php if ($champion['photo_path']): ?>
                            <img src="../<?php echo htmlspecialchars($champion['photo_path']); ?>"
                                 alt="<?php echo htmlspecialchars($champion['member_name']); ?>"
                                 class="champion-photo">
                        <?php endif; ?>

                        <div class="champion-name">
                            <?php echo htmlspecialchars($champion['member_name']); ?>
                        </div>

                        <div class="champion-count">
                            <i class="fas fa-<?php echo $typeInfo['icon']; ?> icon"></i>
                            <span><?php echo number_format($champion['count']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
