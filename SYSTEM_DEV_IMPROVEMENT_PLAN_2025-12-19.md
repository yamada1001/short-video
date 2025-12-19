# システム開発ページ改修計画
**作成日**: 2025年12月19日
**目的**: コンサルからのフィードバックを反映し、成約率向上を目指す

---

## コンサルからのフィードバック（原文）

### ✅ 評価されている点
- 全体的に構成がすごく綺麗
- ターゲットの悩みへの訴求も的確で非常に良い
- 「事務ゼロ化パック」30万円という入り口の価格設定は新規の方も検討しやすく非常に絶妙
- 月40時間の削減で10ヶ月回収という試算も根拠が現実的なので説得力がある
- 導入後のメリットや開発の流れ、よくある質問でのカスタマイズ対応についても丁寧に網羅されており、全体を通して信頼感のある良い構成

### 📝 改善提案
1. **事務ゼロ化パック**：
   「毎日1時間強のコピペ作業がゼロになる」といった現場感覚の補足を添えると、より深く刺さる

2. **売上最大化パック**：
   現状の80万円以上の投資に対して年間増益100万円という数字だと、回収までの期間や手間を考えた時に少し魅力が薄く感じてしまう。
   **→ 60万円〜に設定し直すと「初年度からしっかりプラスが出る」というお得感が強調されて、一気に引きが強くなる**

3. **イメージ画像追加**：
   文字ばかりだと離脱の原因になるので、可能だったら入れる

---

## 改修方針（ユーザー選択内容）

### 価格戦略
- ✅ **60万円〜に変更（コンサル推奨）**
- 「初年度+40万円利益」を強調して心理的ハードルを下げる

### 画像戦略
- ✅ **段階的に実装**
- まず無料SVGで速攻対応 → 後から図解や画面を追加

### 補足の見せ方
- ✅ **投資対効果の直下に目立つ補足**
- 「💡 これは毎日1時間強のコピペ作業がゼロになるのと同じです」と強調表示

### その他の改修
- ✅ パッケージ比較表を追加
- ✅ 導入事例セクションを追加

---

## タスクリスト

### フェーズ1: 価格・訴求の改善
- [ ] タスク1: 売上最大化パックの価格を60万円〜に変更
- [ ] タスク2: 売上最大化パックの投資対効果を「初年度+40万円利益」に変更
- [ ] タスク3: 事務ゼロ化パックに現場感覚の補足を追加

### フェーズ2: ビジュアル改善
- [ ] タスク4: 無料SVGイラストを各パッケージセクションに追加

### フェーズ3: コンテンツ追加
- [ ] タスク5: パッケージ比較表を作成・追加
- [ ] タスク6: 導入事例セクションを作成・追加

### 最終
- [ ] タスク7: commit & push

---

## 詳細な変更内容

### 1. 売上最大化パック - 価格変更

**対象ファイル**:
- `system-development.php` (トップページ)
- `system-development-packages.php` (パッケージ詳細ページ)

**変更箇所**:
```html
<!-- 変更前 -->
<div class="package-card__price">
    <span class="package-card__price-amount">80万円〜</span>
</div>

<!-- 変更後 -->
<div class="package-card__price">
    <span class="package-card__price-amount">60万円〜</span>
</div>
```

**グローバル検索**:
```
検索: 80万円
置換: 60万円
※ただし売上最大化パック関連のみ
```

---

### 2. 売上最大化パック - 投資対効果の変更

**対象ファイル**: `system-development-packages.php`

**変更箇所** (売上最大化パックの投資対効果セクション):

