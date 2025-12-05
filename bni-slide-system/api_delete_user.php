<?php
/**
 * BNI Slide System - Delete User API
 * ユーザー削除API
 */

header('Content-Type: application/json; charset=utf-8');

// Load user authentication helper
require_once __DIR__ . '/includes/user_auth.php';

// Get current user info
$currentUser = getCurrentUserInfo();

// Check if user is logged in and is admin
if (!$currentUser || (isset($currentUser['role']) && $currentUser['role'] !== 'admin')) {
    echo json_encode([
        'success' => false,
        'message' => '管理者権限が必要です'
    ]);
    exit;
}

try {
    // Get username to delete
    $username = $_POST['username'] ?? '';

    if (empty($username)) {
        throw new Exception('ユーザー名が指定されていません');
    }

    // Prevent deleting admin user
    if ($username === 'admin') {
        throw new Exception('adminユーザーは削除できません');
    }

    // Prevent deleting yourself
    if ($username === $currentUser['username']) {
        throw new Exception('自分自身を削除することはできません');
    }

    // Load members data
    $membersFile = __DIR__ . '/data/members.json';

    if (!file_exists($membersFile)) {
        throw new Exception('ユーザーデータファイルが見つかりません');
    }

    $content = file_get_contents($membersFile);
    $data = json_decode($content, true);

    if (!$data || !isset($data['users'])) {
        throw new Exception('ユーザーデータの読み込みに失敗しました');
    }

    // Check if user exists
    if (!isset($data['users'][$username])) {
        throw new Exception('指定されたユーザーが見つかりません');
    }

    // Store deleted user info for logging
    $deletedUser = $data['users'][$username];

    // Delete user
    unset($data['users'][$username]);

    // Save updated data
    $jsonContent = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if (file_put_contents($membersFile, $jsonContent) === false) {
        throw new Exception('ユーザーデータの保存に失敗しました');
    }

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
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
