<?php
/**
 * 重複メンバークリーンアップスクリプトのテスト
 *
 * このスクリプトは以下を実行します：
 * 1. テスト用の重複データを作成
 * 2. cleanup_duplicate_members.php を実行
 * 3. 結果を検証
 *
 * 注意: このスクリプトはテスト環境でのみ実行してください
 */

require_once __DIR__ . '/config.php';

echo "=== 重複メンバークリーンアップスクリプトのテスト ===\n\n";

try {
    $db = getDbConnection();

    // ステップ1: 現在の状態を確認
    echo "ステップ1: 現在の状態を確認\n";
    echo str_repeat('-', 50) . "\n";

    $stmt = $db->query("SELECT COUNT(*) as total FROM members");
    $currentCount = $stmt->fetch()['total'];
    echo "現在のメンバー総数: {$currentCount}\n\n";

    // 最初の3名のメンバーを取得
    $stmt = $db->query("SELECT id, name FROM members ORDER BY id LIMIT 3");
    $testMembers = $stmt->fetchAll();

    if (count($testMembers) < 3) {
        echo "エラー: テスト用のメンバーが不足しています\n";
        exit(1);
    }

    echo "テスト用メンバー:\n";
    foreach ($testMembers as $member) {
        echo "  - ID {$member['id']}: {$member['name']}\n";
    }
    echo "\n";

    // ステップ2: テスト用の重複データを作成
    echo "ステップ2: テスト用の重複データを作成\n";
    echo str_repeat('-', 50) . "\n";

    $db->beginTransaction();

    // 各テストメンバーについて3つの重複を作成
    $duplicatesCreated = 0;
    foreach ($testMembers as $member) {
        // 元のメンバーの全データを取得
        $stmt = $db->prepare("SELECT * FROM members WHERE id = :id");
        $stmt->execute([':id' => $member['id']]);
        $originalData = $stmt->fetch();

        // 3つの重複を作成
        for ($i = 1; $i <= 3; $i++) {
            $stmt = $db->prepare("
                INSERT INTO members (name, company_name, category, photo_path, birthday, is_active)
                VALUES (:name, :company_name, :category, :photo_path, :birthday, :is_active)
            ");

            $stmt->execute([
                ':name' => $originalData['name'],
                ':company_name' => $originalData['company_name'],
                ':category' => $originalData['category'],
                ':photo_path' => $originalData['photo_path'],
                ':birthday' => $originalData['birthday'],
                ':is_active' => $originalData['is_active']
            ]);

            $newId = $db->lastInsertId();
            $duplicatesCreated++;

            echo "  重複作成: {$originalData['name']} (新ID: {$newId})\n";
        }
    }

    $db->commit();

    echo "\n作成された重複数: {$duplicatesCreated}\n\n";

    // ステップ3: 重複を確認
    echo "ステップ3: 重複状態を確認\n";
    echo str_repeat('-', 50) . "\n";

    $stmt = $db->query("SELECT COUNT(*) as total FROM members");
    $beforeCleanup = $stmt->fetch()['total'];
    echo "重複作成後のメンバー総数: {$beforeCleanup}\n";
    echo "増加数: " . ($beforeCleanup - $currentCount) . "\n\n";

    // 重複の詳細を表示
    $duplicateQuery = "
        SELECT
            name,
            COUNT(*) as count,
            MIN(id) as min_id,
            MAX(id) as max_id,
            GROUP_CONCAT(id) as all_ids
        FROM members
        GROUP BY name
        HAVING COUNT(*) > 1
    ";

    $stmt = $db->query($duplicateQuery);
    $duplicates = $stmt->fetchAll();

    echo "重複しているメンバー:\n";
    foreach ($duplicates as $dup) {
        echo "  - {$dup['name']}: {$dup['count']}件 (IDs: {$dup['all_ids']})\n";
    }
    echo "\n";

    // ステップ4: クリーンアップスクリプトを実行
    echo "ステップ4: クリーンアップスクリプトを実行\n";
    echo str_repeat('-', 50) . "\n";
    echo "cleanup_duplicate_members.php を実行中...\n\n";

    // スクリプトを実行
    $output = [];
    $returnCode = 0;
    exec('php ' . escapeshellarg(__DIR__ . '/cleanup_duplicate_members.php'), $output, $returnCode);

    // 重要な情報のみを抽出して表示
    $cleanupSummary = false;
    foreach ($output as $line) {
        // HTMLタグを除去
        $line = strip_tags($line);
        $line = trim($line);

        if (empty($line)) continue;

        // 重要な情報のみ表示
        if (strpos($line, '削除完了:') !== false ||
            strpos($line, 'クリーンアップ完了') !== false ||
            strpos($line, '削除されたレコード総数:') !== false ||
            strpos($line, 'クリーンアップ後のメンバー総数:') !== false ||
            strpos($line, '検証結果:') !== false) {
            echo "  " . $line . "\n";
        }
    }
    echo "\n";

    // ステップ5: クリーンアップ後の状態を確認
    echo "ステップ5: クリーンアップ後の状態を検証\n";
    echo str_repeat('-', 50) . "\n";

    $stmt = $db->query("SELECT COUNT(*) as total FROM members");
    $afterCleanup = $stmt->fetch()['total'];

    echo "クリーンアップ後のメンバー総数: {$afterCleanup}\n";
    echo "元の総数: {$currentCount}\n";
    echo "削除されたレコード数: " . ($beforeCleanup - $afterCleanup) . "\n\n";

    // 重複が残っていないか確認
    $stmt = $db->query($duplicateQuery);
    $remainingDuplicates = $stmt->fetchAll();

    if (count($remainingDuplicates) === 0) {
        echo "✓ 成功: 重複は完全に削除されました\n";
    } else {
        echo "✗ 警告: まだ重複が残っています\n";
        foreach ($remainingDuplicates as $dup) {
            echo "  - {$dup['name']}: {$dup['count']}件\n";
        }
    }
    echo "\n";

    // テストメンバーの最終状態を確認
    echo "テストメンバーの最終状態:\n";
    foreach ($testMembers as $member) {
        $stmt = $db->prepare("SELECT id, name FROM members WHERE name = :name");
        $stmt->execute([':name' => $member['name']]);
        $results = $stmt->fetchAll();

        echo "  - {$member['name']}: ";
        if (count($results) === 1) {
            echo "1件のみ (ID: {$results[0]['id']}) ✓\n";
        } else {
            echo count($results) . "件 (IDs: " . implode(', ', array_column($results, 'id')) . ") ✗\n";
        }
    }
    echo "\n";

    // ステップ6: テスト結果のサマリー
    echo "=== テスト結果サマリー ===\n";
    echo str_repeat('=', 50) . "\n";

    $testPassed = (
        $afterCleanup === $currentCount &&
        count($remainingDuplicates) === 0
    );

    if ($testPassed) {
        echo "✓ すべてのテストに合格しました\n";
        echo "\n";
        echo "検証項目:\n";
        echo "  ✓ 重複データの作成: {$duplicatesCreated}件\n";
        echo "  ✓ 重複データの削除: " . ($beforeCleanup - $afterCleanup) . "件\n";
        echo "  ✓ 最終メンバー数: {$afterCleanup} (元: {$currentCount})\n";
        echo "  ✓ 残存重複: 0件\n";
        echo "\n";
        echo "結論: cleanup_duplicate_members.php は正常に動作しています\n";
    } else {
        echo "✗ テストに失敗しました\n";
        echo "\n";
        echo "問題:\n";
        if ($afterCleanup !== $currentCount) {
            echo "  ✗ メンバー数が元に戻っていません (期待: {$currentCount}, 実際: {$afterCleanup})\n";
        }
        if (count($remainingDuplicates) > 0) {
            echo "  ✗ 重複が残っています (" . count($remainingDuplicates) . "件)\n";
        }
    }

    echo str_repeat('=', 50) . "\n";

} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage() . "\n";
    echo "スタックトレース:\n" . $e->getTraceAsString() . "\n";

    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
        echo "トランザクションをロールバックしました\n";
    }

    exit(1);
}
