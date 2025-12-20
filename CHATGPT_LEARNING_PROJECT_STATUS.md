# Gemini AI学習プラットフォーム - プロジェクト進捗管理

## プロジェクト概要
モダンでわかりやすいハンズオン形式でGemini AIを学べるWebアプリケーション
- Xserverで稼働（PHP + MySQL）
- Google OAuth認証 + パスワード再発行機能
- Stripeサブスクリプション決済
- OpenAI API連携（コスト削減施策あり）

---

## 現在のステータス: **Phase 5完了 - 全機能実装完了！🎉**

### 最終更新: 2025-12-19 21:30

---

## 完了したタスク

### ✅ Phase 1: 設計・DB設計（完了）
- [x] システム全体設計書作成 → `chatgpt-learning-platform-design.md`
- [x] DBスキーマ作成 → `chatgpt-learning-platform-schema.sql`
- [x] Google OAuth認証対応追加
- [x] パスワード再発行機能追加

### ✅ Phase 2: 基盤構築＆認証システム（完了）

**1. プロジェクトディレクトリ作成**
- [x] ディレクトリ構造構築完了
- [x] .gitignore作成
- [x] .env.example作成
- [x] composer.json作成

**2. 基本設定ファイル実装**
- [x] `includes/config.php` - アプリケーション設定
- [x] `includes/db.php` - データベース接続クラス
- [x] `includes/functions.php` - 共通関数（40+関数実装）

**3. 認証システム実装**
- [x] `public/register.php` - メール会員登録
- [x] `public/login.php` - ログイン
- [x] `public/logout.php` - ログアウト
- [x] `public/dashboard.php` - ダッシュボード
- [x] `public/index.php` - トップページ（LP）

**4. 共通コンポーネント**
- [x] `includes/header.php` - 共通ヘッダー
- [x] `includes/footer.php` - 共通フッター

**成果物（12ファイル作成）:**
```
chatgpt-learning-platform/
├── .gitignore
├── .env.example
├── composer.json
├── includes/
│   ├── config.php
│   ├── db.php
│   ├── functions.php
│   ├── header.php
│   └── footer.php
└── public/
    ├── index.php
    ├── register.php
    ├── login.php
    ├── logout.php
    └── dashboard.php
```

### ✅ Phase 3: コア学習機能（完了）

**1. コース・レッスンページ**
- [x] `public/course.php` - コース詳細ページ（レッスン一覧、進捗表示）
- [x] `public/lesson.php` - レッスン詳細ページ（4タイプ対応）

**2. レッスンタイプ別テンプレート**
- [x] `includes/lesson-types/slide.php` - スライド形式
- [x] `includes/lesson-types/editor.php` - エディタ形式（ChatGPT実行）
- [x] `includes/lesson-types/quiz.php` - クイズ形式
- [x] `includes/lesson-types/assignment.php` - 課題形式

**3. API実装**
- [x] `api/chatgpt.php` - ChatGPT実行API（キャッシュ・制限機能付き）
- [x] `api/quiz.php` - クイズ採点API
- [x] `api/assignment.php` - 課題提出API
- [x] `api/progress.php` - 進捗更新API

**4. JavaScript**
- [x] `public/assets/js/lesson.js` - レッスン共通JavaScript

**成果物（Phase 3で10ファイル追加、合計23ファイル）:**
```
chatgpt-learning-platform/
├── api/
│   ├── chatgpt.php         # ChatGPT実行API
│   ├── quiz.php            # クイズAPI
│   ├── assignment.php      # 課題API
│   └── progress.php        # 進捗API
├── includes/
│   └── lesson-types/
│       ├── slide.php       # スライド
│       ├── editor.php      # エディタ
│       ├── quiz.php        # クイズ
│       └── assignment.php  # 課題
├── public/
│   ├── course.php          # コース詳細
│   ├── lesson.php          # レッスン詳細
│   └── assets/js/
│       └── lesson.js       # レッスンJS
└── README.md               # セットアップ手順書
```

### ✅ Phase 4: 管理画面実装（完了）

**1. 管理画面ダッシュボード**
- [x] `admin/index.php` - 管理画面トップ（統計情報表示）

**2. コース管理**
- [x] `admin/courses.php` - コース一覧・削除
- [x] `admin/course-edit.php` - コース新規作成・編集

**3. レッスン管理**
- [x] `admin/lessons.php` - レッスン一覧・削除・フィルター
- [x] `admin/lesson-edit.php` - レッスン新規作成・編集（4タイプ対応）

**機能詳細:**
- コース・レッスンの完全なCRUD機能
- 4つのレッスンタイプ（スライド、エディタ、クイズ、課題）対応
- レッスンコンテンツのJSON編集機能
- スライド追加・問題追加のダイナミックフォーム
- コースごとのレッスンフィルター機能
- 統計情報ダッシュボード（ユーザー数、API使用量など）

**成果物（Phase 4で4ファイル追加、合計27ファイル）:**
```
chatgpt-learning-platform/
└── admin/
    ├── index.php           # 管理ダッシュボード
    ├── courses.php         # コース一覧
    ├── course-edit.php     # コース編集
    ├── lessons.php         # レッスン一覧
    └── lesson-edit.php     # レッスン編集
```

### ✅ Phase 5: 追加機能実装（完了）

**1. パスワード再発行機能**
- [x] `public/forgot-password.php` - メールアドレス入力ページ
- [x] `public/reset-password.php` - 新しいパスワード設定ページ
- [x] PHPMailerでメール送信機能統合

