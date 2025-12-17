# セミナー管理システム

セミナーの申込受付から決済、出席管理まで一貫して行える完全統合型管理システムです。

## 📋 目次

- [主な機能](#主な機能)
- [必要要件](#必要要件)
- [インストール](#インストール)
- [初期設定](#初期設定)
- [使い方](#使い方)
- [Cron設定](#cron設定)
- [トラブルシューティング](#トラブルシューティング)
- [ライセンス](#ライセンス)

---

## ✨ 主な機能

### ユーザー向け機能

- **セミナー申込フォーム**
  - セミナー一覧表示
  - 個人情報入力
  - 申込時アンケート（カスタマイズ可能）
  - 重複申込防止

- **Square決済連携**
  - Payment Links APIによる安全な決済
  - クレジットカード・デビットカード対応
  - 繰越クレジット自動適用

- **欠席申請フォーム**
  - トークンベース認証
  - セミナー料金と同額のクレジット付与
  - 次回セミナーで利用可能

- **QRコードチェックイン**
  - スマホでQRコード表示
  - 受付スタッフがスキャンして出席確認

- **自動メール送信**
  - 申込確認メール（申込後すぐ）
  - 支払い完了メール（決済完了後）
  - リマインダーメール（前日18:00）
  - サンクスメール（当日終了後22:00、PDF添付）

### 管理者向け機能

- **ダッシュボード**
  - 統計カード（総セミナー数、総参加者数、今月売上、出席率）
  - 月別申込推移グラフ
  - ステータス別参加者数グラフ
  - 直近のセミナー一覧
  - 最近の申込者テーブル

- **セミナー管理**
  - セミナーCRUD（作成・編集・削除）
  - PDF資料アップロード（最大10MB）
  - サンクスメールカスタマイズ
  - 有効/無効切り替え
  - 申込受付状況管理

- **参加者管理**
  - 参加者一覧表示
  - フィルター機能（セミナー別、ステータス別）
  - ステータス変更（申込済/支払済/出席済/欠席）
  - クレジット金額確認
  - 削除機能

- **QRスキャンチェックイン**
  - カメラでQRコード読み取り
  - リアルタイム出席登録
  - 参加者情報即時表示

---

## 🔧 必要要件

### サーバー要件

- **Xserver**（または同等のレンタルサーバー）
- **PHP 8.1以上**
- **MySQL 8.0以上**
- **Composer**
- **SSH接続可能**（Composer実行用）

### 外部サービス

- **Square アカウント**（決済処理）
  - Access Token
  - Application ID
  - Location ID
  - Webhook Signature Key

- **SMTPサーバー**（メール送信）
  - Gmail推奨（アプリパスワード）
  - または Xserver メールサーバー

---

## 📥 インストール

### 1. ファイルアップロード

```bash
# ローカルからXserverにアップロード
scp -r seminar-system xs545151@sv12345.xserver.jp:~/yojitu.com/public_html/
```

または、FTPクライアント（FileZilla等）でアップロード。

### 2. SSH接続

```bash
ssh xs545151@sv12345.xserver.jp
cd ~/yojitu.com/public_html/seminar-system
```

### 3. Composer実行

```bash
composer install
```

### 4. パーミッション設定

```bash
# ディレクトリ
chmod 705 public public/admin public/api
chmod 707 logs uploads uploads/seminars

# PHPファイル
find . -name "*.php" -type f -exec chmod 604 {} \;

# .env（作成後）
chmod 600 .env

# Cronスクリプト
chmod 755 cron/send-reminders.php
chmod 755 cron/send-thanks.php
```

### 5. データベース作成

XserverのphpMyAdminで以下を実行：

```sql
-- データベース作成
CREATE DATABASE xs545151_seminar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- ユーザー作成（既にある場合はスキップ）
CREATE USER 'xs545151_seminar'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON xs545151_seminar.* TO 'xs545151_seminar'@'localhost';
FLUSH PRIVILEGES;
```

### 6. テーブル作成

```bash
# SSH接続中
mysql -u xs545151_seminar -p xs545151_seminar < database/schema.sql
```

または、phpMyAdminで `database/schema.sql` をインポート。

---

## ⚙️ 初期設定

### 1. .envファイル作成

```bash
cp .env.example .env
nano .env
```

### 2. .env設定

```env
# Square API
SQUARE_ACCESS_TOKEN=your_square_access_token
SQUARE_APPLICATION_ID=your_square_application_id
SQUARE_LOCATION_ID=your_square_location_id
SQUARE_WEBHOOK_SIGNATURE_KEY=your_square_webhook_signature_key
SQUARE_ENVIRONMENT=production  # または sandbox

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

# Mail (Gmail推奨)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your_email@gmail.com
SMTP_PASSWORD=your_app_password
MAIL_FROM_ADDRESS=noreply@yojitu.com
MAIL_FROM_NAME=セミナー運営事務局

# Logging
LOG_LEVEL=info
```

### 3. Square Webhook設定

1. [Square Developer Dashboard](https://developer.squareup.com/apps)にログイン
2. アプリケーションを選択
3. 「Webhooks」→「Add endpoint」
4. Webhook URL: `https://yojitu.com/seminar-system/public/webhook.php`
5. イベント選択: `payment.updated`
6. 保存後、Signature Keyをコピーして`.env`に設定

---

## 📖 使い方

### セミナーを作成する

1. 管理画面にアクセス: `https://yojitu.com/seminar-system/public/admin/`
2. 「セミナー管理」→「新規セミナー作成」
3. 必要事項を入力:
   - セミナー名
   - 説明
   - 開催場所
   - 開始日時・終了日時
   - 申込締切日時
   - 価格
   - スライドPDF（任意）
   - サンクスメール設定
4. 「作成」をクリック

### 申込受付を開始する

- セミナーが「有効」になっていることを確認
- 申込ページURL: `https://yojitu.com/seminar-system/public/index.php`
- ユーザーに上記URLを共有

### 参加者を管理する

1. 「参加者管理」をクリック
2. フィルター機能で絞り込み:
   - セミナー別
   - ステータス別（申込済/支払済/出席済/欠席）
3. ステータス変更:
   - ドロップダウンから選択→自動保存

### QRコードチェックインを行う

1. 「QRチェックイン」をクリック
2. 「カメラを起動」
3. 参加者のQRコードをスキャン
4. 自動的に「出席済」に更新

### ダッシュボードで統計確認

- 総セミナー数
- 総参加者数
- 今月の売上
- 出席率
- 月別申込推移グラフ
- ステータス別参加者数

---

## ⏰ Cron設定

詳細は `CRON_SETUP.md` を参照してください。

### Xserverサーバーパネル

1. 「Cron設定」を開く
2. 以下のジョブを追加:

#### リマインダーメール（毎日18:00）

```
分: 0
時: 18
日: *
月: *
曜日: *
コマンド: /usr/bin/php /home/xs545151/yojitu.com/public_html/seminar-system/cron/send-reminders.php
```

#### サンクスメール（毎日22:00）

```
分: 0
時: 22
日: *
月: *
曜日: *
コマンド: /usr/bin/php /home/xs545151/yojitu.com/public_html/seminar-system/cron/send-thanks.php
```

### 動作確認

```bash
# SSH接続
cd ~/yojitu.com/public_html/seminar-system

# 手動実行でテスト
php cron/send-reminders.php
php cron/send-thanks.php
```

---

## 🐛 トラブルシューティング

### メールが送信されない

**原因**: SMTP設定が間違っている

**解決方法**:
1. `.env`のSMTP設定を確認
2. Gmailの場合、2段階認証+アプリパスワードを使用
3. ログを確認: `tail -n 50 logs/app.log`

### 決済が完了しない

**原因**: Square Webhook設定が間違っている

**解決方法**:
1. Square Developer Dashboardで設定確認
2. Webhook URL: `https://yojitu.com/seminar-system/public/webhook.php`
3. Signature Keyが`.env`と一致しているか確認
4. `logs/app.log`でエラー確認

### QRコードが表示されない

**原因**: qrcodejs2のCDNが読み込めない

**解決方法**:
1. ブラウザのコンソールでエラー確認
2. CDNが正常か確認: `https://cdn.jsdelivr.net/npm/qrcodejs2@0.0.2/qrcode.min.js`
3. キャッシュをクリア

### 403 Forbidden エラー

**原因**: パーミッション設定が間違っている

**解決方法**:
```bash
# ディレクトリ
chmod 705 public public/admin

# PHPファイル
chmod 604 public/*.php
chmod 604 public/admin/*.php
```

### データベース接続エラー

**原因**: `.env`のDB設定が間違っている

**解決方法**:
1. `.env`のDB_HOST, DB_NAME, DB_USER, DB_PASSWORDを確認
2. phpMyAdminでデータベースが存在するか確認
3. ユーザー権限を確認

---

## 📁 ディレクトリ構造

```
seminar-system/
├── config/
│   └── config.php              # 設定ファイル
├── cron/
│   ├── send-reminders.php      # リマインダーメール送信
│   └── send-thanks.php         # サンクスメール送信
├── database/
│   ├── schema.sql              # テーブル定義
│   └── seeds.sql               # サンプルデータ
├── logs/
│   └── app.log                 # ログファイル
├── public/
│   ├── admin/                  # 管理画面
│   │   ├── index.php           # ダッシュボード
│   │   ├── seminars.php        # セミナー管理
│   │   ├── seminar-form.php    # セミナー作成・編集
│   │   ├── attendees.php       # 参加者管理
│   │   └── checkin-scan.php    # QRスキャン
│   ├── api/
│   │   └── checkin.php         # チェックインAPI
│   ├── index.php               # 申込フォーム
│   ├── thank-you.php           # 申込完了ページ
│   ├── payment.php             # 支払いページ
│   ├── webhook.php             # Square Webhook
│   ├── cancel.php              # 欠席フォーム
│   └── checkin.php             # QRコード表示
├── src/
│   ├── Database.php            # データベースクラス
│   ├── Logger.php              # ロガー
│   ├── helpers.php             # ヘルパー関数
│   ├── SquareClient.php        # Square API
│   ├── Seminar.php             # セミナークラス
│   ├── Attendee.php            # 参加者クラス
│   ├── Survey.php              # アンケートクラス
│   └── EmailSender.php         # メール送信クラス
├── uploads/
│   └── seminars/               # PDFアップロード先
├── .env.example                # 環境変数テンプレート
├── .env                        # 環境変数（要作成）
├── .htaccess                   # Apache設定
├── composer.json               # Composer設定
├── README.md                   # このファイル
├── CRON_SETUP.md               # Cron設定手順
├── SPECIFICATION.md            # 仕様書
└── WORK_LOG.md                 # 作業ログ
```

---

## 🔒 セキュリティ

- **トークンベース認証**（欠席フォーム、QRチェックイン）
- **SQLインジェクション対策**（PDOプリペアドステートメント）
- **XSS対策**（htmlspecialchars()）
- **CSRF対策**（トークン検証）
- **Square Webhook署名検証**（HMAC-SHA256）
- **.envファイルアクセス制限**（.htaccess）
- **PDFファイル以外のアップロード制限**

---

## 📞 サポート

問題が発生した場合:

1. `logs/app.log`を確認
2. `WORK_LOG.md`で実装詳細を確認
3. `CRON_SETUP.md`でCron設定を確認
4. エラーメッセージをGoogleで検索

---

## 📄 ライセンス

このプロジェクトは独自ライセンスです。
© 2025 YOJITU.COM

---

## 🙏 謝辞

- **Square** - 決済処理
- **PHPMailer** - メール送信
- **Chart.js** - グラフ描画
- **qrcodejs2** - QRコード生成
- **jsQR** - QRコード読み取り

---

**開発完了日**: 2025-12-17
**バージョン**: 1.0.0
**進捗率**: 95%
