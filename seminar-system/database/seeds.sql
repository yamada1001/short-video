-- セミナー管理システム - 初期データ（開発用）
-- 作成日: 2025-12-16

-- ==================== サンプルセミナー ====================
INSERT INTO seminars (
    title,
    description,
    venue,
    start_datetime,
    end_datetime,
    registration_deadline,
    price,
    thanks_mail_subject,
    thanks_mail_body,
    mail_sender_name,
    is_active
) VALUES (
    'Webマーケティング入門セミナー',
    'デジタルマーケティングの基礎から実践まで学べる3時間のセミナーです。SEO、SNS活用、広告運用など幅広いテーマを扱います。',
    '東京都渋谷区セミナールームA',
    '2025-01-15 14:00:00',
    '2025-01-15 17:00:00',
    '2025-01-15 13:00:00',
    3000,
    'セミナーご参加ありがとうございました',
    'この度はWebマーケティング入門セミナーにご参加いただき、誠にありがとうございました。\n\n添付のPDFスライドをご確認ください。\n\nまた、アンケートへのご協力もお願いいたします。',
    'セミナー運営事務局',
    1
);

-- ==================== 申込時アンケート質問（共通） ====================
INSERT INTO survey_questions (
    seminar_id,
    survey_type,
    question_text,
    question_type,
    options,
    is_required,
    order_index
) VALUES
(
    NULL,
    'registration',
    '参加目的を教えてください',
    'radio',
    JSON_ARRAY('スキルアップ', '業務に活用', '興味本位', 'その他'),
    1,
    1
),
(
    NULL,
    'registration',
    'どこでこのセミナーを知りましたか？',
    'radio',
    JSON_ARRAY('Webサイト', 'SNS', '友人の紹介', 'その他'),
    0,
    2
),
(
    NULL,
    'registration',
    'ご質問・ご要望がありましたらご記入ください',
    'text',
    NULL,
    0,
    3
);

-- ==================== セミナー後アンケート質問（共通） ====================
INSERT INTO survey_questions (
    seminar_id,
    survey_type,
    question_text,
    question_type,
    options,
    is_required,
    order_index
) VALUES
(
    NULL,
    'post_seminar',
    'セミナーの満足度を教えてください',
    'radio',
    JSON_ARRAY('大変満足', '満足', 'やや不満', '不満'),
    1,
    1
),
(
    NULL,
    'post_seminar',
    '内容は理解できましたか？',
    'radio',
    JSON_ARRAY('十分理解できた', '概ね理解できた', 'やや難しかった', '難しかった'),
    1,
    2
),
(
    NULL,
    'post_seminar',
    '今後取り上げてほしいテーマ（複数選択可）',
    'checkbox',
    JSON_ARRAY('SNSマーケティング', 'SEO対策', '広告運用', 'データ分析', 'その他'),
    0,
    3
),
(
    NULL,
    'post_seminar',
    'ご感想・ご意見をお聞かせください',
    'text',
    NULL,
    0,
    4
);
