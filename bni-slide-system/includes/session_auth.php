<?php
/**
 * BNI Slide System - Session Authentication Helper (SQLite Version)
 * セッションベースの認証ヘルパー
 */

require_once __DIR__ . '/db.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_email']) && isset($_SESSION['user_name']);
}

/**
 * Require login (redirect to login page if not logged in)
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $currentUrl = $_SERVER['REQUEST_URI'];
        header('Location: /bni-slide-system/login.php?redirect=' . urlencode($currentUrl));
        exit;
    }
}

/**
 * Get current logged-in user info
 */
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }

    try {
        $db = getDbConnection();
        $email = $_SESSION['user_email'];

        $query = "SELECT id, name, email, role, company, industry, is_active
                  FROM users
                  WHERE email = :email AND is_active = 1
                  LIMIT 1";

        $user = dbQueryOne($db, $query, [':email' => $email]);
        dbClose($db);

        return $user;
    } catch (Exception $e) {
        if (isset($db)) {
            dbClose($db);
        }
        return null;
    }
}

/**
 * Login user
 */
function loginUser($email, $password) {
    try {
        $db = getDbConnection();

        // Get user from database
        $query = "SELECT id, name, email, password_hash, role, is_active, require_2fa, totp_secret
                  FROM users
                  WHERE email = :email
                  LIMIT 1";

        $user = dbQueryOne($db, $query, [':email' => $email]);

        if (!$user) {
            dbClose($db);
            return ['success' => false, 'message' => 'メールアドレスまたはパスワードが正しくありません'];
        }

        // Check if account is active
        if (!$user['is_active']) {
            dbClose($db);
            return ['success' => false, 'message' => 'このアカウントは無効化されています'];
        }

        // Verify password
        if (!password_verify($password, $user['password_hash'])) {
            dbClose($db);
            return ['success' => false, 'message' => 'メールアドレスまたはパスワードが正しくありません'];
        }

        // Check if 2FA is required
        if ($user['require_2fa'] && !empty($user['totp_secret'])) {
            // Set temporary session for 2FA verification
            $_SESSION['2fa_user_email'] = $email;
            $_SESSION['2fa_user_name'] = $user['name'];
            dbClose($db);
            return [
                'success' => true,
                'require_2fa' => true,
                'message' => '2段階認証が必要です'
            ];
        }

        // Set session
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['login_time'] = time();

        // Update last login
        $updateQuery = "UPDATE users SET last_login = :last_login WHERE id = :id";
        dbExecute($db, $updateQuery, [
            ':last_login' => date('Y-m-d H:i:s'),
            ':id' => $user['id']
        ]);

        dbClose($db);

        return ['success' => true, 'message' => 'ログインしました'];

    } catch (Exception $e) {
        if (isset($db)) {
            dbClose($db);
        }
        return ['success' => false, 'message' => 'システムエラーが発生しました'];
    }
}

/**
 * Verify 2FA code and complete login
 */
function verify2FAAndLogin($email, $code) {
    try {
        $db = getDbConnection();

        // Get user
        $query = "SELECT id, name, totp_secret FROM users WHERE email = :email LIMIT 1";
        $user = dbQueryOne($db, $query, [':email' => $email]);

        if (!$user || empty($user['totp_secret'])) {
            dbClose($db);
            return ['success' => false, 'message' => '認証に失敗しました'];
        }

        // Verify TOTP code
        require_once __DIR__ . '/totp.php';
        if (!verifyTOTP($user['totp_secret'], $code)) {
            dbClose($db);
            return ['success' => false, 'message' => '認証コードが正しくありません'];
        }

        // Set session
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['login_time'] = time();

        // Clear temporary 2FA session
        unset($_SESSION['2fa_user_email']);
        unset($_SESSION['2fa_user_name']);

        // Update last login
        $updateQuery = "UPDATE users SET last_login = :last_login WHERE id = :id";
        dbExecute($db, $updateQuery, [
            ':last_login' => date('Y-m-d H:i:s'),
            ':id' => $user['id']
        ]);

        dbClose($db);

        return ['success' => true, 'message' => 'ログインしました'];

    } catch (Exception $e) {
        if (isset($db)) {
            dbClose($db);
        }
        return ['success' => false, 'message' => 'システムエラーが発生しました'];
    }
}

/**
 * Logout user
 */
function logoutUser() {
    session_unset();
    session_destroy();
}
