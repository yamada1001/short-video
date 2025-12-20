-- ================================================================
-- バッジマスターデータ投入
-- 作成日: 2025-12-21
--
-- バッジ数: 15種類
-- カテゴリー: 学習開始, レッスン, コース, クイズ, ストリーク, 総合
-- ================================================================

-- 既存データを削除（再投入時）
TRUNCATE TABLE gamification_badges;

-- ================================================================
-- 学習開始バッジ（3種類）
-- ================================================================

INSERT INTO gamification_badges (badge_key, name, description, icon_emoji, required_condition, points_reward, display_order) VALUES
('first_step', '初めの一歩', '初めてのレッスンを完了しました', '🎉', '{"type":"lesson_complete","count":1}', 10, 1),
('registration', '新規登録', 'プラットフォームに登録しました', '👋', '{"type":"user_register","count":1}', 5, 2),
('profile_complete', 'プロフィール完成', 'プロフィールを完全に入力しました', '✅', '{"type":"profile_complete","count":1}', 10, 3);

-- ================================================================
-- レッスンバッジ（4種類）
-- ================================================================

INSERT INTO gamification_badges (badge_key, name, description, icon_emoji, required_condition, points_reward, display_order) VALUES
('lesson_5', 'レッスン探求者', '5つのレッスンを完了しました', '📚', '{"type":"lesson_complete","count":5}', 25, 10),
('lesson_10', 'レッスンマスター', '10つのレッスンを完了しました', '📖', '{"type":"lesson_complete","count":10}', 50, 11),
('lesson_25', 'レッスン達人', '25つのレッスンを完了しました', '🎓', '{"type":"lesson_complete","count":25}', 100, 12),
('lesson_50', 'レッスン名人', '50つのレッスンを完了しました', '🏆', '{"type":"lesson_complete","count":50}', 200, 13);

-- ================================================================
-- コース完了バッジ（3種類）
-- ================================================================

INSERT INTO gamification_badges (badge_key, name, description, icon_emoji, required_condition, points_reward, display_order) VALUES
('course_first', 'コース完了', '初めてのコースを完了しました', '🎯', '{"type":"course_complete","count":1}', 50, 20),
('course_3', 'コースコレクター', '3つのコースを完了しました', '⭐', '{"type":"course_complete","count":3}', 150, 21),
('course_all', 'コンプリート', '全てのコースを完了しました', '👑', '{"type":"course_complete","count":"all"}', 500, 22);

-- ================================================================
-- クイズバッジ（2種類）
-- ================================================================

INSERT INTO gamification_badges (badge_key, name, description, icon_emoji, required_condition, points_reward, display_order) VALUES
('quiz_perfect', 'パーフェクト', 'クイズで全問正解しました', '💯', '{"type":"quiz_perfect","count":1}', 20, 30),
('quiz_perfect_10', 'クイズマスター', 'クイズで10回全問正解しました', '🌟', '{"type":"quiz_perfect","count":10}', 100, 31);

-- ================================================================
-- ストリークバッジ（2種類）
-- ================================================================

INSERT INTO gamification_badges (badge_key, name, description, icon_emoji, required_condition, points_reward, display_order) VALUES
('streak_7', '7日連続', '7日連続で学習しました', '🔥', '{"type":"streak","count":7}', 50, 40),
('streak_30', '30日連続', '30日連続で学習しました', '💪', '{"type":"streak","count":30}', 200, 41);

-- ================================================================
-- 総合バッジ（1種類）
-- ================================================================

INSERT INTO gamification_badges (badge_key, name, description, icon_emoji, required_condition, points_reward, display_order) VALUES
('ai_master', 'AI Master', '全ての条件を達成しました', '🏅', '{"type":"all_complete","count":1}', 1000, 99);

-- ================================================================
-- 投入確認
-- ================================================================

-- バッジ一覧を表示（表示順）
SELECT
    display_order AS '順番',
    icon_emoji AS 'アイコン',
    name AS 'バッジ名',
    description AS '説明',
    points_reward AS 'ポイント'
FROM gamification_badges
ORDER BY display_order;

-- カテゴリー別の集計（required_conditionのtypeで分類）
SELECT
    JSON_EXTRACT(required_condition, '$.type') AS 'タイプ',
    COUNT(*) AS 'バッジ数'
FROM gamification_badges
GROUP BY JSON_EXTRACT(required_condition, '$.type');

-- 総投入数
SELECT
    '総投入数' AS info,
    COUNT(*) AS count
FROM gamification_badges;
