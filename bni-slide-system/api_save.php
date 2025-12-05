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

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode([
    'success' => false,
    'message' => '不正なリクエストです'
  ]);
  exit;
}

try {
  // Get base form data
  $baseData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'input_date' => sanitize($_POST['input_date'] ?? ''),
    'introducer_name' => sanitize($_POST['introducer_name'] ?? ''),
    'email' => sanitize($_POST['email'] ?? ''),
    'attendance' => sanitize($_POST['attendance'] ?? ''),
    'thanks_slips' => intval($_POST['thanks_slips'] ?? 0),
    'one_to_one_count' => intval($_POST['one_to_one_count'] ?? 0),
    'activities' => isset($_POST['activities']) ? implode('|', array_map('sanitize', $_POST['activities'])) : '',
    'comments' => sanitize($_POST['comments'] ?? '')
  ];

  // Validate required fields
  if (empty($baseData['input_date']) || empty($baseData['introducer_name']) ||
      empty($baseData['email']) || empty($baseData['attendance'])) {
    throw new Exception('必須項目が入力されていません');
  }

  // Get visitor data (multiple items, optional)
  $visitors = [];
  if (isset($_POST['visitor_name']) && is_array($_POST['visitor_name'])) {
    $count = count($_POST['visitor_name']);
    for ($i = 0; $i < $count; $i++) {
      $visitorName = sanitize($_POST['visitor_name'][$i] ?? '');
      $visitorCompany = sanitize($_POST['visitor_company'][$i] ?? '');
      $visitorIndustry = sanitize($_POST['visitor_industry'][$i] ?? '');

      // Skip empty visitors
      if (empty($visitorName)) {
        continue;
      }

      $visitors[] = [
        'name' => $visitorName,
        'company' => $visitorCompany,
        'industry' => $visitorIndustry
      ];
    }
  }

  // Get referral data (multiple items)
  $referrals = [];
  if (isset($_POST['referral_name']) && is_array($_POST['referral_name'])) {
    $count = count($_POST['referral_name']);
    for ($i = 0; $i < $count; $i++) {
      $referralName = sanitize($_POST['referral_name'][$i] ?? '');
      $referralAmount = sanitize($_POST['referral_amount'][$i] ?? '0');
      $referralCategory = sanitize($_POST['referral_category'][$i] ?? '');
      $referralProvider = sanitize($_POST['referral_provider'][$i] ?? '');

      // Skip empty referrals
      if (empty($referralName)) {
        continue;
      }

      $referrals[] = [
        'name' => $referralName,
        'amount' => intval($referralAmount),
        'category' => $referralCategory,
        'provider' => $referralProvider
      ];
    }
  }

  // Referrals are now optional - if none provided, create a dummy referral with 0 amount
  if (count($referrals) === 0) {
    $referrals[] = [
      'name' => '-',
      'amount' => 0,
      'category' => 'その他',
      'provider' => ''
    ];
  }

  // Check for duplicate submission in the same week
  $duplicateCheck = checkDuplicateSubmission($baseData['timestamp'], $baseData['introducer_name'], $baseData['email']);
  if ($duplicateCheck['isDuplicate']) {
    throw new Exception('今週は既に回答済みです。1週間に1回のみ回答できます。');
  }

  // Save to CSV (one row per combination of visitor and referral)
  $csvSaved = saveToCSV($baseData, $visitors, $referrals);
  if (!$csvSaved) {
    throw new Exception('CSVファイルへの保存に失敗しました');
  }

  // Send email notification
  $emailSent = sendEmailNotification($baseData, $visitors, $referrals);

  // Response
  echo json_encode([
    'success' => true,
    'message' => 'アンケートを送信しました！',
    'data' => $baseData,
    'visitors' => $visitors,
    'referrals' => $referrals,
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
function saveToCSV($baseData, $visitors, $referrals) {
  // Determine CSV file path from timestamp (not input_date)
  // Week boundary: Thursday 9:00 AM ~ Friday 5:00 AM
  $timestamp = $baseData['timestamp'];
  $targetFriday = getTargetFriday($timestamp);

  // CSV filename: YYYY-MM-DD.csv (Friday date)
  $filename = "$targetFriday.csv";
  $csvFile = __DIR__ . '/data/' . $filename;
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

  // Write rows based on visitors and referrals
  // If no visitors, write one row per referral
  // If visitors exist, write one row per referral (visitor info repeated)

  if (count($visitors) === 0) {
    // No visitors - write referrals only
    foreach ($referrals as $referral) {
      $result = fputcsv($fp, [
        $baseData['timestamp'],
        $baseData['input_date'],
        $baseData['introducer_name'],
        $baseData['email'],
        '', // visitor_name
        '', // visitor_company
        '', // visitor_industry
        $referral['name'],
        $referral['amount'],
        $referral['category'],
        $referral['provider'],
        $baseData['attendance'],
        $baseData['thanks_slips'],
        $baseData['one_to_one_count'],
        $baseData['activities'],
        $baseData['comments']
      ]);

      if ($result === false) {
        fclose($fp);
        return false;
      }
    }
  } else {
    // With visitors - write one row per referral for each visitor
    foreach ($visitors as $visitor) {
      foreach ($referrals as $referral) {
        $result = fputcsv($fp, [
          $baseData['timestamp'],
          $baseData['input_date'],
          $baseData['introducer_name'],
          $baseData['email'],
          $visitor['name'],
          $visitor['company'],
          $visitor['industry'],
          $referral['name'],
          $referral['amount'],
          $referral['category'],
          $referral['provider'],
          $baseData['attendance'],
          $baseData['thanks_slips'],
          $baseData['one_to_one_count'],
          $baseData['activities'],
          $baseData['comments']
        ]);

        if ($result === false) {
          fclose($fp);
          return false;
        }
      }
    }
  }

  fclose($fp);

  // Set file permissions
  chmod($csvFile, 0666);

  return true;
}

/**
 * Send email notification to admin
 */
function sendEmailNotification($baseData, $visitors, $referrals) {
  $to = MAIL_TO;
  $subject = '[BNI] 新しいアンケート回答がありました - ' . $baseData['introducer_name'];

  // Build visitor list HTML
  $visitorListHTML = '';
  if (count($visitors) > 0) {
    foreach ($visitors as $index => $visitor) {
      $visitorListHTML .= '
        <div style="margin-bottom: 15px; padding: 10px; background-color: #f9f9f9; border-left: 3px solid #CF2030;">
          <div class="field">
            <span class="label">ビジター' . ($index + 1) . ':</span>
            <span class="value">' . htmlspecialchars($visitor['name']) . '</span>
          </div>
          <div class="field">
            <span class="label">会社名（屋号）:</span>
            <span class="value">' . htmlspecialchars($visitor['company'] ?: '-') . '</span>
          </div>
          <div class="field">
            <span class="label">業種:</span>
            <span class="value">' . htmlspecialchars($visitor['industry'] ?: '-') . '</span>
          </div>
        </div>
      ';
    }
  } else {
    $visitorListHTML = '<p style="color: #999;">ビジター紹介なし</p>';
  }

  // Build referral list HTML
  $referralListHTML = '';
  $totalAmount = 0;
  $realReferralsCount = 0;

  foreach ($referrals as $index => $referral) {
    $totalAmount += $referral['amount'];

    // Check if this is a real referral (not dummy)
    $isRealReferral = ($referral['name'] !== '-' || $referral['amount'] > 0);

    if ($isRealReferral) {
      $realReferralsCount++;
      $referralListHTML .= '
        <div style="margin-bottom: 15px; padding: 10px; background-color: #f9f9f9; border-left: 3px solid #CF2030;">
          <div class="field">
            <span class="label">案件' . $realReferralsCount . ':</span>
            <span class="value">' . htmlspecialchars($referral['name']) . '</span>
          </div>
          <div class="field">
            <span class="label">金額:</span>
            <span class="value">¥' . number_format($referral['amount']) . '</span>
          </div>
          <div class="field">
            <span class="label">カテゴリ:</span>
            <span class="value">' . htmlspecialchars($referral['category']) . '</span>
          </div>
          <div class="field">
            <span class="label">提供者:</span>
            <span class="value">' . htmlspecialchars($referral['provider'] ?: '-') . '</span>
          </div>
        </div>
      ';
    }
  }

  // If no real referrals, show message
  if ($realReferralsCount === 0) {
    $referralListHTML = '<p style="color: #999;">リファーラルなし</p>';
  }

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
    .total { font-size: 18px; font-weight: bold; color: #CF2030; margin-top: 15px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>BNI週次アンケート - 新規回答</h2>
    </div>
    <div class="content">

      <div class="section">
        <h3>基本情報</h3>
        <div class="field">
          <span class="label">入力日:</span>
          <span class="value">' . htmlspecialchars($baseData['input_date']) . '</span>
        </div>
        <div class="field">
          <span class="label">紹介者名:</span>
          <span class="value">' . htmlspecialchars($baseData['introducer_name']) . '</span>
        </div>
        <div class="field">
          <span class="label">メールアドレス:</span>
          <span class="value">' . htmlspecialchars($baseData['email']) . '</span>
        </div>
      </div>

      <div class="section">
        <h3>1. ビジター紹介情報（' . count($visitors) . '件）</h3>
        ' . $visitorListHTML . '
      </div>

      <div class="section">
        <h3>2. リファーラル金額情報（' . $realReferralsCount . '件）</h3>
        ' . $referralListHTML . '
        <div class="total">
          合計: ¥' . number_format($totalAmount) . '
        </div>
      </div>

      <div class="section">
        <h3>3. メンバー情報</h3>
        <div class="field">
          <span class="label">出席状況:</span>
          <span class="value">' . htmlspecialchars($baseData['attendance']) . '</span>
        </div>
        <div class="field">
          <span class="label">サンクスリップ:</span>
          <span class="value">' . $baseData['thanks_slips'] . '件</span>
        </div>
        <div class="field">
          <span class="label">ワンツーワン:</span>
          <span class="value">' . $baseData['one_to_one_count'] . '回</span>
        </div>
        <div class="field">
          <span class="label">アクティビティ:</span>
          <span class="value">' . htmlspecialchars(str_replace('|', ', ', $baseData['activities']) ?: '-') . '</span>
        </div>
        <div class="field">
          <span class="label">コメント:</span>
          <span class="value">' . nl2br(htmlspecialchars($baseData['comments'] ?: '-')) . '</span>
        </div>
      </div>

      <div class="footer">
        <p>送信日時: ' . $baseData['timestamp'] . '</p>
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

  // Send admin notification
  $adminResult = mail($to, $subject, $message, implode("\r\n", $headers));

  // Send thank you email to user
  $thanksResult = sendThanksEmail($baseData);

  return $adminResult && $thanksResult;
}

/**
 * Send thank you email to user
 */
function sendThanksEmail($baseData) {
  $to = $baseData['email'];
  $subject = '[BNI] アンケートご回答ありがとうございます';

  // Email body (HTML)
  $message = '<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; line-height: 1.8; color: #333; }
    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
    .header { background-color: #CF2030; color: white; padding: 30px 20px; text-align: center; }
    .content { background-color: #ffffff; padding: 30px; }
    .message { font-size: 16px; margin-bottom: 20px; }
    .footer { text-align: center; color: #999; font-size: 13px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>アンケートご回答ありがとうございます</h2>
    </div>
    <div class="content">
      <p class="message">
        ' . htmlspecialchars($baseData['introducer_name']) . ' 様
      </p>
      <p class="message">
        BNI週次アンケートにご回答いただき、誠にありがとうございました。
      </p>
      <p class="message">
        ご入力いただいた内容は、週次レポートに反映されます。<br>
        引き続き、よろしくお願いいたします。
      </p>
      <div class="footer">
        <p>このメールは自動送信されています。</p>
        <p>BNI Slide System</p>
        <p>Givers Gain®</p>
      </div>
    </div>
  </div>
</body>
</html>';

  // Email headers
  $headers = [
    'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM . '>',
    'Reply-To: ' . MAIL_TO,
    'Content-Type: text/html; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion()
  ];

  // Send thank you email
  $result = mail($to, $subject, $message, implode("\r\n", $headers));

  return $result;
}

/**
 * Get target Friday date from timestamp
 *
 * Week boundary: Previous Thursday 9:00 AM ~ Friday 5:00 AM
 * - Data from Thursday 9:00 AM ~ Friday 4:59 AM belongs to that Friday's slide
 * - Example: Nov 28 (Thu) 9:00 ~ Dec 5 (Fri) 4:59 → Dec 5 (Fri) slide
 *
 * @param string $timestamp Timestamp in 'Y-m-d H:i:s' format
 * @return string Friday date in 'Y-m-d' format
 */
function getTargetFriday($timestamp) {
  $dt = new DateTime($timestamp);
  $dayOfWeek = intval($dt->format('w')); // 0=Sunday, 4=Thursday, 5=Friday
  $hour = intval($dt->format('H'));

  // Find next Friday from current date/time
  $daysToFriday = (5 - $dayOfWeek + 7) % 7;

  if ($dayOfWeek === 5) {
    // Friday
    if ($hour < 5) {
      // Friday 0:00-4:59 → This Friday (today)
      $daysToFriday = 0;
    } else {
      // Friday 5:00-23:59 → Next Friday
      $daysToFriday = 7;
    }
  } else if ($dayOfWeek === 4 && $hour >= 9) {
    // Thursday 9:00 or later → Tomorrow (Friday)
    $daysToFriday = 1;
  } else if ($daysToFriday === 0) {
    // Any other day where calculation returns 0 → Next week's Friday
    $daysToFriday = 7;
  }

  if ($daysToFriday > 0) {
    $dt->modify("+$daysToFriday days");
  }

  return $dt->format('Y-m-d');
}

/**
 * Check if user has already submitted for the same week
 *
 * @param string $timestamp The submission timestamp in 'Y-m-d H:i:s' format
 * @param string $introducerName The user's name
 * @param string $email The user's email
 * @return array ['isDuplicate' => bool, 'existingDate' => string|null]
 */
function checkDuplicateSubmission($timestamp, $introducerName, $email) {
  // Calculate week file path using Friday boundary logic
  $targetFriday = getTargetFriday($timestamp);
  $filename = "$targetFriday.csv";
  $csvFile = __DIR__ . '/data/' . $filename;

  // If file doesn't exist, no duplicates
  if (!file_exists($csvFile)) {
    return ['isDuplicate' => false];
  }

  // Read CSV and check for existing entry
  if (($handle = fopen($csvFile, 'r')) !== false) {
    // Skip header row
    $header = fgetcsv($handle);

    while (($row = fgetcsv($handle)) !== false) {
      // CSV format based on saveToCSV():
      // 0: timestamp
      // 1: input_date
      // 2: introducer_name
      // 3: email
      // 4-15: other fields

      if (count($row) >= 4) {
        $csvEmail = $row[3];
        $csvName = $row[2];

        // Check if email or name matches (primary check is email)
        if ($csvEmail === $email) {
          fclose($handle);
          return [
            'isDuplicate' => true,
            'existingDate' => $row[1]
          ];
        }
      }
    }
    fclose($handle);
  }

  return ['isDuplicate' => false];
}
