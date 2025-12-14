<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');
try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) { echo json_encode(['success' => false, 'error' => 'データベース接続エラー']); exit; }
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$postData = json_decode(file_get_contents('php://input'), true);
if ($postData) { $action = $postData['action'] ?? $action; }

switch ($action) {
    case 'list':
        $weekDate = $_GET['week_date'] ?? null;
        if (!$weekDate) { echo json_encode(['success' => false, 'error' => '日付が必要です']); exit; }
        $stmt = $db->prepare("SELECT r.*, m1.name as from_name, m2.name as to_name FROM referral_verification r LEFT JOIN members m1 ON r.from_member_id = m1.id LEFT JOIN members m2 ON r.to_member_id = m2.id WHERE r.week_date = :week_date ORDER BY r.id");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();
        $verifications = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $verifications[] = $row; }
        echo json_encode(['success' => true, 'verifications' => $verifications]);
        break;

    case 'create':
        $weekDate = $postData['week_date'] ?? null;
        $fromMemberId = $postData['from_member_id'] ?? null;
        $toMemberId = $postData['to_member_id'] ?? null;
        if (!$weekDate || !$fromMemberId || !$toMemberId) { echo json_encode(['success' => false, 'error' => '必要なデータが不足しています']); exit; }
        
        $stmt = $db->prepare("INSERT INTO referral_verification (week_date, from_member_id, to_member_id) VALUES (:week_date, :from_member_id, :to_member_id)");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':from_member_id', $fromMemberId, PDO::PARAM_INT);
        $stmt->bindValue(':to_member_id', $toMemberId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベースエラー']);
        }
        break;

    case 'delete':
        $id = $postData['id'] ?? null;
        if (!$id) { echo json_encode(['success' => false, 'error' => 'IDが必要です']); exit; }
        
        $stmt = $db->prepare("DELETE FROM referral_verification WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベース削除エラー']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '無効なアクションです']);
        break;
}
