# 参考LPサイト デザイン分析

## 調査日
2025-12-01

---

## 参考サイト3つ

### 1. Cast Me!（キャストミー）
**ディレクトリ**: `/Users/yamadaren/Downloads/castme.jp`
**サービス**: インフルエンサープラットフォーム

#### デザイン特徴（HTMLから読み取れる内容）
- **ビルドツール**: Nuxt.js + Studio.Design
- **フォント**: Font Awesome 6、Material Icons、カスタムフォント（grandam）
- **スタイルアプローチ**: コンポーネントベース、CSS-in-JS的なアプローチ
- **アニメーション**: ページ遷移アニメーション（.page-enter-active, .page-leave-active）
- **カラー変数**: CSS Custom Properties（--g-angle, --g-color-*, --g-position-*）でグラデーション管理
- **レスポンシブ**: モバイルファースト、breakpoints定義あり
- **インタラクション**: ホバー効果、トランジション（cubic-bezier(0.4, 0.4, 0, 1)）

#### 技術的特徴
- リッチテキスト対応
- グラデーション背景（@propertyでアニメーション可能）
- Material Symbols（3種類：Outlined, Rounded, Sharp）
- モーダル・スナックバー実装

---

### 2. WhatYa（ワチャ）
**ディレクトリ**: `/Users/yamadaren/Downloads/service.solairo.co.jp/lp/whatya_202102`
**サービス**: ECサイト向けセールスチャット

#### デザイン特徴（HTMLから読み取れる内容）
- **ファーストビュー**: 「LTVを上げる戦略的チャットサービス」
- **キャッチコピー構成**:
  - リード: 「LTVを上げる戦略的チャットサービス」
  - メイン: 「優良顧客を増やすのも、セールスを上げるのも、実はチャットから。」
  - 説明文: ECサイトのチャットから商品購入顧客を増やす提案
- **ビジュアル**: チャット画面のモックアップ（PC/SPレスポンシブ画像）
- **アンカーナビゲーション**: Top, About, Performance, Future, Developer, Contact
- **CTA**: 「資料請求・お問い合わせ」ヘッダー固定

#### 構成特徴
- ストーリーテリング型（チャットのやり取りを視覚化）
- レスポンシブ画像（picture要素でSP/PC出し分け）
- セクションごとのアンカーリンク

---

### 3. Spicato（スピカート）
**ディレクトリ**: `/Users/yamadaren/Downloads/spicato.com`
**サービス**: Web制作会社

#### デザイン特徴（ファイル構造から推測）
- **ディレクトリ構成**: assets, blog, company, contact, faq, members, philosophy, privacy, projects, service, studio
- **コンテンツ重視**: 大量のblog記事（101件）、projects（132件）
- **ファビコン**: SVG形式（modern approach）
- **サイトマップ**: sitemap-index.xml（SEO対策）
- **特設ページ**: kakizome2025（イベントページ）

---

## 共通する傾向

### デザイン面
1. **レスポンシブ対応**: 全サイトがモバイルファースト
2. **アニメーション**: スムーズなトランジション、イージング関数使用
3. **視覚的ストーリーテリング**: モックアップ・実例を視覚化
4. **セクション構成**: スクロール型LP、アンカーナビゲーション

### 技術面
1. **CSS Custom Properties**: カラー・グラデーション管理
2. **picture要素**: レスポンシブ画像の最適化
3. **GTM（Google Tag Manager）**: 全サイトでアクセス解析
4. **OGP対応**: SNSシェア最適化

---

## 当サービスLPへの示唆

### 採用すべき要素

#### 1. ストーリーテリング型の視覚化
- **WhatYaの手法**: チャット画面モックアップで「使用イメージ」を視覚化
- **当サービス適用**:
  - 「制作中のスクリーンショット」
  - 「3日間のタイムライン視覚化」
  - 「ビフォー・アフター（他社30万円 vs 当社5万円）」

#### 2. キャッチコピーの3層構造
- **リード**: 短いサブヘッド（属性・特徴）
- **メイン**: インパクトあるキャッチコピー
- **説明**: ベネフィットの具体化

**当サービス例:**
```
リード: 最短3営業日、5万円〜
メイン: LPがない1日で、いくら機会損失してますか？
説明: 他社が見積もり出してる間に、うちは完成させます。
```

#### 3. アンカーナビゲーション
セクションが多い場合、ヘッダーに目次リンクを配置
- 当サービスの場合: 「特徴」「タイミング」「料金」「実績」「FAQ」

#### 4. レスポンシブ画像
picture要素でSP/PC画像出し分け（ファイルサイズ最適化）

#### 5. CSS Custom Propertiesでグラデーション管理
```css
:root {
  --primary-gradient: linear-gradient(135deg, #FF6B9D, #A78BFA);
}
```

### 避けるべき要素
1. **過度な技術スタック**: Nuxt.jsのような複雑なフレームワークは不要
2. **重いアニメーション**: ページ読み込み速度を優先
3. **情報過多**: Cast Me!のような多機能は当サービスには不要

---

## デザイントンマナ提案

### 方向性: モダン・ダイナミック × スピード感

#### カラーパレット
- **ベース**: 白 #FFFFFF
- **テキスト**: ダークグレー #1F2937
- **プライマリ**: ビビッドピンク #FF6B9D（スピード・革新性）
- **セカンダリ**: ソフトパープル #A78BFA（AI・先進性）
- **アクセント**: エメラルド #10B981（成功・成長）
- **背景**: オフホワイト #FAFAF9

#### タイポグラフィ
- **フォント**: Noto Sans JP（可読性重視）
- **ウェイト**:
  - 通常テキスト: 400-500
  - 見出し: 700-800
- **サイズ**: clamp()で流動的
  - H1: clamp(36px, 6vw, 64px)
  - H2: clamp(28px, 4vw, 48px)
  - Body: 16px-18px

#### グラデーション
```css
--gradient-primary: linear-gradient(135deg, #FF6B9D 0%, #A78BFA 100%);
--gradient-success: linear-gradient(135deg, #A78BFA 0%, #10B981 100%);
```

#### アニメーション
- **イージング**: cubic-bezier(0.4, 0, 0.2, 1)（Material Design準拠）
- **速度**: 0.3s-0.4s（速すぎず遅すぎず）
- **種類**:
  - フェードイン（opacity）
  - スライドアップ（translateY）
  - ホバー時の軽い浮遊（translateY + shadow）

#### レイアウト
- **最大幅**: 1200px
- **セクション余白**: clamp(60px, 10vw, 100px)
- **カード**: border-radius 16px-24px
- **シャドウ**: 多層（浅い + 深い）

#### ビジュアル要素
1. **グラデーション背景オーブ**: ぼかし効果で浮遊
2. **グラスモーフィズム**: backdrop-filter: blur(20px)
3. **グラデーションテキスト**: 見出しにアクセント
4. **タイムライン視覚化**: 「今日→3日後」をグラフィカルに

---

## 次のステップ

1. デザイントンマナ最終決定
2. preview.htmlへの適用
3. 本番環境でのコーディング

---

## 参考ファイル
- castme.jp: `/Users/yamadaren/Downloads/castme.jp/index.html`
- WhatYa: `/Users/yamadaren/Downloads/service.solairo.co.jp/lp/whatya_202102/index.html`
- Spicato: `/Users/yamadaren/Downloads/spicato.com/index.html`
