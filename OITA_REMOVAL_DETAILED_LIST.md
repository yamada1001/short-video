# 大分エリア対策削除 詳細修正リスト

**作成日**: 2025-12-15
**目的**: 作業落ち時の復帰用・詳細な修正前後対応表

---

## 📄 index.php（12箇所修正）

### 1. タイトルタグ（43行目）
```php
// Before
$page_title = '大分のホームページ制作・Web制作｜余日（Yojitsu）';

// After
$page_title = 'ホームページ制作・Web制作｜余日（Yojitsu）';
```

### 2. メタディスクリプション（44行目）
```php
// Before
$page_description = '大分県のホームページ制作・Web制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。大分市・別府市など県内全域対応。個人事業主・中小企業向けのプロフェッショナルなWeb制作。';

// After
$page_description = 'ホームページ制作・Web制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。個人事業主・中小企業向けのプロフェッショナルなWeb制作。全国対応。';
```

### 3. キーワードメタタグ（45行目）
```php
// Before
$page_keywords = '大分,ホームページ制作,Web制作,AI,大分市,別府市,中小企業,個人事業主,余日,Yojitsu';

// After
$page_keywords = 'ホームページ制作,Web制作,AI,中小企業,個人事業主,システム開発,余日,Yojitsu';
```

### 4. OGPタイトル（53行目）
```php
// Before
<meta property="og:title" content="大分のホームページ制作・Web制作｜余日（Yojitsu）">

// After
<meta property="og:title" content="ホームページ制作・Web制作｜余日（Yojitsu）">
```

### 5. OGPディスクリプション（54行目）
```php
// Before
<meta property="og:description" content="大分県のホームページ制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。大分市・別府市など県内全域対応。">

// After
<meta property="og:description" content="ホームページ制作・Web制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。全国対応。">
```

### 6. 構造化データ description（74行目）
```json
// Before
"description": "大分県のホームページ制作・Web制作会社。AI活用で1週間で初稿提出、充実のサポート体制。大分市・別府市など県内全域対応。個人事業主・中小企業向けのプロフェッショナルなWeb制作。",

// After
"description": "ホームページ制作・Web制作会社。AI活用で1週間で初稿提出、充実のサポート体制。個人事業主・中小企業向けのプロフェッショナルなWeb制作。全国対応。",
```

### 7. 構造化データ address（82-86行目）
```json
// Before
"address": {
    "@type": "PostalAddress",
    "addressRegion": "大分県",
    "addressCountry": "JP"
},

// After
"address": {
    "@type": "PostalAddress",
    "addressRegion": "日本",
    "addressCountry": "JP"
},
```

### 8. 構造化データ areaServed（87-99行目）
```json
// Before
"areaServed": [
    {
        "@type": "City",
        "name": "大分市",
        "addressRegion": "大分県"
    },
    {
        "@type": "City",
        "name": "別府市",
        "addressRegion": "大分県"
    },
    {
        "@type": "State",
        "name": "大分県"
    }
],

// After
削除（"areaServed"プロパティごと削除）
```

### 9. 構造化データ keywords（102行目）
```json
// Before
"keywords": ["大分", "ホームページ制作", "Web制作", "AI", "中小企業", "個人事業主"]

// After
"keywords": ["ホームページ制作", "Web制作", "AI", "システム開発", "中小企業", "個人事業主"]
```

### 10. FAQ JSON-LD（116行目）
```json
// Before
"text": "大分県を拠点に、Web制作（ホームページ制作）とショート動画制作を提供しています。お客様のご要望に応じて最適なプランをご提案いたします。"

// After
"text": "Web制作（ホームページ制作）、ショート動画制作、システム開発を提供しています。全国対応で、お客様のご要望に応じて最適なプランをご提案いたします。"
```

### 11. FAQ JSON-LD（124行目）
```json
// Before
"text": "はい、全国対応可能です。Web制作はオンラインで完結でき、ショート動画は大分県内は出張費無料、県外は別途ご相談となります。"

// After
"text": "はい、全国対応可能です。オンラインで全国どこからでも対応いたします。詳細はお問い合わせください。"
```

