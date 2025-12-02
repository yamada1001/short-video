# LP制作サービス - デザイン仕様書（完全版）

## 作成日
2025-12-01

---

## 1. デザインコンセプト

### コンセプト
**「スピード × 先進性 × 親しみやすさ」**

- **スピード**: 動きのあるアニメーション、タイムライン視覚化で「速さ」を表現
- **先進性**: グラデーション、AI要素で「最新技術」を表現
- **親しみやすさ**: 柔らかい色調、丸みのあるデザインで「敷居の低さ」を表現

### ターゲット印象
- 「安いけど安っぽくない」
- 「速いけど雑じゃない」
- 「AIだけど人間味がある」

---

## 2. カラーシステム

### プライマリカラー
```css
--color-primary: #FF6B9D;      /* ビビッドピンク - スピード感・革新性 */
--color-primary-light: #FFB3D1; /* ライトピンク - ホバー時など */
--color-primary-dark: #E5005A;  /* ダークピンク - 強調時 */
```

### セカンダリカラー
```css
--color-secondary: #A78BFA;     /* ソフトパープル - AI・先進性 */
--color-secondary-light: #C4B5FD;
--color-secondary-dark: #7C3AED;
```

### アクセントカラー
```css
--color-accent: #10B981;        /* エメラルド - 成功・成長 */
--color-accent-light: #34D399;
--color-accent-dark: #059669;
```

### ニュートラルカラー
```css
--color-white: #FFFFFF;
--color-off-white: #FAFAF9;
--color-gray-50: #F9FAFB;
--color-gray-100: #F3F4F6;
--color-gray-200: #E5E7EB;
--color-gray-300: #D1D5DB;
--color-gray-400: #9CA3AF;
--color-gray-500: #6B7280;
--color-gray-600: #4B5563;
--color-gray-700: #374151;
--color-gray-800: #1F2937;
--color-gray-900: #111827;
```

### テキストカラー
```css
--text-primary: #1F2937;        /* メインテキスト */
--text-secondary: #4B5563;      /* サブテキスト */
--text-muted: #6B7280;          /* 注釈など */
--text-inverse: #FFFFFF;        /* 暗背景上のテキスト */
```

### グラデーション定義
```css
--gradient-primary: linear-gradient(135deg, #FF6B9D 0%, #A78BFA 100%);
--gradient-secondary: linear-gradient(135deg, #A78BFA 0%, #10B981 100%);
--gradient-hero: linear-gradient(180deg, #FAFAF9 0%, #F3F4F6 100%);
```

---

## 3. タイポグラフィ

### フォントファミリー
```css
--font-primary: 'Noto Sans JP', -apple-system, BlinkMacSystemFont,
                'Segoe UI', 'Hiragino Sans', sans-serif;
--font-secondary: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
--font-mono: 'Courier New', monospace;
```

**採用理由:**
- **Noto Sans JP**: 日本語の可読性が高い、Googleフォントで無料、多ウェイト対応
- **Inter**: 欧文との混植に適している、数字が見やすい（料金表示に最適）

### フォントウェイト
```css
--font-weight-normal: 400;
--font-weight-medium: 500;
--font-weight-semibold: 600;
--font-weight-bold: 700;
--font-weight-extrabold: 800;
```

### フォントサイズ（レスポンシブ対応）
```css
/* 見出し */
--font-size-h1: clamp(36px, 6vw, 64px);
--font-size-h2: clamp(28px, 4vw, 48px);
--font-size-h3: clamp(24px, 3vw, 36px);
--font-size-h4: clamp(20px, 2.5vw, 28px);

/* 本文 */
--font-size-body-large: clamp(18px, 2vw, 20px);
--font-size-body: 16px;
--font-size-body-small: 14px;

/* UI */
--font-size-caption: 12px;
--font-size-button: 16px;
```

### 行間（Line Height）
```css
--line-height-tight: 1.2;       /* 見出し用 */
--line-height-normal: 1.5;      /* デフォルト */
--line-height-relaxed: 1.8;     /* 本文用 */
--line-height-loose: 2.0;       /* ゆったりした読み物 */
```

### 字間（Letter Spacing）
```css
--letter-spacing-tight: -0.02em;  /* 大見出し */
--letter-spacing-normal: 0;
--letter-spacing-wide: 0.05em;    /* 強調テキスト */
```

### タイポグラフィスタイル定義
```css
.text-h1 {
  font-size: var(--font-size-h1);
  font-weight: var(--font-weight-bold);
  line-height: var(--line-height-tight);
  letter-spacing: var(--letter-spacing-tight);
}

.text-body {
  font-size: var(--font-size-body);
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height-relaxed);
}
```

