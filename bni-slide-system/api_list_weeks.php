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

    // Parse filename: YYYY-MM-DD (Friday date) or legacy YYYY-MM-W
    $parts = explode('-', $filename);
    if (count($parts) === 3) {
      // Try to parse as date first (YYYY-MM-DD format)
      try {
        $targetDate = new DateTime($filename);

        // If successful, it's new format (YYYY-MM-DD)
        $dayOfWeek = $targetDate->format('w'); // 0=Sun, 5=Fri
        $dayNames = ['日', '月', '火', '水', '木', '金', '土'];
        $dayName = $dayNames[$dayOfWeek];

        $label = $targetDate->format('Y年n月j日') . '（' . $dayName . '）';
        $weeks[] = [
          'filename' => $filename,
          'label' => $label,
          'date' => $targetDate,
          'timestamp' => $targetDate->getTimestamp()
        ];
      } catch (Exception $dateEx) {
        // If parsing fails, try old format: YYYY-MM-W
        if (strlen($parts[2]) === 1 || (strlen($parts[2]) === 2 && intval($parts[2]) <= 5)) {
          // Old format: YYYY-MM-W (week number in month, usually 1-5)
          $year = intval($parts[0]);
          $month = intval($parts[1]);
          $weekInMonth = intval($parts[2]);

          // Calculate the Friday date for this week
          $fridayDate = calculateFridayDate($year, $month, $weekInMonth);

          $label = $parts[0] . '年' . $parts[1] . '月第' . $parts[2] . '週';
          $weeks[] = [
            'filename' => $filename,
            'label' => $label,
            'date' => $fridayDate,
            'timestamp' => $fridayDate->getTimestamp()
          ];
        }
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
  // Find the Nth Friday of the month
  $date = new DateTime("$year-$month-01");

  // Find first Friday of the month
  $dayOfWeek = intval($date->format('w')); // 0=Sunday, 5=Friday

  if ($dayOfWeek <= 5) {
    // If month starts on or before Friday, go to first Friday
    $daysToFriday = 5 - $dayOfWeek;
  } else {
    // If month starts on Saturday/Sunday, go to next Friday
    $daysToFriday = (5 - $dayOfWeek + 7) % 7;
  }

  if ($daysToFriday > 0) {
    $date->modify("+$daysToFriday days");
  }

  // Now add (weekInMonth - 1) weeks
  if ($weekInMonth > 1) {
    $date->modify("+" . ($weekInMonth - 1) . " weeks");
  }

  return $date;
}
