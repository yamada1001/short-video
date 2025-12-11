<?php
/**
 * BNI Slide System - Form Data Save API (SQLite Version)
 * Save form data to SQLite database and send email notification
 */

header('Content-Type: application/json; charset=utf-8');

// CORS headers (if needed)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Load dependencies
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/date_helper.php';
require_once __DIR__ . '/includes/audit_logger.php';
require_once __DIR__ . '/includes/file_upload_helper.php';

// Config
define('MAIL_TO', 'yamada@yojitu.com');
define('MAIL_FROM', 'noreply@yojitu.com');
define('MAIL_FROM_NAME', 'BNI Slide System');

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
  // Get base form data
  $baseData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'input_date' => sanitize($_POST['input_date'] ?? ''),
    'introducer_name' => sanitize($_POST['introducer_name'] ?? ''),
    'email' => sanitize($_POST['email'] ?? ''),
    'is_share_story' => intval($_POST['is_share_story'] ?? 0)
  ];

  // Get pitch presenter data (validate but don't upload yet)
  $isPitchPresenter = intval($_POST['is_pitch_presenter'] ?? 0);
  $pitchFileToUpload = null;

  // Validate pitch file if user is pitch presenter
  if ($isPitchPresenter === 1) {
    if (isset($_FILES['pitch_file']) && $_FILES['pitch_file']['error'] !== UPLOAD_ERR_NO_FILE) {
      // Validate pitch file (but don't save yet - we need user ID first)
      $validation = validatePitchFile($_FILES['pitch_file']);
      if (!$validation['success']) {
        throw new Exception($validation['message']);
      }
      $pitchFileToUpload = $_FILES['pitch_file'];
    } else {
      throw new Exception('ピッチ資料をアップロードしてください');
    }
  }

  // Get education presenter data (validate but don't upload yet)
  $isEducationPresenter = intval($_POST['is_education_presenter'] ?? 0);
  $educationFileToUpload = null;

  // Validate education file if user is education presenter
  if ($isEducationPresenter === 1) {
    if (isset($_FILES['education_file']) && $_FILES['education_file']['error'] !== UPLOAD_ERR_NO_FILE) {
      // Validate education file (but don't save yet - we need user ID first)
      $validation = validatePitchFile($_FILES['education_file']); // Use same validation as pitch
      if (!$validation['success']) {
        throw new Exception($validation['message']);
      }
      $educationFileToUpload = $_FILES['education_file'];
    } else {
      throw new Exception('エデュケーション資料をアップロードしてください');
    }
  }

  // Validate required fields
  if (empty($baseData['input_date']) || empty($baseData['introducer_name']) ||
      empty($baseData['email'])) {
    throw new Exception('必須項目が入力されていません');
  }

  // Get visitor data (multiple items, optional)
  $visitors = [];
  if (isset($_POST['visitor_name']) && is_array($_POST['visitor_name'])) {
    $count = count($_POST['visitor_name']);
    for ($i = 0; $i < $count; $i++) {
      $visitorName = sanitize($_POST['visitor_name'][$i] ?? '');
      $visitorCompany = sanitize($_POST['visitor_company'][$i] ?? '');
      $visitorIndustry = sanitize($_POST['visitor_industry'][$i] ?? '');

      // Skip empty visitors
      if (empty($visitorName)) {
        continue;
      }

      $visitors[] = [
        'name' => $visitorName,
        'company' => $visitorCompany,
        'industry' => $visitorIndustry
      ];
    }
  }

  // Check for duplicate submission in the same week
  $duplicateCheck = checkDuplicateSubmission($baseData['timestamp'], $baseData['introducer_name'], $baseData['email']);
  if ($duplicateCheck['isDuplicate']) {
    throw new Exception('今週は既に回答済みです。1週間に1回のみ回答できます。');
  }

  // Save to database
  $db = getDbConnection();
  $surveyId = saveToDatabase($db, $baseData, $visitors, $isPitchPresenter, $pitchFileToUpload, $isEducationPresenter, $educationFileToUpload);
  dbClose($db);

  if (!$surveyId) {
    throw new Exception('データベースへの保存に失敗しました');
  }

  // Write audit log to database
  writeAuditLogToDb(
    'create',
    'survey_data',
    [
      'input_date' => $baseData['input_date'],
      'introducer_name' => $baseData['introducer_name'],
      'visitor_count' => count($visitors),
      'is_share_story' => $baseData['is_share_story'],
      'is_pitch_presenter' => $isPitchPresenter,
      'is_education_presenter' => $isEducationPresenter
    ],
    $baseData['email'],
    $baseData['introducer_name']
  );

  // Send email notification
  $emailSent = sendEmailNotification($baseData, $visitors, $isPitchPresenter, $isEducationPresenter);

  // Response
  echo json_encode([
    'success' => true,
    'message' => 'アンケートを送信しました！',
    'data' => $baseData,
    'visitors' => $visitors,
    'is_pitch_presenter' => $isPitchPresenter,
    'is_education_presenter' => $isEducationPresenter,
    'email_sent' => $emailSent
  ]);

} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}