---

## 4. スペーシング（余白）システム

### 基本スケール
```css
--space-1: 4px;
--space-2: 8px;
--space-3: 12px;
--space-4: 16px;
--space-5: 20px;
--space-6: 24px;
--space-8: 32px;
--space-10: 40px;
--space-12: 48px;
--space-16: 64px;
--space-20: 80px;
--space-24: 96px;
--space-32: 128px;
```

### セクション余白（レスポンシブ）
```css
--section-padding-y: clamp(60px, 10vw, 100px);
--section-padding-x: clamp(20px, 5vw, 40px);
```

### コンテナ最大幅
```css
--container-max-width: 1200px;
--container-narrow: 800px;  /* テキスト中心セクション用 */
--container-wide: 1400px;   /* 画像・グリッド用 */
```

---

## 5. レイアウト・グリッドシステム

### ブレークポイント
```css
--breakpoint-mobile: 640px;
--breakpoint-tablet: 840px;
--breakpoint-desktop: 1140px;
--breakpoint-wide: 1440px;
```

### グリッド定義
```css
/* 2カラム */
.grid-2 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: var(--space-8);
}

/* 3カラム */
.grid-3 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: var(--space-6);
}
```

---

## 6. コンポーネント設計

### 6-1. ヘッダー（グローバルナビゲーション）

#### 構成
- **左**: ロゴ（yojitu.com）
- **中央**: ナビゲーションリンク（デスクトップのみ）
  - 「特徴」「タイミング」「料金」「実績」「FAQ」
- **右**: CTAボタン「無料相談する」

#### スタイル
```css
.header {
  position: fixed;
  top: 0;
  width: 100%;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid var(--color-gray-200);
  z-index: 1000;
  padding: var(--space-4) var(--section-padding-x);
}

.header-logo {
  font-size: 24px;
  font-weight: var(--font-weight-bold);
  color: var(--text-primary);
}

.header-nav a {
  font-size: 14px;
  font-weight: var(--font-weight-medium);
  color: var(--text-secondary);
  transition: color 0.3s;
}

.header-nav a:hover {
  color: var(--color-primary);
}
```

#### モバイル対応
- ハンバーガーメニュー（3本線）
- サイドドロワー方式
- 背景オーバーレイ

---

### 6-2. フッター

#### 構成
```
[ロゴ + キャッチコピー]

[3カラム]
- サービス: 特徴、タイミング、料金
- 実績・サポート: 実績、FAQ、お問い合わせ
- 会社情報: 会社概要、プライバシーポリシー

[下部]
- SNSアイコン（Twitter, Facebook, Instagram）
- コピーライト: © 2025 YOJITU.COM All Rights Reserved.
```

#### スタイル
```css
.footer {
  background: var(--color-gray-900);
  color: var(--text-inverse);
  padding: var(--space-20) var(--section-padding-x) var(--space-10);
}

.footer-links {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: var(--space-10);
  margin-bottom: var(--space-16);
}

.footer-link-title {
  font-size: 14px;
  font-weight: var(--font-weight-semibold);
  margin-bottom: var(--space-4);
  color: var(--color-gray-300);
}

.footer-link-item a {
  font-size: 14px;
  color: var(--color-gray-400);
  transition: color 0.3s;
}

.footer-link-item a:hover {
  color: var(--color-white);
}
```

---

### 6-3. ボタンスタイル

#### プライマリボタン
```css
.btn-primary {
  background: var(--gradient-primary);
  color: var(--text-inverse);
  padding: 14px 32px;
  border-radius: 50px;
  font-size: 16px;
  font-weight: var(--font-weight-bold);
  border: none;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 15px rgba(255, 107, 157, 0.3);
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(255, 107, 157, 0.4);
}
```

#### セカンダリボタン
```css
.btn-secondary {
  background: transparent;
  color: var(--color-primary);
  border: 2px solid var(--color-primary);
  /* 以下同じ */
}
```

#### アウトラインボタン
```css
.btn-outline {
  background: transparent;
  color: var(--text-primary);
  border: 1px solid var(--color-gray-300);
}
```

---

### 6-4. カード

