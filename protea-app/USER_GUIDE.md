# Protea Webアプリ ユーザーガイド

Version 1.0 | 2025-12-30

## 目次

1. [システム概要](#システム概要)
2. [セットアップ（開発環境）](#セットアップ開発環境)
3. [セットアップ（本番環境）](#セットアップ本番環境)
4. [使い方](#使い方)
5. [トラブルシューティング](#トラブルシューティング)

---

## システム概要

Protea Webアプリは、SEO記事作成を支援するWebアプリケーションです。

### 主な機能

- **Excelインポート**: Pythonスクレイピング結果（6シート構造）を一括アップロード
- **キーワード管理**: 一覧表示、検索、フィルタ、詳細表示
- **記事生成**: GPT APIを使った記事自動生成（タイトル、本文）
- **ダッシュボード**: 進捗管理、統計表示

### 技術スタック

- PHP 7.4+
- SQLite（開発）/ MySQL（本番）
- PhpSpreadsheet, Guzzle
- Chart.js, Font Awesome

---

## セットアップ（開発環境）

### 必要なもの

- PHP 7.4以上
- Composer
- Webサーバー（Apache/Nginx）またはPHP組み込みサーバー

### 手順

#### 1. リポジトリクローン

```bash
cd /path/to/yojitu.com
cd protea-app
```

#### 2. 依存関係インストール

```bash
composer install
```

#### 3. 環境変数設定

```bash
cp config/.env.example config/.env
```

`.env`ファイルを編集：

```env
# Database Configuration
DB_TYPE=sqlite
DB_PATH=database/protea.db

# API Keys（開発時はモックレスポンス使用）
USE_MOCK_API=true
OPENAI_API_KEY=
ANTHROPIC_API_KEY=

# Application Settings
APP_ENV=development
APP_DEBUG=true
```

#### 4. データベース初期化

```bash
php database/init_db.php
```

#### 5. ローカルサーバー起動

```bash
php -S localhost:8000 -t public
```

ブラウザで http://localhost:8000 にアクセス

---

## セットアップ（本番環境）

### 前提条件

- Xserver（または同等のPHP/MySQLホスティング）
- MySQL 5.7以上
- SSH/SFTP アクセス

### 手順

#### 1. ファイルアップロード

```bash
# ローカルでビルド
cd protea-app
composer install --no-dev --optimize-autoloader

# サーバーへアップロード（SFTPまたはGit）
# /home/username/public_html/protea-app/ にアップロード
```

#### 2. MySQL データベース作成

Xserverコントロールパネルで：

1. 「MySQL設定」→「MySQL追加」
2. データベース名: `protea_db`
3. ユーザー作成・権限付与

phpMyAdminで`database/schema_mysql.sql`を実行

#### 3. 環境変数設定

`config/.env`を編集：

```env
# Database Configuration
DB_TYPE=mysql
DB_HOST=localhost
DB_NAME=protea_db
DB_USER=your_username
DB_PASS=your_password
DB_CHARSET=utf8mb4

# API Keys
USE_MOCK_API=false
OPENAI_API_KEY=sk-your-openai-key
ANTHROPIC_API_KEY=your-anthropic-key

# Application Settings
APP_ENV=production
APP_DEBUG=false
```

#### 4. .htaccess設定

`public/.htaccess`を作成：

```apache
# セキュリティ設定
<Files .env>
    Order allow,deny
    Deny from all
</Files>

# PHPエラー表示（本番環境では無効化）
php_flag display_errors Off

# アップロードサイズ
php_value upload_max_filesize 10M
php_value post_max_size 10M

# タイムアウト
php_value max_execution_time 300
```

#### 5. パーミッション設定

```bash
chmod 755 public
chmod 755 storage/uploads
chmod 755 storage/logs
chmod 644 config/.env
```

#### 6. 動作確認

https://www.yojitu.com/protea-app/ にアクセス

---

## 使い方

### 1. Excelファイルアップロード

#### Excelファイル構造

6シート構成が必要です：

| シート名 | 列構成 | 行数 |
|---------|--------|------|
| ブログ記事 | URL, タイトル | 10件 |
| 共起語 | 共起語, 出現回数 | 可変（15件程度） |
| サジェスト | クエリ, サジェスト | 4件 |
| URL本文 | URL, タイトル, 本文 | 5件 |
| Yahoo知恵袋 | URL, 質問, ベストアンサー | 15件 |
| goo Q&A | URL, 質問, ベストアンサー | 5件 |

#### アップロード手順

1. 「Excelアップロード」ページへ移動
2. ファイルをドラッグ&ドロップ（または「ファイルを選択」）
3. 「アップロード」ボタンをクリック
4. 登録結果を確認

### 2. キーワード管理

#### 一覧ページ

- **検索**: キーワード名で部分一致検索
- **フィルタ**: ステータス別にフィルタ
- **詳細**: 「詳細」ボタンでスクレイピングデータを確認

#### 詳細ページ

タブで切り替え：

- 競合ブログ（10件）
- 共起語（出現回数順）
- サジェスト（4件）
- 競合記事本文（5件、プレビュー表示）
- Yahoo知恵袋（15件）
- goo Q&A（5件）

### 3. 記事生成

#### 手順

1. キーワード一覧または詳細ページから「記事生成」ボタンをクリック
2. モデル選択（開発環境ではモックレスポンス）
3. 「記事を生成」ボタンをクリック
4. 生成結果をプレビュー確認

#### 注意事項

- **開発環境**: モックレスポンスを使用（APIキー不要）
- **本番環境**: 実際のGPT APIを使用（APIキー必須）

### 4. ダッシュボード

統計情報を確認：

- 登録キーワード数
- 生成記事数
- 記事生成率
- ステータス別分布（円グラフ）
- 最近の活動（タイムライン）

---

## トラブルシューティング

### Q1. Excelアップロードが失敗する

**原因**:
- ファイル形式が`.xlsx`でない
- ファイルサイズが10MBを超えている
- 必須シートが不足している

**解決策**:
- `.xlsx`形式で保存し直す
- ファイルサイズを確認（10MB以下）
- 6シートすべて存在するか確認

### Q2. データベースエラーが発生する

**原因**:
- データベースファイルが存在しない
- パーミッションエラー

**解決策**:

```bash
# データベース再初期化
php database/init_db.php

# パーミッション確認
chmod 755 database
chmod 644 database/protea.db
```

### Q3. 記事生成が動作しない

**原因**:
- 本番環境でAPIキーが未設定
- API制限に達している

**解決策**:
- `config/.env`でAPIキーを確認
- OpenAI/Anthropicのダッシュボードで利用状況を確認

### Q4. 本番環境で500エラーが発生する

**原因**:
- PHPバージョン不一致
- Composer依存関係未インストール
- `.env`ファイルがない

**解決策**:

```bash
# PHPバージョン確認
php -v

# Composer再インストール
composer install --no-dev

# .envファイル確認
ls -la config/.env
```

---

## サポート

問題が解決しない場合は、以下を確認してください：

1. エラーログ: `storage/logs/`
2. PHPエラーログ: サーバーの`error_log`
3. ブラウザコンソール（F12キー）

---

## ライセンス

Proprietary - YOJITU.COM

© 2025 YOJITU.COM - All Rights Reserved
