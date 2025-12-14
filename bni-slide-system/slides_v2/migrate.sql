-- Migration: Make seating_arrangement.week_date nullable
PRAGMA foreign_keys = OFF;

BEGIN TRANSACTION;

-- Create new table with nullable week_date
CREATE TABLE seating_arrangement_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    table_name TEXT NOT NULL,
    position INTEGER NOT NULL,
    member_id INTEGER,
    week_date TEXT,  -- NOT NULL removed
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

-- Copy data
INSERT INTO seating_arrangement_new (id, table_name, position, member_id, week_date, created_at, updated_at)
SELECT id, table_name, position, member_id, week_date, created_at, updated_at
FROM seating_arrangement;

-- Drop old table
DROP TABLE seating_arrangement;

-- Rename new table
ALTER TABLE seating_arrangement_new RENAME TO seating_arrangement;

-- Create indexes
CREATE INDEX idx_seating_week ON seating_arrangement(week_date);
CREATE INDEX idx_seating_member ON seating_arrangement(member_id);

COMMIT;

PRAGMA foreign_keys = ON;
