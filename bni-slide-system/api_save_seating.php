<?php
/**
 * BNI Slide System - Save Seating Chart API
 * 座席表データを保存するAPI
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/session_auth.php';
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

try {
    // Get JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('リクエストデータの形式が正しくありません');
    }

    // CSRFトークン検証
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $requestToken = $data['csrf_token'] ?? '';

    if (empty($sessionToken) || empty($requestToken) || !hash_equals($sessionToken, $requestToken)) {
        http_response_code(403);
        throw new Exception('不正なリクエストです');
    }

    // Validate seating data
    if (!isset($data['seating_data']) || !is_array($data['seating_data'])) {
        throw new Exception('座席表データが不正です');
    }

    $seatingData = $data['seating_data'];

    // Validate required fields
    if (!isset($seatingData['tables']) || !is_array($seatingData['tables'])) {
        throw new Exception('テーブルデータが不正です');
    }

    // Save to file
    $seatingFile = __DIR__ . '/data/seating_chart.json';
    $jsonContent = json_encode($seatingData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    if (file_put_contents($seatingFile, $jsonContent) === false) {
        throw new Exception('座席表データの保存に失敗しました');
    }

    // 監査ログ
    error_log(sprintf(
        '[SEATING SAVE] Admin: %s | Tables: %d | Last Updated: %s',
        $currentUser['email'],
        count($seatingData['tables']),
        $seatingData['last_updated'] ?? 'unknown'
    ));

    echo json_encode([
        'success' => true,
        'message' => '座席表を保存しました'
    ]);

} catch (Exception $e) {
    error_log('[API SAVE SEATING] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
