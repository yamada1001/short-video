<?php
/**
 * BNI Slide System - Update My Data API (SQLite Version)
 * ユーザー自身のデータ更新API
 */

header('Content-Type: application/json; charset=utf-8');

// Load dependencies
require_once __DIR__ . '/includes/user_auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

// CSRF protection
requireCSRFToken();

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
    $weekDate = $_POST['week_date'] ?? '';
    $inputDate = $_POST['input_date'] ?? '';
    $attendance = $_POST['attendance'] ?? '';
    $thanksSlips = intval($_POST['thanks_slips'] ?? 0);
    $oneToOneCount = intval($_POST['one_to_one_count'] ?? 0);
    $comments = $_POST['comments'] ?? '';

    // Pitch presenter data
    $isPitchPresenter = isset($_POST['is_pitch_presenter']) ? 1 : 0;
    $youtubeUrl = trim($_POST['youtube_url'] ?? '');

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

    $db = getDbConnection();

    // Start transaction
    dbBeginTransaction($db);

    // Get existing survey data to preserve pitch file info
    $existingData = dbQueryOne($db,
        "SELECT pitch_file_path, pitch_file_original_name, pitch_file_type
         FROM survey_data
         WHERE week_date = :week_date AND user_email = :email",
        [':week_date' => $weekDate, ':email' => $currentUser['email']]
    );

    // Handle file upload
    $pitchFilePath = $existingData['pitch_file_path'] ?? null;
    $pitchFileOriginalName = $existingData['pitch_file_original_name'] ?? null;
    $pitchFileType = $existingData['pitch_file_type'] ?? null;

    if ($isPitchPresenter && isset($_FILES['pitch_file']) && $_FILES['pitch_file']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['pitch_file'];
        $allowedTypes = ['application/pdf'];
        $maxFileSize = 30 * 1024 * 1024; // 30MB

        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('PDFファイルのみアップロード可能です');
        }

        if ($file['size'] > $maxFileSize) {
            throw new Exception('ファイルサイズは30MB以下にしてください');
        }

        // Delete old file if exists
        if ($pitchFilePath && file_exists($pitchFilePath)) {
            unlink($pitchFilePath);
        }

        // Generate unique filename
        $uploadDir = __DIR__ . '/uploads/pitch/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $uniqueFileName = uniqid('pitch_') . '_' . time() . '.' . $fileExtension;
        $uploadPath = $uploadDir . $uniqueFileName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new Exception('ファイルのアップロードに失敗しました');
        }

        $pitchFilePath = $uploadPath;
        $pitchFileOriginalName = $file['name'];
        $pitchFileType = $file['type'];
    }

    // If not pitch presenter anymore, clear file info
    if (!$isPitchPresenter) {
        if ($pitchFilePath && file_exists($pitchFilePath)) {
            unlink($pitchFilePath);
        }
        $pitchFilePath = null;
        $pitchFileOriginalName = null;
        $pitchFileType = null;
    }

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
        is_pitch_presenter,
        pitch_file_path,
        pitch_file_original_name,
        pitch_file_type,
        youtube_url,
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
        :is_pitch_presenter,
        :pitch_file_path,
        :pitch_file_original_name,
        :pitch_file_type,
        :youtube_url,
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
        ':is_pitch_presenter' => $isPitchPresenter,
        ':pitch_file_path' => $pitchFilePath,
        ':pitch_file_original_name' => $pitchFileOriginalName,
        ':pitch_file_type' => $pitchFileType,
        ':youtube_url' => $youtubeUrl ?: null,
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
            'week_date' => $weekDate,
            'input_date' => $inputDate,
            'attendance' => $attendance,
            'visitor_count' => count($visitors),
            'is_pitch_presenter' => $isPitchPresenter,
            'has_pitch_file' => !empty($pitchFilePath)
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
    error_log('[API UPDATE MY DATA] Error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'データの更新中にエラーが発生しました'
    ], JSON_UNESCAPED_UNICODE);
}
