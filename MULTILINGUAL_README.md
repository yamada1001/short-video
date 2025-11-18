# 多言語化システム（英語版サイト）

yojitu.comの自動多言語化システムのドキュメントです。

## 概要

このシステムは、日本語サイトを自動的に英語に翻訳して表示する仕組みです。
日本語ページが更新されると、自動的に英語版も更新されます。

## 主な機能

1. **自動翻訳**: 日本語ページを自動的に英語に翻訳
2. **キャッシュシステム**: 翻訳結果をキャッシュして高速化（有効期限: 24時間）
3. **辞書機能**: ビジネス用語・専門用語を正確に翻訳
4. **動的ルーティング**: 新しいページが追加されても自動対応
5. **SEO対応**: hreflangタグ、sitemap.xmlで多言語対応

## ディレクトリ構造

```
yojitu.com/
├── en/                          # 英語版エントリーポイント
│   ├── .htaccess               # ルーティング設定
│   └── index.php               # メインコントローラー
├── includes/
│   ├── translator.php          # 翻訳エンジン
│   └── translation_dictionary.json  # 用語辞書
├── cache/
│   └── translations/
│       └── en/                 # 英語版キャッシュ
└── sitemap.xml                 # 多言語サイトマップ
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

#### 辞書ベース翻訳（優先）

`includes/translation_dictionary.json` に登録されている用語は、辞書の訳語が使用されます：

```json
{
  "余日": "Yojitsu",
  "Web制作": "Web Development",
  "大分": "Oita"
}
```

#### API翻訳（オプション）

Google Cloud Translation API を使用する場合、環境変数を設定します：

```bash
export GOOGLE_TRANSLATE_API_KEY="your_api_key_here"
```

APIキーがない場合でも、辞書ベース翻訳は機能します。

### 4. キャッシュ管理

#### キャッシュの確認

```bash
ls -la cache/translations/en/
```

#### キャッシュのクリア

特定のページ：
```bash
rm cache/translations/en/index_php.html
```

全キャッシュ：
```bash
rm cache/translations/en/*.html
```

または、PHPコードから：
```php
require_once 'includes/translator.php';
$translator = new Translator();
$translator->clearCache(); // 全キャッシュクリア
$translator->clearCache('/index.php'); // 特定ページのみ
```

### 5. 用語辞書のカスタマイズ

`includes/translation_dictionary.json` を編集して、専門用語や固有名詞の翻訳を追加できます：

```json
{
  "新しい用語": "New Term",
  "会社名": "Company Name"
}
```

編集後、該当ページのキャッシュをクリアしてください。

## 自動更新の仕組み

### 日本語ページが更新された場合

1. 日本語ページを編集・保存
2. キャッシュの有効期限（24時間）が切れると、次回アクセス時に自動的に再翻訳
3. または、手動でキャッシュをクリアすると即座に再翻訳

### 新しいページが追加された場合

自動的に `/en/` 配下でアクセス可能になります。特別な設定は不要です。

例：
- 日本語版: `/new-page.php` を追加
- 英語版: `/en/new-page.php` で自動的にアクセス可能

## SEO対応

### hreflangタグ

全ページに自動的にhreflangタグが追加されます：

```html
<link rel="alternate" hreflang="ja" href="https://yojitu.com/index.php">
<link rel="alternate" hreflang="en" href="https://yojitu.com/en/index.php">
<link rel="alternate" hreflang="x-default" href="https://yojitu.com/index.php">
```

### サイトマップ

`sitemap.xml` に英語版のURLが追加されています。主要なページに英語版エントリーを追加済み。

## トラブルシューティング

### 翻訳が更新されない

```bash
# キャッシュをクリア
rm cache/translations/en/*.html
```

### 一部の用語が正しく翻訳されない

`includes/translation_dictionary.json` に用語を追加してください。

### 404エラーが発生する

`.htaccess` のRewriteルールが正しく動作しているか確認：

```bash
# en/.htaccess の確認
cat en/.htaccess
```

### パフォーマンスが遅い

キャッシュが正しく機能しているか確認：

```bash
ls -la cache/translations/en/
```

キャッシュファイルがある場合は、2回目以降のアクセスは高速になります。

## 開発者向け情報

### カスタム翻訳ロジックの追加

`includes/translator.php` の `translate()` メソッドをカスタマイズできます：

```php
public function translate($text, $targetLang = 'en') {
    // カスタムロジックをここに追加

    return $translatedText;
}
```

### 翻訳をスキップする要素

以下のHTML要素は翻訳されません：
- `<script>` タグ
- `<style>` タグ
- `<code>` タグ
- `<pre>` タグ

### 内部リンクの自動変換

英語版では、全ての内部リンクに `/en` プレフィックスが自動的に追加されます。

## 今後の拡張

- [ ] 他の言語（中国語、韓国語など）への対応
- [ ] 管理画面からのキャッシュクリア機能
- [ ] 翻訳品質のフィードバック機能
- [ ] ブログ・お知らせのJSONデータ翻訳
- [ ] 自動サイトマップ生成スクリプト

## サポート

質問や問題がある場合は、開発チームにお問い合わせください。

---

**最終更新**: 2025-11-18
**バージョン**: 1.0.0
