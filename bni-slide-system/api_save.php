<?php
/**
 * BNI Slide System - Form Data Save API
 * Save form data to CSV and send email notification
 */

header('Content-Type: application/json; charset=utf-8');

// CORS headers (if needed)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Config
define('MAIL_TO', 'yamada@yojitu.com');
define('MAIL_FROM', 'noreply@yojitu.com');
define('MAIL_FROM_NAME', 'BNI Slide System');

/**
 * Get current week label (e.g., "2024年12月1週目")
 */
function getCurrentWeekLabel() {
  $now = new DateTime();
  $year = $now->format('Y');
  $month = $now->format('n'); // 1-12

  // Get first day of month
  $firstDay = new DateTime($year . '-' . $month . '-01');
  $currentDay = $now->format('j');

  // Calculate week number in month
  $weekInMonth = ceil($currentDay / 7);

  return $year . '年' . $month . '月' . $weekInMonth . '週目';
}

/**
 * Get CSV file path for current week
 */
function getCSVFilePath() {
  $weekLabel = getCurrentWeekLabel();
  // e.g., "2024年12月1週目" -> "2024-12-1.csv"
  $filename = preg_replace('/年|月|週目/', '-', $weekLabel);
  $filename = str_replace('--', '-', $filename) . '.csv';
  return __DIR__ . '/data/' . $filename;
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode([
    'success' => false,
    'message' => '不正なリクエストです'
  ]);
  exit;
}

try {
  // Get form data
  $data = [
    'timestamp' => date('Y-m-d H:i:s'),
    'introducer_name' => sanitize($_POST['introducer_name'] ?? ''),
    'visitor_name' => sanitize($_POST['visitor_name'] ?? ''),
    'introduction_date' => sanitize($_POST['introduction_date'] ?? ''),
    'visitor_industry' => sanitize($_POST['visitor_industry'] ?? ''),
    'referral_name' => sanitize($_POST['referral_name'] ?? ''),
    'referral_amount' => intval($_POST['referral_amount'] ?? 0),
    'referral_category' => sanitize($_POST['referral_category'] ?? ''),
    'referral_provider' => sanitize($_POST['referral_provider'] ?? ''),
    'attendance' => sanitize($_POST['attendance'] ?? ''),
    'thanks_slips' => intval($_POST['thanks_slips'] ?? 0),
    'one_to_one_count' => intval($_POST['one_to_one_count'] ?? 0),
    'activities' => isset($_POST['activities']) ? implode('|', array_map('sanitize', $_POST['activities'])) : '',
    'comments' => sanitize($_POST['comments'] ?? '')
  ];

  // Validate required fields (visitor info is now optional)
  if (empty($data['introducer_name']) || empty($data['referral_name']) ||
      empty($data['referral_category']) || empty($data['attendance'])) {
    throw new Exception('必須項目が入力されていません');
  }

  // Save to CSV
  $csvSaved = saveToCSV($data);
  if (!$csvSaved) {
    throw new Exception('CSVファイルへの保存に失敗しました');
  }

  // Send email notification
  $emailSent = sendEmailNotification($data);

  // Response
  echo json_encode([
    'success' => true,
    'message' => 'アンケートを送信しました！',
    'data' => $data,
    'email_sent' => $emailSent
  ]);

} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ]);
}

/**
 * Sanitize input
 */
