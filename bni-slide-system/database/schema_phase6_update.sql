-- Phase 6 Schema Update
-- weekly_presentersテーブルに「ご紹介してほしい方」カラムを追加

-- referral_targetカラムを追加（既に存在する場合はスキップ）
-- SQLiteはIF NOT EXISTSをサポートしていないため、
-- migrate.phpでカラムの存在チェックを行う
