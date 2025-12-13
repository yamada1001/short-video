<?php
/**
 * Migration: Add display_in_slide column to monthly_ranking_data table
 * 月間ランキングデータテーブルに display_in_slide カラムを追加
 */

require_once __DIR__ . '/../includes/db.php';

header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>\n";
echo "<html lang=\"ja\">\n";
echo "<head><meta charset=\"UTF-8\"><title>Migration: Add display_in_slide</title></head>\n";
echo "<body style=\"font-family: sans-serif; padding: 20px;\">\n";
echo "<h1>🔄 データベースマイグレーション</h1>\n";
echo "<h2>display_in_slide カラムを追加</h2>\n";

try {
  $db = getDbConnection();

  echo "<p>📊 データベース接続: <strong style=\"color: green;\">成功</strong></p>\n";

  // Check if column already exists
  $result = $db->query("PRAGMA table_info(monthly_ranking_data)");
  $columns = [];
  while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $columns[] = $row;
  }
  $columnExists = false;

  foreach ($columns as $column) {
    if ($column['name'] === 'display_in_slide') {
      $columnExists = true;
      break;
    }
  }

  if ($columnExists) {
    echo "<p>ℹ️ <strong>display_in_slide</strong> カラムは既に存在します。</p>\n";
  } else {
    echo "<p>➕ <strong>display_in_slide</strong> カラムを追加中...</p>\n";

    // Add display_in_slide column (default: 0 = not displayed in slide)
    $db->exec("ALTER TABLE monthly_ranking_data ADD COLUMN display_in_slide INTEGER DEFAULT 0");

    echo "<p>✅ <strong>display_in_slide</strong> カラムを追加しました（デフォルト: 0 = スライド非表示）</p>\n";
  }

  // Show table structure
  echo "<h3>📋 テーブル構造（monthly_ranking_data）</h3>\n";
  echo "<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse: collapse;\">\n";
  echo "<tr><th>カラム名</th><th>型</th><th>NULL可</th><th>デフォルト値</th></tr>\n";

  $result = $db->query("PRAGMA table_info(monthly_ranking_data)");
  $columns = [];
  while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $columns[] = $row;
  }

  foreach ($columns as $column) {
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($column['name']) . "</strong></td>";
    echo "<td>" . htmlspecialchars($column['type']) . "</td>";
    echo "<td>" . ($column['notnull'] ? 'NO' : 'YES') . "</td>";
    echo "<td>" . ($column['dflt_value'] !== null ? htmlspecialchars($column['dflt_value']) : '(NULL)') . "</td>";
    echo "</tr>\n";
  }

  echo "</table>\n";

  dbClose($db);

  echo "<h3>✅ マイグレーション完了</h3>\n";
  echo "<p><a href=\"../admin/monthly_ranking.php\">← 月間ランキング入力画面に戻る</a></p>\n";

} catch (Exception $e) {
  echo "<p style=\"color: red;\">❌ エラー: " . htmlspecialchars($e->getMessage()) . "</p>\n";
  echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>\n";
}

echo "</body>\n";
echo "</html>\n";