### 12. ヒーローテキスト（253行目）
```html
<!-- Before -->
<p class="hero-v2__text" data-hero-text>大分から始まる、新しいビジネスの形</p>

<!-- After -->
<p class="hero-v2__text" data-hero-text>ここから始まる、新しいビジネスの形</p>
```

### 13. FAQ HTML 質問（506行目）
```html
<!-- Before -->
<span class="faq-q-text">大分県以外でも対応可能ですか？</span>

<!-- After -->
<span class="faq-q-text">全国対応していますか？</span>
```

### 14. FAQ HTML 回答（511行目）
```html
<!-- Before -->
<p>はい、全国対応可能です。Web制作はオンラインで完結でき、ショート動画は大分県内は出張費無料、県外は別途ご相談となります。</p>

<!-- After -->
<p>はい、全国対応可能です。オンラインで全国どこからでも対応いたします。詳細はお問い合わせください。</p>
```

---

## 📄 web-production.php（6箇所修正）

### 1. 構造化データ areaServed（23-46行目）
```json
// Before
"areaServed": [
    { "@type": "City", "name": "大分市", "addressRegion": "大分県" },
    { "@type": "City", "name": "別府市", "addressRegion": "大分県" },
    // ... 全18市町村
],

// After
削除（"areaServed"プロパティごと削除）
```

### 2. FAQ JSON-LD（99-103行目）
```json
// Before
{
    "@type": "Question",
    "name": "大分県以外でも対応可能ですか？",
    "acceptedAnswer": {
        "@type": "Answer",
        "text": "はい、全国対応可能です。オンラインでのヒアリング・打ち合わせで制作を進められます。"
    }
}

// After
削除（質問ごと削除）
```

### 3. 対応エリアセクション見出し（351行目）
```html
<!-- Before -->
<h3><i class="fas fa-map-marked-alt"></i> 大分県全域に対応しています</h3>

<!-- After -->
<h3><i class="fas fa-globe"></i> 全国対応しています</h3>
```

### 4. 対応エリアセクション本文（352-355行目）
```html
<!-- Before -->
<p>
    大分市、別府市、中津市、日田市など、大分県全18市町村に対応しています。<br>
    各エリアの詳細情報は <a href="/area/" style="color: var(--color-natural-brown); text-decoration: underline;">対応エリアページ</a> をご覧ください。
</p>

<!-- After -->
<p>
    オンラインで全国どこからでもご依頼いただけます。<br>
    対面でのお打ち合わせも可能です。お気軽にご相談ください。
</p>
```

---

## 📄 video-production.php（10箇所修正）

### 1. タイトルタグ（6行目）
```php
// Before
$page_title = '大分のショート動画制作｜TikTok・Instagram・YouTube対応｜余日';

// After
$page_title = 'ショート動画制作｜TikTok・Instagram・YouTube対応｜余日';
```

### 2. メタディスクリプション（7行目）
```php
// Before
$page_description = '大分県のショート動画制作なら余日へ。TikTok、Instagram Reels、YouTube Shortsに最適化。企画・撮影・編集まで一貫対応。大分県内出張費無料。個人事業主・中小企業のSNSマーケティングをサポート。';

// After
$page_description = 'ショート動画制作なら余日へ。TikTok、Instagram Reels、YouTube Shortsに最適化。企画・撮影・編集まで一貫対応。個人事業主・中小企業のSNSマーケティングをサポート。全国対応。';
```

### 3. キーワードメタタグ（8行目）
```php
// Before
$page_keywords = 'ショート動画,動画制作,TikTok,Instagram Reels,YouTube Shorts,大分,大分市,別府市,SNSマーケティング,余日';

// After
$page_keywords = 'ショート動画,動画制作,TikTok,Instagram Reels,YouTube Shorts,SNSマーケティング,余日';
```

### 4. 構造化データ areaServed（23-46行目）
```json
// Before
"areaServed": [全18市町村]

// After
削除
```