#### 基本カードスタイル
```css
.card {
  background: var(--color-white);
  border-radius: 16px;
  padding: var(--space-8);
  box-shadow:
    0 4px 6px rgba(0, 0, 0, 0.05),
    0 10px 25px rgba(0, 0, 0, 0.08);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.card:hover {
  transform: translateY(-8px);
  box-shadow:
    0 8px 12px rgba(0, 0, 0, 0.08),
    0 20px 40px rgba(0, 0, 0, 0.12);
}
```

#### グラスカード（半透明）
```css
.card-glass {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.3);
}
```

---

### 6-5. モーダル

```css
.modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.6);
  backdrop-filter: blur(5px);
  z-index: 9999;
  animation: fadeIn 0.3s;
}

.modal-content {
  background: var(--color-white);
  border-radius: 24px;
  max-width: 600px;
  margin: 5% auto;
  padding: var(--space-12);
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.3s;
}
```

---

## 7. アニメーション仕様

### イージング関数
```css
--ease-default: cubic-bezier(0.4, 0, 0.2, 1);  /* Material Design */
--ease-in: cubic-bezier(0.4, 0, 1, 1);
--ease-out: cubic-bezier(0, 0, 0.2, 1);
--ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
```

### トランジション速度
```css
--duration-fast: 0.15s;
--duration-normal: 0.3s;
--duration-slow: 0.5s;
```

### 基本アニメーション定義

#### フェードイン
```css
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
```

#### スライドアップ
```css
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
```

#### フローティング（浮遊）
```css
@keyframes float {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}
```

### スクロールトリガーアニメーション
- Intersection Observer APIを使用
- ビューポートに入ったら`.is-visible`クラスを付与
- CSSで制御

---

## 8. 画像・ビジュアル要素

### 8-1. 背景要素

#### フローティングオーブ（浮遊するグラデーション球）
```css
.floating-orb {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.3;
  animation: float 20s ease-in-out infinite;
}

.orb-1 {
  width: 400px;
  height: 400px;
  background: var(--gradient-primary);
  top: -100px;
  left: -100px;
}

.orb-2 {
  width: 300px;
  height: 300px;
  background: var(--gradient-secondary);
  bottom: -50px;
  right: -50px;
  animation-delay: 5s;
}
```

#### ノイズテクスチャ（オプション）
- SVGパターンで微細なドット
- opacity: 0.02で超薄く
- 質感を出す

---

### 8-2. SVG使用方針

#### 使用箇所
1. **ロゴ**: yojitu.comロゴ
2. **アイコン**: チェックマーク、矢印、時計など
3. **装飾要素**: 波線、曲線、幾何学模様
4. **グラフ・チャート**: タイムライン、比較図

#### SVGライブラリ
- **heroicons**: https://heroicons.com/ （無料、MIT License）
- または手作りSVG

#### SVGスタイル例
```css
.icon-svg {
  width: 24px;
  height: 24px;
  fill: currentColor;
  transition: fill 0.3s;
}

.icon-svg.large {
  width: 48px;
  height: 48px;
}
```

---

### 8-3. 画像形式・最適化

#### 形式選定
- **写真・スクリーンショット**: WebP（フォールバック: JPEG）
- **ロゴ・アイコン**: SVG
- **装飾**: SVG or CSS

#### レスポンシブ画像
```html
<picture>
  <source media="(max-width: 640px)" srcset="image-sp.webp" type="image/webp">
  <source media="(max-width: 640px)" srcset="image-sp.jpg">
  <source srcset="image-pc.webp" type="image/webp">
  <img src="image-pc.jpg" alt="説明">
</picture>
```

#### 画像サイズ目安
- **ヒーロー画像**: 最大1600px幅
- **セクション画像**: 最大1200px幅
- **カード画像**: 最大600px幅
- **サムネイル**: 最大300px幅

---

## 9. セクション別デザイン仕様

### 9-1. ファーストビュー（Hero）

#### 構成
```
[背景: フローティングオーブ]
  ├ キャッチコピー（H1）
  ├ サブコピー（P）
  ├ 3つの特徴バッジ（5万円〜、最短3営業日、AI活用）
  └ CTAボタン
```

#### スタイル
```css
.hero {
  min-height: 90vh;
  background: var(--gradient-hero);
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: var(--space-20) var(--section-padding-x);
}

.hero h1 {
  font-size: var(--font-size-h1);
  font-weight: var(--font-weight-bold);
  margin-bottom: var(--space-6);
  background: var(--gradient-primary);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
```

---

### 9-2. 問題提起セクション

#### 構成
5つのカード（グリッド配置）

#### スタイル
```css
.problem-card {
  background: var(--color-white);
  border-radius: 16px;
  padding: var(--space-8);
  text-align: center;
}

.problem-card::before {
  content: "💭";
  font-size: 48px;
  display: block;
  margin-bottom: var(--space-4);
}
```

