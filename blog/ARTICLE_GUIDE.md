# ブログ記事作成ガイド

## ⚠️ 重要：記事フォーマットの厳守

記事フォーマットを守らないと、ページが正しく表示されません。
**必ずこのガイドに従ってください。**

## 🚨 データ・統計の引用ルール（重要）

記事内でデータ・統計・調査結果を記載する場合は、**必ず正確な出典を明記**してください。

### ✅ 必須事項

1. **具体的な数値データには必ず出典を記載**
   - 「〜%」「〜割」「〜人」などの統計データ
   - 調査結果、アンケート結果
   - 「調査によると」「統計では」などの表現

2. **出典の記載方法**
   - データの直後に括弧書きで出典を明記
   - 例：「企業のホームページ開設率：93.0%（総務省「令和5年通信利用動向調査」）」
   - 調査名、実施年月、対象者数（n=）なども記載

3. **参考文献セクションの必須化**
   - 記事末尾（article-cta.phpの直前）に参考文献セクションを追加
   - 外部リンク（URL）を含める
   - 以下のフォーマットを使用：

```html
<hr style="margin: 50px 0; border: none; border-top: 1px solid #ddd;">

<div class="note-box">
    <h4><i class="fas fa-book"></i> 参考文献・データソース</h4>
    <ul style="font-size: 14px; line-height: 1.8;">
        <li>調査名・出典名<br>
        <a href="URL" target="_blank" rel="noopener noreferrer" class="external-link">URL</a></li>
    </ul>
</div>
```

### ❌ 禁止事項

1. **根拠のない数値データの記載**
   - 出典不明な統計データ
   - 推測や一般論を数値で表現すること
   - 「一般的に」「多くの場合」などの曖昧な表現を数値化すること

2. **不正確な引用**
   - 調査名が不明確（「ある調査によると」など）
   - 調査年度が不明
   - URLが存在しない、アクセスできない

### ✅ 正しい例

```html
<li>企業のホームページ開設率：<strong>93.0%</strong>（総務省「令和5年通信利用動向調査」2024年6月発表、従業員100人以上の企業対象）</li>
```

### ❌ 間違った例

```html
<li>企業のホームページ保有率：<strong>90%以上</strong>（総務省調査）</li>
<li>消費者の70%以上が「ホームページがない企業は信頼できない」と回答</li>
```

## 記事フォーマットのルール

### ✅ 必須事項

1. **記事は `<article class="blog-article">` で開始**
2. **記事は `</article>` で終了**
3. **セクションは `<section id="section1">` で区切る**
4. **見出しは `<h2>` から開始**（`<h1>` は使用禁止）
5. **記事末尾に CTA を含める**: `<?php include __DIR__ . "/../includes/article-cta.php"; ?>`
6. **posts.json に excerpt（リード文）を記載**: 記事の要約を 2〜3 文程度で記載

### ❌ 禁止事項

1. **インラインスタイル禁止**: `<style>` タグは使用不可
2. **HTML構造タグ禁止**: `<!DOCTYPE>`, `<html>`, `<head>`, `<body>` は不要
3. **H1タグ禁止**: `<h1>` は detail.php で自動表示されます
4. **目次禁止**: `table-of-contents` は detail.php で自動生成されます
5. **メタ情報禁止**: `article-meta`, `lead-text` は detail.php で表示されます
6. **リード文禁止**: HTMLには含めず、posts.json の excerpt に記載してください
7. **CTAセクション禁止**: 独自の `cta-section`, `cta-box`, `cta-buttons` は不要（PHP インクルードを使用）
8. **CTAボタン禁止**: 記事内で `btn btn-primary` クラスを直接使用しないでください（PHP インクルードを使用）
9. **構造化データ禁止**: `<script type="application/ld+json">` は detail.php で自動生成されます。記事に含めないでください
10. **インラインJavaScript禁止**: `<script>` タグは使用不可

## 新規記事の作成手順

### 1. テンプレートをコピー

```bash
cp blog/data/article-template.html blog/data/article-XX-full.html
```

### 2. 記事内容を編集

- セクションを追加・削除
- 見出しと本文を記入
- 必要に応じて装飾クラスを使用（info-box, warning-box など）

### 3. posts.json に記事情報を追加

**重要**: `excerpt` フィールドに記事の要約を記載してください。これがリード文として記事の冒頭に自動表示されます。

```json
{
  "id": XX,
  "title": "記事タイトル",
  "slug": "article-slug",
  "excerpt": "記事の要約（リード文として表示されます）",
  "content": "data/article-XX-full.html",
  "thumbnail": "画像のURL（任意）",
  "publishedAt": "2025-11-15T10:00:00+09:00",
  "updatedAt": "2025-11-15T10:00:00+09:00",
  "category": "カテゴリ名",
  "tags": ["タグ1", "タグ2"],
  "author": "山田 蓮"
}
```

