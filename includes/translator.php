<?php
/**
 * 自動翻訳システム
 * 日本語ページを英語に自動翻訳し、キャッシュを管理
 */

class Translator {
    private $cacheDir;
    private $apiKey;
    private $dictionaries;

    public function __construct() {
        $this->cacheDir = __DIR__ . '/../cache/translations/';

        // キャッシュディレクトリが存在しない場合は作成
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }

        // Google Cloud Translation API Key（環境変数から取得）
        $this->apiKey = getenv('GOOGLE_TRANSLATE_API_KEY') ?: '';

        // ビジネス用語辞書を読み込み
        $this->loadDictionaries();
    }

    /**
     * ビジネス用語・専門用語の辞書を読み込み
     */
    private function loadDictionaries() {
        $dictionaryFile = __DIR__ . '/translation_dictionary.json';
        if (file_exists($dictionaryFile)) {
            $this->dictionaries = json_decode(file_get_contents($dictionaryFile), true);
        } else {
            // デフォルト辞書
            $this->dictionaries = [
                '余日' => 'Yojitsu',
                'Yojitsu' => 'Yojitsu',
                'Web制作' => 'Web Development',
                'ショート動画制作' => 'Short Video Production',
                'デジタルマーケティング' => 'Digital Marketing',
                '大分' => 'Oita',
                '大分県' => 'Oita Prefecture',
                'お問い合わせ' => 'Contact Us',
                'サービス' => 'Services',
                '会社概要' => 'About Us',
                'ブログ' => 'Blog',
                'お知らせ' => 'News',
                'プライバシーポリシー' => 'Privacy Policy',
                '特定商取引法' => 'Commercial Transaction Act',
                '無料相談' => 'Free Consultation',
                '採用情報' => 'Careers',
                '株式会社' => 'Corporation',
                'ホームページ' => 'Website',
            ];
        }
    }

    /**
     * テキストを翻訳（辞書 + Google Translate API）
     *
     * @param string $text 翻訳するテキスト
     * @param string $targetLang ターゲット言語（デフォルト: en）
     * @return string 翻訳されたテキスト
     */
    public function translate($text, $targetLang = 'en') {
        if (empty($text)) {
            return '';
        }

        // 辞書で置き換え可能な用語をチェック
        $protectedTerms = [];
        $placeholder = [];
        $index = 0;

        foreach ($this->dictionaries as $ja => $en) {
            if (strpos($text, $ja) !== false) {
                $placeholderKey = "___PROTECTED_TERM_{$index}___";
                $protectedTerms[$placeholderKey] = $en;
                $text = str_replace($ja, $placeholderKey, $text);
                $index++;
            }
        }

        // Google Translate APIで翻訳
        if (!empty($this->apiKey)) {
            $translatedText = $this->translateWithGoogleAPI($text, $targetLang);
        } else {
            // APIキーがない場合は簡易翻訳（辞書のみ）
            $translatedText = $text;
        }

        // プレースホルダーを元に戻す
        foreach ($protectedTerms as $placeholder => $translation) {
            $translatedText = str_replace($placeholder, $translation, $translatedText);
        }

        return $translatedText;
    }

    /**
     * Google Cloud Translation APIで翻訳
     *
     * @param string $text 翻訳するテキスト
     * @param string $targetLang ターゲット言語
     * @return string 翻訳されたテキスト
     */
    private function translateWithGoogleAPI($text, $targetLang) {
        $url = 'https://translation.googleapis.com/language/translate/v2';

        $data = [
            'q' => $text,
            'target' => $targetLang,
            'source' => 'ja',
            'format' => 'html',
            'key' => $this->apiKey
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);
            if (isset($result['data']['translations'][0]['translatedText'])) {
                return $result['data']['translations'][0]['translatedText'];
            }
        }

        // エラーの場合は元のテキストを返す
        return $text;
    }

    /**
     * HTMLファイル全体を翻訳
     *
     * @param string $htmlContent HTML内容
     * @param string $targetLang ターゲット言語
     * @return string 翻訳されたHTML
     */
    public function translateHTML($htmlContent, $targetLang = 'en') {
        // DOMDocumentを使用してHTMLをパース
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // テキストノードのみを翻訳
        $this->translateNode($dom->documentElement, $targetLang);

        return $dom->saveHTML();
    }

    /**
     * DOMノードを再帰的に翻訳
     *
     * @param DOMNode $node
     * @param string $targetLang
     */
    private function translateNode($node, $targetLang) {
        if ($node->nodeType === XML_TEXT_NODE) {
            $text = trim($node->nodeValue);
            if (!empty($text)) {
                $node->nodeValue = $this->translate($text, $targetLang);
            }
        } else if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $child) {
                $this->translateNode($child, $targetLang);
            }
        }

        // 特定の属性も翻訳（title, alt, placeholderなど）
        if ($node->nodeType === XML_ELEMENT_NODE) {
            $attributes = ['title', 'alt', 'placeholder', 'aria-label'];
            foreach ($attributes as $attr) {
                if ($node->hasAttribute($attr)) {
                    $value = $node->getAttribute($attr);
                    if (!empty($value)) {
                        $node->setAttribute($attr, $this->translate($value, $targetLang));
                    }
                }
            }
        }
    }

    /**
     * ページのキャッシュを取得
     *
     * @param string $pageUrl ページURL（例: /index.php）
     * @param string $lang 言語コード
     * @return string|null キャッシュされたHTML、またはnull
     */
    public function getCache($pageUrl, $lang = 'en') {
        $cacheFile = $this->getCacheFilePath($pageUrl, $lang);

        if (file_exists($cacheFile)) {
            // キャッシュの有効期限をチェック（24時間）
            $cacheTime = filemtime($cacheFile);
            if (time() - $cacheTime < 86400) {
                return file_get_contents($cacheFile);
            }
        }

        return null;
    }

    /**
     * ページをキャッシュに保存
     *
     * @param string $pageUrl ページURL
     * @param string $content 翻訳されたHTML
     * @param string $lang 言語コード
     */
    public function setCache($pageUrl, $content, $lang = 'en') {
        $cacheFile = $this->getCacheFilePath($pageUrl, $lang);

        // ディレクトリが存在しない場合は作成
        $dir = dirname($cacheFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($cacheFile, $content);
    }

    /**
     * キャッシュファイルのパスを取得
     *
     * @param string $pageUrl ページURL
     * @param string $lang 言語コード
     * @return string キャッシュファイルパス
     */
    private function getCacheFilePath($pageUrl, $lang) {
        $pageUrl = trim($pageUrl, '/');
        $pageUrl = str_replace('/', '_', $pageUrl);

        if (empty($pageUrl)) {
            $pageUrl = 'index';
        }

        return $this->cacheDir . $lang . '/' . $pageUrl . '.html';
    }

    /**
     * キャッシュをクリア
     *
     * @param string|null $pageUrl 特定のページ（nullの場合は全キャッシュ）
     * @param string $lang 言語コード
     */
    public function clearCache($pageUrl = null, $lang = 'en') {
        if ($pageUrl === null) {
            // 全キャッシュをクリア
            $langDir = $this->cacheDir . $lang . '/';
            if (is_dir($langDir)) {
                $files = glob($langDir . '*.html');
                foreach ($files as $file) {
                    unlink($file);
                }
            }
        } else {
            // 特定のページのキャッシュをクリア
            $cacheFile = $this->getCacheFilePath($pageUrl, $lang);
            if (file_exists($cacheFile)) {
                unlink($cacheFile);
            }
        }
    }
}
