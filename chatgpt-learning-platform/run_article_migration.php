<?php
/**
 * 記事型レッスンタイプを追加するマイグレーションスクリプト
 * 実行方法: php run_article_migration.php
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

echo "=== 記事型レッスンタイプ追加マイグレーション ===\n\n";

try {
    // 現在のlesson_typeの定義を確認
    echo "1. 現在のlesson_type定義を確認中...\n";
    $result = db()->fetchOne("SHOW COLUMNS FROM lessons LIKE 'lesson_type'");
    echo "   現在の定義: " . $result['Type'] . "\n\n";

    // 'article'が既に含まれているかチェック
    if (strpos($result['Type'], 'article') !== false) {
        echo "✅ 既に'article'タイプが存在します。マイグレーション不要です。\n";
        exit(0);
    }

    // ENUMに'article'を追加
    echo "2. lesson_typeに'article'を追加中...\n";
    $sql = "ALTER TABLE lessons MODIFY COLUMN lesson_type ENUM('slide', 'editor', 'quiz', 'assignment', 'article') NOT NULL";
    db()->query($sql);
    echo "   ✅ 追加完了\n\n";

    // 確認
    echo "3. 更新後の定義を確認中...\n";
    $result = db()->fetchOne("SHOW COLUMNS FROM lessons LIKE 'lesson_type'");
    echo "   更新後の定義: " . $result['Type'] . "\n\n";

    echo "✅ マイグレーション成功！\n";
    echo "   これで lesson_type = 'article' を使用できます。\n";

} catch (Exception $e) {
    echo "❌ エラーが発生しました: " . $e->getMessage() . "\n";
    exit(1);
}
