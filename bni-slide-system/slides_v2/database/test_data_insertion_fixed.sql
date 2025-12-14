-- ========================================
-- BNI Slide System V2 - Test Data Insertion (Fixed)
-- ========================================
-- Created: 2025-12-14
-- Purpose: Insert test data matching actual database schema
-- Note: Run migration_fix_week_date.sql BEFORE running this script
-- ========================================

-- ========================================
-- 1. Visitors Test Data (2-3 entries)
-- ========================================
-- Schema: id, week_date (nullable), visitor_no, name, company_name, specialty, sponsor, attend_member_id
DELETE FROM visitors;

INSERT INTO visitors (
    visitor_no,
    name,
    company_name,
    specialty,
    sponsor,
    attend_member_id,
    week_date
) VALUES
(1, '山田 太郎', '株式会社山田商事', 'Webマーケティング・SEO対策', '佐藤 一郎', NULL, NULL),
(2, '鈴木 花子', 'スズキデザイン事務所', 'グラフィックデザイン・ブランディング', '田中 次郎', NULL, NULL),
(3, '佐々木 健一', '佐々木不動産コンサルティング', '不動産仲介・投資コンサルティング', '高橋 三郎', NULL, NULL);

-- ========================================
-- 2. Substitutes Test Data (2-3 entries)
-- ========================================
-- Schema: id, week_date (nullable), substitute_no, member_id, substitute_company, substitute_name
DELETE FROM substitutes;

INSERT INTO substitutes (
    substitute_no,
    substitute_company,
    substitute_name,
    member_id,
    week_date
) VALUES
(1, '株式会社ABC商事', '伊藤 孝', NULL, NULL),
(2, 'XYZ設計事務所', '渡辺 美咲', NULL, NULL),
(3, 'マルイ工業株式会社', '中村 大輔', NULL, NULL);

-- ========================================
-- 3. New Members Test Data (2-3 entries)
-- ========================================
-- Schema: id, member_id, week_date (nullable)
-- Note: member_id must exist in members table
DELETE FROM new_members;

-- Get actual member IDs and insert
-- Option 1: Using specific member IDs (if you know them)
INSERT INTO new_members (member_id, week_date) VALUES
(5, NULL),
(12, NULL),
(23, NULL);

-- Option 2: Auto-select from members table (commented out - uncomment if needed)
-- INSERT INTO new_members (member_id, week_date)
-- SELECT id, NULL FROM members WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3;

-- ========================================
-- 4. Renewal Members Test Data (2-3 entries)
-- ========================================
-- Schema: id, member_id, week_date (nullable)
-- Note: Table name is renewal_members (NOT renewal)
DELETE FROM renewal_members;

-- Option 1: Using specific member IDs
INSERT INTO renewal_members (member_id, week_date) VALUES
(7, NULL),
(15, NULL),
(28, NULL);

-- Option 2: Auto-select from members table (commented out)
-- INSERT INTO renewal_members (member_id, week_date)
-- SELECT id, NULL FROM members WHERE is_active = 1 ORDER BY created_at ASC LIMIT 3;

-- ========================================
-- 5. Weekly No.1 Test Data
-- ========================================
-- Schema: id, week_date (nullable), category, member_id, count
-- Categories: 'referral', 'visitor', '1to1'
DELETE FROM weekly_no1;

INSERT INTO weekly_no1 (category, member_id, count, week_date) VALUES
('referral', 10, 5, NULL),
('visitor', 8, 3, NULL),
('1to1', 15, 7, NULL);

-- Option 2: Auto-select from members table (commented out)
-- INSERT INTO weekly_no1 (category, member_id, count, week_date)
-- SELECT 'referral', id, 5, NULL FROM members WHERE is_active = 1 LIMIT 1
-- UNION ALL
-- SELECT 'visitor', id, 3, NULL FROM members WHERE is_active = 1 LIMIT 1 OFFSET 1
-- UNION ALL
-- SELECT '1to1', id, 7, NULL FROM members WHERE is_active = 1 LIMIT 1 OFFSET 2;

