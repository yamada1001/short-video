<?php
/**
 * BNI Slide System - Get Pitch File API
 * ピッチファイルを安全に配信するAPI
 */

require_once __DIR__ . '/includes/session_auth.php';
require_once __DIR__ . '/includes/file_upload_helper.php';

// セッション開始
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ログイン確認
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(403);
    die('アクセス権限がありません');
}

// Get file parameter
$file = $_GET['file'] ?? '';

if (empty($file)) {
    http_response_code(400);
    die('ファイルが指定されていません');
}

// Security: Only allow files from pitch directory
$filePath = 'data/pitch/' . basename($file);
$absolutePath = __DIR__ . '/' . $filePath;

// Check if file exists
if (!file_exists($absolutePath)) {
    http_response_code(404);
    die('ファイルが見つかりません');
}

// Security check: ensure file is in pitch directory
$realPath = realpath($absolutePath);
$pitchDir = realpath(PITCH_UPLOAD_DIR);

if (strpos($realPath, $pitchDir) !== 0) {
    error_log('Security: Attempted to access file outside pitch directory: ' . $file);
    http_response_code(403);
    die('不正なアクセスです');
}

// Get file info
$ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
$mimeTypes = [
    'pdf' => 'application/pdf',
    'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'ppt' => 'application/vnd.ms-powerpoint'
];

$mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';

// Set headers
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($absolutePath));
header('Content-Disposition: inline; filename="' . basename($file) . '"');
header('Cache-Control: private, max-age=3600');
header('X-Content-Type-Options: nosniff');

// Output file
readfile($absolutePath);
exit;