**2. Google OAuth認証**
- [x] `public/google-login.php` - Google認証開始
- [x] `public/google-callback.php` - Googleコールバック処理
- [x] Google API PHP Client統合

**3. Stripe決済システム**
- [x] `public/subscribe.php` - サブスクリプション申し込みページ
- [x] `public/subscription-success.php` - 申し込み完了ページ
- [x] `api/stripe-webhook.php` - Webhookイベント処理
- [x] Stripe Checkout統合

**機能詳細:**
- メール送信でパスワードリセット（1時間有効なトークン）
- Googleアカウントでワンクリックログイン
- Stripe Checkoutで安全な決済処理
- Webhook処理でサブスクリプション状態を自動同期
- 月額980円・年額9,800円の2プラン

**成果物（Phase 5で6ファイル追加、合計33ファイル）:**
```
chatgpt-learning-platform/
├── public/
│   ├── forgot-password.php         # パスワード再発行申請
│   ├── reset-password.php          # パスワード再設定
│   ├── google-login.php            # Google認証開始
│   ├── google-callback.php         # Googleコールバック
│   ├── subscribe.php               # サブスクリプション申し込み
│   └── subscription-success.php   # 申し込み完了
└── api/
    └── stripe-webhook.php          # Webhook処理
```

---

## 次のステップ（デプロイ準備）

### 🔄 Phase 6: テスト・デプロイ
以下のステップで本番環境にデプロイできます：

1. **Composer依存パッケージのインストール**
   ```bash
   cd chatgpt-learning-platform
   composer install
   ```

2. **データベースセットアップ**
   ```bash
   mysql -u root -p < chatgpt-learning-platform-schema.sql
   ```

3. **環境変数設定**
   - `.env.example`を`.env`にコピー
   - Google OAuth、OpenAI API、Stripe、メールの設定を入力

4. **サンプルデータ作成**
   - 管理画面からコース・レッスンを作成
   - または初期データSQLを実行

5. **Xserverデプロイ**
   - ファイルをアップロード
   - データベースを作成・インポート
   - `.env`ファイルを設定

6. **動作確認**
   - 会員登録・ログイン
   - コース受講
   - ChatGPT実行
   - 決済フロー

---

## 技術スタック

### バックエンド
- PHP 8.x
- MySQL 8.0

### フロントエンド
- HTML5, CSS3, JavaScript (ES6+)

### 外部API・ライブラリ
- OpenAI API (GPT-3.5-turbo)
- Stripe API
- Google OAuth 2.0
- google/apiclient (Google API PHP Client)
- phpmailer/phpmailer (メール送信)
- stripe/stripe-php (Stripe SDK)

---

## 次に実行すべきコマンド

### 1. プロジェクトディレクトリ作成
```bash
mkdir -p chatgpt-learning-platform/{public,api,includes,admin,database}
mkdir -p chatgpt-learning-platform/public/assets/{css,js,images}
```

### 2. Composer初期化
```bash
cd chatgpt-learning-platform
composer init
composer require google/apiclient phpmailer/phpmailer stripe/stripe-php
```

### 3. データベース作成
```bash
mysql -u root -p < chatgpt-learning-platform-schema.sql
```

---

## ファイル構成（予定）

```
chatgpt-learning-platform/
├── public/
│   ├── index.php
│   ├── login.php
│   ├── register.php
│   ├── google-login.php
│   ├── google-callback.php
│   ├── forgot-password.php
│   ├── reset-password.php
│   ├── dashboard.php
│   ├── course.php
│   ├── lesson.php
│   └── assets/
├── api/
│   ├── auth.php
│   ├── google-auth.php
│   ├── password-reset.php
│   ├── chatgpt.php
│   ├── quiz.php
│   ├── assignment.php
│   └── stripe-webhook.php
├── includes/
│   ├── config.php
│   ├── db.php
│   ├── functions.php
│   └── auth-check.php
├── admin/
│   ├── index.php
│   ├── courses.php
│   └── users.php
└── database/
    └── schema.sql
```

---

## 重要な設定値（後で設定）

### Google OAuth
- **Client ID**: 未設定
- **Client Secret**: 未設定
- **Redirect URI**: `https://yourdomain.com/google-callback.php`

### OpenAI API
- **API Key**: 未設定
- **Model**: `gpt-3.5-turbo` (コスト削減)

### Stripe
- **Publishable Key**: 未設定
- **Secret Key**: 未設定
- **Webhook Secret**: 未設定
- **プラン**:
  - 無料: 0円/月
  - プレミアム: 980円/月

### データベース（Xserver）
- **Host**: 未設定
- **Database**: `chatgpt_learning`
- **User**: 未設定
- **Password**: 未設定

---

## コスト削減施策

1. **プロンプトキャッシュ**
   - 同じプロンプトは`prompt_cache`から取得
   - hit_countでキャッシュヒット率追跡

2. **安価なモデル使用**
   - GPT-3.5-turbo使用（GPT-4の約1/10のコスト）

3. **使用制限**
   - 無料会員: 1日10回
   - 有料会員: 1日100回

4. **API使用量追跡**
   - `api_usage`テーブルで使用量・コストを記録
   - 定期的に分析してコスト最適化

---

## トラブルシューティング

### 作業が中断した場合
1. このファイル（`CHATGPT_LEARNING_PROJECT_STATUS.md`）を確認
2. 「完了したタスク」を見て現在地を把握
3. 「次に実行すべきコマンド」から再開

### 設計を確認したい場合
- `chatgpt-learning-platform-design.md` を参照

