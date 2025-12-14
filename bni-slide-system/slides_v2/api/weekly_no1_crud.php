<?php
/**
 * BNI Slide System V2 - Weekly No.1 CRUD API
 * 週間No.1管理API
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
    case 'get':
    case 'get_latest':
        // 最新のNo.1データ取得
        $stmt = $db->query("
            SELECT
                wn.*,
                m1.name as external_referral_member_name,
                m1.company_name as external_referral_company_name,
                m2.name as visitor_invitation_member_name,
                m2.company_name as visitor_invitation_company_name,
                m3.name as one_to_one_member_name,
                m3.company_name as one_to_one_company_name
            FROM weekly_no1 wn
            LEFT JOIN members m1 ON wn.external_referral_member_id = m1.id
            LEFT JOIN members m2 ON wn.visitor_invitation_member_id = m2.id
            LEFT JOIN members m3 ON wn.one_to_one_member_id = m3.id
            ORDER BY wn.created_at DESC
            LIMIT 1
        ");

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'save':
        // 週間No.1データ保存（全削除→新規挿入）
        $externalReferralMemberId = $_POST['external_referral_member_id'] ?? null;
        $externalReferralCount = $_POST['external_referral_count'] ?? 0;
        $visitorInvitationMemberId = $_POST['visitor_invitation_member_id'] ?? null;
        $visitorInvitationCount = $_POST['visitor_invitation_count'] ?? 0;
        $oneToOneMemberId = $_POST['one_to_one_member_id'] ?? null;
        $oneToOneCount = $_POST['one_to_one_count'] ?? 0;

        // 既存データを全削除
        $db->exec('DELETE FROM weekly_no1');

        // 新規データを挿入
        $stmt = $db->prepare('
            INSERT INTO weekly_no1 (
                external_referral_member_id, external_referral_count,
                visitor_invitation_member_id, visitor_invitation_count,
                one_to_one_member_id, one_to_one_count
            )
            VALUES (
                :external_referral_member_id, :external_referral_count,
                :visitor_invitation_member_id, :visitor_invitation_count,
                :one_to_one_member_id, :one_to_one_count
            )
        ');

        $stmt->bindValue(':external_referral_member_id', $externalReferralMemberId ?: null, PDO::PARAM_INT);
        $stmt->bindValue(':external_referral_count', $externalReferralCount, PDO::PARAM_INT);
        $stmt->bindValue(':visitor_invitation_member_id', $visitorInvitationMemberId ?: null, PDO::PARAM_INT);
        $stmt->bindValue(':visitor_invitation_count', $visitorInvitationCount, PDO::PARAM_INT);
        $stmt->bindValue(':one_to_one_member_id', $oneToOneMemberId ?: null, PDO::PARAM_INT);
        $stmt->bindValue(':one_to_one_count', $oneToOneCount, PDO::PARAM_INT);

        $result = $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => '保存に失敗しました']);
        }
        break;

    case 'delete':
        // 週間No.1データ削除（全削除）
        $db->exec('DELETE FROM weekly_no1');
        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
