<?php
/**
 * Create Test Ranking Data
 * 月間ランキングのテストデータを作成
 */

require_once __DIR__ . '/../includes/db.php';

header('Content-Type: text/html; charset=UTF-8');

echo "<h1>月間ランキング テストデータ作成</h1>";
echo "<style>body{font-family:sans-serif;padding:20px}table{border-collapse:collapse;margin:20px 0}th,td{border:1px solid #ddd;padding:8px}th{background:#CF2030;color:white}</style>";

try {
    $db = getDbConnection();

    // 先月のデータを作成
    $previousMonth = new DateTime();
    $previousMonth->modify('-1 month');
    $yearMonth = $previousMonth->format('Y-m');

    echo "<h2>作成する月: {$yearMonth}</h2>";

    // テストデータ
    $testData = [
        'referral_amount' => [
            ['name' => '山田太郎', 'value' => 5000000],
            ['name' => '佐藤花子', 'value' => 3500000],
            ['name' => '鈴木次郎', 'value' => 2800000],
            ['name' => '高橋美咲', 'value' => 2100000],
            ['name' => '田中健一', 'value' => 1800000]
        ],
        'visitor_count' => [
            ['name' => '佐藤花子', 'value' => 8],
            ['name' => '山田太郎', 'value' => 7],
            ['name' => '鈴木次郎', 'value' => 6],
            ['name' => '高橋美咲', 'value' => 5],
            ['name' => '田中健一', 'value' => 4]
        ],
        'attendance_rate' => [
            ['name' => '田中健一', 'value' => 100.0],
            ['name' => '山田太郎', 'value' => 95.5],
            ['name' => '佐藤花子', 'value' => 90.0],
            ['name' => '高橋美咲', 'value' => 85.5],
            ['name' => '鈴木次郎', 'value' => 80.0]
        ],
        'meeting_121_count' => [
            ['name' => '高橋美咲', 'value' => 12],
            ['name' => '鈴木次郎', 'value' => 10],
            ['name' => '山田太郎', 'value' => 9],
            ['name' => '佐藤花子', 'value' => 8],
            ['name' => '田中健一', 'value' => 7]
        ]
    ];

    // 既存データを確認
    $existing = dbQueryOne($db, "SELECT COUNT(*) as count FROM monthly_ranking_data WHERE year_month = ?", [$yearMonth]);

    if ($existing['count'] > 0) {
        echo "<p style='color:orange'>⚠️ {$yearMonth} のデータは既に存在します。削除して再作成します...</p>";
        dbExecute($db, "DELETE FROM monthly_ranking_data WHERE year_month = ?", [$yearMonth]);
    }

    // データを挿入
    foreach ($testData as $rankingType => $rankings) {
        $rankingJson = json_encode($rankings, JSON_UNESCAPED_UNICODE);

        dbExecute($db,
            "INSERT INTO monthly_ranking_data (year_month, ranking_type, ranking_data, created_at, updated_at)
             VALUES (?, ?, ?, datetime('now', 'localtime'), datetime('now', 'localtime'))",
            [$yearMonth, $rankingType, $rankingJson]
        );

        echo "<p>✅ {$rankingType} のランキングデータを作成しました</p>";
    }

    // 作成されたデータを表示
    echo "<h2>作成されたデータ</h2>";

    $allData = dbQuery($db, "SELECT * FROM monthly_ranking_data WHERE year_month = ? ORDER BY ranking_type", [$yearMonth]);

    foreach ($allData as $row) {
        $rankings = json_decode($row['ranking_data'], true);

        $typeNames = [
            'referral_amount' => 'リファーラル金額ランキング',
            'visitor_count' => 'ビジター紹介数ランキング',
            'attendance_rate' => '出席率ランキング',
            'meeting_121_count' => '121回数ランキング'
        ];

        echo "<h3>" . ($typeNames[$row['ranking_type']] ?? $row['ranking_type']) . "</h3>";
        echo "<table>";
        echo "<tr><th>順位</th><th>名前</th><th>値</th></tr>";

        $rank = 1;
        foreach ($rankings as $ranking) {
            $displayValue = $ranking['value'];
            if ($row['ranking_type'] === 'referral_amount') {
                $displayValue = '¥' . number_format($ranking['value']);
            } elseif ($row['ranking_type'] === 'attendance_rate') {
                $displayValue = $ranking['value'] . '%';
            } else {
                $displayValue = $ranking['value'];
            }

            echo "<tr>";
            echo "<td>" . $rank . "</td>";
            echo "<td>" . htmlspecialchars($ranking['name']) . "</td>";
            echo "<td>" . $displayValue . "</td>";
            echo "</tr>";
            $rank++;
        }

        echo "</table>";
    }

    dbClose($db);

    echo "<hr>";
    echo "<h2>✅ テストデータの作成が完了しました！</h2>";
    echo "<p><a href='../admin/monthly_ranking.php'>→ 月間ランキング入力画面で確認</a></p>";
    echo "<p><a href='../admin/slide.php'>→ スライドページで確認（スライドパターンを「月初ランキング」に設定）</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ エラー発生</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
