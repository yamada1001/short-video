<?php
/**
 * BNI Slide System V2 - Share Story CRUD API
 * シェアストーリー管理API
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
                ss.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM share_story ss
            LEFT JOIN members m ON ss.member_id = m.id
            WHERE ss.week_date = :week_date
        ");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'save':
        $weekDate = $_POST['week_date'] ?? null;
        $memberId = $_POST['member_id'] ?? null;

        if (!$weekDate || !$memberId) {
            echo json_encode(['success' => false, 'error' => '全項目は必須です']);
            exit;
        }

        // 既存データ確認
        $stmt = $db->prepare("SELECT id FROM share_story WHERE week_date = :week_date");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // 更新
            $stmt = $db->prepare('
                UPDATE share_story
                SET member_id = :member_id,
                    updated_at = CURRENT_TIMESTAMP
                WHERE week_date = :week_date
            ');
        } else {
            // 新規
            $stmt = $db->prepare('
                INSERT INTO share_story (week_date, member_id)
                VALUES (:week_date, :member_id)
            ');
        }

        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => '保存に失敗しました']);
        }
        break;

    case 'delete':
        $input = json_decode(file_get_contents('php://input'), true);
        $weekDate = $input['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM share_story WHERE week_date = :week_date');
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => '削除に失敗しました']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
