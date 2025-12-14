<?php
/**
 * BNI Slide System V2 - New Members CRUD API
 * 新入会メンバー管理API（作成・読み取り・更新・削除）
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
    case 'list':
        // 全新入会メンバー一覧取得
        $query = "
            SELECT
                nm.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM new_members nm
            LEFT JOIN members m ON nm.member_id = m.id
            ORDER BY nm.week_date DESC, nm.id ASC
        ";
        $stmt = $db->query($query);

        $newMembers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $newMembers[] = $row;
        }

        echo json_encode(['success' => true, 'new_members' => $newMembers]);
        break;

    case 'get_by_date':
        // 特定日付の新入会メンバー取得
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                nm.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM new_members nm
            LEFT JOIN members m ON nm.member_id = m.id
            WHERE nm.week_date = :week_date
            ORDER BY nm.id ASC
        ");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $newMembers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $newMembers[] = $row;
        }

        echo json_encode(['success' => true, 'new_members' => $newMembers]);
        break;

    case 'create':
        // 新規新入会メンバー追加
        $weekDate = $_POST['week_date'] ?? null;
        $memberId = $_POST['member_id'] ?? null;

        if (!$weekDate || !$memberId) {
            echo json_encode(['success' => false, 'error' => '全項目は必須です']);
            exit;
        }

        // 最大3名チェック
        $stmt = $db->prepare("SELECT COUNT(*) as count FROM new_members WHERE week_date = :week_date");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] >= 3) {
            echo json_encode(['success' => false, 'error' => '新入会メンバーは最大3名までです']);
            exit;
        }

        // 重複チェック
        $stmt = $db->prepare("
            SELECT COUNT(*) as count
            FROM new_members
            WHERE week_date = :week_date AND member_id = :member_id
        ");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            echo json_encode(['success' => false, 'error' => 'このメンバーは既に登録されています']);
            exit;
        }

        $stmt = $db->prepare('
            INSERT INTO new_members (week_date, member_id)
            VALUES (:week_date, :member_id)
        ');

        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);

        $stmt->execute();

        if ($result) {
            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertId()
            ]);
        
        break;

    case 'delete':
        // 新入会メンバー削除
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDは必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM new_members WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        
        break;

    case 'delete_by_date':
        // 特定日付の新入会メンバー全削除
        $input = json_decode(file_get_contents('php://input'), true);
        $weekDate = $input['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM new_members WHERE week_date = :week_date');
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
