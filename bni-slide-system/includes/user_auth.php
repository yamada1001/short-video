<?php
/**
 * BNI Slide System - User Authentication Helper
 * ベーシック認証されたユーザー情報を取得
 */

/**
 * Get current logged-in user info from Basic Auth
 * @return array|null User info array or null if not found
 */
function getCurrentUserInfo() {
    // Get Basic Auth username
    $username = $_SERVER['PHP_AUTH_USER'] ?? null;

    if (!$username) {
        return null;
    }

    // Load members.json
    $membersFile = __DIR__ . '/../data/members.json';

    if (!file_exists($membersFile)) {
        error_log('Members file not found: ' . $membersFile);
        return null;
    }

    $content = file_get_contents($membersFile);
    if ($content === false) {
        error_log('Failed to read members file');
        return null;
    }

    $data = json_decode($content, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Invalid JSON in members file: ' . json_last_error_msg());
        return null;
    }

    // Find user by htpasswd_user
    if (!isset($data['users'])) {
        error_log('No users data found in members.json');
        return null;
    }

    foreach ($data['users'] as $key => $user) {
        if ($user['htpasswd_user'] === $username) {
            return $user;
        }
    }

    // User not found
    error_log('User not found in members.json: ' . $username);
    return null;
}

/**
 * Get user name by htpasswd username
 * @param string $username Basic Auth username
 * @return string|null User's display name or null if not found
 */
function getUserNameByAuth($username) {
    $userInfo = getCurrentUserInfo();
    return $userInfo ? $userInfo['name'] : null;
}

/**
 * Get user email by htpasswd username
 * @param string $username Basic Auth username
 * @return string|null User's email or null if not found
 */
function getUserEmailByAuth($username) {
    $userInfo = getCurrentUserInfo();
    return $userInfo ? $userInfo['email'] : null;
}

/**
 * Check if current user is authenticated
 * @return bool True if user is authenticated and found in members.json
 */
function isUserAuthenticated() {
    return getCurrentUserInfo() !== null;
}
