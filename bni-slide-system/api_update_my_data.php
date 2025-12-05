<?php
/**
 * BNI Slide System - Update My Data API (SQLite Version)
 * ユーザー自身のデータ更新API
 */

header('Content-Type: application/json; charset=utf-8');

// Load dependencies
require_once __DIR__ . '/includes/user_auth.php';
require_once __DIR__ . '/includes/db.php';

// Get current user info
$currentUser = getCurrentUserInfo();

if (!$currentUser) {
    echo json_encode([
        'success' => false,
        'message' => 'ログインが必要です'
    ]);
    exit;
}

try {
    // Get POST data
    $weekDate = $_POST['csv_file'] ?? ''; // week_date
    $inputDate = $_POST['input_date'] ?? '';
    $attendance = $_POST['attendance'] ?? '';
    $thanksSlips = intval($_POST['thanks_slips'] ?? 0);
    $oneToOneCount = intval($_POST['one_to_one_count'] ?? 0);
    $comments = $_POST['comments'] ?? '';

    // Validate required fields
    if (empty($weekDate)) {
        throw new Exception('週が指定されていません');
    }

    // Get visitors data
    $visitors = [];
    if (!empty($_POST['visitor_name'])) {
        foreach ($_POST['visitor_name'] as $index => $name) {
            $name = trim($name);
            $company = trim($_POST['visitor_company'][$index] ?? '');
            $industry = trim($_POST['visitor_industry'][$index] ?? '');

            // Only add visitor if name is provided
            if (!empty($name)) {
                $visitors[] = [
                    'name' => $name,
                    'company' => $company,
                    'industry' => $industry
                ];
            }
        }
    }

    // Get referrals data
    $referrals = [];
    if (!empty($_POST['referral_name'])) {
        foreach ($_POST['referral_name'] as $index => $name) {
            $name = trim($name);
            $amount = intval($_POST['referral_amount'][$index] ?? 0);
            $provider = trim($_POST['referral_provider'][$index] ?? '');

            // Only add referral if name is provided
            if (!empty($name)) {
                $referrals[] = [
                    'name' => $name,
                    'amount' => $amount,
                    'provider' => $provider
                ];
            }
        }
    }

    // If no referrals, create a dummy one
    if (empty($referrals)) {
        $referrals[] = [
            'name' => '-',
            'amount' => 0,
            'provider' => ''
        ];
    }

    $db = getDbConnection();

    // Start transaction
    dbBeginTransaction($db);

    // Delete existing data for this user and week
    $deleteQuery = "DELETE FROM survey_data
                    WHERE week_date = :week_date
                    AND user_email = :email";
    dbExecute($db, $deleteQuery, [
        ':week_date' => $weekDate,
        ':email' => $currentUser['email']
    ]);

    // Insert new survey_data
    $timestamp = date('Y-m-d H:i:s');

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

    // Get user ID
    $user = dbQueryOne($db,
        "SELECT id FROM users WHERE email = :email",
        [':email' => $currentUser['email']]
    );
    $userId = $user ? $user['id'] : null;

    $surveyParams = [
        ':week_date' => $weekDate,
        ':timestamp' => $timestamp,
        ':input_date' => $inputDate,
        ':user_id' => $userId,
        ':user_name' => $currentUser['name'],
        ':user_email' => $currentUser['email'],
        ':attendance' => $attendance,
        ':thanks_slips' => $thanksSlips,
        ':one_to_one' => $oneToOneCount,
        ':activities' => null,
        ':comments' => $comments ?: null,
        ':created_at' => $timestamp
    ];

    $surveyId = dbExecute($db, $surveyQuery, $surveyParams);

    // Insert visitors
    foreach ($visitors as $visitor) {
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
            ':created_at' => $timestamp
        ];

        dbExecute($db, $visitorQuery, $visitorParams);
    }

    // Insert referrals
    foreach ($referrals as $referral) {
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
            ':referral_category' => null,
            ':referral_provider' => $referral['provider'] ?: null,
            ':created_at' => $timestamp
        ];

        dbExecute($db, $referralQuery, $referralParams);
    }

    // Commit transaction
    dbCommit($db);

    // Write audit log
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
        ':user_email' => $currentUser['email'],
        ':user_name' => $currentUser['name'],
        ':data' => json_encode([
            'csv_file' => $weekDate,
            'input_date' => $inputDate,
            'attendance' => $attendance,
            'visitor_count' => count($visitors),
            'referral_count' => count($referrals)
        ], JSON_UNESCAPED_UNICODE),
        ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        ':created_at' => date('Y-m-d H:i:s')
    ];

    dbExecute($db, $auditQuery, $auditParams);

    dbClose($db);

    echo json_encode([
        'success' => true,
        'message' => 'データを更新しました！'
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
