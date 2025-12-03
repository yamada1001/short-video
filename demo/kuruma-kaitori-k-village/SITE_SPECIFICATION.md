# サイト制作仕様書：くるま買取ケイヴィレッジ

## 1. プロジェクト概要

### クライアント情報
- **屋号**: くるま買取ケイヴィレッジ
- **所在地**: 〒870-1113 大分県大分市中判田１５９２‐１
- **事業内容**: 新車販売・中古車販売・買取・車検・整備・板金・オートローン・新車リース
- **従業員数**: ２名
- **古物商許可番号**: ９４１１９０００１１１６

### サイト目的
1. 車買取・販売の新規顧客獲得
2. 車検・整備・板金サービスの認知向上
3. 電話・フォームからの問い合わせ促進
4. 地域での信頼性向上

### ターゲットユーザー
- **主要ターゲット**: 大分市内在住の車所有者（30-60代）
- **サブターゲット**: 車検・整備を検討している方、新車・中古車購入希望者

---

## 2. ディレクトリ構造

```
demo/kuruma-kaitori-k-village/
├── index.php                      # トップページ
├── about.php                      # 会社概要
├── contact.php                    # お問い合わせ
├── news.php                       # お知らせ一覧
├── news-detail.php                # お知らせ詳細
├── privacy.php                    # プライバシーポリシー
├── sitemap.php                    # サイトマップ
├── tokushoho.php                  # 特定商取引法に基づく表記
│
├── data/
│   ├── config.php                 # サイト基本設定
│   ├── services.php               # サービス情報
│   ├── news.php                   # お知らせデータ
│   └── meta.php                   # 各ページのmeta情報
│
├── includes/
│   ├── header.php                 # ヘッダー
│   ├── footer.php                 # フッター
│   └── functions.php              # 共通関数
│
├── sections/
│   ├── hero.php                   # メインビジュアル
│   ├── services-overview.php     # サービス概要
│   ├── strengths.php              # 選ばれる理由
│   ├── cta-contact.php            # お問い合わせCTA
│   └── company-info.php           # 会社情報
│
├── assets/
│   ├── css/
│   │   ├── reset.css              # CSSリセット
│   │   ├── variables.css          # CSS変数
│   │   ├── common.css             # 共通スタイル
│   │   ├── components.css         # コンポーネント
│   │   ├── header.css             # ヘッダー
│   │   ├── footer.css             # フッター
│   │   ├── index.css              # トップページ
│   │   ├── about.css              # 会社概要
│   │   ├── contact.css            # お問い合わせ
│   │   └── news.css               # お知らせ
│   │
│   ├── js/
│   │   ├── common.js              # 共通JS
│   │   ├── form-validation.js    # フォームバリデーション
│   │   └── news.js                # お知らせ用JS
│   │
│   └── images/
│       ├── logo.svg               # ロゴ（SVG）
│       ├── favicon.ico            # ファビコン
│       ├── hero/                  # メインビジュアル
│       ├── services/              # サービス画像
│       ├── staff/                 # スタッフ写真
│       ├── shop/                  # 店舗写真
│       └── cars/                  # 車両写真
│
├── CLIENT_INFO.md
├── DIRECTORY_STRUCTURE.md
└── SITE_SPECIFICATION.md          # 本ファイル
```

---

## 3. デザイン仕様

### 3.1 カラースキーム（信頼感重視・ブルー系）

```css
/* プライマリカラー（メイン） */
--primary-color: #2563eb;          /* 鮮やかなブルー */
--primary-dark: #1e40af;           /* 濃いブルー（ホバー時） */
--primary-light: #dbeafe;          /* 薄いブルー（背景） */

/* セカンダリカラー（アクセント） */
--secondary-color: #f59e0b;        /* オレンジ（CTA、重要情報） */
--secondary-dark: #d97706;         /* 濃いオレンジ（ホバー時） */

/* テキストカラー */
--text-primary: #1f2937;           /* 濃いグレー（本文） */
--text-secondary: #6b7280;         /* 中間グレー（補足） */
--text-light: #9ca3af;             /* 薄いグレー（注釈） */

/* 背景カラー */
--bg-white: #ffffff;               /* 白（基本背景） */
--bg-gray: #f3f4f6;                /* 薄いグレー（セクション背景） */
--bg-dark: #111827;                /* 濃いグレー（フッター） */

/* ステータスカラー */
--success-color: #10b981;          /* 緑（成功メッセージ） */
--error-color: #ef4444;            /* 赤（エラーメッセージ） */
--warning-color: #f59e0b;          /* オレンジ（警告） */
```

