-- ================================================================
-- アンケート質問マスターデータ投入
-- 作成日: 2025-12-21
--
-- 質問数: 5問
-- カテゴリー: 学習目的, 経験レベル, 興味分野, 学習時間, フィードバック
-- ================================================================

-- 既存データを削除（再投入時）
TRUNCATE TABLE survey_questions;

-- ================================================================
-- アンケート質問投入
-- ================================================================

INSERT INTO survey_questions (question_key, question_text, question_type, options, display_order) VALUES
-- Q1: 学習目的（複数選択可）
('learning_goal', 'AIツールを学ぶ目的は何ですか？（複数選択可）', 'multiple', JSON_ARRAY(
    '業務効率化・生産性向上',
    'キャリアアップ・スキルアップ',
    '副業・フリーランスで稼ぎたい',
    '趣味・個人プロジェクト',
    '最新技術のキャッチアップ',
    '転職・就職活動',
    'その他'
), 1),

-- Q2: 経験レベル（単一選択）
('experience_level', 'AIツール（ChatGPT等）の使用経験はどのくらいですか？', 'single', JSON_ARRAY(
    '全く使ったことがない',
    '少し使ったことがある（月1-2回程度）',
    '定期的に使っている（週1-2回程度）',
    '日常的に使っている（ほぼ毎日）',
    '業務で頻繁に活用している'
), 2),

-- Q3: 興味のある分野（複数選択可）
('interest_areas', 'どの分野に興味がありますか？（複数選択可）', 'multiple', JSON_ARRAY(
    '対話型AI（ChatGPT, Claude, Gemini等）',
    'コーディングAI（Cursor, GitHub Copilot等）',
    '画像生成AI（Midjourney, Stable Diffusion等）',
    '動画・音声AI（Runway, ElevenLabs等）',
    'ビジネスAI（Notion AI, Grammarly等）',
    'AIエージェント（Manus等）',
    'その他'
), 3),

-- Q4: 学習可能時間（単一選択）
('available_time', '週にどのくらいの時間を学習に割けますか？', 'single', JSON_ARRAY(
    '1時間未満',
    '1〜3時間',
    '3〜5時間',
    '5〜10時間',
    '10時間以上'
), 4),

-- Q5: 自由記述（任意）
('feedback', 'このプラットフォームに期待することや、学びたい内容があれば教えてください（任意）', 'text', NULL, 5);

-- ================================================================
-- 投入確認
-- ================================================================

-- 質問一覧を表示（表示順）
SELECT
    display_order AS '順番',
    question_key AS 'キー',
    question_text AS '質問文',
    question_type AS 'タイプ'
FROM survey_questions
ORDER BY display_order;

-- 総投入数
SELECT
    '総投入数' AS info,
    COUNT(*) AS count
FROM survey_questions;
