<?php
require_once __DIR__ . '/config.php';

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $weekDate = '2025-12-20';

    // テストデータ
    $testCategories = [
        // オープン募集カテゴリ (p.185)
        ['week_date' => $weekDate, 'type' => 'urgent', 'rank' => null, 'category_name' => '税理士', 'vote_count' => 0],
        ['week_date' => $weekDate, 'type' => 'urgent', 'rank' => null, 'category_name' => '社会保険労務士', 'vote_count' => 0],
        ['week_date' => $weekDate, 'type' => 'urgent', 'rank' => null, 'category_name' => 'システム開発', 'vote_count' => 0],
        ['week_date' => $weekDate, 'type' => 'urgent', 'rank' => null, 'category_name' => '人材紹介', 'vote_count' => 0],
        ['week_date' => $weekDate, 'type' => 'urgent', 'rank' => null, 'category_name' => 'コンサルティング', 'vote_count' => 0],

        // カテゴリアンケート結果 (p.194)
        ['week_date' => $weekDate, 'type' => 'survey', 'rank' => 1, 'category_name' => '弁護士', 'vote_count' => 15],
        ['week_date' => $weekDate, 'type' => 'survey', 'rank' => 2, 'category_name' => '不動産仲介', 'vote_count' => 12],
        ['week_date' => $weekDate, 'type' => 'survey', 'rank' => 3, 'category_name' => 'マーケティング', 'vote_count' => 10],
        ['week_date' => $weekDate, 'type' => 'survey', 'rank' => 4, 'category_name' => 'デザイナー', 'vote_count' => 8],
    ];

    $db->beginTransaction();

    // 既存のテストデータを削除
    $stmt = $db->prepare("DELETE FROM recruiting_categories WHERE week_date = :week_date");
    $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
    $stmt->execute();

    // テストデータを挿入
    $stmt = $db->prepare("INSERT INTO recruiting_categories (week_date, type, rank, category_name, vote_count) VALUES (:week_date, :type, :rank, :category_name, :vote_count)");

    foreach ($testCategories as $category) {
        $stmt->bindValue(':week_date', $category['week_date'], PDO::PARAM_STR);
        $stmt->bindValue(':type', $category['type'], PDO::PARAM_STR);
        $stmt->bindValue(':rank', $category['rank'], PDO::PARAM_INT);
        $stmt->bindValue(':category_name', $category['category_name'], PDO::PARAM_STR);
        $stmt->bindValue(':vote_count', $category['vote_count'], PDO::PARAM_INT);
        $stmt->execute();
    }

    $db->commit();

    echo "✓ カテゴリーテストデータを投入しました\n";
    echo "  - オープン募集カテゴリ: 5件\n";
    echo "  - アンケート結果: 4件 (1〜4位)\n";
    echo "\n管理画面で確認: https://yojitu.com/bni-slide-system/slides_v2/admin/categories.php\n";
    echo "スライド確認:\n";
    echo "  - p.185: https://yojitu.com/bni-slide-system/slides_v2/index.php#185\n";
    echo "  - p.194: https://yojitu.com/bni-slide-system/slides_v2/index.php#194\n";

} catch (Exception $e) {
    if ($db->inTransaction()) {
        $db->rollBack();
    }
    echo "✗ エラー: " . $e->getMessage() . "\n";
}
