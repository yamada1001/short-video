<?php
/**
 * マイグレーション: substitutesテーブルのweek_dateをNULLABLEに変更
 * URL: https://yojitu.com/bni-slide-system/slides_v2/migrate_fix_substitutes_week_date.php
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "Migration: Fix substitutes week_date to NULLABLE\n";
echo "========================================\n\n";

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // トランザクション開始
    $db->beginTransaction();

    echo "[1] 既存データをバックアップ中...\n";

    // 既存データを取得
    $stmt = $db->query("SELECT * FROM substitutes");
    $existingData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "  ✓ " . count($existingData) . " 件のデータをバックアップ\n\n";

    echo "[2] テーブルを再作成中...\n";

    // 古いテーブルを削除
    $db->exec("DROP TABLE IF EXISTS substitutes");

    // 新しいテーブルを作成（week_dateをNULLABLE）
    $db->exec("
        CREATE TABLE substitutes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            week_date TEXT,
            member_id INTEGER NOT NULL,
            substitute_company TEXT NOT NULL,
            substitute_name TEXT NOT NULL,
            substitute_no INTEGER NOT NULL DEFAULT 1,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES members(id) ON DELETE CASCADE
        )
    ");

    echo "  ✓ テーブル再作成完了\n\n";

    // データを復元
    if (!empty($existingData)) {
        echo "[3] データを復元中...\n";

        $insertStmt = $db->prepare("
            INSERT INTO substitutes
            (id, week_date, member_id, substitute_company, substitute_name, substitute_no, created_at, updated_at)
            VALUES
            (:id, :week_date, :member_id, :substitute_company, :substitute_name, :substitute_no, :created_at, :updated_at)
        ");

        foreach ($existingData as $row) {
            $insertStmt->execute([
                ':id' => $row['id'],
                ':week_date' => $row['week_date'] ?? null,
                ':member_id' => $row['member_id'],
                ':substitute_company' => $row['substitute_company'],
                ':substitute_name' => $row['substitute_name'],
                ':substitute_no' => $row['substitute_no'] ?? 1,
                ':created_at' => $row['created_at'],
                ':updated_at' => $row['updated_at']
            ]);
        }

        echo "  ✓ " . count($existingData) . " 件のデータを復元\n\n";
    }

    $db->commit();

    // 確認
    echo "[4] スキーマ確認:\n";
    $schemaStmt = $db->query("PRAGMA table_info(substitutes)");
    $columns = $schemaStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($columns as $col) {
        $nullable = $col['notnull'] == 0 ? 'NULL' : 'NOT NULL';
        echo "  - {$col['name']} ({$col['type']}) {$nullable}\n";
    }

    echo "\n✅ マイグレーション完了\n";

    echo "\n========================================\n";
    echo "完了\n";
    echo "========================================\n";

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo "❌ エラー: " . $e->getMessage() . "\n";
    echo "\n詳細:\n";
    echo $e->getTraceAsString() . "\n";
}
