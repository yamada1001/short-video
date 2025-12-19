# ChatGPT学習プラットフォーム システム設計書

## プロジェクト概要

### コンセプト
Progate風のハンズオン形式でChatGPTの使い方を学べるWebアプリケーション

### 主要機能
1. コース形式の学習コンテンツ
2. インタラクティブなエディタ + ChatGPT実行環境
3. クイズ機能
4. 課題提出・採点機能
5. 会員登録・ログイン（メール + Google OAuth）
6. パスワード再発行機能
7. サブスクリプション決済（Stripe）
8. 進捗管理

---

## システム構成

### 技術スタック
- **バックエンド**: PHP 8.x
- **データベース**: MySQL 8.0
- **フロントエンド**: HTML5, CSS3, JavaScript (ES6+)
- **外部API**:
  - OpenAI API (GPT-3.5-turbo推奨 - コスト削減)
  - Stripe API (決済)
  - Google OAuth 2.0 (ソーシャルログイン)
- **ライブラリ**:
  - Google API PHP Client（google/apiclient）
  - PHPMailer（パスワード再発行メール送信）
- **サーバー**: Xserver

### ディレクトリ構成
```
chatgpt-learning-platform/
├── public/
│   ├── index.php              # トップページ
│   ├── login.php              # ログインページ
│   ├── register.php           # 会員登録ページ
│   ├── google-login.php       # Google OAuth認証
│   ├── google-callback.php    # Google OAuthコールバック
│   ├── forgot-password.php    # パスワード再発行申請
│   ├── reset-password.php     # パスワード再設定
│   ├── dashboard.php          # ダッシュボード
│   ├── course.php             # コース一覧
│   ├── lesson.php             # レッスン画面
│   ├── my-progress.php        # 進捗確認
│   ├── subscribe.php          # サブスク登録
│   └── assets/
│       ├── css/
│       ├── js/
│       └── images/
├── api/
│   ├── auth.php               # 認証API
│   ├── google-auth.php        # Google OAuth API
│   ├── password-reset.php     # パスワード再発行API
│   ├── chatgpt.php            # ChatGPT実行API
│   ├── quiz.php               # クイズAPI
│   ├── assignment.php         # 課題API
│   ├── progress.php           # 進捗API
│   └── stripe-webhook.php     # Stripe Webhook
├── includes/
│   ├── config.php             # 設定ファイル
│   ├── db.php                 # DB接続
│   ├── functions.php          # 共通関数
│   └── auth-check.php         # 認証チェック
├── admin/
│   ├── index.php              # 管理画面
│   ├── courses.php            # コース管理
│   └── users.php              # ユーザー管理
└── database/
    └── schema.sql             # DBスキーマ
```

---

## データベース設計

