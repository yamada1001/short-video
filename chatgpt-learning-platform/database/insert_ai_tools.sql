-- ================================================================
-- AIツールマスターデータ投入
-- 作成日: 2025-12-21
--
-- 対応AIツール: 25ツール
-- カテゴリー: chatbot, coding, image, video, business, agent
-- ================================================================

-- 既存データを削除（再投入時）
TRUNCATE TABLE ai_tools;

-- ================================================================
-- 会話型AI（Chatbot） - 6ツール
-- ================================================================

INSERT INTO ai_tools (name, category, official_url, pricing_type, description, logo_url) VALUES
('ChatGPT', 'chatbot', 'https://chat.openai.com', 'freemium', 'OpenAIが開発した対話型AI。GPT-4を使用し、高度な自然言語処理が可能。無料版と有料版（ChatGPT Plus）がある。', 'https://cdn.openai.com/common/images/favicon.ico'),
('Claude', 'chatbot', 'https://claude.ai', 'freemium', 'Anthropicが開発した対話型AI。長文読解に強く、安全性を重視した設計。無料版と有料版（Claude Pro）がある。', 'https://claude.ai/images/claude_app_icon.png'),
('Gemini', 'chatbot', 'https://gemini.google.com', 'freemium', 'Googleが開発した対話型AI。Gmail、Google Docs等との連携が強み。無料版と有料版（Gemini Advanced）がある。', 'https://www.gstatic.com/lamda/images/gemini_favicon_f069958c85030456e93de685481c559f160ea06b.png'),
('Perplexity AI', 'chatbot', 'https://www.perplexity.ai', 'freemium', 'AI検索エンジン。リアルタイム情報を引用付きで回答。研究・調査に最適。無料版と有料版（Perplexity Pro）がある。', 'https://www.perplexity.ai/favicon.svg'),
('Microsoft Copilot', 'chatbot', 'https://copilot.microsoft.com', 'free', 'Microsoft製の対話型AI。Bing検索と統合され、画像生成機能も搭載。基本無料で利用可能。', 'https://copilot.microsoft.com/rp/favicon.ico'),
('Poe', 'chatbot', 'https://poe.com', 'freemium', '複数のAI（ChatGPT、Claude、Gemini等）を一つのプラットフォームで利用可能。比較や使い分けに便利。', 'https://poe.com/favicon.ico');

-- ================================================================
-- コーディングAI（Coding） - 6ツール
-- ================================================================

INSERT INTO ai_tools (name, category, official_url, pricing_type, description, logo_url) VALUES
('Cursor', 'coding', 'https://cursor.sh', 'freemium', 'VS Codeベースの次世代AIエディタ。コード生成・編集・デバッグを自然言語で指示可能。開発者に大人気。', 'https://cursor.sh/favicon.ico'),
('GitHub Copilot', 'coding', 'https://github.com/features/copilot', 'paid', 'GitHub製のAIコーディング支援ツール。リアルタイムでコード提案。VS Code、JetBrains等に対応。月額$10。', 'https://github.githubassets.com/favicons/favicon.svg'),
('Codeium', 'coding', 'https://codeium.com', 'freemium', '無料で使えるAIコーディング支援ツール。40以上の言語・70以上のIDEに対応。個人利用は完全無料。', 'https://codeium.com/favicon.ico'),
('Tabnine', 'coding', 'https://www.tabnine.com', 'freemium', 'AIによるコード補完ツール。プライバシー重視でローカル実行可能。無料版と有料版（Pro/Enterprise）がある。', 'https://www.tabnine.com/favicon.ico'),
('Amazon CodeWhisperer', 'coding', 'https://aws.amazon.com/codewhisperer/', 'free', 'AWS製のAIコーディング支援ツール。AWS SDKに強い。個人利用は無料。', 'https://aws.amazon.com/favicon.ico'),
('Replit AI', 'coding', 'https://replit.com/ai', 'freemium', 'ブラウザ完結型の開発環境Replitに統合されたAI。コード生成・デバッグ・説明が可能。', 'https://replit.com/public/images/favicon.ico');

-- ================================================================
-- 画像生成AI（Image） - 5ツール
-- ================================================================

