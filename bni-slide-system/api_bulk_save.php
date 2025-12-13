<?php
/**
 * API: Bulk Save Survey Data
 * 管理者による全メンバー一括保存API
 */

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/date_helper.php';
require_once __DIR__ . '/includes/db.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '認証が必要です']);
    exit;
}

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => '管理者権限が必要です']);
    exit;
}

// CSRF トークン検証
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'CSRF検証に失敗しました']);
    exit;
}

// パラメータ取得
$selectedWeek = $_POST['selected_week'] ?? '';
$membersData = $_POST['members'] ?? [];

if (empty($selectedWeek)) {
    echo json_encode(['success' => false, 'message' => '対象週が選択されていません']);
    exit;
}

if (empty($membersData)) {
    echo json_encode(['success' => false, 'message' => 'メンバーデータが空です']);
    exit;
}

// データベース接続
try {
    $db = dbConnect();
} catch (Exception $e) {
    error_log('Database connection error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'データベース接続エラー']);
    exit;
}

// 各メンバーのデータを処理
$submittedAt = date('Y-m-d H:i:s');

foreach ($membersData as $memberData) {
    $memberName = $memberData['name'] ?? '';
    $memberEmail = $memberData['email'] ?? '';
    $attendance = $memberData['attendance'] ?? '出席';
    $thanksSlip = intval($memberData['thanks_slip'] ?? 0);
    $oneToOne = intval($memberData['one_to_one'] ?? 0);
    $comment = $memberData['comment'] ?? '';

    // データベースに保存
    $visitors = $memberData['visitors'] ?? [];
    try {
        $stmt = $db->prepare("
            INSERT INTO survey_data (
                submitted_at, member_name, member_email, visitor_name, visitor_company, visitor_category,
                deal_name, referral_amount, referral_category, attendance_status,
                thanks_slip_count, one_to_one_count, weekly_comment,
                is_pitch_presenter, pitch_file_path,
                week_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if (!empty($visitors)) {
            foreach ($visitors as $visitor) {
                $visitorName = $visitor['name'] ?? '';
                if (empty($visitorName)) {
                    continue;
                }

                $stmt->execute([
                    $submittedAt,
                    $memberName,
                    $memberEmail,
                    $visitorName,
                    $visitor['company'] ?? '',
                    $visitor['category'] ?? '',
                    '', // deal_name
                    0, // referral_amount
                    '', // referral_category
                    $attendance,
                    $thanksSlip,
                    $oneToOne,
                    $comment,
                    0, // is_pitch_presenter
                    '', // pitch_file_path
                    $selectedWeek
                ]);
            }
        } else {
            $stmt->execute([
                $submittedAt,
                $memberName,
                $memberEmail,
                '', // visitor_name
                '', // visitor_company
                '', // visitor_category
                '', // deal_name
                0, // referral_amount
                '', // referral_category
                $attendance,
                $thanksSlip,
                $oneToOne,
                $comment,
                0, // is_pitch_presenter
                '', // pitch_file_path
                $selectedWeek
            ]);
        }
    } catch (Exception $e) {
        error_log('Database insert error: ' . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'データベース保存エラー: ' . $e->getMessage()]);
        exit;
    }
}

// 成功レスポンス
echo json_encode([
    'success' => true,
    'message' => '一括保存が完了しました',
    'saved_members' => count($membersData)
]);
