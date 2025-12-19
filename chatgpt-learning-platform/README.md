# Gemini AI学習プラットフォーム

Progate風のハンズオン形式でGemini AIを学べるWebアプリケーション

## 📌 プロジェクト概要

- **コンセプト**: Gemini AIのプロンプトエンジニアリングを実践的に学習
- **学習形式**: スライド、エディタ、クイズ、課題の4タイプ
- **決済**: Stripeサブスクリプション（月額980円）
- **認証**: メール + Google OAuth 2.0
- **API**: **Google Gemini 1.5 Flash（完全無料 - 1,500リクエスト/日）**
  - 旧: OpenAI GPT-3.5-turbo → 新: Gemini APIに移行（2025-12-19）

## 🚀 実装状況

### ✅ 全機能実装完了（合計33ファイル）

**Phase 1-2: 基盤構築**
- ✅ プロジェクト設計・DB設計（10テーブル）
- ✅ 認証システム（メール + Google OAuth）
- ✅ パスワード再発行機能
- ✅ トップページ・ダッシュボード

**Phase 3: コア学習機能**
- ✅ コース・レッスンページ（進捗表示）
- ✅ 4つのレッスンタイプ（スライド、エディタ、クイズ、課題）
- ✅ Gemini AI実行API（キャッシュ・制限機能付き）
- ✅ クイズ採点・課題提出機能
- ✅ Progate風UIデザイン（1825行CSS）

**Phase 4: 管理画面**
- ✅ コース管理（CRUD、難易度・公開範囲設定）
- ✅ レッスン管理（4タイプ対応の動的フォーム）
- ✅ 統計ダッシュボード（ユーザー数、API使用量）

**Phase 5: 追加機能**
- ✅ Google OAuth認証
- ✅ パスワード再発行（メール送信）
- ✅ Stripe決済（月額980円・年額9,800円）
- ✅ Webhook処理（サブスクリプション自動同期）

### 🔄 次のステップ
- Composerインストール
- データベースセットアップ
- 環境変数設定（.env）
- サンプルデータ作成
- Xserverデプロイ

詳細な進捗は `CHATGPT_LEARNING_PROJECT_STATUS.md` を参照

## 📦 技術スタック

- **バックエンド**: PHP 8.x
- **データベース**: MySQL 8.0
- **フロントエンド**: HTML5, CSS3, JavaScript
- **外部API**: **Google Gemini API**, Stripe API, Google OAuth 2.0
- **ライブラリ**:
  - `google/generative-ai-php` - Gemini API（新規追加）
  - `google/apiclient` - Google OAuth
  - `phpmailer/phpmailer` - メール送信
  - `stripe/stripe-php` - Stripe決済
  - `vlucas/phpdotenv` - 環境変数管理

## 🛠️ セットアップ手順

### 1. リポジトリのクローン

```bash
cd /path/to/your/project
```

### 2. Composerパッケージのインストール

```bash
cd chatgpt-learning-platform
composer install
```

### 3. 環境変数の設定

```bash
cp .env.example .env
```

`.env`ファイルを編集して、以下の値を設定：

```env
# データベース設定
DB_HOST=localhost
DB_NAME=chatgpt_learning
DB_USER=your_db_user
DB_PASSWORD=your_db_password

# Gemini API設定（推奨 - 無料枠: 1,500リクエスト/日）
# API Keyは https://aistudio.google.com/app/apikey から取得
GEMINI_API_KEY=your_gemini_api_key_here
GEMINI_MODEL=gemini-1.5-flash

# OpenAI API設定（非推奨 - Geminiに移行済み）
OPENAI_API_KEY=sk-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
OPENAI_MODEL=gpt-3.5-turbo

# Google OAuth設定
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/chatgpt-learning/google-callback.php

# Stripe設定
STRIPE_PUBLISHABLE_KEY=pk_test_xxxxxxxxxxxxxx
STRIPE_SECRET_KEY=sk_test_xxxxxxxxxxxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxx

# メール設定
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_email_password

# アプリケーション設定
APP_URL=https://yourdomain.com/chatgpt-learning
APP_ENV=production
APP_DEBUG=false
```

