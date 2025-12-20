<?php
/**
 * 一時的なデバッグスクリプト
 * 本番環境のレッスン重複をチェックする
 *
 * 使用後は必ず削除すること
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

// セキュリティ: 本番環境でのみ実行可能にする
if (APP_ENV !== 'development' && APP_ENV !== 'production') {
    die('Access denied');
}

echo "<h1>Lesson Duplicate Check</h1>";
echo "<p>Checking course_id = 1...</p>";

try {
    $db = Database::getInstance()->getPdo();

    $stmt = $db->prepare("
        SELECT id, course_id, title, order_num, lesson_type
        FROM lessons
        WHERE course_id = 1
        ORDER BY order_num
    ");
    $stmt->execute();
    $lessons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<h2>Total lessons found: " . count($lessons) . "</h2>";

    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Course ID</th><th>Title</th><th>Order</th><th>Type</th></tr>";

    $titles = [];
    foreach ($lessons as $lesson) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($lesson['id']) . "</td>";
        echo "<td>" . htmlspecialchars($lesson['course_id']) . "</td>";
        echo "<td>" . htmlspecialchars($lesson['title']) . "</td>";
        echo "<td>" . htmlspecialchars($lesson['order_num']) . "</td>";
        echo "<td>" . htmlspecialchars($lesson['lesson_type']) . "</td>";
        echo "</tr>";

        // 重複チェック
        if (isset($titles[$lesson['title']])) {
            echo "<tr style='background-color: #ffcccc;'>";
            echo "<td colspan='5'><strong>⚠️ DUPLICATE: '{$lesson['title']}' appears multiple times!</strong></td>";
            echo "</tr>";
        }
        $titles[$lesson['title']] = $lesson['id'];
    }

    echo "</table>";

    // 重複タイトルを検出
    $stmt = $db->prepare("
        SELECT title, COUNT(*) as count
        FROM lessons
        WHERE course_id = 1
        GROUP BY title
        HAVING count > 1
    ");
    $stmt->execute();
    $duplicates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($duplicates) > 0) {
        echo "<h2 style='color: red;'>⚠️ Duplicates Found:</h2>";
        foreach ($duplicates as $dup) {
            echo "<p><strong>" . htmlspecialchars($dup['title']) . "</strong> appears " . $dup['count'] . " times</p>";
        }
    } else {
        echo "<h2 style='color: green;'>✅ No duplicates found</h2>";
    }

} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<hr>";
echo "<p><strong>Note:</strong> このファイルは確認後に削除してください。</p>";
