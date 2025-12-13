-- BNI Slide System - Phase 6 Database Schema
-- SQLite3
-- 追加管理機能テーブル

-- 出欠確認管理テーブル
CREATE TABLE IF NOT EXISTS attendance_check (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT UNIQUE NOT NULL,  -- 金曜日の日付 (YYYY-MM-DD)
    members_data TEXT NOT NULL,  -- JSON形式でメンバーの出欠データを保存
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_attendance_check_week_date ON attendance_check(week_date);

-- 更新メンバー管理テーブル
CREATE TABLE IF NOT EXISTS renewal_members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT UNIQUE NOT NULL,  -- 金曜日の日付 (YYYY-MM-DD)
    member_ids TEXT NOT NULL,  -- JSON形式で更新メンバーのIDリストを保存
    notes TEXT,  -- メモ
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_renewal_members_week_date ON renewal_members(week_date);

-- ウィークリープレゼン管理テーブル
CREATE TABLE IF NOT EXISTS weekly_presenters (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT UNIQUE NOT NULL,  -- 金曜日の日付 (YYYY-MM-DD)
    member_id INTEGER,  -- プレゼン担当メンバーID
    member_name TEXT,  -- プレゼン担当メンバー名
    topic TEXT,  -- プレゼントピック
    notes TEXT,  -- メモ
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES users(id)
);

CREATE INDEX idx_weekly_presenters_week_date ON weekly_presenters(week_date);
CREATE INDEX idx_weekly_presenters_member_id ON weekly_presenters(member_id);

-- VP統計情報管理テーブル
CREATE TABLE IF NOT EXISTS vp_statistics (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT UNIQUE NOT NULL,  -- 金曜日の日付 (YYYY-MM-DD)
    stats_data TEXT NOT NULL,  -- JSON形式で統計データを保存
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_vp_statistics_week_date ON vp_statistics(week_date);
