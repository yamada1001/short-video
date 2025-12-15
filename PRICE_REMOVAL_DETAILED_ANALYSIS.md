# 金額表示削除 - 詳細調査レポート

**作成日**: 2025-12-15
**調査範囲**: 全PHPファイル・HTMLファイル
**検出ファイル数**: 92ファイル（デモ・開発用除く）

---

## 📊 調査サマリー

### カテゴリ別ファイル数

| カテゴリ | ファイル数 | 金額の用途 | 優先度 |
|---------|-----------|-----------|--------|
| メインページ | 11 | 自社料金 | 最高 |
| エリアページ | 4 | 自社料金 | 高（ブロック対象） |
| ブログ記事 | 62 | 自社料金＋参考情報 | 中 |
| その他 | 15 | デモ・開発用 | 低（対象外） |

---

## 📝 メインページ詳細（11ファイル）

### 1. index.php
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/index.php`

#### 金額表示箇所
```php
// Line 43
$page_title = '【10万円〜】大分のホームページ制作・Web制作｜余日（Yojitsu）';

// Line 44
$page_description = '大分県のホームページ制作・Web制作なら余日へ。10万円からの格安料金、AI活用で1週間で初稿提出、月額5,800円で更新し放題。大分市・別府市など県内全域対応。個人事業主・中小企業向けのプロフェッショナルなWeb制作。';

// Line 45
$page_keywords = '大分,ホームページ制作,Web制作,格安,10万円,AI,大分市,別府市,中小企業,個人事業主,余日,Yojitsu';

// Line 101
"priceRange": "¥100,000〜",

// Line 133-134
"text": "Web制作は10万円プラン・30万円プラン・カスタムプランの3種類、ショート動画は1本2万円または10本セット15万円です。いずれも月額5,800円の保守は別途必要です。"

// Line 321
<p class="service-card__price">300,000円〜</p>

// Line 338
<p class="service-card__price">1本 20,000円〜</p>
```

#### 修正内容
- タイトルから「【10万円〜】」を削除
- descriptionから「10万円からの格安料金、」「月額5,800円で更新し放題」を削除
- keywordsから「10万円,」を削除
- 構造化データの`priceRange`を削除
- FAQの料金回答を「お見積りによる」に変更
- サービスカードの料金表示を削除

---

### 2. web-production.php
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/web-production.php`

#### 金額表示箇所
```php
// Line 6
$page_title = 'ホームページ制作の料金プラン・制作の流れ｜10万円〜30万円｜余日（Yojitsu）';

// Line 7
$page_description = 'ホームページ制作の料金プラン（10万円・30万円・カスタム）、制作の流れ、AI活用による短納期を詳しく解説。月額5,800円で更新し放題。レスポンシブデザイン、SEO最適化、WordPress対応。個人事業主・中小企業向けのプロフェッショナルなWeb制作サービスの詳細はこちら。';

// Line 8
$page_keywords = 'ホームページ制作,料金,プラン,制作の流れ,10万円,30万円,AI,月額,更新,レスポンシブ,SEO,WordPress,余日';

// Line 48-70 - 構造化データのoffers
{
  "@type": "Offer",
  "name": "10万円プラン",
  "price": "100000",
  "priceCurrency": "JPY",
  "description": "個人事業主の方や安く早く作りたい方向け"
},
{
  "@type": "Offer",
  "name": "30万円プラン",
  "price": "300000",
  "priceCurrency": "JPY",
  "description": "ブログ機能やWebからの集客を目指す方向け"
},
{
  "@type": "Offer",
  "name": "カスタムプラン",
  "priceRange": "500万円〜1200万円",
  "priceCurrency": "JPY",
  "description": "オリジナルデザイン・CMS開発など本格的なWeb制作"
}

// Line 84
"text": "10万円プラン、30万円プラン、カスタムプランの3種類をご用意しています。10万円プランは個人事業主向けのシンプルなサイト、30万円プランはブログ機能やSEO対策込み、カスタムプランは本格的なシステム開発向けです。"

// Line 100
"text": "はい、月額5,800円の保守契約は必須です。テキスト・画像の変更し放題、サーバー・ドメイン管理、セキュリティ対策、電話・メールサポートが含まれます。"
```

