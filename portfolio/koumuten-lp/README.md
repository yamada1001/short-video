# 山田工務店 LP

最高峰にリッチな工務店ランディングページ

## 概要

大阪の工務店「山田工務店」のランディングページ。
想いをかたちに。家族が笑顔になる、あたたかい家づくりをコンセプトにした、最高峰にリッチなデザイン。

## 特徴

### デザイン

- **最高峰のリッチさ**: 表示速度よりデザインの美しさを最優先
- **ヘキサゴンテーマ**: 全体を通してヘキサゴン（六角形）をモチーフに使用
- **グラデーション**: 10種類のリッチなグラデーション
- **Glassmorphism**: 透明感のあるモダンなデザイン

### アニメーション

- **30種類以上のアニメーション**: fadeIn, slideIn, scaleIn, rotate, float, pulse等
- **オープニングアニメーション**: 初回訪問時のみ表示（localStorage使用）
- **スクロールアニメーション**: Intersection Observerによる順次表示
- **スタッガーアニメーション**: 要素が順番にフェードイン
- **パララックス効果**: 3種類の速度で背景が動く
- **リップルエフェクト**: ボタンクリック時の波紋エフェクト

### レスポンシブ

- **モバイルファースト**: 320px〜対応
- **ブレークポイント**: 480px / 768px / 1024px
- **フルードタイポグラフィ**: clamp関数による可変フォントサイズ

### 技術

- **PHP**: 7.4+
- **CSS**: CSS Variables, Flexbox, Grid
- **JavaScript**: ES6+, Intersection Observer API
- **Google Fonts**: Noto Sans JP / Noto Serif JP / Playfair Display

## ファイル構造

```
portfolio/koumuten-lp/
├── index.php                 # トップページ
├── README.md                 # このファイル
├── assets/
│   ├── css/
│   │   ├── common.css       # 共通スタイル（1500行超）
│   │   └── index.css        # トップページ専用スタイル
│   └── js/
│       └── main.js          # メインJavaScript
└── includes/
    ├── header.php           # ヘッダー
    └── footer.php           # フッター
```

## セクション構成

1. **Hero**: メインビジュアル（フルスクリーン）
2. **About**: 想い（特徴3つ）
3. **Works**: 施工事例（6件）
4. **Voice**: お客様の声（3件）
5. **Flow**: 家づくりの流れ（6ステップ）
6. **Contact**: お問い合わせ（電話・メール）

## CSS Variables（一部）

### カラーパレット

- Primary: 50〜900（10段階）
- Secondary: 50〜900（10段階）
- Accent: 50〜900（10段階）

### グラデーション

- Sunset / Ocean / Forest / Fire / Midnight
- Premium / Rose / Purple / Teal / Warm

### タイポグラフィ

- Display: 3.5rem〜6rem
- Heading: 2rem〜4rem
- Title: 1.5rem〜2.5rem
- Body: 1rem〜1.25rem
- Small: 0.875rem〜1rem

### スペーシング

- 0 / 4px / 8px / 12px / 16px / 24px / 32px / 40px / 48px / 64px / 80px / 96px / 128px / 160px / 192px / 224px / 256px

### シャドウ

- xs / sm / md / lg / xl / 2xl / 3xl / inner / outline / glow / glow-lg / glow-xl

## JavaScriptの主要機能

1. **オープニングアニメーション**: localStorage使用
2. **メニュー開閉**: ハンバーガーメニュー
3. **スムーズスクロール**: カスタムイージング
4. **ヘッダースクロール制御**: throttle最適化
5. **スクロールアニメーション**: Intersection Observer
6. **スタッガーアニメーション**: 順次表示
7. **パララックス効果**: GPU加速
8. **ページトップボタン**: スムーズスクロール
9. **リップルエフェクト**: 動的アニメーション
10. **画像遅延読み込み**: Intersection Observer
11. **フォームバリデーション**: リアルタイム
12. **カウントアップアニメーション**: 数値アニメーション
13. **モーダル制御**: ESCキー・外クリック対応

## パフォーマンス最適化

- `will-change` プロパティ
- GPU加速（translate3d）
- throttle/debounce実装
- passive event listeners
- requestAnimationFrame使用
- Intersection Observerでメモリ効率化

## アクセシビリティ

- セマンティックHTML
- ARIA属性
- キーボード操作対応
- フォーカスアウトライン
- `prefers-reduced-motion` 対応
- `prefers-contrast` 対応

## ブラウザ対応

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 開発環境

- PHP 7.4+
- Modern Browser

## URL

https://yojitu.com/portfolio/koumuten-lp/

## クレジット

- **デザイン**: HONEYCOMB.LABO inspired
- **画像**: Unsplash
- **フォント**: Google Fonts

## ライセンス

All Rights Reserved © 2024 YOJITU
