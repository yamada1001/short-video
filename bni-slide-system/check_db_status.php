<?php
/**
 * Database Status Checker
 * 本番環境のデータベース状態を確認
 */

header('Content-Type: text/html; charset=utf-8');

$dbPath = __DIR__ . '/data/bni_system.db';

echo "<h1>🔍 Database Status Check</h1>";
echo "<pre>";

// 1. データベースファイルの存在確認
echo "1. データベースファイル:\n";
if (file_exists($dbPath)) {
    echo "   ✅ 存在します: " . $dbPath . "\n";
    echo "   サイズ: " . number_format(filesize($dbPath)) . " bytes\n";
    echo "   更新日時: " . date('Y-m-d H:i:s', filemtime($dbPath)) . "\n";
} else {
    echo "   ❌ 存在しません: " . $dbPath . "\n";
    echo "   → データベース移行が必要です\n\n";
    echo "<strong>実行コマンド:</strong>\n";
    echo "   php database/migrate_all.php --force\n";
    exit;
}

echo "\n";

// 2. SQLite拡張の確認
echo "2. SQLite3拡張:\n";
if (class_exists('SQLite3')) {
    echo "   ✅ 利用可能\n";
} else {
    echo "   ❌ 利用不可\n";
    exit;
}

echo "\n";

// 3. データベース接続テスト
echo "3. データベース接続:\n";
try {
    $db = new SQLite3($dbPath);
    echo "   ✅ 接続成功\n";
} catch (Exception $e) {
    echo "   ❌ 接続失敗: " . $e->getMessage() . "\n";
    exit;
}

echo "\n";

// 4. テーブル一覧
echo "4. テーブル一覧:\n";
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
$tableList = [];
while ($row = $tables->fetchArray(SQLITE3_ASSOC)) {
    $tableList[] = $row['name'];
    echo "   - " . $row['name'] . "\n";
}

echo "\n";

// 5. usersテーブルのカラム確認
echo "5. usersテーブルのカラム:\n";
if (in_array('users', $tableList)) {
    $columns = $db->query("PRAGMA table_info(users)");
    $hasIsActive = false;
    $hasIndustry = false;
    $hasRequire2fa = false;

    while ($col = $columns->fetchArray(SQLITE3_ASSOC)) {
        echo "   - " . $col['name'] . " (" . $col['type'] . ")\n";
        if ($col['name'] === 'is_active') $hasIsActive = true;
        if ($col['name'] === 'industry') $hasIndustry = true;
        if ($col['name'] === 'require_2fa') $hasRequire2fa = true;
    }

    echo "\n";
    echo "   必須カラム確認:\n";
    echo "   - is_active: " . ($hasIsActive ? "✅" : "❌") . "\n";
    echo "   - industry: " . ($hasIndustry ? "✅" : "❌") . "\n";
    echo "   - require_2fa: " . ($hasRequire2fa ? "✅" : "❌") . "\n";

    if (!$hasIsActive || !$hasIndustry || !$hasRequire2fa) {
        echo "\n   ⚠️ 不足しているカラムがあります\n";
        echo "   → データベースを再作成してください\n";
    }
} else {
    echo "   ❌ usersテーブルが存在しません\n";
}

echo "\n";

// 6. データ件数
echo "6. データ件数:\n";
foreach (['users', 'survey_data', 'visitors', 'referrals'] as $table) {
    if (in_array($table, $tableList)) {
        $result = $db->querySingle("SELECT COUNT(*) FROM $table");
        echo "   - $table: " . number_format($result) . "件\n";
    }
}

echo "\n";

// 7. 最新の週データ
echo "7. 最新の週データ:\n";
if (in_array('survey_data', $tableList)) {
    $weeks = $db->query("SELECT week_date, COUNT(*) as count FROM survey_data GROUP BY week_date ORDER BY week_date DESC LIMIT 5");
    while ($week = $weeks->fetchArray(SQLITE3_ASSOC)) {
        echo "   - " . $week['week_date'] . ": " . $week['count'] . "件\n";
    }
}

$db->close();

echo "\n";
echo "=====================================\n";
echo "✅ データベースは正常に動作しています\n";
echo "=====================================\n";
echo "</pre>";

// セキュリティのため、本番環境ではこのファイルを削除することを推奨
echo "<p style='color: red;'><strong>注意:</strong> 確認が終わったら、このファイル（check_db_status.php）を削除してください。</p>";
?>
