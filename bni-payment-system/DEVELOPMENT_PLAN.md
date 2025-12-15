# BNI定例会費集金システム - 開発計画書

**プロジェクト名**: BNI Payment System
**作成日**: 2025-12-15
**推定開発時間**: 8-12時間
**技術スタック**: PHP 8.x + MySQL 5.7+ + Square API
**デプロイ先**: Xserver

---

## 📋 プロジェクト概要

### 目的
BNI定例会（毎週火曜朝6時開催）の会費1,000円をオンラインで集金するシステム。
Square Payment Links APIを使用し、メンバーが自分で支払い手続きを完了できる。

### 主要機能
1. **メンバー用支払いページ**: 名前選択 → Square決済
2. **管理画面**: メンバー管理、支払い状況確認、CSVエクスポート
3. **Webhook**: Square決済完了通知を受信、DB記録

---

## 🎯 開発フェーズ

### Phase 1: 基盤構築（2-3時間）
- [x] ディレクトリ構造作成
- [ ] Composer設定（composer.json作成）
- [ ] 環境変数テンプレート（.env.example）
- [ ] .gitignore作成
- [ ] DB接続クラス（src/Database.php）
- [ ] ヘルパー関数（src/helpers.php）
- [ ] ログクラス（src/Logger.php）

### Phase 2: データベース（1-2時間）
- [ ] schema.sql作成（members, paymentsテーブル）
- [ ] seeds.sql作成（テストデータ）
- [ ] マイグレーション実行手順書

### Phase 3: モデルクラス（2-3時間）
- [ ] Member.php（メンバーCRUD）
- [ ] Payment.php（支払いCRUD、週ごと集計）
- [ ] Validator.php（入力検証）

### Phase 4: Square API連携（2-3時間）
- [ ] SquareClient.php（Payment Links生成）
- [ ] WebhookHandler.php（Webhook受信、署名検証）
- [ ] Webhook処理フロー実装

### Phase 5: フロントエンド（2-3時間）
- [ ] メンバー用ページ（public/index.php）
- [ ] 管理画面（public/admin/index.php）
- [ ] メンバー管理画面（public/admin/members.php）
- [ ] 支払い状況画面（public/admin/payments.php）
- [ ] CSVエクスポート（public/admin/export.php）
- [ ] CSS（assets/css/style.css）
- [ ] JS（assets/js/app.js）

### Phase 6: セキュリティ・認証（1時間）
- [ ] .htaccess設定（Basic認証）
- [ ] .htpasswd生成
- [ ] SQLインジェクション対策確認
- [ ] XSS対策確認

### Phase 7: テスト・デプロイ（1-2時間）
- [ ] Sandbox環境でテスト
- [ ] Xserverへアップロード
- [ ] 本番環境設定
- [ ] Square Webhook URL設定
- [ ] 本番テスト

---

## 📦 必要なパッケージ（Composer）

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

## 🗃️ データベース設計

### members テーブル
```sql
CREATE TABLE members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    active TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### payments テーブル
```sql
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

## 🔐 環境変数（.env）

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
LOG_FILE=/home/username/bni-payment-system/logs/app.log
```

---

## 🔧 主要クラスの実装仕様

### src/Database.php
```php
<?php
namespace BNI;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $_ENV['DB_HOST'],
            $_ENV['DB_NAME'],
            $_ENV['DB_CHARSET']
        );

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $options);
        } catch (PDOException $e) {
            Logger::error('Database connection failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->pdo;
    }
}
```

### src/Member.php
```php
<?php
namespace BNI;

