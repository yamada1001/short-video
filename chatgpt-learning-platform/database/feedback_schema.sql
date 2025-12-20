-- ================================================================
-- フィードバック機能DBスキーマ
-- 作成日: 2025-12-21
--
-- 機能: ユーザーからのフィードバック（質問・バグ報告・要望）を管理
-- 将来: Timerex連携、Slack通知などに拡張可能
-- ================================================================

-- ================================================================
-- 1. user_feedbackテーブル作成
-- ================================================================

CREATE TABLE IF NOT EXISTS user_feedback (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lesson_id INT NULL COMMENT 'どのレッスンからのフィードバックか（NULL可: 全般的なFB）',
    feedback_type ENUM('question', 'bug', 'request', 'other') NOT NULL DEFAULT 'question' COMMENT 'フィードバックタイプ',
    message TEXT NOT NULL COMMENT 'フィードバック内容',
    reply_message TEXT NULL COMMENT '運営からの返信',
    status ENUM('pending', 'in_progress', 'completed') NOT NULL DEFAULT 'pending' COMMENT '対応状況',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '送信日時',
    replied_at TIMESTAMP NULL COMMENT '返信日時',
    replied_by INT NULL COMMENT '返信者（管理者ID）',

    -- 外部キー
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE SET NULL,

    -- インデックス
    INDEX idx_user_id (user_id),
    INDEX idx_lesson_id (lesson_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ================================================================
-- 2. 確認クエリ
-- ================================================================

-- テーブル構造確認
SHOW CREATE TABLE user_feedback;

-- テーブルが空であることを確認
SELECT COUNT(*) as feedback_count FROM user_feedback;

-- ================================================================
-- コミット確認
-- トランザクション処理のため、手動でCOMMITを実行してください
-- ================================================================

-- START TRANSACTION;
-- （上記のCREATE TABLE文を実行）
-- SELECT 'フィードバックテーブルを作成しました' as message;
-- COMMIT;
