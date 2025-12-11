# インシデント報告 - 2025年12月11日 スライド真っ白エラー

**発生日時**: 2025年12月11日（詳細タイムラインは下記参照）
**影響範囲**: スライドページが真っ白に表示（全機能停止）
**復旧完了時刻**: 2025年12月11日（ロールバック後約3分）
**ダウンタイム**: 約5分
**重大度**: **Critical（最重大）** - 全ユーザーがスライド機能を利用不可

---

## 📋 事象サマリー

### 何が起きたか
- `feature/data-foundation` ブランチ（全19フェーズ、4,450行以上の変更）を `main` にマージ
- GitHub Actions経由で本番環境 https://yojitu.com/bni-slide-system/ に自動デプロイ
- https://yojitu.com/bni-slide-system/admin/slide.php が**完全に真っ白**に表示
- JavaScriptコンソールエラーにより、スライド生成処理が完全停止
- ユーザーはスライドを一切閲覧できない状態

### 影響
- **影響範囲**: 全ユーザー（BNI宗麟チャプターメンバー全員）
- **影響機能**: スライド表示機能（admin/slide.php）が完全停止
- **ダウンタイム**: 約5分間
- **データ損失**: なし（Git管理により完全復旧）
- **ビジネスインパクト**: 定例会でのスライド発表に支障が出る可能性があった

---

## 🔍 根本原因分析（RCA）

### ✅ 確定した直接的な原因（テスト環境で検証済み）

**原因1: LINE Seed JP フォントの404エラー（Critical）**
- **ファイル**: `admin/slide.php` (Lines 48-54)
- **問題**: LINE Seed JPフォントをCDNから読み込もうとしたが、404エラーが発生
- **エラー内容**:
  ```
  Failed to load resource: the server responded with a status of 404 (Not Found)
  https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_11-01@1.0/LINESeedJP_A_TTF_Rg.woff2
  ```
- **影響**: CSSの読み込みが失敗し、スタイルが適用されずレイアウトが崩壊

**原因2: 未定義関数の呼び出し（Critical）**
- **ファイル**: `assets/js/svg-slide-generator.js` (Line 600-604)
- **問題**: `generateMemberPitchSlides()` 関数が実装されていないのに呼び出された
- **エラー内容**:
  ```javascript
  Uncaught ReferenceError: generateMemberPitchSlides is not defined
  ```
- **影響**: JavaScriptの実行が停止し、スライドが一切生成されない

### なぜ起きたか（深層原因）

1. **本番環境での未検証（最大の問題）**
   - ローカル環境でのみ動作確認
   - 本番環境でのテストを一切実施せず
   - 段階的な展開を実施せず、全変更（4,450行以上）を一括マージ
   - **過去の教訓を無視**: INCIDENT_REPORT_2025-12-09.md に同様の問題が記録されていた

2. **過去のインシデントから学ばなかった**
   - INCIDENT_REPORT_2025-12-09.md に「段階的にデプロイ」「本番環境でテスト」という教訓があった
   - しかし、今回も**全く同じミス**を繰り返した
   - インシデントレポートを開発前に確認しなかった

3. **変更規模が大きすぎた（リスク管理の失敗）**
   - 全19フェーズを一度にマージ
   - 4,450行以上の変更（CSS 2,478行追加、JS 872行追加）
   - リスクアセスメント不足
   - どのフェーズが問題かの切り分けが困難に

4. **テスト環境の不在**
   - 本番環境しか存在しない状態
   - テスト環境で事前検証する仕組みがなかった
   - 結果として、ユーザーに直接影響が出る形で問題が発覚

---

## ✅ 実施した復旧手順

### Phase 1: 緊急ロールバック（所要時間: 約5分）

**1-1. 問題の検知と即時判断**（1分以内）
- ユーザーから「真っ白になっている」との報告を受領
- 本番環境が完全停止していることを確認
- 原因特定に時間をかけるとダウンタイムが延びると判断
- **決定**: 即座にロールバックして復旧を優先

