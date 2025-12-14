# Main Presenter File Upload Fix Report
**Date:** 2025-12-14
**Status:** FIXED

## User Report
"ファイルを添付して「保存」を押すと失敗します"

## Issues Found and Fixed

### 1. CRITICAL: Missing `enctype="multipart/form-data"` in Form
**File:** `slides_v2/admin/main_presenter.php` (line 429)

**Problem:**
```html
<form id="presenterForm">
```

**Fixed:**
```html
<form id="presenterForm" enctype="multipart/form-data">
```

**Impact:** Without this attribute, file uploads cannot work at all. The browser sends files as plain text instead of binary data.

---

### 2. CRITICAL: Missing `read` Action in API
**File:** `slides_v2/api/main_presenter_crud.php` (lines 52-83)

**Problem:**
- Frontend calls `action=read` to load existing data
- API only had `action=get`, not `action=read`
- This caused a mismatch and "unknown action" errors

**Fixed:**
```php
case 'get':
case 'read':  // Added this line
    // ... existing code ...
```

Also changed response format to match frontend expectations:
```php
// Old:
echo json_encode(['success' => true, 'presentation' => $presentation]);

// New:
echo json_encode(['success' => true, 'data' => $presentation]);
```

---

### 3. CRITICAL: Missing ID in Update Action
**File:** `slides_v2/api/main_presenter_crud.php` (lines 149-172)

**Problem:**
- Frontend doesn't send `id` field when updating
- Update action required `id` parameter, causing updates to fail
- Error: "ID、メンバーID、開催日は必須です"

**Fixed:**
```php
// Old code:
$id = $_POST['id'] ?? null;
if (!$id || !$memberId || !$weekDate) {
    echo json_encode(['success' => false, 'error' => 'ID、メンバーID、開催日は必須です']);
    exit;
}

// New code - Auto-detect ID from week_date:
$memberId = $_POST['member_id'] ?? null;
$weekDate = $_POST['week_date'] ?? null;

if (!$memberId || !$weekDate) {
    echo json_encode(['success' => false, 'error' => 'メンバーID、開催日は必須です']);
    exit;
}

// week_dateからIDを取得
$checkStmt = $db->prepare('SELECT id FROM main_presenter WHERE week_date = :week_date');
$checkStmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
$checkStmt->execute();
$existing = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$existing) {
    echo json_encode(['success' => false, 'error' => '更新対象のデータが見つかりません']);
    exit;
}

$id = $existing['id'];
```

---

### 4. MISSING: `presentation_type` Column in Database
**Database:** `slides_v2/data/bni_slide_system.db`

**Problem:**
- Code references `presentation_type` field but it didn't exist in the database
- Would cause SQL errors when inserting/updating

**Fixed:**
```sql
ALTER TABLE main_presenter ADD COLUMN presentation_type TEXT DEFAULT 'simple';
```

---

### 5. MISSING: `presentation_type` in SQL Queries
**File:** `slides_v2/api/main_presenter_crud.php` (lines 126-136, 188-216)

**Problem:**
- INSERT and UPDATE queries didn't include `presentation_type` field
- User's selection of "simple" vs "extended" was not being saved

**Fixed CREATE:**
```php
$stmt = $db->prepare('
    INSERT INTO main_presenter (member_id, week_date, presentation_type, pdf_path, youtube_url)
    VALUES (:member_id, :week_date, :presentation_type, :pdf_path, :youtube_url)
');

$stmt->bindValue(':presentation_type', $presentationType, PDO::PARAM_STR);
```

**Fixed UPDATE:**
```php
$stmt = $db->prepare('
    UPDATE main_presenter
    SET member_id = :member_id,
        week_date = :week_date,
        presentation_type = :presentation_type,  // Added
        pdf_path = :pdf_path,
        youtube_url = :youtube_url,
        updated_at = CURRENT_TIMESTAMP
    WHERE id = :id
');

$stmt->bindValue(':presentation_type', $presentationType, PDO::PARAM_STR);
```

---

## Summary of Changes

### Files Modified:
1. `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/slides_v2/admin/main_presenter.php`
   - Added `enctype="multipart/form-data"` to form

2. `/Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/slides_v2/api/main_presenter_crud.php`
   - Added `case 'read'` alongside `case 'get'`
   - Changed response format to use `data` instead of `presentation`
   - Removed `id` requirement from update action
   - Auto-detect ID from `week_date` in update action
   - Added `presentation_type` to CREATE query
   - Added `presentation_type` to UPDATE queries
   - Added `presentation_type` parameter binding

3. **Database Schema:**
   - Added `presentation_type` column to `main_presenter` table

---

## Testing

### Manual Test Steps:
1. Open `http://localhost:8000/admin/main_presenter.php`
2. Select a date (e.g., next Friday)
3. Select a member
4. Choose "拡張版（p.204）"
5. Upload a PDF file
6. Click "保存" button
7. Verify success message appears
8. Check that data is saved in database
9. Reload page and verify data loads correctly
10. Upload different PDF and click "保存" again (should update, not create new)

### Automated Test:
Run the test file:
```bash
cd /Users/yamadaren/Movies/claude-code-projects/yojitu.com/bni-slide-system/slides_v2
php -S localhost:8000 &
php test_file_upload.php
```

---

## Root Cause Analysis

The file upload was failing due to **4 separate issues**:

1. **Form encoding** - Browser couldn't send files without `multipart/form-data`
2. **API mismatch** - Frontend and backend using different action names
3. **Missing ID** - Update action expecting ID that frontend never sends
4. **Database schema** - Missing column that code expected

All issues have been resolved. The system should now:
- ✅ Accept file uploads correctly
- ✅ Load existing data when selecting a date
- ✅ Create new presentations with files
- ✅ Update existing presentations with new files
- ✅ Save presentation type (simple/extended)

---

## Prevention

To prevent similar issues:
1. Always use `enctype="multipart/form-data"` for forms with file inputs
2. Keep API action names consistent with frontend calls
3. Don't require IDs that aren't sent by the frontend (use natural keys)
4. Ensure database schema matches code expectations
5. Add integration tests for file upload functionality
