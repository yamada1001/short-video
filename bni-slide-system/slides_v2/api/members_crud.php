<?php
/**
 * BNI Slide System V2 - Members CRUD API
 * メンバー管理API（作成・読み取り・更新・削除）
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
    case 'list':
        // メンバー一覧取得
        $query = "SELECT * FROM members ORDER BY is_active DESC, name ASC";
        $result = $db->query($query);

        $members = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $members[] = $row;
        }

        echo json_encode(['success' => true, 'members' => $members]);
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

        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':company_name', $companyName, SQLITE3_TEXT);
        $stmt->bindValue(':category', $category, SQLITE3_TEXT);
        $stmt->bindValue(':photo_path', $photoPath, SQLITE3_TEXT);
        $stmt->bindValue(':birthday', $birthday, SQLITE3_TEXT);
        $stmt->bindValue(':is_active', $isActive, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true, 'id' => $db->lastInsertRowID()]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
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
            $stmt->bindValue(':photo_path', $photoPath, SQLITE3_TEXT);
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

        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':company_name', $companyName, SQLITE3_TEXT);
        $stmt->bindValue(':category', $category, SQLITE3_TEXT);
        $stmt->bindValue(':birthday', $birthday, SQLITE3_TEXT);
        $stmt->bindValue(':is_active', $isActive, SQLITE3_INTEGER);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $db->lastErrorMsg()]);
        }
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
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
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
