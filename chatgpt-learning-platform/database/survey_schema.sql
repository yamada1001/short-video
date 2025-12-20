-- ================================================================
-- アンケート機能 - データベーススキーマ
-- 作成日: 2025-12-21
--
-- このスクリプトは以下を追加します:
-- 1. アンケート質問マスターテーブル（survey_questions）
-- 2. ユーザーアンケート回答テーブル（user_survey_responses）
-- 3. usersテーブルにsurvey_completed_atカラム追加
-- ================================================================

-- 安全のため、トランザクションで実行
START TRANSACTION;

-- ================================================================
-- 1. アンケート質問マスターテーブル
-- ================================================================

CREATE TABLE IF NOT EXISTS survey_questions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question_key VARCHAR(50) UNIQUE NOT NULL COMMENT '質問キー（learning_goal等）',
    question_text TEXT NOT NULL COMMENT '質問文',
    question_type ENUM('single', 'multiple', 'text') NOT NULL COMMENT '質問タイプ',
    options JSON COMMENT '選択肢（JSON配列）',
    display_order INT DEFAULT 0 COMMENT '表示順序',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='アンケート質問マスターテーブル';

-- ================================================================
-- 2. ユーザーアンケート回答テーブル
-- ================================================================

CREATE TABLE IF NOT EXISTS user_survey_responses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL COMMENT 'ユーザーID',
    question_id INT NOT NULL COMMENT '質問ID',
    answer_value TEXT COMMENT '回答値（JSON配列または文字列）',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '回答日時',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES survey_questions(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_question (user_id, question_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ユーザーアンケート回答テーブル';

-- ================================================================
-- 3. usersテーブルにsurvey_completed_atカラム追加
-- ================================================================

ALTER TABLE users
ADD COLUMN IF NOT EXISTS survey_completed_at TIMESTAMP NULL COMMENT 'アンケート完了日時' AFTER longest_streak;

-- インデックス追加
ALTER TABLE users
ADD INDEX IF NOT EXISTS idx_survey_completed (survey_completed_at);

-- ================================================================
-- 確認
-- ================================================================

-- 追加されたテーブル一覧を表示
SELECT
    '追加されたテーブル:' AS info,
    TABLE_NAME,
    TABLE_COMMENT
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME IN ('survey_questions', 'user_survey_responses')
ORDER BY TABLE_NAME;

-- usersテーブルの新しいカラムを確認
SELECT
    '追加されたカラム:' AS info,
    COLUMN_NAME,
    COLUMN_TYPE,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'users'
    AND COLUMN_NAME = 'survey_completed_at';

-- ================================================================
-- コミット（問題なければ）
-- ================================================================

-- 確認後、手動でコミット
-- COMMIT;

-- または問題がある場合はロールバック
-- ROLLBACK;
