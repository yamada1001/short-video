-- Phase 6 Schema Update
-- weekly_presentersテーブルに「ご紹介してほしい方」カラムを追加

-- referral_targetカラムを追加
ALTER TABLE weekly_presenters ADD COLUMN referral_target TEXT;

-- 確認
PRAGMA table_info(weekly_presenters);
