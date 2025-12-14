<?php
/**
 * BNI Slide System V2 - Substitutes CRUD API
 * 代理出席管理API（作成・読み取り・更新・削除）
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
        // 全代理出席者一覧取得
        $query = "
            SELECT *
            FROM substitutes
            ORDER BY week_date DESC, substitute_no ASC
        ";
        $stmt = $db->query($query);

        $substitutes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $substitutes[] = $row;
        }

        echo json_encode(['success' => true, 'substitutes' => $substitutes]);
        break;

    case 'get_by_date':
        // 特定日付の代理出席者取得
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT *
            FROM substitutes
            WHERE week_date = :week_date
            ORDER BY substitute_no ASC
        ");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $substitutes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $substitutes[] = $row;
        }

        echo json_encode(['success' => true, 'substitutes' => $substitutes]);
        break;

    case 'get':
        // 特定の代理出席者情報取得
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDが指定されていません']);
            exit;
        }

        $stmt = $db->prepare("SELECT * FROM substitutes WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $substitute = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($substitute) {
            echo json_encode(['success' => true, 'substitute' => $substitute]);
        } else {
            echo json_encode(['success' => false, 'error' => '該当データが見つかりません']);
        }
        break;

    case 'create':
        // 新規代理出席者追加
        $weekDate = $_POST['week_date'] ?? null;
        $memberId = $_POST['member_id'] ?? null;
        $substituteNo = $_POST['substitute_no'] ?? null;
        $companyName = $_POST['company_name'] ?? '';
        $name = $_POST['name'] ?? '';

        if (!$memberId || !$substituteNo || !$companyName || !$name) {
            echo json_encode(['success' => false, 'error' => '全項目は必須です']);
            exit;
        }

        // 最大3名チェック（week_dateがnullの場合は全体でチェック）
        $checkQuery = $weekDate
            ? "SELECT COUNT(*) as count FROM substitutes WHERE week_date = :week_date"
            : "SELECT COUNT(*) as count FROM substitutes WHERE week_date IS NULL";

        $stmt = $db->prepare($checkQuery);
        if ($weekDate) {
            $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        }
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] >= 3) {
            echo json_encode(['success' => false, 'error' => '代理出席者は最大3名までです']);
            exit;
        }

        $stmt = $db->prepare('
            INSERT INTO substitutes (week_date, member_id, substitute_no, substitute_company, substitute_name)
            VALUES (:week_date, :member_id, :substitute_no, :company_name, :name)
        ');

        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
        $stmt->bindValue(':substitute_no', $substituteNo, PDO::PARAM_INT);
        $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertId()
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => '保存に失敗しました']);
        }
        break;

    case 'update':
        // 代理出席者情報更新
        $id = $_POST['id'] ?? null;
        $memberId = $_POST['member_id'] ?? null;
        $substituteNo = $_POST['substitute_no'] ?? null;
        $companyName = $_POST['company_name'] ?? '';
        $name = $_POST['name'] ?? '';

        if (!$id || !$memberId || !$substituteNo || !$companyName || !$name) {
            echo json_encode(['success' => false, 'error' => '全項目は必須です']);
            exit;
        }

        $stmt = $db->prepare('
            UPDATE substitutes
            SET member_id = :member_id,
                substitute_no = :substitute_no,
                substitute_company = :company_name,
                substitute_name = :name,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ');

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);
        $stmt->bindValue(':substitute_no', $substituteNo, PDO::PARAM_INT);
        $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => '更新に失敗しました']);
        }
        break;

    case 'delete':
        // 代理出席者削除
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDは必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM substitutes WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => '削除に失敗しました']);
        }
        break;

    case 'delete_by_date':
        // 特定日付の代理出席者全削除
        $input = json_decode(file_get_contents('php://input'), true);
        $weekDate = $input['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM substitutes WHERE week_date = :week_date');
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => '削除に失敗しました']);
        }
        break;

    case 'get_latest':
        // 最新の代理出席者一覧取得（メンバー情報も結合）
        $stmt = $db->query("
            SELECT
                s.*,
                m.name as member_name
            FROM substitutes s
            LEFT JOIN members m ON s.member_id = m.id
            ORDER BY s.created_at DESC, s.substitute_no ASC
        ");

        $substitutes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $substitutes[] = [
                'id' => $row['id'],
                'member_id' => $row['member_id'],
                'member_name' => $row['member_name'] ?? 'undefined',
                'company_name' => $row['substitute_company'],
                'name' => $row['substitute_name'],
                'substitute_no' => $row['substitute_no'],
                'week_date' => $row['week_date'],
                'created_at' => $row['created_at']
            ];
        }

        echo json_encode(['success' => true, 'substitutes' => $substitutes]);
        break;

    case 'delete_all':
        // 全代理出席者削除
        $db->exec('DELETE FROM substitutes');
        echo json_encode(['success' => true]);
        break;

    case 'get_next_no':
        // 次のNo取得（自動ナンバリング用）
        $stmt = $db->query("
            SELECT COALESCE(MAX(substitute_no), 0) + 1 as next_no
            FROM substitutes
        ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'next_no' => $row['next_no']]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
