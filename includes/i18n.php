<?php
/**
 * 多言語化（i18n）サポート
 */

// 現在の言語を取得
function getCurrentLang() {
    if (defined('CURRENT_LANG')) {
        return CURRENT_LANG;
    }
    return 'ja'; // デフォルトは日本語
}

// 翻訳データをロード
$translations = [];
$currentLang = getCurrentLang();

if ($currentLang !== 'ja') {
    $translationFile = __DIR__ . '/translations/' . $currentLang . '.json';
    if (file_exists($translationFile)) {
        $translations = json_decode(file_get_contents($translationFile), true) ?? [];
    }
}

/**
 * テキストを翻訳
 *
 * @param string $key 翻訳キー（日本語テキスト）
 * @param array $params 置換パラメータ
 * @return string 翻訳されたテキスト
 */
function t($key, $params = []) {
    global $translations;

    // 翻訳データから検索
    $text = $translations[$key] ?? $key;

    // パラメータ置換
    if (!empty($params)) {
        foreach ($params as $param => $value) {
            $text = str_replace('{' . $param . '}', $value, $text);
        }
    }

    return $text;
}

/**
 * テキストを翻訳して出力
 *
 * @param string $key 翻訳キー
 * @param array $params 置換パラメータ
 */
function te($key, $params = []) {
    echo t($key, $params);
}

/**
 * 現在のURLの言語切り替え版を取得
 *
 * @param string $targetLang ターゲット言語
 * @return string URL
 */
function getLangUrl($targetLang = 'en') {
    $currentUrl = $_SERVER['REQUEST_URI'];
    $currentLang = getCurrentLang();

    if ($currentLang === 'ja' && $targetLang === 'en') {
        // 日本語 → 英語
        return '/en' . $currentUrl;
    } elseif ($currentLang === 'en' && $targetLang === 'ja') {
        // 英語 → 日本語
        return str_replace('/en/', '/', str_replace('/en', '', $currentUrl));
    }

    return $currentUrl;
}

/**
 * リンクのURLを言語に応じて調整
 *
 * @param string $url 元のURL
 * @return string 調整されたURL
 */
function langUrl($url) {
    $currentLang = getCurrentLang();

    if ($currentLang === 'en') {
        // 英語版の場合、内部リンクに /en を追加
        if (!str_starts_with($url, 'http') && !str_starts_with($url, 'mailto:') && !str_starts_with($url, 'tel:')) {
            // 既に /en/ がある場合はそのまま
            if (!str_starts_with($url, '/en/')) {
                return '/en' . $url;
            }
        }
    }

    return $url;
}
