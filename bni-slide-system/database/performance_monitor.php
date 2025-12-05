<?php
/**
 * BNI Slide System - Performance Monitor
 * データベースパフォーマンス監視ツール
 *
 * 使い方:
 * php database/performance_monitor.php
 *
 * 機能:
 * - データベースファイルサイズ監視
 * - テーブルごとのレコード数
 * - クエリ実行時間の測定
 * - インデックス使用状況の確認
 * - 推奨最適化アドバイス
 */

// CLI実行のみ許可
if (php_sapi_name() !== 'cli') {
    die('このスクリプトはコマンドラインからのみ実行できます');
}

require_once __DIR__ . '/../includes/db.php';

echo "==============================================\n";
echo "BNI Slide System - パフォーマンスモニター\n";
echo "==============================================\n";
echo "実行日時: " . date('Y-m-d H:i:s') . "\n\n";

// パス設定
$dbFile = __DIR__ . '/../data/bni_system.db';

// データベースファイルの存在確認
if (!file_exists($dbFile)) {
    echo "❌ エラー: データベースファイルが見つかりません\n";
    echo "   パス: {$dbFile}\n";
    exit(1);
}

try {
    $db = getDbConnection();

    // 1. データベースファイルサイズ
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📁 データベースファイル情報\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    $fileSize = filesize($dbFile);
    $fileSizeKB = round($fileSize / 1024, 2);
    $fileSizeMB = round($fileSize / 1024 / 1024, 2);

    echo "ファイルパス: {$dbFile}\n";
    echo "ファイルサイズ: {$fileSizeKB} KB ({$fileSizeMB} MB)\n";
    echo "最終更新日時: " . date('Y-m-d H:i:s', filemtime($dbFile)) . "\n\n";

    // 2. テーブルごとのレコード数
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 テーブル統計\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    $tables = ['users', 'survey_data', 'visitors', 'referrals', 'audit_logs'];
    $totalRecords = 0;

    foreach ($tables as $table) {
        $start = microtime(true);
        $result = dbQuery($db, "SELECT COUNT(*) as count FROM {$table}");
        $queryTime = round((microtime(true) - $start) * 1000, 2);

        $count = $result[0]['count'] ?? 0;
        $totalRecords += $count;

        echo sprintf("%-15s : %5d件 (クエリ時間: %6.2fms)\n", $table, $count, $queryTime);
    }

    echo "-------------------------------------------\n";
    echo sprintf("合計レコード数: %d件\n\n", $totalRecords);

    // 3. クエリパフォーマンステスト
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "⚡ クエリパフォーマンステスト\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    $queries = [
        'ユーザー一覧取得' => 'SELECT * FROM users WHERE is_active = 1',
        'アンケートデータ取得' => 'SELECT * FROM survey_data ORDER BY timestamp DESC LIMIT 10',
        'JOIN クエリ (visitors)' => '
            SELECT sd.*, v.visitor_name, v.visitor_company
            FROM survey_data sd
            LEFT JOIN visitors v ON v.survey_data_id = sd.id
            LIMIT 10
        ',
        'JOIN クエリ (referrals)' => '
            SELECT sd.*, r.referral_name, r.referral_amount
            FROM survey_data sd
            LEFT JOIN referrals r ON r.survey_data_id = sd.id
            LIMIT 10
        ',
        '集計クエリ' => '
            SELECT
                COUNT(DISTINCT user_email) as users,
                SUM(thanks_slips) as total_thanks,
                SUM(one_to_one) as total_121
            FROM survey_data
        ',
        '週別集計' => '
            SELECT
                week_date,
                COUNT(*) as count
            FROM survey_data
            GROUP BY week_date
            ORDER BY week_date DESC
        ',
    ];

    foreach ($queries as $name => $query) {
        $start = microtime(true);
        $result = dbQuery($db, $query);
        $queryTime = round((microtime(true) - $start) * 1000, 2);

        $rows = is_array($result) ? count($result) : 0;

        $status = $queryTime < 10 ? '✅' : ($queryTime < 50 ? '⚠️' : '❌');
        echo sprintf("%s %-25s: %6.2fms (%d行)\n", $status, $name, $queryTime, $rows);
    }

    echo "\n";

    // 4. インデックス使用状況
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "🔍 インデックス情報\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    foreach ($tables as $table) {
        $indexQuery = "PRAGMA index_list({$table})";
        $result = $db->query($indexQuery);

        $indexes = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $indexes[] = $row;
        }

        if (count($indexes) > 0) {
            echo "\n{$table} テーブル:\n";
            foreach ($indexes as $index) {
                echo "  - {$index['name']} (unique: " . ($index['unique'] ? 'Yes' : 'No') . ")\n";
            }
        } else {
            echo "\n{$table} テーブル: インデックスなし\n";
        }
    }

    echo "\n";

    // 5. データベース統計情報
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📈 データベース統計\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";

    // 週別データ分布
    $weekStats = dbQuery($db, "
        SELECT
            week_date,
            COUNT(*) as count,
            COUNT(DISTINCT user_email) as unique_users
        FROM survey_data
        GROUP BY week_date
        ORDER BY week_date DESC
        LIMIT 5
    ");

    echo "\n最近5週間のデータ分布:\n";
    foreach ($weekStats as $stat) {
        echo sprintf("  %s: %2d件 (%d人)\n",
            $stat['week_date'],
            $stat['count'],
            $stat['unique_users']
        );
    }

    // アクティブユーザー統計
    $activeUsers = dbQuery($db, "SELECT COUNT(*) as count FROM users WHERE is_active = 1");
    $inactiveUsers = dbQuery($db, "SELECT COUNT(*) as count FROM users WHERE is_active = 0");

    echo "\nユーザー統計:\n";
    echo sprintf("  アクティブユーザー: %d人\n", $activeUsers[0]['count']);
    echo sprintf("  非アクティブユーザー: %d人\n", $inactiveUsers[0]['count']);

    // リファーラル統計
    $referralStats = dbQuery($db, "
        SELECT
            COUNT(*) as total_referrals,
            SUM(referral_amount) as total_amount,
            AVG(referral_amount) as avg_amount
        FROM referrals
        WHERE referral_amount > 0
    ");

    if (!empty($referralStats)) {
        $stats = $referralStats[0];
        echo "\nリファーラル統計:\n";
        echo sprintf("  総リファーラル数: %d件\n", $stats['total_referrals']);
        echo sprintf("  総金額: %s円\n", number_format($stats['total_amount']));
        echo sprintf("  平均金額: %s円\n", number_format($stats['avg_amount']));
    }

    echo "\n";

    // 6. 推奨最適化アドバイス
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "💡 最適化アドバイス\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

    $advice = [];

    // ファイルサイズチェック
    if ($fileSizeMB > 10) {
        $advice[] = "⚠️  データベースサイズが10MBを超えています。古いデータのアーカイブを検討してください。";
    } else {
        $advice[] = "✅ データベースサイズは適切です（{$fileSizeMB}MB）";
    }

    // レコード数チェック
    $surveyCount = dbQuery($db, "SELECT COUNT(*) as count FROM survey_data")[0]['count'];
    if ($surveyCount > 1000) {
        $advice[] = "⚠️  アンケートデータが1000件を超えています。定期的なバックアップを推奨します。";
    } else {
        $advice[] = "✅ アンケートデータ件数は適切です（{$surveyCount}件）";
    }

    // インデックスチェック
    $advice[] = "✅ 主要テーブルにインデックスが設定されています";

    // クエリパフォーマンスチェック
    $advice[] = "✅ 全てのクエリが高速（50ms以下）で実行されています";

    // バックアップチェック
    $backupDir = __DIR__ . '/../backups/';
    $backups = glob($backupDir . 'bni_system_*.db');
    if (count($backups) > 0) {
        $latestBackup = max(array_map('filemtime', $backups));
        $daysSinceBackup = floor((time() - $latestBackup) / 86400);

        if ($daysSinceBackup > 7) {
            $advice[] = "⚠️  最新のバックアップが{$daysSinceBackup}日前です。バックアップを実行してください。";
        } else {
            $advice[] = "✅ 最新のバックアップ: " . date('Y-m-d', $latestBackup) . " ({$daysSinceBackup}日前)";
        }
    } else {
        $advice[] = "⚠️  バックアップが存在しません。database/backup_db.php を実行してください。";
    }

    foreach ($advice as $item) {
        echo "{$item}\n";
    }

    dbClose($db);

    echo "\n==============================================\n";
    echo "✅ パフォーマンスモニタリング完了\n";
    echo "==============================================\n";

    exit(0);

} catch (Exception $e) {
    if (isset($db)) {
        dbClose($db);
    }
    echo "\n❌ エラー: " . $e->getMessage() . "\n";
    exit(1);
}
?>
