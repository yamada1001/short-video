# BNI Slide System V2 - Database Guide

## Quick Start

### Initialize Fresh Database
```bash
cd /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system

# Full setup (schema + migration + members + test data)
sqlite3 slides_v2/database/bni_slides_v2.db < database/schema_v2.sql && \
sqlite3 slides_v2/database/bni_slides_v2.db < slides_v2/database/migration_fix_week_date.sql && \
sqlite3 slides_v2/database/bni_slides_v2.db < database/initial_members_v2.sql && \
sqlite3 slides_v2/database/bni_slides_v2.db < slides_v2/database/test_data_insertion_fixed.sql
```

### Verify Database
```bash
# Check members count (should be 48)
sqlite3 slides_v2/database/bni_slides_v2.db "SELECT COUNT(*) FROM members;"

# Check test data
sqlite3 slides_v2/database/bni_slides_v2.db "
SELECT 'Visitors:' as table_name, COUNT(*) as count FROM visitors
UNION ALL SELECT 'Substitutes:', COUNT(*) FROM substitutes
UNION ALL SELECT 'New Members:', COUNT(*) FROM new_members
UNION ALL SELECT 'Renewal Members:', COUNT(*) FROM renewal_members;
"
```

## Schema Overview

### Core Tables
- **members** - 48 BNI members (required for all other data)
- **visitors** - Weekly visitors
- **substitutes** - Substitute attendees
- **new_members** - New member announcements
- **renewal_members** - Member renewals
- **weekly_no1** - Weekly top performers (referral, visitor, 1to1)
- **share_story** - Share story presenter
- **main_presenter** - Main presentation

### Important Changes (2025-12-14)
- All `week_date` columns are now **NULLABLE**
- `substitutes` table now has `substitute_no` column
- `member_id` in substitutes is now nullable

## File Reference

### Schema Files
- `database/schema_v2.sql` - Main schema definition
- `slides_v2/database/migration_fix_week_date.sql` - Schema fixes (week_date nullable)

### Data Files
- `database/initial_members_v2.sql` - 48 members initial data
- `slides_v2/database/test_data_insertion_fixed.sql` - Test data (CORRECT VERSION)
- `slides_v2/database/test_data_insertion.sql` - ❌ DEPRECATED (has errors)

### Documentation
- `slides_v2/database/MIGRATION_SUMMARY.md` - Detailed migration report
- `slides_v2/database/README_DATABASE.md` - This file

## Common Operations

### Insert New Visitor
```sql
INSERT INTO visitors (visitor_no, name, company_name, specialty, sponsor)
VALUES (1, '山田太郎', '山田商事', 'IT consulting', '鈴木');
```

### Insert New Substitute
```sql
INSERT INTO substitutes (substitute_no, substitute_company, substitute_name)
VALUES (1, 'ABC株式会社', '田中花子');
```

### Link New Member
```sql
INSERT INTO new_members (member_id)
SELECT id FROM members WHERE name = '高橋' LIMIT 1;
```

### Set Weekly No.1
```sql
INSERT INTO weekly_no1 (category, member_id, count)
VALUES ('referral', 10, 5);
```

### Set Main Presenter
```sql
INSERT INTO main_presenter (member_id, youtube_url)
VALUES (25, 'https://www.youtube.com/watch?v=...');
```

## Table Schemas

### visitors
```sql
id INTEGER PRIMARY KEY
week_date TEXT (nullable)
visitor_no INTEGER NOT NULL
name TEXT NOT NULL
company_name TEXT
specialty TEXT
sponsor TEXT
attend_member_id INTEGER (FK to members.id)
created_at TEXT
updated_at TEXT
```

### substitutes
```sql
id INTEGER PRIMARY KEY
week_date TEXT (nullable)
substitute_no INTEGER (nullable)
member_id INTEGER (nullable, FK to members.id)
substitute_company TEXT NOT NULL
substitute_name TEXT NOT NULL
created_at TEXT
updated_at TEXT
```

### new_members
```sql
id INTEGER PRIMARY KEY
member_id INTEGER NOT NULL (FK to members.id)
week_date TEXT (nullable)
created_at TEXT
updated_at TEXT
```

### renewal_members
```sql
id INTEGER PRIMARY KEY
member_id INTEGER NOT NULL (FK to members.id)
week_date TEXT (nullable)
created_at TEXT
updated_at TEXT
```

### weekly_no1
```sql
id INTEGER PRIMARY KEY
week_date TEXT (nullable)
category TEXT NOT NULL ('referral', 'visitor', '1to1')
member_id INTEGER NOT NULL (FK to members.id)
count INTEGER NOT NULL DEFAULT 0
created_at TEXT
updated_at TEXT
```

### share_story
```sql
id INTEGER PRIMARY KEY
member_id INTEGER NOT NULL (FK to members.id)
week_date TEXT (nullable)
created_at TEXT
updated_at TEXT
```

### main_presenter
```sql
id INTEGER PRIMARY KEY
member_id INTEGER NOT NULL (FK to members.id)
week_date TEXT (nullable)
pdf_path TEXT
youtube_url TEXT
created_at TEXT
updated_at TEXT
```

## Troubleshooting

### Error: "no such table: renewal"
**Solution:** Table name is `renewal_members` not `renewal`

### Error: "table substitutes has no column named substitute_no"
**Solution:** Run migration script `migration_fix_week_date.sql`

### Error: "NOT NULL constraint failed: visitors.week_date"
**Solution:** Run migration script to make week_date nullable

### Error: "FOREIGN KEY constraint failed"
**Solution:** Ensure members exist before inserting data with member_id references

## Test Data

Current test data includes:
- 3 visitors (山田 太郎, 鈴木 花子, 佐々木 健一)
- 3 substitutes (伊藤 孝, 渡辺 美咲, 中村 大輔)
- 3 new members (IDs: 5, 12, 23)
- 3 renewal members (IDs: 7, 15, 28)
- 3 weekly no.1 entries (referral, visitor, 1to1)
- 1 share story (ID: 18)
- 1 main presenter (ID: 25)

## Database Location

**Primary Database:**
```
/Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/slides_v2/database/bni_slides_v2.db
```

## Version History

### 2025-12-14 - Schema Fix
- Made all week_date columns nullable
- Added substitute_no to substitutes table
- Fixed test data insertion script
- Added 48 initial members
