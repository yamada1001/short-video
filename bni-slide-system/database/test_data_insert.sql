-- ============================================
-- BNI Slide System V2 - テストデータ投入SQL
-- 作成日: 2025-12-14
-- 対象日付: 2025-12-20 (次の金曜日)
-- ============================================
-- 【重要】このファイルのデータはすべて「【TEST】」プレフィックス付き
-- 削除方法:
--   DELETE FROM テーブル名 WHERE カラム名 LIKE '%【TEST】%';
--   DELETE FROM テーブル名 WHERE week_date = '2025-12-20';
-- ============================================

-- ============================================
-- 1. main_presenter (メインプレゼン管理)
-- 影響ページ: p.8, p.204
-- ============================================
INSERT INTO main_presenter (member_id, week_date, pdf_path, youtube_url, created_at) VALUES
(9, '2025-12-20', '【TEST】/uploads/main_presenter_20251220.pdf', 'https://www.youtube.com/watch?v=【TEST】dQw4w9WgXcQ', datetime('now'));

-- ============================================
-- 2. substitutes (代理出席管理)
-- 影響ページ: p.22-24
-- ============================================
INSERT INTO substitutes (week_date, member_id, substitute_company, substitute_name, created_at) VALUES
('2025-12-20', 18, 'テスト商事株式会社', '【TEST】代理太郎', datetime('now')),
('2025-12-20', 6, 'TEST Corporation', '【TEST】代理花子', datetime('now')),
('2025-12-20', 44, 'テストサービス合同会社', '【TEST】代理次郎', datetime('now'));

-- ============================================
-- 3. new_members (新入会メンバー管理)
-- 影響ページ: p.25-27, p.100-102
-- ============================================
-- 注意: new_membersテーブルはmember_idのみを管理
-- 新入会メンバー用のテストメンバーを追加
INSERT INTO members (name, company_name, category, birthday, photo_path, is_active, created_at) VALUES
('【TEST】新入会太郎', 'テスト株式会社', 'ITコンサルティング', '1985-05-15', NULL, 1, datetime('now')),
('【TEST】新入会花子', 'TEST Corporation', 'Webマーケティング', '1990-08-22', NULL, 1, datetime('now'));

-- 追加されたメンバーIDを取得して登録（ID 49, 50と仮定）
INSERT INTO new_members (member_id, week_date, created_at) VALUES
(49, '2025-12-20', datetime('now')),
(50, '2025-12-20', datetime('now'));

-- ============================================
-- 4. weekly_no1 (週間No.1管理)
-- 影響ページ: p.28
-- ============================================
INSERT INTO weekly_no1 (week_date, category, member_id, count, created_at) VALUES
('2025-12-20', 'referral', 47, 5, datetime('now')),
('2025-12-20', 'visitor', 48, 3, datetime('now')),
('2025-12-20', '1to1', 33, 4, datetime('now'));

-- ============================================
-- 5. share_story (シェアストーリー管理)
-- 影響ページ: p.72
-- ============================================
INSERT INTO share_story (member_id, week_date, created_at) VALUES
(48, '2025-12-20', datetime('now'));

-- ============================================
-- 6. renewal_members (更新メンバー管理)
-- 影響ページ: p.98, p.229
-- ============================================
INSERT INTO renewal_members (member_id, week_date, created_at) VALUES
(33, '2025-12-20', datetime('now')),
(9, '2025-12-20', datetime('now')),
(18, '2025-12-20', datetime('now'));

