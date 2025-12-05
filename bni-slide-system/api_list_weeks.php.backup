<?php
/**
 * BNI Slide System - List Available Weeks API
 * Return list of available CSV files (weeks)
 */

header('Content-Type: application/json; charset=utf-8');

// Load date helper functions
require_once __DIR__ . '/includes/date_helper.php';

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

    // Parse filename using common helper function
    $result = parseFilenameToDate($filename);

    if ($result['success']) {
      $weeks[] = [
        'filename' => $filename,
        'label' => $result['label'],
        'date' => $result['date'],
        'timestamp' => $result['date']->getTimestamp()
      ];
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