### 5. FAQ JSON-LD 回答（73行目）
```json
// Before
"text": "お客様のご要望や動画の内容により異なります。撮影・編集込みのお見積りを無料でご提案いたします。大分県内への出張費は無料です。お気軽にお問い合わせください。"

// After
"text": "お客様のご要望や動画の内容により異なります。撮影・編集込みのお見積りを無料でご提案いたします。お気軽にお問い合わせください。"
```

### 6. FAQ JSON-LD 質問（94-98行目）
```json
// Before
{
    "@type": "Question",
    "name": "大分県外でも対応可能ですか？",
    "acceptedAnswer": {
        "@type": "Answer",
        "text": "はい、対応可能です。ただし大分県外の場合は別途出張費が発生します。詳細はお問い合わせください。"
    }
}

// After
削除（質問ごと削除）
```

### 7. 対応エリアセクション見出し（761行目）
```html
<!-- Before -->
<h2 class="section__subtitle"><i class="fas fa-map-marker-alt"></i> 大分県内の対応エリア</h2>

<!-- After -->
削除（セクションごと削除）
```

### 8. 対応エリアセクション本文（763行目）
```html
<!-- Before -->
<p style="text-align: center; margin-bottom: var(--spacing-lg); color: var(--color-text-light);">大分県全18市町村に対応。県内出張費は<strong>無料</strong>です。</p>

<!-- After -->
削除（セクションごと削除）
```

---

## 📄 services.php（3箇所修正）

### 1. タイトルタグ（6行目）
```php
// Before
$page_title = '大分のデジタルマーケティングサービス｜Web制作・動画制作・SEO・広告運用｜余日';

// After
$page_title = 'デジタルマーケティングサービス｜Web制作・動画制作・システム開発｜余日';
```

### 2. メタディスクリプション（7行目）
```php
// Before
$page_description = '大分県を拠点にデジタルマーケティングをトータルサポート。ホームページ制作、ショート動画制作、SEO対策、Google広告・SNS広告運用。大分市・別府市など県内全域対応。個人事業主・中小企業向けのプロフェッショナルなサービス。';

// After
$page_description = 'デジタルマーケティングをトータルサポート。ホームページ制作、ショート動画制作、システム開発。個人事業主・中小企業向けのプロフェッショナルなサービス。全国対応。';
```

### 3. キーワードメタタグ（8行目）
```php
// Before
$page_keywords = 'デジタルマーケティング,Web制作,ホームページ制作,ショート動画,動画制作,SEO,広告運用,大分,大分市,別府市,余日';

// After
$page_keywords = 'デジタルマーケティング,Web制作,ホームページ制作,ショート動画,動画制作,システム開発,余日';
```

---

## 📄 about.php（3箇所修正）

### 1. 会社説明（40行目）
```html
<!-- Before -->
特に大分県内の企業様を中心に、地域に根ざしたマーケティング支援を行っています。

<!-- After -->
全国の企業様にマーケティング支援を行っています。
```

### 2. バリュープロポジション（132行目）
```html
<!-- Before -->
<h3 class="value-item__title">地域貢献</h3>
<p class="value-item__description">大分県の企業様と共に成長し、地域経済の発展に寄与します。</p>

<!-- After -->
<h3 class="value-item__title">共に成長</h3>
<p class="value-item__description">全国の企業様と共に成長し、事業発展に寄与します。</p>
```

---

## 🔍 検証用grepコマンド

### 削除確認（残すべきもの以外）
```bash
grep -rn "大分" index.php web-production.php video-production.php services.php about.php | \
  grep -v "実績\|事例\|制作事例\|COMPANY_LOCATION\|拠点とした"
```

### 残すべきもの確認
```bash
# about.php 37行目: 会社所在地
grep -n "大分県を拠点とした" about.php

# web-production.php: 実績
grep -n "大分県のIT移住" web-production.php

# includes/config.php: 会社情報
grep -n "COMPANY_LOCATION" includes/config.php
```

---

**作成日**: 2025-12-15
**用途**: 作業復帰用詳細リスト
