<?php
/**
 * ヘルパー関数
 * グローバルに使用できるユーティリティ関数
 */

if (!function_exists('h')) {
    /**
     * HTMLエスケープ
     * XSS対策
     *
     * @param string $str エスケープする文字列
     * @return string エスケープされた文字列
     */
    function h(?string $str): string {
        if ($str === null) {
            return '';
        }
        return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

if (!function_exists('redirect')) {
    /**
     * リダイレクト
     *
     * @param string $url リダイレクト先URL
     * @param int $code HTTPステータスコード（デフォルト: 302）
     * @return void
     */
    function redirect(string $url, int $code = 302): void {
        header("Location: {$url}", true, $code);
        exit;
    }
}

if (!function_exists('getCurrentWeek')) {
    /**
     * 今週の火曜日を取得
     * BNI定例会は毎週火曜日のため
     *
     * @return string Y-m-d形式の日付
     */
    function getCurrentWeek(): string {
        return date('Y-m-d', strtotime('this tuesday'));
    }
}

if (!function_exists('getWeekLabel')) {
    /**
     * 週のラベル取得（例: 2025年12月17日週）
     *
     * @param string $weekOf Y-m-d形式の火曜日
     * @return string 週ラベル
     */
    function getWeekLabel(string $weekOf): string {
        return date('Y年m月d日', strtotime($weekOf)) . '週';
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * 日時フォーマット
     *
     * @param string $datetime
     * @param string $format
     * @return string
     */
    function formatDateTime(?string $datetime, string $format = 'Y/m/d H:i'): string {
        if ($datetime === null) {
            return '-';
        }
        return date($format, strtotime($datetime));
    }
}

if (!function_exists('dd')) {
    /**
     * デバッグダンプ（開発用）
     *
     * @param mixed ...$vars
     * @return void
     */
    function dd(...$vars): void {
        echo '<pre>';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        exit;
    }
}

if (!function_exists('env')) {
    /**
     * 環境変数取得
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}
