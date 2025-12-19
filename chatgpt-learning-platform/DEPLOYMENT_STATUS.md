# デプロイ状況

## 現在のステータス: 静的版公開中

**公開URL**: https://yojitu.com/chatgpt-learning-platform/

### Phase 1: 静的LP ✅（完了）

**実装内容:**
- `public/index.html` - 静的なランディングページ
- `public/coming-soon.html` - 準備中ページ
- `public/.htaccess` - ディレクトリリスティング無効化
- `index.html` - ルートからのリダイレクト

**特徴:**
- データベース不要
- API不要
- Composer不要
- すぐに表示可能

**表示内容:**
- サービス概要
- 4つのレッスンタイプ紹介
- 料金プラン（無料・プレミアム）
- 特徴説明（6項目）

### Phase 2: データベース構築（次のステップ）

**必要な作業:**
1. Xserverでデータベース作成
2. スキーマをインポート
   ```bash
   mysql -u [user] -p [database] < chatgpt-learning-platform-schema.sql
   ```
3. .envファイル作成（DB接続情報）

### Phase 3: Composer依存パッケージ（その次）

**必要な作業:**
1. SSH接続
   ```bash
   ssh [user]@yojitu.com
   cd /home/yojitu/yojitu.com/public_html/chatgpt-learning-platform
   ```

2. Composerインストール
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

3. 依存パッケージ:
   - google/apiclient
   - phpmailer/phpmailer
   - stripe/stripe-php
   - vlucas/phpdotenv

### Phase 4: 認証システム有効化

**必要な作業:**
1. .envに認証情報を設定
   - Google OAuth設定
   - メール設定（パスワード再発行用）

2. PHPファイルを有効化
   - register.php
   - login.php
   - logout.php
   - dashboard.php

### Phase 5: API機能有効化

**必要な作業:**
1. OpenAI API Key設定
2. Stripe API Key設定
3. Webhook URLをStripeに登録

4. APIファイルを有効化
   - api/chatgpt.php
   - api/quiz.php
   - api/assignment.php
   - api/progress.php
   - api/stripe-webhook.php

## 段階的デプロイ計画

```
Phase 1 [✅完了]
  ↓ 静的LP公開

Phase 2 [⏳次]
  ↓ DB構築

Phase 3 [⏳]
  ↓ Composer

Phase 4 [⏳]
  ↓ 認証有効化

Phase 5 [⏳]
  ↓ API有効化

完全版 [🎯ゴール]
```

## トラブルシューティング

### 403エラーが出る場合
- `.htaccess`で`DirectoryIndex index.html`を確認
- `Options -Indexes`でディレクトリリスティング無効化

### PHPエラーが出る場合
- Composerがインストールされているか確認
- .envファイルが存在するか確認
- データベースに接続できるか確認

## 現在表示されているページ

**トップページ**: 静的なLP（DB不要）
**登録・ログイン**: 準備中ページにリダイレクト
**その他のページ**: まだ無効化
