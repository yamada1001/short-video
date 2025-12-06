# 🔒 Basic認証セットアップガイド

## 📋 セットアップ手順

### ステップ1: .htpasswd を生成する

以下のURLにブラウザでアクセスしてください：

```
https://yojitu.com/travel-guide/generate-htpasswd.php
```

実行すると、以下のような画面が表示されます：

```
✅ .htpasswd ファイルが正常に生成されました！

保存先: /home/xs545151/yojitu.com/public_html/travel-guide/.htpasswd

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ログイン情報
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ユーザー名: travel
パスワード: kyoto2025!
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

⚠️ 重要な次のステップ:
1. このログイン情報を安全な場所に保存してください
2. このスクリプト（generate-htpasswd.php）を削除してください
3. 削除コマンド: rm /home/xs545151/yojitu.com/public_html/travel-guide/generate-htpasswd.php

これでBasic認証が有効になります。
次回アクセス時にログイン画面が表示されます。
```

### ステップ2: ログイン情報を保存する

**必ずメモしてください！**

- **ユーザー名**: `travel`
- **パスワード**: `kyoto2025!`

スマホのメモアプリや、パスワードマネージャーに保存することをおすすめします。

### ステップ3: 生成スクリプトを削除する

セキュリティのため、**必ず削除してください**。

#### 方法1: サーバーのファイルマネージャーで削除

Xserverのファイルマネージャーにログインして、以下のファイルを削除：

```
/home/xs545151/yojitu.com/public_html/travel-guide/generate-htpasswd.php
```

#### 方法2: SSHで削除（SSH接続可能な場合）

```bash
rm /home/xs545151/yojitu.com/public_html/travel-guide/generate-htpasswd.php
```

### ステップ4: 動作確認

以下のURLにアクセスしてください：

```
https://yojitu.com/travel-guide/kyoto/index.php
```

**ログイン画面が表示されます。**

- ユーザー名: `travel`
- パスワード: `kyoto2025!`

を入力してログインしてください。

---

## 🔧 トラブルシューティング

### ログイン画面が表示されない

1. `.htpasswd` ファイルが正しく生成されているか確認
2. `.htaccess` ファイルが存在するか確認
3. ブラウザのキャッシュをクリア

### ログインできない

1. ユーザー名とパスワードを再確認
2. 半角・全角、大文字・小文字に注意
3. パスワード: `kyoto2025!` （感嘆符を忘れずに）

### エラーが表示される

サーバーのエラーログを確認するか、.htaccess の `AuthUserFile` のパスが正しいか確認してください。

---

## 📝 認証情報の変更方法

ユーザー名やパスワードを変更したい場合：

1. `generate-htpasswd.php` の以下の部分を編集

```php
$username = 'travel';        // ← ユーザー名を変更
$password = 'kyoto2025!';    // ← パスワードを変更
```

2. 再度 `generate-htpasswd.php` を実行
3. 新しい `.htpasswd` が生成されます

---

## 🔒 セキュリティ上の注意

- ❌ `generate-htpasswd.php` を削除せずに放置しない
- ✅ パスワードは定期的に変更する
- ✅ ログイン情報は安全な場所に保管する
- ✅ `.htpasswd` ファイルは Git にコミットしない（.gitignore 設定済み）

---

**設定完了後は、このファイル（BASIC_AUTH_SETUP.md）も削除してOKです。**
