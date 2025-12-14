<?php
header('Content-Type: application/json');
$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';
try { $db = new SQLite3($dbPath); } catch (Exception $e) { echo json_encode(['success' => false, 'error' => 'データベース接続エラー']); exit; }
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$postData = json_decode(file_get_contents('php://input'), true);
if ($postData) { $action = $postData['action'] ?? $action; }

switch ($action) {
    case 'list':
        $query = "SELECT * FROM slide_visibility ORDER BY slide_page";
        $result = $db->query($query);
        $visibility = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) { $visibility[] = $row; }
        echo json_encode(['success' => true, 'visibility' => $visibility]);
        break;

    case 'get':
        $slidePage = $_GET['slide_page'] ?? null;
        if (!$slidePage) { echo json_encode(['success' => false, 'error' => 'ページ番号が必要です']); exit; }
        
        $stmt = $db->prepare("SELECT * FROM slide_visibility WHERE slide_page = :slide_page");
        $stmt->bindValue(':slide_page', $slidePage, SQLITE3_INTEGER);
        $result = $stmt->execute();
        $slide = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($slide) {
            echo json_encode(['success' => true, 'slide' => $slide]);
        } else {
            // デフォルトは表示
            echo json_encode(['success' => true, 'slide' => ['is_visible' => 1]]);
        }
        break;

    case 'save_all':
        $visibilityArray = $postData['visibility'] ?? [];
        if (empty($visibilityArray)) { echo json_encode(['success' => false, 'error' => 'データが不足しています']); exit; }

        $db->exec('BEGIN');
        try {
            foreach ($visibilityArray as $item) {
                $stmt = $db->prepare("SELECT id FROM slide_visibility WHERE slide_page = :slide_page");
                $stmt->bindValue(':slide_page', $item['slide_page'], SQLITE3_INTEGER);
                $result = $stmt->execute();
                $existing = $result->fetchArray(SQLITE3_ASSOC);

                if ($existing) {
                    $stmt = $db->prepare("UPDATE slide_visibility SET slide_name = :slide_name, is_visible = :is_visible, updated_at = CURRENT_TIMESTAMP WHERE slide_page = :slide_page");
                } else {
                    $stmt = $db->prepare("INSERT INTO slide_visibility (slide_page, slide_name, is_visible) VALUES (:slide_page, :slide_name, :is_visible)");
                }

                $stmt->bindValue(':slide_page', $item['slide_page'], SQLITE3_INTEGER);
                $stmt->bindValue(':slide_name', $item['slide_name'], SQLITE3_TEXT);
                $stmt->bindValue(':is_visible', $item['is_visible'], SQLITE3_INTEGER);
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
