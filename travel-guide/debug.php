<?php
// エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>デバッグ情報</h1>";

// 1. config.php の読み込みテスト
echo "<h2>1. config.php の読み込み</h2>";
$config_path = __DIR__ . '/includes/config.php';
echo "パス: {$config_path}<br>";
echo "存在: " . (file_exists($config_path) ? '✅ はい' : '❌ いいえ') . "<br>";

if (file_exists($config_path)) {
    echo "読み込み中...<br>";
    try {
        require_once $config_path;
        echo "✅ config.php 読み込み成功<br>";
        echo "SITE_TITLE: " . (defined('SITE_TITLE') ? SITE_TITLE : '未定義') . "<br>";
        echo "BASE_URL: " . (defined('BASE_URL') ? BASE_URL : '未定義') . "<br>";
    } catch (Exception $e) {
        echo "❌ エラー: " . $e->getMessage() . "<br>";
    }
}

// 2. kyoto/index.php のパステスト
echo "<h2>2. kyoto/index.php</h2>";
$kyoto_path = __DIR__ . '/kyoto/index.php';
echo "パス: {$kyoto_path}<br>";
echo "存在: " . (file_exists($kyoto_path) ? '✅ はい' : '❌ いいえ') . "<br>";

// 3. kyoto/index.php から見た相対パス
echo "<h2>3. kyoto/index.php から見た相対パス</h2>";
$from_kyoto = dirname(__DIR__ . '/kyoto/index.php') . '/../includes/config.php';
echo "パス: {$from_kyoto}<br>";
echo "存在: " . (file_exists($from_kyoto) ? '✅ はい' : '❌ いいえ') . "<br>";

// 4. 実際のkyoto/index.phpの内容確認
echo "<h2>4. kyoto/index.php の最初の5行</h2>";
if (file_exists($kyoto_path)) {
    $lines = file($kyoto_path);
    echo "<pre>";
    for ($i = 0; $i < min(10, count($lines)); $i++) {
        echo htmlspecialchars($lines[$i]);
    }
    echo "</pre>";
}
?>
