<?php
/**
 * 座席管理APIテストスクリプト
 */

$dbPath = __DIR__ . '/../database/bni_slide_v2.db';
$db = new SQLite3($dbPath);

echo "=== 座席管理APIテスト ===\n\n";

// テストデータ削除
echo "1. テストデータ削除中...\n";
$db->exec("DELETE FROM seating_arrangement WHERE week_date = '2025-12-14'");
echo "   ✓ 完了\n\n";

// テストデータ挿入
echo "2. テストデータ挿入中...\n";
$testData = [
    ['A', 1, 1],
    ['A', 2, 2],
    ['A', 3, 3],
    ['B', 4, 1],
    ['B', 5, 2],
    ['C', 6, 1],
    ['C', 7, 2],
    ['C', 8, 3],
    ['C', 9, 4],
];

$stmt = $db->prepare('
    INSERT INTO seating_arrangement (table_name, member_id, position, week_date)
    VALUES (:table_name, :member_id, :position, :week_date)
');

foreach ($testData as [$table, $memberId, $position]) {
    $stmt->bindValue(':table_name', $table, SQLITE3_TEXT);
    $stmt->bindValue(':member_id', $memberId, SQLITE3_INTEGER);
    $stmt->bindValue(':position', $position, SQLITE3_INTEGER);
    $stmt->bindValue(':week_date', '2025-12-14', SQLITE3_TEXT);
    $stmt->execute();
    echo "   - テーブル {$table}: メンバーID {$memberId} (位置 {$position})\n";
}
echo "   ✓ 完了\n\n";

// データ取得テスト
echo "3. データ取得テスト...\n";
$query = "
    SELECT
        sa.table_name,
        sa.position,
        sa.member_id,
        m.name
    FROM seating_arrangement sa
    LEFT JOIN members m ON sa.member_id = m.id
    WHERE sa.week_date = '2025-12-14'
    ORDER BY sa.table_name, sa.position
";

$result = $db->query($query);
$seating = [];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $table = $row['table_name'];
    if (!isset($seating[$table])) {
        $seating[$table] = [];
        echo "\n   テーブル {$table}:\n";
    }
    echo "     {$row['position']}. {$row['name']} (ID: {$row['member_id']})\n";
    $seating[$table][] = $row;
}
echo "\n   ✓ 完了\n\n";

// 統計
echo "4. 統計情報...\n";
$totalMembers = $db->querySingle("SELECT COUNT(*) FROM members WHERE is_active = 1");
$assignedMembers = count($testData);
echo "   - 総メンバー数: {$totalMembers}\n";
echo "   - 配置済み: {$assignedMembers}\n";
echo "   - 未配置: " . ($totalMembers - $assignedMembers) . "\n";
echo "   - テーブル数: " . count($seating) . "\n";
echo "\n   ✓ 完了\n\n";

// スライド表示用データ取得テスト
echo "5. スライド表示用データ取得テスト...\n";
$query = "
    SELECT
        sa.table_name,
        sa.position,
        sa.member_id,
        m.name,
        m.company_name,
        m.category
    FROM seating_arrangement sa
    LEFT JOIN members m ON sa.member_id = m.id
    WHERE sa.week_date = '2025-12-14'
    ORDER BY sa.table_name, sa.position
";

$result = $db->query($query);
$slideData = [];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $table = $row['table_name'];
    if (!isset($slideData[$table])) {
        $slideData[$table] = [];
    }
    $slideData[$table][] = [
        'member_id' => $row['member_id'],
        'name' => $row['name'],
        'company_name' => $row['company_name'],
        'category' => $row['category'],
        'position' => $row['position']
    ];
}

echo json_encode($slideData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
echo "\n   ✓ 完了\n\n";

echo "=== すべてのテスト完了 ===\n";

$db->close();
