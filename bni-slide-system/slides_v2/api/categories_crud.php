<?php
header('Content-Type: application/json');
$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';
try { $db = new SQLite3($dbPath); } catch (Exception $e) { echo json_encode(['success' => false, 'error' => 'データベース接続エラー']); exit; }
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$postData = json_decode(file_get_contents('php://input'), true);
if ($postData) { $action = $postData['action'] ?? $action; }

switch ($action) {
    case 'get':
        $weekDate = $_GET['week_date'] ?? null;
        if (!$weekDate) { echo json_encode(['success' => false, 'error' => '日付が必要です']); exit; }
        $stmt = $db->prepare("SELECT * FROM recruiting_categories WHERE week_date = :week_date ORDER BY category_type, rank");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();
        $categories = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) { $categories[] = $row; }
        echo json_encode(['success' => true, 'categories' => $categories]);
        break;

    case 'get_by_type':
        $weekDate = $_GET['week_date'] ?? null;
        $categoryType = $_GET['category_type'] ?? null;
        if (!$weekDate || !$categoryType) { echo json_encode(['success' => false, 'error' => '日付とタイプが必要です']); exit; }
        $stmt = $db->prepare("SELECT * FROM recruiting_categories WHERE week_date = :week_date AND category_type = :category_type ORDER BY rank");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':category_type', $categoryType, SQLITE3_TEXT);
        $result = $stmt->execute();
        $categories = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) { $categories[] = $row; }
        echo json_encode(['success' => true, 'categories' => $categories]);
        break;

    case 'save':
        $weekDate = $postData['week_date'] ?? null;
        $categoryType = $postData['category_type'] ?? null;
        $categoriesData = $postData['categories'] ?? [];
        if (!$weekDate || !$categoryType) { echo json_encode(['success' => false, 'error' => '必要なデータが不足しています']); exit; }

        $db->exec('BEGIN');
        try {
            $stmt = $db->prepare("DELETE FROM recruiting_categories WHERE week_date = :week_date AND category_type = :category_type");
            $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
            $stmt->bindValue(':category_type', $categoryType, SQLITE3_TEXT);
            $stmt->execute();

            foreach ($categoriesData as $category) {
                $stmt = $db->prepare("INSERT INTO recruiting_categories (week_date, category_type, rank, category_name, vote_count) VALUES (:week_date, :category_type, :rank, :category_name, :vote_count)");
                $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
                $stmt->bindValue(':category_type', $categoryType, SQLITE3_TEXT);
                $stmt->bindValue(':rank', $category['rank'], SQLITE3_INTEGER);
                $stmt->bindValue(':category_name', $category['category_name'], SQLITE3_TEXT);
                $stmt->bindValue(':vote_count', $category['vote_count'], SQLITE3_INTEGER);
                $stmt->execute();
            }

            $db->exec('COMMIT');
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $db->exec('ROLLBACK');
            echo json_encode(['success' => false, 'error' => 'データベースエラー: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '無効なアクションです']);
        break;
}

$db->close();
