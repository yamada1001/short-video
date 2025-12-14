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
    case 'get_latest':
        // 最新のシェアストーリーデータ取得
        $stmt = $db->query("
            SELECT
                ss.*,
                m.name as member_name,
                m.company_name,
                m.photo_path
            FROM share_story ss
            LEFT JOIN members m ON ss.member_id = m.id
            ORDER BY ss.created_at DESC
            LIMIT 1
        ");

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'save':
        $memberId = $_POST['member_id'] ?? null;

        if (!$memberId) {
            echo json_encode(['success' => false, 'error' => 'メンバーIDは必須です']);
            exit;
        }

        // 既存データを全削除
        $db->exec('DELETE FROM share_story');

        // 新規データを挿入
        $stmt = $db->prepare('
            INSERT INTO share_story (member_id)
            VALUES (:member_id)
        ');

        $stmt->bindValue(':member_id', $memberId, PDO::PARAM_INT);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => '保存に失敗しました']);
        }
        break;

    case 'delete':
        // シェアストーリーデータ削除（全削除）
        $db->exec('DELETE FROM share_story');
        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
