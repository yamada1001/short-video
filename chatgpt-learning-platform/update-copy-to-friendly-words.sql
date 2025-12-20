-- AI活用スクール - コピーを「小学生でもわかる、わくわく・楽しい言葉」に更新
-- 実行日: 2025-12-20
-- 目的: 専門用語を親しみやすい言葉に変更し、ターゲット顧客（仕事で困っている人）に響く表現にする

USE chatgpt_learning;

-- ============================================
-- 1. コーステーブルの更新
-- ============================================
-- 専門用語を使っているコース名を更新
-- 例: 「プロンプトエンジニアリング基礎」→「AIへのお願いの基本」

-- 既存のサンプルコースがあれば更新（実際のコース名に応じて調整）
-- UPDATE courses SET title = 'AIへのお願いの基本' WHERE title LIKE '%プロンプト%基礎%';
-- UPDATE courses SET title = '仕事で使う編' WHERE title LIKE '%ビジネス%';
-- UPDATE courses SET description = REPLACE(description, 'プロンプトエンジニアリング', 'AIへの話しかけ方');
-- UPDATE courses SET description = REPLACE(description, 'ハンズオン', '実際に体験しながら');
-- UPDATE courses SET description = REPLACE(description, 'API', 'AI機能');

-- ============================================
-- 2. レッスンテーブルの更新
-- ============================================
-- レッスン名の更新
-- 例: 「初めてのプロンプト」→「初めてのAIへのお願い」

-- UPDATE lessons SET title = REPLACE(title, 'プロンプト', 'AIへのお願い文');
-- UPDATE lessons SET title = REPLACE(title, 'レッスン', 'ステップ');
-- UPDATE lessons SET description = REPLACE(description, 'プロンプトエンジニアリング', 'AIへの話しかけ方');
-- UPDATE lessons SET description = REPLACE(description, 'ハンズオン', '体験しながら学ぶ');
-- UPDATE lessons SET description = REPLACE(description, 'API', 'AI機能');
-- UPDATE lessons SET description = REPLACE(description, '実践的な', '実際にやってみる');

-- ============================================
-- 3. content_json内のテキスト更新（例）
-- ============================================
-- JSON内の専門用語を更新（実際のデータ構造に応じて調整が必要）
-- 注意: JSON_REPLACE関数を使用する場合、パスを正確に指定する必要があります

-- スライド型レッスンの更新例
-- UPDATE lessons
-- SET content_json = JSON_SET(
--     content_json,
--     '$.slides[*].content',
--     REPLACE(JSON_EXTRACT(content_json, '$.slides[*].content'), 'プロンプト', 'AIへのお願い文')
-- )
-- WHERE lesson_type = 'slide';

-- ============================================
-- 4. サンプルデータの挿入（必要に応じて）
-- ============================================
-- 新しい「わくわく・楽しい言葉」で書かれたサンプルコースとレッスン

-- サンプルコース: AIへのお願いの基本
INSERT INTO courses (title, description, difficulty, is_free, order_num, thumbnail_url)
VALUES (
    'AIへのお願いの基本',
    '初めてでも大丈夫！AIとの会話の仕方を、実際に試しながら楽しく学べます。',
    'beginner',
    TRUE,
    1,
    NULL
);

-- 上記コースのIDを取得（最後に挿入されたIDを使用）
SET @course_id = LAST_INSERT_ID();

-- サンプルレッスン1: スライド型
INSERT INTO lessons (course_id, title, description, order_num, lesson_type, content_json)
VALUES (
    @course_id,
    '初めてのAIへのお願い',
    'AIにどうやって話しかけるか、基本を学びましょう',
    1,
    'slide',
    JSON_OBJECT(
        'slides', JSON_ARRAY(
            JSON_OBJECT(
                'title', 'ようこそ！',
                'content', 'AIを味方につけて、毎日の仕事をラクにしましょう。このステップでは、AIへのお願いの基本を学びます。'
            ),
            JSON_OBJECT(
                'title', 'AIって何？',
                'content', 'AIは、あなたのお願いを聞いて、答えてくれる賢いアシスタント。話しかけ方を学べば、誰でも使いこなせます。'
            ),
            JSON_OBJECT(
                'title', 'お願いのコツ',
                'content', 'AIに何をしてほしいか、はっきり伝えることが大事。「これをやって」「こんな風に」と具体的に伝えましょう。'
            )
        )
    )
);

