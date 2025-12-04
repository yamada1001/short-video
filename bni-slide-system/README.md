# BNI Slide System

BNIビジネスコミュニティ向けの週次スライド資料管理システム

## 概要

- **アンケートフォーム**: メンバーからビジター紹介・リファーラル情報を収集
- **スライド表示**: Reveal.jsを使った自動生成スライド
- **編集機能**: Web上でデータを直接編集
- **セキュリティ**: Basic認証 + noindex設定

---

## ファイル構成

```
bni-slide-system/
├── .htaccess              # Basic認証設定
├── .htpasswd              # 認証情報（bni / bni2024）
├── index.php              # アンケートフォーム
├── slide.php              # スライド表示
├── edit.php               # データ編集
├── api_save.php           # フォーム保存API
├── api_load.php           # データ読み込みAPI
├── api_update.php         # データ更新API
├── assets/
│   ├── css/               # スタイルシート
│   ├── js/                # JavaScript
│   └── lib/
│       └── reveal.js/     # Reveal.jsライブラリ
├── data/
│   ├── .htaccess          # 直接アクセス禁止
│   └── responses.csv      # アンケートデータ（自動生成）
├── SPECIFICATION.md       # 詳細仕様書
└── README.md              # このファイル
```

---

## デプロイ方法（Xserver）

### 1. ファイルをアップロード

FTPまたはSSHで `bni-slide-system/` フォルダごとアップロード

```
public_html/
└── bni-slide-system/  ← ここにアップロード
```

### 2. .htaccess のパス修正

**重要**: `.htaccess` の `AuthUserFile` を**絶対パス**に変更してください。

```apache
# 修正前（ローカル用）
AuthUserFile /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/.htpasswd

# 修正後（Xserver用）
AuthUserFile /home/your_server_id/your_domain/public_html/bni-slide-system/.htpasswd
```

絶対パスの確認方法:
```php
<?php echo __DIR__; ?>
```
このPHPコードを一時ファイルで実行するとパスが表示されます。

### 3. パーミッション設定

```bash
# ディレクトリ
chmod 755 bni-slide-system
chmod 707 bni-slide-system/data

# ファイル
chmod 644 bni-slide-system/*.php
chmod 644 bni-slide-system/.htaccess
chmod 604 bni-slide-system/.htpasswd
```

### 4. メール設定確認

`api_save.php` の以下の設定を確認:

```php
define('MAIL_TO', 'yamada@yojitu.com');      // 送信先
define('MAIL_FROM', 'noreply@yojitu.com');   // 送信元（自ドメイン）
```

---

## アクセス情報

### URL

```
https://your-domain.com/bni-slide-system/          # アンケートフォーム
https://your-domain.com/bni-slide-system/slide.php # スライド表示
https://your-domain.com/bni-slide-system/edit.php  # データ編集
```

### Basic認証

```
ユーザー名: bni
パスワード: bni2024
```

---

## 使い方

### 1. アンケート回答

1. `index.php` にアクセス
2. フォームに入力して送信
3. データが `data/responses.csv` に保存
4. `yamada@yojitu.com` にメール通知

### 2. スライド表示

1. `slide.php` にアクセス
2. CSVデータから自動生成されたスライドを表示
3. キーボード操作:
   - `→` 次のスライド
   - `←` 前のスライド
   - `Esc` 全体表示
   - `F` フルスクリーン

### 3. データ編集

1. `edit.php` にアクセス
2. テーブル形式でデータを編集
3. 「保存」ボタンで確定
4. 自動でバックアップ作成

---

## トラブルシューティング

### Basic認証が動かない

1. `.htaccess` の `AuthUserFile` パスを確認
2. `.htpasswd` のパーミッションを確認（604）
3. Xserverの `.htaccess` 設定が有効か確認

### CSVが保存されない

1. `data/` ディレクトリのパーミッションを 707 に設定
2. PHPのエラーログを確認
3. `php_value upload_max_filesize` を確認

### メールが届かない

1. `api_save.php` の `MAIL_FROM` を自ドメインのアドレスに変更
2. Xserverのメール送信制限を確認
3. 迷惑メールフォルダを確認

### スライドが表示されない

1. ブラウザのコンソールでエラー確認
2. `api_load.php` に直接アクセスしてJSONが返るか確認
3. Reveal.jsのライブラリが正しく読み込まれているか確認

---

## カスタマイズ

### 1. デザイン変更

`assets/css/slide.css` でBNIカラー（`#CF2030`）を変更可能

### 2. フォーム項目追加

1. `index.php` でフォーム項目追加
2. `api_save.php` でCSV保存処理追加
3. `assets/js/slide.js` でスライド表示追加

### 3. スライド内容変更

`assets/js/slide.js` の `generateSlides()` 関数を編集

---

## セキュリティ

- ✅ Basic認証による全ページ保護
- ✅ `robots.txt` と `noindex` でSEO除外
- ✅ `data/` ディレクトリへの直接アクセス禁止
- ✅ XSS対策（入力値のエスケープ）
- ✅ CSRF対策（今後実装予定）

---

## 今後の改善予定

- [ ] CSRF トークン実装
- [ ] ログイン機能（Basic認証の代替）
- [ ] グラフ・チャート表示（Chart.js）
- [ ] PDF出力機能
- [ ] 過去データのアーカイブ機能
- [ ] メンバー管理機能

---

## サポート

質問・不具合報告は yamada@yojitu.com まで

---

## ライセンス

© 2024 BNI Slide System. All rights reserved.

Reveal.js: MIT License (https://github.com/hakimel/reveal.js)