```html
<!-- 変更前 -->
<div class="package-detail__roi">
    <h3 class="package-detail__section-title">
        <i class="fas fa-chart-line"></i>
        投資対効果
    </h3>
    <div class="roi-calculation">
        <div class="roi-calculation__item">
            <div class="roi-calculation__label">想定増益</div>
            <div class="roi-calculation__value">年間 100万円</div>
        </div>
        <div class="roi-calculation__item">
            <div class="roi-calculation__label">投資額</div>
            <div class="roi-calculation__value">80万円〜</div>
        </div>
        <div class="roi-calculation__result">
            <strong>初年度から黒字化</strong>
        </div>
    </div>
</div>

<!-- 変更後 -->
<div class="package-detail__roi">
    <h3 class="package-detail__section-title">
        <i class="fas fa-chart-line"></i>
        投資対効果
    </h3>
    <div class="roi-calculation">
        <div class="roi-calculation__item">
            <div class="roi-calculation__label">想定増益</div>
            <div class="roi-calculation__value">年間 100万円</div>
        </div>
        <div class="roi-calculation__item">
            <div class="roi-calculation__label">投資額</div>
            <div class="roi-calculation__value">60万円〜</div>
        </div>
        <div class="roi-calculation__result">
            <strong>初年度から +40万円の利益</strong>
        </div>
    </div>
    <div class="roi-highlight">
        <i class="fas fa-check-circle"></i>
        <p>初年度からしっかりプラスが出る、安心の投資対効果です。</p>
    </div>
</div>
```

---

### 3. 事務ゼロ化パック - 現場感覚の補足追加

**対象ファイル**: `system-development-packages.php`

**変更箇所** (事務ゼロ化パックの投資対効果セクション直下):

```html
<!-- 既存の投資対効果セクションの後に追加 -->
<div class="package-detail__roi">
    <h3 class="package-detail__section-title">
        <i class="fas fa-chart-line"></i>
        投資対効果
    </h3>
    <div class="roi-calculation">
        <div class="roi-calculation__item">
            <div class="roi-calculation__label">削減時間</div>
            <div class="roi-calculation__value">月 40時間</div>
        </div>
        <div class="roi-calculation__item">
            <div class="roi-calculation__label">人件費削減</div>
            <div class="roi-calculation__value">月 4万円</div>
        </div>
        <div class="roi-calculation__result">
            <strong>10ヶ月で投資回収</strong>
        </div>
    </div>

    <!-- ★ここに追加★ -->
    <div class="roi-insight">
        <i class="fas fa-lightbulb"></i>
        <p><strong>これは毎日1時間強のコピペ作業がゼロになるのと同じです。</strong><br>
        事務員さんが本来やるべき業務に集中できるようになります。</p>
    </div>
</div>
```

**CSS追加** (`assets/css/pages/system-development-packages.css`):

```css
/* 現場感覚の補足ボックス */
.roi-insight {
    margin-top: 24px;
    padding: 20px;
    background: linear-gradient(135deg, #FFF9E6 0%, #FFFBF0 100%);
    border-left: 4px solid var(--system-color-accent);
    border-radius: 4px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.roi-insight i {
    color: var(--system-color-accent);
    font-size: 24px;
    margin-top: 4px;
    flex-shrink: 0;
}

.roi-insight p {
    font-size: 15px;
    line-height: 1.8;
    color: var(--system-color-primary);
    margin: 0;
}

.roi-insight strong {
    color: var(--system-color-accent);
    font-weight: 700;
}

/* 売上最大化パック用のハイライト */
.roi-highlight {
    margin-top: 24px;
    padding: 20px;
    background: linear-gradient(135deg, #E6F7FF 0%, #F0FAFF 100%);
    border-left: 4px solid #1890ff;
    border-radius: 4px;
    display: flex;
    gap: 16px;
    align-items: center;
}

.roi-highlight i {
    color: #1890ff;
    font-size: 24px;
    flex-shrink: 0;
}

.roi-highlight p {
    font-size: 15px;
    line-height: 1.8;
    color: var(--system-color-primary);
    margin: 0;
}
```

---

### 4. 無料SVGイラストの追加

