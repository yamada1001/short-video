<?php
/**
 * BNI Slide System - Update CSV Data API
 * Update CSV file with edited data
 */

header('Content-Type: application/json; charset=utf-8');

// Config
define('CSV_FILE', __DIR__ . '/data/responses.csv');
define('CSV_BACKUP', __DIR__ . '/data/responses_backup_' . date('YmdHis') . '.csv');

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode([
    'success' => false,
    'message' => '不正なリクエストです'
  ]);
  exit;
}

try {
  // Get JSON data
  $input = file_get_contents('php://input');
  $requestData = json_decode($input, true);

  if (!isset($requestData['data'])) {
    throw new Exception('データが送信されていません');
  }

  $newData = $requestData['data'];

  // Backup existing CSV file
  if (file_exists(CSV_FILE)) {
    copy(CSV_FILE, CSV_BACKUP);
  }

  // Write new CSV file
  $fp = fopen(CSV_FILE, 'w');
  if (!$fp) {
    throw new Exception('CSVファイルを開けませんでした');
  }

  // Write BOM for UTF-8
  fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

  // Write header
  if (count($newData) > 0) {
    $headers = array_keys($newData[0]);
    fputcsv($fp, $headers);

    // Write data rows
    foreach ($newData as $row) {
      fputcsv($fp, array_values($row));
    }
  } else {
    // If no data, write empty file with header
    fputcsv($fp, [
      'タイムスタンプ',
      '入力日',
      '紹介者名',
      'メールアドレス',
      'ビジター名',
      'ビジター会社名',
      'ビジター業種',
      '案件名',
      'リファーラル金額',
      'カテゴリ',
      'リファーラル提供者',
      '出席状況',
      'サンクスリップ数',
      'ワンツーワン数',
      'アクティビティ',
      'コメント'
    ]);
  }

  fclose($fp);

  // Set file permissions
  chmod(CSV_FILE, 0666);

  echo json_encode([
    'success' => true,
    'message' => 'データを保存しました',
    'backup_file' => basename(CSV_BACKUP),
    'row_count' => count($newData)
  ]);

} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}
