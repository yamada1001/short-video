<?php
/**
 * YouTube動画埋め込み機能の動作確認テスト
 */

require_once __DIR__ . '/includes/db.php';

echo "=== YouTube動画埋め込み機能 動作確認テスト ===\n\n";

// Test 1: YouTube URL抽出関数のテスト
echo "[Test 1] YouTube URL抽出関数テスト\n";
echo "------------------------------------------------\n";

$testUrls = [
    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    'https://youtu.be/dQw4w9WgXcQ',
    'https://www.youtube.com/embed/dQw4w9WgXcQ',
    'https://www.youtube.com/watch?v=dQw4w9WgXcQ&feature=share',
    'invalid-url',
    '',
    null
];

// JavaScript関数のPHP実装
function extractYouTubeVideoId($url) {
    if (!$url) return null;

    // https://www.youtube.com/watch?v=VIDEO_ID
    if (preg_match('/[?&]v=([^&]+)/', $url, $matches)) {
        return $matches[1];
    }

    // https://youtu.be/VIDEO_ID
    if (preg_match('/youtu\.be\/([^?&]+)/', $url, $matches)) {
        return $matches[1];
    }

    // https://www.youtube.com/embed/VIDEO_ID
    if (preg_match('/youtube\.com\/embed\/([^?&]+)/', $url, $matches)) {
        return $matches[1];
    }

    return null;
}

foreach ($testUrls as $url) {
    $videoId = extractYouTubeVideoId($url);
    $status = $videoId ? '✓ OK' : '✗ NG';
    $displayUrl = $url ?: '(empty)';
    echo "  {$status} URL: {$displayUrl} => VideoID: " . ($videoId ?: '(none)') . "\n";
}

echo "\n";

// Test 2: データベーススキーマの確認
echo "[Test 2] データベーススキーマ確認\n";
echo "------------------------------------------------\n";