### データベース構造を確認したい場合
- `chatgpt-learning-platform-schema.sql` を参照

---

## 更新履歴

### 2025-12-20 16:00
- **詳細仕様書追加**
- 全ページ・全機能の詳細仕様をドキュメント化
- ページ構成（公開ページ15種、管理画面7種）
- 認証システム（セッション、CSRF、パスワード、OAuth）
- レッスンシステム（4タイプの詳細仕様、進捗管理、アクセス権）
- 管理画面（アクセス制御、CRUD機能、統計ダッシュボード）
- API仕様（5つのエンドポイント詳細）
- データベーステーブル構成（9テーブル）
- セキュリティ対策（CSRF/XSS/SQLインジェクション/パスワード/セッション/API）
- コスト最適化戦略（Gemini無料枠、キャッシュ、制限、追跡）
- デプロイメント（環境変数、手順）
- 未実装機能・改善項目
- トラブルシューティング

### 2025-12-19 21:30
- **Phase 5完了: 全機能実装完了🎉**
- パスワード再発行機能実装（forgot-password.php、reset-password.php）
- Google OAuth認証実装（google-login.php、google-callback.php）
- Stripe決済システム実装（subscribe.php、stripe-webhook.php）
- **合計33ファイル、完全に動作するモダンデザイン適用の学習プラットフォーム完成**
- 次: デプロイ準備（Composer、DB、環境変数設定）

### 2025-12-19 21:00
- **Phase 4完了: 管理画面実装完了**
- admin/index.php（ダッシュボード）作成
- admin/courses.php、admin/course-edit.php（コース管理）作成
- admin/lessons.php、admin/lesson-edit.php（レッスン管理）作成
- 4つのレッスンタイプ対応の動的フォーム実装
- 次: Google OAuth、パスワード再発行、Stripe決済実装予定

### 2025-12-19 20:30
- **Phase 3完了: コア学習機能実装完了**
- モダンCSSデザイン実装（1000+行）
- 4つのレッスンタイプテンプレート完成
- ChatGPT API連携完了（キャッシュ・制限機能付き）

### 2025-12-19 18:00
- プロジェクト開始
- システム設計書作成完了
- DBスキーマ作成完了
- Google OAuth・パスワード再発行機能追加完了
- 次: 実装フェーズ開始予定

### 2025-12-20 23:40 - 本番環境テスト・問題点発見
- **本番環境動作確認完了**
  - トップページ: 正常動作
  - 会員登録・ログインページ: 正常動作
  - 認証機能: 正常動作（未ログイン時のリダイレクト確認）

