<?php
// 最小限のテスト - config.phpを読み込まずにPHPが動作するかチェック
header('Content-Type: text/plain; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "=== Simple PHP Test ===\n\n";
echo "1. PHP Version: " . PHP_VERSION . "\n";
echo "2. Current directory: " . __DIR__ . "\n";
echo "3. Parent directory: " . dirname(__DIR__) . "\n";
echo "4. File exists check: " . (file_exists(__DIR__ . '/../config.php') ? "YES" : "NO") . "\n";
echo "5. Time: " . date('Y-m-d H:i:s') . "\n";
echo "\n=== Test Complete ===\n";
