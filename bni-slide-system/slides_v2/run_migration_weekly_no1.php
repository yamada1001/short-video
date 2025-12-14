<?php
/**
 * Migration Script: Fix weekly_no1 table schema
 * Run this once to update the database structure
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Weekly No.1 Schema Migration</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; }
        h1 { color: #C8102E; }
        .success { background: #c8e6c9; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #ffcdd2; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .code { background: #f5f5f5; padding: 10px; border-radius: 3px; font-family: monospace; white-space: pre-wrap; }
    </style>
</head>
<body>
    <h1>Weekly No.1 Table Schema Migration</h1>
    <p>実行日時: <?php echo date('Y-m-d H:i:s'); ?></p>

<?php

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<div class='info'><strong>Step 1:</strong> 現在のテーブル構造を確認</div>\n";

    // Check current schema
    $stmt = $db->query("PRAGMA table_info(weekly_no1)");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<p>現在のカラム:</p><div class='code'>";
    foreach ($columns as $col) {
        echo "- {$col['name']} ({$col['type']})\n";
    }
    echo "</div>\n";

    echo "<div class='info'><strong>Step 2:</strong> マイグレーション実行</div>\n";

    // Read migration SQL
    $migrationSQL = file_get_contents(__DIR__ . '/database/migration_weekly_no1_schema.sql');

    // Remove comments and split by semicolon
    $statements = array_filter(
        array_map('trim',
            preg_split('/;[\s]*$/m', $migrationSQL)
        ),
        function($stmt) {
            return !empty($stmt) && !preg_match('/^--/', $stmt);
        }
    );

    $db->beginTransaction();

    foreach ($statements as $sql) {
        if (trim($sql)) {
            echo "<p>実行中: <code>" . htmlspecialchars(substr($sql, 0, 100)) . "...</code></p>\n";
            $db->exec($sql);
        }
    }

    $db->commit();

    echo "<div class='success'><h2>マイグレーション成功!</h2></div>\n";

    echo "<div class='info'><strong>Step 3:</strong> 新しいテーブル構造を確認</div>\n";

    $stmt = $db->query("PRAGMA table_info(weekly_no1)");
    $newColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<p>新しいカラム:</p><div class='code'>";
    foreach ($newColumns as $col) {
        echo "- {$col['name']} ({$col['type']})\n";
    }
    echo "</div>\n";

    // Check data
    $stmt = $db->query("SELECT COUNT(*) as count FROM weekly_no1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>テーブル内のレコード数: <strong>{$row['count']}</strong></p>\n";

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "<div class='error'><h2>エラー発生</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>\n";
}

?>

<hr>
<p><a href="api/weekly_no1_crud.php?action=get_latest">API動作テスト</a> | <a href="admin/weekly_no1.php">管理画面に戻る</a></p>

</body>
</html>
