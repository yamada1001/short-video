<?php
/**
 * BNI Slide System - Load Seating Chart API
 * 座席表データを読み込むAPI
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/includes/session_auth.php';

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

try {
    $seatingFile = __DIR__ . '/data/seating_chart.json';

    if (!file_exists($seatingFile)) {
        throw new Exception('座席表データファイルが見つかりません');
    }

    $jsonContent = file_get_contents($seatingFile);
    if ($jsonContent === false) {
        throw new Exception('座席表データの読み込みに失敗しました');
    }

    $seatingData = json_decode($jsonContent, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('座席表データの形式が正しくありません: ' . json_last_error_msg());
    }

    echo json_encode([
        'success' => true,
        'data' => $seatingData
    ]);

} catch (Exception $e) {
    error_log('[API LOAD SEATING] Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
