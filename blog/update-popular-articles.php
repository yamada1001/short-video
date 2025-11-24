#!/usr/bin/env php
<?php
/**
 * GA4 Data APIから人気記事データを取得して保存
 *
 * 使い方:
 * 1. Google Cloud Platformでサービスアカウントを作成
 * 2. credentials.jsonをダウンロードしてこのファイルと同じディレクトリに配置
 * 3. GA4プロパティIDを設定（下記の GA4_PROPERTY_ID）
 * 4. php update-popular-articles.php を実行
 * 5. cronで定期実行: 0 3 * * * cd /path/to/blog && php update-popular-articles.php
 */

// 設定
define('GA4_PROPERTY_ID', 'properties/512709159');
define('CREDENTIALS_FILE', __DIR__ . '/credentials.json');
define('OUTPUT_FILE', __DIR__ . '/data/popular-articles.json');
define('POSTS_FILE', __DIR__ . '/data/posts.json');
define('TOP_N', 10); // 取得する人気記事数

/**
 * アクセストークンを取得
 */
function getAccessToken() {
    if (!file_exists(CREDENTIALS_FILE)) {
        throw new Exception('credentials.json が見つかりません。Google Cloud Platformからサービスアカウントキーをダウンロードしてください。');
    }

    $credentials = json_decode(file_get_contents(CREDENTIALS_FILE), true);

    // JWTを作成
    $now = time();
    $header = [
        'alg' => 'RS256',
        'typ' => 'JWT'
    ];

    $claim = [
        'iss' => $credentials['client_email'],
        'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
        'aud' => 'https://oauth2.googleapis.com/token',
        'exp' => $now + 3600,
        'iat' => $now
    ];

    $header_encoded = base64_url_encode(json_encode($header));
    $claim_encoded = base64_url_encode(json_encode($claim));
    $signature_input = $header_encoded . '.' . $claim_encoded;

    // 秘密鍵で署名
    $private_key = $credentials['private_key'];
    openssl_sign($signature_input, $signature, $private_key, 'SHA256');
    $signature_encoded = base64_url_encode($signature);

    $jwt = $signature_input . '.' . $signature_encoded;

    // アクセストークンをリクエスト
    $ch = curl_init('https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion' => $jwt
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        throw new Exception('アクセストークンの取得に失敗しました: ' . $response);
    }

    $data = json_decode($response, true);
    return $data['access_token'];
}

/**
 * Base64 URL-safe エンコード
 */
function base64_url_encode($data) {
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

/**
 * GA4 Data APIから人気記事を取得
 */
function getPopularArticles($access_token) {
    $url = 'https://analyticsdata.googleapis.com/v1beta/' . GA4_PROPERTY_ID . ':runReport';

    // 過去30日間の記事別ページビューを取得
    $request_body = [
        'dateRanges' => [
            [
                'startDate' => '30daysAgo',
                'endDate' => 'today'
            ]
        ],
        'dimensions' => [
            ['name' => 'pagePath']
        ],
        'metrics' => [
            ['name' => 'screenPageViews'],
            ['name' => 'activeUsers']
        ],
        'dimensionFilter' => [
            'filter' => [
                'fieldName' => 'pagePath',
                'stringFilter' => [
                    'matchType' => 'CONTAINS',
                    'value' => '/blog/detail.php?slug='
                ]
            ]
        ],
        'orderBys' => [
            [
                'metric' => [
                    'metricName' => 'screenPageViews'
                ],
                'desc' => true
            ]
        ],
        'limit' => TOP_N
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_body));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        throw new Exception('GA4 Data APIからのデータ取得に失敗しました: ' . $response);
    }

    return json_decode($response, true);
}

/**
 * slugから記事IDを取得
 */
function getArticleIdBySlug($slug, $posts) {
    foreach ($posts as $post) {
        if ($post['slug'] === $slug) {
            return $post['id'];
        }
    }
    return null;
}

/**
 * メイン処理
 */
try {
    echo "人気記事の更新を開始します...\n";

    // posts.jsonを読み込み
    if (!file_exists(POSTS_FILE)) {
        throw new Exception('posts.json が見つかりません。');
    }
    $posts_data = json_decode(file_get_contents(POSTS_FILE), true);
    $posts = $posts_data['posts'];

    echo "アクセストークンを取得中...\n";
    $access_token = getAccessToken();

    echo "GA4 Data APIから人気記事を取得中...\n";
    $ga_data = getPopularArticles($access_token);

    // 結果を整形
    $popular_articles = [];

    if (isset($ga_data['rows'])) {
        foreach ($ga_data['rows'] as $row) {
            $page_path = $row['dimensionValues'][0]['value'];
            $page_views = (int)$row['metricValues'][0]['value'];
            $users = (int)$row['metricValues'][1]['value'];

            // slugを抽出
            if (preg_match('/slug=([^&]+)/', $page_path, $matches)) {
                $slug = urldecode($matches[1]);
                $article_id = getArticleIdBySlug($slug, $posts);

                if ($article_id) {
                    $popular_articles[] = [
                        'id' => $article_id,
                        'slug' => $slug,
                        'page_views' => $page_views,
                        'users' => $users
                    ];
                }
            }
        }
    }

    // JSONファイルに保存
    $output_data = [
        'updated_at' => date('Y-m-d H:i:s'),
        'period' => '過去30日間',
        'articles' => $popular_articles
    ];

    file_put_contents(OUTPUT_FILE, json_encode($output_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    echo "✅ 人気記事の更新が完了しました！\n";
    echo "取得した人気記事数: " . count($popular_articles) . "件\n";
    echo "保存先: " . OUTPUT_FILE . "\n";

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
    exit(1);
}
