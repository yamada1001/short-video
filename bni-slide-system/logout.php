<?php
/**
 * BNI Slide System - Logout
 * ログアウト処理
 */

// Load session auth helper
require_once __DIR__ . '/includes/session_auth.php';

// Logout
logoutUser();

// Redirect to login page
header('Location: /bni-slide-system/login.php');
exit;
