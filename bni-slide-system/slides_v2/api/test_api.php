<?php
// APIエラーデバッグ用
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');

echo "=== API Test ===\n\n";

try {
    echo "1. Loading config.php...\n";
    require_once __DIR__ . '/../config.php';
    echo "   OK\n\n";

    echo "2. Database path: $db_path\n";
    echo "3. File exists: " . (file_exists($db_path) ? "YES" : "NO") . "\n\n";

    echo "4. Creating PDO connection...\n";
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   OK\n\n";

    echo "5. Fetching members...\n";
    $stmt = $db->query("SELECT * FROM members WHERE is_active = 1 ORDER BY name");
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "   Count: " . count($members) . "\n\n";

    echo "6. First member:\n";
    if (count($members) > 0) {
        print_r($members[0]);
    }

    echo "\n7. JSON response test:\n";
    $response = [
        'success' => true,
        'members' => $members
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== End Test ===\n";