### 3.2 タイポグラフィ（こだわり重視）

#### フォントファミリー

**日本語フォント**
```css
font-family: 'Noto Sans JP', 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3',
             'メイリオ', Meiryo, sans-serif;
```

**Google Fonts読み込み**
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
```

**ウェイト（太さ）**
- `400`: Regular（本文）
- `500`: Medium（サブ見出し、ボタン）
- `700`: Bold（見出し）
- `900`: Black（大見出し、キャッチコピー）

#### フォントサイズ

```css
/* 見出し */
--font-size-h1: 48px;              /* ページタイトル */
--font-size-h2: 36px;              /* セクション見出し */
--font-size-h3: 28px;              /* サブセクション見出し */
--font-size-h4: 24px;              /* カード見出し */

/* 本文 */
--font-size-base: 16px;            /* 通常本文 */
--font-size-large: 18px;           /* リード文 */
--font-size-small: 14px;           /* 注釈、フッター */

/* レスポンシブ（SP） */
@media (max-width: 768px) {
  --font-size-h1: 32px;
  --font-size-h2: 28px;
  --font-size-h3: 24px;
  --font-size-h4: 20px;
  --font-size-base: 15px;
  --font-size-large: 16px;
}
```

#### 行間・文字間

```css
--line-height-tight: 1.3;          /* 見出し */
--line-height-base: 1.7;           /* 本文 */
--line-height-loose: 2.0;          /* リード文 */

