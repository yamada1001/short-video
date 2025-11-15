# エラーページ・重要ファイル管理ガイド

このドキュメントでは、サイトのエラーページや重要なファイルについて説明します。

---

## 📄 作成済みファイル一覧

### エラーページ

| ファイル | 用途 | HTTPステータス |
|---------|------|---------------|
| `404.php` | ページが見つからない時 | 404 Not Found |
| `500.php` | サーバーエラー発生時 | 500 Internal Server Error |
| `503.php` | メンテナンス中 | 503 Service Unavailable |

### 重要なファイル

| ファイル | 用途 | 詳細 |
|---------|------|------|
| `robots.txt` | 検索エンジンへの指示 | クローラーの動作制御 |
| `sitemap.xml` | サイト構造の通知 | SEO対策 |
| `humans.txt` | 制作者情報 | 人間が読む用 |
| `security.txt` | セキュリティ連絡先 | RFC 9116準拠 |
| `.well-known/security.txt` | 同上（推奨位置） | セキュリティ情報開示 |

---

## 🔧 メンテナンスモードの使い方

サイトをメンテナンス中にする際は、以下のコマンドを使用します。

### メンテナンス開始
```bash
php maintenance-mode.php on
```

これにより：
- サイト全体に503エラーページが表示されます
- 管理者（127.0.0.1）からはアクセス可能です
- 静的アセット（CSS/JS/画像）は正常に読み込まれます

### メンテナンス終了
```bash
php maintenance-mode.php off
```

### 状態確認
```bash
php maintenance-mode.php status
```

### 特定のIPアドレスを除外する場合

`maintenance-mode.php on` 実行後、`.htaccess`を編集して以下の行のコメントを解除：

```apache
# RewriteCond %{REMOTE_ADDR} !^xxx\.xxx\.xxx\.xxx$
```

`xxx.xxx.xxx.xxx`を実際のIPアドレスに置き換えてください。

---

## 🎨 エラーページのカスタマイズ

### 404ページ（404.php）
- ページが見つからない時に表示
- トップページ、ブログ一覧、お問い合わせへのリンクあり
- デザインはシンプルで分かりやすい

**カスタマイズポイント**:
- `.error-link`のカラー変更で統一感を出せます
- リンク先は自由に追加・削除可能

### 500ページ（500.php）
- サーバーエラー時に表示
- お問い合わせ情報を表示

**カスタマイズポイント**:
- グラデーションカラーを変更可能
- 連絡先情報は`includes/config.php`から自動取得するよう改善可能

### 503ページ（503.php）
- メンテナンス中に表示
- 5分ごとに自動リロード
- メンテナンス完了予定時刻を表示可能

**カスタマイズポイント**:
```html
<!-- メンテナンス時に以下のコメントを解除して時間を記載 -->
<div class="estimated-time">完了予定: 2025年○月○日 ○時頃</div>
```

---

## 🤖 robots.txt の設定

### 現在の設定
```
User-agent: *
Allow: /
Disallow: /includes/
Disallow: /.git/
Disallow: /.claude/
Disallow: /node_modules/
Disallow: /vendor/
```

### カスタマイズ例

**特定のディレクトリをブロック**:
```
Disallow: /admin/
Disallow: /private/
```

**特定のボットをブロック**:
```
User-agent: BadBot
Disallow: /
```

**クロール速度を制限**:
```
Crawl-delay: 10
```

---

## 🔒 security.txt について

RFC 9116に準拠したセキュリティ情報開示ファイルです。

### 配置場所
- `/.well-known/security.txt`（推奨）
- `/security.txt`（後方互換性のため）

### 内容
```
Contact: mailto:info@yojitu.com
Contact: tel:+81-80-9245-5598
Expires: 2026-11-15T00:00:00.000Z
```

### 更新が必要なタイミング
- **Expiresフィールド**: 1年に1回更新
- **Contact**: 連絡先が変更になった時

---

## 👥 humans.txt について

サイト制作者や使用技術を記載したファイルです。

### アクセス方法
```
https://yojitu.com/humans.txt
```

### カスタマイズ
- チームメンバーが増えたら追加
- 使用技術が変わったら更新
- お礼を伝えたい人・組織を追加

---

## 📊 .htaccess のエラーハンドリング

現在の設定：
```apache
ErrorDocument 404 /404.php
ErrorDocument 500 /500.php
ErrorDocument 503 /503.php
```

### 他のエラーコードを追加する場合

**403 Forbidden（アクセス禁止）**:
```apache
ErrorDocument 403 /403.php
```

**401 Unauthorized（認証エラー）**:
```apache
ErrorDocument 401 /401.php
```

---

## ✅ チェックリスト

定期的に以下を確認してください：

- [ ] **毎年**: `security.txt`のExpiresフィールドを更新
- [ ] **毎年**: `humans.txt`の情報を確認・更新
- [ ] **四半期ごと**: エラーページのリンク先が正しいか確認
- [ ] **メンテナンス時**: `503.php`の完了予定時刻を記載
- [ ] **サイト構造変更時**: `robots.txt`の設定を見直し

---

## 🆘 トラブルシューティング

### メンテナンスモードが解除できない
```bash
# .htaccessを手動で編集して、メンテナンスモードのセクションを削除
vim .htaccess
# または
nano .htaccess
```

### エラーページが表示されない
1. `.htaccess`の設定を確認
2. エラーページのファイルパスが正しいか確認
3. Apacheの設定で`AllowOverride All`になっているか確認

### robots.txtが反映されない
- ブラウザキャッシュをクリア
- Google Search Consoleの「robots.txt テスター」で確認

---

## 📝 関連ドキュメント

- [ブログ記事作成ガイド](blog/README.md)
- [リンクチェッカー使用方法](blog/README.md#リンクチェッカーの使い方)

---

## 📞 サポート

質問や問題がある場合は、以下までご連絡ください：
- Email: info@yojitu.com
- Tel: 080-9245-5598