class Member {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * 全メンバー取得（アクティブのみ）
     */
    public static function getAll(bool $activeOnly = true): array {
        $db = Database::getInstance()->getConnection();
        $sql = 'SELECT * FROM members';
        if ($activeOnly) {
            $sql .= ' WHERE active = 1';
        }
        $sql .= ' ORDER BY name ASC';

        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * ID指定でメンバー取得
     */
    public static function getById(int $id): ?array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('SELECT * FROM members WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * メンバー作成
     */
    public static function create(array $data): int {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO members (name, email) VALUES (?, ?)'
        );
        $stmt->execute([$data['name'], $data['email']]);
        return (int)$db->lastInsertId();
    }

    /**
     * メンバー更新
     */
    public static function update(int $id, array $data): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'UPDATE members SET name = ?, email = ?, active = ? WHERE id = ?'
        );
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['active'] ?? 1,
            $id
        ]);
    }

    /**
     * メンバー削除
     */
    public static function delete(int $id): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare('DELETE FROM members WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
```

### src/Payment.php
```php
<?php
namespace BNI;

class Payment {
    /**
     * 支払い記録作成
     */
    public static function create(array $data): int {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'INSERT INTO payments (member_id, amount, week_of, square_payment_id, paid_at)
             VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['member_id'],
            $data['amount'],
            $data['week_of'],
            $data['square_payment_id'],
            $data['paid_at']
        ]);
        return (int)$db->lastInsertId();
    }

    /**
     * 週ごとの支払い取得
     */
    public static function getByWeek(string $weekOf): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT p.*, m.name, m.email
             FROM payments p
             JOIN members m ON p.member_id = m.id
             WHERE p.week_of = ?
             ORDER BY p.paid_at ASC'
        );
        $stmt->execute([$weekOf]);
        $results = $stmt->fetchAll();

        // member_id をキーにした配列に変換
        $payments = [];
        foreach ($results as $row) {
            $payments[$row['member_id']] = $row;
        }
        return $payments;
    }

    /**
     * 特定メンバー・週の支払い確認
     */
    public static function exists(int $memberId, string $weekOf): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            'SELECT COUNT(*) FROM payments WHERE member_id = ? AND week_of = ?'
        );
        $stmt->execute([$memberId, $weekOf]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * 今週の火曜日を取得
     */
    public static function getCurrentWeek(): string {
        return date('Y-m-d', strtotime('this tuesday'));
    }
}
```

### src/SquareClient.php
```php
<?php
namespace BNI;

use Square\SquareClient as Square;
use Square\Models\Money;
use Square\Models\CreatePaymentLinkRequest;
use Square\Models\QuickPay;

class SquareClient {
    private $client;

    public function __construct() {
        $this->client = new Square([
            'accessToken' => $_ENV['SQUARE_ACCESS_TOKEN'],
            'environment' => $_ENV['SQUARE_ENVIRONMENT'],
        ]);
    }

    /**
     * Payment Link作成
     */
    public function createPaymentLink(int $memberId, int $amount): object {
        $member = Member::getById($memberId);
        $weekOf = Payment::getCurrentWeek();

        $money = new Money();
        $money->setAmount($amount);
        $money->setCurrency('JPY');

        $quickPay = new QuickPay('BNI定例会費 - ' . $member['name'], $money);

        $request = new CreatePaymentLinkRequest();
        $request->setQuickPay($quickPay);
        $request->setCheckoutOptions([
            'redirect_url' => $_ENV['APP_URL'] . '/public/thank-you.php',
        ]);
        $request->setPaymentNote('member_id:' . $memberId . ',week_of:' . $weekOf);

        $response = $this->client->getCheckoutApi()->createPaymentLink($request);

        if ($response->isSuccess()) {
            return $response->getResult()->getPaymentLink();
        } else {
            throw new \Exception('Payment link creation failed: ' . json_encode($response->getErrors()));
        }
    }
}
```

### src/WebhookHandler.php
```php
<?php
namespace BNI;

class WebhookHandler {
    /**
     * Webhook署名検証
     */
    public function verifySignature(string $signature, string $body): bool {
        $signatureKey = $_ENV['SQUARE_WEBHOOK_SIGNATURE_KEY'];
        $expectedSignature = base64_encode(
            hash_hmac('sha256', $body, $signatureKey, true)
        );
        return hash_equals($signature, $expectedSignature);
    }

