-- Phase 4-1: ネットワーキング学習
CREATE TABLE IF NOT EXISTS networking_learning (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    pdf_path TEXT NOT NULL,
    image_paths TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_networking_week ON networking_learning(week_date);

-- Phase 4-2: チャンピオン
CREATE TABLE IF NOT EXISTS champions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    type TEXT NOT NULL CHECK(type IN ('referral', 'value', 'visitor', '1to1', 'ceu')),
    rank INTEGER NOT NULL CHECK(rank IN (1, 2, 3)),
    member_id INTEGER NOT NULL,
    count INTEGER NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_champions_week ON champions(week_date);
CREATE INDEX IF NOT EXISTS idx_champions_type ON champions(type);

-- Phase 4-3: 統計情報
CREATE TABLE IF NOT EXISTS statistics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    stat_type TEXT NOT NULL,
    value TEXT NOT NULL,
    additional_data TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_statistics_week ON statistics(week_date);
CREATE INDEX IF NOT EXISTS idx_statistics_type ON statistics(stat_type);

-- Phase 5-2: 募集カテゴリ
CREATE TABLE IF NOT EXISTS recruiting_categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    category_type TEXT NOT NULL CHECK(category_type IN ('open', 'survey')),
    rank INTEGER,
    category_name TEXT NOT NULL,
    vote_count INTEGER DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_recruiting_week ON recruiting_categories(week_date);
CREATE INDEX IF NOT EXISTS idx_recruiting_type ON recruiting_categories(category_type);

-- Phase 5-3: リファーラル真正度
CREATE TABLE IF NOT EXISTS referral_verification (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    from_member_id INTEGER NOT NULL,
    to_member_id INTEGER NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (to_member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_referral_verify_week ON referral_verification(week_date);

-- Phase 5-4: QRコード
CREATE TABLE IF NOT EXISTS qr_codes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    url TEXT NOT NULL,
    qr_image_path TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_qr_week ON qr_codes(week_date);

-- Phase 5-5: スライド表示/非表示管理
CREATE TABLE IF NOT EXISTS slide_visibility (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    slide_page INTEGER NOT NULL UNIQUE,
    slide_name TEXT NOT NULL,
    is_visible INTEGER NOT NULL DEFAULT 1,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX IF NOT EXISTS idx_slide_visibility_page ON slide_visibility(slide_page);
