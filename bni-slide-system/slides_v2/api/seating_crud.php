<?php
/**
 * BNI Slide System V2 - Seating CRUD API
 * 座席配置管理API（作成・読み取り・更新・削除）
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

// POSTデータを取得（JSON形式の場合）
$postData = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents('php://input');
    $postData = json_decode($rawData, true);
    if ($postData) {
        $action = $postData['action'] ?? $action;
    }
}

switch ($action) {
    case 'get':
        // 座席配置取得（特定の週の日付）
        $weekDate = $_GET['week_date'] ?? null;

        if (empty($weekDate)) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $query = "
            SELECT table_name, member_id, position
            FROM seating_arrangement
            WHERE week_date = :week_date
            ORDER BY table_name, position
        ";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $seating = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableName = $row['table_name'];
            if (!isset($seating[$tableName])) {
                $seating[$tableName] = [];
            }
            $seating[$tableName][] = $row['member_id'];
        }

        echo json_encode(['success' => true, 'seating' => $seating]);
        break;

    case 'save':
        // 座席配置保存（既存のデータを削除して新規保存）
        $weekDate = $postData['week_date'] ?? null;
        $seatingData = $postData['seating'] ?? [];

        if (empty($weekDate)) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $db->beginTransaction();

        try {
            // 既存のデータを削除
            $deleteStmt = $db->prepare('DELETE FROM seating_arrangement WHERE week_date = :week_date');
            $deleteStmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
            $deleteStmt->execute();

            // 新しいデータを挿入
            if (!empty($seatingData)) {
                $insertStmt = $db->prepare('
                    INSERT INTO seating_arrangement (table_name, member_id, position, week_date)
                    VALUES (:table_name, :member_id, :position, :week_date)
                ');

                foreach ($seatingData as $seat) {
                    $insertStmt->bindValue(':table_name', $seat['table_name'], PDO::PARAM_STR);
                    $insertStmt->bindValue(':member_id', $seat['member_id'], PDO::PARAM_INT);
                    $insertStmt->bindValue(':position', $seat['position'], PDO::PARAM_INT);
                    $insertStmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
                    $insertStmt->execute();
                }
            }

            $db->commit();
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        break;

    case 'list':
        // 全座席配置取得（日付別）
        $query = "
            SELECT DISTINCT week_date
            FROM seating_arrangement
            ORDER BY week_date DESC
        ";

        $stmt = $db->query($query);

        $dates = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dates[] = $row['week_date'];
        }

        echo json_encode(['success' => true, 'dates' => $dates]);
        break;

    case 'delete':
        // 特定日の座席配置削除
        $weekDate = $postData['week_date'] ?? null;

        if (empty($weekDate)) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM seating_arrangement WHERE week_date = :week_date');
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true]);
        break;

    case 'get_for_slide':
        // スライド表示用データ取得（メンバー情報も含む）
        $weekDate = $_GET['week_date'] ?? null;

        if (empty($weekDate)) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $query = "
            SELECT
                sa.table_name,
                sa.position,
                sa.member_id,
                m.name,
                m.company_name,
                m.category,
                m.photo_path
            FROM seating_arrangement sa
            LEFT JOIN members m ON sa.member_id = m.id
            WHERE sa.week_date = :week_date
            ORDER BY sa.table_name, sa.position
        ";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $seating = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tableName = $row['table_name'];
            if (!isset($seating[$tableName])) {
                $seating[$tableName] = [];
            }
            $seating[$tableName][] = [
                'member_id' => $row['member_id'],
                'name' => $row['name'],
                'company_name' => $row['company_name'],
                'category' => $row['category'],
                'photo_path' => $row['photo_path'],
                'position' => $row['position']
            ];
        }

        echo json_encode(['success' => true, 'seating' => $seating]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
