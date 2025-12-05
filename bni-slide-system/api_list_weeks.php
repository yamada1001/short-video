<?php
/**
 * BNI Slide System - List Available Weeks API (SQLite Version)
 * Return list of available weeks from database
 */

header('Content-Type: application/json; charset=utf-8');

// Load dependencies
require_once __DIR__ . '/includes/db.php';

try {
  $db = getDbConnection();

  // Get distinct week_dates from survey_data
  $query = "SELECT DISTINCT week_date FROM survey_data ORDER BY week_date DESC";
  $results = dbQuery($db, $query);

  $weeks = [];

  foreach ($results as $row) {
    $weekDate = $row['week_date'];

    // Parse date
    try {
      $date = new DateTime($weekDate);
      $dayOfWeek = $date->format('w'); // 0=Sun, 5=Fri

      // Determine day label
      $dayLabels = ['日', '月', '火', '水', '木', '金', '土'];
      $dayLabel = $dayLabels[$dayOfWeek];

      $label = $date->format('Y年n月j日') . '（' . $dayLabel . '）';

      $weeks[] = [
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

  dbClose($db);

  echo json_encode([
    'success' => true,
    'weeks' => $weeks,
    'count' => count($weeks)
  ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  if (isset($db)) {
    dbClose($db);
  }
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}
