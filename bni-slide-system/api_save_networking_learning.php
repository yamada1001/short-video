<?php
/**
 * API: Save Networking Learning Corner Presenter (with PDF upload)
 * ネットワーキング学習コーナーの担当者を保存（PDF対応）
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
    // FormDataからパラメータ取得
    $weekDate = $_POST['week_date'] ?? '';
    $presenterName = trim($_POST['presenter_name'] ?? '');

    // 必須フィールドのバリデーション
    if (empty($weekDate)) {
        throw new Exception('week_date は必須項目です');
    }

    if (empty($presenterName)) {
        throw new Exception('presenter_name は必須項目です');
    }

    $pdfFilePath = null;
    $pdfFileOriginalName = null;

    // PDFファイルのアップロード処理
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['pdf_file'];

        // ファイルタイプチェック
        $allowedTypes = ['application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $uploadedFile['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            throw new Exception('PDFファイルのみアップロード可能です');
        }

        // ファイルサイズチェック (10MB)
        if ($uploadedFile['size'] > 10 * 1024 * 1024) {
            throw new Exception('ファイルサイズは10MB以下にしてください');
        }

        // アップロードディレクトリ作成
        $uploadDir = __DIR__ . '/uploads/networking_learning/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 安全なファイル名生成
        $pdfFileOriginalName = basename($uploadedFile['name']);
        $fileExtension = pathinfo($pdfFileOriginalName, PATHINFO_EXTENSION);
        $safeFileName = date('Ymd_His') . '_' . uniqid() . '.' . $fileExtension;
        $pdfFilePath = 'uploads/networking_learning/' . $safeFileName;
        $fullPath = __DIR__ . '/' . $pdfFilePath;

        // ファイル移動
        if (!move_uploaded_file($uploadedFile['tmp_name'], $fullPath)) {
            throw new Exception('ファイルのアップロードに失敗しました');
        }
    }

    // データベース接続
    $db = getDbConnection();

    // 既存データを削除前にPDFパスを取得
    $existing = dbQueryOne($db,
        "SELECT pdf_file_path FROM networking_learning_presenters WHERE week_date = ?",
        [$weekDate]
    );

    // 既存のPDFファイルがあれば削除（新しいPDFがアップロードされた場合のみ）
    if ($existing && !empty($existing['pdf_file_path']) && $pdfFilePath) {
        $oldFilePath = __DIR__ . '/' . $existing['pdf_file_path'];
        if (file_exists($oldFilePath)) {
            unlink($oldFilePath);
        }
    }

    // 既存データを削除（週ごとに1人のみのため）
    dbExecute($db,
        "DELETE FROM networking_learning_presenters WHERE week_date = ?",
        [$weekDate]
    );

    // 新規データを挿入
    if ($pdfFilePath) {
        dbExecute($db,
            "INSERT INTO networking_learning_presenters
            (week_date, presenter_name, pdf_file_path, pdf_file_original_name, created_at, updated_at)
            VALUES (?, ?, ?, ?, datetime('now', 'localtime'), datetime('now', 'localtime'))",
            [$weekDate, $presenterName, $pdfFilePath, $pdfFileOriginalName]
        );
    } else {
        // PDFがアップロードされなかった場合、既存のPDFパスを保持
        $existingPdfPath = $existing['pdf_file_path'] ?? null;
        $existingPdfName = null;

        if ($existingPdfPath) {
            // 既存のPDF情報を再取得（削除前のデータから）
            $existingPdfName = basename($existingPdfPath);
        }

        dbExecute($db,
            "INSERT INTO networking_learning_presenters
            (week_date, presenter_name, pdf_file_path, pdf_file_original_name, created_at, updated_at)
            VALUES (?, ?, ?, ?, datetime('now', 'localtime'), datetime('now', 'localtime'))",
            [$weekDate, $presenterName, $existingPdfPath, $existingPdfName]
        );
    }

    $newId = $db->lastInsertRowID();

    dbClose($db);

    echo json_encode([
        'success' => true,
        'message' => '担当者を保存しました',
        'id' => $newId,
        'pdf_uploaded' => !is_null($pdfFilePath)
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    error_log('[API SAVE NETWORKING LEARNING] Error: ' . $e->getMessage());
}
