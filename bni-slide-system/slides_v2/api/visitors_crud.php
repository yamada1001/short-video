<?php
/**
 * BNI Slide System V2 - Visitors CRUD API
 * ビジター管理API（作成・読み取り・更新・削除）
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
        // ビジター一覧取得（全期間）
        $query = "
            SELECT
                v.*,
                m.name as attend_member_name
            FROM visitors v
            LEFT JOIN members m ON v.attend_member_id = m.id
            ORDER BY v.week_date DESC, v.visitor_no ASC
        ";
        $stmt = $db->query($query);

        $visitors = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $visitors[] = $row;
        }

        echo json_encode(['success' => true, 'visitors' => $visitors]);
        break;

    case 'get_by_date':
        // 特定日付のビジター一覧取得
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                v.*,
                m.name as attend_member_name
            FROM visitors v
            LEFT JOIN members m ON v.attend_member_id = m.id
            WHERE v.week_date = :week_date
            ORDER BY v.visitor_no ASC
        ");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $visitors = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $visitors[] = $row;
        }

        echo json_encode(['success' => true, 'visitors' => $visitors]);
        break;

    case 'get':
        // 特定のビジター情報取得
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDが指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT
                v.*,
                m.name as attend_member_name
            FROM visitors v
            LEFT JOIN members m ON v.attend_member_id = m.id
            WHERE v.id = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $visitor = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($visitor) {
            echo json_encode(['success' => true, 'visitor' => $visitor]);
        } else {
            echo json_encode(['success' => false, 'error' => '該当データが見つかりません']);
        }
        break;

    case 'create':
        // 新規ビジター追加
        $weekDate = $_POST['week_date'] ?? null;
        $visitorNo = $_POST['visitor_no'] ?? null;
        $name = $_POST['name'] ?? null;
        $companyName = $_POST['company_name'] ?? '';
        $specialty = $_POST['specialty'] ?? '';
        $sponsor = $_POST['sponsor'] ?? '';
        $attendMemberId = $_POST['attend_member_id'] ?? null;
        $jobDescription = $_POST['job_description'] ?? '';
        $referralRequest = $_POST['referral_request'] ?? '';

        if (!$weekDate || !$visitorNo || !$name) {
            echo json_encode(['success' => false, 'error' => '開催日、No、お名前は必須です']);
            exit;
        }

        $stmt = $db->prepare('
            INSERT INTO visitors (
                week_date, visitor_no, name, company_name, specialty,
                sponsor, attend_member_id, job_description, referral_request
            )
            VALUES (
                :week_date, :visitor_no, :name, :company_name, :specialty,
                :sponsor, :attend_member_id, :job_description, :referral_request
            )
        ');

        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':visitor_no', $visitorNo, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
        $stmt->bindValue(':specialty', $specialty, PDO::PARAM_STR);
        $stmt->bindValue(':sponsor', $sponsor, PDO::PARAM_STR);
        $stmt->bindValue(':attend_member_id', $attendMemberId ?: null, PDO::PARAM_INT);
        $stmt->bindValue(':job_description', $jobDescription, PDO::PARAM_STR);
        $stmt->bindValue(':referral_request', $referralRequest, PDO::PARAM_STR);

        $stmt->execute();

        if ($result) {
            echo json_encode([
                'success' => true,
                'id' => $db->lastInsertId()
            ]);
        
        break;

    case 'update':
        // ビジター情報更新
        $id = $_POST['id'] ?? null;
        $visitorNo = $_POST['visitor_no'] ?? null;
        $name = $_POST['name'] ?? null;
        $companyName = $_POST['company_name'] ?? '';
        $specialty = $_POST['specialty'] ?? '';
        $sponsor = $_POST['sponsor'] ?? '';
        $attendMemberId = $_POST['attend_member_id'] ?? null;
        $jobDescription = $_POST['job_description'] ?? '';
        $referralRequest = $_POST['referral_request'] ?? '';

        if (!$id || !$visitorNo || !$name) {
            echo json_encode(['success' => false, 'error' => 'ID、No、お名前は必須です']);
            exit;
        }

        $stmt = $db->prepare('
            UPDATE visitors
            SET visitor_no = :visitor_no,
                name = :name,
                company_name = :company_name,
                specialty = :specialty,
                sponsor = :sponsor,
                attend_member_id = :attend_member_id,
                job_description = :job_description,
                referral_request = :referral_request,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id
        ');

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':visitor_no', $visitorNo, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
        $stmt->bindValue(':specialty', $specialty, PDO::PARAM_STR);
        $stmt->bindValue(':sponsor', $sponsor, PDO::PARAM_STR);
        $stmt->bindValue(':attend_member_id', $attendMemberId ?: null, PDO::PARAM_INT);
        $stmt->bindValue(':job_description', $jobDescription, PDO::PARAM_STR);
        $stmt->bindValue(':referral_request', $referralRequest, PDO::PARAM_STR);

        $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        
        break;

    case 'delete':
        // ビジター削除
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? null;

        if (!$id) {
            echo json_encode(['success' => false, 'error' => 'IDは必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM visitors WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        
        break;

    case 'delete_by_date':
        // 特定日付のビジター全削除
        $input = json_decode(file_get_contents('php://input'), true);
        $weekDate = $input['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付は必須です']);
            exit;
        }

        $stmt = $db->prepare('DELETE FROM visitors WHERE week_date = :week_date');
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        if ($result) {
            echo json_encode(['success' => true]);
        
        break;

    case 'get_next_visitor_no':
        // 次のビジターNo取得（自動ナンバリング用）
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が指定されていません']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT COALESCE(MAX(visitor_no), 0) + 1 as next_no
            FROM visitors
            WHERE week_date = :week_date
        ");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'next_no' => $row['next_no']]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => '不明なアクション']);
        break;
}
