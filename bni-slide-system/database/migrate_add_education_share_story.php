<?php
/**
 * BNI Slide System - Database Migration
 * Add education presenter and share story fields to survey_data table
 */

require_once __DIR__ . '/../includes/db.php';

echo "=== Migration: Add Education & Share Story Fields ===\n\n";

try {
    $db = getDbConnection();

    echo "Checking current schema...\n";

    // Check if columns already exist
    $result = $db->query("PRAGMA table_info(survey_data)");
    $columns = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $row['name'];
    }

    $columnsToAdd = [
        'is_share_story' => 'INTEGER DEFAULT 0',
        'is_education_presenter' => 'INTEGER DEFAULT 0',
        'education_file_path' => 'TEXT',
        'education_file_original_name' => 'TEXT',
        'education_file_type' => 'TEXT'
    ];

    $needsMigration = false;
    foreach ($columnsToAdd as $column => $definition) {
        if (!in_array($column, $columns)) {
            $needsMigration = true;
            break;
        }
    }

    if (!$needsMigration) {
        echo "✓ All columns already exist. No migration needed.\n";
        dbClose($db);
        exit(0);
    }

    echo "Starting migration...\n\n";

    dbBeginTransaction($db);

    // Add new columns
    foreach ($columnsToAdd as $column => $definition) {
        if (!in_array($column, $columns)) {
            echo "Adding column: {$column}...\n";
            $sql = "ALTER TABLE survey_data ADD COLUMN {$column} {$definition}";
            $db->exec($sql);
            echo "✓ Added: {$column}\n";
        } else {
            echo "- Skipped (already exists): {$column}\n";
        }
    }

    // Remove old columns (attendance, thanks_slips, one_to_one, activities, comments)
    // Note: SQLite doesn't support DROP COLUMN directly, so we'll leave them for backwards compatibility
    // They will simply be ignored by the new code

    echo "\nNote: Old columns (attendance, thanks_slips, one_to_one, activities, comments) are kept for backwards compatibility.\n";

    dbCommit($db);

    echo "\n=== Migration Completed Successfully ===\n\n";

    // Verify new schema
    echo "New schema:\n";
    $result = $db->query("PRAGMA table_info(survey_data)");
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        echo "  - {$row['name']} ({$row['type']})\n";
    }

    dbClose($db);

} catch (Exception $e) {
    if (isset($db)) {
        dbRollback($db);
        dbClose($db);
    }
    echo "\n❌ Migration Failed: " . $e->getMessage() . "\n";
    exit(1);
}
