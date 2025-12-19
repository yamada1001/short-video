-- ChatGPT学習プラットフォーム データベーススキーマ
-- MySQL 8.0+

-- データベース作成
CREATE DATABASE IF NOT EXISTS chatgpt_learning CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chatgpt_learning;

-- 1. ユーザーテーブル
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255),  -- Google認証の場合はNULL
    name VARCHAR(100) NOT NULL,
    oauth_provider ENUM('email', 'google') DEFAULT 'email',  -- 認証方法
    google_id VARCHAR(255) UNIQUE,  -- Google ID（Google認証の場合）
    subscription_status ENUM('free', 'active', 'canceled', 'past_due') DEFAULT 'free',
    stripe_customer_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_google_id (google_id),
    INDEX idx_subscription (subscription_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. サブスクリプションテーブル
CREATE TABLE subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    stripe_subscription_id VARCHAR(255) UNIQUE,
    plan_type ENUM('monthly', 'yearly') NOT NULL,
    status ENUM('active', 'canceled', 'past_due', 'trialing') NOT NULL,
    current_period_start TIMESTAMP NULL,
    current_period_end TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. コーステーブル
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    difficulty ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    is_free BOOLEAN DEFAULT FALSE,
    order_num INT NOT NULL,
    thumbnail_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order (order_num),
    INDEX idx_is_free (is_free)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. レッスンテーブル
CREATE TABLE lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    order_num INT NOT NULL,
    lesson_type ENUM('slide', 'editor', 'quiz', 'assignment') NOT NULL,
    content_json JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_course_order (course_id, order_num),
    INDEX idx_type (lesson_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. ユーザー進捗テーブル
CREATE TABLE user_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_lesson (lesson_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. クイズ結果テーブル
CREATE TABLE quiz_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    score INT NOT NULL,
    max_score INT NOT NULL,
    answers_json JSON,
    passed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX idx_user_lesson (user_id, lesson_id),
    INDEX idx_passed (passed)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. 課題提出テーブル
CREATE TABLE assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    submitted_prompt TEXT NOT NULL,
    chatgpt_response TEXT,
    score INT,
    feedback TEXT,
    status ENUM('submitted', 'graded') DEFAULT 'submitted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX idx_user_lesson (user_id, lesson_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. API使用量追跡テーブル（コスト管理）
CREATE TABLE api_usage (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    endpoint VARCHAR(100) NOT NULL,
    tokens_used INT NOT NULL,
    cost_usd DECIMAL(10, 6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, created_at),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. プロンプトキャッシュテーブル（コスト削減）
CREATE TABLE prompt_cache (
    id INT PRIMARY KEY AUTO_INCREMENT,
    prompt_hash VARCHAR(64) UNIQUE NOT NULL,
    prompt_text TEXT NOT NULL,
    response_text TEXT NOT NULL,
    model VARCHAR(50) NOT NULL,
    hit_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_hash (prompt_hash),
    INDEX idx_last_used (last_used_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. パスワード再発行トークンテーブル
CREATE TABLE password_reset_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- サンプルデータ挿入

-- サンプルコース
INSERT INTO courses (title, description, difficulty, is_free, order_num, thumbnail_url) VALUES
('ChatGPT基礎コース', 'ChatGPTの基本的な使い方を学びます', 'beginner', TRUE, 1, '/assets/images/course-basic.jpg'),
('プロンプトエンジニアリング入門', '効果的なプロンプトの書き方をマスター', 'intermediate', FALSE, 2, '/assets/images/course-prompt.jpg'),
('実務活用コース', 'ビジネスでの実践的な使い方を学習', 'advanced', FALSE, 3, '/assets/images/course-business.jpg');

-- サンプルレッスン（基礎コース）
INSERT INTO lessons (course_id, title, description, order_num, lesson_type, content_json) VALUES
(1, 'ChatGPTとは？', 'ChatGPTの概要を学びます', 1, 'slide',
'{"slides": [{"title": "ChatGPTとは", "content": "ChatGPTは対話型のAIアシスタントです。"}, {"title": "何ができる？", "content": "文章作成、翻訳、質問応答など様々なタスクが可能です。"}]}'),

(1, '初めてのプロンプト', '実際にChatGPTにプロンプトを送ってみましょう', 2, 'editor',
'{"instructions": "以下のプロンプトを入力して実行してみましょう：「こんにちは！自己紹介してください。」", "hint": "丁寧に挨拶することで、ChatGPTも丁寧に返答してくれます。"}'),

(1, '基礎知識クイズ', 'ChatGPTの基礎知識を確認', 3, 'quiz',
'{"questions": [{"question": "ChatGPTは何ができますか？", "type": "multiple", "options": ["文章作成", "画像生成", "音楽作成", "動画編集"], "correct": [0], "explanation": "ChatGPTは主に文章生成が得意です。"}]}'),

(1, '課題：自己紹介文を作成', 'ChatGPTを使って自己紹介文を作成してください', 4, 'assignment',
'{"task": "ChatGPTに自分の経歴や趣味を伝えて、魅力的な自己紹介文を作成してもらってください。", "criteria": "具体性、読みやすさ、魅力の3点で採点します。"}');

-- サンプルレッスン（プロンプトエンジニアリングコース）
INSERT INTO lessons (course_id, title, description, order_num, lesson_type, content_json) VALUES
(2, 'プロンプトの基本構造', '効果的なプロンプトの構成要素を学びます', 1, 'slide',
'{"slides": [{"title": "プロンプトの構成", "content": "役割、タスク、制約条件の3要素が重要です。"}]}'),

(2, 'ロールプレイ実践', 'ChatGPTに役割を与えてみましょう', 2, 'editor',
'{"instructions": "「あなたはプロのマーケターです。新商品のキャッチコピーを考えてください。」のように役割を指定してプロンプトを作成してください。"}');

-- 管理者ユーザー作成（パスワード: admin123）
INSERT INTO users (email, password_hash, name, subscription_status) VALUES
('admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '管理者', 'active');

-- 完了
SELECT 'データベース初期化完了' AS status;
