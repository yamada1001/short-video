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
        // 最新のNo.1データ取得（カテゴリ別に取得）
        $stmt = $db->query("
            SELECT
                wn.*,
                m.name as member_name,
                m.company_name
            FROM weekly_no1 wn
            LEFT JOIN members m ON wn.member_id = m.id
            ORDER BY wn.created_at DESC
        ");

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // カテゴリ別に整理
        $data = [
            'external_referral_member_id' => null,
            'external_referral_member_name' => null,
            'external_referral_company_name' => null,
            'external_referral_count' => 0,
            'visitor_invitation_member_id' => null,
            'visitor_invitation_member_name' => null,
            'visitor_invitation_company_name' => null,
            'visitor_invitation_count' => 0,
            'one_to_one_member_id' => null,
            'one_to_one_member_name' => null,
            'one_to_one_company_name' => null,
            'one_to_one_count' => 0
        ];

        foreach ($rows as $row) {
            if ($row['category'] === 'external_referral') {
                $data['external_referral_member_id'] = $row['member_id'];
                $data['external_referral_member_name'] = $row['member_name'];
                $data['external_referral_company_name'] = $row['company_name'];
                $data['external_referral_count'] = $row['count'];
            } elseif ($row['category'] === 'visitor_invitation') {
                $data['visitor_invitation_member_id'] = $row['member_id'];
                $data['visitor_invitation_member_name'] = $row['member_name'];
                $data['visitor_invitation_company_name'] = $row['company_name'];
                $data['visitor_invitation_count'] = $row['count'];
            } elseif ($row['category'] === 'one_to_one') {
                $data['one_to_one_member_id'] = $row['member_id'];
                $data['one_to_one_member_name'] = $row['member_name'];
                $data['one_to_one_company_name'] = $row['company_name'];
                $data['one_to_one_count'] = $row['count'];
            }
        }

        echo json_encode(['success' => true, 'data' => $data]);
        break;

    case 'save':
        // 週間No.1データ保存（全削除→新規挿入、カテゴリ別）
        $externalReferralMemberId = $_POST['external_referral_member_id'] ?? null;
        $externalReferralCount = $_POST['external_referral_count'] ?? 0;
        $visitorInvitationMemberId = $_POST['visitor_invitation_member_id'] ?? null;
        $visitorInvitationCount = $_POST['visitor_invitation_count'] ?? 0;
        $oneToOneMemberId = $_POST['one_to_one_member_id'] ?? null;
        $oneToOneCount = $_POST['one_to_one_count'] ?? 0;

        // 既存データを全削除
        $db->exec('DELETE FROM weekly_no1');

        // 新規データを挿入（カテゴリ別に3レコード）
        $stmt = $db->prepare('
            INSERT INTO weekly_no1 (week_date, category, member_id, count)
            VALUES (:week_date, :category, :member_id, :count)
        ');

        $weekDate = date('Y-m-d'); // 現在の日付を使用

        // External Referral
        if ($externalReferralMemberId) {
            $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
            $stmt->bindValue(':category', 'external_referral', PDO::PARAM_STR);
            $stmt->bindValue(':member_id', $externalReferralMemberId, PDO::PARAM_INT);
            $stmt->bindValue(':count', $externalReferralCount, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Visitor Invitation
        if ($visitorInvitationMemberId) {
            $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
            $stmt->bindValue(':category', 'visitor_invitation', PDO::PARAM_STR);
            $stmt->bindValue(':member_id', $visitorInvitationMemberId, PDO::PARAM_INT);
            $stmt->bindValue(':count', $visitorInvitationCount, PDO::PARAM_INT);
            $stmt->execute();
        }

        // One-to-One
        if ($oneToOneMemberId) {
            $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
            $stmt->bindValue(':category', 'one_to_one', PDO::PARAM_STR);
            $stmt->bindValue(':member_id', $oneToOneMemberId, PDO::PARAM_INT);
            $stmt->bindValue(':count', $oneToOneCount, PDO::PARAM_INT);
            $stmt->execute();
        }

        echo json_encode(['success' => true]);
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
