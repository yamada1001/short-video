<?php
/**
 * テストデータ投入: substitutesテーブル
 * URL: https://yojitu.com/bni-slide-system/slides_v2/insert_test_substitutes.php
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "代理出席者テストデータ投入\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 既存データを削除
    echo "[1] 既存データを削除中...\n";
    $db->exec('DELETE FROM substitutes');
    echo "  ✓ 削除完了\n\n";

    // テストデータ (member_idは実在するメンバーIDを使用)
    // member_id 1: 高橋, 2: 高野, 3: 渡辺美由紀
    $testData = [
        [
            'member_id' => 1,  // 高橋の代理
            'substitute_company' => 'テスト株式会社',
            'substitute_name' => '山田太郎',
            'substitute_no' => 1
        ],
        [
            'member_id' => 2,  // 高野の代理
            'substitute_company' => 'サンプル商事',
            'substitute_name' => '佐藤花子',
            'substitute_no' => 2
        ],
        [
            'member_id' => 3,  // 渡辺美由紀の代理
            'substitute_company' => 'デモ企画',
            'substitute_name' => '鈴木一郎',
            'substitute_no' => 3
        ]
    ];

    echo "[2] テストデータを投入中...\n";

    $insertStmt = $db->prepare("
        INSERT INTO substitutes
        (week_date, member_id, substitute_company, substitute_name, substitute_no)
        VALUES
        (NULL, :member_id, :substitute_company, :substitute_name, :substitute_no)
    ");

    foreach ($testData as $index => $data) {
        $insertStmt->execute([
            ':member_id' => $data['member_id'],
            ':substitute_company' => $data['substitute_company'],
            ':substitute_name' => $data['substitute_name'],
            ':substitute_no' => $data['substitute_no']
        ]);
        echo "  ✓ " . ($index + 1) . ". {$data['substitute_name']} ({$data['substitute_company']})\n";
    }

    echo "\n[3] 投入されたデータを確認:\n";
    $checkStmt = $db->query('SELECT * FROM substitutes ORDER BY substitute_no ASC');
    $rows = $checkStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        echo "  - No.{$row['substitute_no']}: {$row['substitute_name']} ({$row['substitute_company']})\n";
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
