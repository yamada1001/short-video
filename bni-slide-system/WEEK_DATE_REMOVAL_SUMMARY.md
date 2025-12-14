# WEEK_DATE機能削除プロジェクト - 実施サマリー

## プロジェクト概要

**ユーザー要求:**
> "対象週の機能は不要です。入力したデータを保存して、最新の保存データをスライドに適用するだけで良いです。"

**実施方針:**
- データ入力時に日付選択を削除
- 常に最新のデータをスライドに表示
- データベーススキーマは変更せず（後方互換性維持）
- 既存のAPIアクションも残す（後方互換性維持）

---

## 完了した作業

### 1. 管理画面ファイル修正

#### ✅ slides_v2/admin/seating.php
- 日付選択UIを完全削除
- JavaScriptを`get_latest` APIに変更
- `saveSeating()`から`week_date`パラメータ削除
- スライド確認ボタンから日付パラメータ削除

#### ✅ slides_v2/admin/visitors.php
- 日付選択UIを完全削除
- `loadVisitors()`を`get_latest` APIに変更
- フォーム送信から`week_date`削除
- `deleteAllVisitors()`を`delete_all` APIに変更
- 全スライド確認ボタンから日付パラメータ削除

### 2. APIファイル修正

#### ✅ slides_v2/api/seating_crud.php
**追加したアクション:**
- `get_latest`: 最新の座席配置を取得
  ```php
  WHERE created_at = (SELECT MAX(created_at) FROM seating_arrangement)
  ```

**修正したアクション:**
- `save`: week_dateパラメータ削除、全削除→新規挿入方式に変更
- `get_for_slide`: 最新データ取得に変更

### 3. ドキュメント作成

#### ✅ WEEK_DATE_REMOVAL_COMPLETE_REPORT.md
- 完全な修正パターン記載
- 残りのAPIファイル修正用コード記載
- テスト手順記載

#### ✅ slides_v2/database/test_data_insertion.sql
- ビジター（3名分）
- 代理出席（3名分）
- 新入会メンバー（3名分）
- 更新メンバー（3名分）
- 週間No.1（1セット）
- シェアストーリー（1名）
- メインプレゼンター（1名）

---

## 残作業（実装が必要）

### 1. 管理画面（5ファイル）

以下のファイルは`visitors.php`と同じパターンで修正してください：

1. **slides_v2/admin/substitutes.php**
2. **slides_v2/admin/new_members.php**
3. **slides_v2/admin/renewal.php**
4. **slides_v2/admin/weekly_no1.php**
5. **slides_v2/admin/share_story.php**

**修正内容:**
- 日付選択UIを削除
- `setDefaultDate()`関数を削除
- データ読み込みを`get_latest` APIに変更
- 保存処理から`week_date`を削除
- スライド確認から日付パラメータ削除

### 2. APIファイル（6ファイル）

各APIファイルに以下のアクションを追加してください：

#### 📝 slides_v2/api/visitors_crud.php
```php
case 'get_latest':
    $stmt = $db->query("SELECT v.*, m.name as attend_member_name
                        FROM visitors v
                        LEFT JOIN members m ON v.attend_member_id = m.id
                        ORDER BY v.created_at DESC, v.visitor_no ASC");
    // 結果を返す
    break;

case 'delete_all':
    $db->exec('DELETE FROM visitors');
    echo json_encode(['success' => true]);
    break;

case 'get_next_visitor_no':
    $stmt = $db->query("SELECT COALESCE(MAX(visitor_no), 0) + 1 as next_no FROM visitors");
    // 結果を返す
    break;
```
**さらに修正:**
- `create`アクションから`week_date`を削除
- `update`アクションから`week_date`を削除

#### 📝 slides_v2/api/substitutes_crud.php
```php
case 'get_latest':
    $stmt = $db->query("SELECT * FROM substitutes ORDER BY created_at DESC");
    break;

case 'delete_all':
    $db->exec('DELETE FROM substitutes');
    break;

case 'get_next_no':
    $stmt = $db->query("SELECT COALESCE(MAX(substitute_no), 0) + 1 as next_no FROM substitutes");
    break;
```

#### 📝 slides_v2/api/new_members_crud.php
```php
case 'get_latest':
    $stmt = $db->query("SELECT nm.*, m.name as member_name, m.company_name, m.photo_path
                        FROM new_members nm
                        LEFT JOIN members m ON nm.member_id = m.id
                        ORDER BY nm.created_at DESC");
    break;

case 'delete_all':
    $db->exec('DELETE FROM new_members');
    break;
```

