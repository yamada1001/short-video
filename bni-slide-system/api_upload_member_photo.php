<?php
/**
 * BNI Slide System - Member Photo Upload API
 * メンバー写真アップロードAPI
 */

session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/auth.php';

// CORS Headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

// OPTIONS request handling
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // ログインチェック（管理者のみ）
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => '管理者権限が必要です'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // データベース接続
    $db = getDBConnection();

    // メンバーID取得
    $member_id = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;

    if ($member_id === 0) {
        throw new Exception('メンバーIDが指定されていません');
    }

    // ファイルアップロード処理
    $photo_url = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/member_photos/';

        // ディレクトリ確認
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // ファイル名を安全にする
        $originalName = basename($_FILES['photo']['name']);
        $fileExt = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // 許可する拡張子
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExt, $allowedExts)) {
            throw new Exception('画像ファイル（jpg, png, gif）のみアップロード可能です');
        }

        // ユニークなファイル名を生成
        $newFileName = 'member_' . $member_id . '_' . time() . '.' . $fileExt;
        $uploadPath = $uploadDir . $newFileName;

        // ファイル移動
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
            $photo_url = 'uploads/member_photos/' . $newFileName;
        } else {
            throw new Exception('ファイルのアップロードに失敗しました');
        }
    }

    // メンバー情報更新
    if ($photo_url) {
        $stmt = $db->prepare('UPDATE member_photos SET photo_url = :photo_url, updated_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute([
            ':photo_url' => $photo_url,
            ':id' => $member_id
        ]);
    }

    // その他のフィールド更新（任意）
    $updateFields = [];
    $params = [':id' => $member_id];

    if (isset($_POST['name'])) {
        $updateFields[] = 'name = :name';
        $params[':name'] = $_POST['name'];
    }

    if (isset($_POST['name_highlight'])) {
        $updateFields[] = 'name_highlight = :name_highlight';
        $params[':name_highlight'] = $_POST['name_highlight'];
    }

    if (isset($_POST['company'])) {
        $updateFields[] = 'company = :company';
        $params[':company'] = $_POST['company'];
    }

    if (isset($_POST['industry'])) {
        $updateFields[] = 'industry = :industry';
        $params[':industry'] = $_POST['industry'];
    }

    if (isset($_POST['position_title'])) {
        $updateFields[] = 'position_title = :position_title';
        $params[':position_title'] = $_POST['position_title'];
    }

    if (isset($_POST['position_title_en'])) {
        $updateFields[] = 'position_title_en = :position_title_en';
        $params[':position_title_en'] = $_POST['position_title_en'];
    }

    if (isset($_POST['display_order'])) {
        $updateFields[] = 'display_order = :display_order';
        $params[':display_order'] = intval($_POST['display_order']);
    }

    if (!empty($updateFields)) {
        $updateFields[] = 'updated_at = CURRENT_TIMESTAMP';
        $sql = 'UPDATE member_photos SET ' . implode(', ', $updateFields) . ' WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
    }

    // 更新後のデータ取得
    $stmt = $db->prepare('SELECT * FROM member_photos WHERE id = :id');
    $stmt->execute([':id' => $member_id]);
    $updatedMember = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => 'メンバー情報を更新しました',
        'member' => $updatedMember,
        'photo_url' => $photo_url
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
