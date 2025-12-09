<?php
/**
 * BNI Slide System - Load Survey Data API (SQLite Version)
 * Read survey data from SQLite and return as JSON
 */

header('Content-Type: application/json; charset=utf-8');

// Load dependencies
require_once __DIR__ . '/includes/db.php';

try {
  // Get week parameter (optional)
  $week = $_GET['week'] ?? '';

  // Get database connection
  $db = getDbConnection();

  // Determine week_date
  if ($week) {
    // Load specific week
    $weekDate = $week;
  } else {
    // Load latest week
    $result = dbQueryOne($db, "SELECT week_date FROM survey_data ORDER BY week_date DESC LIMIT 1");
    $weekDate = $result ? $result['week_date'] : null;
  }

  // Check if data exists
  if (!$weekDate) {
    echo json_encode([
      'success' => true,
      'data' => [],
      'message' => 'データがまだありません'
    ]);
    dbClose($db);
    exit;
  }

  // Load survey data for the week
  $data = loadSurveyData($db, $weekDate);

  // Calculate statistics
  $stats = calculateStats($db, $weekDate);

  // Get pitch presenter info
  $pitchPresenter = getPitchPresenter($db, $weekDate);

  // Format date for title slide
  $slideDate = '';
  try {
    $dt = new DateTime($weekDate);
    $slideDate = $dt->format('Y年n月j日');
  } catch (Exception $e) {
    $slideDate = $weekDate;
  }

  dbClose($db);

  echo json_encode([
    'success' => true,
    'data' => $data,
    'stats' => $stats,
    'pitch_presenter' => $pitchPresenter,
    'count' => count($data),
    'date' => $slideDate,
    'week' => $weekDate
  ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  error_log('[API LOAD] Error: ' . $e->getMessage());
  if (isset($db)) {
    dbClose($db);
  }
  echo json_encode([
    'success' => false,
    'message' => 'データの読み込み中にエラーが発生しました'
  ]);
}

/**
 * Load survey data from database
 * Format: CSV-like structure for compatibility with existing frontend
 */
function loadSurveyData($db, $weekDate) {
  $data = [];

  // Query survey data with related visitors and referrals
  $query = "
    SELECT
      s.id,
      s.timestamp,
      s.input_date,
      s.user_name,
      s.user_email,
      s.attendance,
      s.thanks_slips,
      s.one_to_one,
      s.activities,
      s.comments,
      s.is_pitch_presenter,
      s.pitch_file_path,
      s.pitch_file_original_name,
      s.pitch_file_type,
      v.visitor_name,
      v.visitor_company,
      v.visitor_industry,
      r.referral_name,
      r.referral_amount,
      r.referral_category,
      r.referral_provider
    FROM survey_data s
    LEFT JOIN visitors v ON s.id = v.survey_data_id
    LEFT JOIN referrals r ON s.id = r.survey_data_id
    WHERE s.week_date = :week_date
    ORDER BY s.timestamp, s.id
  ";

  $rows = dbQuery($db, $query, [':week_date' => $weekDate]);

  // Convert to CSV-like format (for frontend compatibility)
  foreach ($rows as $row) {
    $data[] = [
      'タイムスタンプ' => $row['timestamp'],
      '入力日' => $row['input_date'],
      '紹介者名' => $row['user_name'],
      'メールアドレス' => $row['user_email'],
      'ビジター名' => $row['visitor_name'] ?: '',
      'ビジター会社名' => $row['visitor_company'] ?: '',
      'ビジター業種' => $row['visitor_industry'] ?: '',
      '案件名' => $row['referral_name'] ?: '',
      'リファーラル金額' => $row['referral_amount'] ?: 0,
      'カテゴリ' => $row['referral_category'] ?: '',
      'リファーラル提供者' => $row['referral_provider'] ?: '',
      '出席状況' => $row['attendance'],
      'サンクスリップ数' => $row['thanks_slips'],
      'ワンツーワン数' => $row['one_to_one'],
      'アクティビティ' => $row['activities'] ?: '',
      'コメント' => $row['comments'] ?: ''
    ];
  }

  return $data;
}

/**
 * Calculate statistics from database
 */
function calculateStats($db, $weekDate) {
  $stats = [
    'total_referral_amount' => 0,
    'total_visitors' => 0,
    'total_attendance' => 0,
    'total_thanks_slips' => 0,
    'total_one_to_one' => 0,
    'categories' => [],
    'members' => []
  ];

  // Total referral amount
  $result = dbQueryOne($db,
    "SELECT COALESCE(SUM(referral_amount), 0) as total
     FROM referrals r
     JOIN survey_data s ON r.survey_data_id = s.id
     WHERE s.week_date = :week_date",
    [':week_date' => $weekDate]
  );
  $stats['total_referral_amount'] = intval($result['total']);

  // Total visitors (excluding empty names)
  $result = dbQueryOne($db,
    "SELECT COUNT(*) as total
     FROM visitors v
     JOIN survey_data s ON v.survey_data_id = s.id
     WHERE s.week_date = :week_date
     AND v.visitor_name IS NOT NULL
     AND v.visitor_name != ''",
    [':week_date' => $weekDate]
  );
  $stats['total_visitors'] = intval($result['total']);

  // Total attendance (count distinct users with attendance = '出席')
  $result = dbQueryOne($db,
    "SELECT COUNT(DISTINCT user_email) as total
     FROM survey_data
     WHERE week_date = :week_date
     AND attendance = '出席'",
    [':week_date' => $weekDate]
  );
  $stats['total_attendance'] = intval($result['total']);

  // Total thanks slips (sum per user, avoiding duplicates)
  $result = dbQueryOne($db,
    "SELECT COALESCE(SUM(thanks_slips), 0) as total
     FROM (
       SELECT DISTINCT user_email, thanks_slips
       FROM survey_data
       WHERE week_date = :week_date
     )",
    [':week_date' => $weekDate]
  );
  $stats['total_thanks_slips'] = intval($result['total']);

  // Total one-to-one (sum per user, avoiding duplicates)
  $result = dbQueryOne($db,
    "SELECT COALESCE(SUM(one_to_one), 0) as total
     FROM (
       SELECT DISTINCT user_email, one_to_one
       FROM survey_data
       WHERE week_date = :week_date
     )",
    [':week_date' => $weekDate]
  );
  $stats['total_one_to_one'] = intval($result['total']);

  // Categories (referral amount by category)
  $categories = dbQuery($db,
    "SELECT r.referral_category as category, SUM(r.referral_amount) as amount
     FROM referrals r
     JOIN survey_data s ON r.survey_data_id = s.id
     WHERE s.week_date = :week_date
     AND r.referral_category IS NOT NULL
     AND r.referral_category != ''
     GROUP BY r.referral_category",
    [':week_date' => $weekDate]
  );

  foreach ($categories as $cat) {
    $stats['categories'][$cat['category']] = intval($cat['amount']);
  }

  // Members (visitors and referral amount by member)
  $members = dbQuery($db,
    "SELECT
       s.user_name,
       COUNT(DISTINCT CASE WHEN v.visitor_name IS NOT NULL AND v.visitor_name != '' THEN v.id END) as visitors,
       COALESCE(SUM(r.referral_amount), 0) as referral_amount
     FROM survey_data s
     LEFT JOIN visitors v ON s.id = v.survey_data_id
     LEFT JOIN referrals r ON s.id = r.survey_data_id
     WHERE s.week_date = :week_date
     GROUP BY s.user_name",
    [':week_date' => $weekDate]
  );

  foreach ($members as $member) {
    $stats['members'][$member['user_name']] = [
      'visitors' => intval($member['visitors']),
      'referral_amount' => intval($member['referral_amount'])
    ];
  }

  return $stats;
}

/**
 * Get pitch presenter info for the week
 * Returns single pitch presenter (one per week)
 */
function getPitchPresenter($db, $weekDate) {
  $query = "
    SELECT
      user_name,
      user_email,
      pitch_file_path,
      pitch_file_original_name,
      pitch_file_type
    FROM survey_data
    WHERE week_date = :week_date
      AND is_pitch_presenter = 1
    ORDER BY id ASC
    LIMIT 1
  ";

  $result = dbQueryOne($db, $query, [':week_date' => $weekDate]);

  if (!$result) {
    return null;
  }

  return [
    'name' => $result['user_name'],
    'email' => $result['user_email'],
    'file_path' => $result['pitch_file_path'],
    'file_original_name' => $result['pitch_file_original_name'],
    'file_type' => $result['pitch_file_type']
  ];
}