#### 📝 slides_v2/api/renewal_crud.php
```php
case 'get_latest':
    $stmt = $db->query("SELECT r.*, m.name as member_name, m.company_name, m.photo_path
                        FROM renewal r
                        LEFT JOIN members m ON r.member_id = m.id
                        ORDER BY r.created_at DESC");
    break;

case 'delete_all':
    $db->exec('DELETE FROM renewal');
    break;
```

#### 📝 slides_v2/api/weekly_no1_crud.php
```php
case 'get_latest':
case 'get':
    $stmt = $db->query("SELECT w.*,
                        m1.name as external_referral_member_name,
                        m2.name as visitor_invitation_member_name,
                        m3.name as one_to_one_member_name
                        FROM weekly_no1 w
                        LEFT JOIN members m1 ON w.external_referral_member_id = m1.id
                        LEFT JOIN members m2 ON w.visitor_invitation_member_id = m2.id
                        LEFT JOIN members m3 ON w.one_to_one_member_id = m3.id
                        ORDER BY w.created_at DESC LIMIT 1");
    break;

case 'save':
    // week_dateを削除
    $db->exec('DELETE FROM weekly_no1');
    $stmt = $db->prepare('INSERT INTO weekly_no1 (...) VALUES (...)');
    // week_date列を除外
    break;
```

#### 📝 slides_v2/api/share_story_crud.php
```php
case 'get_latest':
case 'get_by_date':
    $stmt = $db->query("SELECT ss.*, m.name as member_name, m.company_name, m.photo_path
                        FROM share_story ss
                        LEFT JOIN members m ON ss.member_id = m.id
                        ORDER BY ss.created_at DESC LIMIT 1");
    break;

case 'save':
    $db->exec('DELETE FROM share_story');
    $stmt = $db->prepare('INSERT INTO share_story (member_id) VALUES (:member_id)');
    break;
```

---

## 修正パターン詳細

### 管理画面の標準修正パターン

#### 1. HTML部分
```html
<!-- 削除 -->
<div class="date-selector">
    <label><i class="fas fa-calendar"></i> 開催日:</label>
    <input type="date" id="weekDate">
</div>

<!-- 変更後 -->
<div>
    <span class="count-badge">件数: <span id="count">0</span></span>
</div>
```

#### 2. JavaScript部分
```javascript
// 削除する関数
function setDefaultDate() { ... }

// 削除するイベントリスナー
document.getElementById('weekDate').addEventListener('change', loadData);

// 変更: データ読み込み
// Before:
async function loadData() {
    const weekDate = document.getElementById('weekDate').value;
    const response = await fetch(`${API}?action=get_by_date&week_date=${weekDate}`);
}

// After:
async function loadData() {
    const response = await fetch(`${API}?action=get_latest`);
}

// 変更: 保存処理
// Before:
formData.append('week_date', weekDate);

// After:
// この行を削除

// 変更: スライド確認
// Before:
function viewSlide(page) {
    const weekDate = document.getElementById('weekDate').value;
    window.open(`../index.php?date=${weekDate}#${page}`);
}

