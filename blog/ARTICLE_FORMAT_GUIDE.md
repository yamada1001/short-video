# ブログ記事ファイルフォーマットガイド

## 概要

`blog/data/article-*-full.html` ファイルは、**純粋なコンテンツのみ**を含む必要があります。
`detail.php` が HTML構造とヘッダーを自動生成するため、記事ファイルに HTML構造タグを含めてはいけません。

## ❌ 含めてはいけないタグ

以下のタグは **絶対に含めないでください**：

```html
<!DOCTYPE html>
<html>
<head>
<title>
<meta>
<body>
<article>
<header class="article-header">
<h1> (記事タイトル用のh1タグ)
<div class="article-meta">
<time>
<span class="category">
```

これらのタグは `detail.php` が自動的に生成します。

## ✅ 正しい記事フォーマット

### 推奨される開始タグ

記事は以下のいずれかのタグで始めてください：

```html
<section class="article-section">
<p>
<h2>
<div>
<ul>
<ol>
```

### 記事構造の例

```html
<section class="article-section">
    <h2>はじめに</h2>
    <p>記事の導入文...</p>
</section>

<section class="article-section">
    <h2>ポイント1：○○○</h2>
    <h3>サブ見出し</h3>
    <p>説明文...</p>

    <ul>
        <li>項目1</li>
        <li>項目2</li>
    </ul>
</section>

<!-- その他のセクション -->

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "記事タイトル",
    ...
}
</script>
```

## 構造化データについて

`<script type="application/ld+json">` タグは **含めても OK** です。
これは SEO のための構造化データであり、コンテンツの一部として扱われます。

### 構造化データのテンプレート

```json
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "記事タイトル",
    "description": "記事の説明（160文字程度）",
    "image": "https://yojitu.com/assets/images/blog/article-XX-thumbnail.jpg",
    "author": {
        "@type": "Person",
        "name": "山田 蓮"
    },
    "publisher": {
        "@type": "Organization",
        "name": "余日（Yojitsu）",
        "logo": {
            "@type": "ImageObject",
            "url": "https://yojitu.com/assets/images/logo.png"
        }
    },
    "datePublished": "2025-11-15",
    "dateModified": "2025-11-15",
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://yojitu.com/blog/article-XX.html"
    },
    "keywords": ["キーワード1", "キーワード2", ...],
    "articleSection": "カテゴリ名",
    "wordCount": 5000
}
</script>
```

## 検証方法

記事を作成・編集した後は、必ず検証スクリプトを実行してください：

```bash
python3 blog/validate-articles.py
```

このスクリプトは：
- すべての記事ファイルをチェック
- 不要な HTML構造タグを検出
- 問題があればエラーを報告

## よくある間違い

### ❌ 間違い1: article タグで囲む

```html
<!-- ❌ これはダメ -->
<article class="blog-article">
    <header class="article-header">
        <h1>記事タイトル</h1>
        <div class="article-meta">
            <time datetime="2025-11-15">2025年11月15日</time>
            <span class="category">カテゴリ</span>
        </div>
    </header>
    <div class="article-content">
        <section class="article-section">
            <h2>見出し</h2>
            <p>本文...</p>
        </section>
    </div>
</article>
```

### ✅ 正しい方法

```html
<!-- ✅ これが正しい -->
<section class="article-section">
    <h2>見出し</h2>
    <p>本文...</p>
</section>
```

### ❌ 間違い2: 完全な HTML ドキュメント

```html
<!-- ❌ これはダメ -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>記事タイトル</title>
</head>
<body>
    <p>本文...</p>
</body>
</html>
```

### ✅ 正しい方法

```html
<!-- ✅ これが正しい -->
<p>本文...</p>
```

## detail.php の仕組み

`blog/detail.php` は以下の処理を行います：

1. `posts.json` から記事のメタデータ（タイトル、日付、カテゴリなど）を取得
2. HTML の `<head>` セクションを生成（title、meta タグなど）
3. ヘッダーとナビゲーションを表示
4. 記事のタイトルと日付を表示（`<header class="article-header">`）
5. **記事ファイルの内容をそのまま読み込んで表示**
6. フッターを表示

そのため、記事ファイルには **コンテンツ部分のみ** を含める必要があります。

## トラブルシューティング

### 問題: ページタイトルが2つ表示される

**原因**: 記事ファイルに `<title>` タグが含まれている

**解決策**: 記事ファイルから `<title>` タグを削除

### 問題: 記事タイトルが2回表示される

**原因**: 記事ファイルに `<header class="article-header">` セクションが含まれている

**解決策**: 記事ファイルから `<header>` セクション全体を削除

### 問題: ページ構造がおかしい

**原因**: 記事ファイルに `<html>`, `<body>` などの HTML構造タグが含まれている

**解決策**: 記事ファイルからすべての HTML構造タグを削除し、コンテンツ部分のみにする

## 参考: 既存記事の例

既存の記事（article-1.html から article-80.html）を参考にしてください。
これらはすべて正しいフォーマットで書かれています。

```bash
# 既存記事の最初の20行を確認
head -20 blog/data/article-1-full.html
```

## まとめ

- **記事ファイルはコンテンツのみ**
- **HTML構造タグは含めない**
- **構造化データ（JSON-LD）は含めても OK**
- **検証スクリプトで必ずチェック**

---

Last updated: 2025-11-15
