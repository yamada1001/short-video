-- ========================================
-- BNI Slide System V2 - Database Verification Script
-- ========================================
-- Purpose: Verify database schema and data integrity
-- Usage: sqlite3 /path/to/bni_slides_v2.db < verify_database.sql
-- ========================================

.mode column
.headers on
.width 30 10 60

SELECT '========================================' as '';
SELECT 'DATABASE VERIFICATION REPORT' as '';
SELECT '========================================' as '';
SELECT '' as '';

-- ========================================
-- 1. Table Existence Check
-- ========================================
SELECT '1. TABLE EXISTENCE CHECK' as '';
SELECT '----------------------------------------' as '';

SELECT
    name as table_name,
    'EXISTS' as status
FROM sqlite_master
WHERE type='table'
AND name IN (
    'members', 'visitors', 'substitutes', 'new_members',
    'renewal_members', 'weekly_no1', 'share_story', 'main_presenter'
)
ORDER BY
    CASE name
        WHEN 'members' THEN 1
        WHEN 'visitors' THEN 2
        WHEN 'substitutes' THEN 3
        WHEN 'new_members' THEN 4
        WHEN 'renewal_members' THEN 5
        WHEN 'weekly_no1' THEN 6
        WHEN 'share_story' THEN 7
        WHEN 'main_presenter' THEN 8
    END;

SELECT '' as '';

-- ========================================
-- 2. Schema Verification (week_date nullable)
-- ========================================
SELECT '2. WEEK_DATE NULLABLE CHECK' as '';
SELECT '----------------------------------------' as '';

SELECT
    'visitors' as table_name,
    CASE WHEN (SELECT "notnull" FROM pragma_table_info('visitors') WHERE name='week_date') = 0
        THEN 'NULLABLE ✓' ELSE 'NOT NULL ✗' END as week_date_status;

SELECT
    'substitutes' as table_name,
    CASE WHEN (SELECT "notnull" FROM pragma_table_info('substitutes') WHERE name='week_date') = 0
        THEN 'NULLABLE ✓' ELSE 'NOT NULL ✗' END as week_date_status;

SELECT
    'new_members' as table_name,
    CASE WHEN (SELECT "notnull" FROM pragma_table_info('new_members') WHERE name='week_date') = 0
        THEN 'NULLABLE ✓' ELSE 'NOT NULL ✗' END as week_date_status;

SELECT
    'renewal_members' as table_name,
    CASE WHEN (SELECT "notnull" FROM pragma_table_info('renewal_members') WHERE name='week_date') = 0
        THEN 'NULLABLE ✓' ELSE 'NOT NULL ✗' END as week_date_status;

SELECT
    'share_story' as table_name,
    CASE WHEN (SELECT "notnull" FROM pragma_table_info('share_story') WHERE name='week_date') = 0
        THEN 'NULLABLE ✓' ELSE 'NOT NULL ✗' END as week_date_status;

SELECT
    'main_presenter' as table_name,
    CASE WHEN (SELECT "notnull" FROM pragma_table_info('main_presenter') WHERE name='week_date') = 0
        THEN 'NULLABLE ✓' ELSE 'NOT NULL ✗' END as week_date_status;

SELECT
    'weekly_no1' as table_name,
    CASE WHEN (SELECT "notnull" FROM pragma_table_info('weekly_no1') WHERE name='week_date') = 0
        THEN 'NULLABLE ✓' ELSE 'NOT NULL ✗' END as week_date_status;

SELECT '' as '';

-- ========================================
-- 3. New Columns Check
-- ========================================
SELECT '3. NEW COLUMNS CHECK' as '';
SELECT '----------------------------------------' as '';

SELECT
    'substitutes.substitute_no' as column_check,
    CASE WHEN EXISTS(SELECT 1 FROM pragma_table_info('substitutes') WHERE name='substitute_no')
        THEN 'EXISTS ✓' ELSE 'MISSING ✗' END as status;

SELECT '' as '';

-- ========================================
-- 4. Data Count Check
-- ========================================
SELECT '4. DATA COUNT CHECK' as '';
SELECT '----------------------------------------' as '';

SELECT 'members' as table_name, COUNT(*) as record_count FROM members
UNION ALL SELECT 'visitors', COUNT(*) FROM visitors
UNION ALL SELECT 'substitutes', COUNT(*) FROM substitutes
UNION ALL SELECT 'new_members', COUNT(*) FROM new_members
UNION ALL SELECT 'renewal_members', COUNT(*) FROM renewal_members
UNION ALL SELECT 'weekly_no1', COUNT(*) FROM weekly_no1
UNION ALL SELECT 'share_story', COUNT(*) FROM share_story
UNION ALL SELECT 'main_presenter', COUNT(*) FROM main_presenter;

SELECT '' as '';

-- ========================================
-- 5. Foreign Key Integrity Check
-- ========================================
SELECT '5. FOREIGN KEY INTEGRITY CHECK' as '';
SELECT '----------------------------------------' as '';

-- Check new_members foreign keys
SELECT
    'new_members' as table_name,
    COUNT(*) as total_records,
    COUNT(CASE WHEN member_id IN (SELECT id FROM members) THEN 1 END) as valid_fk,
    COUNT(CASE WHEN member_id NOT IN (SELECT id FROM members) THEN 1 END) as invalid_fk