**リード文についての注意**:
- リード文は HTMLファイルには記載しません
- `posts.json` の `excerpt` フィールドに記載してください
- `detail.php` が自動的にリード文として表示します
- 2〜3文程度で、記事の要点を簡潔にまとめてください

### 4. 記事フォーマットを検証

```bash
cd blog
php validate-article.php data/article-XX-full.html
```

または全記事を一括検証:

```bash
php validate-article.php --all
```

### 5. エラーがなければ完了！

## 利用可能な装飾クラス

### ボックス系クラス（統一デザイン）

以下のクラスは全て無印良品スタイルの統一デザインで表示されます：

```html
<!-- 情報ボックス -->
<div class="info-box">
    <h4><i class="fas fa-info-circle"></i> 情報</h4>
    <p>補足情報を記載します。</p>
</div>

<!-- 警告ボックス -->
<div class="warning-box">
    <h4><i class="fas fa-exclamation-triangle"></i> 注意</h4>
    <p>注意事項を記載します。</p>
</div>

<!-- 成功ボックス -->
<div class="success-box">
    <h4><i class="fas fa-check-circle"></i> ポイント</h4>
    <p>重要なポイントを記載します。</p>
</div>

<!-- その他のボックス -->
<div class="summary-box">まとめの内容</div>
<div class="highlight-box">強調したい内容</div>
<div class="example-box">具体例</div>
<div class="definition-box">用語の定義</div>
<div class="tips-box">Tips</div>
<div class="note-box">補足</div>
<div class="caution-box">注意点</div>
```

### リンクボックス

```html
<div class="internal-link-box">
    <h4><i class="fas fa-link"></i> 関連記事</h4>
    <ul>
        <li><a href="/blog/detail.php?slug=example">記事タイトル</a></li>
    </ul>
</div>
```

### 比較ボックス

```html
<div class="comparison-box">
    <div class="comparison-item">
        <h4>方法A</h4>
        <p>説明文</p>
    </div>
    <div class="comparison-item">
        <h4>方法B</h4>
        <p>説明文</p>
    </div>
</div>
```

### FAQ

```html
<div class="faq-section">
    <div class="faq-item">
        <h4>Q. 質問内容</h4>
        <p>A. 回答内容</p>
    </div>
</div>
```

### リスト

```html
<!-- チェックリスト -->
<ul class="checklist">
    <li>項目1</li>
    <li>項目2</li>
</ul>

<!-- ステップリスト -->
<ol class="step-list">
    <li>ステップ1</li>
    <li>ステップ2</li>
</ol>
```

### リンク

```html
<!-- 内部リンク -->
<a href="/blog/detail.php?slug=example" class="internal-link">関連記事へ</a>

<!-- 外部リンク -->
<a href="https://example.com" class="external-link" target="_blank">外部サイトへ</a>
```

### グリッドレイアウト

```html
<div class="feature-grid">
    <div class="feature-item">
        <h4>特徴1</h4>
        <p>説明文</p>
    </div>
    <div class="feature-item">
        <h4>特徴2</h4>
        <p>説明文</p>
    </div>
</div>
```

## トラブルシューティング

### 検証エラーが出た場合

1. エラーメッセージを確認
2. 該当箇所を修正
3. 再度 `validate-article.php` を実行

### よくあるエラー

- **`<h1>` タグが含まれています**: H1を削除し、H2から開始してください
- **インラインスタイルが含まれています**: `<style>` タグを削除し、既存のCSSクラスを使用してください
- **目次が含まれています**: `table-of-contents` を削除してください（自動生成されます）
- **未定義のCSSクラスが使用されています**: 上記の「利用可能な装飾クラス」に記載されているクラスを使用してください
- **テーブルが多すぎます**: テーブルが6個以上あると、スマホで表示が崩れる可能性があります。必要最小限に抑えてください

## 自動修正ツール

記事フォーマットに問題がある場合、自動修正ツールを使用できます：

```bash
php blog/fix-all-articles.php
```

このスクリプトは以下を自動で行います：
- 不要な `<script>` タグ（構造化データ）を削除
- `<article class="blog-article">` タグを追加
- `<?php include ...article-cta.php... ?>` を追加
- `</article>` タグで終了

## Git Pre-commit Hook

コミット時に自動的に記事を検証するフックが設定されています。
記事に問題がある場合、コミットが拒否され、エラーが表示されます。

**エラーが出た場合の対処法：**
1. `php blog/fix-all-articles.php` で自動修正
2. 再度 `git add .` と `git commit`

## 参考

- 既存記事: `blog/data/article-100-full.html`（正しいフォーマット例）
- CSSクラス: `assets/css/pages/blog.css`
- 検証スクリプト: `blog/validate-article.php`
- 自動修正スクリプト: `blog/fix-all-articles.php`
