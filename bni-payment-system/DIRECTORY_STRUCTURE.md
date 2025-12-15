# BNI定例会費集金システム - ディレクトリ構成案

**作成日**: 2025-12-15
**技術スタック**: PHP 8.x + MySQL 5.7+ + Square API
**サーバー**: Xserver

---

## 📁 推奨ディレクトリ構成

```
bni-payment-system/
│
├── public/                          # Document Root（Webからアクセス可能）
│   ├── index.php                    # メンバー用支払いページ
│   ├── admin/                       # 管理画面（.htaccess で認証）
│   │   ├── .htaccess                # Basic認証設定
│   │   ├── .htpasswd                # 認証情報（平文パスワード不可）
│   │   ├── index.php                # 管理画面トップ（メンバー一覧）
│   │   ├── members.php              # メンバー管理（追加・編集・削除）
│   │   ├── payments.php             # 支払い状況一覧
│   │   └── export.php               # CSVエクスポート
│   ├── webhook.php                  # Square Webhook受信
│   ├── assets/                      # 静的ファイル
│   │   ├── css/
│   │   │   └── style.css            # メインスタイルシート
│   │   ├── js/
│   │   │   └── app.js               # メインJS（非同期処理など）
│   │   └── images/
│   │       └── logo.png             # BNIロゴなど
│   └── .htaccess                    # ルーティング・セキュリティ設定
│
├── src/                             # アプリケーションロジック（Webから直接アクセス不可）
│   ├── Database.php                 # DB接続クラス（PDO）
│   ├── Member.php                   # メンバー管理クラス
│   ├── Payment.php                  # 支払い管理クラス
│   ├── SquareClient.php             # Square API クライアント
│   ├── WebhookHandler.php           # Webhook処理クラス
│   ├── Validator.php                # バリデーションクラス
│   ├── Logger.php                   # ログ記録クラス
│   └── helpers.php                  # ヘルパー関数（h(), redirect()など）
│
├── config/                          # 設定ファイル（Webから直接アクセス不可）
│   ├── config.php                   # 全体設定（環境変数読み込み）
│   └── database.php                 # DB設定
│
├── database/                        # DB関連ファイル
│   ├── schema.sql                   # 初期テーブル定義
│   ├── seeds.sql                    # 初期データ（テスト用メンバーなど）
│   └── migrations/                  # マイグレーションファイル（将来の拡張用）
│       └── 001_initial_schema.sql
│
├── logs/                            # ログファイル（.gitignore対象）
│   ├── app.log                      # アプリケーションログ
│   ├── webhook.log                  # Webhookログ
│   └── error.log                    # エラーログ
│
├── vendor/                          # Composer依存パッケージ（.gitignore対象）
│   └── autoload.php
│
├── tests/                           # テストコード（オプション）
│   ├── MemberTest.php
│   └── PaymentTest.php
│
├── .env                             # 環境変数（.gitignore対象）
├── .env.example                     # 環境変数テンプレート（Git管理）
├── .gitignore                       # Git除外設定
├── composer.json                    # Composer設定
├── composer.lock                    # Composer依存バージョン固定
├── README.md                        # プロジェクト説明
└── INSTALL.md                       # インストール手順
```

---

## 🔒 セキュリティ考慮点

### 1. **public/ 以外はWebからアクセス不可**
Xserverのドキュメントルートを `public/` に設定することを推奨。
設定できない場合は、各ディレクトリに `.htaccess` で `Deny from all` を設定。

### 2. **管理画面の認証**
`public/admin/.htaccess` で Basic認証を設定：
```apache
AuthType Basic
AuthName "BNI Admin Area"
AuthUserFile /home/username/bni-payment-system/public/admin/.htpasswd
Require valid-user
```

### 3. **環境変数の保護**
- `.env` は絶対に公開しない
- `.env.example` をテンプレートとして提供
- Xserverのドキュメントルート外に配置推奨

### 4. **Webhook署名検証**
Square APIのWebhook署名を必ず検証（HMAC-SHA256）

### 5. **SQLインジェクション対策**
PDOのプリペアドステートメント使用必須

---

## 📦 composer.json 例

```json
{
    "name": "yojitsu/bni-payment-system",
    "description": "BNI定例会費集金システム",
    "type": "project",
    "require": {
        "php": ">=8.0",
        "square/square": "^30.0",
        "vlucas/phpdotenv": "^5.5",
        "monolog/monolog": "^3.0"
    },
    "autoload": {
        "psr-4": {
            "BNI\\": "src/"
        },
        "files": [
            "src/helpers.php"
        ]
    }
}
```

---

## 📄 主要ファイルの役割

### **public/index.php（メンバー用ページ）**
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use BNI\Member;
use BNI\SquareClient;

// メンバー一覧取得
$members = Member::getAll();

// メンバー選択 → Square決済リンク生成 → リダイレクト
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberId = $_POST['member_id'];
    $squareClient = new SquareClient();
    $paymentLink = $squareClient->createPaymentLink($memberId, 1100);
    redirect($paymentLink->url);
}
?>
<!-- HTML: メンバー選択フォーム -->
```

### **public/webhook.php（Webhook受信）**
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use BNI\WebhookHandler;
use BNI\Logger;

$handler = new WebhookHandler();
$logger = new Logger('webhook');

try {
    // 署名検証
    if (!$handler->verifySignature($_SERVER['HTTP_X_SQUARE_SIGNATURE'])) {
        http_response_code(401);
        exit;
    }

    // Webhook処理
    $payload = json_decode(file_get_contents('php://input'), true);
    $handler->handle($payload);

    $logger->info('Webhook processed', $payload);
    http_response_code(200);
} catch (Exception $e) {
    $logger->error('Webhook error', ['error' => $e->getMessage()]);
    http_response_code(500);
}
```

