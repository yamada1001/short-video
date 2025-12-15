# 大分エリア対策削除＋システム開発追加 作業計画書

**作成日**: 2025-12-15
**作業者**: Claude Code
**推定作業時間**: 3-4時間

---

## 📌 作業概要

### 目的
1. 「大分」エリア対策テキストを全削除（全国対応への転換）
2. 日本語の不自然な表現を修正
3. システム開発サービスを追加表示

### 背景
- ユーザー要望：「もう別に大分に特化しているわけではないので、大分用のエリア対策は捨てて良い」
- web-production.phpに方言的な表現「かかんの」が存在
- システム開発も実施しているが、サイト上で明示されていない

---

## 🎯 作業フェーズ

### Phase 1: MDファイル作成 ✅
- この作業計画書の作成
- 詳細な修正箇所リストの作成

### Phase 2: 日本語修正
- web-production.php 272行目
- 「えっ、そんなかかんの？」→「えっ、そんなにかかるの？」

### Phase 3: 大分エリア対策削除（34箇所）
#### 3-1. index.php（12箇所）
#### 3-2. web-production.php（6箇所）
#### 3-3. video-production.php（10箇所）
#### 3-4. services.php（3箇所）
#### 3-5. about.php（3箇所）

### Phase 4: システム開発サービス追加
#### 4-1. services.php にカード追加
#### 4-2. index.php のサービスセクションに追加
#### 4-3. system-development.php 新規作成（詳細ページ）
#### 4-4. ヘッダーナビゲーション更新

### Phase 5: 最終確認とGitコミット
- 全ファイル grep 検証
- Git commit & push

---

## 📝 Phase 3 詳細：大分エリア対策削除リスト

### 🔴 Priority: High（SEO影響大）

#### **index.php**（12箇所）

| 行 | Before | After |
|----|--------|-------|
| 43 | `$page_title = '大分のホームページ制作・Web制作｜余日（Yojitsu）';` | `$page_title = 'ホームページ制作・Web制作｜余日（Yojitsu）';` |
| 44 | `$page_description = '大分県のホームページ制作・Web制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。大分市・別府市など県内全域対応。個人事業主・中小企業向けのプロフェッショナルなWeb制作。';` | `$page_description = 'ホームページ制作・Web制作なら余日へ。AI活用で1週間で初稿提出、充実のサポート体制。個人事業主・中小企業向けのプロフェッショナルなWeb制作。';` |
| 45 | `$page_keywords = '大分,ホームページ制作,Web制作,AI,大分市,別府市,中小企業,個人事業主,余日,Yojitsu';` | `$page_keywords = 'ホームページ制作,Web制作,AI,中小企業,個人事業主,余日,Yojitsu';` |
| 53 | OGP title に「大分の」 | 削除 |
| 54 | OGP description に地域限定表現 | 削除 |
| 74 | 構造化データ description に地域限定表現 | 削除 |
| 82-99 | `"address"` と `"areaServed"` セクション（大分県18市町村） | `"address": { "@type": "PostalAddress", "addressRegion": "日本", "addressCountry": "JP" }` に変更、`areaServed` 削除 |
| 102 | `"keywords": ["大分", ...]` | 「大分」削除 |
| 116 | FAQ JSON-LD: 「大分県を拠点に」 | 「日本全国で」に変更 |
| 124 | FAQ JSON-LD: 地域別出張費 | 「全国対応可能です。詳細はお問い合わせください。」に変更 |
| 253 | `<p class="hero-v2__text" data-hero-text">大分から始まる、新しいビジネスの形</p>` | `<p class="hero-v2__text" data-hero-text">ここから始まる、新しいビジネスの形</p>` |
| 506 | FAQ HTML: 「大分県以外でも対応可能ですか？」 | 「全国対応していますか？」に変更 |
| 511 | FAQ HTML: 地域別出張費 | 「はい、全国対応可能です。オンラインで全国どこからでも対応いたします。」 |

---

#### **web-production.php**（6箇所）

| 行 | Before | After |
|----|--------|-------|
| 6 | タイトルに「大分の」なし | 確認のみ（既に削除済みの可能性） |
| 23-46 | 構造化データ `"areaServed"` に大分県18市町村 | 削除 or 「全国」に変更 |
| 99-103 | FAQ JSON-LD: 「大分県以外でも対応可能ですか？」 | 質問ごと削除 |
| 351-355 | `<h3>大分県全域に対応しています</h3>` + 18市町村リスト | `<h3>全国対応しています</h3><p>オンラインで全国どこからでもご依頼いただけます。対面でのお打ち合わせも可能です。</p>` |

