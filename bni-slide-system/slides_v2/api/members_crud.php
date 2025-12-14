<?php
/**
 * BNI Slide System V2 - Members CRUD API
 * メンバー管理API（作成・読み取り・更新・削除）
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
        // メンバー一覧取得
        $query = "SELECT * FROM members ORDER BY is_active DESC, name ASC";
        $stmt = $db->query($query);

        $members = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $members[] = $row;
        }

        echo json_encode(['success' => true, 'members' => $members]);
        break;

    case 'get_latest':
        // 最新のメンバー取得（最新のcreated_atまたはupdated_at）
        $stmt = $db->query("
            SELECT * FROM members
            ORDER BY updated_at DESC, created_at DESC
            LIMIT 1
        ");

        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($member) {
            echo json_encode(['success' => true, 'member' => $member]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データが見つかりません']);
        }
        break;

    case 'create':
        // 新規メンバー追加
        $name = $_POST['name'] ?? '';
        $companyName = $_POST['company_name'] ?? null;
        $category = $_POST['category'] ?? null;
        $birthday = $_POST['birthday'] ?? null;
        $isActive = $_POST['is_active'] ?? 1;

        if (empty($name)) {
            echo json_encode(['success' => false, 'error' => '名前は必須です']);
            exit;
        }

        // 写真アップロード処理
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../data/uploads/members/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('member_') . '.' . $ext;
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $photoPath = '../data/uploads/members/' . $filename;
            }
        }

        $stmt = $db->prepare('
            INSERT INTO members (name, company_name, category, photo_path, birthday, is_active)
            VALUES (:name, :company_name, :category, :photo_path, :birthday, :is_active)
        ');

        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':photo_path', $photoPath, PDO::PARAM_STR);
        $stmt->bindValue(':birthday', $birthday, PDO::PARAM_STR);
        $stmt->bindValue(':is_active', $isActive, PDO::PARAM_INT);

        $stmt->execute();

        echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
        break;

    case 'update':
        // メンバー更新
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $companyName = $_POST['company_name'] ?? null;
        $category = $_POST['category'] ?? null;
        $birthday = $_POST['birthday'] ?? null;
        $isActive = $_POST['is_active'] ?? 1;

        if (empty($id) || empty($name)) {
            echo json_encode(['success' => false, 'error' => 'IDと名前は必須です']);
            exit;
        }

        // 写真アップロード処理
        $photoPath = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../data/uploads/members/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid('member_') . '.' . $ext;
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $photoPath = '../data/uploads/members/' . $filename;
            }
        }

        if ($photoPath) {
            $stmt = $db->prepare('
                UPDATE members
                SET name = :name,
                    company_name = :company_name,
                    category = :category,
                    photo_path = :photo_path,
                    birthday = :birthday,
                    is_active = :is_active,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
            $stmt->bindValue(':photo_path', $photoPath, PDO::PARAM_STR);
        } else {
            $stmt = $db->prepare('
                UPDATE members
                SET name = :name,
                    company_name = :company_name,
                    category = :category,
                    birthday = :birthday,
                    is_active = :is_active,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :id
            ');
        }

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':birthday', $birthday, PDO::PARAM_STR);
        $stmt->bindValue(':is_active', $isActive, PDO::PARAM_INT);

        $stmt->execute();

        echo json_encode(['success' => true]);
        break;

    case 'delete':
        // メンバー削除
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (empty($id)) {
            echo json_encode(['success' => false, 'error' => 'IDは必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM members WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
