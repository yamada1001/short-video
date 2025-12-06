<?php
/**
 * Basic認証を有効化するスクリプト
 *
 * このスクリプトは .htaccess のコメントを外してBasic認証を有効化します。
 * generate-htpasswd.php で .htpasswd を生成した後に実行してください。
 */

$htaccess_path = __DIR__ . '/.htaccess';
$htpasswd_path = __DIR__ . '/.htpasswd';

echo "<h1>🔒 Basic認証有効化スクリプト</h1>";

// .htpasswd の存在確認
if (!file_exists($htpasswd_path)) {
    echo "<div style='background: #fff3cd; border: 1px solid #ffc107; padding: 20px; margin: 20px 0; border-radius: 8px;'>";
    echo "<h2 style='color: #856404; margin-top: 0;'>⚠️ エラー: .htpasswd が見つかりません</h2>";
    echo "<p>先に <a href='generate-htpasswd.php'>generate-htpasswd.php</a> を実行して、.htpasswd ファイルを生成してください。</p>";
    echo "</div>";
    exit;
}

echo "<div style='background: #d4edda; border: 1px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 8px;'>";
echo "<p style='color: #155724;'>✅ .htpasswd ファイルが見つかりました</p>";
echo "</div>";

// .htaccess の内容を読み込む
$content = file_get_contents($htaccess_path);

// コメントを外す
$new_content = str_replace('# AuthType Basic', 'AuthType Basic', $content);
$new_content = str_replace('# AuthName', 'AuthName', $new_content);
$new_content = str_replace('# AuthUserFile', 'AuthUserFile', $new_content);
$new_content = str_replace('# Require valid-user', 'Require valid-user', $new_content);
$new_content = str_replace('# ErrorDocument 401', 'ErrorDocument 401', $new_content);

// 書き込み
if (file_put_contents($htaccess_path, $new_content)) {
    echo "<div style='background: #d4edda; border: 1px solid #28a745; padding: 20px; margin: 20px 0; border-radius: 8px;'>";
    echo "<h2 style='color: #155724; margin-top: 0;'>✅ Basic認証が有効になりました！</h2>";
    echo "<p><strong>次回アクセス時からログイン画面が表示されます。</strong></p>";
    echo "<hr style='border: none; border-top: 1px solid #c3e6cb; margin: 20px 0;'>";
    echo "<h3 style='color: #155724;'>ログイン情報</h3>";
    echo "<p>ユーザー名: <code style='background: #f8f9fa; padding: 4px 8px; border-radius: 4px;'>travel</code></p>";
    echo "<p>パスワード: <code style='background: #f8f9fa; padding: 4px 8px; border-radius: 4px;'>kyoto2025!</code></p>";
    echo "<hr style='border: none; border-top: 1px solid #c3e6cb; margin: 20px 0;'>";
    echo "<h3 style='color: #155724;'>⚠️ 重要な次のステップ:</h3>";
    echo "<ol style='line-height: 2;'>";
    echo "<li><strong>このスクリプト（enable-auth.php）を削除してください</strong></li>";
    echo "<li>generate-htpasswd.php も削除してください（まだの場合）</li>";
    echo "<li>debug.php も削除してください</li>";
    echo "</ol>";
    echo "<p style='color: #721c24; background: #f8d7da; padding: 12px; border-radius: 4px; margin-top: 20px;'>";
    echo "⚠️ セキュリティのため、必ずこれらのスクリプトを削除してください！";
    echo "</p>";
    echo "</div>";

    echo "<div style='background: #d1ecf1; border: 1px solid #17a2b8; padding: 20px; margin: 20px 0; border-radius: 8px;'>";
    echo "<h3 style='color: #0c5460; margin-top: 0;'>動作確認</h3>";
    echo "<p>以下のURLにアクセスして、ログイン画面が表示されることを確認してください:</p>";
    echo "<p><a href='/travel-guide/kyoto/index.php' style='color: #0c5460; font-weight: bold;'>https://yojitu.com/travel-guide/kyoto/index.php</a></p>";
    echo "</div>";
} else {
    echo "<div style='background: #f8d7da; border: 1px solid #dc3545; padding: 20px; margin: 20px 0; border-radius: 8px;'>";
    echo "<h2 style='color: #721c24; margin-top: 0;'>❌ エラー</h2>";
    echo "<p>.htaccess ファイルの書き込みに失敗しました。</p>";
    echo "<p>ファイルの書き込み権限を確認してください。</p>";
    echo "</div>";
}
?>