/**
 * Sanitize input
 */
function sanitize($input) {
  return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Save data to SQLite database
 */
function saveToDatabase($db, $baseData, $visitors, $isPitchPresenter = 0, $pitchFileData = null, $isEducationPresenter = 0, $educationFileData = null) {
  try {
    // Start transaction
    dbBeginTransaction($db);

    // Determine week_date from timestamp
    $weekDate = getTargetFriday($baseData['timestamp']);

    // Get user ID from email
    $user = dbQueryOne($db,
      "SELECT id, name FROM users WHERE email = :email",
      [':email' => $baseData['email']]
    );

    $userId = $user ? $user['id'] : null;
    $userName = $user ? $user['name'] : $baseData['introducer_name'];

    // Handle pitch file upload if pitchFileData is actually the uploaded file
    $actualPitchFileData = null;
    if ($isPitchPresenter === 1 && $pitchFileData !== null) {
      // If $pitchFileData is the uploaded file array (not processed data), process it now
      if (isset($pitchFileData['tmp_name'])) {
        $fileResult = savePitchFile($pitchFileData, $weekDate, $userId ?? 0);
        if (!$fileResult['success']) {
          throw new Exception($fileResult['message']);
        }
        $actualPitchFileData = [
          'path' => $fileResult['file_path'],
          'type' => $fileResult['file_type'],
          'original_name' => $fileResult['original_name']
        ];
      } else {
        // Already processed pitch file data
        $actualPitchFileData = $pitchFileData;
      }
    }

    // Handle education file upload if educationFileData is actually the uploaded file
    $actualEducationFileData = null;
    if ($isEducationPresenter === 1 && $educationFileData !== null) {
      // If $educationFileData is the uploaded file array (not processed data), process it now
      if (isset($educationFileData['tmp_name'])) {
        $fileResult = saveEducationFile($educationFileData, $weekDate, $userId ?? 0);
        if (!$fileResult['success']) {
          throw new Exception($fileResult['message']);
        }
        $actualEducationFileData = [
          'path' => $fileResult['file_path'],
          'type' => $fileResult['file_type'],
          'original_name' => $fileResult['original_name']
        ];
      } else {
        // Already processed education file data
        $actualEducationFileData = $educationFileData;
      }
    }

    // Insert survey_data
    $surveyQuery = "INSERT INTO survey_data (
      week_date,
      timestamp,
      input_date,
      user_id,
      user_name,
      user_email,
      is_share_story,
      is_pitch_presenter,
      pitch_file_path,
      pitch_file_original_name,
      pitch_file_type,
      is_education_presenter,
      education_file_path,
      education_file_original_name,
      education_file_type,
      created_at
    ) VALUES (
      :week_date,
      :timestamp,
      :input_date,
      :user_id,
      :user_name,
      :user_email,
      :is_share_story,
      :is_pitch_presenter,
      :pitch_file_path,
      :pitch_file_original_name,
      :pitch_file_type,
      :is_education_presenter,
      :education_file_path,
      :education_file_original_name,
      :education_file_type,
      :created_at
    )";

    $surveyParams = [
      ':week_date' => $weekDate,
      ':timestamp' => $baseData['timestamp'],
      ':input_date' => $baseData['input_date'],
      ':user_id' => $userId,
      ':user_name' => $userName,
      ':user_email' => $baseData['email'],
      ':is_share_story' => $baseData['is_share_story'],
      ':is_pitch_presenter' => $isPitchPresenter,
      ':pitch_file_path' => $actualPitchFileData ? $actualPitchFileData['path'] : null,
      ':pitch_file_original_name' => $actualPitchFileData ? $actualPitchFileData['original_name'] : null,
      ':pitch_file_type' => $actualPitchFileData ? $actualPitchFileData['type'] : null,
      ':is_education_presenter' => $isEducationPresenter,
      ':education_file_path' => $actualEducationFileData ? $actualEducationFileData['path'] : null,
      ':education_file_original_name' => $actualEducationFileData ? $actualEducationFileData['original_name'] : null,
      ':education_file_type' => $actualEducationFileData ? $actualEducationFileData['type'] : null,
      ':created_at' => $baseData['timestamp']
    ];

    $surveyId = dbExecute($db, $surveyQuery, $surveyParams);

    // Insert visitors
    if (count($visitors) > 0) {
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
          ':created_at' => $baseData['timestamp']
        ];

        dbExecute($db, $visitorQuery, $visitorParams);
      }
    }

    // Commit transaction
    dbCommit($db);

    return $surveyId;

  } catch (Exception $e) {
    // Rollback on error
    dbRollback($db);
    error_log("Database save failed: " . $e->getMessage());
    return false;
  }
}

