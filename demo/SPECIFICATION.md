# デモサイト管理システム 仕様書

## 概要
クライアント向けテストサイトを作成・管理するためのシステム。
サーバーやドメイン契約前に、完成イメージを確認してもらうことが目的。

## ディレクトリ構造

```
yojitu.com/
├── demo/                          # デモ・テストサイト専用ディレクトリ
│   ├── .htaccess                  # Basic認証 + noindex設定
│   ├── .htpasswd                  # 認証用パスワードファイル
│   ├── index.php                  # 案件一覧ページ
│   ├── SPECIFICATION.md           # 本仕様書
│   │
│   ├── _template/                 # テンプレート（新規案件用）
│   │   ├── index.php
│   │   ├── about.php
│   │   ├── contact.php
│   │   ├── assets/
│   │   │   ├── css/
│   │   │   │   └── style.css
│   │   │   ├── js/
│   │   │   │   └── script.js
│   │   │   └── images/
│   │   └── README.md
│   │
│   ├── {client-slug}/             # 案件ごとのディレクトリ
│   │   ├── index.php              # トップページ
│   │   ├── about.php              # 会社概要（任意）
│   │   ├── contact.php            # お問い合わせ（任意）
│   │   ├── services.php           # サービス紹介（任意）
│   │   ├── assets/
│   │   │   ├── css/
│   │   │   │   └── style.css
│   │   │   ├── js/
│   │   │   │   └── script.js
│   │   │   └── images/
│   │   ├── .htpasswd              # 案件ごとに個別パスワード設定可能
│   │   └── CLIENT_INFO.md         # 案件メモ（納期、要望など）
│   │
│   └── （以下、案件ディレクトリが続く）
```

## URL構造

```
# 一覧ページ
https://www.yojitu.com/demo/

# 案件ごとのURL
https://www.yojitu.com/demo/{client-slug}/
https://www.yojitu.com/demo/{client-slug}/about
https://www.yojitu.com/demo/{client-slug}/contact
```

## セキュリティ対策

### 1. Basic認証
- `/demo/` ディレクトリ全体にBasic認証をかける
- 案件ごとに個別パスワード設定も可能（オプション）

### 2. 検索エンジン対策
- `X-Robots-Tag: noindex, nofollow` ヘッダー追加
- `robots.txt` で `/demo/` をDisallow

### 3. アクセス制限
- `.htaccess` でディレクトリリスティングを無効化
- 不要なファイル（.md, .gitなど）へのアクセスを禁止

## 命名規則

### クライアントスラッグ（ディレクトリ名）
- 半角英数字とハイフンのみ使用
- すべて小文字
- 例：
  - `tanaka-koumuten`
  - `suzuki-clinic`
  - `kuruma-kaitori-k-village`

## ファイル構成

### demo/.htaccess
```apache
# Basic認証
AuthType Basic
AuthName "Demo Site - Access Restricted"
AuthUserFile /path/to/.htpasswd
Require valid-user

# 検索エンジン対策
Header set X-Robots-Tag "noindex, nofollow"

# セキュリティ
Options -Indexes
<FilesMatch "\.(md|git|htpasswd)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

### demo/index.php
案件一覧ページ。以下の情報を表示：
- クライアント名
- サムネイル画像
- 作成日・更新日
- リンク

### CLIENT_INFO.md
各案件の情報を記録：
```markdown
# 案件情報

## クライアント情報
- 屋号：
- 所在地：
- 事業内容：
- 従業員数：
- 資格・許可番号：

## 制作情報
- 着手日：
- 納期：
- ステータス：（制作中 / レビュー待ち / 完成）

## 要望・メモ
-
```

## 運用フロー

### 新規案件の開始
1. `_template/` をコピーして新しいディレクトリを作成
2. `CLIENT_INFO.md` にクライアント情報を記入
3. デザイン・コーディングを実施
4. クライアントにURLとパスワードを共有

### 案件の完了
1. クライアントの承認を得る
2. 本番サーバーへ移行
3. demo内のディレクトリはアーカイブまたは削除

## 技術スタック

### フロントエンド
- HTML5, CSS3, JavaScript (ES6+)
- レスポンシブデザイン必須
- モダンブラウザ対応

### バックエンド
- PHP 7.4+
- Apache（.htaccess使用）

### 開発ツール
- Git管理対象（ただし.htpasswdは除外）

## 注意事項

1. **パスワード管理**
   - `.htpasswd` は必ず `.gitignore` に追加
   - クライアントごとに異なるパスワードを設定推奨

2. **本番環境への移行**
   - demo内のコードはあくまでプレビュー用
   - 本番移行時は最適化・セキュリティ強化を実施

3. **容量管理**
   - 画像は適切に圧縮
   - 不要な案件は定期的にアーカイブ

4. **バックアップ**
   - 重要な案件はGit管理推奨
   - 定期的にローカルバックアップ

## 更新履歴

- 2025-12-03: 初版作成
