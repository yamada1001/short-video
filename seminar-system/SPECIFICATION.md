# セミナー申込・決済・管理システム 仕様書

## 📋 プロジェクト概要

### システム名
**セミナー管理システム**

### 目的
セミナーの申込から決済、アンケート、フォローアップまでを一元管理するシステム

### 対象ユーザー
- **参加者**: セミナー申込・決済・アンケート回答
- **管理者**: セミナー管理・参加者管理・メール送信

---

## 🎯 主要機能

### 1. セミナー管理
- セミナーの作成・編集・削除
- 同時進行：1-3個程度
- 定員設定：なし（無制限）
- 申込期限：設定可能（例：開始1時間前まで）

### 2. 参加者管理
- 申込受付
- 1人が複数セミナーに申込可能
- ステータス管理（申込/欠席/支払済/参加済）
- QRコードでチェックイン機能

### 3. 決済機能
- Square Payment Links API使用
- 価格はセミナーごとに設定可能
- 欠席者の繰越クレジット管理・表示

### 4. アンケート機能
- **申込時アンケート**: 参加目的など
- **セミナー後アンケート**: 満足度など
- 質問タイプ：テキスト/ラジオボタン/チェックボックス
- 管理画面で質問を追加・編集・削除

### 5. メール送信機能
- **申込完了メール**: 申込直後（欠席リンク付き）
- **リマインドメール**: セミナー前日に自動送信
- **サンクスメール**: セミナー終了後に自動送信（PDF添付）
- **個別メール送信**: 管理画面から特定参加者へ送信
- SMTP: Xserver使用
- 送信者名: 管理画面で設定可能

### 6. 欠席管理
- サンクスメール内のリンクから欠席申請
- ワンクリック欠席（トークン認証）
- 欠席理由の記録
- 次回セミナーへ繰越（クレジット表示）

---

## 🗄️ データベース設計

### テーブル一覧

#### `seminars` - セミナー情報
```sql
CREATE TABLE seminars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'セミナー名',
    description TEXT COMMENT '説明',
    venue VARCHAR(255) COMMENT '開催場所',
    start_datetime DATETIME NOT NULL COMMENT '開始日時',
    end_datetime DATETIME NOT NULL COMMENT '終了日時',
    registration_deadline DATETIME COMMENT '申込締切日時',
    price INT NOT NULL DEFAULT 1000 COMMENT '価格（円）',
    pdf_path VARCHAR(255) COMMENT 'スライドPDFパス',
    thanks_mail_subject VARCHAR(255) COMMENT 'サンクスメール件名',
    thanks_mail_body TEXT COMMENT 'サンクスメール本文',
    mail_sender_name VARCHAR(100) COMMENT 'メール送信者名',
    is_active TINYINT(1) DEFAULT 1 COMMENT '有効/無効',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `attendees` - 参加者情報
```sql
CREATE TABLE attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seminar_id INT NOT NULL,
    name VARCHAR(100) NOT NULL COMMENT '氏名',
    email VARCHAR(255) NOT NULL COMMENT 'メールアドレス',
    phone VARCHAR(20) COMMENT '電話番号',
    status ENUM('applied', 'absent', 'paid', 'attended') DEFAULT 'applied' COMMENT 'ステータス',
    cancel_token VARCHAR(64) UNIQUE COMMENT '欠席用トークン',
    cancel_reason TEXT COMMENT '欠席理由',
    credit_amount INT DEFAULT 0 COMMENT '繰越クレジット（円）',
    qr_code_token VARCHAR(64) UNIQUE COMMENT 'QRコード用トークン',
    square_payment_id VARCHAR(255) COMMENT 'Square Payment ID',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '申込日時',
    paid_at TIMESTAMP NULL COMMENT '支払日時',
    attended_at TIMESTAMP NULL COMMENT '出席確認日時',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seminar_id) REFERENCES seminars(id) ON DELETE CASCADE,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `survey_questions` - アンケート質問
