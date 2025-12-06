<?php
/**
 * .htpasswd ファイル生成スクリプト
 *
 * このスクリプトを1回実行して、.htpasswdファイルを生成してください。
 * 生成後は、このファイルを削除してください。
 */

// 認証情報
$username = 'travel';
$password = 'kyoto2025!';

// APR1ハッシュを生成（Apacheで使用される形式）
function apr1_hash($password) {
    $salt = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
    return crypt($password, '$apr1$' . $salt);
}

// .htpasswdの内容を生成
$htpasswd_content = $username . ':' . apr1_hash($password);

// ファイルに書き込み
$htpasswd_path = __DIR__ . '/.htpasswd';
if (file_put_contents($htpasswd_path, $htpasswd_content)) {
    echo "✅ .htpasswd ファイルが正常に生成されました！\n\n";
    echo "保存先: {$htpasswd_path}\n\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "ログイン情報\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "ユーザー名: {$username}\n";
    echo "パスワード: {$password}\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "⚠️ 重要な次のステップ:\n";
    echo "1. このログイン情報を安全な場所に保存してください\n";
    echo "2. このスクリプト（generate-htpasswd.php）を削除してください\n";
    echo "3. 削除コマンド: rm " . __FILE__ . "\n\n";
    echo "これでBasic認証が有効になります。\n";
    echo "次回アクセス時にログイン画面が表示されます。\n";
} else {
    echo "❌ エラー: .htpasswd ファイルの生成に失敗しました。\n";
    echo "ディレクトリの書き込み権限を確認してください。\n";
}
?>
