# 500エラー トラブルシューティング

セミナー管理システムで500エラーが発生した場合の対処方法です。

## 🔍 原因の特定手順

### ステップ1: GitHub Actionsのログを確認

1. GitHubリポジトリを開く
   ```
   https://github.com/yamada1001/short-video/actions
   ```

2. 最新の「Deploy Seminar System to Xserver」ワークフローを開く

3. ステータスを確認:
   - ✅ 緑色（成功）→ デプロイは成功しているので別の問題
   - ❌ 赤色（失敗）→ デプロイが失敗している

4. 失敗している場合、エラーログを確認

---

### ステップ2: SSH接続してファイル確認

```bash
# SSH接続
ssh xs545151@sv12345.xserver.jp

# seminar-systemディレクトリに移動
cd ~/yojitu.com/public_html/seminar-system

# ファイル一覧確認
ls -la
```

**確認すべきファイル・ディレクトリ:**
- `vendor/` - Composerの依存パッケージ
- `.env` - 環境変数設定
- `public/` - 公開ディレクトリ
- `logs/` - ログディレクトリ

---

## 🐛 よくある原因と解決方法

### 原因A: vendorディレクトリが存在しない

**確認:**
```bash
ls -la vendor/autoload.php
```

**エラーが出る場合の解決方法:**
```bash
cd ~/yojitu.com/public_html/seminar-system
composer install --no-dev --optimize-autoloader
```

**メモリ不足エラーが出る場合:**
```bash
php -d memory_limit=-1 /usr/local/bin/composer install --no-dev
```

---

### 原因B: .envファイルが存在しない

**確認:**
```bash
ls -la .env
```

**存在しない場合の解決方法:**
```bash
cd ~/yojitu.com/public_html/seminar-system
cp .env.example .env
nano .env
```

**.envに設定する内容:**
```env
# Square API
SQUARE_ACCESS_TOKEN=EAAAxxxxxxxxxxxxxxxxxx
SQUARE_APPLICATION_ID=sq0idp-xxxxxxxxxx
SQUARE_LOCATION_ID=L0xxxxxxxxxxxx
SQUARE_WEBHOOK_SIGNATURE_KEY=xxxxxxxxxx
SQUARE_ENVIRONMENT=production

# Database
DB_HOST=localhost
DB_NAME=xs545151_seminar
DB_USER=xs545151_seminar
DB_PASSWORD=your_database_password
DB_CHARSET=utf8mb4

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yojitu.com/seminar-system

# Mail
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=noreply@yojitu.com
MAIL_FROM_NAME=セミナー運営事務局

# Logging
LOG_LEVEL=info
```

**保存後、パーミッション設定:**
```bash
chmod 600 .env
```

---

### 原因C: パーミッション問題

**確認:**
```bash
ls -la public/
ls -la public/admin/
```

**解決方法:**
```bash
cd ~/yojitu.com/public_html/seminar-system

# ディレクトリ
chmod 705 public public/admin public/api cron
chmod 707 logs uploads uploads/seminars

# PHPファイル
find public -name "*.php" -type f -exec chmod 604 {} \;
find src -name "*.php" -type f -exec chmod 604 {} \;
find config -name "*.php" -type f -exec chmod 604 {} \;

# Cronスクリプト
chmod 755 cron/send-reminders.php
chmod 755 cron/send-thanks.php

# .htaccess
chmod 644 .htaccess
```

---

### 原因D: データベースが存在しない

**確認:**
```bash
mysql -u xs545151_seminar -p -e "SHOW DATABASES LIKE 'xs545151_seminar';"
```

**データベースが存在しない場合:**

1. phpMyAdminを開く
2. 以下のSQLを実行:
```sql
CREATE DATABASE xs545151_seminar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

3. テーブル作成:
```bash
cd ~/yojitu.com/public_html/seminar-system
mysql -u xs545151_seminar -p xs545151_seminar < database/schema.sql
```

---

### 原因E: PHPバージョン問題

**.htaccessの確認:**
```bash
cat .htaccess | grep "AddHandler"
```

**以下が含まれているか確認:**
```apache
AddHandler application/x-httpd-php81 .php
```

**含まれていない場合、.htaccessの先頭に追加:**
```bash
nano .htaccess
```

先頭に追加:
```apache
# PHP 8.1を使用
AddHandler application/x-httpd-php81 .php
```

---

## 📋 エラーログの確認方法

### アプリケーションログ

```bash
# 最新50行
tail -n 50 ~/yojitu.com/public_html/seminar-system/logs/app.log

