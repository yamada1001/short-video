<?php
// デバッグ用：データベースパスと存在確認
header('Content-Type: text/plain; charset=utf-8');

echo "=== Database Path Test ===\n\n";

require_once __DIR__ . '/config.php';

echo "1. config.php loaded: OK\n";
echo "2. Database path: $db_path\n";
echo "3. File exists: " . (file_exists($db_path) ? "YES" : "NO") . "\n";

if (file_exists($db_path)) {
    echo "4. File size: " . filesize($db_path) . " bytes\n";
    echo "5. Readable: " . (is_readable($db_path) ? "YES" : "NO") . "\n";

    try {
        $db = new PDO('sqlite:' . $db_path);
        echo "6. DB Connection: OK\n";

        $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
        echo "7. Tables: " . implode(", ", $tables) . "\n";

        $count = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
        echo "8. Members count: $count\n";
    } catch (Exception $e) {
        echo "6. DB Connection: FAILED - " . $e->getMessage() . "\n";
    }
} else {
    echo "4. Directory exists: " . (is_dir(dirname($db_path)) ? "YES" : "NO") . "\n";
    echo "5. Directory path: " . dirname($db_path) . "\n";
    echo "6. Files in directory:\n";
    if (is_dir(dirname($db_path))) {
        $files = scandir(dirname($db_path));
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                echo "   - $file\n";
            }
        }
    }
}

echo "\n=== End Test ===\n";