#### 修正内容
- タイトルから「｜10万円〜30万円」を削除
- descriptionから料金情報を削除
- keywordsから「10万円,30万円,月額,」を削除
- 構造化データのoffers全体を修正（価格情報削除）
- FAQの料金回答を「お見積りによる」に変更
- 料金プランセクション（HTML）を削除または「お問い合わせください」に変更

---

### 3. video-production.php
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/video-production.php`

#### 金額表示箇所
```php
// Line 6
$page_title = '【1本2万円〜】大分のショート動画制作｜TikTok・Instagram・YouTube対応｜余日';

// Line 7
$page_description = '大分県のショート動画制作なら余日へ。TikTok、Instagram Reels、YouTube Shortsに最適化。1本2万円から、企画・撮影・編集まで一貫対応。10本セット15万円（25%OFF）。大分県内出張費無料。個人事業主・中小企業のSNSマーケティングをサポート。';

// Line 8
$page_keywords = 'ショート動画,動画制作,TikTok,Instagram Reels,YouTube Shorts,大分,大分市,別府市,SNSマーケティング,余日';

// Line 48-63 - 構造化データのoffers
{
  "@type": "Offer",
  "name": "基本プラン",
  "price": "20000",
  "priceCurrency": "JPY",
  "description": "15〜60秒の動画1本"
},
{
  "@type": "Offer",
  "name": "10本セット",
  "price": "150000",
  "priceCurrency": "JPY",
  "description": "15〜60秒の動画10本（25%OFF）"
}

// Line 76
"text": "1本2万円から制作可能です。撮影・編集込みの料金です。10本セットなら15万円（25%OFF）でさらにお得です。大分県内への出張費は無料です。"
```

#### 修正内容
- タイトルから「【1本2万円〜】」を削除
- descriptionから「1本2万円から、」「10本セット15万円（25%OFF）」を削除
- 構造化データのoffers全体を修正
- FAQの料金回答を「お見積りによる」に変更

---

### 4. services.php
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/services.php`

#### 金額表示箇所
```php
// Line 7
$page_description = '大分県を拠点にデジタルマーケティングをトータルサポート。ホームページ制作（10万円〜）、ショート動画制作（1本2万円〜）、SEO対策、Google広告・SNS広告運用。大分市・別府市など県内全域対応。個人事業主・中小企業向けのプロフェッショナルなサービス。';

// Line 8
$page_keywords = 'デジタルマーケティング,Web制作,ホームページ制作,ショート動画,動画制作,SEO,広告運用,大分,大分市,別府市,余日';
```

#### 修正内容
- descriptionから「（10万円〜）」「（1本2万円〜）」を削除
- HTMLのサービスカード料金表示を削除

---

### 5. recruit.php
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/recruit.php`

#### 金額表示箇所
- ライター報酬: 1本5,000円〜10,000円

#### 修正内容
- 報酬を「スキルに応じて相談」に変更

---

### 6. tokushoho.html
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/tokushoho.html`

#### 金額表示箇所
販売価格の項目に具体的な金額が記載されています。

#### 修正内容
```html
<!-- Before -->
<tr>
    <th>販売価格</th>
    <td>各サービスページに記載の通り。お見積もりにより個別に提示いたします。</td>
</tr>

<!-- After -->
<tr>
    <th>販売価格</th>
    <td>各サービスページをご確認ください。お見積もりにより個別に提示いたします。</td>
</tr>
```

---

### 7. llms.txt
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/llms.txt`

#### 金額表示箇所
- Web制作: 10万円、30万円
- 動画制作: 2万円
- 保守: 月額5,800円
- 10本セット: 15万円

#### 修正内容
全ての料金情報を削除し、「料金はお見積りとなります。詳細はお問い合わせください。」のみ記載

---

### 8. includes/data/services.json
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/includes/data/services.json`

#### 金額表示箇所
```json
{
    "name": "Web制作",
    "price": "300,000円〜",
    "description": "..."
}
```

#### 修正内容
```json
{
    "name": "Web制作",
    "price": "お見積り",
    "description": "..."
}
```

---

