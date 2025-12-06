<?php
/**
 * BNI Slide System - Update Survey Data API (SQLite Version)
 * 管理者が週のデータを一括更新
 */

header('Content-Type: application/json; charset=utf-8');

// Load dependencies
require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

// Get current user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = getCurrentUser();

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => '不正なリクエストです'
    ]);
    exit;
}

// CSRF protection
requireCSRFToken();

try {
    // Get JSON data
    $input = file_get_contents('php://input');
    $requestData = json_decode($input, true);

    if (!isset($requestData['data'])) {
        throw new Exception('データが送信されていません');
    }

    $newData = $requestData['data'];
    $weekDate = $requestData['week_date'] ?? null;

    // If week_date not specified, try to get from first data row
    if (!$weekDate && count($newData) > 0) {
        // Week date should be passed separately or determined from context
        throw new Exception('週が指定されていません');
    }

    $db = getDbConnection();
    dbBeginTransaction($db);

    // Delete existing data for this week
    $deleteQuery = "DELETE FROM survey_data WHERE week_date = :week_date";
    dbExecute($db, $deleteQuery, [':week_date' => $weekDate]);

    // Group data by survey_data (same user + input_date + attendance)
    $surveyGroups = [];

    foreach ($newData as $row) {
        $userEmail = $row['メールアドレス'] ?? '';
        $inputDate = $row['入力日'] ?? '';
        $userName = $row['紹介者名'] ?? '';
        $attendance = $row['出席状況'] ?? '';
        $thanksSlips = intval($row['サンクスリップ数'] ?? 0);
        $oneToOne = intval($row['ワンツーワン数'] ?? 0);
        $comments = $row['コメント'] ?? '';
        $timestamp = $row['タイムスタンプ'] ?? date('Y-m-d H:i:s');

        // Create group key
        $groupKey = md5($userEmail . '|' . $inputDate . '|' . $attendance . '|' . $thanksSlips . '|' . $oneToOne . '|' . $comments);

        if (!isset($surveyGroups[$groupKey])) {
            $surveyGroups[$groupKey] = [
                'timestamp' => $timestamp,
                'input_date' => $inputDate,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'attendance' => $attendance,
                'thanks_slips' => $thanksSlips,
                'one_to_one' => $oneToOne,
                'comments' => $comments,
                'visitors' => [],
                'referrals' => []
            ];
        }

        // Add visitor if exists
        $visitorName = trim($row['ビジター名'] ?? '');
        if (!empty($visitorName)) {
            $surveyGroups[$groupKey]['visitors'][] = [
                'name' => $visitorName,
                'company' => trim($row['ビジター会社名'] ?? ''),
                'industry' => trim($row['ビジター業種'] ?? '')
            ];
        }

        // Add referral if exists
        $referralName = trim($row['案件名'] ?? '');
        if (!empty($referralName) && $referralName !== '-') {
            $referralAmount = intval($row['リファーラル金額'] ?? 0);
            $surveyGroups[$groupKey]['referrals'][] = [
                'name' => $referralName,
                'amount' => $referralAmount,
                'category' => trim($row['カテゴリ'] ?? ''),
                'provider' => trim($row['リファーラル提供者'] ?? '')
            ];
        }
    }

    // Insert grouped data
    $totalRecords = 0;

    foreach ($surveyGroups as $group) {
        // Get user ID
        $userQuery = "SELECT id FROM users WHERE email = :email";
        $userResult = dbQueryOne($db, $userQuery, [':email' => $group['user_email']]);
        $userId = $userResult ? $userResult['id'] : null;

        // Insert survey_data
        $surveyQuery = "INSERT INTO survey_data (
            week_date,
            timestamp,
            input_date,
            user_id,
            user_name,
            user_email,
            attendance,
            thanks_slips,
            one_to_one,
            activities,
            comments,
            created_at
        ) VALUES (
            :week_date,
            :timestamp,
            :input_date,
            :user_id,
            :user_name,
            :user_email,
            :attendance,
            :thanks_slips,
            :one_to_one,
            :activities,
            :comments,
            :created_at
        )";

        $surveyParams = [
            ':week_date' => $weekDate,
            ':timestamp' => $group['timestamp'],
            ':input_date' => $group['input_date'],
            ':user_id' => $userId,
            ':user_name' => $group['user_name'],
            ':user_email' => $group['user_email'],
            ':attendance' => $group['attendance'],
            ':thanks_slips' => $group['thanks_slips'],
            ':one_to_one' => $group['one_to_one'],
            ':activities' => null,
            ':comments' => $group['comments'] ?: null,
            ':created_at' => date('Y-m-d H:i:s')
        ];

        $surveyId = dbExecute($db, $surveyQuery, $surveyParams);
        $totalRecords++;

        // Insert visitors
        foreach ($group['visitors'] as $visitor) {
            $visitorQuery = "INSERT INTO visitors (
                survey_data_id,
                visitor_name,
                visitor_company,
                visitor_industry,
                created_at
            ) VALUES (
                :survey_data_id,
                :visitor_name,
                :visitor_company,
                :visitor_industry,
                :created_at
            )";

            $visitorParams = [
                ':survey_data_id' => $surveyId,
                ':visitor_name' => $visitor['name'],
                ':visitor_company' => $visitor['company'] ?: null,
                ':visitor_industry' => $visitor['industry'] ?: null,
                ':created_at' => date('Y-m-d H:i:s')
            ];

            dbExecute($db, $visitorQuery, $visitorParams);
        }

        // Insert referrals
        if (empty($group['referrals'])) {
            // Insert dummy referral if none
            $group['referrals'][] = [
                'name' => '-',
                'amount' => 0,
                'category' => '',
                'provider' => ''
            ];
        }

        foreach ($group['referrals'] as $referral) {
            $referralQuery = "INSERT INTO referrals (
                survey_data_id,
                referral_name,
                referral_amount,
                referral_category,
                referral_provider,
                created_at
            ) VALUES (
                :survey_data_id,
                :referral_name,
                :referral_amount,
                :referral_category,
                :referral_provider,
                :created_at
            )";

            $referralParams = [
                ':survey_data_id' => $surveyId,
                ':referral_name' => $referral['name'],
                ':referral_amount' => $referral['amount'],
                ':referral_category' => $referral['category'] ?: null,
                ':referral_provider' => $referral['provider'] ?: null,
                ':created_at' => date('Y-m-d H:i:s')
            ];

            dbExecute($db, $referralQuery, $referralParams);
        }
    }

    dbCommit($db);

    // Write audit log
    if ($currentUser) {
        $auditQuery = "INSERT INTO audit_logs (
            action,
            target,
            user_email,
            user_name,
            data,
            ip_address,
            user_agent,
            created_at
        ) VALUES (
            :action,
            :target,
            :user_email,
            :user_name,
            :data,
            :ip_address,
            :user_agent,
            :created_at
        )";

        $auditParams = [
            ':action' => 'update',
            ':target' => 'survey_data',
            ':user_email' => $currentUser['email'] ?? 'unknown',
            ':user_name' => $currentUser['name'] ?? 'Admin',
            ':data' => json_encode([
                'action' => 'bulk_edit',
                'week_date' => $weekDate,
                'record_count' => $totalRecords
            ], JSON_UNESCAPED_UNICODE),
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            ':created_at' => date('Y-m-d H:i:s')
        ];

        dbExecute($db, $auditQuery, $auditParams);
    }

    dbClose($db);

    echo json_encode([
        'success' => true,
        'message' => 'データを保存しました',
        'row_count' => $totalRecords
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    if (isset($db)) {
        dbRollback($db);
        dbClose($db);
    }
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
