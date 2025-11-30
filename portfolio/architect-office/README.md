# 建築設計事務所 LP (Architecture Firm Landing Page)

## 概要

東京を拠点とする建築設計事務所のランディングページ。
住宅から商業施設、公共施設まで幅広いプロジェクトを手がける設計事務所のコーポレートサイトです。

## デザインコンセプト

- **ミニマル & 洗練**: 建築写真を主役にした、シンプルで洗練されたデザイン
- **モノクローム基調**: ブラック、グレー、ホワイトを基調とした配色で、建築の美しさを引き立てる
- **グリッドレイアウト**: プロジェクト事例をグリッド表示し、視覚的に整理された印象
- **フィルタリング機能**: 住宅、商業、公共施設でプロジェクトを絞り込み可能
- **レスポンシブ**: モバイルからデスクトップまで、あらゆるデバイスに対応

## 参考サイト

### Kengo Kuma and Associates
- URL: https://kkaa.co.jp/
- 特徴: プロジェクト写真を主役にしたギャラリー形式のレイアウト

### Aoyama Nomura Design
- URL: https://www.and-design.jp/
- 特徴: ミニマルなナビゲーションと余白を活かしたデザイン

## 技術スタック

### フロントエンド
- **HTML5**: セマンティックなマークアップ
- **CSS3**: カスタムプロパティ（CSS Variables）による設計
- **Vanilla JavaScript**: フレームワークを使わないシンプルな実装

### スタイリング
- **レスポンシブデザイン**: モバイルファーストアプローチ（768px ブレークポイント）
- **Google Fonts**:
  - Noto Sans JP（日本語）
  - Montserrat（英語）
- **カラーパレット**:
  - ブラック: #1a1a1a
  - グレー: #333333, #666666, #999999
  - ライトグレー背景: #f5f5f5
  - ホワイト: #ffffff

### JavaScript機能
- スムーズスクロール
- ハンバーガーメニュー（モバイル対応）
- プロジェクトフィルタリング（All / Residential / Commercial / Public）
- Intersection Observer APIによるスクロールアニメーション
- ヘッダーのスクロール時スタイル変更

## ディレクトリ構造

```
portfolio/architect-office/
├── index.php                 # トップページ
├── README.md                 # このファイル
├── includes/
│   ├── header.php            # ヘッダー共通部品
│   └── footer.php            # フッター共通部品
└── assets/
    ├── css/
    │   ├── common.css        # 共通スタイル（ヘッダー、フッター、リセット）
    │   └── index.css         # トップページ専用スタイル
    └── js/
        └── main.js           # メインJavaScript
```

## セクション構成

### 1. Hero Section
- フルスクリーン画像背景
- メインビジュアルとキャッチコピー
- スクロールインジケーター

### 2. About Section
- 事務所の理念・ビジョン
- 画像とテキストの2カラムレイアウト
- CTAボタン（Contact Us）

### 3. Projects Section
- プロジェクト事例のグリッド表示（3列）
- フィルタリング機能（All / Residential / Commercial / Public）
- ホバーエフェクト（画像ズーム）

### 4. Team Section
- チームメンバー紹介
- 3カラムグリッドレイアウト
- グレースケール画像（ホバーでカラー表示）

### 5. Contact Section
- お問い合わせCTA
- 電話・メールボタン
- 営業時間・定休日情報

## 画像素材

すべての画像は **Unsplash** から取得:
- 建築写真: 現代的な住宅、オフィス、公共施設
- 人物写真: プロフェッショナルなポートレート

## 特徴的な実装

### 1. プロジェクトフィルター
JavaScriptで `data-category` 属性を利用したフィルタリング機能を実装。
ボタンクリックで該当カテゴリのプロジェクトのみ表示。

### 2. スクロールアニメーション
Intersection Observer APIを使用し、ビューポートに入った要素を順次表示。
パフォーマンスに優れたアニメーション実装。

### 3. グレースケールエフェクト
チームメンバーの写真にCSS `filter: grayscale(100%)` を適用。
ホバー時にカラー表示することで、洗練された印象を演出。

## ブラウザ対応

- Chrome (最新版)
- Firefox (最新版)
- Safari (最新版)
- Edge (最新版)
- モバイルブラウザ (iOS Safari, Chrome for Android)

## アクセス

開発サーバー: `http://yojitu.local/portfolio/architect-office/`

---

© 2024 STUDIO ARCHITECTS
