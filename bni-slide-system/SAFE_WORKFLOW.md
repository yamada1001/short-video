# 安全な作業手順書

## 🛡️ リスク対策と作業フロー

### ターミナルが落ちるリスクへの対策

#### 1. **作業前の必須チェックリスト**
```bash
# ✅ Gitの状態確認
git status

# ✅ 未コミットの変更がある場合はコミット
git add .
git commit -m "作業開始前のセーブポイント"

# ✅ 新しいブランチを作成（フェーズごと）
git checkout -b feature/phase-1-opening-section
```

#### 2. **各作業ステップの基本フロー**
```
[計画] → [バックアップ] → [実装] → [テスト] → [コミット] → [次へ]
   ↓
もしターミナルが落ちたら：
   設計書（SLIDE_IMPLEMENTATION_PLAN.md）を見て再開地点を確認
```

---

## 📝 推奨作業手順（1フェーズずつ）

### Phase 1例: オープニングセクション実装

#### Step 1: 準備（5分）
```bash
# 作業ブランチ作成
git checkout -b feature/opening-section

# 現在のファイルをバックアップ
cp assets/js/svg-slide-generator.js assets/js/svg-slide-generator.js.backup-$(date +%Y%m%d-%H%M%S)

# 設計書を確認
cat SLIDE_IMPLEMENTATION_PLAN.md | grep -A 10 "フェーズ1"
```

#### Step 2: データ準備（10分）
```bash
# slide_config.jsonを作成
touch data/slide_config.json

# 初期データを投入（手動編集 or APIで）
```

#### Step 3: 実装（30-60分）
```javascript
// assets/js/svg-slide-generator.js に追加

// 出欠確認スライド
function generateAttendanceCheckSlide(config) {
  // 実装...
}

// メインのgenerateSVGSlides関数に追加
slides += generateAttendanceCheckSlide(config);
```

#### Step 4: テスト（10分）
```bash
# スライドページを開いて表示確認
open http://localhost/bni-slide-system/admin/slide.php

# ブラウザのコンソールでエラーチェック
# データがない場合の表示も確認
```

#### Step 5: コミット（5分）
```bash
git add assets/js/svg-slide-generator.js
git add data/slide_config.json
git commit -m "feat: オープニングセクション - 出欠確認スライド実装

- generateAttendanceCheckSlide関数を追加
- slide_config.jsonにteam情報を追加
- テスト完了：データあり/なし両方で正常表示確認

refs: SLIDE_IMPLEMENTATION_PLAN.md フェーズ1"
```

#### Step 6: バックアップの削除（オプション）
```bash
# 正常に動作確認できたら古いバックアップを削除
rm assets/js/svg-slide-generator.js.backup-*
```

---

## 🔄 ターミナルが落ちた場合の復旧手順

### 1. 最後のコミット地点を確認
```bash
git log --oneline -5

# 最後のコミットメッセージから作業状況を確認
```

### 2. 設計書で次のタスクを確認
```bash
cat SLIDE_IMPLEMENTATION_PLAN.md

# または
open SLIDE_IMPLEMENTATION_PLAN.md
```

### 3. 未コミットの変更があれば復元するか判断
```bash
git status
git diff

# 良い変更なら残す
git add .
git commit -m "ターミナル復旧後: 作業途中の変更を保存"

# 不完全な変更なら破棄
git restore .
```

### 4. 作業を再開
```bash
# 設計書の次のフェーズから再開
```

---

## 💾 重要ファイルの定期バックアップ

### 自動バックアップスクリプト（オプション）
```bash
#!/bin/bash
# backup.sh

BACKUP_DIR="backups/$(date +%Y%m%d)"
mkdir -p "$BACKUP_DIR"

# 重要ファイルをバックアップ
cp -r data/ "$BACKUP_DIR/"
cp -r assets/js/ "$BACKUP_DIR/"
cp -r admin/ "$BACKUP_DIR/"

echo "✅ Backup completed: $BACKUP_DIR"
```

### 使い方
```bash
chmod +x backup.sh
./backup.sh
```

---

## 🎯 推奨実装順序（詳細）

### Week 1: データ基盤（リスク：低）
1. [ ] `data/slide_config.json` 作成
2. [ ] `data/members.json` に写真パスを追加
3. [ ] 設定管理画面UI（簡易版）

**ポイント:** データ構造だけなので、失敗してもすぐ修正可能

### Week 2: 最重要機能（リスク：中）
1. [ ] 60秒ピッチスライド生成（段階的）
   - まず1人分だけ表示
   - 次に全員分ループ
   - 最後にタイマー機能

**ポイント:** 小さく分割して、1ステップずつコミット

### Week 3: データ表示（リスク：低）
1. [ ] 月間チャンピオン
2. [ ] ハッピーバースデー
3. [ ] スピーカーローテーション

**ポイント:** 既存のstats計算ロジックを拡張するだけ

### Week 4-5: 静的コンテンツ（リスク：極低）
1. [ ] BNI理念スライド
2. [ ] 各種テンプレート

**ポイント:** ほぼHTMLの追加のみ、影響範囲が小さい

---

## 🚨 緊急時の対処法

### ケース1: スライドが表示されなくなった
```bash
# 最後の正常なコミットに戻す
git log --oneline
git checkout <commit-hash> assets/js/svg-slide-generator.js

# または最新のバックアップを使用
cp assets/js/svg-slide-generator.js.backup-* assets/js/svg-slide-generator.js
```

### ケース2: データが消えた
```bash
# Gitの履歴から復元
git log -- data/slide_config.json
git checkout <commit-hash> data/slide_config.json
```

### ケース3: どこまで実装したか忘れた
```bash
# コミットログを確認
git log --grep="feat:" --oneline

# 差分を確認
git diff main...feature/current-branch
```

---

## ✅ チェックリスト（毎日の作業終了時）

- [ ] 全ての変更をコミット済み
- [ ] スライド表示を確認済み
- [ ] エラーログに異常がない
- [ ] SLIDE_IMPLEMENTATION_PLAN.md に進捗を記録
- [ ] 次回の作業内容を明確にメモ

---

## 📞 トラブル時の連絡先

- 設計書: `SLIDE_IMPLEMENTATION_PLAN.md`
- 技術仕様: `TECHNICAL_SPEC.md`
- このドキュメント: `SAFE_WORKFLOW.md`

**全て保存済みなので、ターミナルが落ちても安心！**