    /**
     * Webhook処理
     */
    public function handle(array $payload): void {
        $eventType = $payload['type'] ?? '';

        if ($eventType === 'payment.created') {
            $this->handlePaymentCreated($payload['data']['object']);
        }
    }

    private function handlePaymentCreated(array $payment): void {
        // payment_noteからmember_id, week_ofを抽出
        $note = $payment['note'] ?? '';
        preg_match('/member_id:(\d+),week_of:(\d{4}-\d{2}-\d{2})/', $note, $matches);

        if (!isset($matches[1], $matches[2])) {
            Logger::error('Invalid payment note', ['note' => $note]);
            return;
        }

        $memberId = (int)$matches[1];
        $weekOf = $matches[2];

        // 重複チェック
        if (Payment::exists($memberId, $weekOf)) {
            Logger::info('Payment already exists', ['member_id' => $memberId, 'week_of' => $weekOf]);
            return;
        }

        // 支払い記録作成
        Payment::create([
            'member_id' => $memberId,
            'amount' => $payment['amount_money']['amount'],
            'week_of' => $weekOf,
            'square_payment_id' => $payment['id'],
            'paid_at' => $payment['created_at'],
        ]);

        Logger::info('Payment recorded', ['member_id' => $memberId, 'week_of' => $weekOf]);
    }
}
```

---

## 🎨 フロントエンド実装

### public/index.php（メンバー用ページ）
```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use BNI\Member;
use BNI\SquareClient;

