<?php
/**
 * BNI Slide System - Delete User API
 * ユーザー削除API
 */

header('Content-Type: application/json; charset=utf-8');

// Error logging
error_log('[DELETE USER] Script started');

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Get current user info
$currentUser = getCurrentUserInfo();

error_log('[DELETE USER] Current user: ' . print_r($currentUser, true));

// Check if user is logged in and is admin
if (!$currentUser) {
    error_log('[DELETE USER] User not logged in');
    echo json_encode([
        'success' => false,
        'message' => 'ログインが必要です'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Check admin role - allow if role is not set (backward compatibility) or if role is admin
$isAdmin = !isset($currentUser['role']) || $currentUser['role'] === 'admin';

if (!$isAdmin) {
    error_log('[DELETE USER] User is not admin: ' . ($currentUser['role'] ?? 'no role'));
    echo json_encode([
        'success' => false,
        'message' => '管理者権限が必要です'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

error_log('[DELETE USER] Admin check passed');

try {
    // Get username to delete
    $username = $_POST['username'] ?? '';
    error_log('[DELETE USER] Requested username: ' . $username);

    if (empty($username)) {
        throw new Exception('ユーザー名が指定されていません');
    }

    // Prevent deleting admin user
    if ($username === 'admin') {
        throw new Exception('adminユーザーは削除できません');
    }

    // Prevent deleting yourself
    $currentUsername = $currentUser['username'] ?? $currentUser['email'] ?? '';
    error_log('[DELETE USER] Current username: ' . $currentUsername);

    if ($username === $currentUsername) {
        throw new Exception('自分自身を削除することはできません');
    }

    // Load members data
    $membersFile = __DIR__ . '/data/members.json';
    error_log('[DELETE USER] Members file: ' . $membersFile);

    if (!file_exists($membersFile)) {
        throw new Exception('ユーザーデータファイルが見つかりません');
    }

    $content = file_get_contents($membersFile);
    $data = json_decode($content, true);

    if (!$data || !isset($data['users'])) {
        error_log('[DELETE USER] Invalid data structure');
        throw new Exception('ユーザーデータの読み込みに失敗しました');
    }

    error_log('[DELETE USER] Total users: ' . count($data['users']));
    error_log('[DELETE USER] User keys: ' . implode(', ', array_keys($data['users'])));

    // Check if user exists
    if (!isset($data['users'][$username])) {
        error_log('[DELETE USER] User not found: ' . $username);
        throw new Exception('指定されたユーザーが見つかりません: ' . $username);
    }

    // Store deleted user info for logging
    $deletedUser = $data['users'][$username];
    error_log('[DELETE USER] Deleting user: ' . print_r($deletedUser, true));

    // Delete user
    unset($data['users'][$username]);

    // Save updated data
    $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents($membersFile, $jsonContent) === false) {
        error_log('[DELETE USER] Failed to write file');
        throw new Exception('ユーザーデータの保存に失敗しました');
    }

    error_log('[DELETE USER] User deleted successfully');

    echo json_encode([
        'success' => true,
        'message' => "ユーザー「{$username}」を削除しました",
        'deleted_user' => [
            'username' => $username,
            'name' => $deletedUser['name'] ?? '',
            'email' => $deletedUser['email'] ?? ''
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    error_log('[DELETE USER] Exception: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
