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
        // 特定日付のNo.1データ取得
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
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
            WHERE wn.week_date = :week_date
        ");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'save':
        // 週間No.1データ保存（新規/更新）
        $weekDate = $_POST['week_date'] ?? null;
        $externalReferralMemberId = $_POST['external_referral_member_id'] ?? null;
        $externalReferralCount = $_POST['external_referral_count'] ?? 0;
        $visitorInvitationMemberId = $_POST['visitor_invitation_member_id'] ?? null;
        $visitorInvitationCount = $_POST['visitor_invitation_count'] ?? 0;
        $oneToOneMemberId = $_POST['one_to_one_member_id'] ?? null;
        $oneToOneCount = $_POST['one_to_one_count'] ?? 0;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        // 既存データ確認
        $stmt = $db->prepare("SELECT id FROM weekly_no1 WHERE week_date = :week_date");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            // 更新
            $stmt = $db->prepare('
                UPDATE weekly_no1
                SET external_referral_member_id = :external_referral_member_id,
                    external_referral_count = :external_referral_count,
                    visitor_invitation_member_id = :visitor_invitation_member_id,
                    visitor_invitation_count = :visitor_invitation_count,
                    one_to_one_member_id = :one_to_one_member_id,
                    one_to_one_count = :one_to_one_count,
                    updated_at = CURRENT_TIMESTAMP
                WHERE week_date = :week_date
            ');
        } else {
            // 新規
            $stmt = $db->prepare('
                INSERT INTO weekly_no1 (
                    week_date,
                    external_referral_member_id, external_referral_count,
                    visitor_invitation_member_id, visitor_invitation_count,
                    one_to_one_member_id, one_to_one_count
                )
                VALUES (
                    :week_date,
                    :external_referral_member_id, :external_referral_count,
                    :visitor_invitation_member_id, :visitor_invitation_count,
                    :one_to_one_member_id, :one_to_one_count
                )
            ');
        }

        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
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
        // 週間No.1データ削除
        $input = json_decode(file_get_contents('php://input'), true);
        $weekDate = $input['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM weekly_no1 WHERE week_date = :week_date');
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