// writeAuditLogToDb() はincludes/audit_logger.phpで定義されています

/**
 * Send email notification to admin
 */
function sendEmailNotification($baseData, $visitors, $isPitchPresenter, $isEducationPresenter) {
  $to = MAIL_TO;
  $subject = '[BNI] 新しいアンケート回答がありました - ' . $baseData['introducer_name'];

  // Build visitor list HTML
  $visitorListHTML = '';
  if (count($visitors) > 0) {
    foreach ($visitors as $index => $visitor) {
      $visitorListHTML .= '
        <div style="margin-bottom: 15px; padding: 10px; background-color: #f9f9f9; border-left: 3px solid #D00C24;">
          <div class="field">
            <span class="label">ビジター' . ($index + 1) . ':</span>
            <span class="value">' . htmlspecialchars($visitor['name']) . '</span>
          </div>
          <div class="field">
            <span class="label">会社名（屋号）:</span>
            <span class="value">' . htmlspecialchars($visitor['company'] ?: '-') . '</span>
          </div>
          <div class="field">
            <span class="label">業種:</span>
            <span class="value">' . htmlspecialchars($visitor['industry'] ?: '-') . '</span>
          </div>
        </div>
      ';
    }
  } else {
    $visitorListHTML = '<p style="color: #999;">ビジター紹介なし</p>';
  }

  // Build presenter info HTML
  $presenterInfoHTML = '';

  // Share Story
  $shareStoryStatus = ($baseData['is_share_story'] == 1) ? 'はい（担当）' : 'いいえ';
  $presenterInfoHTML .= '
    <div class="field">
      <span class="label">シェアストーリー担当:</span>
      <span class="value">' . htmlspecialchars($shareStoryStatus) . '</span>
    </div>
  ';

  // Pitch Presenter
  $pitchStatus = ($isPitchPresenter == 1) ? 'はい（ピッチ資料あり）' : 'いいえ';
  $presenterInfoHTML .= '
    <div class="field">
      <span class="label">33秒ピッチ担当:</span>
      <span class="value">' . htmlspecialchars($pitchStatus) . '</span>
    </div>
  ';

  // Education Presenter
  $educationStatus = ($isEducationPresenter == 1) ? 'はい（エデュケーション資料あり）' : 'いいえ';
  $presenterInfoHTML .= '
    <div class="field">
      <span class="label">エデュケーション担当:</span>
      <span class="value">' . htmlspecialchars($educationStatus) . '</span>
    </div>
  ';

  // Email body (HTML)
  $message = '<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; line-height: 1.6; color: #333; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .header { background-color: #D00C24; color: white; padding: 20px; text-align: center; }
    .content { background-color: #f5f5f5; padding: 20px; }
    .section { background-color: white; padding: 15px; margin-bottom: 15px; border-radius: 4px; }
    .section h3 { color: #D00C24; margin-top: 0; }
    .field { margin-bottom: 10px; }
    .label { font-weight: bold; color: #666; }
    .value { margin-left: 10px; }
    .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>BNI週次アンケート - 新規回答</h2>
    </div>
    <div class="content">

      <div class="section">
        <h3>基本情報</h3>
        <div class="field">
          <span class="label">入力日:</span>
          <span class="value">' . htmlspecialchars($baseData['input_date']) . '</span>
        </div>
        <div class="field">
          <span class="label">紹介者名:</span>
          <span class="value">' . htmlspecialchars($baseData['introducer_name']) . '</span>
        </div>
        <div class="field">
          <span class="label">メールアドレス:</span>
          <span class="value">' . htmlspecialchars($baseData['email']) . '</span>
        </div>
      </div>

      <div class="section">
        <h3>1. ビジター紹介情報（' . count($visitors) . '件）</h3>
        ' . $visitorListHTML . '
      </div>

      <div class="section">
        <h3>2. プレゼンター情報</h3>
        ' . $presenterInfoHTML . '
      </div>

      <div class="footer">
        <p>送信日時: ' . $baseData['timestamp'] . '</p>
        <p>BNI Slide System</p>
      </div>
    </div>
  </div>
</body>
</html>';

  // Email headers
  $headers = [
    'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . '>',
    'Reply-To: ' . MAIL_FROM,
    'Content-Type: text/html; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
  ];

  // Send admin notification
  $adminResult = mail($to, $subject, $message, implode("\r\n", $headers));

  // Send thank you email to user
  $thanksResult = sendThanksEmail($baseData);

  return $adminResult && $thanksResult;
}

/**
 * Send thank you email to user
 */
function sendThanksEmail($baseData) {
  $to = $baseData['email'];
  $subject = '[BNI] アンケートご回答ありがとうございます';

  // Email body (HTML)
  $message = '<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; line-height: 1.8; color: #333; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .header { background-color: #D00C24; color: white; padding: 30px 20px; text-align: center; }
    .content { background-color: #ffffff; padding: 30px; }
    .message { font-size: 16px; margin-bottom: 20px; }
    .footer { text-align: center; color: #999; font-size: 13px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>アンケートご回答ありがとうございます</h2>
    </div>
    <div class="content">
      <p class="message">
        ' . htmlspecialchars($baseData['introducer_name']) . ' 様
      </p>
      <p class="message">
        BNI週次アンケートにご回答いただき、誠にありがとうございました。
      </p>
      <p class="message">
        ご入力いただいた内容は、週次レポートに反映されます。<br>
        引き続き、よろしくお願いいたします。
      </p>
      <div class="footer">
        <p>このメールは自動送信されています。</p>
        <p>BNI Slide System</p>
        <p>Givers Gain®</p>
      </div>
    </div>
  </div>
</body>
</html>';

  // Email headers
  $headers = [
    'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . '>',
    'Reply-To: ' . MAIL_TO,
    'Content-Type: text/html; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
  ];

  // Send thank you email
  $result = mail($to, $subject, $message, implode("\r\n", $headers));

  return $result;
}

// getTargetFriday() はincludes/date_helper.phpで定義されています

/**
 * Check if user has already submitted for the same week
 *
 * @param string $timestamp The submission timestamp in 'Y-m-d H:i:s' format
 * @param string $introducerName The user's name
 * @param string $email The user's email
 * @return array ['isDuplicate' => bool, 'existingDate' => string|null]
 */
function checkDuplicateSubmission($timestamp, $introducerName, $email) {
  try {
    // Calculate week_date using Friday boundary logic
    $weekDate = getTargetFriday($timestamp);

    // Query database for existing submission
    $db = getDbConnection();

    $query = "SELECT input_date FROM survey_data
              WHERE week_date = :week_date
              AND user_email = :email
              LIMIT 1";

    $result = dbQueryOne($db, $query, [
      ':week_date' => $weekDate,
      ':email' => $email
    ]);

    dbClose($db);

    if ($result) {
      return [
        'isDuplicate' => true,
        'existingDate' => $result['input_date']
      ];
    }

    return ['isDuplicate' => false];

  } catch (Exception $e) {
    error_log("Duplicate check failed: " . $e->getMessage());
    return ['isDuplicate' => false]; // Allow submission on error
  }
}
