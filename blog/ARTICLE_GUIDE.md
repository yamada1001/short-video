# ブログ記事作成ガイド

## ⚠️ 重要：記事フォーマットの厳守

記事フォーマットを守らないと、ページが正しく表示されません。
**必ずこのガイドに従ってください。**

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

### 情報ボックス

```html
<div class="info-box">
    <h3><i class="fas fa-info-circle"></i> 情報</h3>
    <p>補足情報を記載します。</p>
</div>
```

### 警告ボックス

```html
<div class="warning-box">
    <h3><i class="fas fa-exclamation-triangle"></i> 注意</h3>
    <p>注意事項を記載します。</p>
</div>
```

### 比較表

```html
<table class="comparison-table">
    <thead>
        <tr>
            <th>項目</th>
            <th>A</th>
            <th>B</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>価格</td>
            <td>10万円</td>
            <td>20万円</td>
        </tr>
    </tbody>
</table>
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
