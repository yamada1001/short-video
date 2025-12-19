-- ==================================================================
-- ChatGPT to Gemini AI Rebranding Migration
-- Execute this file in phpMyAdmin to update database content
-- Date: 2025-12-20
-- ==================================================================

-- Update course titles and descriptions
UPDATE courses
SET title = 'Gemini AI基礎コース',
    description = 'Gemini AIの基本的な使い方を学びます'
WHERE title = 'ChatGPT基礎コース';

-- Update lesson titles
UPDATE lessons
SET title = 'Gemini AIとは？'
WHERE title = 'ChatGPTとは？';

UPDATE lessons
SET title = '課題：自己紹介文を作成（Gemini AI）'
WHERE title LIKE '%ChatGPT%自己紹介%';

-- Update lesson descriptions
UPDATE lessons
SET description = 'Gemini AIの概要を学びます'
WHERE description = 'ChatGPTの概要を学びます';

UPDATE lessons
SET description = '実際にGemini AIにプロンプトを送ってみましょう'
WHERE description LIKE '%ChatGPT%プロンプト%';

UPDATE lessons
SET description = 'Gemini AIの基礎知識を確認'
WHERE description = 'ChatGPTの基礎知識を確認';

UPDATE lessons
SET description = 'Gemini AIを使って自己紹介文を作成してください'
WHERE description LIKE 'ChatGPT%自己紹介%';

UPDATE lessons
SET description = 'Gemini AIに役割を与えてみましょう'
WHERE description LIKE 'ChatGPT%役割%';

-- Update JSON content in lessons (slide content)
UPDATE lessons
SET content_json = JSON_REPLACE(
    content_json,
    '$.slides[0].title', 'Gemini AIとは',
    '$.slides[0].content', 'Gemini AIは対話型のAIアシスタントです。'
)
WHERE lesson_type = 'slide'
AND JSON_EXTRACT(content_json, '$.slides[0].title') = 'ChatGPTとは';

-- Update JSON content in lessons (editor instructions)
UPDATE lessons
SET content_json = JSON_REPLACE(
    content_json,
    '$.hint', REPLACE(JSON_EXTRACT(content_json, '$.hint'), 'ChatGPT', 'Gemini AI')
)
WHERE lesson_type = 'editor'
AND JSON_EXTRACT(content_json, '$.hint') LIKE '%ChatGPT%';

-- Update JSON content in lessons (quiz questions)
UPDATE lessons
SET content_json = JSON_REPLACE(
    content_json,
    '$.questions[0].question', REPLACE(JSON_EXTRACT(content_json, '$.questions[0].question'), 'ChatGPT', 'Gemini AI'),
    '$.questions[0].explanation', REPLACE(JSON_EXTRACT(content_json, '$.questions[0].explanation'), 'ChatGPT', 'Gemini AI')
)
WHERE lesson_type = 'quiz'
AND JSON_EXTRACT(content_json, '$.questions[0].question') LIKE '%ChatGPT%';

-- Update JSON content in lessons (assignment tasks)
UPDATE lessons
SET content_json = JSON_REPLACE(
    content_json,
    '$.task', REPLACE(JSON_EXTRACT(content_json, '$.task'), 'ChatGPT', 'Gemini AI')
)
WHERE lesson_type = 'assignment'
AND JSON_EXTRACT(content_json, '$.task') LIKE '%ChatGPT%';

-- Update thumbnail URLs if they contain ChatGPT references
UPDATE courses
SET thumbnail_url = REPLACE(thumbnail_url, 'ChatGPT', 'Gemini+AI')
WHERE thumbnail_url LIKE '%ChatGPT%';

-- Verify changes
SELECT 'Courses updated:' as status, COUNT(*) as count
FROM courses
WHERE title LIKE '%Gemini AI%' OR description LIKE '%Gemini AI%';

SELECT 'Lessons updated:' as status, COUNT(*) as count
FROM lessons
WHERE title LIKE '%Gemini AI%'
   OR description LIKE '%Gemini AI%'
   OR content_json LIKE '%Gemini AI%';

-- Display summary
SELECT '✅ Rebranding migration completed successfully!' as message;
SELECT 'All ChatGPT references in the database have been updated to Gemini AI' as details;
