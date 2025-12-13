<?php
/**
 * API: Load Monthly Ranking Data
 * 月間ランキングデータを読み込み
 */

header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/db.php';

try {
  // セッション開始
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  // 管理者チェック
  $currentUser = getCurrentUser();
  if (!$currentUser || $currentUser['role'] !== 'admin') {
    throw new Exception('管理者権限が必要です');
  }

  $yearMonth = $_GET['year_month'] ?? '';

  if (empty($yearMonth)) {
    throw new Exception('対象月を指定してください');
  }

  // データベース接続
  $db = getDbConnection();

  $result = dbQueryOne($db, "SELECT ranking_data, display_in_slide FROM monthly_ranking_data WHERE year_month = :year_month", [
    ':year_month' => $yearMonth
  ]);

  dbClose($db);

  if (!$result) {
    echo json_encode([
      'success' => false,
      'message' => 'データが見つかりません'
    ], JSON_UNESCAPED_UNICODE);
    exit;
  }

  $rankingData = json_decode($result['ranking_data'], true);
  $displayInSlide = isset($result['display_in_slide']) ? (int)$result['display_in_slide'] : 0;

  echo json_encode([
    'success' => true,
    'year_month' => $yearMonth,
    'data' => $rankingData,
    'display_in_slide' => $displayInSlide
  ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ], JSON_UNESCAPED_UNICODE);
}
