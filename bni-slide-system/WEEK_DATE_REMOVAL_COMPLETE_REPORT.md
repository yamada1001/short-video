# WEEK_DATE機能削除完了レポート

## 概要
ユーザー要求：「対象週の機能は不要。入力したデータを保存し、最新の保存データをスライドに適用するだけで良い。」

## 実施内容

### 1. 完了したファイル修正

#### 1.1 slides_v2/admin/seating.php
- **変更内容:**
  - 日付選択UIを削除
  - JavaScriptから`week_date`パラメータを削除
  - `loadSeating()`を`get_latest` APIエンドポイントに変更
  - `saveSeating()`から`week_date`を削除
  - `viewSlide()`から日付パラメータを削除

#### 1.2 slides_v2/api/seating_crud.php
- **変更内容:**
  - `get_latest`アクション追加（最新データ取得）
  - `save`アクションを修正（week_date不要、全削除→新規挿入）
  - `get_for_slide`を修正（最新データ取得）

#### 1.3 slides_v2/admin/visitors.php
- **変更内容:**
  - 日付選択UIを完全削除
  - `loadVisitors()`を`get_latest` APIに変更
  - フォーム送信から`week_date`を削除
  - `deleteAllVisitors()`を`delete_all` APIに変更
  - 全スライド確認ボタンから日付パラメータ削除

### 2. 必要な追加API修正（未完了）

以下のAPIファイルに`get_latest`と`delete_all`アクションを追加する必要があります：

#### 2.1 slides_v2/api/visitors_crud.php
```php
case 'get_latest':
    // 最新のビジター一覧取得
    $stmt = $db->query("
        SELECT
            v.*,
            m.name as attend_member_name
        FROM visitors v
        LEFT JOIN members m ON v.attend_member_id = m.id
        ORDER BY v.created_at DESC, v.visitor_no ASC
    ");

    $visitors = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $visitors[] = $row;
    }

    echo json_encode(['success' => true, 'visitors' => $visitors]);
    break;

case 'delete_all':
    // 全ビジター削除
    $stmt = $db->exec('DELETE FROM visitors');
    echo json_encode(['success' => true]);
    break;

case 'get_next_visitor_no':
    // 次のビジターNo取得（week_date不要版）
    $stmt = $db->query("
        SELECT COALESCE(MAX(visitor_no), 0) + 1 as next_no
        FROM visitors
    ");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'next_no' => $row['next_no']]);
    break;

// createとupdateからweek_dateを削除
case 'create':
    $visitorNo = $_POST['visitor_no'] ?? null;
    $name = $_POST['name'] ?? null;
    // ... その他のフィールド（week_dateを除外）

    $stmt = $db->prepare('
        INSERT INTO visitors (
            visitor_no, name, company_name, specialty,
            sponsor, attend_member_id, job_description, referral_request
        )
        VALUES (
            :visitor_no, :name, :company_name, :specialty,
            :sponsor, :attend_member_id, :job_description, :referral_request
        )
    ');
    // バインド処理（week_dateを除外）
    break;
```

#### 2.2 slides_v2/api/substitutes_crud.php
```php
case 'get_latest':
    $stmt = $db->query("
        SELECT * FROM substitutes
        ORDER BY created_at DESC, substitute_no ASC
    ");

    $substitutes = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $substitutes[] = $row;
    }

    echo json_encode(['success' => true, 'substitutes' => $substitutes]);
    break;

case 'delete_all':
    $db->exec('DELETE FROM substitutes');
    echo json_encode(['success' => true]);
    break;

case 'get_next_no':
    $stmt = $db->query("
        SELECT COALESCE(MAX(substitute_no), 0) + 1 as next_no
        FROM substitutes
    ");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'next_no' => $row['next_no']]);
    break;
```

#### 2.3 slides_v2/api/new_members_crud.php
```php
case 'get_latest':
    $stmt = $db->query("
        SELECT
            nm.*,
            m.name as member_name,
            m.company_name,
            m.photo_path
        FROM new_members nm
        LEFT JOIN members m ON nm.member_id = m.id
        ORDER BY nm.created_at DESC
    ");

    $new_members = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $new_members[] = $row;
    }

    echo json_encode(['success' => true, 'new_members' => $new_members]);
    break;

case 'delete_all':
    $db->exec('DELETE FROM new_members');
    echo json_encode(['success' => true]);
    break;
```

#### 2.4 slides_v2/api/renewal_crud.php
```php
case 'get_latest':
    $stmt = $db->query("
        SELECT
            r.*,
            m.name as member_name,
            m.company_name,
            m.photo_path
        FROM renewal r
        LEFT JOIN members m ON r.member_id = m.id
        ORDER BY r.created_at DESC
    ");

    $renewal_members = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $renewal_members[] = $row;
    }

    echo json_encode(['success' => true, 'renewal_members' => $renewal_members]);
    break;

case 'delete_all':
    $db->exec('DELETE FROM renewal');
    echo json_encode(['success' => true]);
    break;
```

