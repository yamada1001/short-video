# デプロイ手順書

Xserverへのセミナー管理システムデプロイ手順を記載します。

## 📋 デプロイチェックリスト

### 事前準備

- [ ] Squareアカウント作成済み
- [ ] Gmail（またはSMTPサーバー）準備済み
- [ ] Xserverアカウント・ドメイン設定済み
- [ ] SSH接続情報確認済み
- [ ] FTPクライアントインストール済み（FileZilla等）

---

## 🚀 デプロイ手順

### ステップ1: ファイルアップロード

#### 方法A: SCP（推奨）

```bash
# ローカルマシンで実行
cd /path/to/local/yojitu.com
scp -r seminar-system xs545151@sv12345.xserver.jp:~/yojitu.com/public_html/
```

#### 方法B: FTP（FileZilla）

1. FileZillaを起動
2. 接続情報を入力:
   - ホスト: `sv12345.xserver.jp`
   - ユーザー名: `xs545151`
   - パスワード: サーバーパスワード
   - ポート: 22（SFTP）
3. `/home/xs545151/yojitu.com/public_html/`に`seminar-system`フォルダをアップロード

---

### ステップ2: SSH接続

```bash
ssh xs545151@sv12345.xserver.jp
cd ~/yojitu.com/public_html/seminar-system
```

---

### ステップ3: Composer実行

```bash
# Composerインストール確認
composer --version

# 依存パッケージインストール
composer install --no-dev --optimize-autoloader

# 成功メッセージ確認
# "Generating optimized autoload files" と表示されればOK
```

**エラーが出る場合**:

```bash
# メモリ不足エラーの場合
php -d memory_limit=-1 /usr/local/bin/composer install --no-dev
```

---

### ステップ4: パーミッション設定

```bash
# ディレクトリ
chmod 705 public public/admin public/api cron
chmod 707 logs uploads uploads/seminars

# PHPファイル
find public -name "*.php" -type f -exec chmod 604 {} \;
find src -name "*.php" -type f -exec chmod 604 {} \;
find config -name "*.php" -type f -exec chmod 604 {} \;
chmod 604 public/admin/*.php
chmod 604 public/api/*.php

# Cronスクリプト
chmod 755 cron/send-reminders.php
chmod 755 cron/send-thanks.php

# .htaccess
chmod 644 .htaccess
```

---

### ステップ5: データベース作成

#### phpMyAdminで実施

1. Xserverサーバーパネルにログイン
2. 「phpMyAdmin」を開く
3. 「SQL」タブをクリック
4. 以下のSQLを実行:

```sql
-- データベース作成
CREATE DATABASE xs545151_seminar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

5. 「ユーザーアカウント」→「ユーザーアカウントを追加する」

```
ユーザー名: xs545151_seminar
ホスト名: localhost
パスワード: [強力なパスワードを生成]
```

6. 権限で「全てチェック」→「実行」

#### テーブル作成

1. 左サイドバーで`xs545151_seminar`を選択
2. 「インポート」タブ
3. `database/schema.sql`をアップロード
4. 「実行」

または、SSHで実行:

```bash
mysql -u xs545151_seminar -p xs545151_seminar < database/schema.sql
# パスワード入力
```

---

### ステップ6: .env設定

```bash
# .envファイル作成
cp .env.example .env
nano .env
```

#### .env設定内容

```env
# Square API（本番環境）
SQUARE_ACCESS_TOKEN=EAAAxxxxxxxxxxxxxxxxxx
SQUARE_APPLICATION_ID=sq0idp-xxxxxxxxxx
SQUARE_LOCATION_ID=L0xxxxxxxxxxxx
SQUARE_WEBHOOK_SIGNATURE_KEY=xxxxxxxxxx
SQUARE_ENVIRONMENT=production

# Database
DB_HOST=localhost
DB_NAME=xs545151_seminar
DB_USER=xs545151_seminar
DB_PASSWORD=your_strong_password_here
DB_CHARSET=utf8mb4

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yojitu.com/seminar-system

# Mail（Gmail使用の場合）
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=xxxx xxxx xxxx xxxx
MAIL_FROM_ADDRESS=noreply@yojitu.com
MAIL_FROM_NAME=セミナー運営事務局

