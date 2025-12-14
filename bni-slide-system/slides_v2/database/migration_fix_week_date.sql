-- ========================================
-- BNI Slide System V2 - Migration Script
-- Purpose: Make week_date columns nullable and fix schema issues
-- Date: 2025-12-14
-- ========================================

-- Note: SQLite does not support ALTER COLUMN directly.
-- We need to recreate tables to modify column constraints.

-- ========================================
-- 1. Fix visitors table
-- ========================================
BEGIN TRANSACTION;

-- Create new table with nullable week_date
CREATE TABLE IF NOT EXISTS visitors_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT,  -- Changed to nullable
    visitor_no INTEGER NOT NULL,
    name TEXT NOT NULL,
    company_name TEXT,
    specialty TEXT,
    sponsor TEXT,
    attend_member_id INTEGER,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (attend_member_id) REFERENCES members(id) ON DELETE SET NULL
);

-- Copy existing data
INSERT INTO visitors_new (id, week_date, visitor_no, name, company_name, specialty, sponsor, attend_member_id, created_at, updated_at)
SELECT id, week_date, visitor_no, name, company_name, specialty, sponsor, attend_member_id, created_at, updated_at
FROM visitors;

-- Drop old table and rename new one
DROP TABLE visitors;
ALTER TABLE visitors_new RENAME TO visitors;

-- Recreate index
CREATE INDEX IF NOT EXISTS idx_visitors_week ON visitors(week_date);

COMMIT;

-- ========================================
-- 2. Fix substitutes table (add substitute_no, make week_date nullable)
-- ========================================
BEGIN TRANSACTION;

-- Create new table with substitute_no and nullable week_date
CREATE TABLE IF NOT EXISTS substitutes_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT,  -- Changed to nullable
    substitute_no INTEGER,  -- Added column
    member_id INTEGER,  -- Changed to nullable for flexibility
    substitute_company TEXT NOT NULL,
    substitute_name TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- Copy existing data
INSERT INTO substitutes_new (id, week_date, member_id, substitute_company, substitute_name, created_at, updated_at)
SELECT id, week_date, member_id, substitute_company, substitute_name, created_at, updated_at
FROM substitutes;

-- Drop old table and rename new one
DROP TABLE substitutes;
ALTER TABLE substitutes_new RENAME TO substitutes;

-- Recreate index
CREATE INDEX IF NOT EXISTS idx_substitutes_week ON substitutes(week_date);

COMMIT;

-- ========================================
-- 3. Fix new_members table (make week_date nullable)
-- ========================================
BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS new_members_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT,  -- Changed to nullable
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

INSERT INTO new_members_new (id, member_id, week_date, created_at, updated_at)
SELECT id, member_id, week_date, created_at, updated_at
FROM new_members;

DROP TABLE new_members;
ALTER TABLE new_members_new RENAME TO new_members;

CREATE INDEX IF NOT EXISTS idx_new_members_week ON new_members(week_date);

COMMIT;

-- ========================================
-- 4. Fix renewal_members table (make week_date nullable)
-- ========================================
BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS renewal_members_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT,  -- Changed to nullable
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

INSERT INTO renewal_members_new (id, member_id, week_date, created_at, updated_at)
SELECT id, member_id, week_date, created_at, updated_at
FROM renewal_members;

DROP TABLE renewal_members;
ALTER TABLE renewal_members_new RENAME TO renewal_members;

COMMIT;

-- ========================================
-- 5. Fix share_story table (make week_date nullable)
-- ========================================
BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS share_story_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT,  -- Changed to nullable
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

INSERT INTO share_story_new (id, member_id, week_date, created_at, updated_at)
SELECT id, member_id, week_date, created_at, updated_at
FROM share_story;

DROP TABLE share_story;
ALTER TABLE share_story_new RENAME TO share_story;

COMMIT;

-- ========================================
-- 6. Fix main_presenter table (make week_date nullable)
-- ========================================
BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS main_presenter_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT,  -- Changed to nullable
    pdf_path TEXT,
    youtube_url TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

INSERT INTO main_presenter_new (id, member_id, week_date, pdf_path, youtube_url, created_at, updated_at)
SELECT id, member_id, week_date, pdf_path, youtube_url, created_at, updated_at
FROM main_presenter;

DROP TABLE main_presenter;
ALTER TABLE main_presenter_new RENAME TO main_presenter;

CREATE INDEX IF NOT EXISTS idx_main_presenter_week ON main_presenter(week_date);

COMMIT;

-- ========================================
-- 7. Fix weekly_no1 table (make week_date nullable)
-- ========================================
BEGIN TRANSACTION;

CREATE TABLE IF NOT EXISTS weekly_no1_new (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT,  -- Changed to nullable
    category TEXT NOT NULL CHECK(category IN ('referral', 'visitor', '1to1')),
    member_id INTEGER NOT NULL,
    count INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

INSERT INTO weekly_no1_new (id, week_date, category, member_id, count, created_at, updated_at)
SELECT id, week_date, category, member_id, count, created_at, updated_at
FROM weekly_no1;

DROP TABLE weekly_no1;
ALTER TABLE weekly_no1_new RENAME TO weekly_no1;

CREATE INDEX IF NOT EXISTS idx_weekly_no1_week ON weekly_no1(week_date);

COMMIT;

-- ========================================
-- Migration completed successfully
-- ========================================
-- All week_date columns are now nullable
-- substitutes table now has substitute_no column
-- All foreign key relationships are maintained
-- All indexes are recreated
-- ========================================
