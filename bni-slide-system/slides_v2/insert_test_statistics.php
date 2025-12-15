<?php
/**
 * テストデータ投入: statistics（統計情報）
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "統計情報テストデータ投入\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 既存データを削除
    echo "[1] 既存データを削除中...\n";
    $db->exec('DELETE FROM statistics');
    echo "  ✓ 削除完了\n\n";

    // テストデータ（4種類の統計）
    // CHECK制約: type IN ('visitor_total', 'referral', 'sales', 'weekly_goal')
    $weekDate = '2025-12-20';
    $testData = [
        // p.188: ビジター統計
        [
            'week_date' => $weekDate,
            'type' => 'visitor_total',
            'data_json' => json_encode([
                'visitor_total' => 145,
                'visitor_last_week_meetings' => 3,
                'visitor_today_meetings' => 5,
                'visitor_current_members' => 48
            ])
        ],
        // p.189: リファーラル統計
        [
            'week_date' => $weekDate,
            'type' => 'referral',
            'data_json' => json_encode([
                'referral_date' => '2025-12-20',
                'referral_total' => 1250,
                'referral_last_week' => 32,
                'referral_last_week_avg' => 6.4
            ])
        ],
        // p.190: 売上統計
        [
            'week_date' => $weekDate,
            'type' => 'sales',
            'data_json' => json_encode([
                'sales_date' => '2025-12-20',
                'sales_total' => 850000000,
                'sales_growth_rate' => 15.3
            ])
        ],
        // p.302: 週次統計
        [
            'week_date' => $weekDate,
            'type' => 'weekly_goal',
            'data_json' => json_encode([
                'weekly_last_week_visitors' => 3,
                'weekly_this_week_visitors' => 5,
                'weekly_countdown_150' => 5,
                'weekly_target' => 5
            ])
        ]
    ];

    echo "[2] テストデータを投入中...\n";

    $insertStmt = $db->prepare("
        INSERT INTO statistics
        (week_date, type, data_json)
        VALUES
        (:week_date, :type, :data_json)
    ");

    $typeNames = [
        'visitor_total' => 'ビジター統計 (p.188)',
        'referral' => 'リファーラル統計 (p.189)',
        'sales' => '売上統計 (p.190)',
        'weekly_goal' => '週次統計 (p.302)'
    ];

    foreach ($testData as $data) {
        $insertStmt->execute([
            ':week_date' => $data['week_date'],
            ':type' => $data['type'],
            ':data_json' => $data['data_json']
        ]);

        $typeName = $typeNames[$data['type']] ?? $data['type'];
        echo "  ✓ {$typeName}\n";

        $values = json_decode($data['data_json'], true);
        foreach ($values as $key => $val) {
            echo "    - {$key}: {$val}\n";
        }
    }

    echo "\n[3] 投入されたデータを確認:\n";
    $checkStmt = $db->query('SELECT * FROM statistics ORDER BY type ASC');
    $rows = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $typeName = $typeNames[$row['type']] ?? $row['type'];
        echo "  - {$typeName}\n";

        $values = json_decode($row['data_json'], true);
        foreach ($values as $key => $val) {
            echo "    • {$key}: {$val}\n";
        }
    }

    echo "\n✅ テストデータ投入完了\n";
    echo "合計: " . count($rows) . " 件\n";

    echo "\n========================================\n";
    echo "完了\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
    echo "\n詳細:\n";
    echo $e->getTraceAsString() . "\n";
}
