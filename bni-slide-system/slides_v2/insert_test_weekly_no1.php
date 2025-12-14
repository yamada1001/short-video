<?php
/**
 * テストデータ投入: weekly_no1テーブル
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "週間No.1テストデータ投入\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 既存データを削除
    echo "[1] 既存データを削除中...\n";
    $db->exec('DELETE FROM weekly_no1');
    echo "  ✓ 削除完了\n\n";

    // テストデータ
    $testData = [
        [
            'week_date' => '2025-12-20',
            'category' => 'referral',
            'member_id' => 5,
            'count' => 8
        ],
        [
            'week_date' => '2025-12-20',
            'category' => 'visitor',
            'member_id' => 12,
            'count' => 5
        ],
        [
            'week_date' => '2025-12-20',
            'category' => '1to1',
            'member_id' => 20,
            'count' => 12
        ]
    ];

    echo "[2] テストデータを投入中...\n";

    $insertStmt = $db->prepare("
        INSERT INTO weekly_no1
        (week_date, category, member_id, count)
        VALUES
        (:week_date, :category, :member_id, :count)
    ");

    // メンバー名を取得するためのステートメント
    $memberStmt = $db->prepare("SELECT name, company_name FROM members WHERE id = :id");

    foreach ($testData as $index => $data) {
        $insertStmt->execute([
            ':week_date' => $data['week_date'],
            ':category' => $data['category'],
            ':member_id' => $data['member_id'],
            ':count' => $data['count']
        ]);

        // メンバー情報を取得
        $memberStmt->execute([':id' => $data['member_id']]);
        $member = $memberStmt->fetch(PDO::FETCH_ASSOC);

        $categoryName = [
            'referral' => 'リファーラル',
            'visitor' => 'ビジター招待',
            '1to1' => '1to1'
        ][$data['category']];

        if ($member) {
            echo "  ✓ {$categoryName}: {$member['name']} ({$data['count']}件)\n";
        } else {
            echo "  ✓ {$categoryName}: メンバーID {$data['member_id']} ({$data['count']}件)\n";
        }
    }

    echo "\n[3] 投入されたデータを確認:\n";
    $checkStmt = $db->query('
        SELECT wn.*, m.name, m.company_name
        FROM weekly_no1 wn
        LEFT JOIN members m ON wn.member_id = m.id
        ORDER BY wn.category ASC
    ');
    $rows = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        $categoryName = [
            'referral' => 'リファーラル',
            'visitor' => 'ビジター招待',
            '1to1' => '1to1'
        ][$row['category']];
        echo "  - {$categoryName}: {$row['name']} ({$row['count']}件)\n";
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
