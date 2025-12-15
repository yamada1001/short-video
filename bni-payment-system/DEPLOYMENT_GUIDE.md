# BNI Payment System - デプロイメントガイド

## 目次
1. [事前準備](#事前準備)
2. [Xserverへのデプロイ](#xserverへのデプロイ)
3. [データベース設定](#データベース設定)
4. [環境変数設定](#環境変数設定)
5. [Square設定](#square設定)
6. [Basic認証設定](#basic認証設定)
7. [動作確認](#動作確認)
8. [トラブルシューティング](#トラブルシューティング)

---

## 事前準備

### 1. Square アカウント設定

#### Application作成
1. [Square Developer Dashboard](https://developer.squareup.com/apps) にログイン
2. "Create App" をクリック
3. アプリケーション名: `BNI Payment System`
4. 作成完了

#### Credentials取得
1. 左メニュー "Credentials" をクリック
2. **Sandbox環境**（テスト用）:
   - Sandbox Access Token をコピー
   - Sandbox Application ID をコピー
   - Sandbox Location ID をコピー（Locations タブ）
3. **Production環境**（本番用）:
   - Production Access Token をコピー
   - Production Application ID をコピー
   - Production Location ID をコピー

#### Webhook設定
1. 左メニュー "Webhooks" をクリック
2. "Add Endpoint" をクリック
3. **Sandbox Endpoint**:
   - URL: `https://yojitu.com/bni-payment-system/webhook.php`
   - Events: `payment.created`, `payment.updated` を選択
   - Signature Key をコピー（自動生成される）
4. **Production Endpoint**: 本番稼働時に同様に設定

---

## Xserverへのデプロイ

### 1. SSHでサーバーに接続

```bash
ssh username@sv12345.xserver.jp
```

### 2. プロジェクトディレクトリ作成

```bash
cd ~/yojitu.com/public_html
mkdir bni-payment-system
cd bni-payment-system
```

### 3. ファイルアップロード

#### 方法A: Git（推奨）

```bash
# リモートリポジトリからクローン
git clone https://github.com/your-repo/bni-payment-system.git .

# または、既存ディレクトリで
git init
git remote add origin https://github.com/your-repo/bni-payment-system.git
git pull origin main
```

#### 方法B: FTP/SFTP

FileZillaなどのFTPクライアントで以下をアップロード:
- `/config/`
- `/database/`
- `/public/`
- `/src/`
- `composer.json`
- `.env.example`

### 4. Composer依存パッケージインストール

```bash
cd ~/yojitu.com/public_html/bni-payment-system

# Composer インストール（未インストールの場合）
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer

# 依存パッケージインストール
php composer install --no-dev --optimize-autoloader
```

---

## データベース設定

### 1. MySQL データベース作成

Xserverサーバーパネルから:

1. "MySQL設定" をクリック
2. "MySQL追加" タブ:
   - データベース名: `bni_payment_db` (例)
   - 文字コード: `UTF-8`
   - 作成
3. "MySQLユーザー追加" タブ:
   - ユーザー名: `bni_user` (例)
   - パスワード: （強力なパスワード生成）
   - 作成
4. "アクセス権所有ユーザー" タブ:
   - データベース `bni_payment_db` に `bni_user` を追加

### 2. テーブル作成

```bash
# SSHで接続
ssh username@sv12345.xserver.jp

# MySQLにログイン
mysql -u bni_user -p bni_payment_db

# スキーマ実行
SOURCE /home/username/yojitu.com/public_html/bni-payment-system/database/schema.sql;

# テストデータ投入（本番環境では不要）
SOURCE /home/username/yojitu.com/public_html/bni-payment-system/database/seeds.sql;

# 確認
SHOW TABLES;
SELECT * FROM members;
EXIT;
```

---

## 環境変数設定

### 1. .env ファイル作成

```bash
cd ~/yojitu.com/public_html/bni-payment-system
cp .env.example .env
vi .env
```

### 2. .env 編集

```env
# ==================== アプリケーション設定 ====================
APP_NAME="BNI Payment System"
APP_URL="https://yojitu.com/bni-payment-system"
APP_DEBUG=false
APP_TIMEZONE="Asia/Tokyo"

# ==================== データベース設定 ====================
DB_HOST="localhost"
DB_NAME="bni_payment_db"
DB_USER="bni_user"
DB_PASSWORD="YOUR_STRONG_PASSWORD"
DB_CHARSET="utf8mb4"

# ==================== Square API設定 ====================
# 本番環境
SQUARE_ENVIRONMENT="production"
SQUARE_ACCESS_TOKEN="YOUR_PRODUCTION_ACCESS_TOKEN"
SQUARE_APPLICATION_ID="YOUR_PRODUCTION_APP_ID"
SQUARE_LOCATION_ID="YOUR_PRODUCTION_LOCATION_ID"
SQUARE_WEBHOOK_SIGNATURE_KEY="YOUR_PRODUCTION_WEBHOOK_SIGNATURE_KEY"

# Sandbox環境（テスト用）
# SQUARE_ENVIRONMENT="sandbox"
# SQUARE_ACCESS_TOKEN="YOUR_SANDBOX_ACCESS_TOKEN"
# SQUARE_APPLICATION_ID="YOUR_SANDBOX_APP_ID"
# SQUARE_LOCATION_ID="YOUR_SANDBOX_LOCATION_ID"
# SQUARE_WEBHOOK_SIGNATURE_KEY="YOUR_SANDBOX_WEBHOOK_SIGNATURE_KEY"

# ==================== ログ設定 ====================
LOG_LEVEL="info"
LOG_PATH="/home/username/yojitu.com/logs/bni-payment-system"
```

### 3. ログディレクトリ作成

```bash
mkdir -p ~/yojitu.com/logs/bni-payment-system
chmod 755 ~/yojitu.com/logs/bni-payment-system
```

---

## Square設定

### 1. Webhook URL登録

Square Developer Dashboard:
1. "Webhooks" → "Add Endpoint"
2. **Production環境**:
   - URL: `https://yojitu.com/bni-payment-system/webhook.php`
   - Events: `payment.created`, `payment.updated`
   - "Save"
3. Signature Key をコピーして `.env` の `SQUARE_WEBHOOK_SIGNATURE_KEY` に設定

### 2. Webhook テスト

Square Developer Dashboard:
1. "Webhooks" → 作成したEndpointをクリック
2. "Send Test Event" → `payment.created` を選択
3. "Send Event"
4. ログ確認:

```bash
tail -f ~/yojitu.com/logs/bni-payment-system/webhook-*.log
```

成功すれば `Webhook processed successfully` と記録されます。

---

## Basic認証設定

### 1. .htpasswd 生成

```bash
cd ~/yojitu.com/public_html/bni-payment-system/admin

# パスワード生成
htpasswd -c .htpasswd admin
# パスワード入力（例: YourStrongPassword123!）

# パーミッション設定
chmod 600 .htpasswd
```

### 2. .htaccess パス修正

`/public/admin/.htaccess` を編集:

```apache
AuthUserFile /home/username/yojitu.com/public_html/bni-payment-system/admin/.htpasswd
```

**絶対パスに変更してください！**

### 3. 確認

```bash
# パス確認
pwd
# 出力例: /home/username/yojitu.com/public_html/bni-payment-system/admin
```

---

## 動作確認

### 1. メンバー支払いページ

```
https://yojitu.com/bni-payment-system/
```

**確認項目:**
- [ ] ページが正しく表示される
- [ ] メンバー一覧が表示される
- [ ] メンバーを選択して「お支払いページへ」ボタンを押す
- [ ] Square決済ページにリダイレクトされる
- [ ] 決済完了後、サンクスページが表示される

### 2. 管理者ダッシュボード

```
https://yojitu.com/bni-payment-system/admin/
```

**確認項目:**
- [ ] Basic認証ダイアログが表示される
- [ ] ユーザー名・パスワード入力後、ダッシュボードが表示される
- [ ] 支払い状況が正しく表示される
- [ ] 統計（総メンバー数、支払い済み、未払い、合計金額）が正しい
- [ ] CSVエクスポートが動作する

### 3. メンバー管理

```
https://yojitu.com/bni-payment-system/admin/members.php
```

**確認項目:**
- [ ] メンバー一覧が表示される
- [ ] 新規メンバー追加が動作する
- [ ] メンバー編集が動作する
- [ ] メンバー削除が動作する（確認ダイアログ表示）

### 4. Webhook

Square Sandboxで実際の決済をテスト:

1. メンバー支払いページでテスト決済
2. Square Sandboxのテストカード情報:
   - カード番号: `4111 1111 1111 1111`
   - CVV: `111`
   - 有効期限: 任意の未来の日付
   - 郵便番号: `12345`
3. 決済完了後、データベース確認:

```bash
mysql -u bni_user -p bni_payment_db
SELECT * FROM payments ORDER BY created_at DESC LIMIT 5;
```

支払い記録が自動的に追加されていることを確認。

---

## トラブルシューティング

### 1. "500 Internal Server Error"

**原因:**
- PHP構文エラー
- .htaccessの設定ミス
- パーミッション問題

**対処:**

```bash
# エラーログ確認
tail -f ~/yojitu.com/logs/error_log

# PHP構文チェック
php -l public/index.php

# パーミッション確認
ls -la public/
chmod 755 public/
```

### 2. データベース接続エラー

**原因:**
- `.env` の接続情報が間違っている
- データベースが作成されていない

**対処:**

```bash
# 接続テスト
mysql -u bni_user -p bni_payment_db

# .env確認
cat .env | grep DB_
```

### 3. Square API エラー

**原因:**
- Access Token が間違っている
- Location ID が間違っている
- 環境（sandbox/production）が間違っている

**対処:**

```bash
# ログ確認
tail -f ~/yojitu.com/logs/bni-payment-system/app-*.log

# .env確認
cat .env | grep SQUARE_
```

### 4. Webhook が動作しない

**原因:**
- Webhook URLが間違っている
- Signature Keyが間違っている
- 署名検証エラー

**対処:**

```bash
# Webhookログ確認
tail -f ~/yojitu.com/logs/bni-payment-system/webhook-*.log

# Square Developer Dashboardでイベント履歴確認
# Webhooks → Endpoint → Event Log
```

### 5. Basic認証が動作しない

**原因:**
- `.htaccess` の `AuthUserFile` パスが間違っている
- `.htpasswd` のパーミッションが正しくない

**対処:**

```bash
# 絶対パス確認
pwd
# /home/username/yojitu.com/public_html/bni-payment-system/admin

# .htaccessパス修正
vi admin/.htaccess
# AuthUserFile を絶対パスに変更

# パーミッション確認
chmod 600 admin/.htpasswd
```

### 6. ログが出力されない

**原因:**
- ログディレクトリが存在しない
- 書き込み権限がない

**対処:**

```bash
# ディレクトリ作成
mkdir -p ~/yojitu.com/logs/bni-payment-system

# パーミッション設定
chmod 755 ~/yojitu.com/logs/bni-payment-system
```

---

## セキュリティチェックリスト

デプロイ後、以下を必ず確認してください:

- [ ] `.env` ファイルがWeb経由でアクセスできないことを確認
- [ ] `APP_DEBUG=false` に設定されていることを確認
- [ ] Strong Password を使用していることを確認
- [ ] HTTPS が有効化されていることを確認
- [ ] Basic認証が正しく動作することを確認
- [ ] データベースユーザーの権限が最小限であることを確認
- [ ] ログファイルのパーミッションが適切であることを確認 (644)

---

## 定期メンテナンス

### ログローテーション

```bash
# 古いログ削除（30日以上前）
find ~/yojitu.com/logs/bni-payment-system/ -name "*.log" -mtime +30 -delete
```

### データベースバックアップ

```bash
# 週次バックアップ（cron設定）
mysqldump -u bni_user -p bni_payment_db > ~/backups/bni_payment_$(date +%Y%m%d).sql
```

### Composer依存パッケージ更新

```bash
cd ~/yojitu.com/public_html/bni-payment-system
php composer update --no-dev
```

---

## サポート

問題が解決しない場合:
1. ログファイルを確認（`~/yojitu.com/logs/bni-payment-system/`）
2. Square Developer Dashboardのイベント履歴を確認
3. XserverサポートにPHP/MySQL設定を確認

---

**デプロイ完了！** 🎉