FROM new_members;

-- Check renewal_members foreign keys
SELECT
    'renewal_members' as table_name,
    COUNT(*) as total_records,
    COUNT(CASE WHEN member_id IN (SELECT id FROM members) THEN 1 END) as valid_fk,
    COUNT(CASE WHEN member_id NOT IN (SELECT id FROM members) THEN 1 END) as invalid_fk
FROM renewal_members;

-- Check weekly_no1 foreign keys
SELECT
    'weekly_no1' as table_name,
    COUNT(*) as total_records,
    COUNT(CASE WHEN member_id IN (SELECT id FROM members) THEN 1 END) as valid_fk,
    COUNT(CASE WHEN member_id NOT IN (SELECT id FROM members) THEN 1 END) as invalid_fk
FROM weekly_no1;

-- Check share_story foreign keys
SELECT
    'share_story' as table_name,
    COUNT(*) as total_records,
    COUNT(CASE WHEN member_id IN (SELECT id FROM members) THEN 1 END) as valid_fk,
    COUNT(CASE WHEN member_id NOT IN (SELECT id FROM members) THEN 1 END) as invalid_fk
FROM share_story;

-- Check main_presenter foreign keys
SELECT
    'main_presenter' as table_name,
    COUNT(*) as total_records,
    COUNT(CASE WHEN member_id IN (SELECT id FROM members) THEN 1 END) as valid_fk,
    COUNT(CASE WHEN member_id NOT IN (SELECT id FROM members) THEN 1 END) as invalid_fk
FROM main_presenter;

SELECT '' as '';

-- ========================================
-- 6. Sample Data Preview
-- ========================================
SELECT '6. SAMPLE DATA PREVIEW' as '';
SELECT '----------------------------------------' as '';

SELECT 'VISITORS:' as '';
SELECT visitor_no, name, company_name FROM visitors LIMIT 3;

SELECT '' as '';
SELECT 'SUBSTITUTES:' as '';
SELECT substitute_no, substitute_name, substitute_company FROM substitutes LIMIT 3;

SELECT '' as '';
SELECT 'NEW MEMBERS:' as '';
SELECT nm.id, m.name, m.company_name
FROM new_members nm
LEFT JOIN members m ON nm.member_id = m.id
LIMIT 3;

SELECT '' as '';
SELECT 'RENEWAL MEMBERS:' as '';
SELECT rm.id, m.name, m.company_name
FROM renewal_members rm
LEFT JOIN members m ON rm.member_id = m.id
LIMIT 3;

SELECT '' as '';
SELECT 'WEEKLY NO.1:' as '';
SELECT w.category, m.name, w.count
FROM weekly_no1 w
LEFT JOIN members m ON w.member_id = m.id;

SELECT '' as '';

-- ========================================
-- 7. Verification Summary
-- ========================================
SELECT '========================================' as '';
SELECT 'VERIFICATION SUMMARY' as '';
SELECT '========================================' as '';

SELECT
    'Expected members count' as check_item,
    '48' as expected,
    (SELECT CAST(COUNT(*) as TEXT) FROM members) as actual,
    CASE WHEN (SELECT COUNT(*) FROM members) = 48 THEN '✓ PASS' ELSE '✗ FAIL' END as status
UNION ALL
SELECT
    'All week_date nullable',
    'YES',
    CASE
        WHEN (SELECT COUNT(*) FROM (
            SELECT 1 FROM pragma_table_info('visitors') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('substitutes') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('new_members') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('renewal_members') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('share_story') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('main_presenter') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('weekly_no1') WHERE name='week_date' AND "notnull"=1
        )) = 0 THEN 'YES' ELSE 'NO'
    END,
    CASE
        WHEN (SELECT COUNT(*) FROM (
            SELECT 1 FROM pragma_table_info('visitors') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('substitutes') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('new_members') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('renewal_members') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('share_story') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('main_presenter') WHERE name='week_date' AND "notnull"=1
            UNION ALL SELECT 1 FROM pragma_table_info('weekly_no1') WHERE name='week_date' AND "notnull"=1
        )) = 0 THEN '✓ PASS' ELSE '✗ FAIL'
    END
UNION ALL
SELECT
    'substitute_no exists',
    'YES',
    CASE WHEN EXISTS(SELECT 1 FROM pragma_table_info('substitutes') WHERE name='substitute_no')
        THEN 'YES' ELSE 'NO' END,
    CASE WHEN EXISTS(SELECT 1 FROM pragma_table_info('substitutes') WHERE name='substitute_no')
        THEN '✓ PASS' ELSE '✗ FAIL' END
UNION ALL
SELECT
    'Test data inserted',
    '> 0 records',
    CASE WHEN (SELECT COUNT(*) FROM visitors) > 0
        AND (SELECT COUNT(*) FROM substitutes) > 0
        THEN 'YES' ELSE 'NO' END,
    CASE WHEN (SELECT COUNT(*) FROM visitors) > 0
        AND (SELECT COUNT(*) FROM substitutes) > 0
        THEN '✓ PASS' ELSE '✗ FAIL' END;

SELECT '' as '';
SELECT '========================================' as '';
SELECT 'END OF VERIFICATION REPORT' as '';
SELECT '========================================' as '';
