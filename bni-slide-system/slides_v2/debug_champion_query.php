<?php
require_once __DIR__ . '/config.php';

$db = new PDO('sqlite:' . $db_path);

echo "Testing champion query...\n\n";

// Test 1: Check total champions
$stmt = $db->query("SELECT COUNT(*) as total FROM champions");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Total champions in DB: " . $row['total'] . "\n\n";

// Test 2: Check referral champions
$stmt = $db->query("SELECT * FROM champions WHERE category = 'referral'");
$champs = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Referral champions count: " . count($champs) . "\n";
foreach ($champs as $c) {
    echo "  - ID {$c['id']}: week_date={$c['week_date']}, rank={$c['rank']}, member_id={$c['member_id']}, count={$c['count']}\n";
}
echo "\n";

// Test 3: Test the exact query from the slide
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

echo "Query result count: " . count($champions) . "\n";
foreach ($champions as $c) {
    echo "  - {$c['member_name']}: rank={$c['rank']}, count={$c['count']}\n";
}

// Test 4: Check MAX(week_date)
$stmt = $db->query("SELECT MAX(week_date) as max_date FROM champions WHERE category = 'referral'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "\nMAX(week_date) for referral: " . $row['max_date'] . "\n";
