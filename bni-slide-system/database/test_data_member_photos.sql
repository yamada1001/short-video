-- BNI Slide System - Member Photos Test Data
-- 画像から抽出したメンバー情報をテストデータとして投入

-- p.42: バイス・プレジデント（Vice President）- 1名
INSERT INTO member_photos (name, name_highlight, company, industry, position_title, position_title_en, display_order) VALUES
('山本 哲郎', '哲郎', '三豊製畳有限会社', '(内装リフォーム)', 'バイス・プレジデント', 'Vice President', 1);

-- p.43: メンバーシップ委員会（Membership Committee）- 3名
INSERT INTO member_photos (name, name_highlight, company, industry, position_title, position_title_en, display_order) VALUES
('木村 伸嗣', '伸', 'くるま買取ケイヴィレッジ', '(中古車買取)', 'メンバーシップ委員会', 'Membership Committee', 2),
('花本 昭彦', '昭彦', 'HEU合同会社', '(住宅塗装)', 'メンバーシップ委員会', 'Membership Committee', 3),
('藤田 亮', '亮', 'プルデンシャル生命保険', '(生命保険スカウト)', 'メンバーシップ委員会', 'Membership Committee', 4);

-- p.44: メンバーシップ委員会（Membership Committee）続き - 3名
INSERT INTO member_photos (name, name_highlight, company, industry, position_title, position_title_en, display_order) VALUES
('三浦 千尋', '千尋', 'POLA CanMe.towa 畑中店', '(POLAエステティシャン)', 'メンバーシップ委員会', 'Membership Committee', 5),
('蔵山 修一', '修一', '株式会社ワン・ポリシー', '(保険のセカンドオピニオン )', 'メンバーシップ委員会', 'Membership Committee', 6),
('渡邉 美由紀', '美由紀', '株式会社アルファー', '(健康下着)', 'メンバーシップ委員会', 'Membership Committee', 7);

-- p.45: 書記兼会計（Accounting Clerk）- 1名
INSERT INTO member_photos (name, name_highlight, company, industry, position_title, position_title_en, display_order) VALUES
('蔵山 佳子', '佳子', '', '(マヤ暦)', '書記兼会計', 'Accounting Clerk', 8);

-- p.46-54: 一般メンバー（役職なし）を追加
-- ここでは役職名を空にして、一般メンバーとして追加します
-- 実際の画像から抽出したデータを使用

-- p.46: エデュケーション・コーディネーター（Education Coordinator）- 1名
INSERT INTO member_photos (name, name_highlight, company, industry, position_title, position_title_en, display_order) VALUES
('今林 大', '大', '株式会社ソニックジャパン', '(建設業専門保険)', 'エデュケーション・コーディネーター', 'Education Coordinator', 9);

-- p.47: ビジターホスト・コーディネーター（Visitor Host Coordinator）- 1名
INSERT INTO member_photos (name, name_highlight, company, industry, position_title, position_title_en, display_order) VALUES
('早野 大介', '大介', '有限会社 光風', '(便利屋)', 'ビジターホスト・コーディネーター', 'Visitor Host Coordinator', 10);

-- p.48: ビジターホスト（Visitor Host）- 4名
INSERT INTO member_photos (name, name_highlight, company, industry, position_title, position_title_en, display_order) VALUES
('安部 佳雄', '佳雄', '虎ノ門法律事務所', '(弁護士)', 'ビジターホスト', 'Visitor Host', 11),
('勝田 秀伸', '秀伸', 'Food＆Bar  GLANZ', '(ダイニングバー)', 'ビジターホスト', 'Visitor Host', 12),
('河野 理枝', '理枝', 'branche', '(神経施術)', 'ビジターホスト', 'Visitor Host', 13),
('河野 里保', '里保', 'ぬくもりサロン ひつじのうたたね', '(びわ温熱療法)', 'ビジターホスト', 'Visitor Host', 14);
