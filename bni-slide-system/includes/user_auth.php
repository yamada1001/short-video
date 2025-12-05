<?php
/**
 * BNI Slide System - User Authentication Helper
 * セッション認証されたユーザー情報を取得
 */

// Load session auth
require_once __DIR__ . '/session_auth.php';

/**
 * Get current logged-in user info from Session
 * @return array|null User info array or null if not found
 */
function getCurrentUserInfo() {
    // Require login (redirect if not logged in)
    requireLogin();

    // Get user from session
    return getCurrentUser();
}

/**
 * Get user name
 * @return string|null User's display name or null if not found
 */
function getUserNameByAuth() {
    $userInfo = getCurrentUser();
    return $userInfo ? $userInfo['name'] : null;
}

/**
 * Get user email
 * @return string|null User's email or null if not found
 */
function getUserEmailByAuth() {
    $userInfo = getCurrentUser();
    return $userInfo ? $userInfo['email'] : null;
}

/**
 * Check if current user is authenticated
 * @return bool True if user is authenticated and found in members.json
 */
function isUserAuthenticated() {
    return isLoggedIn();
}
