# ディレクトリ構造提案 - システム開発サービスページ

## 📁 現在のディレクトリ構造

```
yojitu.com/
├── index.php                          # トップページ
├── services.php                       # サービス一覧ページ
├── web-production.php                 # Web制作詳細ページ
├── video-production.php               # 動画制作詳細ページ
├── about.php                          # 会社概要
├── contact.php                        # お問い合わせ
├── recruit.php                        # 採用情報
│
├── includes/                          # 共通インクルードファイル
│   ├── head.php                      # <head>タグ
│   ├── header.php                    # ヘッダー
│   ├── footer.php                    # フッター
│   ├── cta.php                       # CTAセクション
│   ├── functions.php                 # 共通関数
│   └── cookie-consent.php            # Cookie同意バナー
│
├── assets/                            # 静的ファイル
│   ├── css/
│   │   ├── base.css                  # 基本スタイル
│   │   ├── cookie-consent.css
│   │   └── pages/
│   │       ├── web-production.css    # Web制作ページ専用CSS
│   │       └── ...
│   └── js/
│       ├── app.js                    # メインJS
│       └── ...
│
├── bni-slide-system/                  # BNIスライドシステム
├── bni-payment-system/                # 決済システム
├── demo/                              # デモサイト
│   └── kuruma-kaitori-k-village/     # 車買取デモ
│
├── blog/                              # ブログ
├── portfolio/                         # ポートフォリオ
├── area/                              # 地域別ページ
└── en/                                # 英語版
```

## 🆕 追加するファイル・ディレクトリ

### 1. システム開発詳細ページ
```
yojitu.com/
└── system-development.php              # 【NEW】システム開発詳細ページ
```

**役割**:
- システム開発サービスの詳細情報を表示
- 料金プラン（見積もり制）
- 提供サービス一覧（7カテゴリ）
- 実績・ポートフォリオ（BNIスライドシステムなど）
- 技術スタック紹介
- FAQ
- お問い合わせCTA

**参考**: `web-production.php`と同様の構造

### 2. 専用CSSファイル（オプション）
```
yojitu.com/assets/css/pages/
└── system-development.css              # 【オプション】専用CSS
```

**備考**:
- `web-production.php`はインラインCSSを使用しているため、同様にインラインで実装可能
- 大量のスタイルが必要な場合のみ外部CSSファイルを作成

### 3. 仕様書・ドキュメント（ルート）
```
yojitu.com/
├── SYSTEM_DEVELOPMENT_SPECIFICATION.md  # 【作成済み】システム開発仕様書
└── DIRECTORY_STRUCTURE_PROPOSAL.md      # 【このファイル】ディレクトリ構造提案
```

**役割**:
- 開発者向けドキュメント
- サービス内容の詳細定義
- プロジェクト管理用

## 🔧 既存ファイルの修正箇所

### 1. services.php
**修正内容**: システム開発カードのリンク先を変更

**変更前**:
```php
<a href="contact.php" class="service-card__link">
    <span>お問い合わせ</span>
    <i class="fas fa-arrow-right"></i>
</a>
```

**変更後**:
```php
<a href="system-development.php" class="service-card__link">
    <span>詳しく見る</span>
    <i class="fas fa-arrow-right"></i>
</a>
```

### 2. includes/header.php（必要なら）
ヘッダーナビゲーションにシステム開発ページへのリンクを追加する場合。

### 3. sitemap.xml
新しいページをサイトマップに追加。

## 📊 ファイル構成の詳細

### system-development.php の構成

```php
<?php
$current_page = 'services';
require_once __DIR__ . '/includes/functions.php';

// SEO設定
$page_title = 'システム開発・Webアプリケーション開発｜余日';
$page_description = 'PHP/Python対応。業務管理システム、API開発、決済連携、自動化ツールなど。';
$page_keywords = 'システム開発,Webアプリケーション,PHP,Python,API開発,決済システム,余日';

// 構造化データ（JSON-LD）
$structured_data = '
{
  "@context": "https://schema.org",
  "@type": "Service",
  "serviceType": "システム開発",
  "provider": {
    "@type": "LocalBusiness",
    "name": "余日（Yojitsu）",
    ...
  },
  ...
}
';

// インラインCSS
$inline_styles = <<<'EOD'
  /* web-production.phpと同様のスタイル */
  .page-header { ... }
  .service-category { ... }
  .feature-grid { ... }
  .portfolio-section { ... }
  ...
EOD;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<?php require_once __DIR__ . '/includes/head.php'; ?>
</head>
<body>
    <?php include __DIR__ . '/includes/header.php'; ?>

    <!-- ページヘッダー -->
    <section class="page-header">...</section>

    <!-- サービスカテゴリ -->
    <section class="service-categories">...</section>

    <!-- 実績・ポートフォリオ -->
    <section class="portfolio-section">...</section>

    <!-- 技術スタック -->
    <section class="tech-stack">...</section>

    <!-- 料金プラン -->
    <section class="pricing-section">...</section>

    <!-- FAQ -->
    <section class="faq-section">...</section>

    <!-- CTAセクション -->
    <?php include __DIR__ . '/includes/cta.php'; ?>

    <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
```

