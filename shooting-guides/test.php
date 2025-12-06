<?php
// PHP情報とエラーチェック用テストファイル

// エラー表示を有効化
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== PHP Test Page ===\n\n";

// 1. PHP基本情報
echo "PHP Version: " . phpversion() . "\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Current File: " . __FILE__ . "\n";
echo "Current Dir: " . __DIR__ . "\n\n";

// 2. フォントファイルの存在確認
echo "=== Font Files Check ===\n";
$fontDir = __DIR__ . '/tune-stay-kyoto/fonts/';
$fonts = [
    'LINESeedJP_OTF_Rg.otf',
    'LINESeedJP_OTF_Bd.otf',
    'LINESeedJP_OTF_Th.otf'
];

foreach ($fonts as $font) {
    $path = $fontDir . $font;
    echo $font . ": " . (file_exists($path) ? "OK" : "NOT FOUND") . "\n";
}
echo "\n";

// 3. .htaccessの存在確認
echo "=== .htaccess Check ===\n";
$htaccess = __DIR__ . '/.htaccess';
echo ".htaccess exists: " . (file_exists($htaccess) ? "YES" : "NO") . "\n";
if (file_exists($htaccess)) {
    echo ".htaccess size: " . filesize($htaccess) . " bytes\n";
}
echo "\n";

// 4. .htpasswdの存在確認
echo "=== .htpasswd Check ===\n";
$htpasswd = __DIR__ . '/.htpasswd';
echo ".htpasswd exists: " . (file_exists($htpasswd) ? "YES" : "NO") . "\n";
if (file_exists($htpasswd)) {
    echo ".htpasswd size: " . filesize($htpasswd) . " bytes\n";
}
echo "\n";

// 5. tune-stay-kyoto/index.phpの存在確認
echo "=== Main File Check ===\n";
$indexPhp = __DIR__ . '/tune-stay-kyoto/index.php';
echo "index.php exists: " . (file_exists($indexPhp) ? "YES" : "NO") . "\n";
if (file_exists($indexPhp)) {
    echo "index.php size: " . filesize($indexPhp) . " bytes\n";
    echo "index.php readable: " . (is_readable($indexPhp) ? "YES" : "NO") . "\n";
}
echo "\n";

// 6. エラーログの確認
echo "=== Error Log ===\n";
if (function_exists('error_get_last')) {
    $error = error_get_last();
    if ($error) {
        echo "Last Error: " . print_r($error, true) . "\n";
    } else {
        echo "No errors detected\n";
    }
}
echo "\n";

// 7. Apacheモジュールの確認
echo "=== Apache Modules ===\n";
if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    echo "mod_rewrite: " . (in_array('mod_rewrite', $modules) ? "Enabled" : "Disabled") . "\n";
    echo "mod_auth_basic: " . (in_array('mod_auth_basic', $modules) ? "Enabled" : "Disabled") . "\n";
    echo "mod_headers: " . (in_array('mod_headers', $modules) ? "Enabled" : "Disabled") . "\n";
} else {
    echo "apache_get_modules() not available\n";
}
echo "\n";

// 8. ディレクトリ一覧
echo "=== Directory Listing ===\n";
$files = scandir(__DIR__);
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo $file . (is_dir(__DIR__ . '/' . $file) ? '/' : '') . "\n";
    }
}

echo "\n=== Test Complete ===\n";
