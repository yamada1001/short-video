# BNI定例会費集金システム

**BNI Payment System** - Square APIを使用したオンライン会費集金システム

---

## 📌 概要

BNI定例会（毎週火曜朝6時開催）の会費1,000円をオンラインで集金するシステムです。
メンバーが自分の名前を選択し、Square決済を通じて支払いを完了できます。

### 主要機能
- **メンバー用支払いページ**: 名前選択 → Square決済リンク生成
- **管理画面**: メンバー管理、支払い状況確認、CSVエクスポート
- **Webhook**: Square決済完了通知を自動受信、DB記録

---

## 🛠️ 技術スタック

- **言語**: PHP 8.x
- **データベース**: MySQL 5.7+
- **決済API**: Square Payment Links API
- **フロントエンド**: HTML/CSS（シンプル・レスポンシブ）
- **サーバー**: Xserver

### パッケージ（Composer）
- `square/square`: Square API公式SDK
- `vlucas/phpdotenv`: 環境変数管理
- `monolog/monolog`: ログ記録

---

## 📁 ディレクトリ構成

```
bni-payment-system/
├── public/              # Document Root
│   ├── index.php        # メンバー用支払いページ
│   ├── admin/           # 管理画面（Basic認証）
│   ├── webhook.php      # Webhook受信
│   └── assets/          # CSS/JS/画像
├── src/                 # アプリケーションロジック
├── config/              # 設定ファイル
├── database/            # DBスキーマ・シード
├── logs/                # ログファイル
└── vendor/              # Composer依存パッケージ
```

詳細は [DIRECTORY_STRUCTURE.md](./DIRECTORY_STRUCTURE.md) を参照。

---

## 🚀 クイックスタート

### 1. インストール

```bash
# リポジトリクローン
git clone https://github.com/your-repo/bni-payment-system.git
cd bni-payment-system

# Composer依存インストール
composer install

# 環境変数設定
cp .env.example .env
# .envを編集（DB情報、Square API情報）

# データベース作成
mysql -u root -p -e "CREATE DATABASE bni_payment CHARACTER SET utf8mb4;"
mysql -u root -p bni_payment < database/schema.sql
mysql -u root -p bni_payment < database/seeds.sql
```

### 2. 開発サーバー起動

```bash
# ビルトインサーバー
php -S localhost:8000 -t public
```

ブラウザで `http://localhost:8000` にアクセス。

### 3. Square設定

1. Square Developerアカウント作成
2. アプリケーション作成
3. Access Token、Location ID取得
4. `.env` に設定

---

## 📖 ドキュメント

- **[DEVELOPMENT_PLAN.md](./DEVELOPMENT_PLAN.md)**: 開発計画・実装仕様
- **[IMPLEMENTATION_CHECKLIST.md](./IMPLEMENTATION_CHECKLIST.md)**: 実装チェックリスト
- **[DIRECTORY_STRUCTURE.md](./DIRECTORY_STRUCTURE.md)**: ディレクトリ構成詳細

---

## 🔐 セキュリティ

### 環境変数
`.env` ファイルには機密情報が含まれます。**絶対に公開しないでください。**

### 管理画面認証
`public/admin/` は Basic認証で保護されています。
```bash
# パスワード設定
htpasswd -c public/admin/.htpasswd admin
```

### Webhook署名検証
Square Webhookの署名検証を実装済み。不正なリクエストは拒否されます。

---

## 💾 データベース

### members テーブル
| カラム | 型 | 説明 |
|--------|---|------|
| id | INT | 主キー |
| name | VARCHAR(100) | メンバー名 |
| email | VARCHAR(255) | メールアドレス |
| active | TINYINT(1) | アクティブフラグ |
| created_at | DATETIME | 作成日時 |
| updated_at | DATETIME | 更新日時 |

### payments テーブル
| カラム | 型 | 説明 |
|--------|---|------|
| id | INT | 主キー |
| member_id | INT | メンバーID（外部キー） |
| amount | INT | 金額 |
| week_of | DATE | その週の火曜日 |
| square_payment_id | VARCHAR(255) | Square決済ID |
| paid_at | DATETIME | 支払い日時 |
| created_at | DATETIME | 作成日時 |

---

## 🧪 テスト

### Sandbox環境
Square Sandboxを使用してテスト決済を実行できます。

```env
# .env
SQUARE_ENVIRONMENT=sandbox
```

### テストカード番号
- カード番号: `4111 1111 1111 1111`
- 有効期限: 任意の未来の日付
- CVV: 任意の3桁

---

## 📦 デプロイ（Xserver）

### 1. ファイルアップロード
FTPで全ファイルをアップロード。
ドキュメントルートを `public/` に設定。

### 2. Composer依存インストール
```bash
ssh username@your-server.com
cd ~/bni-payment-system
composer install --no-dev --optimize-autoloader
```

### 3. 権限設定
```bash
chmod 755 public/
chmod 700 config/ src/ database/
chmod 600 .env
chmod 777 logs/
```

### 4. 本番DB作成
```bash
mysql -u user -p -e "CREATE DATABASE bni_payment CHARACTER SET utf8mb4;"
mysql -u user -p bni_payment < database/schema.sql
```

### 5. Square Webhook設定
Square Dashboardで以下のURLを設定：
```
https://yourdomain.com/bni-payment-system/webhook.php
```

---

## 🐛 トラブルシューティング

### Composer install失敗
```bash
# PHPバージョン確認
php -v

# 最新版Composer使用
curl -sS https://getcomposer.org/installer | php
php composer.phar install
```

### DB接続エラー
1. `.env` のDB設定を確認
2. MySQLサービス起動確認
3. ユーザー権限確認

### Webhook受信失敗
1. `logs/webhook.log` 確認
2. Square Dashboardのログ確認
3. 署名検証キー確認

---

## 📝 ライセンス

MIT License

---

## 👨‍💻 開発者

**余日（Yojitsu）**
- Web: https://yojitu.com
- Email: info@yojitu.com

---

## 📅 更新履歴

- **2025-12-15**: プロジェクト初期作成