$members = Member::getAll();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $memberId = $_POST['member_id'] ?? null;

    if (!$memberId) {
        $error = 'メンバーを選択してください';
    } else {
        try {
            $squareClient = new SquareClient();
            $paymentLink = $squareClient->createPaymentLink((int)$memberId, 1100);
            header('Location: ' . $paymentLink->getUrl());
            exit;
        } catch (Exception $e) {
            $error = '決済リンクの生成に失敗しました';
            Logger::error('Payment link creation failed', ['error' => $e->getMessage()]);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNI定例会費 オンライン決済</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>BNI定例会費 オンライン決済</h1>

        <?php if ($error): ?>
        <div class="alert alert-error"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="payment-form">
            <div class="form-group">
                <label for="member_id">お名前を選択してください</label>
                <select name="member_id" id="member_id" required>
                    <option value="">選択してください</option>
                    <?php foreach ($members as $member): ?>
                    <option value="<?= $member['id'] ?>"><?= h($member['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="amount-info">
                <p>金額：<strong>1,100円</strong></p>
                <p class="note">（会費1,000円 + 手数料100円）</p>
            </div>

            <button type="submit" class="btn btn-primary">支払いへ進む</button>
        </form>
    </div>
</body>
</html>
```

### public/admin/index.php（管理画面）
```php
<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/config.php';

use BNI\Member;
use BNI\Payment;

$currentWeek = Payment::getCurrentWeek();
$members = Member::getAll();
$payments = Payment::getByWeek($currentWeek);

// 支払い状況の集計
$paidCount = count($payments);
$totalMembers = count($members);
$unpaidCount = $totalMembers - $paidCount;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>BNI支払い管理画面</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>支払い状況 - <?= date('Y年m月d日', strtotime($currentWeek)) ?>週</h1>

        <div class="stats">
            <div class="stat-card">
                <span class="stat-label">支払い済み</span>
                <span class="stat-value paid"><?= $paidCount ?></span>
            </div>
            <div class="stat-card">
                <span class="stat-label">未払い</span>
                <span class="stat-value unpaid"><?= $unpaidCount ?></span>
            </div>
        </div>

        <table class="payment-table">
            <thead>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th>支払い状況</th>
                    <th>支払い日時</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                <?php $paid = isset($payments[$member['id']]); ?>
                <tr class="<?= $paid ? 'paid' : 'unpaid' ?>">
                    <td><?= h($member['name']) ?></td>
                    <td><?= h($member['email']) ?></td>
                    <td>
                        <?php if ($paid): ?>
                        <span class="badge badge-success">✅ 済</span>
                        <?php else: ?>
                        <span class="badge badge-danger">❌ 未</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $paid ? date('m/d H:i', strtotime($payments[$member['id']]['paid_at'])) : '-' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="actions">
            <a href="members.php" class="btn btn-secondary">メンバー管理</a>
            <a href="export.php?week=<?= $currentWeek ?>" class="btn btn-secondary">CSVエクスポート</a>
        </div>
    </div>
</body>
</html>
```

---

## 🚀 デプロイ手順

### 1. ローカル開発環境セットアップ
```bash
cd bni-payment-system
composer install
cp .env.example .env
# .envを編集（DB情報、Square API情報）

# DB作成
mysql -u root -p -e "CREATE DATABASE bni_payment CHARACTER SET utf8mb4;"
mysql -u root -p bni_payment < database/schema.sql
mysql -u root -p bni_payment < database/seeds.sql
```

### 2. Xserverへアップロード
```bash
# FTPまたはSSHで全ファイルアップロード
# ドキュメントルートを public/ に設定
```

### 3. 本番環境設定
```bash
# SSH接続
ssh username@your-server.com
cd ~/bni-payment-system

# Composer依存インストール
composer install --no-dev --optimize-autoloader

# 権限設定
chmod 755 public/
chmod 700 config/ src/ database/
chmod 600 .env
chmod 777 logs/

# Basic認証設定
cd public/admin
htpasswd -c .htpasswd admin
```

### 4. Square Webhook設定
Square Dashboardで以下のURLを設定：
```
https://yourdomain.com/bni-payment-system/webhook.php
```

---

## ✅ 開発チェックリスト

作業完了したら `[x]` にチェックを入れてください。

### Phase 1: 基盤構築
- [x] ディレクトリ構造作成
- [ ] composer.json作成
- [ ] .env.example作成
- [ ] .gitignore作成
- [ ] config/config.php作成
- [ ] src/Database.php作成
- [ ] src/helpers.php作成
- [ ] src/Logger.php作成

### Phase 2: データベース
- [ ] database/schema.sql作成
- [ ] database/seeds.sql作成
- [ ] ローカルDB作成・マイグレーション

### Phase 3: モデルクラス
- [ ] src/Member.php作成
- [ ] src/Payment.php作成
- [ ] src/Validator.php作成

### Phase 4: Square API連携
- [ ] src/SquareClient.php作成
- [ ] src/WebhookHandler.php作成
- [ ] Sandboxテスト

### Phase 5: フロントエンド
- [ ] public/index.php作成
- [ ] public/admin/index.php作成
- [ ] public/admin/members.php作成
- [ ] public/admin/export.php作成
- [ ] assets/css/style.css作成
- [ ] assets/js/app.js作成

### Phase 6: セキュリティ
- [ ] public/.htaccess作成
- [ ] public/admin/.htaccess作成
- [ ] public/admin/.htpasswd作成

### Phase 7: デプロイ
- [ ] Xserverアップロード
- [ ] 本番DB作成
- [ ] Square Webhook設定
- [ ] 本番テスト

---

## 🐛 トラブルシューティング

### Composer install失敗
```bash
# PHPバージョン確認
php -v

# Composer再インストール
curl -sS https://getcomposer.org/installer | php
php composer.phar install
```

### DB接続エラー
```bash
# .envファイル確認
cat .env

# MySQL接続確認
mysql -h localhost -u username -p
```

### Webhook受信失敗
```bash
# ログ確認
tail -f logs/webhook.log

# Square Dashboard確認
# Webhookログでエラー内容確認
```

---

**作成日**: 2025-12-15
**最終更新**: 2025-12-15
**開発者**: Claude Code