---

### 9-3. 比較表

#### スタイル
```css
.comparison-table {
  background: var(--color-white);
  border-radius: 16px;
  overflow: hidden;
  border: 2px solid transparent;
  background-image:
    linear-gradient(white, white),
    var(--gradient-primary);
  background-origin: border-box;
  background-clip: padding-box, border-box;
}
```

---

### 9-4. タイムライン視覚化

#### 構成
```
今日 → 明日 → 2日後 → 3日後（完成！）
```

#### スタイル
```css
.timeline {
  display: flex;
  justify-content: space-between;
  position: relative;
}

.timeline::before {
  content: "";
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 2px;
  background: var(--gradient-primary);
}

.timeline-item {
  position: relative;
  z-index: 1;
  background: var(--color-white);
  padding: var(--space-4);
  border-radius: 12px;
}
```

---

### 9-5. 料金プラン

#### 構成
2つのカード（横並び）

#### スタイル
```css
.pricing-card {
  background: var(--color-white);
  border-radius: 24px;
  padding: var(--space-12);
  text-align: center;
  position: relative;
}

.pricing-card.featured {
  transform: scale(1.05);
  box-shadow: 0 20px 60px rgba(255, 107, 157, 0.2);
}

.pricing-card.featured::before {
  content: "人気";
  position: absolute;
  top: -12px;
  right: 24px;
  background: var(--gradient-primary);
  color: white;
  padding: 6px 20px;
  border-radius: 20px;
  font-size: 14px;
  font-weight: var(--font-weight-bold);
}
```

---

### 9-6. FAQ（アコーディオン）

#### スタイル
```css
.faq-item {
  background: var(--color-white);
  border-radius: 12px;
  padding: var(--space-6);
  margin-bottom: var(--space-4);
  cursor: pointer;
  transition: all 0.3s;
}

.faq-question {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: var(--font-weight-semibold);
}

.faq-icon {
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: var(--gradient-primary);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: transform 0.3s;
}

.faq-item.active .faq-icon {
  transform: rotate(45deg);
}

.faq-answer {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease;
}

.faq-item.active .faq-answer {
  max-height: 500px;
  padding-top: var(--space-4);
}
```

---

## 10. レスポンシブ対応

### ブレークポイント別調整

#### モバイル（〜640px）
- フォントサイズ縮小
- パディング縮小
- グリッド: 1カラム
- ハンバーガーメニュー表示

#### タブレット（641px〜840px）
- グリッド: 2カラム
- フォントサイズ中間

#### デスクトップ（841px〜）
- グリッド: 3カラム
- フルナビゲーション表示

---

## 11. パフォーマンス最適化

### CSS最適化
- クリティカルCSS（above the fold）をインライン化
- 非クリティカルCSSは遅延読み込み
- 未使用CSSの削除

### 画像最適化
- WebP形式使用
- 遅延読み込み（lazy loading）
- レスポンシブ画像（srcset）

### JavaScript最適化
- defer属性で非同期読み込み
- Intersection Observer APIでスクロールアニメーション

---

## 12. アクセシビリティ

### 基本方針
- WCAG 2.1 AA準拠
- キーボード操作可能
- スクリーンリーダー対応

### 実装
```html
<!-- セマンティックHTML -->
<header role="banner">
<nav role="navigation">
<main role="main">
<footer role="contentinfo">

<!-- ARIAラベル -->
<button aria-label="メニューを開く">

<!-- フォーカス表示 -->
:focus-visible {
  outline: 2px solid var(--color-primary);
  outline-offset: 2px;
}
```

---

## 13. 次のステップ

1. デザインモックアップ作成（Figma/Adobe XD）※省略可
2. HTML/CSS実装
3. JavaScript実装
4. レスポンシブ対応確認
5. パフォーマンステスト
6. アクセシビリティチェック
7. ブラウザ互換性テスト

---

## 付録：使用リソース

### フォント
- Noto Sans JP: https://fonts.google.com/noto/specimen/Noto+Sans+JP
- Inter: https://fonts.google.com/specimen/Inter

### アイコン
- Heroicons: https://heroicons.com/
- Font Awesome: https://fontawesome.com/

### カラーツール
- Coolors: https://coolors.co/
- Adobe Color: https://color.adobe.com/

### デザインインスピレーション
- castme.jp
- service.solairo.co.jp/lp/whatya_202102
- spicato.com
