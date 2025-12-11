<?php
/**
 * BNI Slide System - File Upload Helper
 * ピッチファイルのアップロード共通処理
 */

// ファイルアップロード設定
define('PITCH_UPLOAD_DIR', __DIR__ . '/../data/pitch/');
define('EDUCATION_UPLOAD_DIR', __DIR__ . '/../data/education/');
define('PITCH_MAX_FILE_SIZE', 30 * 1024 * 1024); // 30MB
define('PITCH_ALLOWED_TYPES', ['pdf', 'pptx', 'ppt']);
define('PITCH_ALLOWED_MIMES', [
    'application/pdf',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'application/vnd.ms-powerpoint'
]);

/**
 * ピッチファイルのバリデーション
 *
 * @param array $file アップロードファイル ($_FILES['field_name'])
 * @return array ['success' => bool, 'message' => string, 'file_type' => string]
 */
function validatePitchFile($file) {
    // Check if file was uploaded
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return [
            'success' => false,
            'message' => 'ファイルがアップロードされていません'
        ];
    }

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'ファイルサイズが大きすぎます（php.iniの設定）',
            UPLOAD_ERR_FORM_SIZE => 'ファイルサイズが大きすぎます',
            UPLOAD_ERR_PARTIAL => 'ファイルが一部しかアップロードされませんでした',
            UPLOAD_ERR_NO_TMP_DIR => '一時フォルダが見つかりません',
            UPLOAD_ERR_CANT_WRITE => 'ファイルの書き込みに失敗しました',
            UPLOAD_ERR_EXTENSION => 'PHPの拡張機能によりアップロードが中断されました'
        ];

        $message = $errorMessages[$file['error']] ?? 'ファイルのアップロードに失敗しました';
        return [
            'success' => false,
            'message' => $message
        ];
    }

    // Check file size
    if ($file['size'] > PITCH_MAX_FILE_SIZE) {
        return [
            'success' => false,
            'message' => 'ファイルサイズは10MB以下にしてください（現在: ' . round($file['size'] / 1024 / 1024, 2) . 'MB）'
        ];
    }

    // Check file extension
    $originalName = $file['name'];
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($ext, PITCH_ALLOWED_TYPES)) {
        return [
            'success' => false,
            'message' => '対応していないファイル形式です。PDF (.pdf) または PowerPoint (.pptx, .ppt) をアップロードしてください。'
        ];
    }

    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mimeType, PITCH_ALLOWED_MIMES)) {
        return [
            'success' => false,
            'message' => 'ファイルの形式が正しくありません（MIMEタイプ検証エラー）'
        ];
    }

    // Determine file type
    $fileType = ($ext === 'pdf') ? 'pdf' : 'pptx';

    return [
        'success' => true,
        'message' => 'ファイルは有効です',
        'file_type' => $fileType
    ];
}

/**
 * ピッチファイルを保存
 *
 * @param array $file アップロードファイル ($_FILES['field_name'])
 * @param string $weekDate 週の日付 (YYYY-MM-DD)
 * @param int $userId ユーザーID
 * @return array ['success' => bool, 'message' => string, 'file_path' => string, 'file_type' => string, 'original_name' => string]
 */
function savePitchFile($file, $weekDate, $userId) {
    // Validate file
    $validation = validatePitchFile($file);
    if (!$validation['success']) {
        return $validation;
    }

    // Create upload directory if not exists
    if (!is_dir(PITCH_UPLOAD_DIR)) {
        mkdir(PITCH_UPLOAD_DIR, 0707, true);
    }

    // Generate safe filename
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $timestamp = time();
    $safeFilename = sprintf(
        '%s_%d_%d.%s',
        sanitizeForFilename($weekDate),
        $userId,
        $timestamp,
        $ext
    );

    $targetPath = PITCH_UPLOAD_DIR . $safeFilename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return [
            'success' => false,
            'message' => 'ファイルの保存に失敗しました'
        ];
    }

    // Set file permissions
    chmod($targetPath, 0644);

    return [
        'success' => true,
        'message' => 'ファイルを保存しました',
        'file_path' => 'data/pitch/' . $safeFilename,  // Relative path from project root
        'file_type' => $validation['file_type'],
        'original_name' => $file['name']
    ];
}

/**
 * ピッチファイルを削除
 *
 * @param string $filePath ファイルパス（プロジェクトルートからの相対パス）
 * @return bool 削除成功: true, 失敗: false
 */
