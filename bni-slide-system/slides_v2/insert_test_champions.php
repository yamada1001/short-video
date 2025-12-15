<?php
/**
 * Test Champions Data Insertion Script
 * チャンピオンテストデータ挿入スクリプト
 */

require_once __DIR__ . '/config.php';

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Champions test data insertion started...\n";

    // Get active member IDs from database
    $stmt = $db->query("SELECT id, name FROM members WHERE is_active = 1 LIMIT 15");
    $members = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $members[] = $row;
    }

    if (count($members) < 10) {
        echo "Warning: Less than 10 active members found. Using first available members.\n";
    }

    if (count($members) === 0) {
        echo "ERROR: No members found in database. Please insert members first.\n";
        exit(1);
    }

    echo "Found " . count($members) . " active members.\n";

    $weekDate = '2025-12-20'; // テスト用の日付

    // テストチャンピオンデータ
    $testChampions = [
        // Referral Champion (リファーラルチャンピオン) - p.91
        ['week_date' => $weekDate, 'category' => 'referral', 'rank' => 1, 'member_id' => $members[0]['id'] ?? 1, 'count' => 25],
        ['week_date' => $weekDate, 'category' => 'referral', 'rank' => 2, 'member_id' => $members[1]['id'] ?? 2, 'count' => 20],
        ['week_date' => $weekDate, 'category' => 'referral', 'rank' => 3, 'member_id' => $members[2]['id'] ?? 3, 'count' => 15],
        ['week_date' => $weekDate, 'category' => 'referral', 'rank' => 3, 'member_id' => $members[3]['id'] ?? 4, 'count' => 15], // 同率3位

        // Value Champion (バリューチャンピオン) - p.92
        ['week_date' => $weekDate, 'category' => 'value', 'rank' => 1, 'member_id' => $members[4]['id'] ?? 5, 'count' => 1500000],
        ['week_date' => $weekDate, 'category' => 'value', 'rank' => 2, 'member_id' => $members[5]['id'] ?? 6, 'count' => 1200000],
        ['week_date' => $weekDate, 'category' => 'value', 'rank' => 3, 'member_id' => $members[6]['id'] ?? 7, 'count' => 800000],

        // Visitor Champion (ビジターチャンピオン) - p.93
        ['week_date' => $weekDate, 'category' => 'visitor', 'rank' => 1, 'member_id' => $members[7]['id'] ?? 8, 'count' => 8],
        ['week_date' => $weekDate, 'category' => 'visitor', 'rank' => 2, 'member_id' => $members[8]['id'] ?? 9, 'count' => 6],
        ['week_date' => $weekDate, 'category' => 'visitor', 'rank' => 3, 'member_id' => $members[9]['id'] ?? 10, 'count' => 5],

        // 1to1 Champion (1to1チャンピオン) - p.94
        ['week_date' => $weekDate, 'category' => '1to1', 'rank' => 1, 'member_id' => $members[10]['id'] ?? 11, 'count' => 12],
        ['week_date' => $weekDate, 'category' => '1to1', 'rank' => 2, 'member_id' => $members[11]['id'] ?? 12, 'count' => 10],
        ['week_date' => $weekDate, 'category' => '1to1', 'rank' => 2, 'member_id' => $members[12]['id'] ?? 13, 'count' => 10], // 同率2位
        ['week_date' => $weekDate, 'category' => '1to1', 'rank' => 3, 'member_id' => $members[13]['id'] ?? 14, 'count' => 8],

        // CEU Champion (CEUチャンピオン) - p.95
        ['week_date' => $weekDate, 'category' => 'ceu', 'rank' => 1, 'member_id' => $members[14]['id'] ?? 15, 'count' => 24],
        ['week_date' => $weekDate, 'category' => 'ceu', 'rank' => 2, 'member_id' => $members[0]['id'] ?? 1, 'count' => 20],
        ['week_date' => $weekDate, 'category' => 'ceu', 'rank' => 3, 'member_id' => $members[1]['id'] ?? 2, 'count' => 16],
    ];

    $db->beginTransaction();

    // 既存のテストデータを削除
    $stmt = $db->prepare("DELETE FROM champions WHERE week_date = :week_date");
    $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
    $stmt->execute();
    echo "Deleted existing test champions for week_date: $weekDate\n";

    // 新しいテストデータを挿入
    $stmt = $db->prepare("
        INSERT INTO champions (week_date, category, rank, member_id, count, created_at, updated_at)
        VALUES (:week_date, :category, :rank, :member_id, :count, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
    ");

    $insertedCount = 0;
    foreach ($testChampions as $champion) {
        $stmt->bindValue(':week_date', $champion['week_date'], PDO::PARAM_STR);
        $stmt->bindValue(':category', $champion['category'], PDO::PARAM_STR);
        $stmt->bindValue(':rank', $champion['rank'], PDO::PARAM_INT);
        $stmt->bindValue(':member_id', $champion['member_id'], PDO::PARAM_INT);
        $stmt->bindValue(':count', $champion['count'], PDO::PARAM_INT);
        $stmt->execute();
        $insertedCount++;

        // Display member name if available
        $memberName = '';
        foreach ($members as $m) {
            if ($m['id'] == $champion['member_id']) {
                $memberName = " (" . $m['name'] . ")";
                break;
            }
        }
        echo "  - {$champion['category']} {$champion['rank']}位: Member ID {$champion['member_id']}{$memberName} - {$champion['count']}件\n";
    }

    $db->commit();

    echo "\n✓ Successfully inserted $insertedCount test champions!\n";
    echo "  - Referral Champions: 4件 (p.91)\n";
    echo "  - Value Champions: 3件 (p.92)\n";
    echo "  - Visitor Champions: 3件 (p.93)\n";
    echo "  - 1to1 Champions: 4件 (p.94)\n";
    echo "  - CEU Champions: 3件 (p.95)\n";
    echo "\nPlease check the slides at:\n";
    echo "  - https://yojitu.com/bni-slide-system/slides_v2/index.php#91\n";
    echo "  - https://yojitu.com/bni-slide-system/slides_v2/index.php#92\n";
    echo "  - https://yojitu.com/bni-slide-system/slides_v2/index.php#93\n";
    echo "  - https://yojitu.com/bni-slide-system/slides_v2/index.php#94\n";
    echo "  - https://yojitu.com/bni-slide-system/slides_v2/index.php#95\n";
    echo "  - https://yojitu.com/bni-slide-system/slides_v2/index.php#96\n";

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