INSERT INTO ai_tools (name, category, official_url, pricing_type, description, logo_url) VALUES
('Midjourney', 'image', 'https://www.midjourney.com', 'paid', '高品質な画像生成AI。Discord経由で利用。アート・デザイン・イラスト制作に人気。月額$10〜。', 'https://www.midjourney.com/favicon.ico'),
('DALL-E 3', 'image', 'https://openai.com/dall-e-3', 'freemium', 'OpenAI製の画像生成AI。ChatGPT Plusで利用可能。自然言語からの画像生成に優れる。', 'https://cdn.openai.com/common/images/favicon.ico'),
('Stable Diffusion', 'image', 'https://stability.ai', 'free', 'オープンソースの画像生成AI。ローカル実行可能。カスタマイズ性が高く、無料で利用できる。', 'https://stability.ai/favicon.ico'),
('Adobe Firefly', 'image', 'https://firefly.adobe.com', 'freemium', 'Adobe製の画像生成AI。商用利用を前提とした安全な学習データ。Photoshop等に統合。', 'https://firefly.adobe.com/favicon.ico'),
('Leonardo AI', 'image', 'https://leonardo.ai', 'freemium', 'ゲームアセット・イラスト制作に特化した画像生成AI。無料枠が大きい。日本語対応。', 'https://leonardo.ai/favicon.ico');

-- ================================================================
-- 動画・音声AI（Video） - 4ツール
-- ================================================================

INSERT INTO ai_tools (name, category, official_url, pricing_type, description, logo_url) VALUES
('Runway', 'video', 'https://runwayml.com', 'freemium', 'AI動画生成・編集ツール。テキストから動画生成、画像から動画生成等が可能。クリエイター向け。', 'https://runwayml.com/favicon.ico'),
('ElevenLabs', 'video', 'https://elevenlabs.io', 'freemium', 'AI音声生成ツール。リアルな音声合成・音声クローン作成が可能。多言語対応。', 'https://elevenlabs.io/favicon.ico'),
('Suno AI', 'video', 'https://suno.ai', 'freemium', 'AI音楽生成ツール。テキストから歌詞・メロディー・ボーカル付きの楽曲を生成。', 'https://suno.ai/favicon.ico'),
('HeyGen', 'video', 'https://www.heygen.com', 'freemium', 'AIアバター動画生成ツール。テキストから話すアバター動画を作成。プレゼン・マーケティングに活用。', 'https://www.heygen.com/favicon.ico');

-- ================================================================
-- ビジネスAI（Business） - 3ツール
-- ================================================================

INSERT INTO ai_tools (name, category, official_url, pricing_type, description, logo_url) VALUES
('Notion AI', 'business', 'https://www.notion.so/product/ai', 'freemium', 'Notion統合のAI。文章作成・要約・翻訳・ブレスト等をNotion内で実行。月額$10。', 'https://www.notion.so/images/favicon.ico'),
('Grammarly', 'business', 'https://www.grammarly.com', 'freemium', 'AI英文校正・ライティング支援ツール。文法・スペル・トーン・明瞭性をチェック。無料版あり。', 'https://www.grammarly.com/favicon.ico'),
('Jasper AI', 'business', 'https://www.jasper.ai', 'paid', 'AIライティングツール。ブログ・SNS・広告コピー等のコンテンツ生成に特化。マーケター向け。', 'https://www.jasper.ai/favicon.ico');

-- ================================================================
-- AIエージェント（Agent） - 1ツール
-- ================================================================

INSERT INTO ai_tools (name, category, official_url, pricing_type, description, logo_url) VALUES
('Manus', 'agent', 'https://manus.im', 'freemium', '自律型AIエージェント。複雑なタスクを自動実行。リサーチ・データ収集・レポート作成等を自動化。', 'https://manus.im/favicon.ico');

-- ================================================================
-- 投入確認
-- ================================================================

-- カテゴリー別の投入数を確認
SELECT
    category AS 'カテゴリー',
    COUNT(*) AS '投入数'
FROM ai_tools
GROUP BY category
ORDER BY
    CASE category
        WHEN 'chatbot' THEN 1
        WHEN 'coding' THEN 2
        WHEN 'image' THEN 3
        WHEN 'video' THEN 4
        WHEN 'business' THEN 5
        WHEN 'agent' THEN 6
    END;

-- 全ツール一覧を表示
SELECT
    id,
    name AS 'ツール名',
    category AS 'カテゴリー',
    pricing_type AS '料金',
    official_url AS 'URL'
FROM ai_tools
ORDER BY
    CASE category
        WHEN 'chatbot' THEN 1
        WHEN 'coding' THEN 2
        WHEN 'image' THEN 3
        WHEN 'video' THEN 4
        WHEN 'business' THEN 5
        WHEN 'agent' THEN 6
    END,
    id;

-- 総投入数
SELECT
    '総投入数' AS info,
    COUNT(*) AS count
FROM ai_tools;
