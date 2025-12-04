# BNI Slide System - デプロイガイド

## 現状の問題

GitHubにプッシュしただけでは、Xserverに自動的に反映されません。
手動でアップロードする必要があります。

---

## 📦 デプロイ方法（3つの選択肢）

### 方法1: FTPでアップロード（推奨・簡単）

1. **FTPソフトを起動**
   - FileZilla、Cyberduck、Transmit など

2. **Xserverに接続**
   - ホスト: `your-server.xsrv.jp`
   - ユーザー名: Xserverのサーバーアカウント
   - パスワード: Xserverのパスワード
   - ポート: 21

3. **アップロード**
   ```
   ローカル: /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/
   サーバー: /home/your_server_id/yojitu.com/public_html/bni-slide-system/
   ```

4. **フォルダごとアップロード**
   - `bni-slide-system/` フォルダを丸ごとドラッグ&ドロップ

---

### 方法2: SSH + Git Clone（中級者向け）

Xserverのスタンダードプラン以上であればSSHが使えます。

```bash
# 1. XserverにSSH接続
ssh your_account@your_server.xsrv.jp

# 2. public_htmlに移動
cd ~/yojitu.com/public_html/

# 3. Gitリポジトリをクローン（初回のみ）
git clone https://github.com/yamada1001/short-video.git temp_clone
cp -r temp_clone/bni-slide-system ./
rm -rf temp_clone

# または、既にクローン済みの場合
cd ~/yojitu.com/public_html/bni-slide-system/
git pull origin main
```

---

### 方法3: Xserverファイルマネージャー（ブラウザ）

1. **Xserverサーバーパネルにログイン**
   - https://www.xserver.ne.jp/login_server.php

2. **ファイルマネージャーを開く**

3. **アップロード**
   - `public_html/` に移動
   - ZIPファイルをアップロード → 解凍

---

## 🔧 アップロード後の設定（必須）

### 1. パーミッション設定

FTPソフトまたはSSHで以下を設定:

```bash
chmod 755 bni-slide-system
chmod 707 bni-slide-system/data
chmod 644 bni-slide-system/.htaccess
chmod 604 bni-slide-system/.htpasswd
chmod 644 bni-slide-system/*.php
```

### 2. 絶対パスの確認

一時的なパス確認用PHPファイルを作成:

**path-check.php を作成:**
```php
<?php
echo "絶対パス: " . __DIR__;
?>
```

**アクセス:**
```
https://yojitu.com/bni-slide-system/path-check.php
```

表示された絶対パスをコピー。

### 3. .htaccess の修正

`bni-slide-system/.htaccess` の18行目を編集:

```apache
# 修正前
AuthUserFile /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/.htpasswd

# 修正後（例）
AuthUserFile /home/xs123456/yojitu.com/public_html/bni-slide-system/.htpasswd
```

### 4. path-check.php を削除

セキュリティのため、確認後は削除してください。

---

## ✅ 動作確認

### 1. アンケートフォーム
```
https://yojitu.com/bni-slide-system/
```
- Basic認証が表示される（bni / bni2024）
- フォームが表示される

### 2. スライド表示
```
https://yojitu.com/bni-slide-system/slide.php
```
- 「データがまだありません」と表示される（正常）
- アンケート送信後、スライドが表示される

### 3. データ編集
```
https://yojitu.com/bni-slide-system/edit.php
```
- テーブルが表示される

---

## 🐛 トラブルシューティング

### 500 Internal Server Error

**原因1: .htaccess の AuthUserFile パスが間違っている**
→ path-check.php で絶対パスを確認して修正

**原因2: .htpasswd のパーミッションが間違っている**
→ `chmod 604 .htpasswd`

**原因3: PHPのバージョンが古い**
→ Xserverサーバーパネルで PHP 8.0 以上に設定

### 404 Not Found

**原因: ファイルがアップロードされていない**
→ FTPでアップロード確認

### 認証が動かない

**原因: .htaccess のパスが間違っている**
→ 絶対パスを再確認

### データが保存されない

**原因: data/ ディレクトリのパーミッションが間違っている**
→ `chmod 707 data/`

---

## 📞 サポート

不明点があれば yamada@yojitu.com までご連絡ください。

---

## 🔄 今後の更新方法

ローカルで編集 → Git push → FTPで上書き の流れで更新します。

```bash
# ローカルで編集
git add .
git commit -m "Update: ..."
git push

# FTPで bni-slide-system/ を上書きアップロード
```

自動デプロイが必要な場合は、GitHub Actions の設定が必要です。
