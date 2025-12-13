-- VP統計情報スライドのテストデータ更新
-- referral_targetカラムにデータを追加

UPDATE weekly_presenters SET
  topic = 'びわ温熱療法',
  referral_target = 'スナックのママ・看護師・介護士'
WHERE week_date = '2024-12-06';

UPDATE weekly_presenters SET
  topic = 'ラジコン草刈り代行',
  referral_target = '不動産管理会社・風力発電事業者・農家・農機具販売関係者・電力会社・外構業者・ハウスメーカー・工務店'
WHERE week_date = '2024-12-13';

UPDATE weekly_presenters SET
  topic = 'マヤ暦',
  referral_target = '人材育成に関わっている人・教育者・講師・看護師'
WHERE week_date = '2024-12-20';

UPDATE weekly_presenters SET
  topic = '建設資材',
  referral_target = '建設業者・友達になれそうな人'
WHERE week_date = '2024-12-27';

-- 確認
SELECT week_date, member_name, topic, referral_target FROM weekly_presenters WHERE week_date >= '2024-12-01' ORDER BY week_date;
