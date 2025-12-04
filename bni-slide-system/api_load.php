<?php
/**
 * BNI Slide System - Load CSV Data API
 * Read CSV data and return as JSON
 */

header('Content-Type: application/json; charset=utf-8');

// Config
define('CSV_FILE', __DIR__ . '/data/responses.csv');

try {
  // Check if CSV file exists
  if (!file_exists(CSV_FILE)) {
    echo json_encode([
      'success' => true,
      'data' => [],
      'message' => 'データがまだありません'
    ]);
    exit;
  }

  // Read CSV file
  $data = [];
  $fp = fopen(CSV_FILE, 'r');

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
      $data[] = array_combine($header, $row);
    }
  }

  fclose($fp);

  // Calculate statistics
  $stats = calculateStats($data);

  echo json_encode([
    'success' => true,
    'data' => $data,
    'stats' => $stats,
    'count' => count($data)
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
      $stats['members'][$member]['visitors']++;
      $stats['members'][$member]['referral_amount'] += $amount;
    }
  }

  return $stats;
}
