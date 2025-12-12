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

    // テストデータ（admin/monthly_ranking.phpの送信形式に合わせる）
    $testData = [
        'referral_amount' => [
            ['rank' => 1, 'name' => '山田太郎', 'value' => 5000000],
            ['rank' => 2, 'name' => '佐藤花子', 'value' => 3500000],
            ['rank' => 3, 'name' => '鈴木次郎', 'value' => 2800000],
            ['rank' => 4, 'name' => '高橋美咲', 'value' => 2100000],
            ['rank' => 5, 'name' => '田中健一', 'value' => 1800000]
        ],
        'visitor_count' => [
            ['rank' => 1, 'name' => '佐藤花子', 'value' => 8],
            ['rank' => 2, 'name' => '山田太郎', 'value' => 7],
            ['rank' => 3, 'name' => '鈴木次郎', 'value' => 6],
            ['rank' => 4, 'name' => '高橋美咲', 'value' => 5],
            ['rank' => 5, 'name' => '田中健一', 'value' => 4]
        ],
        'attendance_rate' => [
            ['rank' => 1, 'name' => '田中健一', 'value' => 100.0],
            ['rank' => 2, 'name' => '山田太郎', 'value' => 95.5],
            ['rank' => 3, 'name' => '佐藤花子', 'value' => 90.0],
            ['rank' => 4, 'name' => '高橋美咲', 'value' => 85.5],
            ['rank' => 5, 'name' => '鈴木次郎', 'value' => 80.0]
        ],
        'one_to_one_count' => [
            ['rank' => 1, 'name' => '高橋美咲', 'value' => 12],
            ['rank' => 2, 'name' => '鈴木次郎', 'value' => 10],
            ['rank' => 3, 'name' => '山田太郎', 'value' => 9],
            ['rank' => 4, 'name' => '佐藤花子', 'value' => 8],
            ['rank' => 5, 'name' => '田中健一', 'value' => 7]
        ]
    ];

    // 既存データを確認
    $existing = dbQueryOne($db, "SELECT COUNT(*) as count FROM monthly_ranking_data WHERE year_month = ?", [$yearMonth]);

    if ($existing['count'] > 0) {
        echo "<p style='color:orange'>⚠️ {$yearMonth} のデータは既に存在します。削除して再作成します...</p>";
        dbExecute($db, "DELETE FROM monthly_ranking_data WHERE year_month = ?", [$yearMonth]);
    }

    // 全てのランキングデータを1つのJSONとして保存（api_save_monthly_ranking.phpと同じ形式）
    $rankingJson = json_encode($testData, JSON_UNESCAPED_UNICODE);

    dbExecute($db,
        "INSERT INTO monthly_ranking_data (year_month, ranking_data, created_at, updated_at)
         VALUES (?, ?, datetime('now', 'localtime'), datetime('now', 'localtime'))",
        [$yearMonth, $rankingJson]
    );

    echo "<p>✅ 月間ランキングデータを作成しました</p>";

    // 作成されたデータを表示
    echo "<h2>作成されたデータ</h2>";

    $row = dbQueryOne($db, "SELECT * FROM monthly_ranking_data WHERE year_month = ?", [$yearMonth]);

    if ($row) {
        $allRankings = json_decode($row['ranking_data'], true);

        $typeNames = [
            'referral_amount' => 'リファーラル金額ランキング',
            'visitor_count' => 'ビジター紹介数ランキング',
            'attendance_rate' => '出席率ランキング',
            'one_to_one_count' => '121回数ランキング'
        ];

        foreach ($allRankings as $rankingType => $rankings) {
            echo "<h3>" . ($typeNames[$rankingType] ?? $rankingType) . "</h3>";
            echo "<table>";
            echo "<tr><th>順位</th><th>名前</th><th>値</th></tr>";

            foreach ($rankings as $ranking) {
                $displayValue = $ranking['value'];
                if ($rankingType === 'referral_amount') {
                    $displayValue = '¥' . number_format($ranking['value']);
                } elseif ($rankingType === 'attendance_rate') {
                    $displayValue = $ranking['value'] . '%';
                } else {
                    $displayValue = $ranking['value'];
                }

                echo "<tr>";
                echo "<td>{$ranking['rank']}</td>";
                echo "<td>" . htmlspecialchars($ranking['name']) . "</td>";
                echo "<td>{$displayValue}</td>";
                echo "</tr>";
            }

            echo "</table>";
        }
    } else {
        echo "<p style='color:red'>データの取得に失敗しました</p>";
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
