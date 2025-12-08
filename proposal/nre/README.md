# Instagram運用代行サービス 提案資料

このディレクトリには、Instagram運用代行サービスの提案資料が含まれています。

## ファイル構成

- `index.php` - 提案資料のメインページ
- `test.php` - サーバー情報確認用ページ
- `generate_password.php` - ベーシック認証パスワード生成ツール
- `.htaccess` - ベーシック認証設定ファイル
- `.htpasswd` - 認証情報（ユーザー名・パスワード）

## セットアップ手順

### 1. サーバーにアップロード

このディレクトリ（nre）をサーバーにアップロードします。

### 2. サーバー情報の確認

まず、test.phpにアクセスしてサーバー情報を確認します：

```
https://yourdomain.com/proposal/nre/test.php
```

以下の情報を確認してください：
- PHPバージョン
- Apacheが動作しているか
- .htaccessが使用可能か
- ドキュメントルートのパス

### 3. .htaccessファイルの調整

test.phpで確認したドキュメントルートのパスに基づいて、`.htaccess`ファイルの`AuthUserFile`のパスを調整します。

現在の設定：
```apache
AuthUserFile /Users/yamadaren/Movies/claude-code-projects/yojitu.com/proposal/nre/.htpasswd
```

サーバーの実際のパスに変更してください：
```apache
AuthUserFile /home/username/public_html/proposal/nre/.htpasswd
```

### 4. パスワードの設定

#### 方法1: generate_password.phpを使用（推奨）

1. ブラウザで以下にアクセス：
   ```
   https://yourdomain.com/proposal/nre/generate_password.php
   ```

2. ユーザー名とパスワードを入力

3. 生成されたハッシュをコピー

4. `.htpasswd`ファイルを開き、生成されたハッシュを貼り付け

#### 方法2: コマンドラインを使用

サーバーにSSHでログインできる場合：

```bash
cd /path/to/proposal/nre/
htpasswd -c .htpasswd yourusername
```

### 5. セキュリティ設定

#### 重要：デフォルトパスワードの変更

`.htpasswd`ファイルには以下のデフォルト認証情報が設定されています：

- ユーザー名: `admin`
- パスワード: `changeme`

**必ず変更してください！**

#### ファイル権限の設定

```bash
chmod 644 .htpasswd
chmod 644 .htaccess
```

### 6. 動作確認

1. ブラウザで提案資料にアクセス：
   ```
   https://yourdomain.com/proposal/nre/
   ```

2. ベーシック認証ダイアログが表示されることを確認

3. 設定したユーザー名とパスワードでログイン

4. 提案資料が正しく表示されることを確認

## トラブルシューティング

### ベーシック認証が動作しない

1. test.phpでApacheが動作しているか確認
2. .htaccessファイルのAuthUserFileのパスが正しいか確認
3. .htpasswdファイルのパーミッションを確認（644推奨）

### 500エラーが発生する

1. .htaccessファイルの記述にエラーがないか確認
2. サーバーエラーログを確認
3. AuthUserFileのパスが絶対パスになっているか確認

### 認証情報が受け付けられない

1. .htpasswdファイルの形式が正しいか確認
2. パスワードハッシュが正しく生成されているか確認
3. generate_password.phpで再度パスワードを生成して試す

## セキュリティ推奨事項

1. **パスワードの強度**
   - 8文字以上
   - 英数字と記号を組み合わせる
   - 辞書にある単語を避ける

2. **定期的な変更**
   - パスワードは定期的に変更してください

3. **不要なファイルの削除**
   - 設定完了後、以下のファイルを削除することを推奨：
     - `test.php`
     - `generate_password.php`
     - このREADME.md

4. **HTTPS化**
   - ベーシック認証はHTTPSでの使用を強く推奨
   - 可能であればSSL証明書を導入してください

## 提案資料の内容

### 主な提案ポイント

1. **Instagram運用代行サービス**
   - 撮影から編集、投稿まで全て代行
   - 専門チームによる安定した運用

2. **料金プラン**
   - 予算に応じた柔軟なプラン設定
   - 例：月額15万円で月10本のリール投稿

3. **コンサルティング**
   - 戦略立案・改善提案を追加費用なしで提供

4. **育成サポート**
   - 社員育成も追加費用なしである程度対応可能

5. **サービス範囲**
   - 制作に特化（撮影・編集・投稿）
   - マーケティング施策は別途相談

## お問い合わせ

ご不明な点がございましたら、お気軽にお問い合わせください。
