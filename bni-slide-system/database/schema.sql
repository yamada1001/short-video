-- BNI Slide System Database Schema
-- SQLite3

-- ユーザー/メンバーテーブル
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE NOT NULL,
    name TEXT NOT NULL,
    password_hash TEXT,
    phone TEXT,
    company TEXT,
    category TEXT,
    industry TEXT,
    role TEXT DEFAULT 'member',
    is_active INTEGER DEFAULT 1,
    require_2fa INTEGER DEFAULT 0,
    totp_secret TEXT,
    last_login DATETIME,
    htpasswd_user TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);

-- 週次データテーブル（メインデータ）
CREATE TABLE IF NOT EXISTS survey_data (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT NOT NULL,  -- 金曜日の日付 (YYYY-MM-DD)
    timestamp DATETIME NOT NULL,
    input_date DATE NOT NULL,
    user_id INTEGER NOT NULL,
    user_name TEXT NOT NULL,
    user_email TEXT NOT NULL,
    attendance TEXT NOT NULL,
    thanks_slips INTEGER DEFAULT 0,
    one_to_one INTEGER DEFAULT 0,
    activities TEXT,
    comments TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE INDEX idx_survey_data_week_date ON survey_data(week_date);
CREATE INDEX idx_survey_data_user_id ON survey_data(user_id);
CREATE INDEX idx_survey_data_timestamp ON survey_data(timestamp);

-- ビジターテーブル
CREATE TABLE IF NOT EXISTS visitors (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    survey_data_id INTEGER NOT NULL,
    visitor_name TEXT NOT NULL,
    visitor_company TEXT,
    visitor_industry TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (survey_data_id) REFERENCES survey_data(id) ON DELETE CASCADE
);

CREATE INDEX idx_visitors_survey_data_id ON visitors(survey_data_id);

-- リファーラルテーブル
CREATE TABLE IF NOT EXISTS referrals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    survey_data_id INTEGER NOT NULL,
    referral_name TEXT NOT NULL,
    referral_amount INTEGER DEFAULT 0,
    referral_category TEXT,
    referral_provider TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (survey_data_id) REFERENCES survey_data(id) ON DELETE CASCADE
);

CREATE INDEX idx_referrals_survey_data_id ON referrals(survey_data_id);

-- 監査ログテーブル
CREATE TABLE IF NOT EXISTS audit_logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    action TEXT NOT NULL,  -- create, update, delete
    target TEXT NOT NULL,  -- survey_data, user, etc.
    user_email TEXT NOT NULL,
    user_name TEXT NOT NULL,
    data TEXT,  -- JSON形式
    ip_address TEXT,
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_audit_logs_created_at ON audit_logs(created_at);
CREATE INDEX idx_audit_logs_user_email ON audit_logs(user_email);
CREATE INDEX idx_audit_logs_action ON audit_logs(action);