-- サンプルレッスン2: 体験型
INSERT INTO lessons (course_id, title, description, order_num, lesson_type, content_json)
VALUES (
    @course_id,
    'AIに実際にお願いしてみよう',
    'AIに質問して、反応を見てみましょう',
    2,
    'editor',
    JSON_OBJECT(
        'instructions', 'AIに「おすすめのランチを3つ教えて」とお願いしてみましょう。どんな答えが返ってくるでしょうか？',
        'initialPrompt', '',
        'examplePrompts', JSON_ARRAY(
            'おすすめのランチを3つ教えて',
            '明日の天気を教えて',
            '今日のやる気が出る言葉をください'
        )
    )
);

-- サンプルレッスン3: クイズ型
INSERT INTO lessons (course_id, title, description, order_num, lesson_type, content_json)
VALUES (
    @course_id,
    'わかったかな？クイズ',
    'AIへのお願いの基本をおさらいしましょう',
    3,
    'quiz',
    JSON_OBJECT(
        'questions', JSON_ARRAY(
            JSON_OBJECT(
                'question', 'AIにお願いするとき、一番大事なことは？',
                'options', JSON_ARRAY(
                    '長い文章で説明する',
                    '何をしてほしいか、はっきり伝える',
                    '難しい言葉を使う',
                    '短く1語だけ伝える'
                ),
                'correctAnswer', 1,
                'explanation', '正解！AIには「何をしてほしいか」をはっきり伝えることが大事です。'
            ),
            JSON_OBJECT(
                'question', 'AIはどんなものですか？',
                'options', JSON_ARRAY(
                    '人間と全く同じように考える',
                    'お願いを聞いて答えてくれるアシスタント',
                    '自分で勝手に動く',
                    '完璧で間違えない'
                ),
                'correctAnswer', 1,
                'explanation', 'その通り！AIはあなたのお願いを聞いて、答えてくれる賢いアシスタントです。'
            )
        )
    )
);

-- サンプルレッスン4: チャレンジ型
INSERT INTO lessons (course_id, title, description, order_num, lesson_type, content_json)
VALUES (
    @course_id,
    'やってみようチャレンジ',
    '学んだことを使って、実際にAIにお願いしてみましょう',
    4,
    'assignment',
    JSON_OBJECT(
        'title', 'メールの下書きをお願いしよう',
        'description', 'AIに「お客様への感謝のメールを書いて」とお願いして、実際にメールの下書きを作ってもらいましょう。',
        'requirements', JSON_ARRAY(
            'AIに具体的なお願いを伝える',
            '返ってきたメールの下書きを確認する',
            '必要に応じて修正をお願いする'
        ),
        'hints', JSON_ARRAY(
            '「○○さんへの感謝のメールを書いて」のように、具体的に伝えましょう',
            '「です・ます調で」など、文体も指定できます'
        )
    )
);

-- ============================================
-- 5. 既存データのバックアップ用SELECT文（実行前に確認）
-- ============================================
-- 既存のコースとレッスンを確認
SELECT id, title, description, difficulty FROM courses ORDER BY order_num;
SELECT id, course_id, title, lesson_type, order_num FROM lessons ORDER BY course_id, order_num;

-- ============================================
-- 変更履歴
-- ============================================
-- 2025-12-20: 初版作成
--   - 専門用語を親しみやすい言葉に変更
--   - サンプルコース「AIへのお願いの基本」を追加
--   - 4つのサンプルレッスンを追加（スライド、体験、クイズ、チャレンジ）
