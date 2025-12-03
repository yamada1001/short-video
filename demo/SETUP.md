# Demo Site セットアップ手順

## 初回セットアップ

### 1. Basic認証用パスワードファイルの作成

#### macOS / Linux の場合
```bash
# demo/ ディレクトリで実行
cd /Users/yamadaren/Movies/claude-code-projects/yojitu.com/demo

# パスワードファイル作成（初回）
htpasswd -c .htpasswd demo

# パスワード入力を求められるので入力
# 例: demo2024
```

#### Windows の場合
オンラインツールを使用:
1. https://www.web2generators.com/apache-tools/htpasswd-generator にアクセス
2. Username: `demo`
3. Password: 任意のパスワード
4. 生成された文字列を `.htpasswd` に保存

### 2. .htaccess のパス修正

`.htaccess` の `AuthUserFile` を環境に合わせて修正:

```apache
# ローカル環境の場合
AuthUserFile /Users/yamadaren/Movies/claude-code-projects/yojitu.com/demo/.htpasswd

# 本番サーバーの場合（例）
AuthUserFile /home/username/public_html/demo/.htpasswd
```

**パスの確認方法:**
```bash
pwd
# 出力例: /Users/yamadaren/Movies/claude-code-projects/yojitu.com/demo
```

### 3. パーミッション設定（本番サーバー）

```bash
# .htpasswd のパーミッション
chmod 600 demo/.htpasswd

# .htaccess のパーミッション
chmod 644 demo/.htaccess

# ディレクトリのパーミッション
chmod 755 demo
```

### 4. 動作確認

1. ブラウザで `https://www.yojitu.com/demo/` にアクセス
2. Basic認証ダイアログが表示される
3. ユーザー名 `demo` とパスワードを入力
4. アクセス成功を確認

## 案件ごとの個別認証設定（オプション）

特定の案件にのみ別のパスワードを設定したい場合:

### 1. 案件ディレクトリに .htpasswd を作成
```bash
cd demo/kuruma-kaitori-k-village
htpasswd -c .htpasswd client_demo
# パスワード入力（例: k-village2024）
```

### 2. 案件ディレクトリに .htaccess を作成
```apache
# demo/kuruma-kaitori-k-village/.htaccess
AuthType Basic
AuthName "K-Village Demo Site"
AuthUserFile /path/to/demo/kuruma-kaitori-k-village/.htpasswd
Require valid-user
```

### 3. クライアントへ共有
- URL: `https://www.yojitu.com/demo/kuruma-kaitori-k-village/`
- ユーザー名: `client_demo`
- パスワード: `k-village2024`

## パスワードの変更

### 既存ユーザーのパスワード変更
```bash
# -c オプションなしで実行（既存ファイルを上書きしない）
htpasswd demo/.htpasswd demo
# 新しいパスワード入力
```

### 複数ユーザーの追加
```bash
# ユーザー1: demo
htpasswd -c demo/.htpasswd demo

# ユーザー2: client（-c オプションなし）
htpasswd demo/.htpasswd client

# ユーザー3: reviewer（-c オプションなし）
htpasswd demo/.htpasswd reviewer
```

`.htpasswd` の内容例:
```
demo:$apr1$xyz123$abcdefghijklmnopqrstuvwxyz
client:$apr1$abc456$1234567890abcdefghijklmno
reviewer:$apr1$def789$qwertyuiopasdfghjklzxcvbn
```

## セキュリティのベストプラクティス

### 1. パスワードの強度
- 最低8文字以上
- 英数字 + 記号を含める
- 推測されにくいものを選ぶ
- 例: `Demo#2024!Yojitu`

### 2. パスワードの管理
- `.htpasswd` は絶対に Git にコミットしない（`.gitignore` 確認）
- パスワードはパスワードマネージャーで管理
- クライアントへはメール、Slack、Chatworkなどで共有

### 3. アクセスログの確認
定期的に不正アクセスがないかチェック:
```bash
# Apacheのアクセスログ確認
tail -f /var/log/apache2/access.log | grep "/demo/"
```

### 4. 案件完了後の処理
- Basic認証を解除（または削除）
- `.htpasswd` を削除
- ディレクトリごと削除またはアーカイブ

## トラブルシューティング

### 「Internal Server Error」が表示される
**原因**: `.htaccess` の `AuthUserFile` パスが間違っている

**解決方法**:
```bash
# 絶対パスを確認
cd demo
pwd
# 出力された絶対パスを .htaccess に記載
```

### 「401 Unauthorized」が何度も表示される
**原因1**: パスワードが間違っている
- 正しいパスワードを再確認

**原因2**: `.htpasswd` のパーミッションが正しくない
```bash
chmod 600 demo/.htpasswd
```

**原因3**: ユーザー名が間違っている
- `.htpasswd` の内容を確認
```bash
cat demo/.htpasswd
# demo:$apr1$... のように表示されるはず
```

### Basic認証ダイアログが表示されない
**原因1**: `.htaccess` が読み込まれていない
- Apacheの設定で `AllowOverride` が有効か確認

**原因2**: `.htaccess` のシンタックスエラー
```bash
# エラーログ確認
tail -f /var/log/apache2/error.log
```

## 開発環境でのBasic認証無効化（オプション）

開発時に毎回認証が面倒な場合:

### 方法1: .htaccess を一時的にリネーム
```bash
mv demo/.htaccess demo/.htaccess.bak
# 開発完了後
mv demo/.htaccess.bak demo/.htaccess
```

### 方法2: IPアドレスで除外
`.htaccess` に追加:
```apache
# 特定IPからのアクセスは認証不要
Satisfy Any
Order deny,allow
Deny from all
Allow from 192.168.1.100  # 自分のIPアドレス
Require valid-user
```

## 参考リンク

- [Apache Basic認証公式ドキュメント](https://httpd.apache.org/docs/2.4/howto/auth.html)
- [htpasswd コマンドリファレンス](https://httpd.apache.org/docs/2.4/programs/htpasswd.html)

---

最終更新: 2025-12-03
