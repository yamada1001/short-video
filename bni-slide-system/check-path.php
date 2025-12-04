<?php
// Simple path checker - No HTML, just plain output
header('Content-Type: text/plain; charset=utf-8');

echo "===========================================\n";
echo "BNI Slide System - Path Checker\n";
echo "===========================================\n\n";

echo "【絶対パス】\n";
echo __DIR__ . "\n\n";

echo "【.htaccess に設定する行】\n";
echo "AuthUserFile " . __DIR__ . "/.htpasswd\n\n";

echo "===========================================\n";
echo "その他の情報\n";
echo "===========================================\n\n";

echo "PHP Version: " . phpversion() . "\n";
echo "Server OS: " . PHP_OS . "\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "Server Name: " . ($_SERVER['SERVER_NAME'] ?? 'N/A') . "\n";
echo "This File: " . __FILE__ . "\n\n";

echo "===========================================\n";
echo "ファイル・ディレクトリ確認\n";
echo "===========================================\n\n";

$dataDir = __DIR__ . '/data';
$htpasswdFile = __DIR__ . '/.htpasswd';
$htaccessFile = __DIR__ . '/.htaccess';

echo "data/ ディレクトリ: ";
if (is_dir($dataDir)) {
    echo is_writable($dataDir) ? "存在 (書き込み可能)\n" : "存在 (書き込み不可)\n";
} else {
    echo "存在しません\n";
}

echo ".htpasswd ファイル: ";
echo file_exists($htpasswdFile) ? "存在\n" : "存在しません\n";

echo ".htaccess ファイル: ";
echo file_exists($htaccessFile) ? "存在\n" : "存在しません\n";

echo "\n===========================================\n";
echo "確認後は必ずこのファイルを削除してください\n";
echo "===========================================\n";
?>
