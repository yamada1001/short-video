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

  foreach ($csvFiles as $file) {
    $filename = basename($file, '.csv');

    // Skip backup files
    if (strpos($filename, 'backup') !== false) {
      continue;
    }

    // Convert filename to label
    // e.g., "2024-12-1.csv" -> "2024年12月1週目"
    $parts = explode('-', $filename);
    if (count($parts) === 3) {
      $label = $parts[0] . '年' . $parts[1] . '月' . $parts[2] . '週目';
      $weeks[] = [
        'filename' => $filename,
        'label' => $label,
        'modified' => filemtime($file)
      ];
    }
  }

  // Sort by modified time (newest first)
  usort($weeks, function($a, $b) {
    return $b['modified'] - $a['modified'];
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
