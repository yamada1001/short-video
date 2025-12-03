# Demo Site - README

## 概要
クライアント向けテストサイトを管理するディレクトリです。
サーバーやドメイン契約前に、完成イメージを確認していただくためのプレビュー環境です。

## アクセス方法

### URL
```
https://www.yojitu.com/demo/
```

### Basic認証
- **ユーザー名**: demo
- **パスワード**: （別途管理）

※ パスワードは `.htpasswd` で管理（Git管理対象外）

## 案件ごとのアクセス

### くるま買取ケイヴィレッジ
```
https://www.yojitu.com/demo/kuruma-kaitori-k-village/
```

## 新規案件の追加手順

### 1. ディレクトリ作成
```bash
# _template をコピー（将来的に実装予定）
cp -r demo/_template demo/{client-slug}

# または手動で作成
mkdir -p demo/{client-slug}/assets/{css,js,images}
```

### 2. CLIENT_INFO.md を作成
クライアント情報、案件詳細、要望などを記録。

### 3. 開発開始
- `index.php` からコーディング開始
- `assets/` にCSS、JS、画像を配置

### 4. クライアントへ共有
- URL + Basic認証情報を共有
- フィードバックを受けて修正

### 5. 本番移行
- 承認後、本番サーバーへデプロイ
- demo内のファイルはアーカイブまたは削除

## ディレクトリ命名規則

### クライアントスラッグ
- 半角英数字とハイフン（`-`）のみ
- すべて小文字
- 例:
  - `tanaka-koumuten`（田中工務店）
  - `suzuki-dental-clinic`（鈴木歯科医院）
  - `kuruma-kaitori-k-village`（くるま買取ケイヴィレッジ）

## セキュリティ

### Basic認証
- `/demo/` 全体に認証がかかっています
- `.htpasswd` は Git 管理対象外（`.gitignore` に追加済み）

### パスワード生成方法
```bash
# htpasswd コマンドでパスワード生成
htpasswd -c demo/.htpasswd demo

# 追加ユーザーを作成（-c オプションなし）
htpasswd demo/.htpasswd username
```

### 検索エンジン対策
- `X-Robots-Tag: noindex, nofollow` ヘッダー送信
- 検索エンジンにインデックスされません

## ファイル管理

### Git管理対象
- ✅ `.htaccess`
- ✅ `index.php`
- ✅ `CLIENT_INFO.md`
- ✅ `DIRECTORY_STRUCTURE.md`
- ✅ ソースコード（HTML, CSS, JS）

### Git管理対象外
- ❌ `.htpasswd`（パスワードファイル）
- ❌ `.DS_Store`
- ❌ `node_modules/`（もし使う場合）
- ❌ 大容量画像（別途CDN管理推奨）

## トラブルシューティング

### 403 Forbidden が表示される
1. `.htpasswd` のパスが正しいか確認
2. `.htaccess` の `AuthUserFile` を絶対パスに修正
3. パーミッション確認（`.htpasswd` は 600 推奨）

### 画像が表示されない
1. 相対パスが正しいか確認
2. ファイル名の大文字小文字を確認（Linux は区別あり）
3. パーミッション確認（644 推奨）

### CSS/JS が読み込まれない
1. パスが正しいか確認
2. ブラウザのキャッシュをクリア
3. Developer Tools でエラー確認

## お問い合わせ

技術的な質問、トラブルがあれば下記まで：
- Email: info@yojitu.com
- 担当: YOJITU.COM 開発チーム

---

最終更新: 2025-12-03
