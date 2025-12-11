<?php
/**
 * Database Check Script
 * データベースの状態を確認
 */

require_once __DIR__ . '/includes/db.php';

header('Content-Type: text/html; charset=UTF-8');

echo "<h1>データベース状態確認</h1>";
echo "<style>body{font-family:sans-serif;padding:20px}table{border-collapse:collapse;margin:20px 0}th,td{border:1px solid #ddd;padding:8px}th{background:#CF2030;color:white}</style>";

try {
    $db = getDbConnection();

    // 1. テーブル一覧
    echo "<h2>1. テーブル一覧</h2>";
    $tables = dbQuery($db, "SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . htmlspecialchars($table['name']) . "</li>";
    }
    echo "</ul>";

    // 2. survey_data のカラム確認
    echo "<h2>2. survey_data テーブルのカラム</h2>";
    $columns = dbQuery($db, "PRAGMA table_info(survey_data)");
    echo "<table><tr><th>カラム名</th><th>型</th></tr>";
    foreach ($columns as $col) {
        echo "<tr><td>" . htmlspecialchars($col['name']) . "</td><td>" . htmlspecialchars($col['type']) . "</td></tr>";
    }
    echo "</table>";

    // youtube_url カラムがあるか確認
    $hasYoutubeUrl = false;
    foreach ($columns as $col) {
        if ($col['name'] === 'youtube_url') {
            $hasYoutubeUrl = true;
            break;
        }
    }
    echo "<p><strong>youtube_url カラム:</strong> " . ($hasYoutubeUrl ? "✅ 存在する" : "❌ 存在しない") . "</p>";

    // 3. データ件数
    echo "<h2>3. データ件数</h2>";
    $count = dbQueryOne($db, "SELECT COUNT(*) as count FROM survey_data");
    echo "<p><strong>survey_data:</strong> " . $count['count'] . " 件</p>";

    if ($count['count'] > 0) {
        // 最新データを表示
        echo "<h3>最新データ（最大5件）</h3>";
        $recent = dbQuery($db, "SELECT week_date, user_name, created_at FROM survey_data ORDER BY created_at DESC LIMIT 5");
        echo "<table><tr><th>週</th><th>ユーザー</th><th>登録日時</th></tr>";
        foreach ($recent as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['week_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

    // 4. monthly_ranking_data テーブル確認
    echo "<h2>4. monthly_ranking_data テーブル</h2>";
    $tableExists = dbQuery($db, "SELECT name FROM sqlite_master WHERE type='table' AND name='monthly_ranking_data'");

    if (empty($tableExists)) {
        echo "<p>❌ <strong>monthly_ranking_data テーブルが存在しません</strong></p>";
        echo "<p>→ migrate_add_monthly_ranking.php を実行する必要があります</p>";
    } else {
        echo "<p>✅ monthly_ranking_data テーブルは存在します</p>";
        $rankingCount = dbQueryOne($db, "SELECT COUNT(*) as count FROM monthly_ranking_data");
        echo "<p><strong>登録データ数:</strong> " . $rankingCount['count'] . " 件</p>";
    }

    // 5. users テーブル
    echo "<h2>5. users テーブル</h2>";
    $userCount = dbQueryOne($db, "SELECT COUNT(*) as count FROM users");
    echo "<p><strong>登録ユーザー数:</strong> " . $userCount['count'] . " 人</p>";

    dbClose($db);

    echo "<hr>";
    echo "<h2>✅ データベース接続成功</h2>";
    echo "<p>データベースファイル: " . realpath(__DIR__ . '/database/bni_data.db') . "</p>";

} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ エラー発生</h2>";
    echo "<pre>" . htmlspecialchars($e->getMessage()) . "</pre>";
}
