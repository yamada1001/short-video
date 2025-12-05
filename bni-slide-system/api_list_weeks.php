<?php
/**
 * BNI Slide System - List Available Weeks API
 * Return list of available CSV files (weeks)
 */

header('Content-Type: application/json; charset=utf-8');

try {
  $dataDir = __DIR__ . '/data';
  $csvFiles = glob($dataDir . '/*.csv');

  $weeks = [];
  $today = new DateTime();

  foreach ($csvFiles as $file) {
    $filename = basename($file, '.csv');

    // Skip backup files
    if (strpos($filename, 'backup') !== false) {
      continue;
    }

    // Parse filename: YYYY-MM-DD (Thursday date) or legacy YYYY-MM-W
    $parts = explode('-', $filename);
    if (count($parts) === 3) {
      // Check if it's new format (YYYY-MM-DD) or old format (YYYY-MM-W)
      if (strlen($parts[2]) === 2 && intval($parts[2]) <= 12) {
        // Old format: YYYY-MM-W (week number in month)
        $year = intval($parts[0]);
        $month = intval($parts[1]);
        $weekInMonth = intval($parts[2]);

        // Calculate the Friday date for this week
        $fridayDate = calculateFridayDate($year, $month, $weekInMonth);

        $label = $parts[0] . '年' . $parts[1] . '月' . $parts[2] . '週目 (' . $fridayDate->format('n/j') . ')';
        $weeks[] = [
          'filename' => $filename,
          'label' => $label,
          'date' => $fridayDate,
          'timestamp' => $fridayDate->getTimestamp()
        ];
      } else {
        // New format: YYYY-MM-DD (Thursday date)
        $thursdayDate = new DateTime($filename);
        $label = $thursdayDate->format('Y年n月j日') . '（木）';
        $weeks[] = [
          'filename' => $filename,
          'label' => $label,
          'date' => $thursdayDate,
          'timestamp' => $thursdayDate->getTimestamp()
        ];
      }
    }
  }

  // Sort by date (newest first, but only past/today dates)
  usort($weeks, function($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
  });

  echo json_encode([
    'success' => true,
    'weeks' => $weeks,
    'count' => count($weeks)
  ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}

/**
 * Calculate Friday date for given year, month, and week number
 */
function calculateFridayDate($year, $month, $weekInMonth) {
  $firstDay = new DateTime("$year-$month-01");
  $firstDayOfWeek = intval($firstDay->format('w')); // 0 (Sunday) to 6 (Saturday)

  // Calculate days to first Friday (5 = Friday)
  $daysToFirstFriday = (5 - $firstDayOfWeek + 7) % 7;
  if ($daysToFirstFriday === 0 && $firstDayOfWeek !== 5) {
    $daysToFirstFriday = 7;
  }

  // Calculate target day: first Friday + (weekInMonth - 1) * 7 days
  $targetDay = 1 + $daysToFirstFriday + (($weekInMonth - 1) * 7);
  $targetDate = new DateTime("$year-$month-$targetDay");

  return $targetDate;
}