### 9. includes/translations/en.json
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/includes/translations/en.json`

#### 修正内容
英語版の料金情報を"Quote required"に変更

---

### 10. includes/area-section.php
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/includes/area-section.php`

#### 修正内容
エリアセクション自体をコメントアウトまたは`/area/`へのリンクを削除

---

### 11. includes/cta.php
**ファイルパス**: `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/includes/cta.php`

#### 修正内容
CTAの料金訴求（「10万円から」「格安」など）を削除

---

## 📍 エリアページ詳細（4ファイル）

### 1. area/index.php
**金額表示箇所**:
- Line 23: タイトル「【10万円〜】大分県全18市町村対応」
- Line 24: description「10万円から、AI活用で1週間で初稿提出」
- Line 108: Hero「10万円〜」
- Line 164: Features「10万円からの格安料金」
- Line 171: Features「月額5,800円で更新し放題」

**対応**: .htaccessでブロックするため修正不要（念のため金額削除も可）

---

### 2. area/detail.php
**金額表示箇所**:
- Line 32: タイトル「【10万円〜】」
- Line 33: description「10万円からの格安料金」
- Line 98: 構造化データ「¥100,000〜」
- Line 111-144: offers全体（10万円、30万円、5,800円）
- Line 188: FAQ「10万円プラン、30万円プラン、...月額5,800円」
- Line 243: Hero「10万円〜」
- Line 270: Features「10万円からの格安料金」
- Line 284: Features「月額5,800円で更新し放題」
- Line 354-433: 料金プランセクション全体
- Line 417-433: 保守セクション「月額5,800円」

**対応**: .htaccessでブロックするため修正不要

---

### 3. area/video/index.php
**金額表示箇所**:
- Line 29: タイトル「【2万円〜】」
- Line 30: description「1本2万円から」
- Line 90-98: Hero料金表示

**対応**: .htaccessでブロックするため修正不要

---

### 4. area/video/detail.php
**金額表示箇所**: area/detail.phpと同様の構造

**対応**: .htaccessでブロックするため修正不要

---

## 📰 ブログ記事詳細（62ファイル）

### 自社料金が記載されている可能性のある記事

以下の記事をチェックし、自社料金の記載があれば削除します。

| # | ファイル名 | 内容 | 優先度 |
|---|----------|------|--------|
| 1 | article-81-full.html | HP制作費の相場（30〜100万円など） | 高 |
| 2 | article-84-full.html | Web広告費（月5,000円〜など） | 中 |
| 3 | article-85-full.html | HP運用費（月額1,000円〜50,000円など） | 中 |
| 4 | article-76-full.html | AIツール料金（月額2,480円〜など） | 低 |
| 5 | article-78-full.html | LLMO対策費用（10〜30万円など） | 中 |
| 6 | article-103-full.html | 動画編集相場（1本1,000〜50,000円など） | 中 |
| 7 | article-110-full.html | HP制作相場（10〜50万円など） | 高 |
| 8 | article-87-full.html | 金額記載あり | 中 |
| 9 | article-88-full.html | 金額記載あり | 中 |
| 10 | article-89-full.html | 金額記載あり | 中 |
| 11 | article-91-full.html | 金額記載あり | 中 |
| 12 | article-92-full.html | 金額記載あり | 中 |
| 13 | article-96-full.html | ビジネス事例の売上金額（月間120万円など） | 低 |
| 14 | article-100-full.html | 金額記載あり | 中 |
| 15 | article-102-full.html | 金額記載あり | 中 |

### 作業手順

1. **自社料金パターンの検索**
```bash
grep -n "余日.*[0-9０-９]+万円\|当社.*[0-9０-９]+万円\|弊社.*[0-9０-９]+万円" blog/data/article-*.html
```

2. **該当箇所の確認と修正**
- 自社料金の場合: 削除または「お見積りによる」に変更
- 相場情報の場合: そのまま保持

3. **例**
```html
<!-- 削除対象 -->
余日では10万円からホームページ制作を承っています。

<!-- 保持対象 -->
一般的な制作会社では、30万円〜100万円が相場です。
```

---

## 🔍 検索用正規表現パターン

### 自社料金の検索
```regex
(余日|当社|弊社|私たち).*?([0-9０-９]+万円|[0-9,０-９]+円)
```

