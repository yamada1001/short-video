CREATE TABLE members (
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
CREATE INDEX idx_members_name ON members(name);
CREATE INDEX idx_members_is_active ON members(is_active);
CREATE TABLE main_presenter (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    pdf_path TEXT,
    youtube_url TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP, presentation_type TEXT DEFAULT 'simple',
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE INDEX idx_main_presenter_week ON main_presenter(week_date);
CREATE TABLE visitors (
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
CREATE INDEX idx_visitors_week ON visitors(week_date);
CREATE TABLE new_members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE INDEX idx_new_members_week ON new_members(week_date);
CREATE TABLE weekly_no1 (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    category TEXT NOT NULL CHECK(category IN ('referral', 'visitor', '1to1')),
    member_id INTEGER NOT NULL,
    count INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE INDEX idx_weekly_no1_week ON weekly_no1(week_date);
CREATE TABLE share_story (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE TABLE networking_learning (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    pdf_path TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE champions (
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
CREATE INDEX idx_champions_week_category ON champions(week_date, category);
CREATE TABLE renewal_members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE TABLE member_pitch_attendance (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    member_id INTEGER NOT NULL,
    week_date TEXT NOT NULL,
    is_absent INTEGER NOT NULL DEFAULT 0,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE TABLE recruiting_categories (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    type TEXT NOT NULL CHECK(type IN ('urgent', 'survey')),
    rank INTEGER,
    category_name TEXT NOT NULL,
    vote_count INTEGER,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE statistics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    type TEXT NOT NULL CHECK(type IN ('visitor_total', 'referral', 'sales', 'weekly_goal')),
    data_json TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_statistics_week_type ON statistics(week_date, type);
CREATE TABLE referral_verification (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    from_member_id INTEGER NOT NULL,
    to_member_id INTEGER NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_member_id) REFERENCES members(id) ON DELETE CASCADE,
    FOREIGN KEY (to_member_id) REFERENCES members(id) ON DELETE CASCADE
);
CREATE TABLE qr_codes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    url TEXT NOT NULL,
    qr_code_path TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE slide_visibility (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,
    slide_number INTEGER NOT NULL,
    is_visible INTEGER NOT NULL DEFAULT 1,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX idx_slide_visibility_week_number ON slide_visibility(week_date, slide_number);
CREATE TABLE IF NOT EXISTS "speaker_rotation" (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    rotation_date TEXT NOT NULL,
    main_presenter_id INTEGER,
    referral_target TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (main_presenter_id) REFERENCES members(id) ON DELETE SET NULL
);
CREATE INDEX idx_speaker_rotation_date ON speaker_rotation(rotation_date);
CREATE TABLE IF NOT EXISTS "seating_arrangement" (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    table_name TEXT NOT NULL,
    position INTEGER NOT NULL,
    member_id INTEGER,
    week_date TEXT,  -- NOT NULL removed
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
);
CREATE INDEX idx_seating_week ON seating_arrangement(week_date);
CREATE INDEX idx_seating_member ON seating_arrangement(member_id);
CREATE TABLE start_dash_presenter (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            member_id INTEGER NOT NULL,
            week_date TEXT,
            page_number INTEGER NOT NULL DEFAULT 15,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
        );
CREATE TABLE substitutes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            week_date TEXT,
            member_id INTEGER NOT NULL,
            substitute_company TEXT NOT NULL,
            substitute_name TEXT NOT NULL,
            substitute_no INTEGER NOT NULL DEFAULT 1,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
        );
