<?php
require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');
try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) { echo json_encode(['success' => false, 'error' => 'データベース接続エラー']); exit; }
$action = $_GET['action'] ?? $_POST['action'] ?? null;
$postData = json_decode(file_get_contents('php://input'), true);
if ($postData) { $action = $postData['action'] ?? $action; }

// 対象週を取得
$weekDate = $_GET['week_date'] ?? $postData['week_date'] ?? getTargetFriday();

switch ($action) {
    case 'list':
        $query = "SELECT * FROM slide_visibility WHERE week_date = :week_date ORDER BY slide_number";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();
        $visibility = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { $visibility[] = $row; }
        echo json_encode(['success' => true, 'visibility' => $visibility, 'week_date' => $weekDate]);
        break;

    case 'get':
        $slideNumber = $_GET['slide_number'] ?? null;
        if (!$slideNumber) { echo json_encode(['success' => false, 'error' => 'ページ番号が必要です']); exit; }

        $stmt = $db->prepare("SELECT * FROM slide_visibility WHERE week_date = :week_date AND slide_number = :slide_number");
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':slide_number', $slideNumber, PDO::PARAM_INT);
        $stmt->execute();
        $slide = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($slide) {
            echo json_encode(['success' => true, 'slide' => $slide]);
        } else {
            // デフォルトは表示
            echo json_encode(['success' => true, 'slide' => ['is_visible' => 1]]);
        }
        break;

    case 'get_latest':
        // 最新のweek_dateのスライド表示設定取得
        $latestWeekStmt = $db->query("SELECT MAX(week_date) as latest_week FROM slide_visibility");
        $latestWeekRow = $latestWeekStmt->fetch(PDO::FETCH_ASSOC);
        $latestWeek = $latestWeekRow['latest_week'];

        if ($latestWeek) {
            $stmt = $db->prepare("
                SELECT * FROM slide_visibility
                WHERE week_date = :week_date
                ORDER BY slide_number
            ");
            $stmt->bindValue(':week_date', $latestWeek, PDO::PARAM_STR);
            $stmt->execute();

            $visibility = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $visibility[] = $row;
            }

            echo json_encode(['success' => true, 'visibility' => $visibility, 'week_date' => $latestWeek]);
        } else {
            echo json_encode(['success' => true, 'visibility' => [], 'week_date' => null]);
        }
        break;

    case 'save_all':
        $visibilityArray = $postData['visibility'] ?? [];
        if (empty($visibilityArray)) { echo json_encode(['success' => false, 'error' => 'データが不足しています']); exit; }

        $db->beginTransaction();
        try {
            foreach ($visibilityArray as $item) {
                $stmt = $db->prepare("SELECT id FROM slide_visibility WHERE week_date = :week_date AND slide_number = :slide_number");
                $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
                $stmt->bindValue(':slide_number', $item['slide_number'], PDO::PARAM_INT);
                $stmt->execute();
                $existing = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    $stmt = $db->prepare("UPDATE slide_visibility SET is_visible = :is_visible, updated_at = CURRENT_TIMESTAMP WHERE week_date = :week_date AND slide_number = :slide_number");
                    $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
                    $stmt->bindValue(':slide_number', $item['slide_number'], PDO::PARAM_INT);
                    $stmt->bindValue(':is_visible', $item['is_visible'], PDO::PARAM_INT);
                } else {
                    $stmt = $db->prepare("INSERT INTO slide_visibility (week_date, slide_number, is_visible) VALUES (:week_date, :slide_number, :is_visible)");
                    $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
                    $stmt->bindValue(':slide_number', $item['slide_number'], PDO::PARAM_INT);
                    $stmt->bindValue(':is_visible', $item['is_visible'], PDO::PARAM_INT);
                }

                $stmt->execute();
            }

            $db->commit();
            echo json_encode(['success' => true, 'message' => '保存しました']);
        } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(['success' => false, 'error' => 'データベースエラー: ' . $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '無効なアクションです']);
        break;
}