**1-2. Gitロールバック実行**（2分）
```bash
# マージ前の安定バージョン（474db3d）にハードリセット
git reset --hard 474db3d

# 本番環境(main)に強制プッシュ
git push origin main --force
```
- コミットID `474db3d`: 2025年12月10日の安定バージョン（GTM追加前）
- 強制プッシュにより、問題のある変更を完全に削除

**1-3. 自動デプロイ待機**（2-3分）
- GitHub Actions が自動的にFTPデプロイを開始
- 本番環境に安定版が反映されるまで待機
- デプロイ完了を確認

**1-4. 復旧確認**（1分以内）
- https://yojitu.com/bni-slide-system/admin/slide.php にアクセス
- スライドが正常に表示されることを確認
- **結果**: 復旧完了 ✅

**復旧完了時刻**: ロールバック開始から約5分後

---

### Phase 2: テスト環境での原因調査（所要時間: 約15分）

**2-1. テスト環境の構築**
- Xserverファイルマネージャーで `bni-slide-system-test/` ディレクトリを作成
- ローカルから問題のあるバージョンをアップロード
- テスト環境URL: `https://yojitu.com/bni-slide-system-test/`

**2-2. ブラウザ開発者ツールでデバッグ**
- **Consoleタブ**: JavaScriptエラーを確認
  - `Uncaught ReferenceError: generateMemberPitchSlides is not defined` を発見
  - assets/js/svg-slide-generator.js:600 でエラー発生

- **Networkタブ**: リソース読み込みエラーを確認
  - LINE Seed JP フォントが404エラー
  - `https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_11-01@1.0/LINESeedJP_A_TTF_Rg.woff2` が見つからない

**2-3. 根本原因の特定**
- **原因1**: LINE Seed JP フォントのCDNリンクが無効
- **原因2**: `generateMemberPitchSlides()` 関数が未実装

---

### Phase 3: 修正とテスト環境での検証（所要時間: 約10分）

**3-1. 修正内容**

**修正1: フォントをGoogle Fontsに変更**
- **ファイル**: `admin/slide.php`
- **変更前**:
  ```html
  <link href="https://cdn.jsdelivr.net/.../LINESeedJP_A_TTF_Rg.woff2" rel="stylesheet">
  ```
- **変更後**:
  ```html
  <!-- Google Fonts: Noto Sans JP -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
  ```

**修正2: 未実装関数の呼び出しをコメントアウト**
- **ファイル**: `assets/js/svg-slide-generator.js`
- **変更前**:
  ```javascript
  slides += generateMemberPitchSlides(slideConfig.members);
  ```
- **変更後**:
  ```javascript
  // Phase 13: Member 60-second Pitch Slides
  // TODO: Implement generateMemberPitchSlides function
  // if (slideConfig && slideConfig.members && slideConfig.members.length > 0) {
  //   slides += generateMemberPitchSlides(slideConfig.members);
  // }
  ```

**3-2. テスト環境で検証**
- 修正ファイルをテスト環境にアップロード
- https://yojitu.com/bni-slide-system-test/admin/slide.php で動作確認
- **結果**: スライドが正常に表示される ✅
- ブラウザコンソールエラーなし ✅

---

### Phase 4: 本番環境への再デプロイ（所要時間: 約5分）

**4-1. 修正をmainブランチにマージ**
```bash
git add admin/slide.php assets/js/svg-slide-generator.js
git commit -m "Fix: LINE Seed JPフォント404エラーとgenerateMemberPitchSlides未定義エラーを修正"
git push origin main
```

**4-2. 自動デプロイ**
- GitHub Actions が自動的にFTPデプロイを実行
- 約2-3分で本番環境に反映

**4-3. 本番環境で最終確認**
- https://yojitu.com/bni-slide-system/admin/slide.php で動作確認
- スライドが正常に表示される ✅
- **結果**: 本番環境復旧完了 ✅

---

## 🛡️ 再発防止策

