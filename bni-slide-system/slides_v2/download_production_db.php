<?php
/**
 * 本番環境のデータベースをダウンロード
 */

require_once __DIR__ . '/config.php';

// データベースファイルを読み込み
$dbContent = file_get_contents($db_path);

// ダウンロードヘッダーを送信
header('Content-Type: application/x-sqlite3');
header('Content-Disposition: attachment; filename="bni_slide_system_backup_' . date('Y-m-d_H-i-s') . '.db"');
header('Content-Length: ' . strlen($dbContent));

echo $dbContent;
