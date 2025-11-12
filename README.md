# 余日（Yojitsu） コーポレートサイト

大分県を拠点としたデジタルマーケティング・Web制作会社「余日（Yojitsu）」の公式Webサイトです。

## 🌐 サイト情報

- **URL**: https://yojitu.com
- **サーバー**: Xserver
- **言語**: HTML, CSS, JavaScript, PHP
- **デプロイ**: SourceTree経由でGitプッシュ

## 📁 プロジェクト構成

```
/
├── index.html              # トップページ
├── about.html              # 会社概要
├── services.html           # サービス一覧
├── contact.html            # お問い合わせ
├── assets/
│   ├── css/
│   │   ├── reset.css       # リセットCSS
│   │   ├── common.css      # 共通スタイル
│   │   ├── components.css  # コンポーネント
│   │   ├── news.css        # お知らせ専用
│   │   └── pages/          # ページ別CSS
│   │       ├── top.css
│   │       ├── about.css
│   │       ├── services.css
│   │       └── contact.css
│   ├── js/
│   │   ├── common.js               # 共通機能
│   │   ├── nav.js                  # ナビゲーション
│   │   ├── fontawesome-init.js     # Font Awesome
│   │   └── form-validation.js      # フォームバリデーション
│   └── images/             # 画像ファイル（今後追加）
├── news/
│   ├── index.php           # お知らせ一覧
│   ├── detail.php          # お知らせ詳細
│   └── data/
│       └── articles.json   # 記事データ
├── php/
│   ├── config.php          # 設定ファイル
│   ├── functions.php       # 共通関数
│   └── contact-form.php    # お問い合わせ処理
├── robots.txt
├── .htaccess
├── .gitignore
└── README.md
```

## 🎨 デザインコンセプト

### JQ風ミニマルデザイン
- 余白を贅沢に使った洗練されたレイアウト
- モノトーンベースのカラーパレット
- レスポンシブデザイン（モバイルファースト）

### カラーパレット
- Primary: `#000000` (黒)
- Text: `#1a1a1a`
- Text Light: `#666666`
- Background: `#ffffff`
- Background Gray: `#f5f5f5`
- Border: `#e5e5e5`

### タイポグラフィ
- 日本語: Noto Sans JP (400, 500, 700)
- 英語: Inter, Helvetica Neue

## ✨ 実装済み機能

### フロントエンド
- ✅ レスポンシブデザイン
- ✅ スクロールアニメーション (Intersection Observer API)
- ✅ スティッキーヘッダー
- ✅ ハンバーガーメニュー
- ✅ スムーススクロール
- ✅ ページトップへ戻るボタン
- ✅ フォームバリデーション（クライアント側）

### バックエンド
- ✅ PHP設定ファイル（config.php）
- ✅ 共通関数（functions.php）
- ✅ お問い合わせフォーム処理
- ✅ 自動返信メール
- ✅ JSONベースの記事管理
- ✅ ページネーション

### SEO
- ✅ 構造化データ（JSON-LD）
- ✅ OGP設定
- ✅ メタタグ最適化
- ✅ ローカルSEO（大分県）
- ✅ robots.txt
- ✅ .htaccess最適化（Gzip, キャッシュ, セキュリティ）

## 🚀 デプロイ手順

### 1. SourceTreeでコミット
```
git add .
git commit -m "コミットメッセージ"
git push origin main
```

### 2. Xserverで確認
- https://yojitu.com/ にアクセスして動作確認

### 3. 注意事項
- **PHPファイルのパーミッション**: 644
- **ディレクトリのパーミッション**: 755
- **PHPバージョン**: 8.1以上に設定

## 📝 記事の追加方法

### お知らせ記事の追加
1. `news/data/articles.json` を編集
2. 以下の形式で記事を追加：

```json
{
  "id": 6,
  "title": "記事タイトル",
  "slug": "article-slug",
  "content": "<p>記事本文（HTMLタグ使用可）</p>",
  "publishedAt": "2025-11-12T10:00:00+09:00",
  "updatedAt": "2025-11-12T10:00:00+09:00",
  "category": "お知らせ",
  "thumbnail": ""
}
```

3. ファイル保存後、Gitでプッシュ

## 🔧 設定ファイルの編集

### メール設定（php/config.php）
```php
define('ADMIN_EMAIL', 'yamada1881r@gmail.com');  // 受信先
define('FROM_EMAIL', 'noreply@yojitu.com');     // 送信元
```

### サイト情報
```php
define('SITE_NAME', '余日（Yojitsu）');
define('SITE_URL', 'https://yojitu.com');
define('SITE_EMAIL', 'yamada1881r@gmail.com');
define('SITE_TEL', '080-4692-9681');
```

## 📊 アクセス解析設定

### Google Analytics
- GTMタグID: `GTM-WZX7SZ3V`（既に実装済み）

### Google Search Console
1. プロパティ追加: `https://yojitu.com`
2. サイトマップ送信: 手動で作成する必要があります（現在自動生成未実装）

## 🔒 セキュリティ

### 実装済み対策
- XSS対策（HTMLエスケープ関数 `h()`）
- セキュリティヘッダー（.htaccess）
- メールインジェクション対策
- フォームバリデーション

### 推奨事項
- 本番環境では `php/config.php` のエラー表示をOFFに：
```php
ini_set('display_errors', 0);
```

## 📞 お問い合わせ

- **Email**: yamada1881r@gmail.com
- **Tel**: 080-4692-9681
- **LINE**: https://line.me/ti/p/CTOCx9YKjk

## 📄 ライセンス

© 2025 余日（Yojitsu）. All rights reserved.

---

## 🛠️ 開発メモ

### 今後の実装予定
- [ ] Sitemap自動生成（PHP）
- [ ] RSS配信
- [ ] ブログ機能
- [ ] 管理画面（記事追加UI）
- [ ] 画像最適化（WebP対応）

### リンク構造
- 相対パス使用
- トップ: `index.html`
- サービス: `services.html`
- 会社概要: `about.html`
- お問い合わせ: `contact.html`
- お知らせ一覧: `news/`
- お知らせ詳細: `news/detail.php?id=1`

### カスタマイズ
すべてのHTML/CSS/JSはファイル分離されており、各ファイルを編集することでカスタマイズ可能です。