--letter-spacing-tight: -0.02em;   /* 見出し */
--letter-spacing-base: 0;          /* 本文 */
--letter-spacing-wide: 0.05em;     /* ボタン、ラベル */
```

### 3.3 アイコン（SVG / Font Awesome）

#### Font Awesome 6（無料版）

```html
<!-- CDN読み込み -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
```

**使用アイコン例**
- 📞 電話: `<i class="fa-solid fa-phone"></i>`
- ✉️ メール: `<i class="fa-solid fa-envelope"></i>`
- 📍 場所: `<i class="fa-solid fa-location-dot"></i>`
- 🕒 時計: `<i class="fa-solid fa-clock"></i>`
- 🚗 車: `<i class="fa-solid fa-car"></i>`
- 🔧 整備: `<i class="fa-solid fa-wrench"></i>`
- ✅ チェック: `<i class="fa-solid fa-check"></i>`
- ➡️ 矢印: `<i class="fa-solid fa-arrow-right"></i>`

#### カスタムSVGアイコン

サービスアイコンは独自SVGを使用（より個性的に）:
- 買取: `assets/images/icons/kaitori.svg`
- 販売: `assets/images/icons/hanbai.svg`
- 車検: `assets/images/icons/shaken.svg`
- 整備: `assets/images/icons/seibi.svg`
- 板金: `assets/images/icons/bankin.svg`
- リース: `assets/images/icons/lease.svg`

---

## 4. ページ構成

### 4.1 トップページ（index.php）

#### セクション構成

1. **Hero（メインビジュアル）** - `sections/hero.php`
   - キャッチコピー: 「大分で車のことなら、ケイヴィレッジへ」
   - サブコピー: 「買取・販売・車検・整備まで、すべてお任せください」
   - 背景: 車の画像（店舗前など）
   - CTAボタン: 「無料査定を申し込む」「お問い合わせ」

2. **サービス概要** - `sections/services-overview.php`
   - 6つのサービスをカード形式で表示
   - 各カードにSVGアイコン、見出し、簡単な説明

3. **選ばれる理由（強み）** - `sections/strengths.php`
   - 3-4つの強みを提示
   - 例: 「地域密着」「誠実対応」「ワンストップサービス」「資格所有者在籍」

4. **新着お知らせ** - 3件表示
   - 日付、タイトル、カテゴリー
   - 「一覧を見る」リンク

5. **お問い合わせCTA** - `sections/cta-contact.php`
   - 電話番号を大きく表示
   - 「無料査定フォーム」へのリンク

6. **会社情報・アクセス** - `sections/company-info.php`
   - 会社概要（簡易版）
   - Googleマップ埋め込み
   - 営業時間

### 4.2 会社概要（about.php）

- 会社情報（屋号、所在地、事業内容、古物商許可番号）
- スタッフ紹介（木村さん）
- 事業理念・メッセージ
- アクセスマップ
- 営業時間・定休日

### 4.3 お問い合わせ（contact.php）

- 無料査定フォーム（詳細は後述）
- 電話番号（大きく表示、SPでタップでかかる）
- 営業時間
- 所在地

### 4.4 お知らせ一覧（news.php）

- お知らせ一覧（10件/ページ）
- カテゴリー絞り込み（「お知らせ」「キャンペーン」「営業情報」）
- ページネーション

### 4.5 お知らせ詳細（news-detail.php）

- タイトル、日付、カテゴリー
- 本文
- 前の記事・次の記事リンク

### 4.6 プライバシーポリシー（privacy.php）

- 個人情報の取り扱いに関する方針
- テンプレート使用

### 4.7 サイトマップ（sitemap.php）

- サイト内の全ページリンク

### 4.8 特定商取引法に基づく表記（tokushoho.php）

- 販売業者情報
- 責任者、所在地、電話番号など

---

## 5. フォーム設計（重要）

### 5.1 お問い合わせフォームの種類

#### パターンA: 統合型フォーム（推奨）
1つのフォームで「買取」「購入」「車検・整備」「その他」を選択式にする。

#### パターンB: 分離型フォーム
- 買取専用フォーム
- 購入専用フォーム
- 車検・整備専用フォーム

**→ 今回は「パターンA: 統合型フォーム」を採用**
理由: 小規模事業者のため管理しやすく、ユーザーも迷わない

---

### 5.2 フォーム項目設計

#### 基本項目（すべての問い合わせで共通）

| 項目 | 必須 | 入力タイプ | バリデーション |
|------|------|-----------|--------------|
| お名前 | ✅ | text | 1文字以上 |
| ふりがな | ✅ | text | ひらがなのみ |
| 電話番号 | ✅ | tel | 10-11桁の数字、ハイフン自動挿入 |
| メールアドレス | ✅ | email | メール形式チェック |
| お問い合わせ種別 | ✅ | select | 「買取」「購入（新車）」「購入（中古車）」「車検・整備・板金」「リース」「その他」 |

---

#### 買取の場合の追加項目

| 項目 | 必須 | 入力タイプ | バリデーション |
|------|------|-----------|--------------|
| メーカー | ✅ | select | 主要メーカー（トヨタ、ホンダ、日産など） |
| 車種 | ✅ | text | - |
| 年式 | ✅ | select | 過去30年分（例: 2024年、2023年...） |
| 走行距離 | ⚪ | text | 数字のみ、単位は「km」 |
| 車検有効期限 | ⚪ | date | YYYY-MM-DD形式 |
| 希望査定方法 | ✅ | radio | 「店頭持ち込み」「出張査定」 |

---

#### 購入（新車・中古車）の場合の追加項目

| 項目 | 必須 | 入力タイプ | バリデーション |
|------|------|-----------|--------------|
| 希望車種 | ⚪ | text | - |
| 希望メーカー | ⚪ | select | - |
| 予算 | ⚪ | select | 「50万円以下」「50-100万円」「100-200万円」「200万円以上」「未定」 |
| 希望納期 | ⚪ | select | 「1ヶ月以内」「2-3ヶ月」「3ヶ月以上」「未定」 |

---

#### 車検・整備・板金の場合の追加項目

| 項目 | 必須 | 入力タイプ | バリデーション |
|------|------|-----------|--------------|
| サービス内容 | ✅ | checkbox | 「車検」「点検・整備」「板金・塗装」「その他」（複数選択可） |
| 車種 | ✅ | text | - |
| 車検有効期限 | ⚪ | date | YYYY-MM-DD形式（車検の場合のみ） |
| 希望日時 | ⚪ | date | - |

---

#### リースの場合の追加項目

| 項目 | 必須 | 入力タイプ | バリデーション |
|------|------|-----------|--------------|
| 希望車種 | ⚪ | text | - |
| リース期間 | ⚪ | select | 「3年」「5年」「7年」「未定」 |
| 月額予算 | ⚪ | select | 「2万円以下」「2-3万円」「3-5万円」「5万円以上」「未定」 |

---

#### 共通項目（すべての問い合わせ）

| 項目 | 必須 | 入力タイプ | バリデーション |
|------|------|-----------|--------------|
| お問い合わせ内容 | ⚪ | textarea | - |
| 個人情報の取り扱い | ✅ | checkbox | チェック必須（同意確認） |

---

### 5.3 フォームのUX設計

#### 動的フォーム切り替え
- 「お問い合わせ種別」を選択すると、該当する追加項目が表示される
- JavaScriptで動的に表示/非表示を切り替え

例:
```javascript
// 買取を選択 → 買取用項目が表示
// 購入を選択 → 購入用項目が表示
```

#### バリデーション
- **リアルタイムバリデーション**: フォーカスアウト時にエラー表示
- **送信前チェック**: 送信ボタン押下時に全項目チェック
- **エラー表示**: 項目の下に赤文字でメッセージ表示

#### 確認画面
- フォーム送信前に確認画面を表示（オプション）
- 送信内容を確認してから送信

#### 送信後
- サンクスページ（contact-thanks.php）にリダイレクト
- 「お問い合わせありがとうございます。担当者より折り返しご連絡いたします。」

---

### 5.4 フォーム送信先

#### メール送信
- PHPの`mail()`関数またはPHPMailerを使用
- 送信先: クライアントのメールアドレス（ヒアリング要）
- 自動返信メール: お客様にも確認メールを送信

#### データ保存（オプション）
- JSON形式で`data/contacts/`に保存（`.gitignore`に追加）
- 管理画面は今回非実装（将来的に追加可能）

---

## 6. レスポンシブデザイン

### ブレークポイント

```css
/* Mobile First */
/* SP: 〜767px（デフォルト） */

