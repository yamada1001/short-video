<?php
/**
 * API: Delete Networking Learning PDF
 * ネットワーキング学習コーナーのPDF資料を削除
 */

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/db.php';

header('Content-Type: application/json; charset=UTF-8');

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'ログインが必要です'], JSON_UNESCAPED_UNICODE);
    exit;
}

// 管理者権限チェック
$isAdmin = isset($currentUser['role']) && $currentUser['role'] === 'admin';
if (!$isAdmin) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => '管理者権限が必要です'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    // POSTデータを取得
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('JSONデータが不正です');
    }

    if (empty($data['week_date'])) {
        throw new Exception('week_date パラメータが必要です');
    }

    $weekDate = $data['week_date'];

    // データベース接続
    $db = getDbConnection();

    // 既存データからPDFパスを取得
    $existing = dbQueryOne($db,
        "SELECT pdf_file_path FROM networking_learning_presenters WHERE week_date = ?",
        [$weekDate]
    );

    if (!$existing) {
        dbClose($db);
        throw new Exception('該当する週のデータが見つかりません');
    }

    // PDFファイルを削除
    if (!empty($existing['pdf_file_path'])) {
        $filePath = __DIR__ . '/' . $existing['pdf_file_path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // データベースのPDFカラムをNULLに更新
    dbExecute($db,
        "UPDATE networking_learning_presenters
        SET pdf_file_path = NULL, pdf_file_original_name = NULL, updated_at = datetime('now', 'localtime')
        WHERE week_date = ?",
        [$weekDate]
    );

    dbClose($db);

    echo json_encode([
        'success' => true,
        'message' => 'PDF資料を削除しました'
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    error_log('[API DELETE NETWORKING PDF] Error: ' . $e->getMessage());
}