### 4. データベースのセットアップ

```bash
mysql -u root -p < ../chatgpt-learning-platform-schema.sql
```

または、MySQL CLIで直接実行：

```sql
CREATE DATABASE chatgpt_learning CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE chatgpt_learning;
SOURCE /path/to/chatgpt-learning-platform-schema.sql;
```

### 5. Xserverへのデプロイ

#### 方法1: FTPアップロード
1. FileZillaなどのFTPクライアントでXserverに接続
2. `public_html/chatgpt-learning/` ディレクトリを作成
3. プロジェクトファイルをアップロード
4. `.env`ファイルをアップロード（非公開ディレクトリに配置推奨）

#### 方法2: SSH経由
```bash
scp -r chatgpt-learning-platform your_user@your_server:/home/your_account/
```

### 6. 動作確認

1. ブラウザで `https://yourdomain.com/chatgpt-learning/` にアクセス
2. トップページが表示されることを確認
3. 会員登録 → ログイン → ダッシュボードの流れを確認

## 📁 ディレクトリ構造

```
chatgpt-learning-platform/
├── public/              # 公開ディレクトリ
│   ├── index.php        # トップページ
│   ├── register.php     # 会員登録
│   ├── login.php        # ログイン
│   ├── dashboard.php    # ダッシュボード
│   └── assets/          # CSS/JS/画像
├── api/                 # APIエンドポイント
├── includes/            # 共通ファイル
│   ├── config.php       # 設定
│   ├── db.php           # DB接続
│   ├── functions.php    # 共通関数
│   ├── header.php       # ヘッダー
│   └── footer.php       # フッター
├── admin/               # 管理画面
├── database/            # DB関連
│   └── schema.sql       # スキーマ
├── vendor/              # Composerパッケージ
├── .env                 # 環境変数
├── .gitignore           # Git除外設定
└── composer.json        # Composer設定
```

## 🔐 セキュリティ

- **パスワード**: `password_hash()`でハッシュ化
- **CSRF対策**: トークン検証実装済み
- **SQLインジェクション**: PDOプリペアドステートメント使用
- **XSS対策**: `htmlspecialchars()`でエスケープ
- **セッション**: HTTPOnly, Secure cookie設定

## 💰 コスト管理

### API使用制限
- 無料会員: 10回/日
- 有料会員: 100回/日

### コスト削減施策（Gemini API移行により完全無料化）
1. **Gemini API使用**: Google Gemini 1.5 Flash（**無料枠: 1,500リクエスト/日**）
2. **プロンプトキャッシュ**: 同一プロンプトは`prompt_cache`テーブルから取得
3. **使用量追跡**: `api_usage`テーブルで記録

### 月額コスト試算（2025-12-19更新）
- **Gemini API料金**: **¥0**（無料枠内: 1日平均333リクエスト < 1,500リクエスト）
- Stripe手数料: 35円（980円 × 3.6%）
- **粗利**: **945円/月**（OpenAI APIから+20円改善）

### 無料枠の余裕度
- 月間10,000リクエスト ÷ 30日 = 約333リクエスト/日
- 無料枠1,500リクエスト/日 ÷ 333リクエスト/日 = **約4.5倍の余裕**

## 📚 ドキュメント

- **設計書**: `chatgpt-learning-platform-design.md`
- **DBスキーマ**: `chatgpt-learning-platform-schema.sql`
- **進捗管理**: `CHATGPT_LEARNING_PROJECT_STATUS.md`
- **Gemini移行レポート**: `GEMINI_MIGRATION.md`（2025-12-19追加）

## 🐛 トラブルシューティング

### Composerエラー
```bash
composer install --ignore-platform-reqs
```

### データベース接続エラー
- `.env`のDB設定を確認
- MySQLサーバーが起動しているか確認

### セッションエラー
- セッション保存ディレクトリの書き込み権限を確認
- `php.ini`の`session.save_path`を確認

## 📝 ライセンス

このプロジェクトは非公開プロジェクトです。

## 👤 作成者

YOJITU.COM - Web制作・システム開発

---

**最終更新**: 2025-12-19
