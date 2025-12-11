<?php
/**
 * Migration: Add monthly_ranking_data table
 * 月間ランキングデータを保存するテーブル
 */

require_once __DIR__ . '/../includes/db.php';

try {
    $db = getDbConnection();

    echo "Starting migration: Add monthly_ranking_data table\n";

    // Create monthly_ranking_data table
    $sql = "CREATE TABLE IF NOT EXISTS monthly_ranking_data (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        year_month TEXT UNIQUE NOT NULL,  -- YYYY-MM形式
        ranking_data TEXT NOT NULL,  -- JSON形式でランキングデータ保存
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";

    dbExecute($db, $sql);

    echo "✓ Created monthly_ranking_data table\n";

    // Create index
    $indexSql = "CREATE INDEX IF NOT EXISTS idx_monthly_ranking_year_month
                 ON monthly_ranking_data(year_month)";
    dbExecute($db, $indexSql);

    echo "✓ Created index on year_month\n";
    echo "Migration completed successfully!\n";

    dbClose($db);

} catch (Exception $e) {
    echo "✗ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