-- ============================================
-- 7. member_pitch_attendance (メンバーピッチ出席管理)
-- 影響ページ: p.112-166
-- is_absent: 0=出席, 1=欠席
-- ============================================
-- 48名のうち40名出席、8名欠席としてランダムに設定
INSERT INTO member_pitch_attendance (member_id, week_date, is_absent, created_at) VALUES
(1, '2025-12-20', 0, datetime('now')),
(2, '2025-12-20', 0, datetime('now')),
(3, '2025-12-20', 0, datetime('now')),
(4, '2025-12-20', 0, datetime('now')),
(5, '2025-12-20', 0, datetime('now')),
(6, '2025-12-20', 1, datetime('now')),  -- 欠席
(7, '2025-12-20', 0, datetime('now')),
(8, '2025-12-20', 0, datetime('now')),
(9, '2025-12-20', 0, datetime('now')),
(10, '2025-12-20', 0, datetime('now')),
(11, '2025-12-20', 0, datetime('now')),
(12, '2025-12-20', 0, datetime('now')),
(13, '2025-12-20', 1, datetime('now')), -- 欠席
(14, '2025-12-20', 0, datetime('now')),
(15, '2025-12-20', 0, datetime('now')),
(16, '2025-12-20', 0, datetime('now')),
(17, '2025-12-20', 0, datetime('now')),
(18, '2025-12-20', 0, datetime('now')),
(19, '2025-12-20', 0, datetime('now')),
(20, '2025-12-20', 0, datetime('now')),
(21, '2025-12-20', 1, datetime('now')), -- 欠席
(22, '2025-12-20', 0, datetime('now')),
(23, '2025-12-20', 0, datetime('now')),
(24, '2025-12-20', 0, datetime('now')),
(25, '2025-12-20', 0, datetime('now')),
(26, '2025-12-20', 0, datetime('now')),
(27, '2025-12-20', 0, datetime('now')),
(28, '2025-12-20', 0, datetime('now')),
(29, '2025-12-20', 1, datetime('now')), -- 欠席
(30, '2025-12-20', 0, datetime('now')),
(31, '2025-12-20', 0, datetime('now')),
(32, '2025-12-20', 0, datetime('now')),
(33, '2025-12-20', 0, datetime('now')),
(34, '2025-12-20', 0, datetime('now')),
(35, '2025-12-20', 0, datetime('now')),
(36, '2025-12-20', 1, datetime('now')), -- 欠席
(37, '2025-12-20', 0, datetime('now')),
(38, '2025-12-20', 0, datetime('now')),
(39, '2025-12-20', 0, datetime('now')),
(40, '2025-12-20', 0, datetime('now')),
(41, '2025-12-20', 0, datetime('now')),
(42, '2025-12-20', 1, datetime('now')), -- 欠席
(43, '2025-12-20', 0, datetime('now')),
(44, '2025-12-20', 0, datetime('now')),
(45, '2025-12-20', 0, datetime('now')),
(46, '2025-12-20', 0, datetime('now')),
(47, '2025-12-20', 1, datetime('now')), -- 欠席
(48, '2025-12-20', 1, datetime('now')); -- 欠席

-- ============================================
-- 8. networking_learning (ネットワーキング学習管理)
-- 影響ページ: p.74-85 (12ページ)
-- ============================================
INSERT INTO networking_learning (week_date, pdf_path, created_at) VALUES
('2025-12-20', '【TEST】/uploads/networking_learning_20251220.pdf', datetime('now'));

-- ============================================
-- 9. champions (チャンピオン管理)
-- 影響ページ: p.91-96
-- category: 'referral', 'value', 'visitor', '1to1', 'ceu'
-- rank: 1, 2, 3
-- count: 件数・金額・単位など
-- ============================================
-- リファーラルチャンピオン
INSERT INTO champions (week_date, category, rank, member_id, count, created_at) VALUES
('2025-12-20', 'referral', 1, 9, 10, datetime('now')),
('2025-12-20', 'referral', 2, 18, 8, datetime('now')),
('2025-12-20', 'referral', 3, 32, 6, datetime('now'));

-- バリューチャンピオン (金額を整数で格納: 500万円 = 5000000)
INSERT INTO champions (week_date, category, rank, member_id, count, created_at) VALUES
('2025-12-20', 'value', 1, 6, 5000000, datetime('now')),
('2025-12-20', 'value', 2, 34, 3500000, datetime('now')),
('2025-12-20', 'value', 3, 44, 2000000, datetime('now'));

-- ビジターチャンピオン
INSERT INTO champions (week_date, category, rank, member_id, count, created_at) VALUES
('2025-12-20', 'visitor', 1, 45, 3, datetime('now')),
('2025-12-20', 'visitor', 2, 47, 2, datetime('now')),
('2025-12-20', 'visitor', 3, 48, 2, datetime('now'));

-- 1to1チャンピオン
INSERT INTO champions (week_date, category, rank, member_id, count, created_at) VALUES
('2025-12-20', '1to1', 1, 33, 5, datetime('now')),
('2025-12-20', '1to1', 2, 9, 4, datetime('now')),
('2025-12-20', '1to1', 3, 18, 3, datetime('now'));

-- CEUチャンピオン
INSERT INTO champions (week_date, category, rank, member_id, count, created_at) VALUES
('2025-12-20', 'ceu', 1, 32, 8, datetime('now')),
('2025-12-20', 'ceu', 2, 6, 6, datetime('now')),
('2025-12-20', 'ceu', 3, 34, 5, datetime('now'));

-- ============================================
-- 10. statistics (統計情報管理)
-- 影響ページ: p.188-190, p.302
-- type: 'visitor_total', 'referral', 'sales', 'weekly_goal'
-- data_json: JSON形式でデータを格納
-- ============================================
-- ビジター統計 (p.188)
INSERT INTO statistics (week_date, type, data_json, created_at) VALUES
('2025-12-20', 'visitor_total', '{"this_week":7,"last_week":5,"yearly_target":100,"yearly_current":45}', datetime('now'));

-- リファーラル統計 (p.189)
INSERT INTO statistics (week_date, type, data_json, created_at) VALUES
('2025-12-20', 'referral', '{"this_week":25,"last_week":20,"yearly_target":500,"yearly_current":280}', datetime('now'));

