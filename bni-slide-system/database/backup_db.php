<?php
/**
 * BNI Slide System - Database Backup Script
 * データベースの自動バックアップスクリプト
 *
 * 使い方:
 * php database/backup_db.php
 *
 * cron設定例（毎日深夜1時）:
 * 0 1 * * * /usr/bin/php /path/to/database/backup_db.php >> /path/to/logs/backup.log 2>&1
 */

// CLI実行のみ許可
if (php_sapi_name() !== 'cli') {
    die('このスクリプトはコマンドラインからのみ実行できます');
}

echo "==============================================\n";
echo "BNI Slide System - データベースバックアップ\n";
echo "==============================================\n\n";

// パス設定
$dbFile = __DIR__ . '/../data/bni_system.db';
$backupDir = __DIR__ . '/../backups/';

// データベースファイルの存在確認
if (!file_exists($dbFile)) {
    echo "❌ エラー: データベースファイルが見つかりません\n";
    echo "   パス: {$dbFile}\n";
    exit(1);
}

// バックアップディレクトリの作成
if (!is_dir($backupDir)) {
    if (!mkdir($backupDir, 0755, true)) {
        echo "❌ エラー: バックアップディレクトリを作成できません\n";
        echo "   パス: {$backupDir}\n";
        exit(1);
    }
    echo "✓ バックアップディレクトリを作成しました\n";
}

// バックアップファイル名（日時付き）
$date = date('Y-m-d_H-i-s');
$backupFile = $backupDir . "bni_system_{$date}.db";

// データベースをコピー
echo "バックアップを作成中...\n";
if (!copy($dbFile, $backupFile)) {
    echo "❌ エラー: バックアップファイルのコピーに失敗しました\n";
    exit(1);
}

$fileSize = filesize($backupFile);
$fileSizeKB = round($fileSize / 1024, 2);

echo "✅ バックアップ作成完了\n";
echo "   ファイル: {$backupFile}\n";
echo "   サイズ: {$fileSizeKB} KB\n\n";

// 古いバックアップの削除（7日以上前）
echo "古いバックアップを削除中...\n";
$files = glob($backupDir . 'bni_system_*.db');
$deletedCount = 0;
$retentionDays = 7;
$cutoffTime = time() - ($retentionDays * 86400);

foreach ($files as $file) {
    if (filemtime($file) < $cutoffTime) {
        $fileName = basename($file);
        if (unlink($file)) {
            echo "  ✓ 削除: {$fileName}\n";
            $deletedCount++;
        }
    }
}

if ($deletedCount === 0) {
    echo "  - 削除対象のファイルはありませんでした\n";
} else {
    echo "  ✓ {$deletedCount}個のファイルを削除しました\n";
}

echo "\n";

// バックアップ一覧表示
echo "==============================================\n";
echo "現在のバックアップ一覧\n";
echo "==============================================\n";

$files = glob($backupDir . 'bni_system_*.db');
rsort($files); // 新しい順にソート

if (empty($files)) {
    echo "バックアップファイルがありません\n";
} else {
    echo "保持期間: {$retentionDays}日間\n\n";

    foreach ($files as $file) {
        $fileName = basename($file);
        $fileTime = filemtime($file);
        $fileDate = date('Y-m-d H:i:s', $fileTime);
        $fileSize = filesize($file);
        $fileSizeKB = round($fileSize / 1024, 2);
        $daysOld = floor((time() - $fileTime) / 86400);

        echo "  {$fileName}\n";
        echo "    作成日時: {$fileDate}\n";
        echo "    サイズ: {$fileSizeKB} KB\n";
        echo "    経過日数: {$daysOld}日\n\n";
    }

    echo "合計: " . count($files) . "個のバックアップ\n";
}

echo "==============================================\n";
echo "✅ バックアップ処理が完了しました\n";
echo "==============================================\n";

exit(0);
?>
