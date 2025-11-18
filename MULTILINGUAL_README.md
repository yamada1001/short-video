# 多言語化システム（英語版サイト）

yojitu.comの多言語化システムのドキュメントです。

## 概要

このシステムは、日本語サイトを自動的に英語に翻訳して表示する仕組みです。
日本語ページが更新されると、英語版も自動的に更新されます。

## 主な機能

1. **自動翻訳**: 日本語ページを実行してHTML出力を英語に翻訳
2. **辞書ベース翻訳**: JSON形式の翻訳辞書で専門用語を正確に翻訳
3. **動的ルーティング**: 新しいページが追加されても自動対応
4. **パス自動修正**: CSS、JS、画像などのパスを自動的に絶対パスに変換
5. **SEO対応**: hreflangタグ、sitemap.xmlで多言語対応

## ディレクトリ構造

```
yojitu.com/
├── en/                                # 英語版エントリーポイント
│   ├── .htaccess                     # ルーティング設定
│   └── index.php                     # メインコントローラー
├── includes/
│   ├── i18n.php                      # 翻訳ヘルパー関数
│   └── translations/
│       └── en.json                   # 英語版翻訳辞書
└── sitemap.xml                       # 多言語サイトマップ
```

## 使い方

### 1. 英語版へのアクセス

日本語版のURLに `/en` を追加するだけで英語版にアクセスできます：

- 日本語版: `https://yojitu.com/index.php`
- 英語版: `https://yojitu.com/en/index.php`

### 2. 言語切り替え

ヘッダーに言語切り替えボタンが表示されます：
- 日本語版では「English」ボタンが表示
- 英語版では「日本語」ボタンが表示

### 3. 翻訳の仕組み

#### ステップ 1: 日本語ページを実行

`/en/index.php` が日本語版のPHPファイルを実行し、HTML出力を取得します。

#### ステップ 2: 翻訳辞書に基づいて置換

`includes/translations/en.json` に定義された翻訳データに基づいて、日本語テキストを英語に置換します。

#### ステップ 3: パスの修正

- 内部リンクに `/en` プレフィックスを追加
- アセット（CSS、JS、画像）のパスを絶対パスに変換

#### ステップ 4: 出力

翻訳されたHTMLを出力します。

### 4. 翻訳辞書のカスタマイズ

`includes/translations/en.json` を編集して、翻訳を追加・修正できます：

```json
{
  "余日": "Yojitsu",
  "サービス": "Services",
  "Web制作": "Web Development"
}
```

## 自動更新の仕組み

### 日本語ページが更新された場合

日本語ページを編集・保存すると、次回アクセス時に自動的に英語版にも反映されます。
キャッシュは使用していないため、常に最新の内容が翻訳されます。

### 新しいページが追加された場合

自動的に `/en/` 配下でアクセス可能になります。特別な設定は不要です。

例：
- 日本語版: `/new-page.php` を追加
- 英語版: `/en/new-page.php` で自動的にアクセス可能

ただし、新しいページのテキストを英語化するには、`includes/translations/en.json` に翻訳データを追加する必要があります。

## SEO対応

### hreflangタグ

全ページに自動的にhreflangタグが追加されます（`includes/head.php`）：

```html
<link rel="alternate" hreflang="ja" href="https://yojitu.com/index.php">
<link rel="alternate" hreflang="en" href="https://yojitu.com/en/index.php">
<link rel="alternate" hreflang="x-default" href="https://yojitu.com/index.php">
```

### サイトマップ

`sitemap.xml` に英語版のURLが追加されています。主要なページに英語版エントリーを追加済み。

## トラブルシューティング

### 一部のテキストが翻訳されない

`includes/translations/en.json` にその日本語テキストを追加してください。

### CSSやJSが読み込まれない

`includes/head.php` のパスが正しく設定されているか確認してください。
`$css_base_path` がデフォルトで `/` に設定されています。

### 404エラーが発生する

`.htaccess` のRewriteルールが正しく動作しているか確認：

```bash
# en/.htaccess の確認
cat en/.htaccess
```

### リンクが正しく動作しない

`en/index.php` の `addEnPrefixToLinks()` 関数が正しく動作しているか確認してください。

## 開発者向け情報

### 翻訳データの追加

新しいページやセクションを追加した場合、`includes/translations/en.json` に翻訳データを追加してください：

```json
{
  "新しいテキスト": "New Text",
  "別のテキスト": "Another Text"
}
```

### 翻訳順序

翻訳は長いキーから順に置換されます。これにより、部分一致による誤翻訳を防ぎます。

### HTML属性の翻訳

現在、以下の属性は自動的には翻訳されません：
- `title`
- `alt`
- `placeholder`
- `aria-label`

これらを翻訳する場合は、`en/index.php` の `translateHTML()` 関数を拡張してください。

## パフォーマンス

キャッシュは使用していないため、毎回PHP実行と翻訳処理が行われます。
将来的にキャッシュ機能を追加することで、パフォーマンスを向上させることができます。

## 今後の拡張

- [ ] 翻訳結果のキャッシュ機能
- [ ] 他の言語（中国語、韓国語など）への対応
- [ ] HTML属性（title、altなど）の自動翻訳
- [ ] ブログ・お知らせのJSONデータ翻訳
- [ ] 自動サイトマップ生成スクリプト
- [ ] Google Cloud Translation API連携（未翻訳テキストの自動翻訳）

## サポート

質問や問題がある場合は、開発チームにお問い合わせください。

---

**最終更新**: 2025-11-18
**バージョン**: 2.0.0
