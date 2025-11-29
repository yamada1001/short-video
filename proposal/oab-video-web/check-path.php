<?php
// 現在のディレクトリの絶対パスを表示
echo "<h2>絶対パスチェック</h2>";
echo "<p><strong>現在のディレクトリ:</strong><br>" . __DIR__ . "</p>";
echo "<p><strong>.htpasswd の正しいパス:</strong><br>" . __DIR__ . "/.htpasswd</p>";

// .htpasswd ファイルが存在するかチェック
$htpasswd_path = __DIR__ . "/.htpasswd";
if (file_exists($htpasswd_path)) {
    echo "<p style='color: green;'><strong>✓ .htpasswd ファイルが存在します</strong></p>";
    echo "<p>パーミッション: " . substr(sprintf('%o', fileperms($htpasswd_path)), -3) . "</p>";
} else {
    echo "<p style='color: red;'><strong>✗ .htpasswd ファイルが見つかりません</strong></p>";
}

// .htaccess に記載すべき正しいパス
echo "<hr>";
echo "<h3>.htaccess に記載する正しいパス:</h3>";
echo "<pre>AuthUserFile " . __DIR__ . "/.htpasswd</pre>";
?>
