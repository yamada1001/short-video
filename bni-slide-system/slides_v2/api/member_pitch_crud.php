<?php
/**
 * BNI Slide System V2 - Member Pitch CRUD API
 * メンバーピッチ管理API
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $members = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $members[] = $row;
        }

        echo json_encode(['success' => true, 'members' => $members]);
        break;

    case 'get_latest':
        // 最新のweek_dateのメンバーピッチ出席データ取得
        $latestWeekStmt = $db->query("SELECT MAX(week_date) as latest_week FROM member_pitch_attendance");
        $latestWeekRow = $latestWeekStmt->fetch(PDO::FETCH_ASSOC);
        $latestWeek = $latestWeekRow['latest_week'];

        if ($latestWeek) {
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
            $stmt->bindValue(':week_date', $latestWeek, PDO::PARAM_STR);
            $stmt->execute();

            $members = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $members[] = $row;
            }

            echo json_encode(['success' => true, 'members' => $members, 'week_date' => $latestWeek]);
        } else {
            echo json_encode(['success' => true, 'members' => [], 'week_date' => null]);
        }
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
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

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

        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
        $stmt->bindValue(':is_absent', $isAbsent, PDO::PARAM_INT);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => '保存に失敗しました']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