### **public/admin/index.php（管理画面）**
```php
<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';

use BNI\Member;
use BNI\Payment;

// 週ごとの支払い状況
$currentWeek = date('Y-m-d', strtotime('this tuesday'));
$members = Member::getAll();
$payments = Payment::getByWeek($currentWeek);

// 各メンバーの支払い状況を判定
foreach ($members as &$member) {
    $member['paid'] = isset($payments[$member['id']]);
    $member['paid_at'] = $payments[$member['id']]['paid_at'] ?? null;
}
?>
<!-- HTML: 支払い状況一覧テーブル -->
```

---

## 🗃️ database/schema.sql

```sql
-- Members テーブル
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Payments テーブル
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    amount INT NOT NULL,
    week_of DATE NOT NULL COMMENT 'その週の火曜日の日付',
    square_payment_id VARCHAR(255) UNIQUE,
    paid_at DATETIME NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE,
    INDEX idx_week_of (week_of),
    INDEX idx_member_week (member_id, week_of),
    UNIQUE KEY unique_member_week (member_id, week_of)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 🔧 .env.example

```env
# Square API
SQUARE_ACCESS_TOKEN=your_square_access_token_here
SQUARE_LOCATION_ID=your_square_location_id_here
SQUARE_ENVIRONMENT=sandbox
SQUARE_WEBHOOK_SIGNATURE_KEY=your_webhook_signature_key_here

# Database
DB_HOST=localhost
DB_NAME=bni_payment
DB_USER=your_db_user
DB_PASSWORD=your_db_password
DB_CHARSET=utf8mb4

# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com/bni-payment-system

# Logging
LOG_LEVEL=info
```

---

## 📝 .gitignore

```gitignore
# 環境変数
.env

# Composer
/vendor/

# ログファイル
/logs/*.log
/logs/*.txt
!/logs/.gitkeep

# SQLダンプ
*.sql
!database/schema.sql
!database/seeds.sql
!database/migrations/*.sql

# Basic認証
public/admin/.htpasswd

# IDE
.vscode/
.idea/
*.swp
*.swo

# OS
.DS_Store
Thumbs.db

# Backup
*.bak
*.backup
*~
```

---

## 🚀 インストール手順（INSTALL.md）

```markdown
# インストール手順

## 1. ファイルアップロード
FTPでXserverにアップロード（ドキュメントルートを `public/` に設定）

## 2. Composer依存インストール
ssh username@your-server.com
cd ~/bni-payment-system
composer install

## 3. 環境変数設定
cp .env.example .env
vi .env  # 各変数を設定

## 4. DB作成・マイグレーション
mysql -u user -p -e "CREATE DATABASE bni_payment CHARACTER SET utf8mb4;"
mysql -u user -p bni_payment < database/schema.sql

## 5. Basic認証設定
cd public/admin
htpasswd -c .htpasswd admin  # パスワード入力

## 6. 権限設定
chmod 755 public/
chmod 700 config/ src/ database/
chmod 600 .env
chmod 777 logs/

## 7. Square Webhook URL設定
Square Dashboardで Webhook URL を設定:
https://yourdomain.com/bni-payment-system/webhook.php
```

---

## 🎨 画面イメージ

### メンバー用ページ（public/index.php）
```
┌────────────────────────────────┐
│   BNI定例会費 オンライン決済   │
├────────────────────────────────┤
│ お名前を選択してください：     │
│ ┌──────────────────────┐       │
│ │ ▼ 選択してください   │       │
│ │   山田太郎            │       │
│ │   鈴木花子            │       │
│ │   田中一郎            │       │
│ └──────────────────────┘       │
│                                │
│ 金額：1,100円                  │
│ （会費1,000円 + 手数料100円）  │
│                                │
│  ┌──────────────┐             │
│  │  支払いへ進む  │             │
│  └──────────────┘             │
└────────────────────────────────┘
```

### 管理画面（public/admin/index.php）
```
┌────────────────────────────────────────────┐
│ 支払い状況 - 2025年12月17日週              │
├────────────────────────────────────────────┤
│ 名前      │ 支払い状況 │ 支払い日時        │
├──────────┼───────────┼──────────────────┤
│ 山田太郎  │ ✅ 済      │ 12/17 06:05      │
│ 鈴木花子  │ ❌ 未      │ -                │
│ 田中一郎  │ ✅ 済      │ 12/17 06:12      │
└────────────────────────────────────────────┘
 [CSVエクスポート] [メンバー管理]
```

---

## 💡 追加推奨機能

1. **リマインダーメール**
   - 月曜夜に未払いメンバーへ自動送信

2. **支払い履歴**
   - メンバーごとの過去支払い履歴表示

3. **統計ダッシュボード**
   - 月間収支グラフ
   - 出席率統計

4. **QRコード生成**
   - メンバーごとの専用QRコード
   - スマホで即座に支払い可能

---

**作成日**: 2025-12-15
**推定開発時間**: 8-12時間
