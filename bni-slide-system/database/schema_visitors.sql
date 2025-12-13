-- Visitor Introduction Table
-- ビジターご紹介データを管理するテーブル

CREATE TABLE IF NOT EXISTS visitor_introductions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,  -- YYYY-MM-DD形式（金曜日の日付）
    visitor_name TEXT NOT NULL,  -- ビジター名
    company TEXT,  -- 会社名（屋号）
    specialty TEXT,  -- 専門分野
    sponsor TEXT NOT NULL,  -- スポンサー（紹介者）
    attendant TEXT NOT NULL,  -- アテンド（同行者）
    display_order INTEGER DEFAULT 0,  -- 表示順序
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Index for faster queries
CREATE INDEX IF NOT EXISTS idx_visitor_week_date ON visitor_introductions(week_date);

-- Networking Learning Corner Presenter Table
-- ネットワーキング学習コーナーの発表者を管理するテーブル

CREATE TABLE IF NOT EXISTS networking_learning_presenters (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,  -- YYYY-MM-DD形式（金曜日の日付）
    presenter_name TEXT NOT NULL,  -- 発表者名
    presenter_email TEXT,  -- 発表者メールアドレス（members.jsonから取得）
    presenter_company TEXT,  -- 発表者の会社名
    presenter_category TEXT,  -- 発表者のカテゴリ
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(week_date)  -- 週ごとに1人のみ
);

-- Index for faster queries
CREATE INDEX IF NOT EXISTS idx_networking_week_date ON networking_learning_presenters(week_date);