**実績セクション（490, 506行目）は残す**（実際の事例のため）

---

#### **video-production.php**（10箇所）

| 行 | Before | After |
|----|--------|-------|
| 6 | `$page_title = '大分のショート動画制作｜...';` | `$page_title = 'ショート動画制作｜TikTok・Instagram・YouTube対応｜余日';` |
| 7 | `$page_description` に「大分県」「大分県内出張費無料」 | 地域限定削除 |
| 8 | `$page_keywords` に「大分,大分市,別府市」 | 削除 |
| 23-46 | 構造化データ `"areaServed"` に大分県18市町村 | 削除 |
| 73 | FAQ JSON-LD: 「大分県内への出張費は無料」 | 「詳細はお問い合わせください」に変更 |
| 94-98 | FAQ JSON-LD: 「大分県外でも対応可能ですか？」 | 質問ごと削除 |
| 761 | `<h2>大分県内の対応エリア</h2>` | セクションごと削除 |
| 763 | 18市町村リスト | 削除 |

---

#### **services.php**（3箇所）

| 行 | Before | After |
|----|--------|-------|
| 6 | `$page_title = '大分のデジタルマーケティングサービス｜...';` | `$page_title = 'デジタルマーケティングサービス｜Web制作・動画制作・システム開発｜余日';` |
| 7 | `$page_description` に「大分県を拠点に」「大分市・別府市など県内全域対応」 | 地域限定削除 |
| 8 | `$page_keywords` に「大分,大分市,別府市」 | 削除 |

---

#### **about.php**（3箇所）

| 行 | Before | After |
|----|--------|-------|
| 37 | 「大分県を拠点とした」 | そのまま残す（事実） |
| 40 | 「特に大分県内の企業様を中心に、地域に根ざしたマーケティング支援」 | 「全国の企業様にマーケティング支援を行っています」 |
| 132 | 「大分県の企業様と共に成長し、地域経済の発展に寄与」 | 「全国の企業様と共に成長し、事業発展に寄与」 |

---

### 🟢 残すもの（事実ベース）

| ファイル | 行 | 内容 | 理由 |
|---------|---|------|------|
| about.php | 37 | 「大分県を拠点とした」 | 会社所在地は事実 |
| recruit.php | 24-36, 93 | 大分県・福岡県・東京都の勤務地 | 実際の募集条件 |
| includes/config.php | 34 | `COMPANY_LOCATION = '大分県'` | 会社情報 |
| web-production.php | 490, 506 | 実績「大分県のIT移住」 | 実際の制作事例 |

---

## 🆕 Phase 4 詳細：システム開発サービス追加

### 4-1. services.php にカード追加

**場所**: 410行目付近（動画制作カードの後）

```html
<!-- システム開発 -->
<div class="service-card service-card--system" data-service="system">
    <div class="service-card__header">
        <div class="service-card__icon">
            <i class="fas fa-cogs"></i>
        </div>
        <h2 class="service-card__title">システム開発</h2>
        <p class="service-card__subtitle">SYSTEM DEVELOPMENT</p>
    </div>
    <div class="service-card__content">
        <p class="service-card__description">
            業務効率化システムからWebアプリケーション開発まで。お客様のビジネス課題を解決するカスタムシステムを開発します。
        </p>
        <ul class="service-card__features">
            <li>
                <i class="fas fa-check-circle"></i>
                <span>業務管理システム - お見積り</span>
            </li>
            <li>
                <i class="fas fa-check-circle"></i>
                <span>Webアプリケーション - お見積り</span>
            </li>
            <li>
                <i class="fas fa-check-circle"></i>
                <span>API開発・外部連携 - お見積り</span>
            </li>
            <li>
                <i class="fas fa-check-circle"></i>
                <span>PHP/JavaScript/Python対応</span>
            </li>
            <li>
                <i class="fas fa-check-circle"></i>
                <span>データベース設計・構築</span>
            </li>
        </ul>
        <div class="service-card__cta">
            <a href="system-development.php" class="service-card__link">
                <span>詳しく見る</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
```

