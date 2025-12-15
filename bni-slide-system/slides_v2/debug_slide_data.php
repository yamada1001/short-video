<?php
/**
 * Debug what the slide PHP files see
 */

require_once __DIR__ . '/config.php';

echo "<pre>";
echo "Config loaded.\n\n";

echo "Database path from config: $db_path\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    echo "Database connection: SUCCESS\n\n";

    // Test the exact query from referral_champion.php
    $stmt = $db->prepare("
        SELECT c.*, m.name as member_name, m.photo_path
        FROM champions c
        LEFT JOIN members m ON c.member_id = m.id
        WHERE c.category = 'referral' AND c.week_date = (SELECT MAX(week_date) FROM champions WHERE category = 'referral')
        ORDER BY c.rank, c.count DESC, c.id
    ");
    $stmt->execute();

    $champions = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $champions[] = $row;
    }

    echo "Champions count: " . count($champions) . "\n";
    echo "empty(\$champions): " . (empty($champions) ? 'TRUE' : 'FALSE') . "\n\n";

    if (empty($champions)) {
        echo "WILL SHOW: データが登録されていません\n";
    } else {
        echo "WILL SHOW: Champions list\n\n";
        print_r($champions);
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "</pre>";