-- 売上統計 (p.190)
INSERT INTO statistics (week_date, type, data_json, created_at) VALUES
('2025-12-20', 'sales', '{"this_week":12000000,"last_week":10000000,"yearly_target":300000000,"yearly_current":145000000}', datetime('now'));

-- 週次統計 (p.302)
INSERT INTO statistics (week_date, type, data_json, created_at) VALUES
('2025-12-20', 'weekly_goal', '{"attendance_rate":90,"visitor_count":7,"referral_count":25,"sales_amount":12000000}', datetime('now'));

-- ============================================
-- 11. recruiting_categories (募集カテゴリ管理)
-- 影響ページ: p.185, p.194
-- type: 'urgent' (激しく募集中), 'survey' (アンケート結果)
-- ============================================
-- 激しく募集中のカテゴリ (p.185)
INSERT INTO recruiting_categories (week_date, type, rank, category_name, vote_count, created_at) VALUES
('2025-12-20', 'urgent', 1, '【TEST】税理士', NULL, datetime('now')),
('2025-12-20', 'urgent', 2, '【TEST】社労士', NULL, datetime('now')),
('2025-12-20', 'urgent', 3, '【TEST】Webデザイナー', NULL, datetime('now')),
('2025-12-20', 'urgent', 4, '【TEST】動画制作', NULL, datetime('now')),
('2025-12-20', 'urgent', 5, '【TEST】不動産仲介', NULL, datetime('now'));

-- アンケート結果 (p.194)
INSERT INTO recruiting_categories (week_date, type, rank, category_name, vote_count, created_at) VALUES
('2025-12-20', 'survey', 1, '【TEST】Webデザイナー', 22, datetime('now')),
('2025-12-20', 'survey', 2, '【TEST】不動産仲介', 20, datetime('now')),
('2025-12-20', 'survey', 3, '【TEST】税理士', 18, datetime('now')),
('2025-12-20', 'survey', 4, '【TEST】保険代理店', 16, datetime('now')),
('2025-12-20', 'survey', 5, '【TEST】社労士', 15, datetime('now')),
('2025-12-20', 'survey', 6, '【TEST】人材紹介', 14, datetime('now')),
('2025-12-20', 'survey', 7, '【TEST】動画制作', 12, datetime('now')),
('2025-12-20', 'survey', 8, '【TEST】翻訳サービス', 10, datetime('now'));

-- ============================================
-- 12. referral_verification (リファーラル真正度管理)
-- 影響ページ: p.227
-- ============================================
-- リファーラルの真正度確認データ（from → to のリファーラル）
-- 20件のリファーラル例
INSERT INTO referral_verification (week_date, from_member_id, to_member_id, created_at) VALUES
('2025-12-20', 9, 18, datetime('now')),
('2025-12-20', 9, 32, datetime('now')),
('2025-12-20', 18, 6, datetime('now')),
('2025-12-20', 18, 34, datetime('now')),
('2025-12-20', 32, 44, datetime('now')),
('2025-12-20', 6, 45, datetime('now')),
('2025-12-20', 34, 47, datetime('now')),
('2025-12-20', 44, 48, datetime('now')),
('2025-12-20', 45, 33, datetime('now')),
('2025-12-20', 47, 9, datetime('now')),
('2025-12-20', 48, 18, datetime('now')),
('2025-12-20', 33, 32, datetime('now')),
('2025-12-20', 9, 6, datetime('now')),
('2025-12-20', 18, 34, datetime('now')),
('2025-12-20', 32, 44, datetime('now')),
('2025-12-20', 6, 45, datetime('now')),
('2025-12-20', 34, 47, datetime('now')),
('2025-12-20', 44, 48, datetime('now')),
('2025-12-20', 45, 33, datetime('now')),
('2025-12-20', 47, 9, datetime('now'));

-- ============================================
-- 13. qr_codes (QRコード管理)
-- 影響ページ: p.242
-- ============================================
INSERT INTO qr_codes (week_date, url, qr_code_path, created_at) VALUES
('2025-12-20', 'https://example.com/test-survey', '【TEST】/uploads/qr_codes/test_survey_20251220.png', datetime('now'));

-- ============================================
-- テストデータ投入完了
-- 投入件数:
--   1. main_presenter: 1件
--   2. substitutes: 3件
--   3. new_members: 2件 + members: 2件
--   4. weekly_no1: 3件
--   5. share_story: 1件
--   6. renewal_members: 3件
--   7. member_pitch_attendance: 48件
--   8. networking_learning: 1件
--   9. champions: 15件
--  10. statistics: 4件
--  11. recruiting_categories: 13件
--  12. referral_verification: 20件
--  13. qr_codes: 1件
-- 合計: 約115件
-- ============================================
