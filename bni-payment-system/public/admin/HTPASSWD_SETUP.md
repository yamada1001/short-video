# Basic認証 .htpasswd 設定方法

## 概要
管理画面（/admin/）へのアクセスをBasic認証で保護します。

## .htpasswdファイル生成手順

### 方法1: htpasswdコマンド（推奨）

```bash
# ローカル環境
cd /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-payment-system/public/admin
htpasswd -c .htpasswd admin
# パスワード入力を求められます

# 本番環境（Xserver SSH）
cd /home/username/yojitu.com/public_html/bni-payment-system/admin
htpasswd -c .htpasswd admin
```

### 方法2: オンラインツール

1. https://www.web2generators.com/apache-tools/htpasswd-generator にアクセス
2. ユーザー名: `admin`
3. パスワード: （強力なパスワードを設定）
4. "Generate" をクリック
5. 生成された文字列を `.htpasswd` ファイルに保存

```bash
# .htpasswd ファイル作成
echo "admin:$apr1$..." > .htpasswd
```

### 方法3: PHPスクリプト

以下のコマンドで生成できます:

```bash
php -r "echo 'admin:' . password_hash('YOUR_PASSWORD', PASSWORD_BCRYPT) . PHP_EOL;" > .htpasswd
```

## .htpasswd ファイル形式

```
admin:$apr1$rOioh4Wq$AtQ8FXGwVvt5y5SmMWV.V1
```

## .htaccess パス設定確認

`/admin/.htaccess` の `AuthUserFile` パスを確認してください:

```apache
AuthUserFile /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-payment-system/public/admin/.htpasswd
```

**本番環境では絶対パスに変更が必要です！**

例（Xserver）:
```apache
AuthUserFile /home/username/yojitu.com/public_html/bni-payment-system/admin/.htpasswd
```

## セキュリティ推奨事項

1. **強力なパスワード**: 12文字以上、英数字記号混在
2. **定期変更**: 3ヶ月ごとにパスワード変更
3. **権限設定**: `.htpasswd` のパーミッションを `600` に設定

```bash
chmod 600 .htpasswd
```

4. **ユーザー追加** (複数管理者):

```bash
# 2人目以降は -c オプション不要
htpasswd .htpasswd user2
```

## トラブルシューティング

### "Internal Server Error" が出る場合

1. `.htaccess` の `AuthUserFile` パスが正しいか確認
2. `.htpasswd` のパーミッションを確認（600または644）
3. Apacheの `mod_auth_basic` が有効か確認

### パスワードが通らない場合

1. `.htpasswd` のフォーマットが正しいか確認
2. 半角スペース・改行が余分に入っていないか確認
3. 再生成して試す

## テスト方法

```bash
# ローカル環境
http://localhost/bni-payment-system/admin/

# 本番環境
https://yojitu.com/bni-payment-system/admin/
```

認証ダイアログが表示され、ユーザー名・パスワード入力後にアクセスできることを確認してください。

## 初期設定例（開発用）

**※本番環境では必ず変更してください！**

```bash
# 開発用（弱いパスワード - 本番環境では使用禁止）
htpasswd -cb .htpasswd admin password123
```

**本番環境では強力なパスワードを設定してください。**
