<?php
/**
 * CSRF Protection
 * クロスサイトリクエストフォージェリ対策
 */

/**
 * CSRFトークンを生成
 * セッションにトークンを保存し、返す
 */
function generateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * CSRFトークンを検証
 * POSTリクエストの場合、トークンが一致するか確認
 *
 * @return bool トークンが有効な場合true
 */
function validateCSRFToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // GETリクエストはチェック不要
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true;
    }

    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $requestToken = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    // トークンが空の場合は無効
    if (empty($sessionToken) || empty($requestToken)) {
        return false;
    }

    // タイミング攻撃対策のためhash_equalsを使用
    return hash_equals($sessionToken, $requestToken);
}

/**
 * CSRFトークンを検証し、無効な場合は403エラーを返す
 * POSTエンドポイントの冒頭で呼び出す
 */
function requireCSRFToken() {
    if (!validateCSRFToken()) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => '不正なリクエストです。ページを再読み込みしてください。'
        ]);
        exit;
    }
}

/**
 * CSRFトークンをHTMLのhidden inputとして出力
 */
function csrfTokenField() {
    $token = generateCSRFToken();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * CSRFトークンを取得（JavaScript用）
 */
function getCSRFToken() {
    return generateCSRFToken();
}
