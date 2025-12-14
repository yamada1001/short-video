<?php
/**
 * BNI Slide System V2 - 重複メンバーデータクリーンアップスクリプト
 *
 * このスクリプトは重複したメンバーレコードを削除します。
 * 同じ名前を持つメンバーが複数存在する場合、最も古い（IDが最小の）レコードのみを保持します。
 *
 * 実行方法:
 * php cleanup_duplicate_members.php
 *
 * または、ブラウザから直接アクセス（開発環境のみ推奨）
 */

require_once __DIR__ . '/config.php';

// セキュリティ: 本番環境での実行を防ぐ（必要に応じてコメントアウト）
// if ($_SERVER['SERVER_NAME'] !== 'localhost' && php_sapi_name() !== 'cli') {
//     die('このスクリプトは本番環境では実行できません。');
// }

// HTMLヘッダー（CLI実行時は表示されない）
if (php_sapi_name() !== 'cli') {
    echo "<!DOCTYPE html>\n<html>\n<head>\n";
    echo "<meta charset='UTF-8'>\n";
    echo "<title>重複メンバークリーンアップ</title>\n";
    echo "<style>\n";
    echo "body { font-family: Arial, sans-serif; max-width: 1200px; margin: 20px auto; padding: 20px; }\n";
    echo "h1 { color: #C8102E; }\n";
    echo ".info { background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 10px 0; }\n";
    echo ".success { background: #c8e6c9; padding: 15px; border-radius: 5px; margin: 10px 0; }\n";
    echo ".warning { background: #fff3e0; padding: 15px; border-radius: 5px; margin: 10px 0; }\n";
    echo ".error { background: #ffcdd2; padding: 15px; border-radius: 5px; margin: 10px 0; }\n";
    echo "table { border-collapse: collapse; width: 100%; margin: 20px 0; }\n";
    echo "th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }\n";
    echo "th { background-color: #C8102E; color: white; }\n";
    echo "tr:nth-child(even) { background-color: #f2f2f2; }\n";
    echo ".code { background: #f5f5f5; padding: 10px; border-radius: 3px; font-family: monospace; }\n";
    echo "</style>\n";
    echo "</head>\n<body>\n";
}

echo "<h1>重複メンバーデータクリーンアップ</h1>\n";
echo "<p>実行日時: " . date('Y-m-d H:i:s') . "</p>\n";

