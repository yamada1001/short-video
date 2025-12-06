<?php
// サーバー情報確認用ファイル
// このファイルで正しいパスを確認してから.htaccessを修正してください

echo "<h1>Server Path Information</h1>";
echo "<pre>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Current File:  " . __FILE__ . "\n";
echo "Current Dir:   " . __DIR__ . "\n\n";

echo "Suggested AuthUserFile path:\n";
echo __DIR__ . "/.htpasswd\n\n";

echo ".htpasswd exists: " . (file_exists(__DIR__ . '/.htpasswd') ? 'YES' : 'NO') . "\n";

if (file_exists(__DIR__ . '/.htpasswd')) {
    echo ".htpasswd readable: " . (is_readable(__DIR__ . '/.htpasswd') ? 'YES' : 'NO') . "\n";
    echo ".htpasswd permissions: " . substr(sprintf('%o', fileperms(__DIR__ . '/.htpasswd')), -4) . "\n";
}

echo "\n--- Copy this line to .htaccess ---\n";
echo "AuthUserFile " . __DIR__ . "/.htpasswd\n";
echo "</pre>";

// このファイルは確認後に削除してください
echo "<p style='color: red;'><strong>Important:</strong> Delete this file after confirming the path!</p>";
