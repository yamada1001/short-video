<?php
/**
 * BNI Slide System - Save Weekly Presenter Data API
 * 管理者が週ごとのウィークリープレゼンターデータを保存するAPI
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
    $memberId = trim($_POST['member_id'] ?? '');
    $memberName = trim($_POST['member_name'] ?? '');
    $topic = trim($_POST['topic'] ?? '');
    $referralTarget = trim($_POST['referral_target'] ?? '');
    $notes = trim($_POST['notes'] ?? '');

    if (empty($weekDate)) {
        throw new Exception('週の日付が指定されていません');
    }

    if (empty($memberId)) {
        throw new Exception('プレゼン担当メンバーが指定されていません');
    }

    // データベースに保存
    $db = getDbConnection();

    // 既存データがあるか確認
    $checkQuery = "SELECT id FROM weekly_presenters WHERE week_date = :week_date LIMIT 1";
    $existing = dbQueryOne($db, $checkQuery, [':week_date' => $weekDate]);

    if ($existing) {
        // UPDATE
        $updateQuery = "
            UPDATE weekly_presenters
            SET member_id = :member_id,
                member_name = :member_name,
                topic = :topic,
                referral_target = :referral_target,
                notes = :notes,
                updated_at = datetime('now', 'localtime')
            WHERE week_date = :week_date
        ";

        dbExecute($db, $updateQuery, [
            ':member_id' => $memberId,
            ':member_name' => $memberName,
            ':topic' => $topic,
            ':referral_target' => $referralTarget,
            ':notes' => $notes,
            ':week_date' => $weekDate
        ]);

        $message = 'ウィークリープレゼンターデータを更新しました';
    } else {
        // INSERT
        $insertQuery = "
            INSERT INTO weekly_presenters (week_date, member_id, member_name, topic, referral_target, notes, created_at, updated_at)
            VALUES (:week_date, :member_id, :member_name, :topic, :referral_target, :notes, datetime('now', 'localtime'), datetime('now', 'localtime'))
        ";

        dbExecute($db, $insertQuery, [
            ':week_date' => $weekDate,
            ':member_id' => $memberId,
            ':member_name' => $memberName,
            ':topic' => $topic,
            ':referral_target' => $referralTarget,
            ':notes' => $notes
        ]);

        $message = 'ウィークリープレゼンターデータを保存しました';
    }

    dbClose($db);

    // 監査ログ
    error_log(sprintf(
        '[WEEKLY PRESENTER SAVE] Admin: %s | Week: %s | Presenter: %s | Topic: %s',
        $currentUser['email'],
        $weekDate,
        $memberName,
        $topic ? substr($topic, 0, 50) : '(なし)'
    ));

    echo json_encode([
        'success' => true,
        'message' => $message,
        'data' => [
            'week_date' => $weekDate,
            'member_id' => $memberId,
            'member_name' => $memberName,
            'topic' => $topic
        ]
    ]);

} catch (Exception $e) {
    error_log('[API SAVE WEEKLY PRESENTER] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
