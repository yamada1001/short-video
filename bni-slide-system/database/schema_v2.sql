-- BNI Slide System V2 - Database Schema
-- Created: 2025-12-14 00:53
-- SQLite3

-- ===========================================
-- 1. Members - メンバー管理（最重要）
-- ===========================================
CREATE TABLE IF NOT EXISTS members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    company_name TEXT,
    category TEXT,
    photo_path TEXT,
    birthday TEXT,
    is_active INTEGER NOT NULL DEFAULT 1,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_members_name ON members(name);
CREATE INDEX IF NOT EXISTS idx_members_is_active ON members(is_active);

-- ===========================================
-- 2. Seating Arrangement - 座席配置（p.7）
-- ===========================================
CREATE TABLE IF NOT EXISTS seating_arrangement (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    table_name TEXT NOT NULL,
    position INTEGER NOT NULL,
    member_id INTEGER,
    week_date TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_seating_week ON seating_arrangement(week_date);
CREATE INDEX IF NOT EXISTS idx_seating_member ON seating_arrangement(member_id);

-- ===========================================
-- 3. Main Presenter - メインプレゼン（p.8, p.204）
-- ===========================================
CREATE TABLE IF NOT EXISTS main_presenter (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    pdf_path TEXT,
    youtube_url TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_main_presenter_week ON main_presenter(week_date);

-- ===========================================
-- 4. Speaker Rotation - スピーカーローテーション（p.9-14, p.199-203, p.297-301）
-- ===========================================
CREATE TABLE IF NOT EXISTS speaker_rotation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    rotation_date TEXT NOT NULL,
    main_presenter_id INTEGER NOT NULL,
    referral_target TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (main_presenter_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_speaker_rotation_date ON speaker_rotation(rotation_date);

-- ===========================================
-- 5. Start Dash Presenter - スタートダッシュプレゼン（p.15, p.107）
-- ===========================================
CREATE TABLE IF NOT EXISTS start_dash_presenter (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- ===========================================
-- 6. Visitors - ビジター管理（p.19, p.169-180, p.213-224, p.235）
-- ===========================================
CREATE TABLE IF NOT EXISTS visitors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    visitor_no INTEGER NOT NULL,
    name TEXT NOT NULL,
    company_name TEXT,
    specialty TEXT,
    sponsor TEXT,
    attend_member_id INTEGER,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (attend_member_id) REFERENCES members(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_visitors_week ON visitors(week_date);

-- ===========================================
-- 7. Substitutes - 代理出席（p.22-24）
-- ===========================================
CREATE TABLE IF NOT EXISTS substitutes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    member_id INTEGER NOT NULL,
    substitute_company TEXT NOT NULL,
    substitute_name TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_substitutes_week ON substitutes(week_date);

-- ===========================================
-- 8. New Members - 新入会メンバー（p.25-27, p.100-102）
-- ===========================================
CREATE TABLE IF NOT EXISTS new_members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_new_members_week ON new_members(week_date);

-- ===========================================
-- 9. Weekly No.1 - 週間No.1（p.28）
-- ===========================================
CREATE TABLE IF NOT EXISTS weekly_no1 (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    category TEXT NOT NULL CHECK(category IN ('referral', 'visitor', '1to1')),
    member_id INTEGER NOT NULL,
    count INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_weekly_no1_week ON weekly_no1(week_date);

-- ===========================================
-- 10. Share Story - シェアストーリー（p.72）
-- ===========================================
CREATE TABLE IF NOT EXISTS share_story (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- ===========================================
-- 11. Networking Learning - ネットワーキング学習（p.74-85）
-- ===========================================
CREATE TABLE IF NOT EXISTS networking_learning (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    pdf_path TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 12. Champions - チャンピオン（p.91-96）
-- ===========================================
CREATE TABLE IF NOT EXISTS champions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    category TEXT NOT NULL CHECK(category IN ('referral', 'value', 'visitor', '1to1', 'ceu')),
    rank INTEGER NOT NULL CHECK(rank IN (1, 2, 3)),
    member_id INTEGER NOT NULL,
    count INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_champions_week_category ON champions(week_date, category);

-- ===========================================
-- 13. Renewal Members - 更新メンバー（p.98, p.229）
-- ===========================================
CREATE TABLE IF NOT EXISTS renewal_members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- ===========================================
-- 14. Member Pitch Attendance - メンバーピッチ出欠（p.112-166）
-- ===========================================
CREATE TABLE IF NOT EXISTS member_pitch_attendance (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    is_absent INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

-- ===========================================
-- 15. Recruiting Categories - 募集カテゴリ（p.185, p.194）
-- ===========================================
CREATE TABLE IF NOT EXISTS recruiting_categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    type TEXT NOT NULL CHECK(type IN ('urgent', 'survey')),
    rank INTEGER,
    category_name TEXT NOT NULL,
    vote_count INTEGER,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 16. Statistics - 統計情報（p.188, p.189, p.190, p.302）
-- ===========================================
CREATE TABLE IF NOT EXISTS statistics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    type TEXT NOT NULL CHECK(type IN ('visitor_total', 'referral', 'sales', 'weekly_goal')),
    data_json TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_statistics_week_type ON statistics(week_date, type);

-- ===========================================
-- 17. Referral Verification - リファーラル真正度（p.227）
-- ===========================================
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

-- ===========================================
-- 18. QR Codes - QRコード（p.242）
-- ===========================================
CREATE TABLE IF NOT EXISTS qr_codes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    url TEXT NOT NULL,
    qr_code_path TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ===========================================
-- 19. Slide Visibility - スライド表示/非表示管理
-- ===========================================
CREATE TABLE IF NOT EXISTS slide_visibility (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    slide_number INTEGER NOT NULL,
    is_visible INTEGER NOT NULL DEFAULT 1,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_slide_visibility_week_number ON slide_visibility(week_date, slide_number);
