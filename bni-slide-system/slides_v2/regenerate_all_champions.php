<?php
/**
 * Regenerate all_champions slide image after deleting value champion data
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "Regenerating all_champions slide...\n\n";

// Get the latest week_date
try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->query("SELECT MAX(week_date) as latest_date FROM champions");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $weekDate = $result['latest_date'];

    echo "Latest week_date: $weekDate\n\n";

    // Regenerate the all_champions slide image
    $success = generateSlideImage('all_champions.php', 95, $weekDate);

    if ($success) {
        echo "✓ Slide image regeneration started\n";
        echo "  The image will be updated in a few seconds.\n\n";
        echo "SUCCESS: all_champions slide regeneration initiated\n";
    } else {
        echo "ERROR: Failed to start slide image regeneration\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
