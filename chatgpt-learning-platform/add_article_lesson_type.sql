-- 記事型レッスンタイプを追加するマイグレーション
-- 実行日: 2025-12-28

USE chatgpt_learning;

-- lesson_typeのENUMに'article'を追加
ALTER TABLE lessons
MODIFY COLUMN lesson_type ENUM('slide', 'editor', 'quiz', 'assignment', 'article') NOT NULL;

-- 確認
SHOW COLUMNS FROM lessons LIKE 'lesson_type';
