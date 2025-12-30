-- Protea Webアプリ データベーススキーマ（MySQL版）
-- 作成日: 2025-12-30
-- 本番環境（Xserver MySQL）用

-- ============================================
-- 1. キーワードマスタ
-- ============================================
CREATE TABLE IF NOT EXISTS keywords (
  id INT AUTO_INCREMENT PRIMARY KEY,
  keyword VARCHAR(255) NOT NULL UNIQUE COMMENT 'キーワード',
  status ENUM('未着手', 'スクレイピング完了', '記事生成中', '記事完成', '公開済') DEFAULT '未着手' COMMENT 'ステータス',
  target_month DATE COMMENT '対象月',
  search_needs TEXT COMMENT '検索ニーズ',
  excel_filename VARCHAR(255) COMMENT 'アップロードしたExcelファイル名',
  uploaded_at TIMESTAMP NULL COMMENT 'Excelアップロード日時',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_status (status),
  INDEX idx_target_month (target_month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='キーワード管理';

-- ============================================
-- 2. 競合ブログ記事（シート1: ブログ記事）
-- ============================================
CREATE TABLE IF NOT EXISTS blog_articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  keyword_id INT NOT NULL,
  url VARCHAR(500) NOT NULL COMMENT 'ブログURL',
  title TEXT COMMENT '記事タイトル',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE,
  INDEX idx_keyword_id (keyword_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='競合ブログ記事';

-- ============================================
-- 3. 共起語（シート2: 共起語）
-- ============================================
CREATE TABLE IF NOT EXISTS cooccurrence_words (
  id INT AUTO_INCREMENT PRIMARY KEY,
  keyword_id INT NOT NULL,
  word VARCHAR(255) NOT NULL COMMENT '共起語',
  count INT DEFAULT 0 COMMENT '出現回数',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE,
  INDEX idx_keyword_id (keyword_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='共起語';

-- ============================================
-- 4. サジェスト（シート3: サジェスト）
-- ============================================
CREATE TABLE IF NOT EXISTS suggestions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  keyword_id INT NOT NULL,
  query VARCHAR(255) COMMENT 'クエリ',
  suggestion VARCHAR(255) COMMENT 'サジェスト',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE,
  INDEX idx_keyword_id (keyword_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='サジェスト';

-- ============================================
-- 5. 競合記事本文（シート4: URL本文）
-- ============================================
CREATE TABLE IF NOT EXISTS article_contents (
  id INT AUTO_INCREMENT PRIMARY KEY,
  keyword_id INT NOT NULL,
  url VARCHAR(500) NOT NULL COMMENT '記事URL',
  title TEXT COMMENT 'タイトル',
  content LONGTEXT COMMENT '本文',
  word_count INT COMMENT '文字数',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE,
  INDEX idx_keyword_id (keyword_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='競合記事本文';

-- ============================================
-- 6. Yahoo知恵袋（シート5: Yahoo知恵袋）
-- ============================================
CREATE TABLE IF NOT EXISTS yahoo_qa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  keyword_id INT NOT NULL,
  url VARCHAR(500) COMMENT 'URL',
  question TEXT COMMENT '質問',
  best_answer TEXT COMMENT 'ベストアンサー',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE,
  INDEX idx_keyword_id (keyword_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Yahoo知恵袋';

-- ============================================
-- 7. goo Q&A（シート6: goo Q&A）
-- ============================================
CREATE TABLE IF NOT EXISTS goo_qa (
  id INT AUTO_INCREMENT PRIMARY KEY,
  keyword_id INT NOT NULL,
  url VARCHAR(500) COMMENT 'URL',
  question TEXT COMMENT '質問',
  best_answer TEXT COMMENT 'ベストアンサー',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE,
  INDEX idx_keyword_id (keyword_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='goo Q&A';

-- ============================================
-- 8. 生成記事
-- ============================================
CREATE TABLE IF NOT EXISTS generated_articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  keyword_id INT NOT NULL,
  title VARCHAR(255) COMMENT '記事タイトル',
  content LONGTEXT COMMENT '記事本文',
  gpt_model VARCHAR(50) DEFAULT 'gpt-4-turbo' COMMENT '使用したGPTモデル',
  prompt_template TEXT COMMENT '使用したプロンプト',
  word_count INT COMMENT '文字数',
  status ENUM('下書き', 'レビュー中', '公開済') DEFAULT '下書き',
  published_at TIMESTAMP NULL COMMENT '公開日時',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (keyword_id) REFERENCES keywords(id) ON DELETE CASCADE,
  INDEX idx_keyword_id (keyword_id),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='生成記事';

-- ============================================
-- 9. プロンプトテンプレート
-- ============================================
CREATE TABLE IF NOT EXISTS prompt_templates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL COMMENT 'テンプレート名',
  description TEXT COMMENT '説明',
  template TEXT NOT NULL COMMENT 'プロンプト本文（変数: {{keyword}}, {{cooccurrence}}, {{competitors}} など）',
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='プロンプトテンプレート';

-- ============================================
-- デフォルトプロンプトテンプレート挿入
-- ============================================
INSERT IGNORE INTO prompt_templates (id, name, description, template) VALUES
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
