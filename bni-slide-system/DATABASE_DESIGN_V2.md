# BNI Slide System V2 - データベース設計

**作成日時**: 2025-12-14 00:50

## 📊 データベース概要

- **DBMS**: SQLite3
- **ファイル**: `database/bni_slide_v2.db`
- **文字コード**: UTF-8

---

## 🗂️ テーブル設計

### 1. `members` - メンバー管理（最重要）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| name | TEXT | NOT NULL | - | 名前 |
| company_name | TEXT | NULL | - | 会社名 |
| category | TEXT | NULL | - | カテゴリ（業種） |
| photo_path | TEXT | NULL | - | 写真パス |
| birthday | TEXT | NULL | - | 誕生日（YYYY-MM-DD） |
| is_active | INTEGER | NOT NULL | 1 | 1=在籍中, 0=退会 |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**初期データ**: 48名（本番PDF 2ページ目から抽出）

---

### 2. `seating_arrangement` - 座席配置（p.7）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| table_name | TEXT | NOT NULL | - | テーブル名（A, B, C...） |
| position | INTEGER | NOT NULL | - | 座席番号（1, 2, 3...） |
| member_id | INTEGER | NULL | - | メンバーID（FK: members.id） |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 3. `main_presenter` - メインプレゼン（p.8, p.204）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| member_id | INTEGER | NOT NULL | - | メンバーID（FK: members.id） |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| pdf_path | TEXT | NULL | - | プレゼン資料PDF |
| youtube_url | TEXT | NULL | - | YouTube URL（動画の場合） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 4. `speaker_rotation` - スピーカーローテーション（p.9-14, p.199-203, p.297-301）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| rotation_date | TEXT | NOT NULL | - | 日程（YYYY-MM-DD） |
| main_presenter_id | INTEGER | NOT NULL | - | メインプレゼンID（FK: members.id） |
| referral_target | TEXT | NULL | - | ご紹介してほしい方（自由記述） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `main_presenter_id` → `members.id`

---

### 5. `start_dash_presenter` - スタートダッシュプレゼン（p.15, p.107）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| member_id | INTEGER | NOT NULL | - | メンバーID（FK: members.id） |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 6. `visitors` - ビジター管理（p.19, p.169-180, p.213-224, p.235）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| visitor_no | INTEGER | NOT NULL | - | No（ナンバリング） |
| name | TEXT | NOT NULL | - | ビジター名 |
| company_name | TEXT | NULL | - | 会社名 |
| specialty | TEXT | NULL | - | 専門分野 |
| sponsor | TEXT | NULL | - | スポンサー |
| attend_member_id | INTEGER | NULL | - | アテンド（FK: members.id） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `attend_member_id` → `members.id`

---

### 7. `substitutes` - 代理出席（p.22-24）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| member_id | INTEGER | NOT NULL | - | 代理出席するメンバーID（FK: members.id） |
| substitute_company | TEXT | NOT NULL | - | 代理出席者の会社名 |
| substitute_name | TEXT | NOT NULL | - | 代理出席者の名前 |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 8. `new_members` - 新入会メンバー（p.25-27, p.100-102）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| member_id | INTEGER | NOT NULL | - | メンバーID（FK: members.id） |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 9. `weekly_no1` - 週間No.1（p.28）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| category | TEXT | NOT NULL | - | 部門（referral/visitor/1to1） |
| member_id | INTEGER | NOT NULL | - | メンバーID（FK: members.id） |
| count | INTEGER | NOT NULL | 0 | 件数 |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 10. `share_story` - シェアストーリー（p.72）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| member_id | INTEGER | NOT NULL | - | メンバーID（FK: members.id） |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 11. `networking_learning` - ネットワーキング学習（p.74-85）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| pdf_path | TEXT | NOT NULL | - | PDF資料パス |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**注意**: PDFは画像に変換してスライドに挿入

---

### 12. `champions` - チャンピオン（p.91-96）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| category | TEXT | NOT NULL | - | 部門（referral/value/visitor/1to1/ceu） |
| rank | INTEGER | NOT NULL | - | 順位（1, 2, 3） |
| member_id | INTEGER | NOT NULL | - | メンバーID（FK: members.id） |
| count | INTEGER | NOT NULL | 0 | 件数 |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

**同率対応**: 同じ週・部門・順位で複数レコード可能

---

