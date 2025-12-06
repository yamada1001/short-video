<?php
/**
 * Basic認証診断スクリプト
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Basic認証診断</h1>";
echo "<style>
body { font-family: sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
.box { background: #f8f9fa; border: 1px solid #dee2e6; padding: 20px; margin: 20px 0; border-radius: 8px; }
.success { background: #d4edda; border-color: #28a745; }
.error { background: #f8d7da; border-color: #dc3545; }
.warning { background: #fff3cd; border-color: #ffc107; }
pre { background: #f8f9fa; padding: 12px; border-radius: 4px; overflow-x: auto; }
</style>";

$htaccess_path = __DIR__ . '/.htaccess';
$htpasswd_path = __DIR__ . '/.htpasswd';

// 1. .htaccess の確認
echo "<div class='box'>";
echo "<h2>1. .htaccess ファイル</h2>";
if (file_exists($htaccess_path)) {
    echo "<p class='success'>✅ 存在します</p>";
    echo "<p><strong>内容:</strong></p>";
    echo "<pre>" . htmlspecialchars(file_get_contents($htaccess_path)) . "</pre>";

    $perms = substr(sprintf('%o', fileperms($htaccess_path)), -4);
    echo "<p>パーミッション: {$perms}</p>";
} else {
    echo "<p class='error'>❌ 存在しません</p>";
}
echo "</div>";

// 2. .htpasswd の確認
echo "<div class='box'>";
echo "<h2>2. .htpasswd ファイル</h2>";
if (file_exists($htpasswd_path)) {
    echo "<p class='success'>✅ 存在します</p>";
    echo "<p><strong>内容:</strong></p>";
    $htpasswd_content = file_get_contents($htpasswd_path);
    echo "<pre>" . htmlspecialchars($htpasswd_content) . "</pre>";

    $perms = substr(sprintf('%o', fileperms($htpasswd_path)), -4);
    echo "<p>パーミッション: {$perms}</p>";

    // パスワードの検証
    $parts = explode(':', trim($htpasswd_content));
    if (count($parts) == 2) {
        echo "<p>ユーザー名: <code>{$parts[0]}</code></p>";
        echo "<p>ハッシュ: <code>" . substr($parts[1], 0, 20) . "...</code></p>";

        // パスワードテスト
        $test_password = 'kyoto2025!';
        $hash = $parts[1];

        echo "<h3>パスワード検証テスト</h3>";
        echo "<p>テストパスワード: <code>{$test_password}</code></p>";

        // APR1ハッシュの検証
        if (strpos($hash, '$apr1$') === 0) {
            echo "<p class='success'>✅ APR1形式のハッシュです</p>";

            // cryptで検証
            $test_hash = crypt($test_password, $hash);
            if ($test_hash === $hash) {
                echo "<p class='success'>✅ パスワードが一致します</p>";
            } else {
                echo "<p class='error'>❌ パスワードが一致しません</p>";
                echo "<p>生成されたハッシュ: <code>" . substr($test_hash, 0, 20) . "...</code></p>";
                echo "<p>保存されたハッシュ: <code>" . substr($hash, 0, 20) . "...</code></p>";
            }
        } else {
            echo "<p class='warning'>⚠️ APR1形式ではありません</p>";
        }
    } else {
        echo "<p class='error'>❌ .htpasswd の形式が正しくありません</p>";
    }
} else {
    echo "<p class='error'>❌ 存在しません</p>";
}
echo "</div>";

// 3. Apacheの設定確認
echo "<div class='box'>";
echo "<h2>3. Apache環境</h2>";
echo "<p>DocumentRoot: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "</p>";
echo "<p>現在のディレクトリ: " . __DIR__ . "</p>";
echo "<p>.htpasswd の絶対パス: {$htpasswd_path}</p>";

// .htaccess内のパスと実際のパスが一致しているか確認
if (file_exists($htaccess_path)) {
    $htaccess_content = file_get_contents($htaccess_path);
    if (preg_match('/AuthUserFile\s+(.+)/', $htaccess_content, $matches)) {
        $configured_path = trim($matches[1]);
        echo "<p>.htaccess で設定されたパス: <code>{$configured_path}</code></p>";

        if ($configured_path === $htpasswd_path) {
            echo "<p class='success'>✅ パスが一致します</p>";
        } else {
            echo "<p class='error'>❌ パスが一致しません！</p>";
            echo "<p class='warning'>これが原因の可能性が高いです。</p>";
        }
    }
}
echo "</div>";

// 4. 推奨事項
echo "<div class='box warning'>";
echo "<h2>4. トラブルシューティング</h2>";
echo "<ol>";
echo "<li><strong>ブラウザのキャッシュをクリア</strong>するか、<strong>シークレットモード</strong>でアクセスしてください</li>";
echo "<li>ユーザー名: <code>travel</code></li>";
echo "<li>パスワード: <code>kyoto2025!</code></li>";
echo "<li>パスワードに余分なスペースや改行が含まれていないか確認してください</li>";
echo "<li>別のブラウザで試してみてください</li>";
echo "</ol>";
echo "</div>";

// 5. 手動でパスワードを再生成
echo "<div class='box'>";
echo "<h2>5. パスワード再生成</h2>";
echo "<p><a href='generate-htpasswd.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block;'>パスワードを再生成する</a></p>";
echo "</div>";
?>
