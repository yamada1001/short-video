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
├── privacy.html            # プライバシーポリシー (予定)
├── tokushoho.html          # 特定商取引法 (予定)
├── assets/
│   ├── css/
│   │   ├── reset.css       # リセットCSS
│   │   ├── common.css      # 共通スタイル (MUJI風デザイン)
│   │   ├── components.css  # コンポーネント (MUJI風デザイン)
│   │   └── pages/          # ページ別CSS
│   │       ├── top.css
│   │       ├── about.css
│   │       ├── services.css
│   │       ├── contact.css
│   │       └── blog.css    # ブログ専用CSS
│   ├── js/
│   │   ├── common.js               # 共通機能
│   │   ├── nav.js                  # ナビゲーション
│   │   ├── fontawesome-init.js     # Font Awesome
│   │   └── form-validation.js      # フォームバリデーション
│   └── images/             # 画像ファイル
├── news/
│   ├── index.php           # お知らせ一覧
│   ├── detail.php          # お知らせ詳細
│   └── data/
│       └── articles.json   # 記事データ
├── blog/                   # ブログ機能
│   ├── index.php           # ブログ一覧
│   ├── detail.php          # ブログ詳細
│   └── data/
│       └── posts.json      # ブログ記事データ
├── includes/               # 共通PHPファイル (旧 php/)
│   ├── config.php          # 設定ファイル
│   ├── functions.php       # 共通関数
│   ├── contact-form.php    # お問い合わせ処理
│   ├── sitemap-generator.php    # Sitemap自動生成 (予定)
│   └── rss-generator.php        # RSS配信 (予定)
├── robots.txt
├── .htaccess
├── .gitignore
└── README.md
```

## 🎨 デザインコンセプト

### 無印良品風ミニマルデザイン
- 余白を贅沢に使った洗練されたレイアウト
- 自然素材を思わせるアースカラー
- レスポンシブデザイン（モバイルファースト）
- "Less is more" - 無駄を削ぎ落としたデザイン

### カラーパレット
- Beige: `#E5DDD5` (ベースカラー)
- Off White: `#F5F3F0` (背景)
- Natural Brown: `#8B7355` (アクセント)
- Charcoal: `#4A4A4A` (テキスト・フッター)
- Light Gray: `#E8E8E8` (サブ背景)

### タイポグラフィ
- 日本語: Noto Sans JP (400, 500のみ)
- フォントウェイト: 基本400、強調500まで（700以上は使用禁止）
- レターススペーシング: 0.08em - 0.15em（広め）
- 行間: 1.9 - 2.0（ゆったり）

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
- ✅ JSONベースの記事管理（お知らせ・ブログ）
- ✅ ページネーション
- ✅ ブログ機能（カテゴリフィルター・タグ機能）

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
- [ ] 管理画面（記事追加UI）
- [ ] 画像最適化（WebP対応）
- [ ] ショート動画セクションの拡充

### 手動作成が必要なファイル

以下のHTMLファイルは、テンプレートをコピーして手動で作成してください：

#### 1. privacy.html（プライバシーポリシー）
以下の内容で作成してください（tokushoho.htmlと同じ構造）：

主な記載内容：
- 個人情報の定義
- 個人情報の収集方法（お問い合わせフォーム、メール・電話・LINE等）
- 個人情報の利用目的（お問い合わせ対応、サービス提供、契約履行等）
- 個人情報の第三者提供（法令に基づく場合を除き提供しない）
- 個人情報の安全管理
- Cookieの使用について
- Google Analyticsの使用について
- お問い合わせ窓口（yamada1881r@gmail.com / 080-4692-9681）
- 制定日：2025年11月12日

#### 2. tokushoho.html（特定商取引法）
以下の内容で作成してください：

```html
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>特定商取引法に基づく表記 | 余日（Yojitsu）</title>
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/common.css">
    <link rel="stylesheet" href="assets/css/components.css">
</head>
<body>
    <header class="header">
        <div class="container header__container">
            <a href="index.html" class="header__logo">余日</a>
            <nav class="nav">
                <ul class="nav__list">
                    <li><a href="index.html#services" class="nav__link">サービス</a></li>
                    <li><a href="news/" class="nav__link">お知らせ</a></li>
                    <li><a href="blog/" class="nav__link">ブログ</a></li>
                    <li><a href="about.html" class="nav__link">会社概要</a></li>
                    <li><a href="contact.html" class="nav__link">お問い合わせ</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="section section--white" style="padding: 80px 0;">
        <div class="container container--narrow">
            <h1 class="section__title">特定商取引法に基づく表記</h1>

            <table style="width: 100%; border: 1px solid var(--color-border); margin-top: 40px;">
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; width: 30%; text-align: left; font-weight: 400;">事業者名</th>
                    <td style="padding: 16px;">余日（Yojitsu）</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">代表者</th>
                    <td style="padding: 16px;">山田 蓮</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">所在地</th>
                    <td style="padding: 16px;">大分県（オンライン対応）</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">電話番号</th>
                    <td style="padding: 16px;">080-4692-9681</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">メールアドレス</th>
                    <td style="padding: 16px;">yamada1881r@gmail.com</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">登録番号</th>
                    <td style="padding: 16px;">適格請求書発行事業者 T9810094141774</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">サービス内容</th>
                    <td style="padding: 16px;">SEO対策、Web広告運用、Webサイト制作、ショート動画制作、デジタルマーケティングコンサルティング</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">販売価格</th>
                    <td style="padding: 16px;">各サービスページに記載の通り。お見積もりにより個別に提示いたします。</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">支払方法</th>
                    <td style="padding: 16px;">銀行振込、請求書払い</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">支払時期</th>
                    <td style="padding: 16px;">契約締結後、請求書発行日より30日以内</td>
                </tr>
                <tr style="border-bottom: 1px solid var(--color-border);">
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">サービス提供時期</th>
                    <td style="padding: 16px;">契約締結後、個別に定める納期による</td>
                </tr>
                <tr>
                    <th style="background-color: var(--color-beige); padding: 16px; text-align: left; font-weight: 400;">返品・キャンセル</th>
                    <td style="padding: 16px;">サービスの性質上、原則として返品・返金には応じられません。契約内容の変更については個別にご相談ください。</td>
                </tr>
            </table>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h3 class="footer__section-title">余日（Yojitsu）</h3>
                    <p style="color: rgba(255, 255, 255, 0.8);">デジタルマーケティング・Web制作会社</p>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 余日（Yojitsu）. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/fontawesome-init.js"></script>
    <script src="assets/js/nav.js"></script>
    <script src="assets/js/common.js"></script>
</body>
</html>
```

#### 3. 404.html（404エラーページ）
シンプルな404エラーページ（テンプレートは割愛）

#### 4. recruit.html（リクルート・ネットワーク）
業務委託募集と大分での交流希望を記載したページ

**募集内容：**
1. **動画撮影者**（大分県内在住）
   - TikTok・Instagram・YouTubeショート向け撮影
   - 業務委託契約

2. **動画編集者**（リモートOK）
   - ショート動画の編集業務
   - 全国どこでも可

3. **大分での交流希望**
   - デジタルマーケティング・Web制作関連の方
   - 情報交換や協業の可能性を探りたい方

**お問い合わせ方法：**
- メール：yamada1881r@gmail.com
- 電話：080-4692-9681
- LINE：https://line.me/ti/p/CTOCx9YKjk
- 件名に「業務委託応募」または「交流希望」と記載

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
