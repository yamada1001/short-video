<?php
/**
 * 本番サーバー用マイグレーション実行スクリプト
 * Usage: https://yojitu.com/bni-slide-system/slides_v2/run_migration_on_server.php
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "本番サーバー - DBマイグレーション実行\n";
echo "========================================\n\n";
echo "実行日時: " . date('Y-m-d H:i:s') . "\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 1: 現在のスキーマ確認
    echo "[Step 1] 現在のスキーマを確認中...\n";
    $stmt = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='seating_arrangement'");
    $currentSchema = $stmt->fetch(PDO::FETCH_ASSOC)['sql'];
    echo $currentSchema . "\n\n";

    // week_dateがNOT NULLかどうかチェック
    if (strpos($currentSchema, 'week_date TEXT NOT NULL') !== false) {
        echo "⚠ week_dateにNOT NULL制約があります。マイグレーションが必要です。\n\n";

        // Step 2: 既存データ件数確認
        $stmt = $db->query("SELECT COUNT(*) as count FROM seating_arrangement");
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "[Step 2] 既存データ件数: {$count}件\n\n";

        // Step 3: マイグレーション実行
        echo "[Step 3] マイグレーションを実行中...\n";

        $migrationSQL = file_get_contents(__DIR__ . '/migrate.sql');

        if ($migrationSQL) {
            $db->exec($migrationSQL);
            echo "✓ マイグレーション完了\n\n";
        } else {
            throw new Exception('migrate.sqlファイルが見つかりません');
        }

        // Step 4: 新しいスキーマ確認
        echo "[Step 4] 新しいスキーマを確認中...\n";
        $stmt = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='seating_arrangement'");
        $newSchema = $stmt->fetch(PDO::FETCH_ASSOC)['sql'];
        echo $newSchema . "\n\n";

        // Step 5: データ整合性確認
        $stmt = $db->query("SELECT COUNT(*) as count FROM seating_arrangement");
        $newCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "[Step 5] 移行後データ件数: {$newCount}件\n\n";

        if ($count === $newCount) {
            echo "✅ データ整合性確認: OK\n";
        } else {
            echo "⚠ 警告: レコード数が一致しません (移行前: {$count}, 移行後: {$newCount})\n";
        }

        echo "\n========================================\n";
        echo "✅ マイグレーション成功\n";
        echo "========================================\n";

    } else {
        echo "✓ week_dateは既にnullableです。マイグレーション不要。\n";
        echo "\n========================================\n";
        echo "✅ マイグレーション不要\n";
        echo "========================================\n";
    }

} catch (Exception $e) {
    echo "\n========================================\n";
    echo "❌ エラー発生\n";
    echo "========================================\n";
    echo "エラーメッセージ: " . $e->getMessage() . "\n";
    echo "ファイル: " . $e->getFile() . "\n";
    echo "行番号: " . $e->getLine() . "\n";
    exit(1);
}
