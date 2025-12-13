<?php
/**
 * BNI Slide System - List Available Weeks API (SQLite Version)
 * Return list of available weeks from database
 */

header('Content-Type: application/json; charset=utf-8');

// Load dependencies
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/date_helper.php';

try {
  $db = getDbConnection();

  // Get current week's Friday (今週の金曜日を取得)
  $currentTimestamp = date('Y-m-d H:i:s');
  $currentWeekFriday = getTargetFriday($currentTimestamp);

  // Get distinct week_dates from survey_data
  $query = "SELECT DISTINCT week_date FROM survey_data ORDER BY week_date DESC";
  $results = dbQuery($db, $query);

  $weeks = [];
  $currentWeekExists = false;

  foreach ($results as $row) {
    $weekDate = $row['week_date'];

    // Check if current week exists in database
    if ($weekDate === $currentWeekFriday) {
      $currentWeekExists = true;
    }

    // Parse date
    try {
      $date = new DateTime($weekDate);
      $dayOfWeek = $date->format('w'); // 0=Sun, 5=Fri

      // Determine day label
      $dayLabels = ['日', '月', '火', '水', '木', '金', '土'];
      $dayLabel = $dayLabels[$dayOfWeek];

      $label = $date->format('Y年n月j日') . '（' . $dayLabel . '）';

      $weeks[] = [
        'value' => $weekDate,
        'filename' => $weekDate,
        'label' => $label,
        'date' => $date->format('Y-m-d'),
        'timestamp' => $date->getTimestamp()
      ];
    } catch (Exception $e) {
      // Skip invalid dates
      continue;
    }
  }

  // If current week doesn't exist in database, add it to the top
  if (!$currentWeekExists) {
    try {
      $date = new DateTime($currentWeekFriday);
      $dayOfWeek = $date->format('w'); // 0=Sun, 5=Fri

      // Determine day label
      $dayLabels = ['日', '月', '火', '水', '木', '金', '土'];
      $dayLabel = $dayLabels[$dayOfWeek];

      $label = $date->format('Y年n月j日') . '（' . $dayLabel . '）';

      // Add to beginning of array
      array_unshift($weeks, [
        'value' => $currentWeekFriday,
        'filename' => $currentWeekFriday,
        'label' => $label,
        'date' => $date->format('Y-m-d'),
        'timestamp' => $date->getTimestamp()
      ]);
    } catch (Exception $e) {
      // Ignore if current week date is invalid
      error_log('[API LIST WEEKS] Current week date invalid: ' . $e->getMessage());
    }
  }

  dbClose($db);

  echo json_encode([
    'success' => true,
    'weeks' => $weeks,
    'count' => count($weeks),
    'current_week_friday' => $currentWeekFriday  // 今週の金曜日を追加
  ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  error_log('[API LIST WEEKS] Error: ' . $e->getMessage());
  if (isset($db)) {
    dbClose($db);
  }
  echo json_encode([
    'success' => false,
    'message' => '週一覧の取得中にエラーが発生しました'
  ]);
}
