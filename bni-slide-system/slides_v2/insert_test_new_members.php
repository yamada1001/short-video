<?php
/**
 * テストデータ投入: new_membersテーブル
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "新メンバーテストデータ投入\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 既存データを削除
    echo "[1] 既存データを削除中...\n";
    $db->exec('DELETE FROM new_members');
    echo "  ✓ 削除完了\n\n";

    // テストデータ (member_idは実在するメンバーIDを使用)
    $testData = [
        [
            'member_id' => 46,
            'week_date' => '2025-12-20'
        ],
        [
            'member_id' => 47,
            'week_date' => '2025-12-20'
        ],
        [
            'member_id' => 48,
            'week_date' => '2025-12-20'
        ]
    ];

    echo "[2] テストデータを投入中...\n";

    $insertStmt = $db->prepare("
        INSERT INTO new_members
        (member_id, week_date)
        VALUES
        (:member_id, :week_date)
    ");

    // メンバー名を取得するためのステートメント
    $memberStmt = $db->prepare("SELECT name, company_name FROM members WHERE id = :id");

    foreach ($testData as $index => $data) {
        $insertStmt->execute([
            ':member_id' => $data['member_id'],
            ':week_date' => $data['week_date']
        ]);

        // メンバー情報を取得
        $memberStmt->execute([':id' => $data['member_id']]);
        $member = $memberStmt->fetch(PDO::FETCH_ASSOC);

        if ($member) {
            echo "  ✓ " . ($index + 1) . ". {$member['name']} ({$member['company_name']})\n";
        } else {
            echo "  ✓ " . ($index + 1) . ". メンバーID: {$data['member_id']}\n";
        }
    }

    echo "\n[3] 投入されたデータを確認:\n";
    $checkStmt = $db->query('
        SELECT nm.*, m.name, m.company_name
        FROM new_members nm
        LEFT JOIN members m ON nm.member_id = m.id
        ORDER BY nm.id ASC
    ');
    $rows = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        echo "  - {$row['name']} ({$row['company_name']})\n";
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