### 1. users（ユーザー）
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255),  -- Google認証の場合はNULL
    name VARCHAR(100) NOT NULL,
    oauth_provider ENUM('email', 'google') DEFAULT 'email',  -- 認証方法
    google_id VARCHAR(255) UNIQUE,  -- Google ID（Google認証の場合）
    subscription_status ENUM('free', 'active', 'canceled', 'past_due') DEFAULT 'free',
    stripe_customer_id VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_google_id (google_id),
    INDEX idx_subscription (subscription_status)
);
```

### 2. subscriptions（サブスクリプション）
```sql
CREATE TABLE subscriptions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    stripe_subscription_id VARCHAR(255) UNIQUE,
    plan_type ENUM('monthly', 'yearly') NOT NULL,
    status ENUM('active', 'canceled', 'past_due', 'trialing') NOT NULL,
    current_period_start TIMESTAMP,
    current_period_end TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id)
);
```

### 3. courses（コース）
```sql
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    difficulty ENUM('beginner', 'intermediate', 'advanced') NOT NULL,
    is_free BOOLEAN DEFAULT FALSE,
    order_num INT NOT NULL,
    thumbnail_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order (order_num)
);
```

### 4. lessons（レッスン）
```sql
CREATE TABLE lessons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    order_num INT NOT NULL,
    lesson_type ENUM('slide', 'editor', 'quiz', 'assignment') NOT NULL,
    content_json JSON,  -- スライド内容、クイズデータ、課題内容など
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_course_order (course_id, order_num)
);
```

### 5. user_progress（進捗管理）
```sql
CREATE TABLE user_progress (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    status ENUM('not_started', 'in_progress', 'completed') DEFAULT 'not_started',
    completed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_lesson (user_id, lesson_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_lesson (lesson_id)
);
```

### 6. quiz_results（クイズ結果）
```sql
CREATE TABLE quiz_results (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    score INT NOT NULL,
    max_score INT NOT NULL,
    answers_json JSON,  -- ユーザーの回答データ
    passed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX idx_user_lesson (user_id, lesson_id)
);
```

### 7. assignments（課題提出）
```sql
CREATE TABLE assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    submitted_prompt TEXT NOT NULL,
    chatgpt_response TEXT,
    score INT,
    feedback TEXT,
    status ENUM('submitted', 'graded') DEFAULT 'submitted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX idx_user_lesson (user_id, lesson_id)
);
```

### 8. api_usage（API使用量追跡 - コスト管理）
```sql
CREATE TABLE api_usage (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    endpoint VARCHAR(100) NOT NULL,  -- 'chatgpt', 'quiz', 'assignment'
    tokens_used INT NOT NULL,
    cost_usd DECIMAL(10, 6) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_date (user_id, created_at),
    INDEX idx_created_at (created_at)
);
```

### 9. prompt_cache（プロンプトキャッシュ - コスト削減）
```sql
CREATE TABLE prompt_cache (
    id INT PRIMARY KEY AUTO_INCREMENT,
    prompt_hash VARCHAR(64) UNIQUE NOT NULL,  -- SHA256ハッシュ
    prompt_text TEXT NOT NULL,
    response_text TEXT NOT NULL,
    model VARCHAR(50) NOT NULL,
    hit_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_hash (prompt_hash),
    INDEX idx_last_used (last_used_at)
);
```

### 10. password_reset_tokens（パスワード再発行トークン）
```sql
CREATE TABLE password_reset_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(64) UNIQUE NOT NULL,  -- ランダム生成トークン
    expires_at TIMESTAMP NOT NULL,  -- 有効期限（1時間）
    used BOOLEAN DEFAULT FALSE,  -- 使用済みフラグ
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (token),
    INDEX idx_user (user_id),
    INDEX idx_expires (expires_at)
);
```

---

## 主要機能の実装方針

### 1. 認証システム

#### メール認証
- セッション管理でログイン状態を維持
- パスワードは`password_hash()`でハッシュ化
- CSRF対策トークンを実装

#### Google OAuth認証
- Google API PHP Clientライブラリを使用
- OAuth 2.0フローで認証
- 初回ログイン時に`users`テーブルにGoogle ID保存
- 既存ユーザーは`google_id`で照合してログイン

#### パスワード再発行
- ユーザーがメールアドレスを入力
- ランダムトークン生成（64文字）
- `password_reset_tokens`テーブルに保存（有効期限1時間）
- PHPMailerでメール送信（リセットURL含む）
- ユーザーがURLクリック → トークン検証 → パスワード再設定

### 2. サブスクリプション決済
- **無料プラン**:
  - 基礎コースのみアクセス可
  - 1日のAPI呼び出し: 10回まで
- **有料プラン（月額980円想定）**:
  - 全コースアクセス可
  - 1日のAPI呼び出し: 100回まで
- Stripe Checkoutで決済画面を生成
- Webhookでサブスク状態を同期

### 3. ChatGPTエディタ + 実行機能
- テキストエリアでプロンプト入力
- 「実行」ボタンクリックでAPI呼び出し
- レスポンスをリアルタイム表示
- キャッシュ機能で同一プロンプトは再利用（コスト削減）

### 4. クイズ機能
- 選択式 or 記述式
- 正解判定は自動（選択式）or ChatGPT採点（記述式）
- 合格ラインを設定（例: 80%以上で合格）

### 5. 課題提出・採点
- ユーザーがプロンプトを提出
- ChatGPTで実行して結果を保存
- 管理者 or 自動採点（ChatGPTで採点プロンプト実行）

### 6. 進捗管理
- レッスン完了時に`user_progress`を更新
- ダッシュボードで進捗率を可視化

### 7. コスト削減施策
- **キャッシュ**: 同じプロンプトは`prompt_cache`から取得
- **安価なモデル**: GPT-3.5-turbo使用（GPT-4の1/10のコスト）
- **使用制限**: 無料会員は1日10回、有料会員は100回
- **ログ記録**: `api_usage`で使用量を追跡・分析

---

## 料金プラン案

| プラン | 月額料金 | 機能 |
|--------|----------|------|
| 無料 | 0円 | 基礎コースのみ、API 10回/日 |
| プレミアム | 980円 | 全コースアクセス、API 100回/日 |

### コスト試算
- GPT-3.5-turbo: $0.0015/1K tokens (入力) + $0.002/1K tokens (出力)
- 1回のプロンプト平均: 200 tokens (入力) + 500 tokens (出力) = 約$0.0013
- 1ユーザー/月100回使用: $0.13 ≈ 20円
- Stripe手数料: 980円 × 3.6% = 35円
- **粗利**: 980円 - 20円 - 35円 = 925円/月

---

## 次のステップ

1. DB作成とテーブル生成
2. 認証システム実装
3. Stripe統合
4. コース・レッスン管理画面
5. エディタ機能実装
6. クイズ・課題機能実装
7. フロントエンドUI実装

このプランで進めてよろしいですか？
