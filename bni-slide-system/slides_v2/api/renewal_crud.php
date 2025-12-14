<?php
/**
 * BNI Slide System V2 - Renewal Members CRUD API
 * 更新メンバー管理API
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
                rm.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM renewal_members rm
            LEFT JOIN members m ON rm.member_id = m.id
            WHERE rm.week_date = :week_date
            ORDER BY rm.id ASC
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();

        $renewalMembers = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $renewalMembers[] = $row;
        }

        echo json_encode(['success' => true, 'renewal_members' => $renewalMembers]);
        break;

    case 'create':
        $weekDate = $_POST['week_date'] ?? null;
        $memberId = $_POST['member_id'] ?? null;

        if (!$weekDate || !$memberId) {
            echo json_encode(['success' => false, 'error' => '全項目は必須です']);
            exit;
        }

        // 重複チェック
        $stmt = $db->prepare("
            SELECT COUNT(*) as count
            FROM renewal_members
            WHERE week_date = :week_date AND member_id = :member_id
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':member_id', $memberId, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row['count'] > 0) {
            echo json_encode(['success' => false, 'error' => 'このメンバーは既に登録されています']);
            exit;
        }

        $stmt = $db->prepare('
            INSERT INTO renewal_members (week_date, member_id)
            VALUES (:week_date, :member_id)
        ');

        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':member_id', $memberId, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true, 'id' => $db->lastInsertRowID()]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    case 'delete':
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDは必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM renewal_members WHERE id = :id');
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
        break;

    case 'delete_by_date':
        $input = json_decode(file_get_contents('php://input'), true);
        $weekDate = $input['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM renewal_members WHERE week_date = :week_date');
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
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
