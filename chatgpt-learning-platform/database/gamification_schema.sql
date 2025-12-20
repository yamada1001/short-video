-- ================================================================
-- ゲーミフィケーション機能 - データベーススキーマ
-- 作成日: 2025-12-21
--
-- このスクリプトは以下を追加します:
-- 1. AIツールマスターテーブル（ai_tools）
-- 2. バッジマスターテーブル（gamification_badges）
-- 3. ユーザーバッジ獲得履歴（user_badges）
-- 4. ポイント履歴テーブル（user_points）
-- 5. ストリーク記録テーブル（user_streaks）
-- 6. usersテーブルにゲーミフィケーション関連カラム追加
-- 7. coursesテーブルにai_tool_idカラム追加
-- ================================================================

-- 安全のため、トランザクションで実行
START TRANSACTION;

-- ================================================================
-- 1. AIツールマスターテーブル
-- ================================================================

CREATE TABLE IF NOT EXISTS ai_tools (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL COMMENT 'ツール名（ChatGPT, Claude等）',
    category ENUM('chatbot', 'coding', 'image', 'video', 'business', 'agent') NOT NULL COMMENT 'カテゴリー',
    official_url VARCHAR(255) NOT NULL COMMENT '公式サイトURL',
    pricing_type ENUM('free', 'freemium', 'paid') NOT NULL COMMENT '料金体系',
    description TEXT COMMENT 'ツール説明',
    logo_url VARCHAR(255) COMMENT 'ロゴ画像URL',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='AIツールマスターテーブル';

-- ================================================================
-- 2. バッジマスターテーブル
-- ================================================================

CREATE TABLE IF NOT EXISTS gamification_badges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    badge_key VARCHAR(50) UNIQUE NOT NULL COMMENT 'バッジキー（first_step等）',
    name VARCHAR(100) NOT NULL COMMENT 'バッジ名',
    description TEXT COMMENT 'バッジ説明',
    icon_emoji VARCHAR(10) COMMENT 'アイコン絵文字',
    required_condition JSON COMMENT '獲得条件（JSON形式）',
    points_reward INT DEFAULT 0 COMMENT '獲得時のボーナスポイント',
    display_order INT DEFAULT 0 COMMENT '表示順序',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_display_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='バッジマスターテーブル';

-- ================================================================
-- 3. ユーザーバッジ獲得履歴
-- ================================================================

CREATE TABLE IF NOT EXISTS user_badges (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL COMMENT 'ユーザーID',
    badge_id INT NOT NULL COMMENT 'バッジID',
    earned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '獲得日時',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (badge_id) REFERENCES gamification_badges(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_badge (user_id, badge_id),
    INDEX idx_user_id (user_id),
    INDEX idx_earned_at (earned_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ユーザーバッジ獲得履歴';

-- ================================================================
-- 4. ポイント履歴テーブル
-- ================================================================

CREATE TABLE IF NOT EXISTS user_points (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL COMMENT 'ユーザーID',
    points INT NOT NULL COMMENT 'ポイント数（マイナス可）',
    reason VARCHAR(255) COMMENT '理由',
    related_type ENUM('lesson', 'quiz', 'course', 'badge', 'streak', 'other') COMMENT '関連タイプ',
    related_id INT COMMENT '関連ID（lesson_id, badge_id等）',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '付与日時',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ポイント履歴テーブル';

-- ================================================================
-- 5. ストリーク記録テーブル
-- ================================================================

CREATE TABLE IF NOT EXISTS user_streaks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL COMMENT 'ユーザーID',
    activity_date DATE NOT NULL COMMENT '学習日',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_date (user_id, activity_date),
    INDEX idx_user_id (user_id),
    INDEX idx_activity_date (activity_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='ストリーク記録テーブル';

-- ================================================================
-- 6. usersテーブルにゲーミフィケーション関連カラム追加
-- ================================================================

-- total_pointsカラム追加
ALTER TABLE users
ADD COLUMN IF NOT EXISTS total_points INT DEFAULT 0 COMMENT '総獲得ポイント' AFTER password;

-- levelカラム追加
ALTER TABLE users
ADD COLUMN IF NOT EXISTS level INT DEFAULT 1 COMMENT 'レベル' AFTER total_points;

-- current_streakカラム追加
ALTER TABLE users
ADD COLUMN IF NOT EXISTS current_streak INT DEFAULT 0 COMMENT '現在の連続学習日数' AFTER level;

-- longest_streakカラム追加
ALTER TABLE users
ADD COLUMN IF NOT EXISTS longest_streak INT DEFAULT 0 COMMENT '最長連続学習日数' AFTER current_streak;

-- インデックス追加
ALTER TABLE users
ADD INDEX IF NOT EXISTS idx_level (level);

ALTER TABLE users
ADD INDEX IF NOT EXISTS idx_total_points (total_points);

-- ================================================================
-- 7. coursesテーブルにai_tool_idカラム追加
-- ================================================================

-- ai_tool_idカラム追加
ALTER TABLE courses
ADD COLUMN IF NOT EXISTS ai_tool_id INT COMMENT '対応AIツールID' AFTER difficulty;

-- 外部キー制約追加
ALTER TABLE courses
ADD CONSTRAINT fk_courses_ai_tool
FOREIGN KEY (ai_tool_id) REFERENCES ai_tools(id) ON DELETE SET NULL;

-- インデックス追加
ALTER TABLE courses
ADD INDEX IF NOT EXISTS idx_ai_tool_id (ai_tool_id);

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
    AND TABLE_NAME IN ('ai_tools', 'gamification_badges', 'user_badges', 'user_points', 'user_streaks')
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
    AND COLUMN_NAME IN ('total_points', 'level', 'current_streak', 'longest_streak');

-- coursesテーブルの新しいカラムを確認
SELECT
    '追加されたカラム:' AS info,
    COLUMN_NAME,
    COLUMN_TYPE,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'courses'
    AND COLUMN_NAME = 'ai_tool_id';

-- ================================================================
-- コミット（問題なければ）
-- ================================================================

-- 確認後、手動でコミット
-- COMMIT;

-- または問題がある場合はロールバック
-- ROLLBACK;
