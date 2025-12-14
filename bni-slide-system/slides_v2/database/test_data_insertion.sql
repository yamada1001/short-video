-- ========================================
-- BNI Slide System V2 テストデータ挿入SQL
-- ========================================
-- 作成日: 2025-12-14
-- 目的: week_date機能削除後のテストデータ投入
-- ========================================

-- ビジターテストデータ（2-3名）
-- ========================================
DELETE FROM visitors; -- 既存データをクリア

INSERT INTO visitors (
    visitor_no,
    name,
    company_name,
    specialty,
    sponsor,
    attend_member_id,
    job_description,
    referral_request
) VALUES
(1, '山田 太郎', '株式会社山田商事', 'Webマーケティング・SEO対策', '佐藤 一郎', NULL,
 'SEO対策、リスティング広告運用、SNSマーケティング、アクセス解析・改善提案',
 '飲食店オーナー、小売店経営者、ECサイト運営者'),
(2, '鈴木 花子', 'スズキデザイン事務所', 'グラフィックデザイン・ブランディング', '田中 次郎', NULL,
 'ロゴデザイン、パンフレット制作、名刺デザイン、ブランディング戦略立案',
 'スタートアップ企業、リブランディングを検討中の企業、新商品発売予定の企業'),
(3, '佐々木 健一', '佐々木不動産コンサルティング', '不動産仲介・投資コンサルティング', '高橋 三郎', NULL,
 '賃貸・売買仲介、投資用物件紹介、資産運用アドバイス、相続対策',
 '転勤予定の方、不動産投資を始めたい方、資産運用に興味がある方');

-- ========================================
-- 代理出席テストデータ（2-3名）
-- ========================================
DELETE FROM substitutes; -- 既存データをクリア

INSERT INTO substitutes (
    substitute_no,
    company_name,
    name
) VALUES
(1, '株式会社ABC商事', '伊藤 孝'),
(2, 'XYZ設計事務所', '渡辺 美咲'),
(3, 'マルイ工業株式会社', '中村 大輔');

-- ========================================
-- 新入会メンバーテストデータ（2-3名）
-- ========================================
-- 注意: member_idは既存のメンバーテーブルに存在するIDを指定してください
-- 以下は例です。実際のIDに置き換えてください。

DELETE FROM new_members; -- 既存データをクリア

-- members テーブルから実際のIDを取得して挿入する場合:
-- INSERT INTO new_members (member_id)
-- SELECT id FROM members WHERE is_active = 1 ORDER BY created_at DESC LIMIT 3;

-- または、既知のIDを直接指定:
INSERT INTO new_members (member_id) VALUES
(5),  -- 既存メンバーID（実際のIDに置き換えてください）
(12), -- 既存メンバーID（実際のIDに置き換えてください）
(23); -- 既存メンバーID（実際のIDに置き換えてください）

-- ========================================
-- 更新メンバーテストデータ（2-3名）
-- ========================================
-- 注意: member_idは既存のメンバーテーブルに存在するIDを指定してください

DELETE FROM renewal; -- 既存データをクリア

-- members テーブルから実際のIDを取得して挿入する場合:
-- INSERT INTO renewal (member_id)
-- SELECT id FROM members WHERE is_active = 1 ORDER BY created_at ASC LIMIT 3;

-- または、既知のIDを直接指定:
INSERT INTO renewal (member_id) VALUES
(7),  -- 既存メンバーID（実際のIDに置き換えてください）
(15), -- 既存メンバーID（実際のIDに置き換えてください）
(28); -- 既存メンバーID（実際のIDに置き換えてください）

-- ========================================
-- 週間No.1テストデータ
-- ========================================
-- 注意: member_idは既存のメンバーテーブルに存在するIDを指定してください

DELETE FROM weekly_no1; -- 既存データをクリア

INSERT INTO weekly_no1 (
    external_referral_member_id,
    external_referral_count,
    visitor_invitation_member_id,
    visitor_invitation_count,
    one_to_one_member_id,
    one_to_one_count
) VALUES
(10, 5, 8, 3, 15, 7); -- 既存メンバーID（実際のIDに置き換えてください）

-- ========================================
-- シェアストーリーテストデータ
-- ========================================
-- 注意: member_idは既存のメンバーテーブルに存在するIDを指定してください

DELETE FROM share_story; -- 既存データをクリア

INSERT INTO share_story (member_id) VALUES
(18); -- 既存メンバーID（実際のIDに置き換えてください）

-- ========================================
-- メインプレゼンターテストデータ
-- ========================================
-- 注意: member_idは既存のメンバーテーブルに存在するIDを指定してください

DELETE FROM main_presenter; -- 既存データをクリア

INSERT INTO main_presenter (
    member_id,
    presentation_type,
    youtube_url
) VALUES
(25, 'simple', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'); -- 既存メンバーID（実際のIDに置き換えてください）

-- ========================================
-- 実行確認用クエリ
-- ========================================
-- 以下のクエリで挿入されたデータを確認できます：

-- ビジター確認
-- SELECT * FROM visitors ORDER BY visitor_no;

-- 代理出席確認
-- SELECT * FROM substitutes ORDER BY substitute_no;

-- 新入会メンバー確認
-- SELECT nm.*, m.name, m.company_name FROM new_members nm LEFT JOIN members m ON nm.member_id = m.id;

-- 更新メンバー確認
-- SELECT r.*, m.name, m.company_name FROM renewal r LEFT JOIN members m ON r.member_id = m.id;

-- 週間No.1確認
-- SELECT w.*,
--        m1.name as external_referral_name,
--        m2.name as visitor_invitation_name,
--        m3.name as one_to_one_name
-- FROM weekly_no1 w
-- LEFT JOIN members m1 ON w.external_referral_member_id = m1.id
-- LEFT JOIN members m2 ON w.visitor_invitation_member_id = m2.id
-- LEFT JOIN members m3 ON w.one_to_one_member_id = m3.id;

-- シェアストーリー確認
-- SELECT ss.*, m.name, m.company_name FROM share_story ss LEFT JOIN members m ON ss.member_id = m.id;

-- メインプレゼンター確認
-- SELECT mp.*, m.name, m.company_name, m.category FROM main_presenter mp LEFT JOIN members m ON mp.member_id = m.id;

-- ========================================
-- 実際のメンバーIDを取得するクエリ
-- ========================================
-- 以下のクエリでアクティブなメンバーのIDを確認できます：

-- SELECT id, name, company_name FROM members WHERE is_active = 1 ORDER BY id LIMIT 30;

-- ========================================
-- 使用方法
-- ========================================
-- 1. SQLiteデータベースに接続
--    sqlite3 /path/to/slides_v2.db
--
-- 2. このSQLファイルを実行
--    .read test_data_insertion.sql
--
-- 3. または、コマンドラインから直接実行
--    sqlite3 /path/to/slides_v2.db < test_data_insertion.sql
--
-- 4. 実際のメンバーIDを確認して、上記のINSERT文のIDを置き換えてください
--
-- ========================================