### ✅ 即座に実施済みの対策

#### 1. テスト環境の構築（完了）
- **実施日**: 2025年12月11日
- **URL**: https://yojitu.com/bni-slide-system-test/
- **方法**: Xserverファイルマネージャーでサブディレクトリを作成
- **運用ルール**:
  - 今後、全ての変更は必ずテスト環境で検証してから本番デプロイ
  - テスト環境でブラウザ開発者ツール（Console、Network、Elements）を使った検証を実施
  - 本番デプロイ後も必ず動作確認を実施

---

### 📋 今後必ず守るべきルール（強制）

#### ルール1: 段階的デプロイの徹底（Phase単位）

**❌ 禁止**: 複数のフェーズを一度にマージ
**✅ 正しい方法**:
1. Phase 1のみ実装 → テスト環境で検証 → 本番デプロイ → 動作確認
2. Phase 2のみ実装 → テスト環境で検証 → 本番デプロイ → 動作確認
3. ... 繰り返し

**メリット**:
- 問題が起きても、どのフェーズが原因か即座に特定可能
- ロールバックの範囲が最小限
- ダウンタイムを最小化
- ユーザーへの影響を最小限に抑える

---

#### ルール2: デプロイ前チェックリストの徹底（必須）

**全てのデプロイ前に以下を必ず確認**:

**ローカル環境**:
- [ ] ローカル環境で動作確認（ブラウザでアクセスして確認）
- [ ] JavaScriptのコンソールエラーがないか確認（F12 → Console）
- [ ] CSSが正しく読み込まれているか確認（F12 → Network）
- [ ] 設定ファイル（`slide_config.json`など）がGitで管理されているか確認

**テスト環境**:
- [ ] テスト環境にデプロイ（https://yojitu.com/bni-slide-system-test/）
- [ ] テスト環境で動作確認（ブラウザでアクセスして確認）
- [ ] ブラウザ開発者ツールで以下をチェック:
  - [ ] Console: エラーが出ていないか
  - [ ] Network: 全てのリソース（CSS、JS、画像、フォント）が200 OKか
  - [ ] Elements: HTMLが正しく生成されているか
- [ ] 主要機能が全て動作するか確認

**本番環境**:
- [ ] mainブランチにマージ
- [ ] GitHub Actionsのデプロイ完了を確認
- [ ] 本番環境で動作確認（https://yojitu.com/bni-slide-system/）
- [ ] 最低でも以下のページをチェック:
  - [ ] admin/slide.php（スライド表示）
  - [ ] admin/edit.php（編集画面）
  - [ ] index.php（ダッシュボード）

---

#### ルール3: 過去のインシデントレポートを必ず確認

**デプロイ前に以下を実施**:
- [ ] `INCIDENT_REPORT_*.md` ファイルを全て確認
- [ ] 過去の失敗パターンを避けているか確認
- [ ] 同じミスを繰り返していないか確認

**現在の教訓**:
- INCIDENT_REPORT_2025-12-09.md: 段階的デプロイの重要性
- INCIDENT_REPORT_2025-12-11.md（本レポート）: テスト環境での事前検証の必要性

---

#### ルール4: CDNリソースの事前検証

**外部CDNを使う場合**:
- [ ] CDNのURLが有効か確認（ブラウザでアクセスして200 OKか確認）
- [ ] CDNがダウンしても影響がないようにフォールバックを用意
- [ ] 可能であれば、ローカルにファイルを配置して使用

**推奨**:
- Google Fonts、Font Awesome など、信頼性の高いCDNを使用
- 個人や小規模プロジェクトが提供するCDNは避ける

---

#### ルール5: 未実装機能の呼び出し禁止

**コーディングルール**:
- [ ] 関数を呼び出す前に、その関数が実装されているか確認
- [ ] 未実装の機能は呼び出さない（コメントアウトまたは削除）
- [ ] TODOコメントで実装予定を明記

