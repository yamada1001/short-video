<?php
/**
 * Check all rank 1 champions in the database
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "=== ALL RANK 1 CHAMPIONS ===\n\n";

    $stmt = $db->query("
        SELECT c.*, m.name as member_name
        FROM champions c
        LEFT JOIN members m ON c.member_id = m.id
        WHERE c.rank = 1 AND c.week_date = (SELECT MAX(week_date) FROM champions)
        ORDER BY c.category
    ");

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Category: " . $row['category'] . "\n";
        echo "Member: " . $row['member_name'] . "\n";
        echo "Count: " . $row['count'] . "\n";
        echo "Week Date: " . $row['week_date'] . "\n";
        echo "---\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