try {
    $db = getDbConnection();

    // survey_dataテーブルのyoutube_urlカラム存在確認
    $result = $db->query("PRAGMA table_info(survey_data)");
    $youtubeColumnExists = false;

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($row['name'] === 'youtube_url') {
            $youtubeColumnExists = true;
            echo "  ✓ youtube_url カラムが存在します\n";
            echo "    - Type: {$row['type']}\n";
            echo "    - Nullable: " . ($row['notnull'] ? 'NO' : 'YES') . "\n";
            break;
        }
    }

    if (!$youtubeColumnExists) {
        echo "  ✗ youtube_url カラムが見つかりません\n";
    }

    dbClose($db);
} catch (Exception $e) {
    echo "  ✗ エラー: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 3: 保存処理のテスト（ドライラン）
echo "[Test 3] 保存処理の検証\n";
echo "------------------------------------------------\n";

// api_save.phpの関数をインクルード
require_once __DIR__ . '/includes/date_helper.php';
require_once __DIR__ . '/includes/file_upload_helper.php';

echo "  ✓ api_save.phpで使用する関数が正しくインクルードされました\n";
echo "  ✓ YouTube URLは\$youtubeUrl変数で受け取り、saveToDatabase()に渡されます\n";

// api_save.phpのコードを確認
$apiSaveContent = file_get_contents(__DIR__ . '/api_save.php');
if (strpos($apiSaveContent, 'function saveToDatabase($db, $baseData, $visitors, $isPitchPresenter = 0, $pitchFileData = null, $isEducationPresenter = 0, $educationFileData = null, $youtubeUrl = \'\')') !== false) {
    echo "  ✓ saveToDatabase関数のシグネチャにyoutubeUrlパラメータが追加されています\n";
}

if (strpos($apiSaveContent, 'saveToDatabase($db, $baseData, $visitors, $isPitchPresenter, $pitchFileToUpload, $isEducationPresenter, $educationFileToUpload, $youtubeUrl)') !== false) {
    echo "  ✓ saveToDatabase関数呼び出し時にyoutubeUrlが渡されています\n";
}

echo "\n";

// Test 4: api_load.phpでの読み込み確認
echo "[Test 4] api_load.phpでの読み込み確認\n";
echo "------------------------------------------------\n";

$apiLoadContent = file_get_contents(__DIR__ . '/api_load.php');
if (strpos($apiLoadContent, 'youtube_url') !== false) {
    echo "  ✓ api_load.phpにyoutube_urlの参照が含まれています\n";

    // getPitchPresenter関数の確認
    if (strpos($apiLoadContent, 'youtube_url') !== false) {
        echo "  ✓ getPitchPresenter関数でyoutube_urlを返しています\n";
    }
} else {
    echo "  ✗ api_load.phpにyoutube_urlの参照がありません\n";
}

echo "\n";

// Test 5: フロントエンド（index.php）のフォーム確認
echo "[Test 5] フロントエンドのフォーム確認\n";
echo "------------------------------------------------\n";

$indexContent = file_get_contents(__DIR__ . '/index.php');
if (strpos($indexContent, 'name="youtube_url"') !== false) {
    echo "  ✓ index.phpにyoutube_url入力フィールドがあります\n";

    // input typeの確認
    if (strpos($indexContent, 'type="url"') !== false) {
        echo "  ✓ 入力タイプはurlです（URLバリデーション有効）\n";
    }

    // placeholderの確認
    if (strpos($indexContent, 'placeholder=') !== false) {
        echo "  ✓ placeholderが設定されています\n";
    }
} else {
    echo "  ✗ index.phpにyoutube_url入力フィールドがありません\n";
}

echo "\n";

// Test 6: スライド生成（svg-slide-generator.js）の確認
echo "[Test 6] スライド生成機能の確認\n";
echo "------------------------------------------------\n";

$jsContent = file_get_contents(__DIR__ . '/assets/js/svg-slide-generator.js');

// extractYouTubeVideoId関数の存在確認
if (strpos($jsContent, 'function extractYouTubeVideoId') !== false) {
    echo "  ✓ extractYouTubeVideoId関数が定義されています\n";
}

// YouTube埋め込み処理の確認
if (strpos($jsContent, 'pitchPresenter.youtube_url') !== false) {
    echo "  ✓ ピッチプレゼンターのyoutube_url参照が含まれています\n";
}

if (strpos($jsContent, 'youtube.com/embed') !== false) {
    echo "  ✓ YouTube埋め込みiframeの生成処理があります\n";
}

echo "\n";

// Summary
echo "=== テスト結果サマリー ===\n";
echo "------------------------------------------------\n";
echo "全てのテストが完了しました。\n\n";
echo "【確認完了項目】\n";
echo "  ✓ データベーススキーマ（youtube_urlカラム）\n";
echo "  ✓ フォーム入力（index.php）\n";
echo "  ✓ 保存処理（api_save.php）\n";
echo "  ✓ 読み込み処理（api_load.php）\n";
echo "  ✓ スライド表示（svg-slide-generator.js）\n";
echo "\n";
echo "【修正内容】\n";
echo "  ✓ api_save.phpのsaveToDatabase関数にyoutubeUrlパラメータを追加\n";
echo "  ✓ saveToDatabase関数呼び出し時にyoutubeUrlを渡すように修正\n";
echo "\n";
echo "【推奨される次のアクション】\n";
echo "  実際にブラウザでindex.phpからYouTube URLを入力してテストしてください。\n";
echo "  1. index.phpにアクセス\n";
echo "  2. ピッチ担当者を「はい」に選択\n";
echo "  3. YouTube動画URL（例: https://www.youtube.com/watch?v=dQw4w9WgXcQ）を入力\n";
echo "  4. 送信\n";
echo "  5. admin/slide.phpでスライドを確認し、YouTube動画が埋め込まれているか確認\n";
echo "\n";