**例**:
```javascript
// ✅ 正しい
// TODO: Implement generateMemberPitchSlides function
// if (slideConfig && slideConfig.members && slideConfig.members.length > 0) {
//   slides += generateMemberPitchSlides(slideConfig.members);
// }

// ❌ 間違い
slides += generateMemberPitchSlides(slideConfig.members); // 未実装なのに呼び出し
```

---

## 📊 詳細タイムライン（2025年12月11日）

| 時刻（推定） | フェーズ | イベント | 対応者 | ステータス |
|-------------|---------|---------|--------|-----------|
| 不明 | デプロイ | `feature/data-foundation` を `main` にマージ | Claude | ❌ 問題発生 |
| +1分 | デプロイ | GitHub Actions が自動デプロイ開始 | GitHub Actions | 自動実行 |
| +3分 | デプロイ | 本番環境に反映完了 | Xserver | 自動実行 |
| +4分 | 検知 | ユーザーが「真っ白」と報告 | ユーザー（余日様） | ⚠️ インシデント検知 |
| +4分 | 判断 | 即座にロールバック決定（原因調査は後回し） | Claude | 🚨 緊急対応開始 |
| +5分 | 復旧 | `git reset --hard 474db3d` 実行 | Claude | ロールバック中 |
| +5分 | 復旧 | `git push origin main --force` 実行 | Claude | 強制プッシュ |
| +6分 | 復旧 | GitHub Actions が再デプロイ開始 | GitHub Actions | 自動実行 |
| +9分 | 復旧 | 本番環境に安定版が反映完了 | Xserver | 自動実行 |
| +10分 | 確認 | 本番環境で正常動作を確認 | Claude + ユーザー | ✅ 復旧完了 |
| +15分 | 調査 | テスト環境の構築開始 | Claude + ユーザー | 原因調査フェーズ |
| +30分 | 調査 | ブラウザ開発者ツールでエラー特定 | Claude | ✅ 原因判明 |
| +40分 | 修正 | フォント変更 + 関数呼び出しコメントアウト | Claude | 修正実施 |
| +50分 | テスト | テスト環境で修正版を検証 | Claude | ✅ 検証成功 |
| +55分 | デプロイ | 修正版を本番環境にデプロイ | Claude | 再デプロイ |
| +60分 | 確認 | 本番環境で正常動作を確認 | Claude + ユーザー | ✅ 完全復旧 |

**合計ダウンタイム**: 約5分（緊急ロールバックにより最小化）
**合計対応時間**: 約60分（原因調査 + 修正 + 再デプロイ含む）

---

## 🎯 完了した対応

### ✅ Phase 1: 緊急復旧（完了）
- ロールバックにより本番環境を5分で復旧
- ユーザーへの影響を最小限に抑えることに成功

### ✅ Phase 2: 原因調査（完了）
- テスト環境を構築し、安全に調査
- ブラウザ開発者ツールで2つの根本原因を特定:
  1. LINE Seed JP フォントの404エラー
  2. `generateMemberPitchSlides()` 関数の未定義エラー

### ✅ Phase 3: 修正と検証（完了）
- 2つの問題を修正:
  1. Google Fonts Noto Sans JPに変更
  2. 未実装関数の呼び出しをコメントアウト
- テスト環境で正常動作を確認

### ✅ Phase 4: 本番デプロイ（完了）
- 修正版を本番環境にデプロイ
- 正常動作を確認

---

## 🔄 今後の課題（Next Steps）

### 1. メンバー情報の完全化（優先度：高）
- **現状**: `slide_config.json` に9名のみ登録
- **目標**: PDFから全メンバーを抽出して追加
- **方法**: `slide_images/` の309枚の画像から情報を抽出

### 2. カウントダウンタイマーの実装（優先度：高）
- **現状**: メンバーピッチのカウントダウンタイマーが未実装
- **目標**: 各メンバーのピッチ時間（33秒）を自動カウントダウン
- **要件**: スライド表示時に自動開始