### 金額表示全般の検索
```regex
[0-9０-９]+万円|[0-9,０-９]+円|¥[0-9,]+|円〜
```

### 料金関連キーワードの検索
```regex
(料金|価格|費用|プラン|お見積|見積もり|格安|低価格)
```

---

## 📝 置換例

### タイトル
```php
// Before
$page_title = '【10万円〜】大分のホームページ制作・Web制作｜余日（Yojitsu）';

// After
$page_title = '大分のホームページ制作・Web制作｜余日（Yojitsu）';
```

### Description
```php
// Before
$page_description = '大分県のホームページ制作なら余日へ。10万円からの格安料金、AI活用で1週間で初稿提出、月額5,800円で更新し放題。';

// After
$page_description = '大分県のホームページ制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。';
```

### 構造化データ
```php
// Before
"priceRange": "¥100,000〜",
"offers": [
    {
        "@type": "Offer",
        "name": "10万円プラン",
        "price": "100000",
        "priceCurrency": "JPY"
    }
]

// After
"offers": [
    {
        "@type": "Offer",
        "name": "スタンダードプラン",
        "description": "お見積りはお問い合わせください"
    }
]
```

### FAQ
```php
// Before
"text": "10万円プラン、30万円プラン、カスタムプランの3種類をご用意しています。"

// After
"text": "お客様のご予算・ご要望に応じて最適なプランをご提案いたします。お見積りは無料ですので、お気軽にお問い合わせください。"
```

### HTML
```html
<!-- Before -->
<p class="service-card__price">300,000円〜</p>

<!-- After -->
<p class="service-card__price">お見積り</p>
```

---

## 🛠️ 便利なコマンド

### 金額表示の一括検索
```bash
# 全PHPファイルで金額表示を検索
find . -name "*.php" -type f \
  -not -path "./demo/*" \
  -not -path "./bni-slide-system/*" \
  -not -path "./proposal/*" \
  -exec grep -l "[0-9０-９]*万円\|[0-9,０-９]*円\|¥[0-9,]*" {} \;

# ブログ記事で自社料金を検索
grep -rn "余日.*[0-9０-９]*万円\|当社.*[0-9０-９]*万円" blog/data/article-*.html
```

### 特定パターンの置換
```bash
# 「10万円〜」を削除
find . -name "*.php" -type f -exec sed -i '' 's/10万円〜//g' {} \;

# 「月額5,800円」を削除
find . -name "*.php" -type f -exec sed -i '' 's/月額5,800円//g' {} \;
```

### 修正後の確認
```bash
# 金額表示が残っていないか確認
grep -r "10万円\|30万円\|2万円\|5,800円" \
  --include="*.php" --include="*.html" \
  --exclude-dir=demo --exclude-dir=bni-slide-system \
  .
```

---

## 📚 参考情報

### 金額記載のあるブログ記事一覧

| 記事ID | タイトル | 金額記載内容 | 削除対象 |
|--------|---------|------------|---------|
| 76 | AIツール紹介 | 各ツールの月額料金 | × 参考情報 |
| 78 | LLMO対策 | 対策費用の相場 | × 参考情報 |
| 81 | HP制作費 | 業界相場 | × 参考情報 |
| 84 | Web広告 | 広告費の目安 | × 参考情報 |
| 85 | HP運用費 | 運用費の相場 | × 参考情報 |
| 87 | 要確認 | 要確認 | 要確認 |
| 88 | 要確認 | 要確認 | 要確認 |
| 89 | 要確認 | 要確認 | 要確認 |
| 91 | 要確認 | 要確認 | 要確認 |
| 92 | 要確認 | 要確認 | 要確認 |
| 96 | ビジネス事例 | 売上金額 | × 参考情報 |
| 100 | 要確認 | 要確認 | 要確認 |
| 102 | 要確認 | 要確認 | 要確認 |
| 103 | 動画編集相場 | 相場情報 | × 参考情報 |
| 110 | HP制作相場 | 相場情報 | × 参考情報 |

※ 「要確認」の記事は実際にファイルを開いて自社料金の記載があるかチェックが必要

---

**最終更新日**: 2025-12-15