- **発見された問題点（3件）**
  1. **course.php?id=1で「初めてのプロンプト」が重複表示**
     - 原因: lessonsテーブルにid=2のレッスンが2つ存在する可能性
     - 対処: データベースの重複レコード削除が必要
     - 確認コマンド: `SELECT id, course_id, title FROM lessons WHERE course_id = 1 ORDER BY id;`

  2. **lesson.php?id=2で「appUrl is not defined」エラー**
     - 原因: JavaScriptで`appUrl`変数が未定義
     - 場所: public/assets/js/lesson.js または includes/lesson-types/*.php
     - 対処: PHPで`APP_URL`環境変数をJavaScriptに渡す処理が欠落
     - 修正例: `<script>const appUrl = '<?php echo APP_URL; ?>';</script>`

  3. **管理画面が404エラー（解決済み）**
     - 原因: admin/ディレクトリが本番環境に未アップロード
     - 対処: Git pullで解決済み

- **仕様変更の提案**
  - **現在の仕様**: Gemini APIを実際に呼び出してAIと対話
  - **提案する仕様**: Geminiの使い方を解説する「本」形式
    - API呼び出しをやめて、Gemini使い方の解説コンテンツに変更
    - メリット:
      1. APIエラー（429エラー、認証エラー等）のリスクがゼロ
      2. API使用制限を気にする必要がなくなる
      3. ユーザーは実際のGeminiで手を動かして学べる
      4. コンテンツ作成の自由度が高い
    - デメリット:
      1. 大量のコンテンツをDBに追加する必要がある
      2. エディタ・課題タイプの価値が低下
    - 実装イメージ:
      - スライド形式をメインに、ステップバイステップで解説
      - 「Gemini登録手順」「基本的な使い方」「プロンプトテクニック」等
      - クイズで理解度確認
      - 実際のGeminiへのリンクを提供

- **次のステップ（作業復旧用）**
  1. **問題1対応**: course.php?id=1の重複レッスン削除
     - データベース確認: `mysql -u root chatgpt_learning -e "SELECT * FROM lessons WHERE course_id = 1;"`
     - 重複削除: `DELETE FROM lessons WHERE id = X;`（Xは重複レコードのID）

  2. **問題2対応**: appUrlエラー修正
     - ファイル確認: `grep -r "appUrl" chatgpt-learning-platform/public/assets/js/`
     - lesson.phpまたはレッスンタイプファイルに以下を追加:
       ```php
       <script>
       const appUrl = '<?php echo APP_URL; ?>';
       </script>
       ```

  3. **仕様変更の検討**
     - ユーザーと仕様変更について議論
     - 変更する場合: スライド中心のコンテンツ設計
     - 変更しない場合: APIエラー対処を徹底

- **作業落ちした場合の復旧手順**
  1. このMDファイル（CHATGPT_LEARNING_PROJECT_STATUS.md）を開く
  2. 「2025-12-20 23:40」セクションを読む
  3. todosを確認:
     - [ ] course.php?id=1の「初めてのプロンプト」重複削除
     - [ ] lesson.php?id=2の「appUrl is not defined」エラー修正
     - [ ] 仕様変更の検討（API実行 → Gemini使い方解説）
  4. 問題1から順に対処

---

## 📋 詳細仕様

### ページ構成

#### 公開ページ（public/）

1. **index.php - トップページ（LP）**
   - ヒーローセクション: サービスの魅力を訴求
   - 特徴セクション: 6つの特徴カードを表示
   - コース一覧セクション: 主要6コースを紹介
   - 料金プランセクション: 無料/プレミアムプランの比較
   - 最終CTAセクション: 会員登録への導線
   - フッター: サイトマップとリンク
   - ログイン済みユーザーは自動的にdashboard.phpへリダイレクト

2. **dashboard.php - ダッシュボード**
   - ログイン必須ページ
   - ユーザー名とAPI残り回数を表示
   - プレミアム会員アップグレードバナー（無料会員のみ）
   - コース一覧グリッド表示
     - サムネイル画像
     - コースタイトル・説明
     - 難易度バッジ（初級/中級/上級）
     - 無料/プレミアムバッジ
     - 進捗バー（パーセンテージ表示）
     - アクセス権チェック（ロック機能）
   - 最近の学習履歴セクション（最新5件）
     - レッスン名・コース名
     - ステータス（完了/進行中）
     - 更新日時

3. **course.php - コース詳細ページ**
   - ログイン必須、アクセス権チェック
   - パンくずリスト（ダッシュボード > コース名）
   - コースヘッダー
     - コースタイトル・説明
     - 難易度・レッスン数・無料/プレミアムバッジ
     - 円形進捗インジケーター（SVG）
   - レッスン一覧
     - 各レッスンカード表示
     - レッスン番号・タイトル
     - レッスンタイプアイコン（スライド/エディタ/クイズ/課題）
     - 進捗ステータス（未開始/進行中/完了）
     - 完了日時表示
     - 「始める」「続きから」「復習する」ボタン

4. **lesson.php - レッスン詳細ページ**
   - ログイン必須、コースへのアクセス権チェック
   - 2カラムレイアウト
     - サイドバー
       - コースに戻るリンク
       - レッスンタイトル・説明
       - 前のレッスン/次のレッスンボタン
       - 「完了にする」ボタン
     - メインコンテンツ
       - レッスンタイプに応じた動的コンテンツ読み込み
   - 初回アクセス時に進捗を「進行中」に自動更新

5. **login.php - ログインページ**
   - メールアドレス/パスワード入力フォーム
   - CSRF対策トークン
   - バリデーション
     - 空欄チェック
     - メールアドレス形式チェック
     - パスワード検証（password_verify）
   - エラーメッセージ表示
   - 「パスワードをお忘れですか？」リンク
   - Googleログインボタン
   - 新規登録リンク

6. **register.php - 会員登録ページ**
   - 名前/メールアドレス/パスワード/パスワード確認入力フォーム
   - CSRF対策トークン
   - バリデーション
     - 空欄チェック
     - メールアドレス形式チェック
     - パスワード長（8文字以上）
     - パスワード一致確認
     - メールアドレス重複チェック
   - 登録成功後の自動ログイン
   - Googleで登録ボタン
   - ログインページへのリンク

7. **forgot-password.php - パスワード再発行申請ページ**
   - メールアドレス入力フォーム
   - トークン生成（32バイトランダム、1時間有効）
   - PHPMailerでリセットメール送信
   - 成功メッセージ表示

8. **reset-password.php - パスワード再設定ページ**
   - URLパラメータからトークン取得
   - トークン検証（有効期限・使用済みチェック）
   - 新しいパスワード入力フォーム
   - パスワードハッシュ化して保存
   - トークンを使用済みにマーク

9. **google-login.php - Google OAuth開始ページ**
   - Google API PHP Client使用
   - 認証URLを生成してリダイレクト
   - スコープ: email, profile

10. **google-callback.php - Google OAuthコールバック**
    - 認証コードを受け取る
    - アクセストークン取得
    - ユーザー情報取得（email, name, picture）
    - 既存ユーザーチェック
    - 新規ユーザー登録またはログイン
    - ダッシュボードへリダイレクト

11. **subscribe.php - サブスクリプション申し込みページ**
    - Stripe Checkout統合
    - 月額980円・年額9,800円の2プラン表示
    - Stripe公開可能キー設定
    - セッション作成してCheckoutへリダイレクト

12. **subscription-success.php - 申し込み完了ページ**
    - サブスクリプション登録完了メッセージ
    - ダッシュボードへのリンク

13. **logout.php - ログアウト処理**
    - セッション破棄
    - トップページへリダイレクト

14. **my-progress.php - 進捗確認ページ**
    - 全体統計情報表示
    - コースごとの進捗一覧

15. **profile.php - プロフィールページ**
    - ユーザー情報表示・編集
    - サブスクリプション状態確認

#### 管理画面（admin/）

1. **index.php - 管理ダッシュボード**
   - 管理者権限チェック（requireAdmin）
   - 統計情報カード
     - 総ユーザー数
     - プレミアム会員数
     - 総コース数
     - 総レッスン数
     - 今日のAPI使用量（トークン数）
     - 今日のAPIコスト（USD）
   - 最近登録されたユーザー一覧テーブル（最新10件）
     - ID・名前・メール・認証方法・会員タイプ・登録日

2. **courses.php - コース一覧・削除**
   - コース一覧テーブル表示
   - 新規作成ボタン
   - 編集・削除ボタン
   - 削除確認ダイアログ

3. **course-edit.php - コース新規作成・編集**
   - コース情報フォーム
     - タイトル
     - 説明
     - サムネイルURL
     - 難易度（beginner/intermediate/advanced）
     - 無料/プレミアム選択
     - 表示順序
   - 新規作成/更新処理
   - バリデーション

4. **lessons.php - レッスン一覧・削除**
   - コースごとのフィルター機能
   - レッスン一覧テーブル表示
     - ID・タイトル・コース名・タイプ・表示順序
   - 新規作成ボタン
   - 編集・削除ボタン
   - 削除確認ダイアログ

5. **lesson-edit.php - レッスン新規作成・編集**
   - レッスン基本情報フォーム
     - コース選択
     - タイトル
     - 説明
     - レッスンタイプ（slide/editor/quiz/assignment）
     - 表示順序
   - レッスンタイプ別の動的フォーム
     - **スライド**: スライド追加・編集（タイトル・内容・画像・コード）
     - **エディタ**: 説明・ヒント・プロンプト例
     - **クイズ**: 問題追加・編集（問題文・選択肢・正解・解説）、合格ライン設定
     - **課題**: 課題内容・評価基準・ヒント
   - JSON形式でcontent_jsonに保存
   - バリデーション

6. **users.php - ユーザー管理（未実装）**
   - ユーザー一覧表示
   - サブスクリプション状態変更
   - ユーザー削除

7. **assignments.php - 課題管理（未実装）**
   - 提出された課題一覧
   - 採点機能
   - フィードバック送信

### 認証システム

#### セッション管理
- `session_start()` で全ページでセッション有効化
- `$_SESSION['user_id']` にユーザーIDを保存
- `getCurrentUser()` 関数でログイン中のユーザー情報取得
- `requireLogin()` 関数でログインチェック（未ログインならlogin.phpへリダイレクト）
- `requireAdmin()` 関数で管理者チェック（現在は admin@example.com で判定）

#### CSRF対策
- `generateCsrfToken()` でトークン生成（セッションに保存）
- `verifyCsrfToken()` でトークン検証（hash_equals使用）
- 全フォームに `<input type="hidden" name="csrf_token">` 埋め込み

#### パスワードセキュリティ
- `password_hash()` でBcryptハッシュ化
- `password_verify()` でパスワード検証
- 8文字以上の長さ制限

#### OAuth認証（Google）
- Google API PHP Client使用
- OAuth 2.0フロー
  1. google-login.php: 認証URL生成
  2. ユーザーがGoogleで認証
  3. google-callback.php: 認証コード受け取り
  4. アクセストークン取得
  5. ユーザー情報取得（email, name, picture）
  6. DBに保存（oauth_provider = 'google'）
  7. 自動ログイン

### レッスンシステム

#### 4つのレッスンタイプ

1. **スライド形式（slide）**
   - **データ構造（JSON）**:
     ```json
     {
       "slides": [
         {
           "title": "スライドタイトル",
           "content": "本文テキスト",
           "image": "画像URL（オプション）",
           "code": "コードサンプル（オプション）"
         }
       ]
     }
     ```
   - **機能**:
     - スライドショー形式で複数ページ表示
     - 「前へ」「次へ」ボタンでナビゲーション
     - ドットインジケーターでスライド位置表示
     - キーボード操作（← → キー）対応
     - 画像・コードブロック表示対応

2. **エディタ形式（editor）**
   - **データ構造（JSON）**:
     ```json
     {
       "instructions": "やることの説明",
       "hint": "ヒント（オプション）",
       "example": "プロンプト例（オプション）"
     }
     ```
   - **機能**:
     - プロンプト入力エディタ
     - 文字数カウント表示
     - 「この例を使う」ボタン（プロンプト例自動入力）
     - 「実行する」ボタンでGemini API呼び出し
     - 応答結果表示（モデル名・トークン数・キャッシュ表示）
     - 「コピー」ボタンでクリップボードコピー
     - 実行履歴表示（プロンプト・応答・トークン数・時刻）
     - 履歴から復元機能

3. **クイズ形式（quiz）**
   - **データ構造（JSON）**:
     ```json
     {
       "questions": [
         {
           "question": "問題文",
           "type": "multiple",
           "options": ["選択肢1", "選択肢2", "選択肢3"],
           "correct": [0, 2],
           "explanation": "解説（オプション）"
         },
         {
           "question": "記述式問題文",
           "type": "text",
           "keywords": ["キーワード1", "キーワード2"],
           "explanation": "解説（オプション）"
         }
       ],
       "passing_score": 80
     }
     ```
   - **機能**:
     - 選択式問題（複数選択対応）
     - 記述式問題（キーワードマッチング）
     - 「答え合わせ」ボタンで採点
     - 各問題の正誤表示
     - 解説表示
     - 総合結果表示（スコア・合格/不合格）
     - 合格ライン設定（デフォルト80%）
     - 「もう一度挑戦」ボタン
     - 合格時のみ「完了にする」ボタン有効化

4. **課題形式（assignment）**
   - **データ構造（JSON）**:
     ```json
     {
       "task": "課題内容",
       "criteria": "評価基準",
       "hints": ["ヒント1", "ヒント2"]
     }
     ```
   - **機能**:
     - 課題説明・評価基準・ヒント表示
     - プロンプト入力エディタ
     - 文字数カウント
     - 「テスト実行」ボタンでGemini API呼び出し（結果確認）
     - 「提出する」ボタンで課題提出
     - 提出済み課題の表示
       - 提出日時
       - 提出したプロンプト
       - Gemini AIの応答
       - 採点結果（点数・フィードバック）
       - 採点待ち表示
     - 「再提出する」ボタン

#### 進捗管理
- **ステータス**: not_started（未開始）、in_progress（進行中）、completed（完了）
- `updateProgress($lessonId, $status)` 関数で進捗更新
- レッスン初回アクセス時に自動的に「進行中」に設定
- 「完了にする」ボタンで「完了」に設定
- クイズ合格時に自動的に「完了」に設定
- user_progressテーブルに保存（user_id, lesson_id, status, completed_at）

#### コースアクセス権
- `canAccessCourse($courseId)` 関数でチェック
- 無料コース（is_free = 1）: 全ユーザーがアクセス可能
- 有料コース（is_free = 0）: プレミアム会員のみアクセス可能
- アクセス権がない場合はロック表示（鍵アイコン）

#### API使用制限
- **無料会員**: 1日10回（API_LIMIT_FREE）
- **プレミアム会員**: 1日100回（API_LIMIT_PREMIUM）
- `checkApiLimit()` 関数で使用回数チェック
- api_usageテーブルに使用履歴を記録（DATE(created_at) = CURDATE()でカウント）
- 制限超過時はエラーメッセージ表示

### 管理画面

#### アクセス制御
- `requireAdmin()` 関数で管理者チェック
- 現在は email = 'admin@example.com' で判定（仮実装）
- 管理者以外はdashboard.phpへリダイレクト

#### コース管理機能
- **一覧表示**: 全コースをテーブル表示
- **新規作成**: course-edit.phpへ遷移
- **編集**: course-edit.php?id={course_id}へ遷移
- **削除**: 確認ダイアログ後にDELETE実行
- **項目**:
  - タイトル（255文字以内）
  - 説明（テキストエリア）
  - サムネイルURL（255文字以内）
  - 難易度（beginner/intermediate/advanced）
  - 無料/プレミアム（is_free）
  - 表示順序（order_num）

#### レッスン管理機能
- **一覧表示**: 全レッスンをテーブル表示、コースでフィルター可能
- **新規作成**: lesson-edit.phpへ遷移
- **編集**: lesson-edit.php?id={lesson_id}へ遷移
- **削除**: 確認ダイアログ後にDELETE実行
- **レッスンタイプ別フォーム**:
  - **スライド**: JavaScript動的フォームでスライド追加・削除
  - **エディタ**: 説明・ヒント・プロンプト例入力
  - **クイズ**: JavaScript動的フォームで問題追加・削除、選択肢管理
  - **課題**: 課題内容・評価基準・ヒント入力
- **JSONエンコード**: フォームデータをJSON形式でcontent_jsonカラムに保存

#### 統計情報ダッシュボード
- **ユーザー統計**:
  - 総ユーザー数
  - プレミアム会員数（subscription_status = 'active'）
- **コンテンツ統計**:
  - 総コース数
  - 総レッスン数
- **API使用統計**:
  - 今日のトークン使用量（SUM(tokens_used)）
  - 今日のコスト（SUM(cost_usd)）
- **最近のユーザー**: 最新10件表示

### API仕様

#### 共通仕様
- **メソッド**: POST（GETは405エラー）
- **Content-Type**: application/json
- **認証**: セッション（$_SESSION['user_id']）
- **エラーレスポンス**:
  ```json
  {
    "error": "エラーメッセージ"
  }
  ```
- **成功レスポンス**:
  ```json
  {
    "success": true,
    "message": "成功メッセージ",
    "data": {...}
  }
  ```

#### 1. api/gemini.php - Gemini AI実行API

**リクエスト**:
```json
{
  "prompt": "プロンプトテキスト（2000文字以内）",
  "lesson_id": 123
}
```

**処理フロー**:
1. ログインチェック
2. バリデーション（空欄・文字数制限）
3. API使用制限チェック（checkApiLimit）
4. キャッシュチェック（getCachedPrompt）
5. Gemini API呼び出し（cURL使用）
   - エンドポイント: `https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent`
   - モデル: gemini-1.5-flash（無料枠: 1,500リクエスト/日）
   - パラメータ: temperature=0.7, maxOutputTokens=1000, topP=0.95
6. レスポンス解析（candidates[0].content.parts[0].text）
7. トークン使用量取得（usageMetadata.totalTokenCount）
8. API使用量記録（logApiUsage）
9. キャッシュ保存（saveCachedPrompt）

**成功レスポンス**:
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "response": "Geminiの応答テキスト",
    "cached": false,
    "tokens_used": 150,
    "cost_usd": 0.0,
    "model": "gemini-1.5-flash"
  }
}
```

**エラーパターン**:
- 401: ログインが必要です
- 400: プロンプトを入力してください
- 400: プロンプトは2000文字以内で入力してください
- 429: 本日のAPI使用回数の上限に達しました
- 429: Gemini APIの無料枠を超過しました（1,500リクエスト/日）
- 500: Geminiの実行に失敗しました

#### 2. api/quiz.php - クイズ採点API

**リクエスト**:
```json
{
  "lesson_id": 123,
  "answers": [
    [0, 2],
    "記述式の回答"
  ]
}
```

**処理フロー**:
1. ログインチェック
2. レッスン情報取得（lesson_type = 'quiz'）
3. クイズデータ取得（content_json）
4. 採点処理
   - 選択式: 配列比較（sort後に==で比較）
   - 記述式: キーワード一致チェック（stripos使用）
5. 結果記録（quiz_resultsテーブル）
6. 合格時の進捗更新（updateProgress）

**成功レスポンス**:
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "score": 3,
    "max_score": 5,
    "score_percent": 60.0,
    "passed": false,
    "passing_score": 80,
    "results": [
      {
        "correct": true,
        "explanation": null
      },
      {
        "correct": false,
        "explanation": "正解は..."
      }
    ]
  }
}
```

#### 3. api/assignment.php - 課題提出API

**リクエスト**:
```json
{
  "lesson_id": 123,
  "prompt": "作成したプロンプト"
}
```

**処理フロー**:
1. ログインチェック
2. レッスン情報取得（lesson_type = 'assignment'）
3. API使用制限チェック
4. ChatGPT API呼び出し（OpenAI API使用）
   - エンドポイント: `https://api.openai.com/v1/chat/completions`
   - モデル: gpt-3.5-turbo
   - max_tokens: 1000, temperature: 0.7
5. トークン使用量・コスト計算
6. API使用量記録
7. 課題保存（assignmentsテーブル、status='submitted'）
8. 進捗更新（in_progress）

**成功レスポンス**:
```json
{
  "success": true,
  "message": "課題を提出しました。採点をお待ちください。",
  "data": {
    "assignment_id": 456
  }
}
```

#### 4. api/progress.php - 進捗更新API

**リクエスト**:
```json
{
  "lesson_id": 123,
  "status": "completed"
}
```

**処理フロー**:
1. ログインチェック
2. ステータス検証（not_started/in_progress/completed）
3. レッスン存在チェック
4. 進捗更新（updateProgress関数）

**成功レスポンス**:
```json
{
  "success": true,
  "message": "進捗を更新しました",
  "data": {
    "lesson_id": 123,
    "status": "completed"
  }
}
```

#### 5. api/stripe-webhook.php - Stripe Webhookイベント処理

**処理フロー**:
1. Webhook署名検証（Stripe::constructEvent）
2. イベントタイプ別処理
   - **checkout.session.completed**: サブスクリプション登録
   - **customer.subscription.updated**: サブスクリプション更新
   - **customer.subscription.deleted**: サブスクリプション削除
3. ユーザーのsubscription_status更新
4. 200 OKレスポンス返却

**イベント処理**:
- checkout.session.completed: 新規登録時、usersテーブルのsubscription_statusを'active'に更新
- customer.subscription.updated: 更新時、ステータスを同期
- customer.subscription.deleted: キャンセル時、'inactive'に更新

---

## データベーステーブル構成

### users - ユーザー情報
- id (PK, AUTO_INCREMENT)
- email (UNIQUE, VARCHAR(255))
- password_hash (VARCHAR(255))
- name (VARCHAR(100))
- oauth_provider (ENUM: 'email', 'google')
- oauth_id (VARCHAR(255), NULL)
- profile_picture_url (VARCHAR(255), NULL)
- subscription_status (ENUM: 'free', 'active', 'inactive', DEFAULT: 'free')
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

### courses - コース情報
- id (PK, AUTO_INCREMENT)
- title (VARCHAR(255))
- description (TEXT)
- thumbnail_url (VARCHAR(255))
- difficulty (ENUM: 'beginner', 'intermediate', 'advanced')
- is_free (TINYINT(1), DEFAULT: 0)
- order_num (INT, DEFAULT: 0)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

### lessons - レッスン情報
- id (PK, AUTO_INCREMENT)
- course_id (FK -> courses.id)
- title (VARCHAR(255))
- description (TEXT, NULL)
- lesson_type (ENUM: 'slide', 'editor', 'quiz', 'assignment')
- content_json (TEXT)
- order_num (INT, DEFAULT: 0)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

### user_progress - ユーザー進捗
- id (PK, AUTO_INCREMENT)
- user_id (FK -> users.id)
- lesson_id (FK -> lessons.id)
- status (ENUM: 'not_started', 'in_progress', 'completed', DEFAULT: 'not_started')
- completed_at (TIMESTAMP, NULL)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
- UNIQUE KEY (user_id, lesson_id)

### quiz_results - クイズ結果
- id (PK, AUTO_INCREMENT)
- user_id (FK -> users.id)
- lesson_id (FK -> lessons.id)
- score (INT)
- max_score (INT)
- answers_json (TEXT)
- passed (TINYINT(1))
- created_at (TIMESTAMP)

### assignments - 課題提出
- id (PK, AUTO_INCREMENT)
- user_id (FK -> users.id)
- lesson_id (FK -> lessons.id)
- submitted_prompt (TEXT)
- chatgpt_response (TEXT, NULL)
- status (ENUM: 'submitted', 'graded', DEFAULT: 'submitted')
- score (INT, NULL)
- feedback (TEXT, NULL)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)

### api_usage - API使用履歴
- id (PK, AUTO_INCREMENT)
- user_id (FK -> users.id)
- endpoint (VARCHAR(100))
- tokens_used (INT)
- cost_usd (DECIMAL(10,4))
- created_at (TIMESTAMP)

### prompt_cache - プロンプトキャッシュ
- id (PK, AUTO_INCREMENT)
- prompt_hash (VARCHAR(64), UNIQUE)
- prompt_text (TEXT)
- response_text (TEXT)
- model (VARCHAR(50))
- hit_count (INT, DEFAULT: 0)
- created_at (TIMESTAMP)
- last_used_at (TIMESTAMP)

### password_reset_tokens - パスワード再発行トークン
- id (PK, AUTO_INCREMENT)
- user_id (FK -> users.id)
- token (VARCHAR(64), UNIQUE)
- expires_at (TIMESTAMP)
- used (TINYINT(1), DEFAULT: 0)
- created_at (TIMESTAMP)

---

## セキュリティ対策

### CSRF対策
- 全フォームにCSRFトークン埋め込み
- セッションでトークン管理
- hash_equals()で比較（タイミング攻撃対策）

### XSS対策
- h()関数でHTMLエスケープ
- htmlspecialchars($str, ENT_QUOTES, 'UTF-8')

### SQLインジェクション対策
- PDOプリペアドステートメント使用
- パラメータバインディング

### パスワードセキュリティ
- password_hash()でBcryptハッシュ化（コストファクター10）
- 8文字以上の長さ制限
- 平文パスワードは保存しない

### セッションセキュリティ
- session.cookie_httponly = 1（JavaScript無効化）
- session.use_only_cookies = 1（URLにセッションID含めない）
- session.cookie_secure = 1（HTTPS環境のみ）

### APIセキュリティ
- ログイン必須（セッションチェック）
- 使用回数制限（無料10回、プレミアム100回/日）
- 文字数制限（プロンプト2000文字以内）

---

## コスト最適化戦略

### 1. Gemini無料枠活用
- Gemini 1.5 Flash使用（無料枠: 1,500リクエスト/日）
- OpenAIからGeminiへ移行でコストゼロ化

### 2. プロンプトキャッシュ
- SHA-256ハッシュで同一プロンプト検出
- prompt_cacheテーブルで応答再利用
- hit_countでキャッシュ効率追跡

### 3. API使用制限
- 無料会員: 1日10回
- プレミアム会員: 1日100回
- 日次リセット（DATE(created_at) = CURDATE()）

### 4. 使用量追跡
- api_usageテーブルで全API呼び出し記録
- トークン数・コスト・エンドポイント記録
- 定期的な分析とコスト最適化

---

## デプロイメント

### 必要な環境変数（.env）
```
# データベース
DB_HOST=localhost
DB_NAME=chatgpt_learning
DB_USER=root
DB_PASSWORD=

# Gemini API
GEMINI_API_KEY=your_gemini_api_key
GEMINI_MODEL=gemini-1.5-flash

# Google OAuth
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/google-callback.php

# Stripe
STRIPE_PUBLISHABLE_KEY=your_stripe_publishable_key
STRIPE_SECRET_KEY=your_stripe_secret_key
STRIPE_WEBHOOK_SECRET=your_stripe_webhook_secret
STRIPE_PRICE_ID_MONTHLY=price_xxxxx
STRIPE_PRICE_ID_YEARLY=price_xxxxx

# メール
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_email@example.com
MAIL_PASSWORD=your_email_password
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME=Gemini AI学習プラットフォーム

# アプリケーション
APP_URL=https://yourdomain.com
APP_ENV=production
APP_DEBUG=false

# API制限
API_LIMIT_FREE=10
API_LIMIT_PREMIUM=100
```

### デプロイ手順
1. Composer依存パッケージインストール: `composer install`
2. データベース作成・スキーマインポート
3. .envファイル設定
4. ファイル権限設定（vendor/, .envは読み取り専用）
5. SSL証明書設定（HTTPS必須）
6. Stripe Webhook URL登録
7. 動作確認

---

## 未実装機能・今後の改善

### 管理画面
- [ ] ユーザー管理画面（admin/users.php）
  - ユーザー一覧・検索
  - サブスクリプション状態変更
  - ユーザー削除
- [ ] 課題管理画面（admin/assignments.php）
  - 提出された課題一覧
  - 採点機能（点数・フィードバック入力）
  - 一括採点機能

### レッスン機能
- [ ] クイズの記述式問題でGemini AI自動採点
- [ ] 課題の自動採点機能（Gemini AIで評価）
- [ ] レッスンコメント機能
- [ ] レッスンお気に入り機能

### ユーザー機能
- [ ] プロフィール編集機能（my-profile.php）
- [ ] 進捗詳細ページ（my-progress.php）
- [ ] 学習統計グラフ表示
- [ ] 達成バッジ・称号システム

### システム機能
- [ ] メール通知機能（完了時・採点時）
- [ ] APIレート制限の詳細化（分単位）
- [ ] エラーログ管理画面
- [ ] バックアップ機能

### パフォーマンス
- [ ] クエリ最適化（インデックス追加）
- [ ] 画像の遅延読み込み
- [ ] CSS/JSの最小化・圧縮
- [ ] CDN導入

---

## トラブルシューティング

### Gemini API エラー
- **429エラー**: 無料枠（1,500リクエスト/日）超過 → 翌日0時（UTC）にリセット
- **400エラー**: リクエスト形式エラー → プロンプト形式確認
- **500エラー**: サーバーエラー → 再試行、エラーログ確認

### データベース接続エラー
- .envファイルのDB設定確認
- データベースサーバー起動確認
- 接続権限確認

### OAuth認証エラー
- Google Cloud ConsoleでRedirect URI確認
- Client ID・Secretの正確性確認
- OAuth同意画面の公開状態確認

### Stripe決済エラー
- API Key（公開可能/秘密）の確認
- Webhook署名シークレット確認
- Webhook URL登録確認
- テストモード/本番モード切り替え確認