function deletePitchFile($filePath) {
    if (empty($filePath)) {
        return false;
    }

    $absolutePath = __DIR__ . '/../' . $filePath;

    // Check if file exists
    if (!file_exists($absolutePath)) {
        return false;
    }

    // Security check: ensure file is in pitch directory
    $realPath = realpath($absolutePath);
    $pitchDir = realpath(PITCH_UPLOAD_DIR);

    if (strpos($realPath, $pitchDir) !== 0) {
        error_log('Security: Attempted to delete file outside pitch directory: ' . $filePath);
        return false;
    }

    // Delete file
    return unlink($absolutePath);
}

/**
 * ピッチファイルのURLを取得（API経由）
 *
 * @param string $filePath ファイルパス
 * @return string URL
 */
function getPitchFileUrl($filePath) {
    if (empty($filePath)) {
        return '';
    }

    // For now, return API endpoint that will serve the file
    // TODO: Implement api_get_pitch_file.php to serve files securely
    return 'api_get_pitch_file.php?file=' . urlencode(basename($filePath));
}

/**
 * ファイル名用のサニタイズ
 *
 * @param string $string サニタイズする文字列
 * @return string サニタイズ後の文字列
 */
function sanitizeForFilename($string) {
    // Remove any character that is not alphanumeric, dash, underscore, or dot
    $string = preg_replace('/[^a-zA-Z0-9\-_\.]/', '', $string);
    return $string;
}

/**
 * 既存のピッチファイルを取得（特定の週・ユーザー）
 *
 * @param string $weekDate 週の日付 (YYYY-MM-DD)
 * @param int $userId ユーザーID
 * @return array|null ファイル情報（見つからない場合はnull）
 */
function getExistingPitchFile($weekDate, $userId) {
    $pattern = sprintf(
        '%s_%d_*.{pdf,pptx,ppt}',
        sanitizeForFilename($weekDate),
        $userId
    );

    $files = glob(PITCH_UPLOAD_DIR . $pattern, GLOB_BRACE);

    if (empty($files)) {
        return null;
    }

    // Return the first (and should be only) match
    $file = $files[0];
    $basename = basename($file);
    $ext = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

    return [
        'path' => 'data/pitch/' . $basename,
        'type' => ($ext === 'pdf') ? 'pdf' : 'pptx',
        'basename' => $basename
    ];
}

/**
 * ピッチファイルの存在チェック
 *
 * @param string $filePath ファイルパス
 * @return bool
 */
function pitchFileExists($filePath) {
    if (empty($filePath)) {
        return false;
    }

    $absolutePath = __DIR__ . '/../' . $filePath;
    return file_exists($absolutePath);
}

/**
 * エデュケーションファイルを保存
 *
 * @param array $file アップロードファイル ($_FILES['field_name'])
 * @param string $weekDate 週の日付 (YYYY-MM-DD)
 * @param int $userId ユーザーID
 * @return array ['success' => bool, 'message' => string, 'file_path' => string, 'file_type' => string, 'original_name' => string]
 */
function saveEducationFile($file, $weekDate, $userId) {
    // Validate file (use same validation as pitch file)
    $validation = validatePitchFile($file);
    if (!$validation['success']) {
        return $validation;
    }

    // Create upload directory if not exists
    if (!is_dir(EDUCATION_UPLOAD_DIR)) {
        mkdir(EDUCATION_UPLOAD_DIR, 0707, true);
    }

    // Generate safe filename
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $timestamp = time();
    $safeFilename = sprintf(
        '%s_%d_%d.%s',
        sanitizeForFilename($weekDate),
        $userId,
        $timestamp,
        $ext
    );

    $targetPath = EDUCATION_UPLOAD_DIR . $safeFilename;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return [
            'success' => false,
            'message' => 'ファイルの保存に失敗しました'
        ];
    }

    // Set file permissions
    chmod($targetPath, 0644);

    return [
        'success' => true,
        'message' => 'ファイルを保存しました',
        'file_path' => 'data/education/' . $safeFilename,  // Relative path from project root
        'file_type' => $validation['file_type'],
        'original_name' => $file['name']
    ];
}

/**
 * エデュケーションファイルを削除
 *
 * @param string $filePath ファイルパス（プロジェクトルートからの相対パス）
 * @return bool 削除成功: true, 失敗: false
 */
function deleteEducationFile($filePath) {
    if (empty($filePath)) {
        return false;
    }

    $absolutePath = __DIR__ . '/../' . $filePath;

    // Check if file exists
    if (!file_exists($absolutePath)) {
        return false;
    }

    // Security check: ensure file is in education directory
    $realPath = realpath($absolutePath);
    $educationDir = realpath(EDUCATION_UPLOAD_DIR);

    if (strpos($realPath, $educationDir) !== 0) {
        error_log('Security: Attempted to delete file outside education directory: ' . $filePath);
        return false;
    }

    // Delete file
    return unlink($absolutePath);
}