```sql
CREATE TABLE survey_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seminar_id INT NULL COMMENT 'NULL=全セミナー共通',
    survey_type ENUM('registration', 'post_seminar') NOT NULL COMMENT '申込時/セミナー後',
    question_text TEXT NOT NULL COMMENT '質問文',
    question_type ENUM('text', 'radio', 'checkbox') NOT NULL COMMENT '回答形式',
    options JSON COMMENT '選択肢（radio/checkbox用）',
    is_required TINYINT(1) DEFAULT 0 COMMENT '必須回答',
    order_index INT DEFAULT 0 COMMENT '表示順',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seminar_id) REFERENCES seminars(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `survey_answers` - アンケート回答
```sql
CREATE TABLE survey_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attendee_id INT NOT NULL,
    question_id INT NOT NULL,
    answer_text TEXT COMMENT '回答内容',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (attendee_id) REFERENCES attendees(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES survey_questions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### `email_logs` - メール送信履歴
```sql
CREATE TABLE email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    attendee_id INT NOT NULL,
    email_type ENUM('registration', 'reminder', 'thanks', 'individual') NOT NULL COMMENT 'メール種別',
    subject VARCHAR(255) COMMENT '件名',
    body TEXT COMMENT '本文',
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (attendee_id) REFERENCES attendees(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🔄 システムフロー

### 参加者側フロー

```
1. 申込フォーム
   ↓ 個人情報入力
   ↓ 申込時アンケート回答
   ↓
2. DB登録（status: applied）
   ↓
3. 申込完了メール送信（欠席リンク付き）
   ↓
4. リマインドメール（前日に自動送信）
   ↓
5. 支払いページ
   ↓ Square決済
   ↓
6. DB更新（status: paid）
   ↓ Webhook
   ↓
7. セミナー当日
   ↓ QRコードチェックイン
   ↓
8. DB更新（status: attended）
   ↓
9. サンクスメール送信（PDF添付 + アンケートリンク）
   ↓
10. セミナー後アンケート回答
```

### 欠席フロー

```
1. 申込完了メールの欠席リンクをクリック
   ↓
2. 欠席フォーム（理由入力）
   ↓
3. DB更新（status: absent, credit_amount: セミナー価格）
   ↓
4. 次回申込時に繰越クレジット表示
```

---

## 🎨 画面設計

### 参加者画面

#### 1. 申込フォーム (`/public/index.php`)
- セミナー選択
- 個人情報入力（名前、メール、電話）
- 申込時アンケート
- 申込ボタン

#### 2. 申込完了ページ (`/public/thank-you.php`)
- 申込完了メッセージ
- セミナー情報表示
- 欠席リンク

#### 3. 支払いページ (`/public/payment.php`)
- 申込者リストから選択
- 繰越クレジット表示（ある場合）
- Square決済ボタン

#### 4. 欠席フォーム (`/public/cancel.php`)
- 欠席理由入力
- 送信ボタン

#### 5. セミナー後アンケート (`/public/survey.php`)
- アンケート質問表示
- 回答送信

#### 6. QRコードチェックイン (`/public/checkin.php`)
- QRコード表示（申込完了メール内のリンク）

---

### 管理画面

#### 1. ダッシュボード (`/public/admin/index.php`)
- 今後のセミナー一覧
- 統計サマリー（申込数、支払済数、参加済数）

#### 2. セミナー管理 (`/public/admin/seminars.php`)
- セミナー一覧
- 新規作成・編集・削除
- 入力項目：
  - セミナー名
  - 説明
  - **開催場所**
  - 開始日時
  - 終了日時
  - 申込締切日時
  - 価格
  - PDF添付
  - サンクスメール件名・本文
  - メール送信者名

#### 3. 参加者管理 (`/public/admin/attendees.php`)
- セミナー別参加者一覧
- ステータスフィルター
- 手動ステータス変更
- 個別メール送信ボタン
- CSVエクスポート

#### 4. アンケート管理 (`/public/admin/surveys.php`)
- 質問一覧（申込時/セミナー後別）
- 質問追加・編集・削除
- 回答結果表示

#### 5. QRチェックイン画面 (`/public/admin/checkin-scan.php`)
- QRコードスキャン（カメラ使用）
- 出席確認

#### 6. メール設定 (`/public/admin/emails.php`)
- 申込完了メールテンプレート編集
- リマインドメールテンプレート編集
- SMTP設定

---

## 🛠️ 技術スタック

### バックエンド
- **PHP 8.1**（Xserver対応）
- MySQL 8.0
- Composer
- PHPMailer（メール送信）

### フロントエンド
- HTML5 + CSS3（無印良品スタイル）
- Noto Sans JP フォント
- JavaScript（バニラJS）
- QRコード生成: qrcode.js

### 外部API
- Square Payment Links API
- Square Webhooks

### サーバー
- Xserver
- Cron（自動メール送信用）

---

## 📁 ディレクトリ構造

```
seminar-system/
├── public/
│   ├── index.php                # 申込フォーム
│   ├── payment.php              # 支払いページ
│   ├── cancel.php               # 欠席フォーム
│   ├── survey.php               # セミナー後アンケート
│   ├── checkin.php              # QRコード表示
│   ├── thank-you.php            # 申込完了ページ
│   ├── webhook.php              # Square Webhook
│   ├── admin/
│   │   ├── index.php            # ダッシュボード
│   │   ├── seminars.php         # セミナー管理
│   │   ├── attendees.php        # 参加者管理
│   │   ├── surveys.php          # アンケート管理
│   │   ├── checkin-scan.php     # QRチェックイン
│   │   ├── emails.php           # メール設定
│   │   └── assets/
│   │       └── css/
│   │           └── admin.css
│   └── assets/
│       ├── css/
│       │   └── style.css
│       └── js/
│           ├── app.js
│           └── qrcode.min.js
├── src/
│   ├── Seminar.php              # セミナー管理クラス
│   ├── Attendee.php             # 参加者管理クラス
│   ├── Survey.php               # アンケート管理クラス
│   ├── MailSender.php           # メール送信クラス
│   ├── SquareClient.php         # Square API クライアント
│   ├── QRCodeGenerator.php      # QRコード生成
│   ├── Database.php             # DB接続
│   ├── Logger.php               # ログ管理
│   └── helpers.php              # ヘルパー関数
├── config/
│   └── config.php               # 設定ファイル
├── database/
│   ├── schema.sql               # テーブル定義
│   └── seeds.sql                # 初期データ
├── uploads/
│   └── seminars/                # PDF保存先
│       └── {seminar_id}/
│           └── slide.pdf
├── logs/
│   ├── app.log
│   └── email.log
├── cron/
│   ├── send-reminder-mail.php   # リマインドメール送信
│   └── send-thanks-mail.php     # サンクスメール送信
├── vendor/                      # Composer依存関係
├── .env                         # 環境変数
├── .htaccess                    # Xserver用設定
├── .gitignore
├── composer.json
├── composer.lock
├── README.md
├── SPECIFICATION.md             # 本仕様書
└── WORK_LOG.md                  # 作業進捗ログ
```

---

## ⚙️ 環境変数 (.env)

```env
# Square API
SQUARE_ACCESS_TOKEN=
SQUARE_APPLICATION_ID=
SQUARE_LOCATION_ID=
SQUARE_WEBHOOK_SIGNATURE_KEY=
SQUARE_ENVIRONMENT=production

# Database
DB_HOST=localhost
DB_NAME=xs545151_seminar
DB_USER=xs545151_seminar
DB_PASSWORD=
DB_CHARSET=utf8mb4

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yojitu.com/seminar-system

# Mail (Xserver SMTP)
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME=

# Logging
LOG_LEVEL=info
```

---

## 🔧 Xserver設定

### .htaccess（ルートディレクトリ）

```apache
# PHP 8.1を使用
AddHandler application/x-httpd-php81 .php

# エラーページ
ErrorDocument 404 /seminar-system/public/404.php
ErrorDocument 500 /seminar-system/public/500.php

# セキュリティ設定
<Files ".env">
    Order allow,deny
    Deny from all
</Files>

# uploads/へのダイレクトアクセス制限（PDF以外）
<Directory "uploads/">
    Options -Indexes
    <FilesMatch "\.pdf$">
        Order allow,deny
        Allow from all
    </FilesMatch>
</Directory>

# logs/へのアクセス禁止
<Directory "logs/">
    Order allow,deny
    Deny from all
</Directory>

# Composerファイルへのアクセス禁止
<FilesMatch "^(composer\.(json|lock)|\.git)">
    Order allow,deny
    Deny from all
</FilesMatch>

# HTTPS強制（本番環境）
# RewriteEngine On
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### ファイルパーミッション設定

```bash
# ディレクトリ: 705
chmod 705 seminar-system/
chmod 705 seminar-system/public/
chmod 705 seminar-system/uploads/
chmod 705 seminar-system/logs/

# PHPファイル: 604
find seminar-system/ -type f -name "*.php" -exec chmod 604 {} \;

# 書き込み可能ディレクトリ: 707
chmod 707 seminar-system/uploads/seminars/
chmod 707 seminar-system/logs/

# .env: 600（重要）
chmod 600 seminar-system/.env
```

---

## 🚀 実装フェーズ

### フェーズ1: 基盤構築（1週間）
- [ ] ディレクトリ構造作成
- [ ] データベーステーブル作成
- [ ] 基本クラス実装（Database, Logger, helpers）
- [ ] Square APIクライアント実装

### フェーズ2: セミナー・参加者管理（1週間）
- [ ] セミナー管理画面
- [ ] 申込フォーム
- [ ] 参加者管理画面
- [ ] 支払いページ（繰越クレジット対応）

### フェーズ3: アンケート機能（1週間）
- [ ] アンケート管理画面
- [ ] 申込時アンケート
- [ ] セミナー後アンケート
- [ ] 回答結果表示

### フェーズ4: メール機能（1週間）
- [ ] PHPMailer導入
- [ ] 申込完了メール送信
- [ ] リマインドメール（Cron）
- [ ] サンクスメール（Cron + PDF添付）
- [ ] 個別メール送信機能

### フェーズ5: QRコード・欠席機能（1週間）
- [ ] QRコード生成・表示
- [ ] QRスキャンチェックイン
- [ ] 欠席フォーム
- [ ] 繰越クレジット管理

### フェーズ6: テスト・本番移行（1週間）
- [ ] 総合テスト
- [ ] セキュリティチェック
- [ ] 本番環境デプロイ
- [ ] マニュアル作成

**総開発期間: 約6週間**

---

## 🔒 セキュリティ要件

### 認証・認可
- 管理画面: Basic認証またはログイン機能
- 欠席リンク: トークン認証（64文字ランダム）
- QRコード: トークン認証（64文字ランダム）

### データ保護
- SQLインジェクション対策: PDOプリペアドステートメント
- XSS対策: htmlspecialchars()
- CSRF対策: トークン検証
- ファイルアップロード: 拡張子・MIMEタイプチェック

### Webhook
- Square署名検証（HMAC-SHA256）

---

## 📊 運用・保守

### Cronジョブ設定

```bash
# リマインドメール（毎日1回実行）
0 9 * * * php /path/to/seminar-system/cron/send-reminder-mail.php

# サンクスメール（10分ごと）
*/10 * * * * php /path/to/seminar-system/cron/send-thanks-mail.php
```

### ログ管理
- アプリケーションログ: `logs/app.log`
- メール送信ログ: `logs/email.log`
- エラーログ: `logs/error.log`

### バックアップ
- データベース: 毎日自動バックアップ（Xserver機能）
- アップロードファイル: 定期的にローカルバックアップ

---

## 📝 備考

### 他システムとの関係
- **完全分離**: 別ディレクトリで独立管理
- **データベース**: 専用DB使用（`xs545151_seminar`）

### 将来的な拡張性
- 複数セミナー同時申込機能
- クーポン・割引コード機能
- オンラインセミナー対応（Zoom連携）
- 参加証明書PDF自動発行

---

**作成日**: 2025年12月16日
**最終更新**: 2025年12月16日
**バージョン**: 1.0.0
