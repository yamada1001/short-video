-- VP統計情報スライドのテストデータ
-- weekly_presentersテーブルにスピーカーローテーションデータを挿入

-- 既存データを削除（テスト用）
DELETE FROM weekly_presenters WHERE week_date IN (
  '2024-12-06',
  '2024-12-13',
  '2024-12-20',
  '2024-12-27',
  '2025-01-03'
);

-- スピーカーローテーション用データ挿入
INSERT INTO weekly_presenters (week_date, member_id, member_name, topic, notes, created_at, updated_at) VALUES
  ('2024-12-06', 1, '河野 里保', 'スナックのママ・看護師・介護士', 'テストデータ', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  ('2024-12-13', 2, '伊東 健太', '不動産管理会社・風力発電事業者・農家・農機具販売関係者・電力会社・外構業者・ハウスメーカー・工務店', 'ラジコン草刈り代行', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  ('2024-12-20', 3, '鷲山 佳子', '人材育成に関わっている人・教育者・講師・看護師', 'マヤ暦', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  ('2024-12-27', 4, '南金山 真幸', '建設業者・友達になれそうな人', '建設資材', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
  ('2025-01-03', 5, '山田 太郎', 'IT関連・Web制作者・デザイナー', 'システム開発', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- 確認
SELECT * FROM weekly_presenters WHERE week_date >= '2024-12-01' ORDER BY week_date;