#### 2.5 slides_v2/api/weekly_no1_crud.php
```php
case 'get':
case 'get_latest':
    // 最新データ取得
    $stmt = $db->query("
        SELECT
            w.*,
            m1.name as external_referral_member_name,
            m2.name as visitor_invitation_member_name,
            m3.name as one_to_one_member_name
        FROM weekly_no1 w
        LEFT JOIN members m1 ON w.external_referral_member_id = m1.id
        LEFT JOIN members m2 ON w.visitor_invitation_member_id = m2.id
        LEFT JOIN members m3 ON w.one_to_one_member_id = m3.id
        ORDER BY w.created_at DESC
        LIMIT 1
    ");

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $data]);
    break;

case 'save':
    // week_dateを削除
    // 既存データを全削除して新規挿入
    $db->exec('DELETE FROM weekly_no1');

    $stmt = $db->prepare('
        INSERT INTO weekly_no1 (
            external_referral_member_id, external_referral_count,
            visitor_invitation_member_id, visitor_invitation_count,
            one_to_one_member_id, one_to_one_count
        )
        VALUES (
            :external_referral_member_id, :external_referral_count,
            :visitor_invitation_member_id, :visitor_invitation_count,
            :one_to_one_member_id, :one_to_one_count
        )
    ');
    // バインド処理
    break;

case 'delete':
    $db->exec('DELETE FROM weekly_no1');
    echo json_encode(['success' => true]);
    break;
```

#### 2.6 slides_v2/api/share_story_crud.php
```php
case 'get_latest':
case 'get_by_date':
    $stmt = $db->query("
        SELECT
            ss.*,
            m.name as member_name,
            m.company_name,
            m.photo_path
        FROM share_story ss
        LEFT JOIN members m ON ss.member_id = m.id
        ORDER BY ss.created_at DESC
        LIMIT 1
    ");

    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $data]);
    break;

case 'save':
    // 全削除して新規挿入
    $db->exec('DELETE FROM share_story');

    $stmt = $db->prepare('
        INSERT INTO share_story (member_id)
        VALUES (:member_id)
    ');
    $stmt->bindValue(':member_id', $_POST['member_id'], PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);
    break;

case 'delete':
    $db->exec('DELETE FROM share_story');
    echo json_encode(['success' => true]);
    break;
```

#### 2.7 slides_v2/api/main_presenter_crud.php
```php
case 'get':
case 'read':
case 'get_latest':
    // 最新データ取得
    $stmt = $db->query("
        SELECT
            mp.*,
            m.name as member_name,
            m.company_name,
            m.category,
            m.photo_path
        FROM main_presenter mp
        LEFT JOIN members m ON mp.member_id = m.id
        ORDER BY mp.created_at DESC
        LIMIT 1
    ");

    $presentation = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($presentation) {
        echo json_encode(['success' => true, 'data' => $presentation]);
    } else {
        echo json_encode(['success' => false, 'data' => null]);
    }
    break;

case 'create':
case 'update':
    // week_dateを削除
    // 既存データチェックして、あればupdate、なければinsert
    $checkStmt = $db->query('SELECT id FROM main_presenter LIMIT 1');
    $existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // UPDATE処理
    } else {
        // INSERT処理
    }
    break;
```

### 3. 残りの管理画面修正（未完了）

以下のファイルも同様に修正が必要：

1. **slides_v2/admin/substitutes.php** - visitors.phpと同じパターン
2. **slides_v2/admin/new_members.php** - visitors.phpと同じパターン
3. **slides_v2/admin/renewal.php** - visitors.phpと同じパターン
4. **slides_v2/admin/weekly_no1.php** - 日付選択削除、最新データ表示
5. **slides_v2/admin/share_story.php** - 日付選択削除、最新データ表示

### 4. テストデータ挿入SQL

```sql
-- ビジターテストデータ
INSERT INTO visitors (visitor_no, name, company_name, specialty, sponsor, attend_member_id, job_description, referral_request) VALUES
(1, '山田 太郎', '株式会社山田商事', 'Webマーケティング', '佐藤 一郎', 1, 'SEO対策、Web広告運用、SNSマーケティング', '飲食店オーナー、小売店経営者'),
(2, '鈴木 花子', 'スズキデザイン事務所', 'グラフィックデザイン', '田中 次郎', 2, 'ロゴデザイン、パンフレット制作、ブランディング', 'スタートアップ企業、リブランディングを検討中の企業'),
(3, '佐々木 健一', '佐々木不動産', '不動産仲介', '高橋 三郎', 3, '賃貸・売買仲介、投資用物件紹介', '転勤予定の方、投資を始めたい方');

-- 代理出席テストデータ
INSERT INTO substitutes (substitute_no, company_name, name) VALUES
(1, '株式会社ABC商事', '伊藤 孝'),
(2, 'XYZ設計事務所', '渡辺 美咲'),
(3, 'マルイ工業株式会社', '中村 大輔');

-- 新入会メンバーテストデータ（既存メンバーIDを使用）
INSERT INTO new_members (member_id) VALUES
(5),  -- 既存メンバーID 5
(12), -- 既存メンバーID 12
(23); -- 既存メンバーID 23

-- 更新メンバーテストデータ（既存メンバーIDを使用）
INSERT INTO renewal (member_id) VALUES
(7),  -- 既存メンバーID 7
(15), -- 既存メンバーID 15
(28); -- 既存メンバーID 28

-- 週間No.1テストデータ
INSERT INTO weekly_no1 (external_referral_member_id, external_referral_count, visitor_invitation_member_id, visitor_invitation_count, one_to_one_member_id, one_to_one_count) VALUES
(10, 5, 8, 3, 15, 7);

-- シェアストーリーテストデータ
INSERT INTO share_story (member_id) VALUES
(18);

-- メインプレゼンターテストデータ
INSERT INTO main_presenter (member_id, presentation_type, youtube_url) VALUES
(25, 'simple', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');
```

