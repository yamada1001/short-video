<?php
/**
 * p.7 座席表スライド
 * 6つのテーブル（A-F）の座席配置を表示
 */

require_once __DIR__ . '/../config.php';

// 日付パラメータ（デフォルト: 次の金曜日）
$target_date = $_GET['date'] ?? getTargetFriday();

// データベース接続
try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('データベース接続エラー: ' . $e->getMessage());
}

// 座席配置データを取得
$stmt = $db->prepare("
    SELECT
        sa.table_name,
        sa.position,
        m.name as member_name,
        m.company_name,
        m.category
    FROM seating_arrangement sa
    LEFT JOIN members m ON sa.member_id = m.id
    WHERE sa.week_date = :week_date
    ORDER BY sa.table_name, sa.position
");
$stmt->execute(['week_date' => $target_date]);
$seating_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// テーブル別にグループ化
$tables = [];
foreach ($seating_data as $seat) {
    $table_name = $seat['table_name'];
    if (!isset($tables[$table_name])) {
        $tables[$table_name] = [];
    }
    $tables[$table_name][] = $seat;
}

// テーブルが空の場合、デフォルトで6テーブル（A-F）を用意
if (empty($tables)) {
    $tables = ['A' => [], 'B' => [], 'C' => [], 'D' => [], 'E' => [], 'F' => []];
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>座席表 - BNI Slide System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', Meiryo, メイリオ, sans-serif;
            background: linear-gradient(135deg, #C8102E 0%, #8B0000 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px;
        }

        .slide-container {
            width: 100%;
            max-width: 1400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 60px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .slide-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .slide-header h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .slide-header .date {
            font-size: 24px;
            opacity: 0.9;
        }

        .tables-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            margin-top: 40px;
        }

        .table-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 25px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .table-header {
            text-align: center;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        .table-header i {
            margin-right: 10px;
        }

        .seat-list {
            list-style: none;
        }

        .seat-item {
            background: rgba(255, 255, 255, 0.15);
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .seat-item:last-child {
            margin-bottom: 0;
        }

        .seat-number {
            font-size: 20px;
            font-weight: 700;
            min-width: 35px;
            text-align: center;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px;
            border-radius: 6px;
        }

        .seat-info {
            flex: 1;
        }

        .member-name {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .member-details {
            font-size: 14px;
            opacity: 0.8;
        }

        .empty-seat {
            opacity: 0.5;
            font-style: italic;
        }

        /* レスポンシブ対応 */
        @media (max-width: 1200px) {
            .tables-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .tables-grid {
                grid-template-columns: 1fr;
            }

            .slide-header h1 {
                font-size: 36px;
            }
        }
    </style>
</head>
<body>
    <div class="slide-container">
        <div class="slide-header">
            <h1><i class="fas fa-chair"></i> 座席表</h1>
            <div class="date"><?= date('Y年m月d日', strtotime($target_date)) ?></div>
        </div>

        <div class="tables-grid">
            <?php
            $table_names = ['A', 'B', 'C', 'D', 'E', 'F'];
            foreach ($table_names as $table_name):
                $seats = $tables[$table_name] ?? [];
            ?>
                <div class="table-card">
                    <div class="table-header">
                        <i class="fas fa-users"></i> テーブル <?= $table_name ?>
                    </div>
                    <ul class="seat-list">
                        <?php if (empty($seats)): ?>
                            <li class="seat-item empty-seat">
                                <div class="seat-number">-</div>
                                <div class="seat-info">
                                    <div class="member-name">座席なし</div>
                                </div>
                            </li>
                        <?php else: ?>
                            <?php foreach ($seats as $seat): ?>
                                <li class="seat-item <?= empty($seat['member_name']) ? 'empty-seat' : '' ?>">
                                    <div class="seat-number"><?= $seat['position'] ?></div>
                                    <div class="seat-info">
                                        <div class="member-name">
                                            <?= $seat['member_name'] ? htmlspecialchars($seat['member_name']) : '空席' ?>
                                        </div>
                                        <?php if ($seat['company_name'] || $seat['category']): ?>
                                            <div class="member-details">
                                                <?php if ($seat['company_name']): ?>
                                                    <?= htmlspecialchars($seat['company_name']) ?>
                                                <?php endif; ?>
                                                <?php if ($seat['category']): ?>
                                                    / <?= htmlspecialchars($seat['category']) ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