try {
    // データベース接続
    $db = getDbConnection();

    echo "<div class='info'><strong>ステップ 1:</strong> クリーンアップ前の状態を確認</div>\n";

    // クリーンアップ前のメンバー総数
    $stmt = $db->query("SELECT COUNT(*) as total FROM members");
    $beforeCount = $stmt->fetch()['total'];
    echo "<p>現在のメンバー総数: <strong>{$beforeCount}</strong></p>\n";

    // 重複レコードの確認
    $duplicateQuery = "
        SELECT
            name,
            COUNT(*) as duplicate_count,
            MIN(id) as keep_id,
            GROUP_CONCAT(id) as all_ids
        FROM members
        GROUP BY name
        HAVING COUNT(*) > 1
        ORDER BY name
    ";

    $stmt = $db->query($duplicateQuery);
    $duplicates = $stmt->fetchAll();
    $duplicateCount = count($duplicates);

    if ($duplicateCount === 0) {
        echo "<div class='success'>";
        echo "<h2>重複データは見つかりませんでした</h2>";
        echo "<p>データベースは正常な状態です。クリーンアップは不要です。</p>";
        echo "</div>\n";

        // 全メンバーリストを表示
        echo "<h3>現在のメンバー一覧（ID順）</h3>\n";
        $stmt = $db->query("SELECT id, name FROM members ORDER BY id");
        $allMembers = $stmt->fetchAll();

        echo "<table>\n";
        echo "<tr><th>ID</th><th>名前</th></tr>\n";
        foreach ($allMembers as $member) {
            echo "<tr><td>{$member['id']}</td><td>{$member['name']}</td></tr>\n";
        }
        echo "</table>\n";

    } else {
        echo "<div class='warning'>";
        echo "<h2>重複データが見つかりました</h2>";
        echo "<p>重複している名前の数: <strong>{$duplicateCount}</strong></p>";
        echo "</div>\n";

        // 重複データの詳細を表示
        echo "<h3>重複データの詳細</h3>\n";
        echo "<table>\n";
        echo "<tr><th>名前</th><th>重複数</th><th>保持するID</th><th>すべてのID</th></tr>\n";

        foreach ($duplicates as $dup) {
            echo "<tr>";
            echo "<td>{$dup['name']}</td>";
            echo "<td>{$dup['duplicate_count']}</td>";
            echo "<td style='background-color: #c8e6c9;'><strong>{$dup['keep_id']}</strong></td>";
            echo "<td>{$dup['all_ids']}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";

        // クリーンアップ実行
        echo "<div class='info'><strong>ステップ 2:</strong> 重複データを削除中...</div>\n";

        $db->beginTransaction();

        $deletedTotal = 0;
        $cleanedNames = [];

        foreach ($duplicates as $dup) {
            $name = $dup['name'];
            $keepId = $dup['keep_id'];

            // 最小ID以外のレコードを削除
            $deleteStmt = $db->prepare("
                DELETE FROM members
                WHERE name = :name AND id != :keep_id
            ");

            $deleteStmt->execute([
                ':name' => $name,
                ':keep_id' => $keepId
            ]);

            $deletedCount = $deleteStmt->rowCount();
            $deletedTotal += $deletedCount;

            $cleanedNames[] = [
                'name' => $name,
                'keep_id' => $keepId,
                'deleted_count' => $deletedCount,
                'all_ids' => $dup['all_ids']
            ];

            echo "<p>削除完了: <strong>{$name}</strong> - ID {$keepId} を保持、{$deletedCount}件の重複を削除</p>\n";
        }

        // トランザクションをコミット
        $db->commit();

        echo "<div class='success'>";
        echo "<h2>クリーンアップ完了</h2>";
        echo "<p>削除されたレコード総数: <strong>{$deletedTotal}</strong></p>";
        echo "</div>\n";

        // クリーンアップ後の確認
        echo "<div class='info'><strong>ステップ 3:</strong> クリーンアップ後の状態を確認</div>\n";

        $stmt = $db->query("SELECT COUNT(*) as total FROM members");
        $afterCount = $stmt->fetch()['total'];

        echo "<p>クリーンアップ後のメンバー総数: <strong>{$afterCount}</strong></p>\n";
        echo "<p>削除されたレコード: <strong>" . ($beforeCount - $afterCount) . "</strong></p>\n";

        // クリーンアップされた名前の詳細
        echo "<h3>クリーンアップされたメンバーの詳細</h3>\n";
        echo "<table>\n";
        echo "<tr><th>名前</th><th>保持したID</th><th>削除した数</th><th>削除前のID一覧</th></tr>\n";

        foreach ($cleanedNames as $cleaned) {
            echo "<tr>";
            echo "<td>{$cleaned['name']}</td>";
            echo "<td style='background-color: #c8e6c9;'><strong>{$cleaned['keep_id']}</strong></td>";
            echo "<td>{$cleaned['deleted_count']}</td>";
            echo "<td>{$cleaned['all_ids']}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";

        // 重複チェック（念のため再確認）
        $stmt = $db->query($duplicateQuery);
        $remainingDuplicates = $stmt->fetchAll();

        if (count($remainingDuplicates) === 0) {
            echo "<div class='success'>";
            echo "<h3>検証結果: 重複データは完全に削除されました</h3>";
            echo "</div>\n";
        } else {
            echo "<div class='error'>";
            echo "<h3>警告: まだ重複データが残っています</h3>";
            echo "<pre>" . print_r($remainingDuplicates, true) . "</pre>";
            echo "</div>\n";
        }

        // クリーンアップ後のメンバー一覧（最初の50件）
        echo "<h3>クリーンアップ後のメンバー一覧（ID順・最初の50件）</h3>\n";
        $stmt = $db->query("SELECT id, name FROM members ORDER BY id LIMIT 50");
        $currentMembers = $stmt->fetchAll();

        echo "<table>\n";
        echo "<tr><th>ID</th><th>名前</th></tr>\n";
        foreach ($currentMembers as $member) {
            echo "<tr><td>{$member['id']}</td><td>{$member['name']}</td></tr>\n";
        }
        echo "</table>\n";
    }

    // サマリー
    echo "<div class='info'>";
    echo "<h2>クリーンアップサマリー</h2>";
    echo "<ul>";
    echo "<li>実行前のメンバー総数: {$beforeCount}</li>";
    if (isset($afterCount)) {
        echo "<li>実行後のメンバー総数: {$afterCount}</li>";
        echo "<li>削除されたレコード数: " . ($beforeCount - $afterCount) . "</li>";
        echo "<li>クリーンアップされた名前の数: {$duplicateCount}</li>";
    } else {
        echo "<li>実行後のメンバー総数: {$beforeCount}（変更なし）</li>";
        echo "<li>削除されたレコード数: 0</li>";
    }
    echo "</ul>";
    echo "</div>\n";

    // 実行したSQLの説明
    echo "<h3>実行されたSQL処理</h3>\n";
    echo "<div class='code'>";
    echo "<p><strong>1. 重複検出クエリ:</strong></p>";
    echo "<pre>SELECT name, COUNT(*) as duplicate_count, MIN(id) as keep_id, GROUP_CONCAT(id) as all_ids\n";
    echo "FROM members \n";
    echo "GROUP BY name \n";
    echo "HAVING COUNT(*) > 1\n";
    echo "ORDER BY name</pre>\n";

    echo "<p><strong>2. 重複削除クエリ（各名前ごとに実行）:</strong></p>";
    echo "<pre>DELETE FROM members \n";
    echo "WHERE name = :name AND id != :keep_id</pre>\n";
    echo "<p>このクエリは最小IDを持つレコード以外をすべて削除します。</p>";
    echo "</div>\n";

} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }

    echo "<div class='error'>";
    echo "<h2>エラーが発生しました</h2>";
    echo "<p>エラーメッセージ: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>スタックトレース:</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    echo "</div>\n";

    error_log("重複メンバークリーンアップエラー: " . $e->getMessage());
}

// HTMLフッター
if (php_sapi_name() !== 'cli') {
    echo "\n<hr>\n";
    echo "<p><a href='admin/members.php'>メンバー管理画面に戻る</a></p>\n";
    echo "</body>\n</html>\n";
}
