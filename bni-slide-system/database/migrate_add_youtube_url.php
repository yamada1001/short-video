<?php
/**
 * Migration: Add youtube_url column to survey_data table
 */

require_once __DIR__ . '/../includes/db.php';

try {
    $db = getDbConnection();

    echo "Starting migration: Add youtube_url column\n";

    // Add youtube_url column to survey_data table
    $sql = "ALTER TABLE survey_data ADD COLUMN youtube_url TEXT";

    dbExecute($db, $sql);

    echo "✓ Added youtube_url column to survey_data table\n";
    echo "Migration completed successfully!\n";

    dbClose($db);

} catch (Exception $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
