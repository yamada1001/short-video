<?php
/**
 * Delete all value champion data from database
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check how many value champions exist
    $stmt = $db->query("SELECT COUNT(*) as count FROM champions WHERE category = 'value'");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

    echo "Found $count value champion record(s)\n\n";

    if ($count > 0) {
        // Delete all value champions
        $stmt = $db->prepare("DELETE FROM champions WHERE category = 'value'");
        $stmt->execute();

        echo "✓ Deleted all value champion data\n\n";

        // Verify deletion
        $stmt = $db->query("SELECT COUNT(*) as count FROM champions WHERE category = 'value'");
        $remaining = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

        echo "Remaining value champions: $remaining\n\n";

        if ($remaining == 0) {
            echo "SUCCESS: All value champion data removed from database\n";
        } else {
            echo "WARNING: Some value champion data still exists\n";
        }
    } else {
        echo "No value champion data to delete\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