### 3. レイアウトの調整（優先度：中）
- **現状**: 一部のスライドが幅に収まっていない
- **目標**: 全てのスライドをスクリーン幅に最適化

### 4. デザインの統一（優先度：中）
- **現状**: BNIレッドの使用が不統一
- **目標**: 全スライドでBNIレッドをメインカラーとして統一

---

## 📚 今回の教訓（まとめ）

| # | 教訓 | 今回の問題 | 対策 | 実施状況 |
|---|------|-----------|------|---------|
| 1 | **過去のインシデントから学ばなかった** | INCIDENT_REPORT_2025-12-09.md に同様の問題が記録されていたが、無視した | デプロイ前に必ず過去のインシデントレポートを確認 | ✅ ルール化 |
| 2 | **本番でいきなりテストした** | テスト環境がなく、ユーザーに直接影響が出る形で問題が発覚 | テスト環境で必ず事前検証してから本番デプロイ | ✅ 環境構築済み |
| 3 | **大規模変更を一度にマージした** | 全19フェーズ（4,450行以上）を一括マージ | Phase単位で段階的にデプロイ | ✅ ルール化 |
| 4 | **CDNリソースの検証不足** | LINE Seed JP フォントのCDNが404エラー | CDNは信頼性の高いもののみ使用、事前検証必須 | ✅ ルール化 |
| 5 | **未実装関数を呼び出した** | `generateMemberPitchSlides()` が未実装なのに呼び出し | 関数呼び出し前に実装を確認、未実装はコメントアウト | ✅ ルール化 |
| 6 | **ロールバック手順が機能した** ✅ | Git管理により5分で復旧完了 | 引き続きGitでバージョン管理を徹底 | ✅ 継続 |

---

## ⚠️ 重要な注意事項

### このインシデントを忘れないために

**次回デプロイ前に必ず以下を確認してください**:

1. ✅ このレポート（INCIDENT_REPORT_2025-12-11.md）を読み直す
2. ✅ 過去のインシデントレポート（INCIDENT_REPORT_2025-12-09.md）を読み直す
3. ✅ デプロイ前チェックリスト（上記ルール2）を全て実施
4. ✅ テスト環境で動作確認（https://yojitu.com/bni-slide-system-test/）
5. ✅ ブラウザ開発者ツールでエラーがないか確認
6. ✅ 段階的デプロイ（Phase単位）を徹底

**絶対に繰り返してはいけないこと**:
- ❌ 本番環境でいきなりテスト
- ❌ 大規模変更を一度にマージ
- ❌ 過去のインシデントレポートを読まずにデプロイ
- ❌ CDNリソースの事前検証なし
- ❌ 未実装関数の呼び出し

---

## 🤝 関係者

- **担当者**: Claude Code（AI）+ 余日様
- **影響を受けたユーザー**: BNI宗麟チャプターメンバー全員
- **復旧作業**: Claude Code
- **協力**: 余日様（ユーザー報告、テスト環境構築サポート）

---

## 📝 インシデントレポート情報

- **作成日**: 2025年12月11日
- **最終更新日**: 2025年12月11日
- **作成者**: Claude Code
- **レビュー**: 余日様
- **バージョン**: 2.0（詳細版）

---

## 🔗 関連ドキュメント

- [INCIDENT_REPORT_2025-12-09.md](./INCIDENT_REPORT_2025-12-09.md) - 前回のインシデント（類似の問題）
- [SESSION_RESUME.md](./SESSION_RESUME.md) - セッション再開ガイド
- [IMPLEMENTATION_HISTORY.md](./IMPLEMENTATION_HISTORY.md) - 実装履歴

---

**このレポートの目的**:
- ✅ 同様のインシデントを二度と起こさないための教訓として活用
- ✅ 今後の開発の参考資料として保存
- ✅ デプロイ前に必ず確認する必須ドキュメントとして位置付け

---

_**重要**: このインシデントは、過去の教訓を無視した結果起きました。今後は必ず過去のインシデントレポートを確認してからデプロイしてください。_
