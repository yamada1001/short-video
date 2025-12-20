-- ================================================================
-- API統合削除スクリプト
-- 実行日: 2025-12-21
--
-- このスクリプトは以下を削除します:
-- 1. api_usage, prompt_cache, assignmentsテーブル
-- 2. editor/assignmentタイプのレッスン
-- 3. lesson_typeのENUM変更
-- ================================================================

-- 安全のため、トランザクションで実行
START TRANSACTION;

-- ================================================================
-- 1. テーブル削除（3テーブル）
-- ================================================================

-- API使用量追跡テーブル削除
DROP TABLE IF EXISTS api_usage;

-- プロンプトキャッシュテーブル削除
DROP TABLE IF EXISTS prompt_cache;

-- 課題提出データテーブル削除
DROP TABLE IF EXISTS assignments;

-- ================================================================
-- 2. editor/assignmentタイプのレッスンを削除
-- ================================================================

-- 削除前に確認（削除されるレッスン数を表示）
SELECT
    '削除されるレッスン数:' AS info,
    COUNT(*) AS count
FROM lessons
WHERE lesson_type IN ('editor', 'assignment');

-- 削除されるレッスンの詳細を表示
SELECT
    id,
    course_id,
    title,
    lesson_type,
    order_num
FROM lessons
WHERE lesson_type IN ('editor', 'assignment')
ORDER BY course_id, order_num;

-- レッスン削除実行
DELETE FROM lessons WHERE lesson_type IN ('editor', 'assignment');

-- ================================================================
-- 3. lesson_typeのENUM変更
-- ================================================================

-- ENUMからeditor, assignmentを削除
ALTER TABLE lessons
MODIFY COLUMN lesson_type ENUM('slide', 'quiz') NOT NULL;

-- ================================================================
-- 4. 削除後の確認
-- ================================================================

-- 残っているレッスンタイプを確認
SELECT
    '残っているレッスンタイプ:' AS info,
    lesson_type,
    COUNT(*) AS count
FROM lessons
GROUP BY lesson_type;

-- 削除されたテーブルを確認
SELECT
    '削除後のテーブル一覧:' AS info,
    TABLE_NAME
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = DATABASE()
ORDER BY TABLE_NAME;

-- ================================================================
-- コミット（問題なければ）
-- 問題がある場合は ROLLBACK; を実行
-- ================================================================

-- 確認後、手動でコミット
-- COMMIT;

-- または問題がある場合はロールバック
-- ROLLBACK;
