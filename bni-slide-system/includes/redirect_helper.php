<?php
/**
 * Redirect Helper
 * オープンリダイレクト脆弱性対策
 */

/**
 * リダイレクト先URLを検証
 * 外部URLや不正なパスを拒否し、安全なURLのみを許可
 *
 * @param string $url リダイレクト先URL
 * @param string $default デフォルトのリダイレクト先
 * @return string 検証済みのリダイレクト先URL
 */
function validateRedirectUrl($url, $default = '/bni-slide-system/index.php') {
    // 空の場合はデフォルト
    if (empty($url)) {
        return $default;
    }

    // URLをパース
    $parsed = parse_url($url);

    // 外部URLを拒否（scheme または host が設定されている場合）
    if (isset($parsed['scheme']) || isset($parsed['host'])) {
        return $default;
    }

    // 許可されたパスのプレフィックス
    $allowedPaths = [
        '/bni-slide-system/',
        '../'  // 相対パスも許可（同じディレクトリ内のみ）
    ];

    // パスが許可リストに含まれているかチェック
    foreach ($allowedPaths as $allowedPath) {
        if (strpos($url, $allowedPath) === 0) {
            return $url;
        }
    }

    // 許可されていないパスの場合はデフォルト
    return $default;
}

/**
 * 安全にリダイレクト
 * validateRedirectUrl()を使用してリダイレクト先を検証してからリダイレクト
 *
 * @param string $url リダイレクト先URL
 * @param string $default デフォルトのリダイレクト先
 */
function safeRedirect($url, $default = '/bni-slide-system/index.php') {
    $safeUrl = validateRedirectUrl($url, $default);
    header('Location: ' . $safeUrl);
    exit;
}
