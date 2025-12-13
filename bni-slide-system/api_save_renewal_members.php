<?php
/**
 * BNI Slide System - Save Renewal Members Data API
 * 管理者が週ごとの更新メンバーデータを保存するAPI
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
    $memberIds = trim($_POST['member_ids'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($weekDate)) {
        throw new Exception('週の日付が指定されていません');
    }

    if (empty($memberIds)) {
        throw new Exception('更新メンバーが指定されていません');
    }

    // JSON形式検証
    $memberIdsArray = json_decode($memberIds, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('更新メンバーデータの形式が不正です');
    }

    // データベースに保存
    $db = getDbConnection();

    // 既存データがあるか確認
    $checkQuery = "SELECT id FROM renewal_members WHERE week_date = :week_date LIMIT 1";
    $existing = dbQueryOne($db, $checkQuery, [':week_date' => $weekDate]);

    if ($existing) {
        // UPDATE
        $updateQuery = "
            UPDATE renewal_members
            SET member_ids = :member_ids,
                notes = :notes,
                updated_at = datetime('now', 'localtime')
            WHERE week_date = :week_date
        ";

        dbExecute($db, $updateQuery, [
            ':member_ids' => $memberIds,
            ':notes' => $notes,
            ':week_date' => $weekDate
        ]);

        $message = '更新メンバーデータを更新しました';
    } else {
        // INSERT
        $insertQuery = "
            INSERT INTO renewal_members (week_date, member_ids, notes, created_at, updated_at)
            VALUES (:week_date, :member_ids, :notes, datetime('now', 'localtime'), datetime('now', 'localtime'))
        ";

        dbExecute($db, $insertQuery, [
            ':week_date' => $weekDate,
            ':member_ids' => $memberIds,
            ':notes' => $notes
        ]);

        $message = '更新メンバーデータを保存しました';
    }

    dbClose($db);

    // 監査ログ
    error_log(sprintf(
        '[RENEWAL MEMBERS SAVE] Admin: %s | Week: %s | Members: %d',
        $currentUser['email'],
        $weekDate,
        count($memberIdsArray)
    ));

    echo json_encode([
        'success' => true,
        'message' => $message,
        'data' => [
            'week_date' => $weekDate,
            'members_count' => count($memberIdsArray)
        ]
    ]);

} catch (Exception $e) {
    error_log('[API SAVE RENEWAL MEMBERS] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