**CSS追加**（inline_styles内）:
```css
.service-card--system .service-card__header {
    background: var(--color-charcoal);
}

.service-card--system:hover {
    border-color: var(--color-natural-brown);
}

.service-card--system .service-card__features li i {
    color: var(--color-natural-brown);
}

.service-card--system .service-card__link {
    background: var(--color-charcoal);
    color: var(--color-bg-white);
}

.service-card--system .service-card__link:hover {
    background: var(--color-natural-brown);
    gap: var(--spacing-md);
}
```

**タブ追加**（318行目付近）:
```html
<button class="service-tab" data-service="system" onclick="switchService('system')">システム開発</button>
```

---

### 4-2. index.php のサービスセクションに追加

**場所**: サービスカード（3つ目として追加）

```html
<div class="service-item" data-aos="fade-up" data-aos-delay="400">
    <div class="service-item__icon">
        <i class="fas fa-cogs"></i>
    </div>
    <h3 class="service-item__title">システム開発</h3>
    <p class="service-item__description">
        業務効率化システムからWebアプリケーション開発まで、カスタムシステムを開発します。
    </p>
    <a href="system-development.php" class="service-item__link">
        詳しく見る <i class="fas fa-arrow-right"></i>
    </a>
</div>
```

---

### 4-3. system-development.php 新規作成

**参考**: web-production.php の構造を流用

**主要セクション**:
1. ページヘッダー
2. システム開発とは
3. こんな課題を解決
4. 開発プラン（3つ）
   - ライトプラン（小規模システム）
   - スタンダードプラン（業務管理システム）
   - エンタープライズプラン（大規模システム）
5. 開発事例
6. 技術スタック
7. 開発フロー
8. FAQ
9. CTA

---

### 4-4. ヘッダーナビゲーション更新

**includes/header.php の修正は不要**（サービスページへのリンクで対応）

---

## ✅ チェックリスト

### Phase 2: 日本語修正
- [ ] web-production.php 272行目修正
- [ ] Git commit

### Phase 3: 大分エリア対策削除
- [ ] index.php（12箇所）
- [ ] web-production.php（6箇所）
- [ ] video-production.php（10箇所）
- [ ] services.php（3箇所）
- [ ] about.php（3箇所）
- [ ] Git commit

### Phase 4: システム開発追加
- [ ] services.php カード追加
- [ ] index.php サービス追加
- [ ] system-development.php 新規作成
- [ ] Git commit

### Phase 5: 最終確認
- [ ] grep で「大分」検証（残すべきもののみ残っているか）
- [ ] 全ページブラウザ確認
- [ ] Git push

---

## 🔍 検証コマンド

### 大分テキスト確認
```bash
grep -r "大分" index.php web-production.php video-production.php services.php about.php | grep -v "実績\|事例\|COMPANY_LOCATION"
```

### システム開発追加確認
```bash
grep -r "システム開発\|system-development" index.php services.php
```

---

## 📊 作業時間見積もり

| Phase | 作業内容 | 見積時間 |
|-------|---------|---------|
| Phase 1 | MDファイル作成 | 30分 ✅ |
| Phase 2 | 日本語修正 | 10分 |
| Phase 3 | 大分エリア対策削除（34箇所） | 1.5時間 |
| Phase 4 | システム開発追加 | 1時間 |
| Phase 5 | 最終確認 | 30分 |
| **合計** | | **3-4時間** |

---

## 🚨 注意事項

1. **構造化データ (JSON-LD) の変更は慎重に**
   - シンタックスエラーがあると Google に認識されない
   - 変更後は JSON バリデーターで確認

2. **areaServed の削除**
   - 完全削除 or "全国" に変更
   - Local Business schema から一般的な Organization に変更も検討

3. **実績・事例の「大分」は残す**
   - 実際の制作事例なので削除不要
   - SEO的にも問題なし

4. **システム開発ページ**
   - 実績がない場合は「開発中」「準備中」として記載
   - または架空の事例を作成（lorem ipsum的に）

---

## 🎯 作業完了後の確認項目

- [ ] Google Search Console でエラーなし
- [ ] 構造化データテストツールでエラーなし
- [ ] 全ページが正常に表示される
- [ ] モバイル表示も確認
- [ ] タブ切り替えが動作する
- [ ] リンク切れがない

---

**作成日**: 2025-12-15
**最終更新**: 2025-12-15
