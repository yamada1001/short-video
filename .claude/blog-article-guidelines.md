# ブログ記事作成ガイドライン

このドキュメントは、yojitu.comのブログ記事を作成する際の必須ルールと推奨事項をまとめたものです。

## 🚫 絶対に守るべきルール

### 1. CTAセクションについて
**❌ 禁止事項**
- `cta-section` クラスの使用
- `cta-button` クラスの使用
- インラインスタイル（`style="text-align: center;"` など）の使用

**✅ 正しい書き方**
```html
<h4><i class="fas fa-hands-helping"></i> Web制作・AI活用でお悩みの方へ</h4>

<p>余日（Yojitsu）では、AIを活用したWeb制作、デジタルマーケティング、業務効率化のご支援を行っています。（サービス説明）</p>

<p><a href="/contact.php"><i class="fas fa-envelope"></i> 無料相談はこちら</a></p>
```

### 2. インラインスタイルの禁止
- 記事本文内で `style=""` 属性を使用しないこと
- 全てのスタイリングはCSSクラスで行う

### 3. 絵文字の使用禁止
- テキストやタイトルに絵文字（😊、🚀など）を使用しない
- 代わりにFont Awesomeアイコンを使用する

### 4. カテゴリの統一
- AI関連記事のカテゴリは「**AI**」に統一（「AI・機械学習」ではない）
- その他のカテゴリも既存の統一ルールに従う

## ✅ 推奨事項

### Font Awesomeアイコンの活用

**見出しにアイコンを使用**
```html
<h2 id="heading-0"><i class="fas fa-robot"></i> セクションタイトル</h2>
<h3><i class="fas fa-check-circle"></i> サブセクション</h3>
```

**外部リンクにアイコン**
```html
<a href="https://example.com" target="_blank" rel="noopener noreferrer">
  <i class="fas fa-external-link-alt"></i> 外部サイト名
</a>
```

**内部リンクにアイコン**
```html
<a href="/blog/detail.php?slug=example">
  <i class="fas fa-link"></i> 関連記事タイトル
</a>
```

**よく使うアイコン一覧**
- `fa-robot` - AI・技術関連
- `fa-lightbulb` - ポイント・アイデア
- `fa-check-circle` - チェック・確認
- `fa-info-circle` - 情報
- `fa-exclamation-circle` - 注意・警告
- `fa-trophy` - 特徴・成果
- `fa-chart-bar` - データ・統計
- `fa-code-branch` - 比較・分岐
- `fa-dollar-sign` - 料金・価格
- `fa-briefcase` - ビジネス・活用
- `fa-rocket` - スタート・開始
- `fa-book` - まとめ・学習
- `fa-external-link-alt` - 外部リンク
- `fa-link` - 内部リンク
- `fa-envelope` - お問い合わせ
- `fa-hands-helping` - サポート・支援

### ボックス要素の使用

**利用可能なボックスクラス**
- `point-box` - 重要なポイント
- `note-box` - 注意事項
- `highlight-box` - 特に強調したい内容
- `tip-box` - ヒント・コツ
- `example-box` - 具体例
- `summary-box` - まとめ
- `flow-diagram` - フロー図

**使用例**
```html
<div class="point-box">
    <h4><i class="fas fa-check-circle"></i> タイトル</h4>
    <p>内容</p>
</div>
```

## 📝 記事構成

### 必須要素

1. **導入部分**
   - 記事の背景・問題提起
   - 記事で得られる情報の要約
   - point-boxで「この記事でわかること」を箇条書き

2. **本文**
   - H2、H3、H4を適切に使った階層構造
   - 各セクションにFont Awesomeアイコン
   - 表やリストを活用した視覚的な整理

3. **まとめセクション**
   - summary-boxで重要ポイントを箇条書き
   - 推奨事項や使い分けを表形式で提示

4. **CTAセクション**
   - H4見出し
   - サービス説明の段落
   - お問い合わせリンク（シンプルな段落として）

5. **参考文献**
   - `reference-list` クラスのリスト
   - 外部リンクにはFont Awesomeアイコン

### 構造化データ（JSON-LD）

記事の最後に必ず以下を含める：

1. **TechArticle または Article**
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TechArticle",
  "headline": "記事タイトル",
  "description": "記事の説明",
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
  "datePublished": "2025-11-28T10:00:00+09:00",
  "dateModified": "2025-11-28T10:00:00+09:00"
}
</script>
```

2. **FAQPage（任意だが推奨）**
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "質問",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "回答"
      }
    }
  ]
}
</script>
```

## 🔗 リンク設定

### 内部リンク
- 関連する過去記事へのリンクを積極的に設置
- サービスページへのリンク（/services/web-production.php、/services/seo.phpなど）
- Font Awesomeアイコン `fa-link` を使用

### 外部リンク
- 信頼性の高いソースへのリンク
- 必ず `target="_blank" rel="noopener noreferrer"` を設定
- Font Awesomeアイコン `fa-external-link-alt` を使用
- ユーザーにとって有益な場合は積極的に設置

## 📊 表の活用

比較や一覧は表形式で整理する：

```html
<table>
<thead>
<tr>
    <th>項目1</th>
    <th>項目2</th>
    <th>項目3</th>
</tr>
</thead>
<tbody>
<tr>
    <td>内容1</td>
    <td>内容2</td>
    <td>内容3</td>
</tr>
</tbody>
</table>
```

## 🎯 SEO対策

### メタ情報（posts.json）
- `title`: 30-35文字程度
- `excerpt`: 120文字前後
- `category`: 適切なカテゴリを選択（AI関連は「AI」に統一）
- `tags`: 関連するタグを5-8個程度
- `author`: "山田 蓮"

### 見出しID
- H2見出しには必ず `id="heading-N"` を設定（Nは0から始まる連番）
- 目次の自動生成に使用される

## 🔍 競合リサーチ

記事作成前に必ず実施：
1. Excelの競合データを確認
2. 主要サイトから追加情報を収集
3. ユーザーの検索意図を把握
4. 既存記事にない独自の価値を追加

## ✅ チェックリスト

記事作成後、以下を確認：

- [ ] CTAセクションにcta-section、cta-buttonクラスを使用していない
- [ ] インラインスタイルを使用していない
- [ ] 絵文字を使用していない
- [ ] カテゴリが統一ルールに従っている（AI関連は「AI」）
- [ ] Font Awesomeアイコンを適切に使用している
- [ ] 内部リンクを3つ以上設置している
- [ ] 外部リンクに適切な属性を設定している
- [ ] 構造化データ（JSON-LD）を含めている
- [ ] H2見出しにidを設定している
- [ ] 表やリストで情報を整理している
- [ ] 参考文献を記載している

## 📁 ファイル構成

新規記事作成時の手順：
1. `blog/data/article-[N]-full.html` を作成
2. `blog/data/posts.json` を更新（配列の先頭に追加）
3. `php generate-sitemap.php` でsitemap.xmlを更新
4. Git commit & push

---

**最終更新**: 2025-11-28
**管理者**: 山田 蓮
