<?php
/**
 * Database Migration: Add referrals_weekly table
 * 管理者が入力する週ごとのリファーラル総額を保存するテーブル
 */

require_once __DIR__ . '/../includes/db.php';

echo "========================================\n";
echo "Migration: Create referrals_weekly table\n";
echo "========================================\n\n";

try {
    $db = getDbConnection();

    // Check if table already exists
    $checkQuery = "SELECT name FROM sqlite_master WHERE type='table' AND name='referrals_weekly'";
    $result = dbQuery($db, $checkQuery);

    if (count($result) > 0) {
        echo "✓ Table 'referrals_weekly' already exists.\n";
        dbClose($db);
        exit(0);
    }

    // Create referrals_weekly table
    $createTableSQL = "
    CREATE TABLE referrals_weekly (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        week_date TEXT NOT NULL UNIQUE,
        total_amount INTEGER NOT NULL DEFAULT 0,
        notes TEXT,
        created_at TEXT DEFAULT (datetime('now', 'localtime')),
        updated_at TEXT DEFAULT (datetime('now', 'localtime'))
    )";

    dbExecute($db, $createTableSQL);

    echo "✓ Table 'referrals_weekly' created successfully.\n";

    // Create index on week_date for faster lookups
    $createIndexSQL = "CREATE INDEX idx_referrals_week_date ON referrals_weekly(week_date)";
    dbExecute($db, $createIndexSQL);

    echo "✓ Index on 'week_date' created successfully.\n";

    dbClose($db);

    echo "\n✅ Migration completed successfully!\n\n";
    echo "Table Structure:\n";
    echo "  - id: Primary key\n";
    echo "  - week_date: Week identifier (YYYY-MM-DD format)\n";
    echo "  - total_amount: Total referral amount in yen\n";
    echo "  - notes: Optional notes\n";
    echo "  - created_at: Creation timestamp\n";
    echo "  - updated_at: Last update timestamp\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