## 🎨 デザインの統一性

### カラースキーム
既存のサイトと統一：
- プライマリ: `var(--color-natural-brown)`
- セカンダリ: `var(--color-charcoal)`
- 背景: `var(--color-bg-white)`, `var(--color-bg-gray)`
- アクセント: `var(--color-border)`

### コンポーネント
既存のコンポーネントを再利用：
- `.page-header` - ページヘッダー
- `.section__subtitle` - セクションタイトル
- `.btn` - ボタン
- `.highlight-box` - 強調ボックス
- カード系（`.service-card`, `.pricing-card`など）

## 📋 実装の優先順位

### Phase 1: 基本ページ作成（優先度: 高）
1. ✅ `SYSTEM_DEVELOPMENT_SPECIFICATION.md` 作成
2. ⏳ `system-development.php` 作成
3. ⏳ `services.php` のリンク修正

### Phase 2: コンテンツ充実（優先度: 中）
4. 実績・ポートフォリオセクションの追加
5. 技術スタック詳細の記載
6. FAQの充実

### Phase 3: SEO・最適化（優先度: 低）
7. 構造化データ（JSON-LD）の実装
8. `sitemap.xml` への追加
9. OGP画像の作成
10. ページ表示速度の最適化

## 🔗 リンク構造

### ページ間のリンク
```
index.php
  └── services.php
        ├── web-production.php
        ├── video-production.php
        └── system-development.php  ← 【NEW】
              └── contact.php
```

### 内部リンク
- トップページ → サービス一覧 → システム開発詳細
- ヘッダーナビ → サービス → システム開発
- フッター → サービス → システム開発
- CTAセクション → お問い合わせ

## 💡 追加提案

### 1. ポートフォリオページの充実
`/portfolio/` ディレクトリに実績詳細ページを追加：
```
portfolio/
├── bni-slide-system.php          # BNIスライドシステム詳細
├── payment-integration.php        # 決済システム連携事例
└── automation-tool.php            # 自動化ツール事例
```

### 2. ブログ記事での集客
`/blog/` にシステム開発関連の記事を追加：
- 「業務効率化システムの選び方」
- 「PHP vs Python どちらを選ぶべきか」
- 「APIとは？基礎から解説」

### 3. お問い合わせフォームのカスタマイズ
`contact.php` にシステム開発専用の項目を追加：
- システムの種類（選択式）
- 開発言語の希望
- 予算感
- 納期希望

## 📊 アクセス解析の設定

### Google Analytics イベント
- システム開発ページ閲覧
- お問い合わせボタンクリック
- 料金プラン確認
- ポートフォリオクリック

### コンバージョン設定
- システム開発ページからのお問い合わせ完了
- 電話番号クリック
- メールアドレスクリック

## 🚀 次のステップ

1. **仕様書の確認**
   - `SYSTEM_DEVELOPMENT_SPECIFICATION.md` の内容を確認
   - 不足している情報があれば追記

2. **system-development.php の作成**
   - `web-production.php` をベースに作成
   - サービスカテゴリ（7種類）を表示
   - 実績・ポートフォリオセクションを追加

3. **services.php の修正**
   - リンク先を `system-development.php` に変更
   - ボタンテキストを「詳しく見る」に変更

4. **動作確認**
   - リンク遷移の確認
   - レスポンシブデザインの確認
   - SEOタグの確認

5. **本番環境へのデプロイ**
   - Gitコミット・プッシュ
   - 本番サーバーへのアップロード
   - 最終確認

---

**作成日**: 2025-12-16
**作成者**: Claude Code
**バージョン**: 1.0
