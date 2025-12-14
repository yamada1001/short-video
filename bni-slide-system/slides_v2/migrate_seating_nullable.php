<?php
/**
 * Migration: Make seating_arrangement.week_date nullable
 * 座席配置テーブルのweek_dateをNULL許容に変更
 */

require_once __DIR__ . '/config.php';

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("PRAGMA busy_timeout = 5000");
    $db->exec("PRAGMA foreign_keys = OFF");

    echo "========================================\n";
    echo "Migration: seating_arrangement.week_date を NULL許容に変更\n";
    echo "========================================\n\n";

    // Step 1: 既存データを確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM seating_arrangement");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "✓ 既存レコード数: {$count}件\n\n";

    // トランザクション開始
    $db->beginTransaction();

    try {
        // Step 2: 新しいテーブルを作成（week_dateをnullableに）
        echo "Step 1: 新しいテーブルを作成中...\n";
        $db->exec("
            CREATE TABLE seating_arrangement_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                table_name TEXT NOT NULL,
                position INTEGER NOT NULL,
                member_id INTEGER,
                week_date TEXT,  -- NOT NULL制約を削除
                created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE SET NULL
            )
        ");
        echo "✓ 新テーブル作成完了\n\n";

        // Step 3: データをコピー
        echo "Step 2: データをコピー中...\n";
        $db->exec("
            INSERT INTO seating_arrangement_new (id, table_name, position, member_id, week_date, created_at, updated_at)
            SELECT id, table_name, position, member_id, week_date, created_at, updated_at
            FROM seating_arrangement
        ");
        echo "✓ データコピー完了\n\n";

        // Step 4: 古いテーブルを削除
        echo "Step 3: 古いテーブルを削除中...\n";
        $db->exec("DROP TABLE seating_arrangement");
        echo "✓ 削除完了\n\n";

        // Step 5: 新しいテーブルをリネーム
        echo "Step 4: テーブルをリネーム中...\n";
        $db->exec("ALTER TABLE seating_arrangement_new RENAME TO seating_arrangement");
        echo "✓ リネーム完了\n\n";

        // Step 6: インデックスを再作成
        echo "Step 5: インデックス再作成中...\n";
        $db->exec("CREATE INDEX idx_seating_week ON seating_arrangement(week_date)");
        $db->exec("CREATE INDEX idx_seating_member ON seating_arrangement(member_id)");
        echo "✓ インデックス作成完了\n\n";

        // コミット
        $db->commit();
        echo "✓ トランザクションコミット完了\n\n";

    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }

    // 外部キー制約を再度有効化
    $db->exec("PRAGMA foreign_keys = ON");

    // Step 7: 最終確認
    $newSchema = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='seating_arrangement'")->fetch(PDO::FETCH_ASSOC)['sql'];
    echo "========================================\n";
    echo "✅ マイグレーション完了\n";
    echo "========================================\n\n";
    echo "新しいスキーマ:\n";
    echo $newSchema . "\n\n";

    // データ件数確認
    $stmt = $db->query("SELECT COUNT(*) as count FROM seating_arrangement");
    $newCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "✓ 移行後レコード数: {$newCount}件\n";

    if ($count === $newCount) {
        echo "✓ データ整合性確認: OK\n";
    } else {
        echo "⚠ 警告: レコード数が一致しません\n";
    }

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
    exit(1);
}