# リアルタイム監視
tail -f ~/yojitu.com/public_html/seminar-system/logs/app.log

# エラーのみ抽出
grep -i error ~/yojitu.com/public_html/seminar-system/logs/app.log
```

### Apacheエラーログ

```bash
# 最新50行
tail -n 50 ~/log/yojitu.com/error_log

# リアルタイム監視
tail -f ~/log/yojitu.com/error_log
```

---

## ✅ チェックリスト

デプロイ後、以下を順番に確認してください:

- [ ] GitHub Actionsが成功している（✅緑）
- [ ] `vendor/autoload.php`が存在する
- [ ] `.env`ファイルが存在し、正しく設定されている
- [ ] パーミッションが正しい（public: 705, PHPファイル: 604）
- [ ] データベースが存在する
- [ ] テーブルが作成されている
- [ ] .htaccessにPHP 8.1設定がある
- [ ] logs/ディレクトリが書き込み可能（707）

---

## 🔧 よくあるエラーメッセージと対処方法

### "Call to undefined function Dotenv\..."

**原因:** vendorディレクトリがない

**解決:**
```bash
composer install --no-dev
```

### "SQLSTATE[HY000] [1045] Access denied"

**原因:** データベース接続情報が間違っている

**解決:**
```bash
nano .env
# DB_HOST, DB_NAME, DB_USER, DB_PASSWORDを確認
```

### "failed to open stream: Permission denied"

**原因:** パーミッション問題

**解決:**
```bash
chmod 707 logs uploads
chmod 604 public/admin/index.php
```

### "Class 'Seminar\Database' not found"

**原因:** オートロードが機能していない

**解決:**
```bash
composer dump-autoload --optimize
```

---

## 🚀 完全リセット手順

全て試してもダメな場合、以下の手順で完全にやり直す:

```bash
# 1. ディレクトリを削除
cd ~/yojitu.com/public_html
rm -rf seminar-system

# 2. GitHubから再デプロイ
# GitHubで「Actions」→「Deploy Seminar System to Xserver」→「Re-run all jobs」

# 3. SSH接続して以下を実行
ssh xs545151@sv12345.xserver.jp
cd ~/yojitu.com/public_html/seminar-system

# 4. Composer実行
composer install --no-dev --optimize-autoloader

# 5. .env作成
cp .env.example .env
nano .env
# 設定を記入
chmod 600 .env

# 6. パーミッション設定
chmod 705 public public/admin public/api
chmod 707 logs uploads
chmod 644 .htaccess
find public -name "*.php" -exec chmod 604 {} \;

# 7. データベース作成（未作成の場合）
mysql -u xs545151_seminar -p xs545151_seminar < database/schema.sql

# 8. アクセステスト
curl -I https://yojitu.com/seminar-system/public/admin/
```

---

## 📞 それでも解決しない場合

以下の情報を集めて確認してください:

1. **GitHub Actionsのログ** (スクリーンショット)
2. **アプリケーションログ**
   ```bash
   tail -n 100 ~/yojitu.com/public_html/seminar-system/logs/app.log
   ```
3. **Apacheエラーログ**
   ```bash
   tail -n 100 ~/log/yojitu.com/error_log
   ```
4. **ファイル一覧**
   ```bash
   ls -laR ~/yojitu.com/public_html/seminar-system/
   ```
5. **composer.jsonの内容**
   ```bash
   cat ~/yojitu.com/public_html/seminar-system/composer.json
   ```

---

## 💡 デバッグモード有効化

一時的にエラーを画面に表示する方法:

**.envを編集:**
```bash
nano .env
```

以下を変更:
```env
APP_DEBUG=true  # falseからtrueに変更
```

**ブラウザでアクセス** → 詳細なエラーメッセージが表示される

**注意:** 本番環境では必ず`APP_DEBUG=false`に戻す！

---

## 🎯 成功の確認方法

以下のURLにアクセスしてエラーが出なければ成功:

- 管理画面: `https://yojitu.com/seminar-system/public/admin/`
- 申込ページ: `https://yojitu.com/seminar-system/public/index.php`

画面が正しく表示されれば完了です！