## 修正パターン概要

### 管理画面（admin/*.php）の標準的な修正パターン

1. **UIから日付選択を削除:**
   ```html
   <!-- 削除 -->
   <div class="date-selector">
       <label><i class="fas fa-calendar"></i> 開催日:</label>
       <input type="date" id="weekDate">
   </div>
   ```

2. **JavaScriptのデフォルト日付設定を削除:**
   ```javascript
   // 削除
   function setDefaultDate() { ... }
   document.getElementById('weekDate').addEventListener('change', loadData);
   ```

3. **データ読み込みを最新データに変更:**
   ```javascript
   // 変更前
   async function loadData() {
       const weekDate = document.getElementById('weekDate').value;
       const response = await fetch(`${API_BASE}?action=get_by_date&week_date=${weekDate}`);
   }

   // 変更後
   async function loadData() {
       const response = await fetch(`${API_BASE}?action=get_latest`);
   }
   ```

4. **保存処理からweek_dateを削除:**
   ```javascript
   // 変更前
   formData.append('week_date', weekDate);

   // 変更後
   // week_dateの行を削除
   ```

5. **スライド確認ボタンから日付パラメータを削除:**
   ```javascript
   // 変更前
   function viewSlide(page) {
       const weekDate = document.getElementById('weekDate').value;
       const url = `../index.php?date=${weekDate}#${page}`;
   }

   // 変更後
   function viewSlide(page) {
       const url = `../index.php#${page}`;
   }
   ```

### API（api/*_crud.php）の標準的な修正パターン

1. **get_latestアクションを追加:**
   ```php
   case 'get_latest':
       $query = "SELECT * FROM table_name ORDER BY created_at DESC LIMIT 1";
       // または複数レコードの場合
       $query = "SELECT * FROM table_name ORDER BY created_at DESC";
       // 結果を返す
       break;
   ```

2. **saveアクションを修正:**
   ```php
   case 'save':
       // week_dateパラメータを削除
       // 既存データを全削除
       $db->exec('DELETE FROM table_name');
       // 新規データを挿入（week_date列を除外）
       $stmt = $db->prepare('INSERT INTO table_name (...) VALUES (...)');
       break;
   ```

3. **delete_allアクションを追加（データが複数ある場合）:**
   ```php
   case 'delete_all':
       $db->exec('DELETE FROM table_name');
       echo json_encode(['success' => true]);
       break;
   ```

## テスト手順

1. 各管理画面にアクセスして、日付選択UIが表示されないことを確認
2. データを登録して保存
3. 別のデータを登録して保存
4. 最新のデータが表示されることを確認
5. スライド確認ボタンをクリックして、最新データが表示されることを確認
6. 全削除ボタンで全データが削除されることを確認

## 注意事項

1. **データベーススキーマは変更しない** - week_date列は残します（後方互換性のため）
2. **既存のAPIアクションは残す** - 古いシステムとの互換性のため
3. **created_at列を活用** - 最新データの判定にはcreated_at列を使用
4. **generateSlideImage()関数** - week_dateパラメータを削除または省略可能に変更

## 完了状況

- ✅ slides_v2/admin/seating.php
- ✅ slides_v2/api/seating_crud.php
- ✅ slides_v2/admin/visitors.php
- ⏳ slides_v2/api/visitors_crud.php（コード記載済み、実装待ち）
- ⏳ slides_v2/admin/substitutes.php
- ⏳ slides_v2/api/substitutes_crud.php
- ⏳ slides_v2/admin/new_members.php
- ⏳ slides_v2/api/new_members_crud.php
- ⏳ slides_v2/admin/renewal.php
- ⏳ slides_v2/api/renewal_crud.php
- ⏳ slides_v2/admin/weekly_no1.php
- ⏳ slides_v2/api/weekly_no1_crud.php
- ⏳ slides_v2/admin/share_story.php
- ⏳ slides_v2/api/share_story_crud.php
- ⏳ slides_v2/admin/main_presenter.php（一部完了）
- ⏳ slides_v2/api/main_presenter_crud.php

## 次のステップ

1. 上記の未完了APIファイルを修正
2. 残りの管理画面ファイルを修正
3. テストデータを挿入してテスト実施
4. スライド表示側のPHPファイルも確認（date パラメータが不要になる場合は修正）

---

作成日時: 2025-12-14
作成者: Claude Code
