<?php
/**
 * BNI Slide System - Load CSV Data API
 * Read CSV data and return as JSON
 */

header('Content-Type: application/json; charset=utf-8');

// Load date helper functions
require_once __DIR__ . '/includes/date_helper.php';

try {
  // Get week parameter (optional)
  $week = $_GET['week'] ?? '';

  // Determine CSV file path
  if ($week) {
    // Load specific week
    $csvFile = __DIR__ . '/data/' . $week . '.csv';
  } else {
    // Load current week
    $csvFiles = glob(__DIR__ . '/data/*.csv');
    // Filter out backup files
    $csvFiles = array_filter($csvFiles, function($file) {
      return strpos(basename($file), 'backup') === false;
    });
    // Sort by modified time (newest first)
    usort($csvFiles, function($a, $b) {
      return filemtime($b) - filemtime($a);
    });
    $csvFile = $csvFiles[0] ?? null;
  }

  // Check if CSV file exists
  if (!$csvFile || !file_exists($csvFile)) {
    echo json_encode([
      'success' => true,
      'data' => [],
      'message' => 'データがまだありません'
    ]);
    exit;
  }

  // Read CSV file
  $data = [];
  $fp = fopen($csvFile, 'r');

  if (!$fp) {
    throw new Exception('CSVファイルの読み込みに失敗しました');
  }

  // Skip BOM if exists
  $bom = fread($fp, 3);
  if ($bom !== chr(0xEF).chr(0xBB).chr(0xBF)) {
    rewind($fp);
  }

  // Read header
  $header = fgetcsv($fp);

  // Read data rows
  while (($row = fgetcsv($fp)) !== false) {
    if (count($row) === count($header)) {
      $rowData = array_combine($header, $row);
      if ($rowData !== false) {
        $data[] = $rowData;
      }
    }
  }

  fclose($fp);

  // Calculate statistics
  $stats = calculateStats($data);

  // Extract date from filename for title slide
  $slideDate = '';
  $weekFilename = '';
  if ($csvFile) {
    $weekFilename = basename($csvFile, '.csv');
    $result = parseFilenameToDate($weekFilename);

    if ($result['success']) {
      $slideDate = $result['date']->format('Y年n月j日');
    }
  }

  echo json_encode([
    'success' => true,
    'data' => $data,
    'stats' => $stats,
    'count' => count($data),
    'date' => $slideDate,
    'week' => $weekFilename
  ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}

/**
 * Calculate statistics from data
 */
function calculateStats($data) {
  $stats = [
    'total_referral_amount' => 0,
    'total_visitors' => 0,
    'total_attendance' => 0,
    'total_thanks_slips' => 0,
    'total_one_to_one' => 0,
    'categories' => [],
    'members' => []
  ];

  foreach ($data as $row) {
    // Total referral amount
    $amount = intval($row['リファーラル金額'] ?? 0);
    $stats['total_referral_amount'] += $amount;

    // Total visitors
    if (!empty($row['ビジター名'])) {
      $stats['total_visitors']++;
    }

    // Attendance
    if (($row['出席状況'] ?? '') === '出席') {
      $stats['total_attendance']++;
    }

    // Thanks slips
    $stats['total_thanks_slips'] += intval($row['サンクスリップ数'] ?? 0);

    // One-to-one
    $stats['total_one_to_one'] += intval($row['ワンツーワン数'] ?? 0);

    // Categories
    $category = $row['カテゴリ'] ?? '';
    if ($category) {
      if (!isset($stats['categories'][$category])) {
        $stats['categories'][$category] = 0;
      }
      $stats['categories'][$category] += $amount;
    }

    // Members
    $member = $row['紹介者名'] ?? '';
    if ($member) {
      if (!isset($stats['members'][$member])) {
        $stats['members'][$member] = [
          'visitors' => 0,
          'referral_amount' => 0
        ];
      }
      // Only count visitor if visitor name is not empty
      if (!empty($row['ビジター名'])) {
        $stats['members'][$member]['visitors']++;
      }
      $stats['members'][$member]['referral_amount'] += $amount;
    }
  }

  return $stats;
}
