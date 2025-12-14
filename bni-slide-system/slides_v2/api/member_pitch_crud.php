<?php
/**
 * BNI Slide System V2 - Member Pitch CRUD API
 * メンバーピッチ管理API
 */

header('Content-Type: application/json');

$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';

try {
    $db = new SQLite3($dbPath);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'データベース接続エラー']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'get_by_date':
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                m.id,
                m.name,
                m.company_name,
                m.photo_path,
                COALESCE(mpa.is_absent, 0) as is_absent
            FROM members m
            LEFT JOIN member_pitch_attendance mpa
                ON m.id = mpa.member_id AND mpa.week_date = :week_date
            WHERE m.is_active = 1
            ORDER BY m.name ASC
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();

        $members = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $members[] = $row;
        }

        echo json_encode(['success' => true, 'members' => $members]);
        break;

    case 'toggle_absence':
        $weekDate = $_POST['week_date'] ?? null;
        $memberId = $_POST['member_id'] ?? null;
        $isAbsent = $_POST['is_absent'] ?? 0;

        if (!$weekDate || !$memberId) {
            echo json_encode(['success' => false, 'error' => '全項目は必須です']);
            exit;
        }

        // 既存データ確認
        $stmt = $db->prepare("
            SELECT id FROM member_pitch_attendance
            WHERE week_date = :week_date AND member_id = :member_id
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':member_id', $memberId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $existing = $result->fetchArray(SQLITE3_ASSOC);

        if ($existing) {
            // 更新
            $stmt = $db->prepare('
                UPDATE member_pitch_attendance
                SET is_absent = :is_absent,
                    updated_at = CURRENT_TIMESTAMP
                WHERE week_date = :week_date AND member_id = :member_id
            ');
        } else {
            // 新規
            $stmt = $db->prepare('
                INSERT INTO member_pitch_attendance (week_date, member_id, is_absent)
                VALUES (:week_date, :member_id, :is_absent)
            ');
        }

        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':member_id', $memberId, SQLITE3_INTEGER);
        $stmt->bindValue(':is_absent', $isAbsent, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}

$db->close();
