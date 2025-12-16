<?php
/**
 * ヘルパー関数
 */

/**
 * ランダムトークン生成（64文字）
 */
function generateToken(): string {
    return bin2hex(random_bytes(32));
}

/**
 * 日付が過去かチェック
 */
function isPast(string $datetime): bool {
    return strtotime($datetime) < time();
}

/**
 * 日付が未来かチェック
 */
function isFuture(string $datetime): bool {
    return strtotime($datetime) > time();
}

/**
 * 2つの日時の差分を取得（分単位）
 */
function getMinutesDiff(string $datetime1, string $datetime2): int {
    $timestamp1 = strtotime($datetime1);
    $timestamp2 = strtotime($datetime2);
    return abs($timestamp1 - $timestamp2) / 60;
}

/**
 * 金額フォーマット（円）
 */
function formatPrice(int $price): string {
    return '¥' . number_format($price);
}

/**
 * メールアドレスバリデーション
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * 電話番号フォーマット（ハイフンなし）
 */
function formatPhoneNumber(?string $phone): string {
    if (!$phone) return '';
    return preg_replace('/[^0-9]/', '', $phone);
}

/**
 * CSRFトークン生成
 */
function generateCsrfToken(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRFトークン検証
 */
function verifyCsrfToken(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * POSTデータ取得
 */
function post(string $key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * GETデータ取得
 */
function get(string $key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * セッションデータ取得
 */
function session(string $key, $default = null) {
    return $_SESSION[$key] ?? $default;
}

/**
 * セッションデータ設定
 */
function setSession(string $key, $value): void {
    $_SESSION[$key] = $value;
}

/**
 * フラッシュメッセージ設定
 */
function setFlash(string $type, string $message): void {
    $_SESSION['flash'] = [
        'type' => $type, // success, error, warning, info
        'message' => $message
    ];
}

/**
 * フラッシュメッセージ取得
 */
function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * 曜日取得（日本語）
 */
function getWeekday(string $date): string {
    $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
    return $weekdays[date('w', strtotime($date))];
}

/**
 * ステータスラベル取得
 */
function getStatusLabel(string $status): string {
    $labels = [
        'applied' => '申込済',
        'absent' => '欠席',
        'paid' => '支払済',
        'attended' => '出席済'
    ];
    return $labels[$status] ?? $status;
}

/**
 * ステータスバッジクラス取得
 */
function getStatusBadgeClass(string $status): string {
    $classes = [
        'applied' => 'badge-warning',
        'absent' => 'badge-muted',
        'paid' => 'badge-info',
        'attended' => 'badge-success'
    ];
    return $classes[$status] ?? 'badge-default';
}

/**
 * ファイル拡張子取得
 */
function getFileExtension(string $filename): string {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * PDFファイルかチェック
 */
function isPdfFile(string $filename): bool {
    return getFileExtension($filename) === 'pdf';
}

/**
 * ファイルサイズをフォーマット（MB）
 */
function formatFileSize(int $bytes): string {
    $mb = $bytes / 1024 / 1024;
    return number_format($mb, 2) . ' MB';
}

/**
 * URLからファイル名取得
 */
function getFilenameFromPath(string $path): string {
    return basename($path);
}

/**
 * 配列から特定のキーの値を抽出
 */
function pluck(array $array, string $key): array {
    return array_column($array, $key);
}

/**
 * 配列が空かチェック
 */
function isEmpty($value): bool {
    if (is_array($value)) {
        return empty($value);
    }
    return $value === null || $value === '';
}

/**
 * 文字列がJSONかチェック
 */
function isJson(string $string): bool {
    json_decode($string);
    return json_last_error() === JSON_ERROR_NONE;
}
