-- Migration: Fix weekly_no1 table schema
-- Date: 2025-12-14
-- Purpose: Update column names to match API expectations

-- Create new table with correct schema
CREATE TABLE IF NOT EXISTS weekly_no1_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT,
    external_referral_member_id INTEGER,
    external_referral_count INTEGER DEFAULT 0,
    visitor_invitation_member_id INTEGER,
    visitor_invitation_count INTEGER DEFAULT 0,
    one_to_one_member_id INTEGER,
    one_to_one_count INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (external_referral_member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (visitor_invitation_member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (one_to_one_member_id) REFERENCES members(id) ON DELETE SET NULL
);

-- Try to copy data from old table if it exists
INSERT OR IGNORE INTO weekly_no1_new
SELECT * FROM weekly_no1;

-- Drop old table
DROP TABLE IF EXISTS weekly_no1;

-- Rename new table
ALTER TABLE weekly_no1_new RENAME TO weekly_no1;
