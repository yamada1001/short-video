# BNI Payment System - 実装チェックリスト

**目的**: 作業落ち時の即座復帰用
**使い方**: 完了したら `[ ]` → `[x]` に変更

---

## 🎯 現在のステータス

**開発フェーズ**: Phase 1（基盤構築）
**完了率**: 10%
**次のタスク**: composer.json作成

---

## ✅ Phase 1: 基盤構築（2-3時間）

### 1-1. プロジェクト初期化
- [x] ディレクトリ構造作成
- [x] DEVELOPMENT_PLAN.md作成
- [x] IMPLEMENTATION_CHECKLIST.md作成（このファイル）
- [x] DIRECTORY_STRUCTURE.md作成
- [ ] README.md作成
- [ ] .gitignore作成
- [ ] Git初期化 `git init`

### 1-2. Composer設定
- [ ] composer.json作成
  - square/square パッケージ
  - vlucas/phpdotenv パッケージ
  - monolog/monolog パッケージ
  - PSR-4 オートロード設定
- [ ] `composer install` 実行
- [ ] vendor/ が生成されることを確認

### 1-3. 環境変数
- [ ] .env.example作成
  - SQUARE_ACCESS_TOKEN
  - SQUARE_LOCATION_ID
  - SQUARE_ENVIRONMENT
  - SQUARE_WEBHOOK_SIGNATURE_KEY
  - DB設定（HOST, NAME, USER, PASSWORD）
  - APP設定（ENV, DEBUG, URL）
- [ ] .env作成（ローカル開発用）
- [ ] .gitignoreに.env追加

### 1-4. 設定ファイル
- [ ] config/config.php作成
  - Dotenv読み込み
  - タイムゾーン設定
  - エラーハンドリング設定
  - オートロード読み込み
- [ ] config/database.php作成（オプション）

### 1-5. 基本クラス
- [ ] src/Database.php作成
  - Singleton パターン
  - PDO接続
  - エラーハンドリング
- [ ] src/helpers.php作成
  - h() - HTMLエスケープ
  - redirect() - リダイレクト
  - getCurrentWeek() - 今週の火曜日取得
- [ ] src/Logger.php作成
  - Monologラッパー
  - ログレベル設定
  - ファイル出力

---

## ✅ Phase 2: データベース（1-2時間）

### 2-1. スキーマ定義
- [ ] database/schema.sql作成
  - members テーブル
  - payments テーブル
  - インデックス設定
  - 外部キー制約
- [ ] database/seeds.sql作成
  - テストメンバー5人
  - テスト支払いデータ

### 2-2. マイグレーション
- [ ] ローカルDB作成
  ```bash
  mysql -u root -p -e "CREATE DATABASE bni_payment CHARACTER SET utf8mb4;"
  ```
- [ ] スキーマ実行
  ```bash
  mysql -u root -p bni_payment < database/schema.sql
  ```
- [ ] シードデータ実行
  ```bash
  mysql -u root -p bni_payment < database/seeds.sql
  ```
- [ ] データ確認
  ```bash
  mysql -u root -p bni_payment -e "SELECT * FROM members;"
  ```

---

## ✅ Phase 3: モデルクラス（2-3時間）

### 3-1. Member.php
- [ ] クラス作成 `src/Member.php`
- [ ] getAll() - 全メンバー取得
- [ ] getById() - ID指定取得
- [ ] create() - メンバー作成
- [ ] update() - メンバー更新
- [ ] delete() - メンバー削除
- [ ] テスト実行（手動）

### 3-2. Payment.php
- [ ] クラス作成 `src/Payment.php`
- [ ] create() - 支払い記録作成
- [ ] getByWeek() - 週ごと取得
- [ ] exists() - 重複チェック
- [ ] getCurrentWeek() - 今週の火曜日
- [ ] テスト実行（手動）

### 3-3. Validator.php
- [ ] クラス作成 `src/Validator.php`
- [ ] validateEmail() - メール検証
- [ ] validateRequired() - 必須チェック
- [ ] validateLength() - 文字数チェック
- [ ] sanitize() - サニタイズ

---

## ✅ Phase 4: Square API連携（2-3時間）

### 4-1. SquareClient.php
- [ ] クラス作成 `src/SquareClient.php`
- [ ] __construct() - Square SDK初期化
- [ ] createPaymentLink() - Payment Link生成
  - member_id, amount受け取り
  - QuickPay設定
  - メタデータ設定
  - リダイレクトURL設定
- [ ] Sandboxテスト
  - テスト用member_id=1で実行
  - 生成URLにアクセス
  - 決済完了まで確認

### 4-2. WebhookHandler.php
- [ ] クラス作成 `src/WebhookHandler.php`
- [ ] verifySignature() - 署名検証実装
  - HMAC-SHA256検証
- [ ] handle() - Webhookルーティング
- [ ] handlePaymentCreated() - 支払い完了処理
  - payment_note解析
  - 重複チェック
  - Payment::create()呼び出し
- [ ] Sandboxテスト
  - ngrokでローカルトンネル
  - Square DashboardでWebhook設定
  - テスト決済実行

---

## ✅ Phase 5: フロントエンド（2-3時間）

### 5-1. メンバー用ページ
- [ ] public/index.php作成
  - メンバー一覧取得
  - フォーム表示
  - POST処理（決済リンク生成）
  - エラーハンドリング
- [ ] public/thank-you.php作成
  - 決済完了後のリダイレクト先
  - 「支払いが完了しました」表示

### 5-2. 管理画面 - 支払い状況
- [ ] public/admin/index.php作成
  - 今週の火曜日取得
  - メンバー一覧取得
  - 支払い状況取得
  - テーブル表示
  - 統計表示（支払い済み/未払い）

