# Xserverでのベーシック認証設定方法

## 概要
このディレクトリ（proposal/oab-video-web/）にベーシック認証をかける手順です。

## 必要なファイル
1. `.htaccess` - アクセス制御設定
2. `.htpasswd` - ユーザー名とパスワードのハッシュ値

---

## 手順

### ステップ1: パスワードのハッシュ値を生成

以下のいずれかの方法でパスワードのハッシュ値を生成します：

#### 方法A: オンラインツールを使う（簡単）
1. ブラウザで以下のサイトにアクセス
   - https://www.luft.co.jp/cgi/htpasswd.php
   - https://hostingcanada.org/htpasswd-generator/

2. ユーザー名とパスワードを入力
   - 例: ユーザー名 `oab`、パスワード `video2025`

3. 生成された文字列をコピー
   - 例: `oab:$apr1$xxxxx$yyyyy`

#### 方法B: コマンドライン（Mac/Linux）
```bash
htpasswd -c .htpasswd oab
# パスワード入力を求められます
```

---

### ステップ2: .htpasswdファイルを作成

1. `proposal/oab-video-web/.htpasswd` ファイルを作成
2. 以下の形式で記述（ステップ1で生成した文字列）:
```
oab:$apr1$xxxxx$yyyyy
```

**複数のユーザーを追加する場合:**
```
oab:$apr1$xxxxx$yyyyy
user2:$apr1$aaaaa$bbbbb
user3:$apr1$ccccc$ddddd
```

---

### ステップ3: .htaccessファイルを修正

現在の `proposal/oab-video-web/.htaccess` に以下を追加：

```apache
# ベーシック認証設定
AuthType Basic
AuthName "OAB Proposal - Restricted Access"
AuthUserFile /home/あなたのサーバーID/yojitu.com/public_html/proposal/oab-video-web/.htpasswd
Require valid-user
```

**重要: `AuthUserFile` のパスについて**

Xserverの場合、**絶対パス**を指定する必要があります：
```
/home/サーバーID/ドメイン名/public_html/proposal/oab-video-web/.htpasswd
```

例:
```
/home/xs123456/yojitu.com/public_html/proposal/oab-video-web/.htpasswd
```

**サーバーIDの確認方法:**
1. Xserverサーバーパネルにログイン
2. 左メニュー「サーバー情報」をクリック
3. 「サーバーID」の項目を確認

---

### ステップ4: ファイルをサーバーにアップロード

#### 方法A: FTPソフトを使う
1. FileZilla等のFTPソフトで接続
2. `proposal/oab-video-web/` に移動
3. `.htpasswd` と `.htaccess` をアップロード

#### 方法B: Gitでプッシュ（.htpasswdのみ手動アップロード推奨）
```bash
# .htpasswdはセキュリティ上Gitに含めない
echo ".htpasswd" >> .gitignore

# .htaccessのみコミット
git add proposal/oab-video-web/.htaccess
git commit -m "Add: ベーシック認証設定"
git push

# .htpasswdは手動でFTPアップロード
```

---

## 完成形の.htaccess例

```apache
# proposal/oab-video-web/ 専用設定

# index.htmlをデフォルトページに設定
DirectoryIndex index.html

# アクセス許可
<IfModule mod_authz_core.c>
    Require all granted
</IfModule>

# ベーシック認証設定
AuthType Basic
AuthName "OAB Proposal - Restricted Access"
AuthUserFile /home/xs123456/yojitu.com/public_html/proposal/oab-video-web/.htpasswd
Require valid-user

# キャッシュ設定（親の設定を継承）
# セキュリティヘッダーも親から継承
```

---

## 動作確認

1. ブラウザで以下にアクセス:
   ```
   https://yojitu.com/proposal/oab-video-web/
   ```

2. 認証ダイアログが表示されればOK

3. ユーザー名とパスワードを入力してログイン

---

## トラブルシューティング

### 500 Internal Server Error が出る場合

**原因1: AuthUserFile のパスが間違っている**
- 絶対パスが正しいか確認
- サーバーIDが正しいか確認

**原因2: .htpasswd のパーミッションが間違っている**
```bash
# FTPソフトまたはサーバーのファイルマネージャーで
# .htpasswd のパーミッションを 644 に設定
```

**原因3: .htaccess の構文エラー**
- 余計なスペースや改行がないか確認
- コピペ時の文字化けがないか確認

### 認証ダイアログが表示されない場合

**原因: .htaccess が読み込まれていない**
1. ファイル名が `.htaccess` で正しいか確認（先頭のドットを忘れずに）
2. サーバーにファイルがアップロードされているか確認
3. ブラウザのキャッシュをクリア

---

## セキュリティ上の注意

1. **.htpasswd は必ず .gitignore に追加**
   ```bash
   echo ".htpasswd" >> .gitignore
   ```

2. **パスワードは複雑なものを使用**
   - 最低12文字以上
   - 英数字+記号を組み合わせる

3. **定期的にパスワードを変更**

4. **使用後は認証を解除するか、ファイルを削除**

---

## 認証の解除方法

### 一時的に解除する場合
`.htaccess` の認証部分をコメントアウト:
```apache
# AuthType Basic
# AuthName "OAB Proposal - Restricted Access"
# AuthUserFile /home/xs123456/yojitu.com/public_html/proposal/oab-video-web/.htpasswd
# Require valid-user
```

### 完全に解除する場合
1. `.htaccess` から認証設定を削除
2. `.htpasswd` ファイルを削除

---

## 参考リンク
- Xserver公式: ベーシック認証設定
  https://www.xserver.ne.jp/manual/man_server_access.php
- .htpasswd生成ツール
  https://www.luft.co.jp/cgi/htpasswd.php
