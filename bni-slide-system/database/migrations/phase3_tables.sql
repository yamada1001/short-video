-- Phase 3 データベーステーブル作成
-- BNI Slide System V2

-- Phase 3-2: 代理出席管理（substitutes）
CREATE TABLE IF NOT EXISTS substitutes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    substitute_no INTEGER NOT NULL,
    company_name TEXT NOT NULL,
    name TEXT NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_substitutes_week_date ON substitutes(week_date);

-- Phase 3-3: 新入会メンバー管理（new_members）
CREATE TABLE IF NOT EXISTS new_members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    member_id INTEGER NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_new_members_week_date ON new_members(week_date);

-- Phase 3-4: 週間No.1管理（weekly_no1）
CREATE TABLE IF NOT EXISTS weekly_no1 (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL UNIQUE,
    external_referral_member_id INTEGER,
    external_referral_count INTEGER DEFAULT 0,
    visitor_invitation_member_id INTEGER,
    visitor_invitation_count INTEGER DEFAULT 0,
    one_to_one_member_id INTEGER,
    one_to_one_count INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (external_referral_member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (visitor_invitation_member_id) REFERENCES members(id) ON DELETE SET NULL,
    FOREIGN KEY (one_to_one_member_id) REFERENCES members(id) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS idx_weekly_no1_week_date ON weekly_no1(week_date);

-- Phase 3-6: シェアストーリー管理（share_story）
CREATE TABLE IF NOT EXISTS share_story (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL UNIQUE,
    member_id INTEGER NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_share_story_week_date ON share_story(week_date);

-- Phase 3-7: 更新メンバー管理（renewal_members）
CREATE TABLE IF NOT EXISTS renewal_members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    member_id INTEGER NOT NULL,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_renewal_members_week_date ON renewal_members(week_date);

-- Phase 3-8: メンバーピッチ管理（member_pitch_attendance）
CREATE TABLE IF NOT EXISTS member_pitch_attendance (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    member_id INTEGER NOT NULL,
    is_absent INTEGER DEFAULT 0,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    UNIQUE(week_date, member_id)
);

CREATE INDEX IF NOT EXISTS idx_member_pitch_week_date ON member_pitch_attendance(week_date);