### 5-3. 管理画面 - メンバー管理
- [ ] public/admin/members.php作成
  - メンバー一覧表示
  - 追加フォーム
  - 編集フォーム（モーダル or 別ページ）
  - 削除機能（確認ダイアログ）
  - AJAX対応（オプション）

### 5-4. 管理画面 - CSVエクスポート
- [ ] public/admin/export.php作成
  - 週指定でデータ取得
  - CSV生成
  - ヘッダー設定（Content-Type, Content-Disposition）
  - ダウンロード実行

### 5-5. Webhook受信
- [ ] public/webhook.php作成
  - 署名検証
  - WebhookHandler呼び出し
  - ログ記録
  - レスポンス返却（200 or 401/500）

### 5-6. CSS
- [ ] public/assets/css/style.css作成
  - リセットCSS
  - レイアウト（container, grid）
  - フォームスタイル
  - ボタンスタイル
  - テーブルスタイル
  - バッジスタイル（✅ 済、❌ 未）
  - レスポンシブ対応

### 5-7. JavaScript（オプション）
- [ ] public/assets/js/app.js作成
  - フォームバリデーション
  - AJAX送信
  - モーダル制御

---

## ✅ Phase 6: セキュリティ・認証（1時間）

### 6-1. .htaccess設定
- [ ] public/.htaccess作成
  - リライトルール
  - セキュリティヘッダー
  - DirectoryIndex設定
- [ ] public/admin/.htaccess作成
  - Basic認証設定
  - AuthUserFile パス指定

### 6-2. Basic認証
- [ ] public/admin/.htpasswd作成
  ```bash
  htpasswd -c public/admin/.htpasswd admin
  ```
- [ ] .gitignoreに.htpasswd追加

### 6-3. セキュリティチェック
- [ ] SQLインジェクション対策確認
  - プリペアドステートメント使用確認
- [ ] XSS対策確認
  - h()関数使用確認
  - HTMLエスケープ確認
- [ ] CSRF対策（オプション）
  - トークン生成・検証

---

## ✅ Phase 7: テスト・デプロイ（1-2時間）

### 7-1. ローカルテスト
- [ ] メンバー用ページテスト
  - メンバー選択
  - 決済リンク生成
  - Square決済完了
- [ ] Webhookテスト
  - ngrok起動
  - Webhook受信確認
  - DB記録確認
- [ ] 管理画面テスト
  - Basic認証確認
  - 支払い状況表示確認
  - メンバー管理（CRUD）確認
  - CSVエクスポート確認

### 7-2. Xserverデプロイ
- [ ] FTP/SFTP接続確認
- [ ] 全ファイルアップロード
- [ ] SSH接続
  ```bash
  ssh username@your-server.com
  ```
- [ ] Composer依存インストール
  ```bash
  cd ~/bni-payment-system
  composer install --no-dev --optimize-autoloader
  ```
- [ ] 権限設定
  ```bash
  chmod 755 public/
  chmod 700 config/ src/ database/
  chmod 600 .env
  chmod 777 logs/
  ```
- [ ] 本番DB作成
  ```bash
  mysql -u user -p -e "CREATE DATABASE bni_payment CHARACTER SET utf8mb4;"
  mysql -u user -p bni_payment < database/schema.sql
  ```
- [ ] .env本番設定
  - SQUARE_ENVIRONMENT=production
  - 本番DB情報
  - 本番Square API情報

### 7-3. 本番テスト
- [ ] メンバー用ページアクセス確認
- [ ] テスト決済実行（少額）
- [ ] Webhook受信確認
- [ ] 管理画面アクセス確認
- [ ] 全機能動作確認

### 7-4. Square本番設定
- [ ] Square Dashboard
  - Webhook URL設定
    ```
    https://yourdomain.com/bni-payment-system/webhook.php
    ```
  - payment.created イベント有効化
  - Webhook署名キー取得
  - .envに設定

---

## 📝 完了後の確認事項

- [ ] 全ログファイルが正常に出力されているか
- [ ] エラーログに異常がないか
- [ ] 支払いが正しく記録されているか
- [ ] CSVエクスポートが正しく動作するか
- [ ] スマホ表示が正常か
- [ ] Basic認証が機能しているか

---

## 🐛 既知の問題・TODO

### 現時点での問題
- なし

### 将来の機能追加
- [ ] リマインダーメール（月曜夜に未払いメンバーへ）
- [ ] 支払い履歴ページ（メンバーごと）
- [ ] 統計ダッシュボード（月間収支グラフ）
- [ ] QRコード生成（メンバーごと専用QR）
- [ ] メール通知（管理者へ支払い完了通知）

---

## 📌 重要メモ

### Square Sandbox vs Production
- **Sandbox**: テスト環境、実際の決済なし
- **Production**: 本番環境、実際の決済あり
- 環境切り替えは `.env` の `SQUARE_ENVIRONMENT` で制御

### Webhook署名検証
必ず実装すること。検証しないと不正なリクエストを受け付けてしまう。

### 週の火曜日計算
```php
// 今週の火曜日
date('Y-m-d', strtotime('this tuesday'));

// 先週の火曜日
date('Y-m-d', strtotime('last tuesday'));

// 来週の火曜日
date('Y-m-d', strtotime('next tuesday'));
```

### 手数料について
- 会費: 1,000円
- 手数料: 100円
- 合計: 1,100円
- Squareの決済手数料は別途かかる（約3.25%）

---

**最終更新**: 2025-12-15
**次回作業**: composer.json作成から開始