/* Tablet: 768px〜1023px */
@media (min-width: 768px) { ... }

/* PC: 1024px〜 */
@media (min-width: 1024px) { ... }

/* Large PC: 1280px〜 */
@media (min-width: 1280px) { ... }
```

### SPでの重要要素
- **電話ボタン**: 固定フッターまたは画面下部に大きく配置
- **タップ領域**: ボタンは最低44x44px
- **フォント**: SP版は1px小さく（読みやすさ優先）

---

## 7. SEO対策

### meta情報（data/meta.php）

```php
$meta = [
    'index' => [
        'title' => '大分の車買取・販売・車検ならくるま買取ケイヴィレッジ',
        'description' => '大分市中判田の車買取・販売・車検専門店。新車・中古車販売、買取、車検、整備、板金、リースまで幅広く対応。誠実対応で地域の皆様に選ばれています。',
        'keywords' => '大分,車買取,中古車販売,車検,整備,板金,中判田,ケイヴィレッジ'
    ],
    // 各ページのmeta情報
];
```

### 構造化データ（JSON-LD）
- LocalBusiness（会社情報）
- PostalAddress（所在地）
- OpeningHours（営業時間）

---

## 8. パフォーマンス最適化

### 画像最適化
- WebP形式を優先使用
- 適切なサイズにリサイズ
- 遅延読み込み（Lazy Loading）

### CSS/JS最適化
- 本番環境では圧縮版を使用
- 不要なコメント削除

### Google Fonts最適化
- `&display=swap`で高速表示
- 使用するウェイトのみ読み込み

---

## 9. 技術スタック（確定版）

### フロントエンド

#### HTML
- HTML5（セマンティックタグ使用）
- PHP include で コンポーネント分割

#### CSS
- **Pure CSS / Vanilla CSS**（フレームワーク不使用）
- CSS Variables（カスタムプロパティ）使用
- Mobile First設計
- BEM記法（Block Element Modifier）推奨

#### JavaScript
- **Vanilla JavaScript**（フレームワーク不使用）
- ES6+モジュール構文
- 必要最小限の実装（軽量化重視）

---

### アイコン・ビジュアル

#### SVGアイコン
- **Font Awesome 6（SVG版）** - メインアイコンライブラリ
- CDN読み込み（最新版）
```html
<!-- Font Awesome 6 Free -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
```

**使用可能アイコン（無料版）**
- Solid（塗りつぶし）: 2,000+ アイコン
- Regular（線のみ）: 一部アイコン
- Brands（ブランドロゴ）: SNSアイコン等

**主要アイコン**
```html
<!-- 電話 -->
<i class="fa-solid fa-phone"></i>

<!-- メール -->
<i class="fa-solid fa-envelope"></i>

