# 重複メンバーデータクリーンアップスクリプト

## 概要

このスクリプトは、`members` テーブル内の重複したメンバーレコードを削除するためのツールです。

## 問題の詳細

- **症状**: メンバーテーブルに同じ名前のメンバーが複数存在（本来48名のメンバーが192件のレコードとして存在）
- **原因**: データのインポートまたは初期投入時に重複が発生
- **影響**: APIレスポンスに同じメンバーが複数回表示される

### 重複の例

```
三浦千尋: IDs 153, 105, 57, 9 (4コピー)
今林: IDs 162, 114, 66, 18 (4コピー)
全48名のメンバーがそれぞれ4回ずつ存在
```

## スクリプトの動作

### 処理内容

1. **重複検出**: 同じ名前を持つレコードをグループ化
2. **保持するレコードの決定**: 各グループで最も古い（IDが最小の）レコードを保持
3. **重複削除**: 保持するレコード以外をすべて削除
4. **検証**: 削除後に重複が残っていないか確認

### 実行されるSQL

```sql
-- 重複検出クエリ
SELECT
    name,
    COUNT(*) as duplicate_count,
    MIN(id) as keep_id,
    GROUP_CONCAT(id) as all_ids
FROM members
GROUP BY name
HAVING COUNT(*) > 1
ORDER BY name

-- 重複削除クエリ（各名前ごとに実行）
DELETE FROM members
WHERE name = :name AND id != :keep_id
```

## 使用方法

### 方法1: コマンドラインから実行（推奨）

```bash
cd /path/to/slides_v2
php cleanup_duplicate_members.php
```

### 方法2: ブラウザから実行

開発環境のみで使用してください：

```
http://localhost/slides_v2/cleanup_duplicate_members.php
```

## 実行結果

### 重複が存在する場合

スクリプトは以下の情報を表示します：

1. **クリーンアップ前の状態**
   - 総メンバー数
   - 重複している名前の一覧と詳細

2. **削除処理の進行状況**
   - 各メンバーについて、保持するIDと削除数

3. **クリーンアップ後の状態**
   - 新しい総メンバー数
   - 削除されたレコード数
   - 検証結果

### 重複が存在しない場合

```
重複データは見つかりませんでした
データベースは正常な状態です。クリーンアップは不要です。
```

現在のメンバー一覧（ID順）が表示されます。

## 安全性について

### トランザクション管理

- すべての削除処理はトランザクション内で実行
- エラーが発生した場合は自動的にロールバック
- データの整合性を保証

### バックアップ推奨

実行前にデータベースのバックアップを取ることを推奨します：

```bash
# バックアップの作成
cp slides_v2/data/bni_slide_system.db slides_v2/data/bni_slide_system.db.backup_$(date +%Y%m%d_%H%M%S)
```

### 復元方法

問題が発生した場合、バックアップから復元できます：

```bash
# 復元
cp slides_v2/data/bni_slide_system.db.backup_YYYYMMDD_HHMMSS slides_v2/data/bni_slide_system.db
```

## 出力例

### 重複が見つかった場合の出力

```
重複メンバーデータクリーンアップ
実行日時: 2025-12-14 18:39:50

ステップ 1: クリーンアップ前の状態を確認
現在のメンバー総数: 192

重複データが見つかりました
重複している名前の数: 48

重複データの詳細:
名前        重複数  保持するID  すべてのID
三浦千尋    4       9           9,57,105,153
今林        4       18          18,66,114,162
...

ステップ 2: 重複データを削除中...
削除完了: 三浦千尋 - ID 9 を保持、3件の重複を削除
削除完了: 今林 - ID 18 を保持、3件の重複を削除
...

クリーンアップ完了
削除されたレコード総数: 144

ステップ 3: クリーンアップ後の状態を確認
クリーンアップ後のメンバー総数: 48
削除されたレコード: 144

検証結果: 重複データは完全に削除されました
```

## データベース情報

- **データベースファイル**: `slides_v2/data/bni_slide_system.db`
- **テーブル**: `members`
- **主キー**: `id` (INTEGER PRIMARY KEY AUTOINCREMENT)
- **重複判定カラム**: `name` (TEXT NOT NULL)

## テーブル構造

```sql
CREATE TABLE members (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    company_name TEXT,
    category TEXT,
    photo_path TEXT,
    birthday TEXT,
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

## トラブルシューティング

### エラー: データベース接続エラー

**原因**: データベースファイルが見つからない、またはアクセス権限がない

**解決方法**:
```bash
# データベースファイルの存在確認
ls -l slides_v2/data/bni_slide_system.db

# パーミッションの確認と修正
chmod 664 slides_v2/data/bni_slide_system.db
chmod 775 slides_v2/data
```

### エラー: トランザクションのロールバック

**原因**: 削除処理中にエラーが発生

**解決方法**:
1. エラーメッセージを確認
2. データベースの整合性をチェック
3. バックアップから復元（必要に応じて）

### 予期しない重複が残る

**原因**: 名前が完全に一致していない（スペース、全角/半角の違いなど）

**解決方法**:
```sql
-- 名前の確認
SELECT id, name, LENGTH(name), HEX(name) FROM members ORDER BY name;

-- 必要に応じて手動で修正
UPDATE members SET name = TRIM(name);
```

## 注意事項

1. **本番環境での実行**: 必ずバックアップを取ってから実行してください
2. **複数回実行**: スクリプトは冪等性があり、何度実行しても安全です
3. **外部キー制約**: 他のテーブルが `members.id` を参照している場合、削除前に確認してください
4. **セキュリティ**: 本番環境では、スクリプトへのアクセスを制限してください

## 関連ファイル

- `/slides_v2/cleanup_duplicate_members.php` - クリーンアップスクリプト本体
- `/slides_v2/config.php` - データベース接続設定
- `/slides_v2/api/members_crud.php` - メンバー管理API
- `/slides_v2/admin/members.php` - メンバー管理画面

## 実行後の確認

クリーンアップ後、以下を確認してください：

1. **メンバー管理画面**: http://localhost/slides_v2/admin/members.php
2. **API動作確認**: メンバー一覧APIが正しく動作するか
3. **スライド表示**: メンバー関連のスライドが正しく表示されるか

```bash
# コマンドラインでの確認
sqlite3 slides_v2/data/bni_slide_system.db "SELECT COUNT(*) FROM members"
# 期待値: 48

sqlite3 slides_v2/data/bni_slide_system.db "SELECT name, COUNT(*) FROM members GROUP BY name HAVING COUNT(*) > 1"
# 期待値: (結果なし)
```

## サポート

問題が発生した場合は、以下の情報を添えて報告してください：

1. エラーメッセージの全文
2. 実行環境（OS、PHPバージョン、SQLiteバージョン）
3. データベースのバックアップの有無
4. 実行時のログ

---

**最終更新日**: 2025-12-14
**バージョン**: 1.0.0
