-- ========================================
-- サムネイル画像をプレースホルダーに変更するSQL
-- ========================================
-- 実行方法: phpMyAdminで xs545151_chatgptlearning データベースを選択し、
--          このSQLを「SQL」タブから実行してください。
-- ========================================

-- 1. コースのサムネイル画像を更新
UPDATE courses
SET thumbnail_url = CASE id
    WHEN 1 THEN 'https://placehold.co/400x225/667eea/white?text=Gemini+AI+Basic+Course'
    WHEN 2 THEN 'https://placehold.co/400x225/764ba2/white?text=Prompt+Engineering'
    WHEN 3 THEN 'https://placehold.co/400x225/5b7fff/white?text=Advanced+Gemini+AI'
    WHEN 4 THEN 'https://placehold.co/400x225/4a66e6/white?text=Business+Applications'
    ELSE CONCAT('https://placehold.co/400x225/667eea/white?text=Course+', id)
END
WHERE id IN (1, 2, 3, 4);

-- 2. 存在する全コースのサムネイル画像を一括更新（上記以外のコース用）
UPDATE courses
SET thumbnail_url = CONCAT('https://placehold.co/400x225/667eea/white?text=Course+', id)
WHERE thumbnail_url IS NULL OR thumbnail_url = '';

-- ========================================
-- 確認クエリ（実行後に確認してください）
-- ========================================
SELECT id, title, thumbnail_url FROM courses ORDER BY id;

-- ========================================
-- 注意事項
-- ========================================
-- ・placehold.co は無料のプレースホルダー画像サービスです
-- ・画像サイズ: 400x225 (16:9比率)
-- ・背景色: 各コースごとに異なる紫〜青系のカラー
-- ・テキスト色: 白
-- ・日本語テキストは使えないため英語表記にしています
-- ========================================
