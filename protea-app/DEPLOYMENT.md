# Protea 本番環境セットアップ手順

## 前提条件

- Xserverのサーバーアカウント
- SSH接続可能
- Composer がインストール済み（未インストールの場合は後述）

---

## セットアップ手順

### 1. サーバーへSSH接続

```bash
ssh your_account@your_server.xsrv.jp
```

### 2. プロジェクトディレクトリへ移動

```bash
cd ~/www.yojitu.com/public_html
```

### 3. 最新コードをpull

```bash
git pull origin main
```

### 4. protea-appディレクトリへ移動

```bash
cd protea-app
```

### 5. Composerで依存関係をインストール

```bash
composer install --no-dev --optimize-autoloader
```

**Composerがインストールされていない場合**:
```bash
# ホームディレクトリにComposerをインストール
cd ~
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer

# PATHに追加（~/.bashrcまたは~/.bash_profileに追加）
echo 'alias composer="php ~/composer"' >> ~/.bashrc
source ~/.bashrc

# protea-appディレクトリに戻って再実行
cd ~/www.yojitu.com/public_html/protea-app
composer install --no-dev --optimize-autoloader
```

### 6. データベース初期化（SQLite）

```bash
php database/init_db.php
```

出力例:
```
=== Protea データベース初期化 ===
データベース接続成功
作成されたテーブル:
  - keywords (レコード数: 0)
  - blog_articles (レコード数: 0)
  ...
データベース初期化完了！
```

### 7. パーミッション設定

```bash
# storageディレクトリを書き込み可能に
chmod 777 storage
chmod 777 storage/uploads
chmod 777 storage/logs

# databaseディレクトリを書き込み可能に（SQLiteファイル作成のため）
chmod 777 database

# データベースファイルを書き込み可能に
chmod 666 database/protea.db
```

### 8. 動作確認

ブラウザで以下のURLにアクセス:

- **トップページ**: https://www.yojitu.com/protea-app/
- **ダッシュボード**: https://www.yojitu.com/protea-app/dashboard.php
- **アップロード**: https://www.yojitu.com/protea-app/upload.php

---

## トラブルシューティング

### 403 Forbidden エラー

**原因**: パーミッション不足、または.htaccessの問題

**解決策**:
```bash
# ファイル・ディレクトリのパーミッション確認
ls -la

# publicディレクトリのパーミッション
chmod 755 public

# .htaccessのパーミッション
chmod 644 .htaccess
chmod 644 public/.htaccess
```

### 500 Internal Server Error

**原因**: PHPエラー、データベース接続エラー

**解決策**:
```bash
# エラーログ確認
tail -f /path/to/error_log

# または、一時的にAPP_DEBUG=trueに設定
vi config/.env
# APP_DEBUG=true に変更してエラー詳細を確認
```

### Composerが見つからない

**エラー**: `composer: command not found`

**解決策**:
```bash
# Xserverの場合、Composerは通常インストール済み
# パスを確認
which composer

# 見つからない場合は、上記「Composerがインストールされていない場合」を参照
```

### データベースファイルが作成できない

**エラー**: `failed to open stream: Permission denied`

**解決策**:
```bash
# databaseディレクトリのパーミッション確認
ls -la database/

# 書き込み権限を付与
chmod 777 database

# 再度初期化
php database/init_db.php
```

---

## MySQL切り替え（将来用）

現在はSQLiteで動作していますが、将来MySQLに切り替える場合:

### 1. XserverコンパネでMySQLデータベース作成

- データベース名: `protea_db`
- ユーザー名: （任意）
- パスワード: （任意）

### 2. スキーマ実行

```bash
# phpMyAdminまたはコマンドラインで実行
mysql -u your_user -p protea_db < database/schema_mysql.sql
```

### 3. .env設定変更

```bash
vi config/.env
```

以下のように変更:
```env
# Database Configuration
DB_TYPE=mysql
# DB_TYPE=sqlite  # この行をコメントアウト

# MySQL設定を有効化
DB_HOST=localhost
DB_NAME=protea_db
DB_USER=your_mysql_user
DB_PASS=your_mysql_password
DB_CHARSET=utf8mb4
```

### 4. 動作確認

ブラウザでアクセスして、MySQLに切り替わっているか確認。

---

## 本番API切り替え（モック→実API）

現在はモックレスポンスで動作していますが、実際のGPT APIを使う場合:

### 1. APIキー取得

- **OpenAI**: https://platform.openai.com/api-keys
- **Anthropic**: https://console.anthropic.com/settings/keys

### 2. .env設定

```bash
vi config/.env
```

以下のように変更:
```env
# API Keys
USE_MOCK_API=false  # true → false に変更
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxx
ANTHROPIC_API_KEY=sk-ant-xxxxxxxxxxxxx
```

### 3. 動作確認

記事生成ページで実際のAPI呼び出しが動作するか確認。

---

## セキュリティ推奨設定

### 1. .envファイルの保護

```bash
chmod 600 config/.env
```

### 2. vendor/, config/, database/ へのアクセス禁止

`.htaccess` で既に設定済みですが、念のため確認:

```bash
cat public/.htaccess | grep -A 3 "vendor"
```

出力:
```apache
# セキュリティ: vendor/, config/, database/へのアクセス禁止
RewriteRule ^(vendor|config|database|storage)/ - [F,L]
```

### 3. APP_DEBUG=false

本番環境では必ず `APP_DEBUG=false` に設定:

```bash
vi config/.env
# APP_DEBUG=false を確認
```

---

## 完了チェックリスト

- [ ] `git pull` でコード取得
- [ ] `composer install` で依存関係インストール
- [ ] `php database/init_db.php` でDB初期化
- [ ] `chmod 777 storage database` でパーミッション設定
- [ ] `chmod 666 database/protea.db` でDBファイル書き込み可能化
- [ ] https://www.yojitu.com/protea-app/ でアクセス確認
- [ ] ダッシュボード、アップロード、一覧ページの動作確認

---

## サポート

問題が発生した場合は、以下を確認してください:

1. **エラーログ**: `tail -f storage/logs/*.log`
2. **PHPエラーログ**: Xserverのエラーログ
3. **USER_GUIDE.md**: 詳細な使い方マニュアル
4. **README.md**: プロジェクト概要とセットアップ
