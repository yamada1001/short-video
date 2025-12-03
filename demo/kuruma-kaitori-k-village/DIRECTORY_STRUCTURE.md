# ディレクトリ構造：くるま買取ケイヴィレッジ

## 現在のディレクトリ構造

```
demo/kuruma-kaitori-k-village/
├── CLIENT_INFO.md                    # クライアント情報・案件管理
├── DIRECTORY_STRUCTURE.md            # 本ファイル（構造説明）
├── index.php                         # トップページ
├── services.php                      # サービス紹介
├── about.php                         # 会社概要
├── contact.php                       # お問い合わせ
├── .htaccess                         # Basic認証（オプション）
├── .htpasswd                         # パスワードファイル（オプション）
│
└── assets/
    ├── css/
    │   ├── reset.css                 # CSSリセット
    │   ├── variables.css             # CSS変数（カラー、フォントなど）
    │   ├── common.css                # 共通スタイル（ヘッダー・フッターなど）
    │   ├── components.css            # コンポーネントスタイル（ボタン、カードなど）
    │   ├── index.css                 # トップページ専用スタイル
    │   ├── services.css              # サービス紹介専用スタイル
    │   ├── about.css                 # 会社概要専用スタイル
    │   └── contact.css               # お問い合わせ専用スタイル
    │
    ├── js/
    │   ├── common.js                 # 共通JS（ヘッダー、スムーススクロールなど）
    │   ├── form-validation.js        # フォームバリデーション
    │   └── carousel.js               # カルーセル（スライダー）
    │
    └── images/
        ├── logo.png                  # ロゴ画像
        ├── favicon.ico               # ファビコン
        ├── hero-bg.jpg               # メインビジュアル背景
        ├── services/                 # サービス紹介用画像
        │   ├── kaitori.jpg
        │   ├── hanbai.jpg
        │   ├── shaken.jpg
        │   └── ...
        ├── staff/                    # スタッフ写真
        │   └── kimura.jpg
        ├── shop/                     # 店舗写真
        │   ├── exterior.jpg
        │   └── interior.jpg
        └── cars/                     # 車両写真（在庫など）
            └── ...
```

## ファイル詳細

### ルートファイル

#### index.php（トップページ）
- メインビジュアル（ヒーロー）
- サービス紹介（カード形式）
- 選ばれる理由（3-4つ）
- お客様の声（あれば）
- 無料査定フォーム（CTA）
- アクセス・営業時間

#### services.php（サービス紹介）
- 買取
- 新車販売
- 中古車販売
- 車検・整備
- 板金
- オートローン
- 新車リース
各サービスの詳細説明、料金目安（あれば）

#### about.php（会社概要）
- 屋号
- 所在地
- 事業内容
- 古物商許可番号
- スタッフ紹介（木村さん）
- Googleマップ埋め込み

#### contact.php（お問い合わせ）
- 無料査定フォーム
  - お名前
  - 電話番号
  - メールアドレス
  - 車種・年式
  - 希望サービス
  - メッセージ
- 電話番号（大きく）
- 営業時間・定休日
- LINE ID（あれば）

### CSS構成

#### reset.css
```css
/* モダンCSSリセット */
*, *::before, *::after {
  box-sizing: border-box;
}
body, h1, h2, h3, p, ul, li {
  margin: 0;
  padding: 0;
}
...
```

#### variables.css
```css
:root {
  /* カラー */
  --primary-color: #2563eb;      /* ブルー */
  --secondary-color: #f97316;    /* オレンジ */
  --text-color: #1f2937;
  --bg-color: #ffffff;
  --gray-100: #f3f4f6;

  /* フォント */
  --font-base: 'Noto Sans JP', sans-serif;
  --font-size-base: 16px;

  /* スペーシング */
  --spacing-xs: 8px;
  --spacing-sm: 16px;
  --spacing-md: 24px;
  --spacing-lg: 48px;
}
```

#### common.css
- ヘッダー（ロゴ、ナビゲーション）
- フッター（コピーライト、リンク）
- コンテナ幅
- レスポンシブブレークポイント

#### components.css
- ボタン（プライマリ、セカンダリ）
- カード
- フォーム要素
- セクション見出し

### JavaScript構成

#### common.js
```javascript
// ヘッダー固定
// スムーススクロール
// ハンバーガーメニュー（SP）
// ページトップボタン
```

#### form-validation.js
```javascript
// フォームバリデーション
// 電話番号フォーマット
// メールアドレス検証
// 必須項目チェック
```

## レスポンシブ対応

### ブレークポイント
```css
/* Mobile First */
/* SP: 〜767px（デフォルト） */

/* Tablet: 768px〜 */
@media (min-width: 768px) { ... }

/* PC: 1024px〜 */
@media (min-width: 1024px) { ... }

/* Large PC: 1280px〜 */
@media (min-width: 1280px) { ... }
```

## 画像最適化

### 推奨サイズ
- **ロゴ**: 300x100px（PNG、透過背景）
- **ヒーロー背景**: 1920x1080px（JPEG、WebP）
- **サービス画像**: 600x400px（JPEG、WebP）
- **スタッフ写真**: 400x400px（JPEG）
- **店舗写真**: 800x600px（JPEG、WebP）

### 命名規則
- 小文字のみ
- ハイフン区切り
- 例: `service-kaitori.jpg`, `staff-kimura.jpg`

## 開発環境

### 必須ツール
- PHP 7.4+
- モダンブラウザ（Chrome, Firefox, Safari, Edge）

### 推奨ツール
- VSCode
- Live Server拡張機能
- PHP Intelephense

## デプロイ

### 本番環境へのアップロード
1. FTPクライアント（FileZilla など）
2. アップロード先: `demo/kuruma-kaitori-k-village/`
3. パーミッション設定:
   - ディレクトリ: 755
   - ファイル: 644
   - `.htpasswd`: 600

### 確認URL
```
https://www.yojitu.com/demo/kuruma-kaitori-k-village/
```

## セキュリティ

### Basic認証（オプション）
`.htaccess` で案件ごとに認証設定可能。
クライアント確認時のみ一時的に認証を外すことも可。

### 本番移行時の注意
- `CLIENT_INFO.md` は削除
- `DIRECTORY_STRUCTURE.md` は削除
- `.htaccess` の認証設定を削除
- robots.txt で noindex を解除

---

最終更新: 2025-12-03
