<?php
/**
 * 共通関数
 * サイト全体で使用するユーティリティ関数
 */

/**
 * HTMLエスケープ
 * @param string $str 文字列
 * @return string エスケープされた文字列
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * 現在のページがアクティブかチェック
 * @param string $page ページ名
 * @return string アクティブクラス
 */
function is_current_page($page) {
    $current = basename($_SERVER['PHP_SELF'], '.php');
    return ($current === $page) ? 'active' : '';
}

/**
 * 日付をフォーマット
 * @param string $date 日付文字列（YYYY-MM-DD）
 * @param string $format フォーマット
 * @return string フォーマットされた日付
 */
function format_date($date, $format = 'Y年m月d日') {
    $timestamp = strtotime($date);
    return date($format, $timestamp);
}

/**
 * 日付を和暦表示に変換
 * @param string $date 日付文字列（YYYY-MM-DD）
 * @return string 和暦表示
 */
function format_date_jp($date) {
    $timestamp = strtotime($date);
    $year = date('Y', $timestamp);
    $month = date('n', $timestamp);
    $day = date('j', $timestamp);

    // 令和変換（2019年5月1日以降）
    if ($year >= 2019) {
        $reiwa_year = $year - 2018;
        if ($year == 2019 && $month < 5) {
            $era_year = $year - 1988;
            return "平成{$era_year}年{$month}月{$day}日";
        }
        return "令和{$reiwa_year}年{$month}月{$day}日";
    }

    // 平成変換（1989〜2019年4月）
    $era_year = $year - 1988;
    return "平成{$era_year}年{$month}月{$day}日";
}

/**
 * カテゴリーのクラス名を取得
 * @param string $category カテゴリー名
 * @return string クラス名
 */
function get_category_class($category) {
    $classes = [
        'お知らせ' => 'category-news',
        'キャンペーン' => 'category-campaign',
        '営業情報' => 'category-business'
    ];
    return $classes[$category] ?? 'category-other';
}

/**
 * 電話番号をフォーマット
 * @param string $phone 電話番号
 * @param string $format フォーマット（'display' or 'tel'）
 * @return string フォーマットされた電話番号
 */
function format_phone($phone, $format = 'display') {
    if ($format === 'tel') {
        // tel:リンク用（ハイフンなし）
        return preg_replace('/[^0-9]/', '', $phone);
    }
    // 表示用
    return $phone;
}

/**
 * 営業日かチェック
 * @return bool 営業日ならtrue
 */
function is_business_day() {
    $day_of_week = date('w'); // 0:日曜 6:土曜
    return ($day_of_week !== 0); // 日曜日以外は営業
}

/**
 * 営業時間内かチェック
 * @return bool 営業時間内ならtrue
 */
function is_business_hours() {
    $current_hour = (int)date('H');
    return ($current_hour >= 9 && $current_hour < 18);
}

/**
 * 画像パスを取得（WebP対応）
 * @param string $path 画像パス
 * @return string 画像パス
 */
function get_image_path($path) {
    // WebP対応ブラウザならWebP画像を返す
    $webp_path = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
    if (file_exists($webp_path)) {
        return $webp_path;
    }
    return $path;
}

/**
 * トランケート（文字数制限）
 * @param string $str 文字列
 * @param int $length 文字数
 * @param string $suffix 省略記号
 * @return string トランケートされた文字列
 */
function truncate($str, $length = 100, $suffix = '...') {
    if (mb_strlen($str) > $length) {
        return mb_substr($str, 0, $length) . $suffix;
    }
    return $str;
}

/**
 * アセットパスを取得
 * @param string $path アセットのパス
 * @return string 絶対パス
 */
function asset($path) {
    $base_path = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    return $base_path . '/' . ltrim($path, '/');
}

/**
 * URLを生成
 * @param string $page ページ名（拡張子なし）
 * @return string URL
 */
function url($page = '') {
    $base_url = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
    if (empty($page)) {
        return $base_url . '/';
    }
    return $base_url . '/' . $page . '.php';
}

/**
 * リダイレクト
 * @param string $url リダイレクト先URL
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * CSRFトークンを生成
 * @return string トークン
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRFトークンを検証
 * @param string $token トークン
 * @return bool 検証結果
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * サニタイズ（入力値の浄化）
 * @param string $str 文字列
 * @return string サニタイズされた文字列
 */
function sanitize($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

/**
 * メールアドレスのバリデーション
 * @param string $email メールアドレス
 * @return bool バリデーション結果
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * 電話番号のバリデーション（日本）
 * @param string $phone 電話番号
 * @return bool バリデーション結果
 */
function validate_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return preg_match('/^0\d{9,10}$/', $phone);
}
