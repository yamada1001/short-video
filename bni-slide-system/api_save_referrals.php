<?php
/**
 * BNI Slide System - Save Referral Data API
 * 管理者が週ごとのリファーラル総額を保存するAPI
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
if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => '不正なリクエストです'
    ]);
    exit;
}

try {
    // バリデーション
    $weekDate = trim($_POST['week_date'] ?? '');
    $totalAmount = trim($_POST['total_amount'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($weekDate)) {
        throw new Exception('週の日付が指定されていません');
    }

    if (empty($totalAmount)) {
        throw new Exception('リファーラル総額を入力してください');
    }

    if (!is_numeric($totalAmount)) {
        throw new Exception('リファーラル総額は数値で入力してください');
    }

    $totalAmount = intval($totalAmount);

    if ($totalAmount < 0) {
        throw new Exception('リファーラル総額は0以上の数値を入力してください');
    }

    // データベースに保存
    $db = getDbConnection();

    // 既存データがあるか確認
    $checkQuery = "SELECT id FROM referrals_weekly WHERE week_date = :week_date LIMIT 1";
    $existing = dbQueryOne($db, $checkQuery, [':week_date' => $weekDate]);

    if ($existing) {
        // UPDATE
        $updateQuery = "
            UPDATE referrals_weekly
            SET total_amount = :total_amount,
                notes = :notes,
                updated_at = datetime('now', 'localtime')
            WHERE week_date = :week_date
        ";

        dbExecute($db, $updateQuery, [
            ':total_amount' => $totalAmount,
            ':notes' => $notes,
            ':week_date' => $weekDate
        ]);

        $message = 'リファーラル金額を更新しました';
    } else {
        // INSERT
        $insertQuery = "
            INSERT INTO referrals_weekly (week_date, total_amount, notes, created_at, updated_at)
            VALUES (:week_date, :total_amount, :notes, datetime('now', 'localtime'), datetime('now', 'localtime'))
        ";

        dbExecute($db, $insertQuery, [
            ':week_date' => $weekDate,
            ':total_amount' => $totalAmount,
            ':notes' => $notes
        ]);

        $message = 'リファーラル金額を保存しました';
    }

    dbClose($db);

    // 監査ログ
    error_log(sprintf(
        '[REFERRAL SAVE] Admin: %s | Week: %s | Amount: %d | Notes: %s',
        $currentUser['email'],
        $weekDate,
        $totalAmount,
        $notes ? substr($notes, 0, 50) : '(なし)'
    ));

    echo json_encode([
        'success' => true,
        'message' => $message,
        'data' => [
            'week_date' => $weekDate,
            'total_amount' => $totalAmount,
            'notes' => $notes
        ]
    ]);

} catch (Exception $e) {
    error_log('[API SAVE REFERRALS] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
