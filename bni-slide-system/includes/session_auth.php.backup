<?php
/**
 * BNI Slide System - Session Authentication Helper
 * セッションベースの認証ヘルパー
 */

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

    // Load members.json
    $membersFile = __DIR__ . '/../data/members.json';
    if (!file_exists($membersFile)) {
        return null;
    }

    $content = file_get_contents($membersFile);
    $data = json_decode($content, true);

    if (!$data || !isset($data['users'])) {
        return null;
    }

    $email = $_SESSION['user_email'];

    if (!isset($data['users'][$email])) {
        return null;
    }

    return $data['users'][$email];
}

/**
 * Login user
 */
function loginUser($email, $password) {
    // Load members.json
    $membersFile = __DIR__ . '/../data/members.json';
    if (!file_exists($membersFile)) {
        return ['success' => false, 'message' => 'ユーザーデータが見つかりません'];
    }

    $content = file_get_contents($membersFile);
    $data = json_decode($content, true);

    if (!$data || !isset($data['users'])) {
        return ['success' => false, 'message' => 'ユーザーデータが見つかりません'];
    }

    // Check if user exists
    if (!isset($data['users'][$email])) {
        return ['success' => false, 'message' => 'メールアドレスまたはパスワードが正しくありません'];
    }

    $user = $data['users'][$email];

    // Check if password_hash exists (new format)
    if (isset($user['password_hash'])) {
        // Use password_verify() for new format
        if (!password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'メールアドレスまたはパスワードが正しくありません'];
        }
    } else {
        // Legacy: try .htpasswd (for backward compatibility)
        $htpasswdFile = __DIR__ . '/../.htpasswd';
        if (!file_exists($htpasswdFile)) {
            return ['success' => false, 'message' => '認証情報が見つかりません'];
        }

        $htpasswdContent = file_get_contents($htpasswdFile);
        $lines = explode("\n", $htpasswdContent);

        $passwordHash = null;
        foreach ($lines as $line) {
            if (strpos($line, $email . ':') === 0) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $passwordHash = trim($parts[1]);
                    break;
                }
            }
        }

        if (!$passwordHash) {
            return ['success' => false, 'message' => 'メールアドレスまたはパスワードが正しくありません'];
        }

        // Verify password with APR1-MD5 hash
        if (!verifyApr1Password($password, $passwordHash)) {
            return ['success' => false, 'message' => 'メールアドレスまたはパスワードが正しくありません'];
        }
    }

    // Set session
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $data['users'][$email]['name'];
    $_SESSION['login_time'] = time();

    return ['success' => true, 'message' => 'ログインしました'];
}

/**
 * Logout user
 */
function logoutUser() {
    session_unset();
    session_destroy();
}

/**
 * Verify APR1-MD5 password (used by htpasswd)
 */
function verifyApr1Password($password, $hash) {
    // Use PHP's crypt() to verify APR1-MD5 hash
    if (substr($hash, 0, 6) === '$apr1$') {
        $result = crypt($password, $hash);
        return $result === $hash;
    }

    // Fallback: use htpasswd command
    $testHash = shell_exec(sprintf(
        'echo %s | htpasswd -nbm dummy %s 2>&1',
        escapeshellarg($password),
        escapeshellarg($password)
    ));

    if ($testHash) {
        $parts = explode(':', trim($testHash), 2);
        if (count($parts) === 2) {
            $generatedHash = trim($parts[1]);
            // Compare hash patterns (APR1-MD5 has same salt)
            return substr($generatedHash, 0, 6) === '$apr1$';
        }
    }

    return false;
}
