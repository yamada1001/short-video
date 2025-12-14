<?php
/**
 * テストデータ投入: visitorsテーブル
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "ビジターテストデータ投入\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 既存データを削除
    echo "[1] 既存データを削除中...\n";
    $db->exec('DELETE FROM visitors');
    echo "  ✓ 削除完了\n\n";

    // テストデータ (member_idは実在するメンバーIDを使用)
    $testData = [
        [
            'week_date' => '2025-12-20',
            'visitor_no' => 1,
            'name' => '田中健一',
            'company_name' => 'ABC商事株式会社',
            'specialty' => 'ITコンサルティング',
            'sponsor' => '山田商店',
            'attend_member_id' => 1
        ],
        [
            'week_date' => '2025-12-20',
            'visitor_no' => 2,
            'name' => '佐々木美咲',
            'company_name' => 'デジタルソリューションズ',
            'specialty' => 'Webマーケティング',
            'sponsor' => '鈴木工業',
            'attend_member_id' => 2
        ],
        [
            'week_date' => '2025-12-20',
            'visitor_no' => 3,
            'name' => '中村雄太',
            'company_name' => 'グローバルトレーディング',
            'specialty' => '貿易・輸出入',
            'sponsor' => '高橋建設',
            'attend_member_id' => 3
        ]
    ];

    echo "[2] テストデータを投入中...\n";

    $insertStmt = $db->prepare("
        INSERT INTO visitors
        (week_date, visitor_no, name, company_name, specialty, sponsor, attend_member_id)
        VALUES
        (:week_date, :visitor_no, :name, :company_name, :specialty, :sponsor, :attend_member_id)
    ");

    foreach ($testData as $index => $data) {
        $insertStmt->execute([
            ':week_date' => $data['week_date'],
            ':visitor_no' => $data['visitor_no'],
            ':name' => $data['name'],
            ':company_name' => $data['company_name'],
            ':specialty' => $data['specialty'],
            ':sponsor' => $data['sponsor'],
            ':attend_member_id' => $data['attend_member_id']
        ]);
        echo "  ✓ " . ($index + 1) . ". {$data['name']} ({$data['company_name']}) - {$data['specialty']}\n";
    }

    echo "\n[3] 投入されたデータを確認:\n";
    $checkStmt = $db->query('SELECT * FROM visitors ORDER BY visitor_no ASC');
    $rows = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        echo "  - No.{$row['visitor_no']}: {$row['name']} ({$row['company_name']}) - {$row['specialty']}\n";
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