**使用するイラストライブラリ**: unDraw (https://undraw.co/illustrations)

**追加するイラスト**:
1. **事務ゼロ化パック**: `data-processing.svg` (データ処理・自動化)
2. **売上最大化パック**: `revenue-growth.svg` (売上成長)
3. **オーダーメイド開発**: `code-development.svg` (カスタム開発)

**追加場所**: 各パッケージカードのヘッダー部分

**HTML例**:
```html
<div class="package-card__image">
    <img src="https://illustrations.popsy.co/amber/data-processing.svg"
         alt="事務ゼロ化パック"
         loading="lazy">
</div>
```

**CSS追加** (`assets/css/pages/system-development.css`):

```css
.package-card__image {
    width: 100%;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #F8F8F8 0%, #FFFFFF 100%);
    padding: 32px;
    border-bottom: 1px solid var(--system-color-border);
}

.package-card__image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    opacity: 0.9;
}
```

---

### 5. パッケージ比較表の追加

**追加場所**: `system-development.php` のパッケージ紹介セクションの後

**HTML**:
```html
<!-- パッケージ比較表セクション -->
<section class="package-comparison">
    <div class="container">
        <h2 class="section-title center">
            <i class="fas fa-table"></i>
            パッケージ比較表
        </h2>
        <p class="section-lead center">
            3つのパッケージを比較して、御社に最適なプランをお選びください。
        </p>

        <div class="comparison-table-wrapper">
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th class="comparison-table__header">項目</th>
                        <th class="comparison-table__header comparison-table__header--highlight">
                            事務ゼロ化パック
                        </th>
                        <th class="comparison-table__header">
                            売上最大化パック
                        </th>
                        <th class="comparison-table__header">
                            オーダーメイド開発
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="comparison-table__label">価格</td>
                        <td class="comparison-table__cell comparison-table__cell--highlight">
                            <strong>30万円〜</strong>
                        </td>
                        <td class="comparison-table__cell">
                            <strong>60万円〜</strong>
                        </td>
                        <td class="comparison-table__cell">
                            <strong>100万円〜</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="comparison-table__label">開発期間</td>
                        <td class="comparison-table__cell comparison-table__cell--highlight">
                            1〜2ヶ月
                        </td>
                        <td class="comparison-table__cell">
                            2〜3ヶ月
                        </td>
                        <td class="comparison-table__cell">
                            3〜6ヶ月
                        </td>
                    </tr>
                    <tr>
                        <td class="comparison-table__label">向いている会社</td>
                        <td class="comparison-table__cell comparison-table__cell--highlight">
                            手作業が多い<br>事務負担を減らしたい
                        </td>
                        <td class="comparison-table__cell">
                            新規顧客を増やしたい<br>売上を伸ばしたい
                        </td>
                        <td class="comparison-table__cell">
                            独自の業務フロー<br>既存システムを刷新
                        </td>
                    </tr>
                    <tr>
                        <td class="comparison-table__label">主な機能（例）</td>
                        <td class="comparison-table__cell comparison-table__cell--highlight">
                            ・自動データ取り込み<br>
                            ・帳票自動生成<br>
                            ・メール自動送信
                        </td>
                        <td class="comparison-table__cell">
                            ・予約システム<br>
                            ・ECサイト<br>
                            ・顧客管理
                        </td>
                        <td class="comparison-table__cell">
                            ・完全カスタム<br>
                            ・既存連携<br>
                            ・独自ルール対応
                        </td>
                    </tr>
                    <tr>
                        <td class="comparison-table__label">投資対効果</td>
                        <td class="comparison-table__cell comparison-table__cell--highlight">
                            10ヶ月で回収
                        </td>
                        <td class="comparison-table__cell">
                            初年度+40万円
                        </td>
                        <td class="comparison-table__cell">
                            リスク回避・効率化
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="package-comparison__cta">
            <p>どのパッケージが合うか迷ったら、まずは無料診断をご利用ください。</p>
            <a href="#contact" class="btn btn-primary btn--large">
                <i class="fas fa-comments"></i>
                無料診断を受ける
            </a>
        </div>
    </div>
</section>
```

**CSS追加** (`assets/css/pages/system-development.css`):

```css
/* ========================================
   パッケージ比較表
   ======================================== */
.package-comparison {
    padding: 80px 0;
    background: #FFFFFF;
}

.comparison-table-wrapper {
    overflow-x: auto;
    margin: 48px 0;
}

.comparison-table {
    width: 100%;
    border-collapse: collapse;
    background: #FFFFFF;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border-radius: 8px;
    overflow: hidden;
}

.comparison-table__header {
    background: var(--system-color-primary);
    color: #FFFFFF;
    padding: 20px 16px;
    font-size: 16px;
    font-weight: 700;
    text-align: center;
    border-bottom: 3px solid var(--system-color-accent);
}

.comparison-table__header--highlight {
    background: var(--system-color-accent);
    color: #FFFFFF;
}

.comparison-table__label {
    background: #F8F8F8;
    padding: 20px 16px;
    font-weight: 700;
    color: var(--system-color-primary);
    border-right: 1px solid var(--system-color-border);
    white-space: nowrap;
}

.comparison-table__cell {
    padding: 20px 16px;
    text-align: center;
    border-bottom: 1px solid var(--system-color-border);
    line-height: 1.8;
    color: var(--system-color-text);
}

.comparison-table__cell--highlight {
    background: #FFFBF0;
}

.comparison-table tbody tr:last-child .comparison-table__cell {
    border-bottom: none;
}

.package-comparison__cta {
    text-align: center;
    margin-top: 48px;
    padding: 32px;
    background: linear-gradient(135deg, #F8F8F8 0%, #FFFFFF 100%);
    border-radius: 8px;
}

.package-comparison__cta p {
    font-size: 16px;
    margin-bottom: 24px;
    color: var(--system-color-text);
}

/* レスポンシブ */
@media (max-width: 768px) {
    .comparison-table {
        font-size: 14px;
    }

    .comparison-table__header,
    .comparison-table__label,
    .comparison-table__cell {
        padding: 12px 8px;
    }
}
```

---

### 6. 導入事例セクションの追加

**追加場所**: `system-development.php` のパッケージ比較表の後

**HTML**:
```html
<!-- 導入事例セクション -->
<section class="case-studies">
    <div class="container">
        <h2 class="section-title center">
            <i class="fas fa-briefcase"></i>
            導入事例（例）
        </h2>
        <p class="section-lead center">
            実際に導入された企業様の課題と効果をご紹介します。
        </p>

        <div class="case-studies__grid">
            <!-- 事例1: 事務ゼロ化パック -->
            <div class="case-study-card">
                <div class="case-study-card__label">事務ゼロ化パック</div>
                <div class="case-study-card__company">
                    <i class="fas fa-building"></i>
                    <div>
                        <h3>大分県の建設会社様</h3>
                        <p class="case-study-card__industry">従業員15名 / 建設業</p>
                    </div>
                </div>
                <div class="case-study-card__section">
                    <h4><i class="fas fa-exclamation-triangle"></i> 導入前の課題</h4>
                    <p>見積書・請求書の作成に毎日2時間かかっており、事務員の負担が大きかった。手書き→エクセル転記→PDF化という二度手間も発生。</p>
                </div>
                <div class="case-study-card__section">
                    <h4><i class="fas fa-check-circle"></i> 導入後の効果</h4>
                    <ul>
                        <li>見積書・請求書作成が<strong>自動化</strong>され、月60時間削減</li>
                        <li>事務員が営業サポートに集中できるようになった</li>
                        <li>ミスがなくなり、顧客からの信頼も向上</li>
                    </ul>
                </div>
                <div class="case-study-card__result">
                    <strong>投資回収期間: 8ヶ月</strong>
                </div>
            </div>

            <!-- 事例2: 売上最大化パック -->
            <div class="case-study-card">
                <div class="case-study-card__label case-study-card__label--sales">売上最大化パック</div>
                <div class="case-study-card__company">
                    <i class="fas fa-store"></i>
                    <div>
                        <h3>福岡県の美容院様</h3>
                        <p class="case-study-card__industry">スタッフ8名 / 美容業</p>
                    </div>
                </div>
                <div class="case-study-card__section">
                    <h4><i class="fas fa-exclamation-triangle"></i> 導入前の課題</h4>
                    <p>電話予約のみで、営業時間外の予約を逃していた。新規顧客のリピート率も低く、売上が伸び悩んでいた。</p>
                </div>
                <div class="case-study-card__section">
                    <h4><i class="fas fa-check-circle"></i> 導入後の効果</h4>
                    <ul>
                        <li>24時間Web予約で<strong>月15件の新規予約</strong>を獲得</li>
                        <li>LINE連携で来店3日前にリマインド → キャンセル率30%減</li>
                        <li>顧客管理で誕生日クーポン配信 → リピート率20%向上</li>
                    </ul>
                </div>
                <div class="case-study-card__result">
                    <strong>年間売上: +150万円</strong>
                </div>
            </div>

            <!-- 事例3: オーダーメイド開発 -->
            <div class="case-study-card">
                <div class="case-study-card__label case-study-card__label--custom">オーダーメイド開発</div>
                <div class="case-study-card__company">
                    <i class="fas fa-industry"></i>
                    <div>
                        <h3>熊本県の製造業様</h3>
                        <p class="case-study-card__industry">従業員50名 / 製造業</p>
                    </div>
                </div>
                <div class="case-study-card__section">
                    <h4><i class="fas fa-exclamation-triangle"></i> 導入前の課題</h4>
                    <p>20年前の生産管理システムが老朽化し、サポート終了のリスクがあった。新しいパッケージソフトでは自社の複雑な工程に対応できない。</p>
                </div>
                <div class="case-study-card__section">
                    <h4><i class="fas fa-check-circle"></i> 導入後の効果</h4>
                    <ul>
                        <li>独自の工程管理ルールに完全対応した<strong>専用システム</strong>を構築</li>
                        <li>在庫管理の精度が向上し、過剰在庫を20%削減</li>
                        <li>リアルタイムで生産状況を把握でき、納期遵守率が向上</li>
                    </ul>
                </div>
                <div class="case-study-card__result">
                    <strong>システムリスクの完全排除</strong>
                </div>
            </div>

            <!-- 事例4: 事務ゼロ化パック -->
            <div class="case-study-card">
                <div class="case-study-card__label">事務ゼロ化パック</div>
                <div class="case-study-card__company">
                    <i class="fas fa-hospital"></i>
                    <div>
                        <h3>宮崎県の整骨院様</h3>
                        <p class="case-study-card__industry">スタッフ5名 / 医療・福祉</p>
                    </div>
                </div>
                <div class="case-study-card__section">
                    <h4><i class="fas fa-exclamation-triangle"></i> 導入前の課題</h4>
                    <p>保険請求の集計作業に毎月丸2日かかっており、その間は施術ができない状態。計算ミスも頻発していた。</p>
                </div>
                <div class="case-study-card__section">
                    <h4><i class="fas fa-check-circle"></i> 導入後の効果</h4>
                    <ul>
                        <li>保険請求の<strong>自動集計</strong>で作業時間を2日→2時間に短縮</li>
                        <li>計算ミスがゼロになり、返戻率が大幅に減少</li>
                        <li>空いた時間で施術に専念でき、患者様の満足度も向上</li>
                    </ul>
                </div>
                <div class="case-study-card__result">
                    <strong>投資回収期間: 6ヶ月</strong>
                </div>
            </div>
        </div>

        <div class="case-studies__note">
            <i class="fas fa-info-circle"></i>
            <p>※ 上記は一例です。業種や規模、課題によって最適なプランは異なります。まずは無料診断でご相談ください。</p>
        </div>
    </div>
</section>
```

**CSS追加** (`assets/css/pages/system-development.css`):

```css
/* ========================================
   導入事例セクション
   ======================================== */
.case-studies {
    padding: 80px 0;
    background: linear-gradient(135deg, #F8F8F8 0%, #FFFFFF 100%);
}

.case-studies__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 32px;
    margin: 48px 0;
}

.case-study-card {
    background: #FFFFFF;
    border-radius: 8px;
    padding: 32px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border-top: 4px solid var(--system-color-accent);
}

.case-study-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px);
}

.case-study-card__label {
    display: inline-block;
    padding: 6px 16px;
    background: var(--system-color-accent);
    color: #FFFFFF;
    font-size: 12px;
    font-weight: 700;
    border-radius: 4px;
    margin-bottom: 16px;
}

.case-study-card__label--sales {
    background: #1890ff;
}

.case-study-card__label--custom {
    background: #52c41a;
}

.case-study-card__company {
    display: flex;
    gap: 16px;
    align-items: flex-start;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 2px solid #F0F0F0;
}

.case-study-card__company i {
    font-size: 32px;
    color: var(--system-color-accent);
}

.case-study-card__company h3 {
    font-size: 18px;
    font-weight: 700;
    color: var(--system-color-primary);
    margin: 0 0 4px 0;
}

.case-study-card__industry {
    font-size: 13px;
    color: var(--system-color-text);
    margin: 0;
}

.case-study-card__section {
    margin-bottom: 20px;
}

.case-study-card__section h4 {
    font-size: 14px;
    font-weight: 700;
    color: var(--system-color-primary);
    margin: 0 0 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.case-study-card__section h4 i {
    font-size: 16px;
}

.case-study-card__section p {
    font-size: 14px;
    line-height: 1.8;
    color: var(--system-color-text);
    margin: 0;
}

.case-study-card__section ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.case-study-card__section li {
    font-size: 14px;
    line-height: 1.8;
    color: var(--system-color-text);
    padding-left: 20px;
    position: relative;
    margin-bottom: 8px;
}

.case-study-card__section li::before {
    content: "✓";
    position: absolute;
    left: 0;
    color: var(--system-color-accent);
    font-weight: 700;
}

.case-study-card__result {
    margin-top: 24px;
    padding: 16px;
    background: linear-gradient(135deg, #FFF9E6 0%, #FFFBF0 100%);
    border-radius: 4px;
    text-align: center;
}

.case-study-card__result strong {
    font-size: 16px;
    color: var(--system-color-accent);
    font-weight: 700;
}

.case-studies__note {
    margin-top: 48px;
    padding: 24px;
    background: #E6F7FF;
    border-left: 4px solid #1890ff;
    border-radius: 4px;
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.case-studies__note i {
    color: #1890ff;
    font-size: 20px;
    margin-top: 2px;
    flex-shrink: 0;
}

.case-studies__note p {
    font-size: 14px;
    line-height: 1.8;
    color: var(--system-color-text);
    margin: 0;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .case-studies__grid {
        grid-template-columns: 1fr;
    }
}
```

---

## 実装の優先順位

### 最優先（フェーズ1）
1. ✅ 売上最大化パックの価格変更（60万円〜）
2. ✅ 投資対効果の訴求変更（初年度+40万円）
3. ✅ 事務ゼロ化パックの現場感覚補足

### 高優先（フェーズ2）
4. ✅ 無料SVGイラストの追加

### 中優先（フェーズ3）
5. ✅ パッケージ比較表の追加
6. ✅ 導入事例セクションの追加

---

## 注意事項

### グローバル変更時の注意
- 「80万円」を「60万円」に置換する際、**売上最大化パック以外**を誤って変更しないこと
- 検索して1つずつ確認しながら変更する

### レスポンシブ対応
- すべての新規セクションはSP対応を考慮する
- 比較表は横スクロール可能にする
- 導入事例カードは1カラムに切り替える

### パフォーマンス
- SVG画像は遅延読み込み（loading="lazy"）を使用
- 画像サイズは最適化する

### ブランド統一
- カラー: アクセントカラー（#B8860B）を統一
- フォント: 既存のフォントファミリーを継承
- 余白: 既存のセクション間隔（80px）に合わせる

---

## 完了チェックリスト

- [ ] 売上最大化パックの価格が全箇所で60万円になっている
- [ ] 投資対効果が「初年度+40万円利益」になっている
- [ ] 事務ゼロ化パックに現場感覚の補足が表示されている
- [ ] 各パッケージにSVGイラストが表示されている
- [ ] パッケージ比較表が正しく表示されている
- [ ] 導入事例が4件表示されている
- [ ] SP表示でレイアウトが崩れていない
- [ ] すべてのリンクが正しく動作している
- [ ] コミットメッセージが適切に記載されている
- [ ] 本番環境にプッシュされている

---

## 後続タスク（次のフェーズ）

### フェーズ4: より高度なビジュアル化
- [ ] 投資対効果のグラフ化
- [ ] ビフォー・アフターの図解作成
- [ ] 実際のシステム画面のモックアップ作成

### フェーズ5: SEO・マーケティング強化
- [ ] メタディスクリプションの最適化
- [ ] 構造化データ（JSON-LD）の追加
- [ ] OGP画像の設定

---

**作業開始日**: 2025-12-19
**担当**: Claude Code
**承認**: ユーザー承認済み
