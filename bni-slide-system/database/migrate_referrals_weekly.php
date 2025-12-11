<?php
/**
 * Database Migration: referrals_weekly table
 * リファーラル週次総額テーブルの作成
 */

require_once __DIR__ . '/../includes/db.php';

try {
    echo "Starting migration: referrals_weekly table...\n";

    $db = getDbConnection();

    // referrals_weeklyテーブルが存在するか確認
    $tableCheck = dbQueryOne($db, "SELECT name FROM sqlite_master WHERE type='table' AND name='referrals_weekly'");

    if ($tableCheck) {
        echo "✓ referrals_weekly table already exists.\n";
    } else {
        // テーブル作成
        $createTable = "
        CREATE TABLE IF NOT EXISTS referrals_weekly (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            week_date TEXT UNIQUE NOT NULL,
            total_amount INTEGER DEFAULT 0,
            notes TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        dbExecute($db, $createTable);
        echo "✓ referrals_weekly table created successfully.\n";

        // インデックス作成
        $createIndex = "CREATE INDEX IF NOT EXISTS idx_referrals_weekly_week_date ON referrals_weekly(week_date)";
        dbExecute($db, $createIndex);
        echo "✓ Index created successfully.\n";
    }

    dbClose($db);

    echo "\nMigration completed successfully!\n";

} catch (Exception $e) {
    echo "ERROR: Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
