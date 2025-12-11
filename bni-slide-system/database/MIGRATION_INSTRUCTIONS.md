# データベースマイグレーション手順

## referrals_weeklyテーブル作成

### 本番環境での実行手順

1. SSHでサーバーに接続
```bash
ssh yojitu@yojitu.com
```

2. プロジェクトディレクトリに移動
```bash
cd /home/yojitu/yojitu.com/public_html/bni-slide-system
```

3. マイグレーションスクリプトを実行
```bash
php database/migrate_referrals_weekly.php
```

4. 実行結果を確認
```
Starting migration: referrals_weekly table...
✓ referrals_weekly table created successfully.
✓ Index created successfully.

Migration completed successfully!
```

### テーブル構造確認

SQLiteで直接確認する場合：

```bash
sqlite3 data/bni_system.db
```

```sql
-- テーブルが作成されたか確認
.tables

-- テーブル構造を確認
.schema referrals_weekly

-- データを確認（空のはず）
SELECT * FROM referrals_weekly;

-- 終了
.exit
```

### 想定されるテーブル構造

```sql
CREATE TABLE referrals_weekly (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    week_date TEXT UNIQUE NOT NULL,  -- 金曜日の日付 (YYYY-MM-DD)
    total_amount INTEGER DEFAULT 0,  -- リファーラル総額
    notes TEXT,  -- メモ
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_referrals_weekly_week_date ON referrals_weekly(week_date);
```

### トラブルシューティング

#### 問題1: データベースファイルが見つからない
```bash
ls -la data/bni_system.db
```
ファイルが存在しない場合は、初期化スクリプトを実行してください。

#### 問題2: 権限エラー
```bash
chmod 664 data/bni_system.db
chmod 775 data/
```

#### 問題3: テーブルがすでに存在する
スクリプトは `CREATE TABLE IF NOT EXISTS` を使用しているため、すでに存在する場合はスキップされます。
エラーメッセージを確認してください。

---

**実行完了後、admin/referrals.phpが正常に動作することを確認してください。**