function sanitize($input) {
  return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Save data to CSV
 */
function saveToCSV($data) {
  $csvFile = getCSVFilePath();
  $isNewFile = !file_exists($csvFile);

  // Create data directory if not exists
  $dataDir = dirname($csvFile);
  if (!is_dir($dataDir)) {
    mkdir($dataDir, 0707, true);
  }

  // Open CSV file
  $fp = fopen($csvFile, 'a');
  if (!$fp) {
    return false;
  }

  // Set CSV encoding to UTF-8 with BOM (for Excel compatibility)
  if ($isNewFile) {
    fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

    // Write header
    fputcsv($fp, [
      'タイムスタンプ',
      '紹介者名',
      'ビジター名',
      '紹介日',
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

  // Write data
  $result = fputcsv($fp, [
    $data['timestamp'],
    $data['introducer_name'],
    $data['visitor_name'],
    $data['introduction_date'],
    $data['visitor_industry'],
    $data['referral_name'],
    $data['referral_amount'],
    $data['referral_category'],
    $data['referral_provider'],
    $data['attendance'],
    $data['thanks_slips'],
    $data['one_to_one_count'],
    $data['activities'],
    $data['comments']
  ]);

  fclose($fp);

  // Set file permissions
  chmod($csvFile, 0666);

  return $result !== false;
}

/**
 * Send email notification
 */
function sendEmailNotification($data) {
  $to = MAIL_TO;
  $subject = '[BNI] 新しいアンケート回答がありました - ' . $data['introducer_name'];

  // Email body (HTML)
  $message = '<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; line-height: 1.6; color: #333; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .header { background-color: #CF2030; color: white; padding: 20px; text-align: center; }
    .content { background-color: #f5f5f5; padding: 20px; }
    .section { background-color: white; padding: 15px; margin-bottom: 15px; border-radius: 4px; }
    .section h3 { color: #CF2030; margin-top: 0; }
    .field { margin-bottom: 10px; }
    .label { font-weight: bold; color: #666; }
    .value { margin-left: 10px; }
    .footer { text-align: center; color: #999; font-size: 12px; margin-top: 20px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>BNI週次アンケート - 新規回答</h2>
    </div>
    <div class="content">

      <div class="section">
        <h3>1. ビジター紹介情報</h3>
        <div class="field">
          <span class="label">紹介者名:</span>
          <span class="value">' . htmlspecialchars($data['introducer_name']) . '</span>
        </div>
        <div class="field">
          <span class="label">ビジター名:</span>
          <span class="value">' . htmlspecialchars($data['visitor_name']) . '</span>
        </div>
        <div class="field">
          <span class="label">紹介日:</span>
          <span class="value">' . htmlspecialchars($data['introduction_date']) . '</span>
        </div>
        <div class="field">
          <span class="label">ビジター業種:</span>
          <span class="value">' . htmlspecialchars($data['visitor_industry']) . '</span>
        </div>
      </div>

      <div class="section">
        <h3>2. リファーラル金額情報</h3>
        <div class="field">
          <span class="label">案件名:</span>
          <span class="value">' . htmlspecialchars($data['referral_name']) . '</span>
        </div>
        <div class="field">
          <span class="label">金額:</span>
          <span class="value">¥' . number_format($data['referral_amount']) . '</span>
        </div>
        <div class="field">
          <span class="label">カテゴリ:</span>
          <span class="value">' . htmlspecialchars($data['referral_category']) . '</span>
        </div>
        <div class="field">
          <span class="label">提供者:</span>
          <span class="value">' . htmlspecialchars($data['referral_provider']) . '</span>
        </div>
      </div>

      <div class="section">
        <h3>3. メンバー情報</h3>
        <div class="field">
          <span class="label">出席状況:</span>
          <span class="value">' . htmlspecialchars($data['attendance']) . '</span>
        </div>
        <div class="field">
          <span class="label">サンクスリップ:</span>
          <span class="value">' . $data['thanks_slips'] . '件</span>
        </div>
        <div class="field">
          <span class="label">ワンツーワン:</span>
          <span class="value">' . $data['one_to_one_count'] . '回</span>
        </div>
        <div class="field">
          <span class="label">アクティビティ:</span>
          <span class="value">' . htmlspecialchars(str_replace('|', ', ', $data['activities'])) . '</span>
        </div>
        <div class="field">
          <span class="label">コメント:</span>
          <span class="value">' . nl2br(htmlspecialchars($data['comments'])) . '</span>
        </div>
      </div>

      <div class="footer">
        <p>送信日時: ' . $data['timestamp'] . '</p>
        <p>BNI Slide System</p>
      </div>
    </div>
  </div>
</body>
</html>';

  // Email headers
  $headers = [
    'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . '>',
    'Reply-To: ' . MAIL_FROM,
    'Content-Type: text/html; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
  ];

  // Send email
  $result = mail($to, $subject, $message, implode("\r\n", $headers));

  return $result;
}
