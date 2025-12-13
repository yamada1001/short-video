-- BNI Slide System - Member Photos Extension
-- SQLite3
-- メンバー紹介スライド用のテーブル拡張

-- 新しい member_photos テーブルを作成（usersとは別管理）
CREATE TABLE IF NOT EXISTS member_photos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,  -- usersテーブルとの紐付け（任意）
    name TEXT NOT NULL,  -- メンバー名（フルネーム）
    name_highlight TEXT,  -- 名前の赤強調部分（例: "哲郎" → 山本哲郎の「哲郎」が赤）
    company TEXT,  -- 会社名
    industry TEXT,  -- 業種
    photo_url TEXT,  -- メンバー写真のパス（uploads/member_photos/xxx.jpg）
    position_title TEXT,  -- 役職名（日本語）例: "バイス・プレジデント"
    position_title_en TEXT,  -- 役職名（英語）例: "Vice President"
    display_order INTEGER DEFAULT 999,  -- メンバー紹介スライドでの表示順
    is_active INTEGER DEFAULT 1,  -- アクティブフラグ（1: 表示, 0: 非表示）
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- インデックス
CREATE INDEX IF NOT EXISTS idx_member_photos_user_id ON member_photos(user_id);
CREATE INDEX IF NOT EXISTS idx_member_photos_display_order ON member_photos(display_order);
CREATE INDEX IF NOT EXISTS idx_member_photos_position_title ON member_photos(position_title);
