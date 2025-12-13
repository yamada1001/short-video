<?php
/**
 * Migration: Add visitor_introductions and networking_learning_presenters tables
 * ビジターご紹介とネットワーキング学習コーナーのテーブルを追加
 */

require_once __DIR__ . '/../includes/db.php';

header('Content-Type: text/html; charset=UTF-8');

echo "<!DOCTYPE html>\n";
echo "<html lang=\"ja\">\n";
echo "<head><meta charset=\"UTF-8\"><title>Migration: Add Visitor & Networking Tables</title></head>\n";
echo "<body style=\"font-family: sans-serif; padding: 20px;\">\n";
echo "<h1>🔄 データベースマイグレーション</h1>\n";
echo "<h2>ビジターご紹介 & ネットワーキング学習コーナー テーブル追加</h2>\n";

try {
  $db = getDbConnection();

  echo "<p>📊 データベース接続: <strong style=\"color: green;\">成功</strong></p>\n";

  // Create visitor_introductions table
  echo "<h3>1. visitor_introductions テーブル作成</h3>\n";

  $db->exec("
    CREATE TABLE IF NOT EXISTS visitor_introductions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        week_date TEXT NOT NULL,
        visitor_name TEXT NOT NULL,
        company TEXT,
        specialty TEXT,
        sponsor TEXT NOT NULL,
        attendant TEXT NOT NULL,
        display_order INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
  ");

  $db->exec("CREATE INDEX IF NOT EXISTS idx_visitor_week_date ON visitor_introductions(week_date)");

  echo "<p>✅ <strong>visitor_introductions</strong> テーブルを作成しました</p>\n";

  // Create networking_learning_presenters table
  echo "<h3>2. networking_learning_presenters テーブル作成</h3>\n";

  $db->exec("
    CREATE TABLE IF NOT EXISTS networking_learning_presenters (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        week_date TEXT NOT NULL,
        presenter_name TEXT NOT NULL,
        presenter_email TEXT,
        presenter_company TEXT,
        presenter_category TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(week_date)
    )
  ");

  $db->exec("CREATE INDEX IF NOT EXISTS idx_networking_week_date ON networking_learning_presenters(week_date)");

  echo "<p>✅ <strong>networking_learning_presenters</strong> テーブルを作成しました</p>\n";

  // Show table structures
  echo "<h3>📋 テーブル構造</h3>\n";

  // visitor_introductions
  echo "<h4>visitor_introductions</h4>\n";
  echo "<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse: collapse;\">\n";
  echo "<tr><th>カラム名</th><th>型</th><th>NULL可</th><th>デフォルト値</th></tr>\n";

  $result = $db->query("PRAGMA table_info(visitor_introductions)");
  while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
    echo "<td>" . ($row['notnull'] ? 'NO' : 'YES') . "</td>";
    echo "<td>" . ($row['dflt_value'] !== null ? htmlspecialchars($row['dflt_value']) : '(NULL)') . "</td>";
    echo "</tr>\n";
  }

  echo "</table>\n";

  // networking_learning_presenters
  echo "<h4>networking_learning_presenters</h4>\n";
  echo "<table border=\"1\" cellpadding=\"5\" cellspacing=\"0\" style=\"border-collapse: collapse;\">\n";
  echo "<tr><th>カラム名</th><th>型</th><th>NULL可</th><th>デフォルト値</th></tr>\n";

  $result = $db->query("PRAGMA table_info(networking_learning_presenters)");
  while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    echo "<tr>";
    echo "<td><strong>" . htmlspecialchars($row['name']) . "</strong></td>";
    echo "<td>" . htmlspecialchars($row['type']) . "</td>";
    echo "<td>" . ($row['notnull'] ? 'NO' : 'YES') . "</td>";
    echo "<td>" . ($row['dflt_value'] !== null ? htmlspecialchars($row['dflt_value']) : '(NULL)') . "</td>";
    echo "</tr>\n";
  }

  echo "</table>\n";

  dbClose($db);

  echo "<h3>✅ マイグレーション完了</h3>\n";
  echo "<p><a href=\"../admin/edit.php\">← 編集画面に戻る</a></p>\n";

} catch (Exception $e) {
  echo "<p style=\"color: red;\">❌ エラー: " . htmlspecialchars($e->getMessage()) . "</p>\n";
  echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>\n";
}

echo "</body>\n";
echo "</html>\n";
