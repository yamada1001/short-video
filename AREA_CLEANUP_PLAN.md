# エリア関連セクション削除計画

**作成日**: 2025-12-15
**目的**: 全国対応への転換に伴い、不要なエリアセクション・リンクを削除

---

## 🎯 削除対象箇所

### 1. **index.php（トップページ）**
- **426-427行目**: エリアセクション全体
```php
<!-- エリアセクション -->
<?php include __DIR__ . '/includes/area-section.php'; ?>
```
**削除理由**: 全国対応なので、大分県18市町村へのリンクは不要

---

### 2. **includes/footer.php（フッター）**

#### 削除対象1: 翻訳テキスト（17行目、44行目）
```php
// 17行目（英語）
'area' => 'Service Areas',

// 44行目（日本語）
'area' => '対応エリア',
```

#### 削除対象2: エリアリンク（83-84行目）
```php
<a href="/area/" class="footer__link"><i class="fas fa-map-marker-alt"></i> <?php echo $footerTexts['area']; ?>（Web制作）</a>
<a href="/area/video/" class="footer__link"><i class="fas fa-map-marker-alt"></i> <?php echo $footerTexts['area']; ?>（動画）</a>
```

**削除理由**: /area/は410 Goneでブロック済み、リンクがあると混乱を招く

---

### 3. **includes/area-section.php（エリアセクションファイル）**
**ファイル全体削除の検討**
- 現在、index.phpからのみincludeされている
- 大分県18市町村へのリンク生成が主目的
- 全国対応になったため、このファイル自体が不要

**削除 or コメントアウト**:
- ファイルを削除してOK（Git履歴に残る）
- または、ファイル全体をコメントアウト

---

## 🔍 その他の確認が必要な箇所

### 4. **includes/head.php**
- 「大分」の言及があるか確認
- hreflang、canonical URLなど

### 5. **各サービスページ**
- web-production.php: 既に修正済み ✅
- video-production.php: 既に修正済み ✅

### 6. **ブログ記事（blog/）**
- 大分エリア関連の記事がある場合、どうするか
- 実績・事例は残す方針

---

## ✅ 作業チェックリスト

### Phase 1: index.php修正
- [ ] 426-427行目のエリアセクションinclude削除
- [ ] コミット

### Phase 2: includes/footer.php修正
- [ ] 17行目 'area' => 'Service Areas' 削除
- [ ] 44行目 'area' => '対応エリア' 削除
- [ ] 83-84行目のエリアリンク2つ削除
- [ ] コミット

### Phase 3: includes/area-section.php対応
**選択肢A**: ファイル削除
- [ ] git rm includes/area-section.php
- [ ] コミット

**選択肢B**: コメントアウト（保険）
- [ ] ファイル全体をコメントアウト
- [ ] コミット

### Phase 4: 最終確認
- [ ] grep で「エリア」「area」「対応エリア」を検索
- [ ] 残っているものが適切か確認
- [ ] ブラウザで確認
- [ ] Git push

---

## 🚨 注意事項

1. **.htaccess で /area/ は410 Gone設定済み**
   - フッターリンクがあるとユーザーが混乱する
   - 必ず削除すること

2. **area-section.phpは削除してOK**
   - index.phpからのみincludeされている
   - 他のページでは使われていない

3. **ブログ記事のエリア言及は残してOK**
   - 過去の実績・事例として
   - SEO的にも問題なし

4. **会社所在地の「大分県」は残す**
   - about.php の会社概要
   - 構造化データのaddress
   - これらは事実なので削除不要

---

## 📝 削除理由まとめ

| 箇所 | 削除理由 |
|------|---------|
| index.php エリアセクション | 全国対応なので、大分18市町村リンクは不要 |
| footer.php エリアリンク | /area/は410 Goneでブロック済み、リンク切れになる |
| area-section.php | 使われなくなったファイル |

---

**作成日**: 2025-12-15
**推定作業時間**: 30分