# Logging
LOG_LEVEL=info
```

#### .envパーミッション設定

```bash
chmod 600 .env
```

---

### ステップ7: Square Webhook設定

1. [Square Developer Dashboard](https://developer.squareup.com/apps)にログイン
2. アプリケーションを選択（なければ作成）
3. 左サイドバー「Webhooks」
4. 「Add endpoint」をクリック
5. 設定:
   ```
   Endpoint URL: https://yojitu.com/seminar-system/public/webhook.php
   API version: 2024-11-20（最新）
   ```
6. イベントを選択:
   - `payment.updated`にチェック
7. 「Save」
8. 表示された「Signature key」をコピー
9. `.env`の`SQUARE_WEBHOOK_SIGNATURE_KEY`に貼り付け

---

### ステップ8: Gmail SMTP設定

#### Googleアカウント設定

1. [Google アカウント](https://myaccount.google.com/)にログイン
2. 「セキュリティ」→「2段階認証プロセス」を有効化
3. 「アプリパスワード」を選択
4. アプリ: 「メール」、デバイス: 「その他」→「セミナーシステム」
5. 「生成」をクリック
6. 表示された16桁のパスワード（スペース含む）をコピー
7. `.env`の`SMTP_PASSWORD`に貼り付け

---

### ステップ9: Cron設定

#### Xserverサーバーパネルで設定

1. サーバーパネルにログイン
2. 「Cron設定」をクリック
3. ドメイン`yojitu.com`を選択
4. 「Cron設定追加」タブ

#### リマインダーメール（毎日18:00）

```
分: 0
時: 18
日: *
月: *
曜日: *
コマンド: /usr/bin/php /home/xs545151/yojitu.com/public_html/seminar-system/cron/send-reminders.php
コメント: セミナーリマインダーメール送信
```

#### サンクスメール（毎日22:00）

```
分: 0
時: 22
日: *
月: *
曜日: *
コマンド: /usr/bin/php /home/xs545151/yojitu.com/public_html/seminar-system/cron/send-thanks.php
コメント: セミナーサンクスメール送信
```

5. 「確認画面へ進む」→「追加する」

---

### ステップ10: 動作確認

#### 1. 管理画面アクセス

```
https://yojitu.com/seminar-system/public/admin/
```

- ダッシュボードが表示されればOK
- エラーが出る場合は`logs/app.log`を確認

#### 2. テストセミナー作成

1. 「セミナー管理」→「新規セミナー作成」
2. 必須項目を入力:
   - セミナー名: 「テストセミナー」
   - 開始日時: 明日の日付
   - 終了日時: 明日の日付（開始+2時間）
   - 価格: 1000
3. 「作成」をクリック

#### 3. 申込テスト

```
https://yojitu.com/seminar-system/public/index.php
```

1. テストセミナーが表示されることを確認
2. 申込フォームに入力
3. 申込完了メールが届くか確認

#### 4. Cron手動実行テスト

```bash
# SSH接続
cd ~/yojitu.com/public_html/seminar-system

# リマインダーメール
php cron/send-reminders.php
# 「送信対象の参加者はいません。」と表示されればOK

# サンクスメール
php cron/send-thanks.php
# 「送信対象の参加者はいません。」と表示されればOK
```

#### 5. ログ確認

```bash
tail -n 100 logs/app.log
```

エラーがないことを確認。

---

## 🔍 デプロイ後チェックリスト

### 必須項目

- [ ] 管理画面にアクセスできる
- [ ] セミナーを作成できる
- [ ] 申込フォームが表示される
- [ ] 申込確認メールが届く
- [ ] Square決済ページに遷移できる
- [ ] Webhook URLが正しく設定されている
- [ ] Cronが設定されている
- [ ] ログファイルにエラーがない

### オプション項目

- [ ] テスト申込を完了させる
- [ ] 支払い完了メールが届く（Sandbox決済）
- [ ] QRコードが表示される
- [ ] QRスキャンでチェックインできる
- [ ] 欠席フォームが動作する
- [ ] PDFアップロードが動作する

---

## 🐛 トラブルシューティング

### 500 Internal Server Error

**原因**: .htaccess または PHPエラー

**解決方法**:
```bash
# エラーログ確認
tail -n 50 ~/yojitu.com/public_html/seminar-system/logs/app.log

# Apacheエラーログ確認（Xserver）
tail -n 50 ~/log/yojitu.com/error_log
```

### Composer install エラー

**原因**: メモリ不足

**解決方法**:
```bash
php -d memory_limit=-1 /usr/local/bin/composer install --no-dev
```

### データベース接続エラー

**原因**: .env設定が間違っている

**解決方法**:
1. `.env`のDB設定を再確認
2. phpMyAdminでデータベース・ユーザーが存在するか確認
3. パスワードが正しいか確認

```bash
# MySQL接続テスト
mysql -u xs545151_seminar -p xs545151_seminar
# パスワード入力してログインできればOK
```

### メールが送信されない

**原因**: SMTP設定エラー

**解決方法**:
```bash
# ログ確認
grep 'メール送信' logs/app.log

# 手動テスト
php -r "
require 'vendor/autoload.php';
require 'config/config.php';
\$sender = new \Seminar\EmailSender();
echo 'EmailSender created successfully';
"
```

### Cronが実行されない

**原因**: パーミッションまたはパス

**解決方法**:
```bash
# Cronスクリプトのパーミッション確認
ls -la cron/

# PHPパス確認
which php
# /usr/bin/php であることを確認

# 手動実行でエラー確認
/usr/bin/php cron/send-reminders.php
```

---

## 📊 本番運用開始後の監視

### 毎日確認すること

```bash
# ログ確認（エラーチェック）
grep -i error logs/app.log

# Cron実行ログ確認
grep '\[Cron\]' logs/app.log
```

### 週1回確認すること

- 参加者数の推移
- メール送信成功率
- エラーログの傾向
- ディスク使用量

### 月1回確認すること

- データベースバックアップ
- Square決済ログ確認
- PDFファイル整理

---

## 🔄 アップデート手順

新機能追加時:

```bash
# SSH接続
cd ~/yojitu.com/public_html/seminar-system

# Gitで最新版を取得（Gitを使用している場合）
git pull origin main

# Composer更新
composer install --no-dev --optimize-autoloader

# データベースマイグレーション（必要な場合）
mysql -u xs545151_seminar -p xs545151_seminar < database/migration_xxx.sql

# キャッシュクリア（必要な場合）
rm -rf logs/*.log
```

---

## 📞 サポート連絡先

問題が解決しない場合:

1. `WORK_LOG.md`で実装詳細確認
2. `README.md`で基本的な使い方確認
3. `logs/app.log`のエラーメッセージを記録
4. サポートに連絡（エラーログを添付）

---

## ✅ デプロイ完了

すべてのチェックリストが完了したら、本番運用開始です！

**本番URL**:
- 申込ページ: `https://yojitu.com/seminar-system/public/index.php`
- 管理画面: `https://yojitu.com/seminar-system/public/admin/`

お疲れ様でした！🎉