-- ========================================
-- 6. Share Story Test Data
-- ========================================
-- Schema: id, member_id, week_date (nullable)
DELETE FROM share_story;

INSERT INTO share_story (member_id, week_date) VALUES
(18, NULL);

-- Option 2: Auto-select from members table (commented out)
-- INSERT INTO share_story (member_id, week_date)
-- SELECT id, NULL FROM members WHERE is_active = 1 ORDER BY RANDOM() LIMIT 1;

-- ========================================
-- 7. Main Presenter Test Data
-- ========================================
-- Schema: id, member_id, week_date (nullable), pdf_path, youtube_url
DELETE FROM main_presenter;

INSERT INTO main_presenter (
    member_id,
    youtube_url,
    pdf_path,
    week_date
) VALUES
(25, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL);

-- Option 2: Auto-select from members table (commented out)
-- INSERT INTO main_presenter (member_id, youtube_url, pdf_path, week_date)
-- SELECT id, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL, NULL
-- FROM members WHERE is_active = 1 ORDER BY RANDOM() LIMIT 1;

-- ========================================
-- Verification Queries
-- ========================================
-- Uncomment to verify inserted data:

-- SELECT 'Visitors:' as table_name;
-- SELECT * FROM visitors ORDER BY visitor_no;

-- SELECT 'Substitutes:' as table_name;
-- SELECT * FROM substitutes ORDER BY substitute_no;

-- SELECT 'New Members:' as table_name;
-- SELECT nm.id, nm.member_id, m.name, m.company_name, nm.week_date
-- FROM new_members nm
-- LEFT JOIN members m ON nm.member_id = m.id;

-- SELECT 'Renewal Members:' as table_name;
-- SELECT rm.id, rm.member_id, m.name, m.company_name, rm.week_date
-- FROM renewal_members rm
-- LEFT JOIN members m ON rm.member_id = m.id;

-- SELECT 'Weekly No.1:' as table_name;
-- SELECT w.id, w.category, w.count, m.name, m.company_name, w.week_date
-- FROM weekly_no1 w
-- LEFT JOIN members m ON w.member_id = m.id;

-- SELECT 'Share Story:' as table_name;
-- SELECT ss.id, ss.member_id, m.name, m.company_name, ss.week_date
-- FROM share_story ss
-- LEFT JOIN members m ON ss.member_id = m.id;

-- SELECT 'Main Presenter:' as table_name;
-- SELECT mp.id, mp.member_id, m.name, m.company_name, mp.youtube_url, mp.week_date
-- FROM main_presenter mp
-- LEFT JOIN members m ON mp.member_id = m.id;

-- ========================================
-- Helper Query: Get Available Member IDs
-- ========================================
-- Use this query to find available member IDs for test data:
-- SELECT id, name, company_name, category FROM members WHERE is_active = 1 ORDER BY id LIMIT 30;

-- ========================================
-- Usage Instructions
-- ========================================
-- 1. First, run migration_fix_week_date.sql to update schema:
--    sqlite3 /path/to/bni_slides_v2.db < migration_fix_week_date.sql
--
-- 2. Check available member IDs:
--    sqlite3 /path/to/bni_slides_v2.db "SELECT id, name FROM members WHERE is_active = 1;"
--
-- 3. Update member_id values in this file (lines 30, 31, 32, 42, 43, 44, 53-55, 63, 72)
--
-- 4. Run this test data insertion script:
--    sqlite3 /path/to/bni_slides_v2.db < test_data_insertion_fixed.sql
--
-- 5. Verify data was inserted:
--    sqlite3 /path/to/bni_slides_v2.db "SELECT COUNT(*) FROM visitors; SELECT COUNT(*) FROM substitutes;"
--
-- ========================================
-- Important Notes
-- ========================================
-- - All week_date columns are now nullable (set to NULL)
-- - week_date functionality has been removed from the system
-- - Table name is 'renewal_members' not 'renewal'
-- - substitutes table now has 'substitute_no' column
-- - All member_id foreign keys must reference existing members
-- - visitor and substitute data doesn't require member_id (can be NULL)
-- ========================================
