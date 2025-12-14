<?php
/**
 * データベース復元スクリプト
 * バックアップからデータベースを復元します
 */

require_once __DIR__ . '/config.php';

header('Content-Type: text/plain; charset=utf-8');

echo "========================================\n";
echo "データベース復元\n";
echo "========================================\n\n";

// データベースファイルが存在するか確認
if (file_exists($db_path)) {
    echo "[1] 既存のデータベースファイルが見つかりました\n";
    echo "  パス: $db_path\n";
    echo "  サイズ: " . filesize($db_path) . " bytes\n\n";
} else {
    echo "[1] データベースファイルが存在しません\n";
    echo "  パス: $db_path\n\n";
    echo "[2] 新しいデータベースファイルを作成します...\n";

    // 空のデータベースファイルを作成
    touch($db_path);
    chmod($db_path, 0666);

    echo "  ✓ データベースファイルを作成しました\n\n";
}

// データベーススキーマを初期化
try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // テーブルが存在するか確認
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "[3] テーブルが存在しません。スキーマを作成します...\n";

        // schema.sqlを読み込んで実行
        $schemaPath = __DIR__ . '/schema.sql';
        if (file_exists($schemaPath)) {
            $schema = file_get_contents($schemaPath);
            $db->exec($schema);
            echo "  ✓ スキーマを作成しました\n\n";
        } else {
            echo "  ❌ schema.sqlが見つかりません\n\n";
        }
    } else {
        echo "[3] 既存のテーブル: " . implode(', ', $tables) . "\n\n";
    }

    // データを確認
    echo "[4] データ件数を確認:\n";

    try {
        $count = $db->query("SELECT COUNT(*) FROM members")->fetchColumn();
        echo "  - メンバー: {$count}件\n";
    } catch (Exception $e) {
        echo "  - メンバー: テーブル未作成\n";
    }

    try {
        $count = $db->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
        echo "  - ビジター: {$count}件\n";
    } catch (Exception $e) {
        echo "  - ビジター: テーブル未作成\n";
    }

    try {
        $count = $db->query("SELECT COUNT(*) FROM substitutes")->fetchColumn();
        echo "  - 代理出席: {$count}件\n";
    } catch (Exception $e) {
        echo "  - 代理出席: テーブル未作成\n";
    }

    echo "\n========================================\n";
    echo "完了\n";
    echo "========================================\n";

} catch (Exception $e) {
    echo "❌ エラー: " . $e->getMessage() . "\n";
    echo "\n詳細:\n";
    echo $e->getTraceAsString() . "\n";
}
