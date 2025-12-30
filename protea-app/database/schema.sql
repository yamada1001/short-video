-- Protea Webアプリ データベーススキーマ（SQLite対応版）
-- 作成日: 2025-12-30
-- 注: SQLiteではAUTO_INCREMENT → AUTOINCREMENT、ENUM → TEXT

-- ============================================
-- 1. キーワードマスタ
-- ============================================
CREATE TABLE IF NOT EXISTS keywords (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyword TEXT NOT NULL UNIQUE,
  status TEXT DEFAULT '未着手' CHECK(status IN ('未着手', 'スクレイピング完了', '記事生成中', '記事完成', '公開済')),
  target_month TEXT,
  search_needs TEXT,
  excel_filename TEXT,
  uploaded_at TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_keywords_status ON keywords(status);
CREATE INDEX IF NOT EXISTS idx_keywords_target_month ON keywords(target_month);

-- ============================================
-- 2. 競合ブログ記事（シート1: ブログ記事）
-- ============================================
CREATE TABLE IF NOT EXISTS blog_articles (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyword_id INTEGER NOT NULL,
  url TEXT NOT NULL,
  title TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_blog_articles_keyword_id ON blog_articles(keyword_id);

-- ============================================
-- 3. 共起語（シート2: 共起語）
-- ============================================
CREATE TABLE IF NOT EXISTS cooccurrence_words (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyword_id INTEGER NOT NULL,
  word TEXT NOT NULL,
  count INTEGER DEFAULT 0,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_cooccurrence_words_keyword_id ON cooccurrence_words(keyword_id);

-- ============================================
-- 4. サジェスト（シート3: サジェスト）
-- ============================================
CREATE TABLE IF NOT EXISTS suggestions (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyword_id INTEGER NOT NULL,
  query TEXT,
  suggestion TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_suggestions_keyword_id ON suggestions(keyword_id);

-- ============================================
-- 5. 競合記事本文（シート4: URL本文）
-- ============================================
CREATE TABLE IF NOT EXISTS article_contents (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyword_id INTEGER NOT NULL,
  url TEXT NOT NULL,
  title TEXT,
  content TEXT,
  word_count INTEGER,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_article_contents_keyword_id ON article_contents(keyword_id);

-- ============================================
-- 6. Yahoo知恵袋（シート5: Yahoo知恵袋）
-- ============================================
CREATE TABLE IF NOT EXISTS yahoo_qa (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyword_id INTEGER NOT NULL,
  url TEXT,
  question TEXT,
  best_answer TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_yahoo_qa_keyword_id ON yahoo_qa(keyword_id);

-- ============================================
-- 7. goo Q&A（シート6: goo Q&A）★追加
-- ============================================
CREATE TABLE IF NOT EXISTS goo_qa (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyword_id INTEGER NOT NULL,
  url TEXT,
  question TEXT,
  best_answer TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_goo_qa_keyword_id ON goo_qa(keyword_id);

-- ============================================
-- 8. 生成記事
-- ============================================
CREATE TABLE IF NOT EXISTS generated_articles (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  keyword_id INTEGER NOT NULL,
  title TEXT,
  content TEXT,
  gpt_model TEXT DEFAULT 'gpt-4-turbo',
  prompt_template TEXT,
  word_count INTEGER,
  status TEXT DEFAULT '下書き' CHECK(status IN ('下書き', 'レビュー中', '公開済')),
  published_at TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_generated_articles_keyword_id ON generated_articles(keyword_id);
CREATE INDEX IF NOT EXISTS idx_generated_articles_status ON generated_articles(status);

-- ============================================
-- 9. プロンプトテンプレート
-- ============================================
CREATE TABLE IF NOT EXISTS prompt_templates (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  description TEXT,
  template TEXT NOT NULL,
  is_active INTEGER DEFAULT 1,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP,
  updated_at TEXT DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- デフォルトプロンプトテンプレート挿入
-- ============================================
INSERT OR IGNORE INTO prompt_templates (id, name, description, template) VALUES
(1, 'SEO記事基本', 'SEO最適化された基本的なブログ記事',
'以下の情報を元に、SEO最適化されたブログ記事を作成してください。

【キーワード】
{{keyword}}

【共起語】
{{cooccurrence}}

【競合記事タイトル】
{{competitor_titles}}

【Yahoo知恵袋のQ&A】
{{yahoo_qa}}

記事の構成:
1. タイトル（H1）
2. 導入文（300文字程度）
3. 本文（見出しH2-H3を活用、5000文字以上）
4. まとめ（300文字程度）

注意事項:
- 共起語を自然に含める
- 見出しは検索意図に沿ったものにする
- 具体例を多用する
- Yahoo知恵袋の悩みに応える内容にする');
