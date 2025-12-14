-- Migration: start_dash_presenterテーブルにpage_numberカラムを追加
-- 作成日: 2025-12-14
-- 説明: p.15とp.107の2つのスタートダッシュページを区別するためのカラム

-- page_numberカラムを追加（デフォルト: 15）
ALTER TABLE start_dash_presenter ADD COLUMN page_number INTEGER NOT NULL DEFAULT 15;

-- 既存データの確認用コメント
-- p.15: 新規メンバー向けスタートダッシュ
-- p.107: ベテランメンバー向けスタートダッシュ
