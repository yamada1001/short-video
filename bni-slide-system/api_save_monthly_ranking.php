<?php
/**
 * API: Save Monthly Ranking Data
 * 月間ランキングデータを保存
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

  // リクエストボディを取得
  $json = file_get_contents('php://input');
  $data = json_decode($json, true);

  if (!$data) {
    throw new Exception('Invalid JSON data');
  }

  $yearMonth = $data['year_month'] ?? '';
  $rankingData = $data['ranking_data'] ?? null;

  if (empty($yearMonth)) {
    throw new Exception('対象月を指定してください');
  }

  if (!$rankingData) {
    throw new Exception('ランキングデータが不正です');
  }

  // データベース接続
  $db = getDbConnection();

  // 既存データを確認
  $existing = dbQueryOne($db, "SELECT id FROM monthly_ranking_data WHERE year_month = :year_month", [
    ':year_month' => $yearMonth
  ]);

  $rankingJson = json_encode($rankingData, JSON_UNESCAPED_UNICODE);

  if ($existing) {
    // 更新
    $query = "UPDATE monthly_ranking_data
              SET ranking_data = :ranking_data,
                  updated_at = CURRENT_TIMESTAMP
              WHERE year_month = :year_month";

    dbExecute($db, $query, [
      ':ranking_data' => $rankingJson,
      ':year_month' => $yearMonth
    ]);
  } else {
    // 新規作成
    $query = "INSERT INTO monthly_ranking_data (year_month, ranking_data)
              VALUES (:year_month, :ranking_data)";

    dbExecute($db, $query, [
      ':year_month' => $yearMonth,
      ':ranking_data' => $rankingJson
    ]);
  }

  dbClose($db);

  echo json_encode([
    'success' => true,
    'message' => 'ランキングデータを保存しました',
    'year_month' => $yearMonth
  ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
  http_response_code(400);
  echo json_encode([
    'success' => false,
    'message' => $e->getMessage()
  ], JSON_UNESCAPED_UNICODE);
}