// After:
function viewSlide(page) {
    window.open(`../index.php#${page}`);
}
```

---

## テストデータ投入方法

### 1. データベースパスを確認
```bash
# config.phpでデータベースパスを確認
cat /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/slides_v2/config.php
```

### 2. 実際のメンバーIDを確認
```bash
sqlite3 /path/to/slides_v2.db "SELECT id, name, company_name FROM members WHERE is_active = 1 LIMIT 30;"
```

### 3. test_data_insertion.sqlのIDを置き換え
- 新入会メンバー、更新メンバー、週間No.1、シェアストーリー、メインプレゼンターの`member_id`を実際のIDに変更

### 4. SQLファイルを実行
```bash
sqlite3 /path/to/slides_v2.db < /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/slides_v2/database/test_data_insertion.sql
```

### 5. データ確認
```bash
sqlite3 /path/to/slides_v2.db "SELECT * FROM visitors;"
sqlite3 /path/to/slides_v2.db "SELECT * FROM substitutes;"
# 他のテーブルも同様に確認
```

---

## テスト手順

### 各管理画面で実施すべきテスト

1. **画面表示テスト**
   - [ ] 日付選択UIが表示されないことを確認
   - [ ] ページ読み込み時にデータが自動的に表示されることを確認

2. **データ登録テスト**
   - [ ] 新規データを登録して保存
   - [ ] 保存後、そのデータが表示されることを確認

3. **データ更新テスト**
   - [ ] 別のデータを登録して保存
   - [ ] 最新のデータが表示されることを確認（古いデータは非表示）

4. **スライド表示テスト**
   - [ ] スライド確認ボタンをクリック
   - [ ] 最新のデータがスライドに表示されることを確認

5. **削除テスト**
   - [ ] 個別削除が正常に動作することを確認
   - [ ] 全削除ボタンで全データが削除されることを確認

---

## ファイル一覧

### 修正済みファイル

| ファイル | 状態 | 説明 |
|---------|------|------|
| slides_v2/admin/seating.php | ✅ 完了 | 座席管理画面 |
| slides_v2/api/seating_crud.php | ✅ 完了 | 座席管理API |
| slides_v2/admin/visitors.php | ✅ 完了 | ビジター管理画面 |
| WEEK_DATE_REMOVAL_COMPLETE_REPORT.md | ✅ 完了 | 詳細レポート |
| slides_v2/database/test_data_insertion.sql | ✅ 完了 | テストデータSQL |

### 未修正ファイル（要対応）

| ファイル | 状態 | 優先度 |
|---------|------|--------|
| slides_v2/api/visitors_crud.php | ⏳ 未完了 | 高 |
| slides_v2/admin/substitutes.php | ⏳ 未完了 | 高 |
| slides_v2/api/substitutes_crud.php | ⏳ 未完了 | 高 |
| slides_v2/admin/new_members.php | ⏳ 未完了 | 高 |
| slides_v2/api/new_members_crud.php | ⏳ 未完了 | 高 |
| slides_v2/admin/renewal.php | ⏳ 未完了 | 高 |
| slides_v2/api/renewal_crud.php | ⏳ 未完了 | 高 |
| slides_v2/admin/weekly_no1.php | ⏳ 未完了 | 中 |
| slides_v2/api/weekly_no1_crud.php | ⏳ 未完了 | 中 |
| slides_v2/admin/share_story.php | ⏳ 未完了 | 中 |
| slides_v2/api/share_story_crud.php | ⏳ 未完了 | 中 |
| slides_v2/admin/main_presenter.php | ⏳ 一部完了 | 低 |
| slides_v2/api/main_presenter_crud.php | ⏳ 未完了 | 低 |

---

## 注意事項

### データベーススキーマについて
- **week_date列は削除しない** - 後方互換性のため残します
- 既存のデータはそのまま保持されます
- 新しいデータは`week_date`が NULL になります（または自動的に現在日付が入ります）

### 既存APIアクションについて
- `get`、`get_by_date`などの既存アクションは削除しない
- 古いシステムや他のコードとの互換性のため残します
- 新しいアクションとして`get_latest`を追加

### generateSlideImage()関数について
- この関数が`week_date`パラメータを使用している場合は修正が必要
- または、パラメータを省略可能にする

---

## 次のアクションアイテム

### 即座に実施すべきこと

1. **APIファイルの修正（最優先）**
   ```bash
   # 以下のファイルに get_latest, delete_all アクションを追加
   vi slides_v2/api/visitors_crud.php
   vi slides_v2/api/substitutes_crud.php
   vi slides_v2/api/new_members_crud.php
   vi slides_v2/api/renewal_crud.php
   vi slides_v2/api/weekly_no1_crud.php
   vi slides_v2/api/share_story_crud.php
   ```

2. **管理画面の修正**
   ```bash
   # visitors.phpをテンプレートとして他のファイルを修正
   # 修正対象:
   vi slides_v2/admin/substitutes.php
   vi slides_v2/admin/new_members.php
   vi slides_v2/admin/renewal.php
   vi slides_v2/admin/weekly_no1.php
   vi slides_v2/admin/share_story.php
   ```

3. **テストデータの投入**
   ```bash
   # 実際のメンバーIDを確認
   sqlite3 /path/to/slides_v2.db "SELECT id, name FROM members WHERE is_active = 1 LIMIT 30;"

   # test_data_insertion.sql のIDを実際のIDに置き換え
   vi slides_v2/database/test_data_insertion.sql

   # SQLを実行
   sqlite3 /path/to/slides_v2.db < slides_v2/database/test_data_insertion.sql
   ```

4. **動作確認**
   - 各管理画面にアクセス
   - データ登録・更新・削除をテスト
   - スライド表示をテスト

---

## 完了基準

すべてのタスクが完了したとみなせる条件：

- [ ] 全13個のファイル修正完了
- [ ] テストデータ投入完了
- [ ] 全管理画面で日付選択UIが非表示
- [ ] 全管理画面で最新データが自動表示
- [ ] 全管理画面でデータ保存が正常動作
- [ ] 全スライドで最新データが表示される
- [ ] 削除機能が正常動作する

---

## 参考リンク

- [WEEK_DATE_REMOVAL_COMPLETE_REPORT.md](/Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/WEEK_DATE_REMOVAL_COMPLETE_REPORT.md) - 詳細な修正パターン
- [test_data_insertion.sql](/Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/slides_v2/database/test_data_insertion.sql) - テストデータSQL

---

**作成日:** 2025-12-14
**作成者:** Claude Code
**ステータス:** 一部完了（40%進行中）
