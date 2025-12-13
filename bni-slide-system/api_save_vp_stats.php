<?php
/**
 * BNI Slide System - Save VP Statistics Data API
 * 管理者が週ごとのVP統計データを保存するAPI
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/csrf.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'ログインが必要です'
    ]);
    exit;
}

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => '管理者権限が必要です'
    ]);
    exit;
}

// POSTメソッドのみ受け付ける
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'POSTメソッドのみ許可されています'
    ]);
    exit;
}

// CSRFトークン検証
requireCSRFToken();

try {
    // バリデーション
    $weekDate = trim($_POST['week_date'] ?? '');
    $statsData = trim($_POST['stats_data'] ?? '');

    if (empty($weekDate)) {
        throw new Exception('週の日付が指定されていません');
    }

    if (empty($statsData)) {
        throw new Exception('統計データが指定されていません');
    }

    // JSON形式検証
    $statsArray = json_decode($statsData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('統計データの形式が不正です');
    }

    // データベースに保存
    $db = getDbConnection();

    // 既存データがあるか確認
    $checkQuery = "SELECT id FROM vp_statistics WHERE week_date = :week_date LIMIT 1";
    $existing = dbQueryOne($db, $checkQuery, [':week_date' => $weekDate]);

    if ($existing) {
        // UPDATE
        $updateQuery = "
            UPDATE vp_statistics
            SET stats_data = :stats_data,
                updated_at = datetime('now', 'localtime')
            WHERE week_date = :week_date
        ";

        dbExecute($db, $updateQuery, [
            ':stats_data' => $statsData,
            ':week_date' => $weekDate
        ]);

        $message = 'VP統計データを更新しました';
    } else {
        // INSERT
        $insertQuery = "
            INSERT INTO vp_statistics (week_date, stats_data, created_at, updated_at)
            VALUES (:week_date, :stats_data, datetime('now', 'localtime'), datetime('now', 'localtime'))
        ";

        dbExecute($db, $insertQuery, [
            ':week_date' => $weekDate,
            ':stats_data' => $statsData
        ]);

        $message = 'VP統計データを保存しました';
    }

    dbClose($db);

    // 監査ログ
    error_log(sprintf(
        '[VP STATS SAVE] Admin: %s | Week: %s | Stats: %s',
        $currentUser['email'],
        $weekDate,
        json_encode($statsArray)
    ));

    echo json_encode([
        'success' => true,
        'message' => $message,
        'data' => [
            'week_date' => $weekDate
        ]
    ]);

} catch (Exception $e) {
    error_log('[API SAVE VP STATS] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