<!-- 場所 -->
<i class="fa-solid fa-location-dot"></i>

<!-- 時計 -->
<i class="fa-solid fa-clock"></i>

<!-- 車 -->
<i class="fa-solid fa-car"></i>

<!-- 整備・工具 -->
<i class="fa-solid fa-wrench"></i>

<!-- チェック -->
<i class="fa-solid fa-check"></i>

<!-- 矢印 -->
<i class="fa-solid fa-arrow-right"></i>

<!-- 星（評価用） -->
<i class="fa-solid fa-star"></i>

<!-- ユーザー -->
<i class="fa-solid fa-user"></i>

<!-- カレンダー -->
<i class="fa-solid fa-calendar"></i>
```

#### カスタムSVG（必要に応じて）
サービス固有のアイコンは手動作成も検討:
- 買取アイコン
- 販売アイコン
- 車検アイコン
- リースアイコン

---

### バックエンド

#### PHP
- **バージョン**: PHP 7.4+（8.0+ 推奨）
- **用途**:
  - コンポーネントの読み込み（include/require）
  - フォーム送信処理
  - お知らせデータの読み込み

#### PHPMailer
- **バージョン**: 6.9+（最新版）
- **導入方法**: Composer または手動ダウンロード
- **機能**:
  - SMTP対応
  - HTML形式メール送信
  - 自動返信メール
  - 添付ファイル対応（将来的に）

**Composer導入コマンド**
```bash
composer require phpmailer/phpmailer
```

**手動導入**
1. https://github.com/PHPMailer/PHPMailer からダウンロード
2. `includes/PHPMailer/` に配置
3. `require 'includes/PHPMailer/src/PHPMailer.php';` で読み込み

**基本設定例**
```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';
$mail->isSMTP();
$mail->Host = 'smtp.example.com';
$mail->SMTPAuth = true;
$mail->Username = 'user@example.com';
$mail->Password = 'password';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;
```

---

### フォント

#### Google Fonts
- **Noto Sans JP**（日本語）
- **ウェイト**: 400, 500, 700, 900

```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&display=swap" rel="stylesheet">
```

**CSS適用**
```css
body {
  font-family: 'Noto Sans JP', 'Hiragino Kaku Gothic ProN', 'ヒラギノ角ゴ ProN W3', 'メイリオ', Meiryo, sans-serif;
}
```

---

### 画像・メディア

#### 画像形式
- **WebP**: 最優先（軽量・高品質）
- **JPEG**: WebP非対応ブラウザ用フォールバック
- **SVG**: ロゴ、アイコン

#### 画像最適化ツール
- **Squoosh**: https://squoosh.app/ （ブラウザ上で圧縮）
- **TinyPNG**: https://tinypng.com/ （PNG/JPEG圧縮）

#### 推奨サイズ
- ロゴ: 300x100px（SVG推奨）
- ヒーロー背景: 1920x1080px（WebP）
- サービス画像: 600x400px（WebP）
- スタッフ写真: 400x400px（JPEG）
- 店舗写真: 800x600px（WebP）

#### 画像素材について
- **ネット上の店舗画像**: 利用許諾取得済み（使用OK）
- **収集方法**: Google画像検索、SNS、口コミサイト等
- **著作権**: クライアント様から使用許可済み
- **注意事項**: 第三者の肖像権・プライバシーに配慮

---

### データ管理

#### お知らせデータ
- **形式**: PHP配列（`data/news.php`）
- **JSON形式も検討可**（将来的にCMS化しやすい）

```php
// data/news.php
$news = [
    [
        'id' => 1,
        'date' => '2025-12-03',
        'category' => 'お知らせ',
        'title' => '年末年始の営業時間について',
        'content' => '...',
        'slug' => 'news-001'
    ],
    // ...
];
```

#### フォーム送信データ
- **保存先**: `data/contacts/`（オプション）
- **形式**: JSON
- **ファイル名**: `contact_YYYYMMDD_HHMMSS.json`
- **Git管理**: `.gitignore` に追加

---

### 開発環境

#### 必須ツール
- PHP 7.4+（8.0+ 推奨）
- モダンブラウザ（Chrome, Firefox, Safari, Edge）
- テキストエディタ（VSCode推奨）

#### 推奨VSCode拡張機能
- PHP Intelephense
- Live Server
- Auto Rename Tag
- Prettier - Code formatter

#### ローカル開発サーバー
- **PHP内蔵サーバー**:
```bash
cd demo/kuruma-kaitori-k-village
php -S localhost:8000
```

- **MAMP / XAMPP**: GUIで簡単に環境構築

---

### デプロイ環境

#### 本番サーバー要件
- PHP 7.4+
- Apache / Nginx
- メール送信機能（mail() または SMTP）
- SSL証明書（Let's Encrypt推奨）

#### .htaccess設定
- mod_rewrite有効化（URL書き換え用）
- Gzip圧縮有効化
- ブラウザキャッシュ設定

---

### セキュリティ

#### XSS対策
- PHPの`htmlspecialchars()`でエスケープ
- ユーザー入力は常にサニタイズ

#### CSRF対策
- トークン生成・検証
- セッション管理

#### SQLインジェクション対策
- 今回はDB不使用のため該当なし
- 将来的にDB導入時はPDOのプリペアドステートメント使用

---

### パフォーマンス最適化

#### CSS/JS
- 本番環境では圧縮版を使用
- 不要なコメント削除
- Critical CSS（初回表示に必要なCSS）をインライン化

#### 画像
- WebP形式優先
- 遅延読み込み（Lazy Loading）
```html
<img src="image.webp" loading="lazy" alt="...">
```

#### Google Fonts
- `&display=swap`で高速表示
- 使用するウェイトのみ読み込み

#### Gzip圧縮
- Apache: `.htaccess`で有効化
```apache
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/css text/javascript application/javascript
</IfModule>
```

---

## 10. 今後のヒアリング事項

### クライアントに確認が必要な項目

- [ ] 営業時間・定休日
- [ ] 電話番号（サイト掲載用）
- [ ] メールアドレス（フォーム送信先）
- [ ] ロゴデータ（SVG推奨）
- [ ] 写真素材（店舗外観、内観、スタッフ、車両）
- [ ] お客様の声（あれば）
- [ ] 希望するデザインのイメージ（参考サイト）
- [ ] LINE ID（あれば）
- [ ] SNSアカウント（あれば）
- [ ] 既存サイトの有無
- [ ] 予算・納期

---

## 11. 開発中の特別仕様

### 11.1 キャッシュ無効化機能（開発中のみ）

#### 目的
開発中はリロードするたびに最新のファイルを表示するため、ブラウザキャッシュを無効化。

#### 実装箇所と内容

**1. includes/header.php - HTTPヘッダー（行11-15）**
```php
// 開発中: キャッシュ無効化
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
```

**2. includes/header.php - メタタグ（行24-27）**
```html
<!-- 開発中: キャッシュ無効化 -->
<meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
```

**3. includes/functions.php - asset()関数（行143-151）**
```php
function asset($path) {
    $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    $url = $base_path . '/' . ltrim($path, '/');

    // 開発中: キャッシュバスター（タイムスタンプ）を追加
    $version = time();
    $separator = (strpos($url, '?') === false) ? '?' : '&';
    return $url . $separator . 'v=' . $version;
}
```

#### 効果
- HTMLページ: HTTPヘッダーとメタタグでブラウザキャッシュ完全無効化
- CSS/JSファイル: `?v={timestamp}` パラメータで毎回異なるURLとして読み込み
- 例: `assets/css/common.css?v=1733123456`
- リロードするたびに常に最新版が表示される

#### ⚠️ 重要: 本番環境移行時の対応
**開発終了後、必ず以下の設定を削除してください:**

1. **includes/header.php の修正**:
   - 行11-15の HTTPヘッダー設定を削除
   - 行24-27の メタタグを削除

2. **includes/functions.php の修正**:
   - asset()関数からキャッシュバスター部分を削除
   - 元の形に戻す:
   ```php
   function asset($path) {
       $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
       return $base_path . '/' . ltrim($path, '/');
   }
   ```

3. **本番環境では逆にキャッシュを有効化**:
   - .htaccessでブラウザキャッシュ設定を追加
   - CSS/JSファイルにバージョン番号を付ける（例: `common.css?v=1.0.0`）
   - Gzip圧縮を有効化

#### 削除を忘れた場合の影響
- ページ読み込み速度が遅くなる
- サーバー負荷が増加する
- ユーザーエクスペリエンスが低下する

---

最終更新: 2025-12-03
作成者: YOJITU.COM