### 13. `renewal_members` - 更新メンバー（p.98, p.229）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| member_id | INTEGER | NOT NULL | - | メンバーID（FK: members.id） |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 14. `member_pitch_attendance` - メンバーピッチ出欠（p.112-166）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| member_id | INTEGER | NOT NULL | - | メンバーID（FK: members.id） |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| is_absent | INTEGER | NOT NULL | 0 | 1=不参加, 0=参加 |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**: `member_id` → `members.id`

---

### 15. `recruiting_categories` - 募集カテゴリ（p.185, p.194）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| type | TEXT | NOT NULL | - | 種類（urgent/survey） |
| rank | INTEGER | NULL | - | 順位（survey用: 1-4） |
| category_name | TEXT | NOT NULL | - | カテゴリ名 |
| vote_count | INTEGER | NULL | - | 得票数（survey用） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**type**:
- `urgent`: 激しく募集中（p.185）
- `survey`: アンケート結果（p.194）

---

### 16. `statistics` - 統計情報（p.188, p.189, p.190, p.302）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| type | TEXT | NOT NULL | - | 種類（visitor_total/referral/sales/weekly_goal） |
| data_json | TEXT | NOT NULL | - | 統計データ（JSON） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**data_json 構造例**:

```json
// visitor_total (p.188)
{
  "total_visitors": 500,
  "last_week_count": 10,
  "this_week_count": 8,
  "current_members": 48
}

// referral (p.189)
{
  "as_of_date": "2025-12-14",
  "total_referrals": 1200,
  "last_week_referrals": 30,
  "last_week_average_per_member": 0.625
}

// sales (p.190)
{
  "as_of_date": "2025-12-14",
  "total_sales": 50000000,
  "growth_rate": "+15%"
}

// weekly_goal (p.302)
{
  "last_week_visitors": 10,
  "this_week_visitors": 8,
  "countdown_to_150": 102,
  "weekly_goal": 150
}
```

---

### 17. `referral_verification` - リファーラル真正度（p.227）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| from_member_id | INTEGER | NOT NULL | - | リファーラル元（FK: members.id） |
| to_member_id | INTEGER | NOT NULL | - | リファーラル先（FK: members.id） |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**外部キー**:
- `from_member_id` → `members.id`
- `to_member_id` → `members.id`

---

### 18. `qr_codes` - QRコード（p.242）

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| url | TEXT | NOT NULL | - | アンケートURL |
| qr_code_path | TEXT | NOT NULL | - | QRコード画像パス |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

---

### 19. `slide_visibility` - スライド表示/非表示管理

| カラム名 | 型 | NULL | デフォルト | 説明 |
|---------|-----|------|-----------|------|
| id | INTEGER | NOT NULL | AUTO | 主キー |
| week_date | TEXT | NOT NULL | - | 週の金曜日（YYYY-MM-DD） |
| slide_number | INTEGER | NOT NULL | - | スライド番号（1-309） |
| is_visible | INTEGER | NOT NULL | 1 | 1=表示, 0=非表示 |
| created_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 作成日時 |
| updated_at | TEXT | NOT NULL | CURRENT_TIMESTAMP | 更新日時 |

**デフォルト**: 全スライド is_visible = 1

---

## 📝 インデックス

```sql
-- 検索高速化用インデックス
CREATE INDEX idx_members_name ON members(name);
CREATE INDEX idx_members_is_active ON members(is_active);

CREATE INDEX idx_seating_week ON seating_arrangement(week_date);
CREATE INDEX idx_seating_member ON seating_arrangement(member_id);

CREATE INDEX idx_main_presenter_week ON main_presenter(week_date);
CREATE INDEX idx_speaker_rotation_date ON speaker_rotation(rotation_date);

CREATE INDEX idx_visitors_week ON visitors(week_date);
CREATE INDEX idx_substitutes_week ON substitutes(week_date);
CREATE INDEX idx_new_members_week ON new_members(week_date);

CREATE INDEX idx_weekly_no1_week ON weekly_no1(week_date);
CREATE INDEX idx_champions_week_category ON champions(week_date, category);

CREATE INDEX idx_statistics_week_type ON statistics(week_date, type);
CREATE INDEX idx_slide_visibility_week_number ON slide_visibility(week_date, slide_number);
```

---

## 🚀 次のステップ

1. ✅ データベース設計完了
2. ⏳ SQLファイル作成（schema_v2.sql）
3. ⏳ 初期データ投入SQL作成（48名のメンバー）
4. ⏳ メンバー管理画面実装

---

**最終更新**: 2025-12-14 00:50
