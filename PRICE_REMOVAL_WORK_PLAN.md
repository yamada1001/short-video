# 金額表示削除・エリアページブロック作業計画書

**作成日**: 2025-12-15
**ステータス**: 未着手
**推定作業時間**: 6-9時間

---

## 📋 目次

1. [方針確認](#方針確認)
2. [作業フェーズ](#作業フェーズ)
3. [詳細ファイルリスト](#詳細ファイルリスト)
4. [作業サマリー](#作業サマリー)
5. [注意事項](#注意事項)
6. [作業進捗チェックリスト](#作業進捗チェックリスト)

---

## 方針確認

### ✅ 決定事項（ユーザー回答より）

1. **金額表示**: 全ての金額を完全削除（自社料金・参考情報含む全て）
2. **エリアページ**: 完全にブロック（.htaccessで410 Gone）
3. **ブログ記事**: 自社料金のみ削除（相場情報は残す）
4. **特商法ページ**: 「各サービスページ参照」に変更

### 背景
- 安売り路線から脱却
- 高級路線への転換
- 価格競争を避ける

---

## 作業フェーズ

### Phase 1: エリアページの完全ブロック ⏱️ 5分

#### ファイル修正
`.htaccess`に以下を追加：

```apache
# ==============================================
# エリアマーケティングページを完全ブロック（410 Gone）
# 作成日: 2025-12-15
# ==============================================
RedirectMatch 410 ^/area/
```

#### 影響範囲
- `/area/` 配下の全ページ（約36ページ）
- Web制作エリアページ（18市町村）
- 動画制作エリアページ（18市町村）

#### 結果
- 検索エンジンに「ページは恒久的に削除された」と通知
- アクセスすると410エラーが表示される

#### 動作確認コマンド
```bash
curl -I https://yojitu.com/area/
# HTTP/1.1 410 Gone が返ってくればOK
```

---

### Phase 2: メインページの金額完全削除 ⏱️ 3-4時間

#### 対象ファイル（11ファイル）

##### 1. `index.php`
**削除内容**:
- [ ] タイトル（line 43）: `【10万円〜】` → 削除
- [ ] description（line 44）: `10万円からの格安料金、` → 削除
- [ ] keywords（line 45）: `10万円,` → 削除
- [ ] 構造化データ（line 101）: `"priceRange": "¥100,000〜",` → 削除
- [ ] サービスカード（line 321）: `<p class="service-card__price">300,000円〜</p>` → 削除
- [ ] サービスカード（line 338）: `<p class="service-card__price">1本 20,000円〜</p>` → 削除
- [ ] FAQ構造化データ（line 133-134）: 料金に関する質問の回答を「お見積りによる」に変更

**置換例**:
```php
// Before
$page_title = '【10万円〜】大分のホームページ制作・Web制作｜余日（Yojitsu）';

// After
$page_title = '大分のホームページ制作・Web制作｜余日（Yojitsu）';
```

##### 2. `web-production.php`
**削除内容**:
- [ ] タイトル（line 6）: `10万円〜30万円` → 削除
- [ ] 構造化データ（line 48-70）: offers全体を削除または価格情報のみ削除
- [ ] FAQ（line 82-84）: 料金回答を「お見積りによる」に変更
- [ ] 料金プランセクション（HTML内）: 全削除または「お問い合わせください」に変更

**置換例**:
```php
// Before
"offers": [
    {
        "@type": "Offer",
        "name": "10万円プラン",
        "price": "100000",
        "priceCurrency": "JPY",
        "description": "個人事業主の方や安く早く作りたい方向け"
    },
    ...
]

// After
"offers": [
    {
        "@type": "Offer",
        "name": "スタンダードプラン",
        "description": "個人事業主の方や安く早く作りたい方向け。お見積りはお問い合わせください。"
    },
    ...
]
```

##### 3. `video-production.php`
**削除内容**:
- [ ] タイトル（line 6）: `【1本2万円〜】` → 削除
- [ ] description（line 7）: `1本2万円から、` `10本セット15万円` → 削除
- [ ] 構造化データ（line 48-63）: offers全体の価格情報削除
- [ ] FAQ（line 74-77）: 料金回答を「お見積りによる」に変更

##### 4. `services.php`
**削除内容**:
- [ ] description（line 7）: `10万円〜` `1本2万円〜` → 削除
- [ ] keywords（line 8）: `10万円,30万円,` → 削除
- [ ] HTMLのサービスカード料金表示を全削除

##### 5. `recruit.php`
**削除内容**:
- [ ] ライター報酬記載: `1本5,000円〜` → `報酬はスキルに応じて相談` に変更

##### 6. `tokushoho.html`
**削除内容**:
- [ ] 販売価格の項目を以下に変更:
```html
<tr>
    <th>販売価格</th>
    <td>各サービスページをご確認ください。お見積もりにより個別に提示いたします。</td>
</tr>
```

##### 7. `llms.txt`
**削除内容**:
- [ ] 全ての料金情報を削除
- [ ] 「料金はお見積りとなります」のみ記載

##### 8. `includes/data/services.json`
**削除内容**:
- [ ] `price`フィールドを削除または`"お見積り"`に変更

**置換例**:
```json
// Before
{
    "name": "Web制作",
    "price": "300,000円〜",
    "description": "..."
}

// After
{
    "name": "Web制作",
    "price": "お見積り",
    "description": "..."
}
```

##### 9. `includes/translations/en.json`
**削除内容**:
- [ ] 英語版の料金情報を削除または"Quote required"に変更

##### 10. `includes/area-section.php`
**削除内容**:
- [ ] エリアセクション自体を非表示化（コメントアウト）
- [ ] または`/area/`へのリンクを削除

##### 11. `includes/cta.php`
**削除内容**:
- [ ] CTAの料金訴求を削除
- [ ] 「10万円から」「格安」などの文言を削除

---

### Phase 3: ブログ記事の自社料金のみ削除 ⏱️ 1-2時間

#### 削除対象パターン（正規表現で検索）

```regex
余日.*?[0-9０-９]+万円
当社.*?[0-9０-９]+万円
弊社.*?[0-9０-９]+万円
私たち.*?[0-9０-９]+万円
```

#### 保持対象パターン
- 「一般的に」
- 「業界相場」
- 「他社では」
- 「フリーランスの相場」
- 「制作会社の平均」

#### 対象ブログ記事（62件）

以下の記事をチェックし、自社料金の記載がある箇所のみを修正：

1. blog/data/article-76-full.html
2. blog/data/article-78-full.html
3. blog/data/article-81-full.html
4. blog/data/article-84-full.html
5. blog/data/article-85-full.html
6. blog/data/article-87-full.html
7. blog/data/article-88-full.html
8. blog/data/article-89-full.html
9. blog/data/article-91-full.html
10. blog/data/article-92-full.html
11. blog/data/article-96-full.html
12. blog/data/article-100-full.html
13. blog/data/article-102-full.html
14. blog/data/article-103-full.html
15. blog/data/article-110-full.html

... および他の金額記載がある記事

#### 作業手順
1. Grepで自社料金パターンを検索
2. 該当箇所を確認
3. 自社料金のみを削除（相場情報は保持）
4. ファイルを保存

---

### Phase 4: 構造化データ・メタタグの全面修正 ⏱️ 1-2時間

#### 対象箇所

##### 構造化データ（JSON-LD）
全ページの`<script type="application/ld+json">`内で以下を修正：

- [ ] `"priceRange"`フィールドを削除
- [ ] `"price"`フィールドを削除
- [ ] `"offers"`配列の価格情報を削除

##### OGPメタタグ
- [ ] `<meta property="og:description">`から金額表示を削除

##### FAQPage構造化データ
- [ ] 料金に関する質問の回答を「お見積りによる」に変更

#### 対象ファイル
- index.php
- web-production.php
- video-production.php
- services.php
- area/detail.php（エリアページはブロックするが念のため）

---

### Phase 5: 動作確認とテスト ⏱️ 1時間

#### チェックリスト

##### 1. ページ表示確認
- [ ] トップページ（index.php）が正常に表示される
- [ ] サービスページ（services.php）が正常に表示される
- [ ] Web制作ページ（web-production.php）が正常に表示される
- [ ] 動画制作ページ（video-production.php）が正常に表示される
- [ ] ブログ記事が正常に表示される

##### 2. エリアページブロック確認
- [ ] `/area/`にアクセスして410エラーが表示される
- [ ] `/area/?area=oita-city`にアクセスして410エラーが表示される
- [ ] `/area/video/`にアクセスして410エラーが表示される

##### 3. 金額表示確認
- [ ] トップページに金額表示がない
- [ ] サービスページに金額表示がない
- [ ] ブログ記事に自社料金の記載がない
- [ ] ブログ記事に相場情報は残っている

##### 4. 構造化データバリデーション
- [ ] Google Rich Results Testで各ページをチェック
- [ ] エラーが出ないことを確認
- [ ] 料金情報が削除されていることを確認

##### 5. メタタグ確認
- [ ] `<title>`タグに金額表示がない
- [ ] `<meta name="description">`に金額表示がない
- [ ] OGPタグに金額表示がない

#### テストコマンド

```bash
# エリアページブロック確認
curl -I https://yojitu.com/area/

# トップページの金額表示確認（grepで検索）
curl https://yojitu.com/ | grep -E "10万円|30万円|2万円|5,800円"

# Web制作ページの金額表示確認
curl https://yojitu.com/web-production.php | grep -E "10万円|30万円"

# 動画制作ページの金額表示確認
curl https://yojitu.com/video-production.php | grep -E "2万円|15万円"
```

---

## 詳細ファイルリスト

### Phase 2: メインページ（11ファイル）

| # | ファイルパス | 修正内容 | 優先度 | 完了 |
|---|------------|---------|--------|------|
| 1 | index.php | タイトル、description、構造化データ、サービスカード | 最高 | [ ] |
| 2 | web-production.php | タイトル、構造化データ、FAQ、料金プランセクション | 最高 | [ ] |
| 3 | video-production.php | タイトル、構造化データ、FAQ | 最高 | [ ] |
| 4 | services.php | description、keywords、サービスカード | 高 | [ ] |
| 5 | recruit.php | ライター報酬記載 | 中 | [ ] |
| 6 | tokushoho.html | 販売価格項目 | 高 | [ ] |
| 7 | llms.txt | 全料金情報 | 中 | [ ] |
| 8 | includes/data/services.json | priceフィールド | 高 | [ ] |
| 9 | includes/translations/en.json | 英語版料金情報 | 低 | [ ] |
| 10 | includes/area-section.php | エリアセクションの非表示化 | 中 | [ ] |
| 11 | includes/cta.php | CTA料金訴求 | 中 | [ ] |

### Phase 3: ブログ記事（約62ファイル）

| # | ファイルパス | 金額記載内容 | 完了 |
|---|------------|------------|------|
| 1 | blog/data/article-76-full.html | AIツール料金 | [ ] |
| 2 | blog/data/article-78-full.html | LLMO対策費用 | [ ] |
| 3 | blog/data/article-81-full.html | HP制作費の相場 | [ ] |
| 4 | blog/data/article-84-full.html | Web広告費 | [ ] |
| 5 | blog/data/article-85-full.html | HP運用費 | [ ] |
| 6 | blog/data/article-87-full.html | 金額記載あり | [ ] |
| 7 | blog/data/article-88-full.html | 金額記載あり | [ ] |
| 8 | blog/data/article-89-full.html | 金額記載あり | [ ] |
| 9 | blog/data/article-91-full.html | 金額記載あり | [ ] |
| 10 | blog/data/article-92-full.html | 金額記載あり | [ ] |
| 11 | blog/data/article-96-full.html | ビジネス事例の売上金額 | [ ] |
| 12 | blog/data/article-100-full.html | 金額記載あり | [ ] |
| 13 | blog/data/article-102-full.html | 金額記載あり | [ ] |
| 14 | blog/data/article-103-full.html | 動画編集相場 | [ ] |
| 15 | blog/data/article-110-full.html | HP制作相場 | [ ] |

※ 他47ファイルは詳細調査ファイル（PRICE_REMOVAL_DETAILED_ANALYSIS.md）を参照

---

## 作業サマリー

| フェーズ | 対象ファイル数 | 推定時間 | 難易度 | ステータス |
|---------|--------------|---------|--------|-----------|
| Phase 1: エリアページブロック | 1ファイル (.htaccess) | 5分 | 低 | [ ] 未着手 |
| Phase 2: メインページ修正 | 11ファイル | 3-4時間 | 中 | [ ] 未着手 |
| Phase 3: ブログ記事修正 | 約62ファイル | 1-2時間 | 中 | [ ] 未着手 |
| Phase 4: 構造化データ修正 | 約10ファイル | 1-2時間 | 高 | [ ] 未着手 |
| Phase 5: 動作確認 | - | 1時間 | 低 | [ ] 未着手 |
| **合計** | **約84ファイル** | **6-9時間** | - | **0%** |

---

## 注意事項

### ⚠️ 1. SEOへの影響

#### エリアページの削除
- エリアページ（約36ページ）が完全に削除されます
- 「大分市 ホームページ制作」などの地域キーワードでのSEO流入がなくなります
- Google Search Consoleで410エラーが大量に表示される可能性があります

#### 金額訴求の削除
- トップページから金額訴求がなくなるため、CVRが変動する可能性があります
- 価格比較サイトからの流入が減る可能性があります
- 「格安 ホームページ制作」などのキーワードでの流入が減る可能性があります

### 💾 2. バックアップ

作業前に必ずバックアップを取ります：

```bash
# 全ファイルのバックアップ
tar -czf backup_before_price_removal_$(date +%Y%m%d_%H%M%S).tar.gz \
  index.php \
  web-production.php \
  video-production.php \
  services.php \
  recruit.php \
  tokushoho.html \
  llms.txt \
  includes/ \
  blog/data/ \
  .htaccess

# Gitでコミット前の状態を保存
git stash push -m "作業前バックアップ"
```

### 📝 3. 段階的な確認

各フェーズごとにGitコミットを行い、問題があればロールバックできるようにします：

```bash
# Phase 1完了後
git add .htaccess
git commit -m "Phase1: エリアページを410でブロック"
git push origin main

# Phase 2完了後
git add index.php web-production.php video-production.php ...
git commit -m "Phase2: メインページの金額表示を削除"
git push origin main

# 以降同様
```

### 🔍 4. 確認用コマンド集

```bash
# 金額表示が残っていないか全体チェック
grep -r "10万円\|30万円\|2万円\|5,800円\|300,000円\|100,000円" \
  --include="*.php" --include="*.html" \
  --exclude-dir=demo --exclude-dir=bni-slide-system \
  .

# エリアページへのリンクが残っていないかチェック
grep -r "href=\"/area/" --include="*.php" --include="*.html" .

# 構造化データに料金情報が残っていないかチェック
grep -r "\"price\"" --include="*.php" .
grep -r "\"priceRange\"" --include="*.php" .
```

---

## 作業進捗チェックリスト

### 全体進捗
- [ ] Phase 1: エリアページブロック（5分）
- [ ] Phase 2: メインページ修正（3-4時間）
- [ ] Phase 3: ブログ記事修正（1-2時間）
- [ ] Phase 4: 構造化データ修正（1-2時間）
- [ ] Phase 5: 動作確認（1時間）

### Phase 1詳細
- [ ] .htaccessにRedirectMatch追加
- [ ] Gitコミット＆プッシュ
- [ ] 動作確認（410エラー表示）

### Phase 2詳細
- [ ] index.php 修正
- [ ] web-production.php 修正
- [ ] video-production.php 修正
- [ ] services.php 修正
- [ ] recruit.php 修正
- [ ] tokushoho.html 修正
- [ ] llms.txt 修正
- [ ] includes/data/services.json 修正
- [ ] includes/translations/en.json 修正
- [ ] includes/area-section.php 修正
- [ ] includes/cta.php 修正
- [ ] Gitコミット＆プッシュ

### Phase 3詳細
- [ ] 自社料金検索パターン作成
- [ ] 62件のブログ記事をチェック
- [ ] 自社料金のみ削除（相場情報保持）
- [ ] Gitコミット＆プッシュ

### Phase 4詳細
- [ ] 構造化データの料金情報削除
- [ ] OGPメタタグの金額削除
- [ ] FAQの料金回答変更
- [ ] Gitコミット＆プッシュ

### Phase 5詳細
- [ ] ページ表示確認（5ページ）
- [ ] エリアページブロック確認
- [ ] 金額表示確認
- [ ] 構造化データバリデーション
- [ ] メタタグ確認
- [ ] 最終Gitコミット＆プッシュ

---

## 作業再開時のクイックスタート

### 1. このファイルを読み込む
```bash
cat PRICE_REMOVAL_WORK_PLAN.md
```

### 2. 詳細調査ファイルを確認
```bash
cat PRICE_REMOVAL_DETAILED_ANALYSIS.md
```

### 3. 現在の進捗を確認
このファイルの「作業進捗チェックリスト」セクションを確認

### 4. 未完了のPhaseから作業再開
各Phaseの詳細手順に従って作業を進める

### 5. 作業完了後
```bash
git add .
git commit -m "金額表示削除作業完了"
git push origin main
```

---

## 連絡先・参考情報

- **作業者**: Claude Code
- **ユーザー**: 余日（Yojitsu）
- **プロジェクトURL**: https://yojitu.com
- **Gitリポジトリ**: /Users/yamadaren/Movies/claude-code-projects/yojitu.com

---

**最終更新日**: 2025-12-15
**進捗状況**: 0% (未着手)
