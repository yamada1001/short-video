<?php
/**
 * DeepL API 翻訳機能
 */

// config.phpから設定を読み込み（未読み込みの場合のみ）
if (!defined('DEEPL_API_KEY')) {
    require_once __DIR__ . '/config.php';
}

// キャッシュディレクトリ設定
define('TRANSLATION_CACHE_DIR', __DIR__ . '/../cache/translations');

/**
 * テキストを翻訳する
 *
 * @param string $text 翻訳するテキスト
 * @param string $targetLang ターゲット言語（デフォルト: EN）
 * @param string $sourceLang ソース言語（デフォルト: JA）
 * @return string 翻訳されたテキスト
 */
function translateText($text, $targetLang = 'EN', $sourceLang = 'JA') {
    if (empty($text)) {
        return $text;
    }

    // キャッシュチェック
    $cacheKey = md5($text . $targetLang . $sourceLang);
    $cachedTranslation = getTranslationCache($cacheKey);
    if ($cachedTranslation !== false) {
        return $cachedTranslation;
    }

    // DeepL APIリクエスト
    $postData = [
        'auth_key' => DEEPL_API_KEY,
        'text' => $text,
        'source_lang' => $sourceLang,
        'target_lang' => $targetLang,
        'tag_handling' => 'html'
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => DEEPL_API_URL,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($postData),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded'
        ]
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        // エラー時は元のテキストを返す
        error_log("DeepL API Error: HTTP $httpCode - $response");
        return $text;
    }

    $result = json_decode($response, true);
    if (!isset($result['translations'][0]['text'])) {
        return $text;
    }

    $translatedText = $result['translations'][0]['text'];

    // キャッシュに保存
    setTranslationCache($cacheKey, $translatedText);

    return $translatedText;
}

/**
 * 記事データを翻訳する
 *
 * @param array $post 記事データ
 * @return array 翻訳された記事データ
 */
function translatePost($post) {
    // キャッシュキーを記事ID+更新日時で生成
    $cacheKey = 'post_' . $post['id'] . '_' . md5($post['updatedAt']);
    $cachedPost = getPostTranslationCache($cacheKey);
    if ($cachedPost !== false) {
        return $cachedPost;
    }

    $translatedPost = $post;

    // タイトルを翻訳
    $translatedPost['title'] = translateText($post['title']);

    // 抜粋を翻訳
    $translatedPost['excerpt'] = translateText($post['excerpt']);

    // カテゴリを翻訳
    $translatedPost['category'] = translateText($post['category']);

    // タグを翻訳
    if (!empty($post['tags'])) {
        $translatedPost['tags'] = array_map(function($tag) {
            return translateText($tag);
        }, $post['tags']);
    }

    // キャッシュに保存
    setPostTranslationCache($cacheKey, $translatedPost);

    return $translatedPost;
}

/**
 * 記事コンテンツを翻訳する
 *
 * @param string $content HTML記事コンテンツ
 * @param int $postId 記事ID
 * @param string $updatedAt 更新日時
 * @return string 翻訳されたコンテンツ
 */
function translateContent($content, $postId, $updatedAt) {
    $cacheKey = 'content_' . $postId . '_' . md5($updatedAt);
    $cachedContent = getTranslationCache($cacheKey);
    if ($cachedContent !== false) {
        return $cachedContent;
    }

    // HTMLコンテンツを翻訳（DeepLはHTMLタグを保持）
    $translatedContent = translateText($content);

    // キャッシュに保存
    setTranslationCache($cacheKey, $translatedContent);

    return $translatedContent;
}

/**
 * 翻訳キャッシュを取得
 */
function getTranslationCache($key) {
    $cacheFile = TRANSLATION_CACHE_DIR . '/' . $key . '.txt';
    if (file_exists($cacheFile)) {
        $content = file_get_contents($cacheFile);
        // 空のキャッシュは無効とみなす
        if (!empty(trim($content))) {
            return $content;
        }
    }
    return false;
}

/**
 * 翻訳キャッシュを保存
 */
function setTranslationCache($key, $text) {
    if (!is_dir(TRANSLATION_CACHE_DIR)) {
        mkdir(TRANSLATION_CACHE_DIR, 0755, true);
    }
    $cacheFile = TRANSLATION_CACHE_DIR . '/' . $key . '.txt';
    file_put_contents($cacheFile, $text);
}

/**
 * 記事翻訳キャッシュを取得
 */
function getPostTranslationCache($key) {
    $cacheFile = TRANSLATION_CACHE_DIR . '/' . $key . '.json';
    if (file_exists($cacheFile)) {
        return json_decode(file_get_contents($cacheFile), true);
    }
    return false;
}

/**
 * 記事翻訳キャッシュを保存
 */
function setPostTranslationCache($key, $post) {
    if (!is_dir(TRANSLATION_CACHE_DIR)) {
        mkdir(TRANSLATION_CACHE_DIR, 0755, true);
    }
    $cacheFile = TRANSLATION_CACHE_DIR . '/' . $key . '.json';
    file_put_contents($cacheFile, json_encode($post, JSON_UNESCAPED_UNICODE));
}

/**
 * キャッシュをクリア
 */
function clearTranslationCache() {
    $files = glob(TRANSLATION_CACHE_DIR . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
}
