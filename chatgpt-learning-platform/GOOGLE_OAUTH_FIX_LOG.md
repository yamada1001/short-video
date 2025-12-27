# Google OAuth認証エラー修正ログ

**作業開始日時**: 2025-12-21 09:23
**エラー発生日時**: 2025-12-21 09:20頃
**優先度**: 🔴 緊急

---

## 🚨 発生しているエラー

### エラーメッセージ
```
The OAuth client was not found.
このアプリのデベロッパーの場合は、エラーの詳細をご確認ください。
エラー 401: invalid_client
```

### エラー発生箇所
- 会員登録時のGoogleボタンをクリックした際
- `public/google-login.php` → Google OAuth認証画面への遷移時

---

## 🔍 原因分析

### 考えられる原因
1. **Google Cloud ConsoleのOAuthクライアントIDが未作成または削除された**
   - クライアントIDが存在しない
   - クライアントシークレットが一致しない

2. **.envファイルの設定ミス**
   - `GOOGLE_CLIENT_ID` が未設定または間違っている
   - `GOOGLE_CLIENT_SECRET` が未設定または間違っている

3. **リダイレクトURIの不一致**
   - Google Cloud Consoleで許可されたリダイレクトURIが実際のURLと異なる
   - 本番環境: `https://yojitu.com/chatgpt-learning-platform/public/google-callback.php`

4. **OAuth 2.0クライアントの無効化**
   - Google Cloud Consoleで誤って無効化された

---

## 📋 修正手順（詳細版）

### ステップ1: Google Cloud Consoleにアクセス

1. **Google Cloud Consoleを開く**
   - URL: https://console.cloud.google.com/
   - Googleアカウントでログイン

2. **プロジェクトを作成または選択**
   - 画面上部のプロジェクト選択ドロップダウンをクリック
   - 既存プロジェクトがある場合: 選択
   - 新規作成する場合:
     - 「新しいプロジェクト」をクリック
     - プロジェクト名: `Gemini AI Learning Platform`（任意）
     - 「作成」をクリック
     - 作成完了まで数秒待つ

---

### ステップ2: OAuth同意画面の設定

1. **左サイドバーから「APIとサービス」をクリック**

2. **「OAuth同意画面」をクリック**

3. **User Typeを選択**
   - 「外部」を選択（一般ユーザー向け）
   - 「作成」をクリック

4. **アプリ情報を入力**
   - **アプリ名**: `Gemini AI学習プラットフォーム`
   - **ユーザーサポートメール**: あなたのGmailアドレス
   - **デベロッパーの連絡先情報**: 同じGmailアドレス
   - 「保存して次へ」をクリック

5. **スコープ（権限）の設定**
   - 「スコープを追加または削除」をクリック
   - 以下を選択:
     - `email`
     - `profile`
   - 「更新」をクリック
   - 「保存して次へ」をクリック

6. **テストユーザー（任意）**
   - スキップ可能
   - 「保存して次へ」をクリック

7. **概要を確認**
   - 「ダッシュボードに戻る」をクリック

---

### ステップ3: OAuth 2.0 クライアントIDの作成

1. **左サイドバーから「認証情報」をクリック**

2. **「認証情報を作成」をクリック**
   - 「OAuth 2.0 クライアント ID」を選択

3. **アプリケーションの種類を選択**
   - 「ウェブアプリケーション」を選択

4. **名前を入力**
   - 名前: `Gemini AI Learning - Web Client`（任意）

5. **承認済みのリダイレクトURIを追加**
   - 「URIを追加」をクリック
   - 以下の2つを追加:
     ```
     https://yojitu.com/chatgpt-learning-platform/public/google-callback.php
     ```
     ```
     http://localhost/chatgpt-learning-platform/public/google-callback.php
     ```
   - ※ローカル開発用の2つ目は任意

6. **「作成」をクリック**

7. **クライアントIDとシークレットをコピー**
   - ポップアップに表示される「クライアントID」と「クライアントシークレット」をコピー
   - **重要**: この情報は後で確認できますが、今メモしておくと便利です

---

### ステップ4: .envファイルの更新（本番環境）

1. **Xserverファイルマネージャーにアクセス**
   - URL: https://www.xserver.ne.jp/login_file.php
   - ログイン

2. **.envファイルを開く**
   - パス: `/home/xs545151/yojitu.com/public_html/chatgpt-learning-platform/.env`
   - 右クリック → 「編集」

3. **Google OAuth設定を更新**
   ```bash
   GOOGLE_CLIENT_ID=【ステップ3-7でコピーしたクライアントID】
   GOOGLE_CLIENT_SECRET=【ステップ3-7でコピーしたクライアントシークレット】
   GOOGLE_REDIRECT_URI=https://yojitu.com/chatgpt-learning-platform/public/google-callback.php
   ```

4. **保存**
   - 「保存する」をクリック

---

### ステップ5: 動作確認

1. **会員登録ページにアクセス**
   - URL: https://yojitu.com/chatgpt-learning-platform/public/register.php

2. **「Googleで登録」ボタンをクリック**

3. **期待される動作**
   - Google認証画面にリダイレクト
   - アカウント選択画面が表示
   - アカウントを選択
   - 権限の確認（メールアドレスとプロフィール情報へのアクセス）
   - 「許可」をクリック
   - ダッシュボード（`dashboard.php`）にリダイレクト

4. **エラーが発生する場合**
   - エラーメッセージをメモ
   - GOOGLE_OAUTH_FIX_LOG.mdの「トラブルシューティング」セクションを参照

---

### ステップ6: トラブルシューティング

#### エラー: "The OAuth client was not found"
- 原因: クライアントIDが間違っている
- 対処: ステップ4で.envファイルのGOOGLE_CLIENT_IDを再確認

#### エラー: "redirect_uri_mismatch"
- 原因: リダイレクトURIが一致しない
- 対処: Google Cloud Consoleの「認証情報」で設定したURIと.envのGOOGLE_REDIRECT_URIが完全一致するか確認

#### エラー: "Access blocked: This app's request is invalid"
- 原因: OAuth同意画面の設定が不完全
- 対処: ステップ2のOAuth同意画面設定を再確認

---

### ✅ 作業完了チェックリスト

- [ ] Google Cloud Consoleでプロジェクト作成/選択
- [ ] OAuth同意画面の設定完了
- [ ] OAuth 2.0クライアントIDの作成完了
- [ ] クライアントIDとシークレットをコピー
- [ ] 本番環境の.envファイルを更新
- [ ] 会員登録ページで動作確認
- [ ] Google認証が正常に動作することを確認

---

## 📂 関連ファイル

### 設定ファイル
- `.env` - Google OAuth認証情報
- `includes/config.php` - Google Client初期化

### 認証関連ファイル
- `public/google-login.php` - Google OAuth認証開始
- `public/google-callback.php` - コールバック処理

---

## 🚨 処理落ち時の復旧手順

1. このファイル（`GOOGLE_OAUTH_FIX_LOG.md`）を読む
2. 「修正手順」のチェックリストを確認
3. 未完了のステップから再開

---

**最終更新**: 2025-12-21 09:23
**作成者**: Claude Code
**ステータス**: 調査開始
