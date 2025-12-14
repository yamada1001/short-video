<?php
/**
 * 座席データ確認テスト
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "座席データ確認\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 全データ件数
    $countStmt = $db->query('SELECT COUNT(*) as count FROM seating_arrangement');
    $count = $countStmt->fetch(PDO::FETCH_ASSOC);
    echo "総データ件数: " . $count['count'] . "\n\n";

    // 最新のcreated_at
    $maxStmt = $db->query('SELECT MAX(created_at) as max_created FROM seating_arrangement');
    $maxCreated = $maxStmt->fetch(PDO::FETCH_ASSOC);
    echo "最新のcreated_at: " . ($maxCreated['max_created'] ?? 'なし') . "\n\n";

    // 全データ表示
    echo "全データ一覧:\n";
    $allStmt = $db->query('SELECT * FROM seating_arrangement ORDER BY created_at DESC, table_name, position');

    while ($row = $allStmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  ID: {$row['id']}, テーブル: {$row['table_name']}, メンバーID: {$row['member_id']}, ";
        echo "位置: {$row['position']}, 作成日時: {$row['created_at']}\n";
    }

    echo "\n========================================\n";
    echo "完了\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "エラー: " . $e->getMessage() . "\n";
}
