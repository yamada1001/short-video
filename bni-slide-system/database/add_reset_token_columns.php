<?php
/**
 * BNI Slide System - Add Reset Token Columns Migration
 * usersテーブルにreset_tokenとreset_token_expiresカラムを追加
 *
 * 使い方:
 * php database/add_reset_token_columns.php
 */

// CLI実行のみ許可（ブラウザからも実行可能に一時的に変更）
// if (php_sapi_name() !== 'cli') {
//     die('このスクリプトはコマンドラインからのみ実行できます');
// }

// ブラウザから実行の場合、HTML形式で出力
$isCli = php_sapi_name() === 'cli';
if (!$isCli) {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Migration</title>";
    echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}pre{background:#fff;padding:15px;border:1px solid #ddd;}</style>";
    echo "</head><body><pre>";
}

echo "==============================================\n";
echo "Reset Token Columns Migration\n";
echo "==============================================\n\n";

// パス設定
$dbFile = __DIR__ . '/../data/bni_system.db';

// データベースファイルの存在確認
if (!file_exists($dbFile)) {
    echo "❌ エラー: データベースファイルが見つかりません\n";
    echo "   パス: {$dbFile}\n";
    exit(1);
}

try {
    $db = new SQLite3($dbFile);
    $db->enableExceptions(true);

    echo "データベース接続成功\n\n";

    // カラムが既に存在するかチェック
    $result = $db->query("PRAGMA table_info(users)");
    $columns = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $row['name'];
    }

    $hasResetToken = in_array('reset_token', $columns);
    $hasResetTokenExpires = in_array('reset_token_expires', $columns);

    echo "現在のusersテーブルカラム:\n";
    foreach ($columns as $col) {
        echo "  - {$col}\n";
    }
    echo "\n";

    // reset_tokenカラムを追加
    if (!$hasResetToken) {
        echo "reset_tokenカラムを追加中...\n";
        $db->exec("ALTER TABLE users ADD COLUMN reset_token TEXT");
        echo "✅ reset_tokenカラムを追加しました\n";
    } else {
        echo "⚠️  reset_tokenカラムは既に存在します\n";
    }

    // reset_token_expiresカラムを追加
    if (!$hasResetTokenExpires) {
        echo "reset_token_expiresカラムを追加中...\n";
        $db->exec("ALTER TABLE users ADD COLUMN reset_token_expires DATETIME");
        echo "✅ reset_token_expiresカラムを追加しました\n";
    } else {
        echo "⚠️  reset_token_expiresカラムは既に存在します\n";
    }

    $db->close();

    echo "\n==============================================\n";
    echo "✅ マイグレーション完了\n";
    echo "==============================================\n";

    if (!$isCli) {
        echo "</pre></body></html>";
    }

    exit(0);

} catch (Exception $e) {
    if (isset($db)) {
        $db->close();
    }
    echo "\n❌ エラー: " . $e->getMessage() . "\n";

    if (!$isCli) {
        echo "</pre></body></html>";
    }

    exit(1);
}
?>
