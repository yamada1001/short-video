<?php
/**
 * BNI Slide System V2 - Champions CRUD API
 * チャンピオン管理API
 */

header('Content-Type: application/json');

$dbPath = __DIR__ . '/../../database/bni_slide_v2.db';

try {
    $db = new SQLite3($dbPath);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'データベース接続エラー']);
    exit;
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;

// POSTデータをJSONから取得
$postData = json_decode(file_get_contents('php://input'), true);
if ($postData) {
    $action = $postData['action'] ?? $action;
}

switch ($action) {
    case 'get':
        // 特定週のチャンピオンデータ取得
        $weekDate = $_GET['week_date'] ?? null;

        if (!$weekDate) {
            echo json_encode(['success' => false, 'error' => '日付が必要です']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT c.*, m.name as member_name, m.photo_path
            FROM champions c
            LEFT JOIN members m ON c.member_id = m.id
            WHERE c.week_date = :week_date
            ORDER BY c.type, c.rank, c.id
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $result = $stmt->execute();

        $champions = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $champions[] = $row;
        }

        echo json_encode(['success' => true, 'champions' => $champions]);
        break;

    case 'get_by_type':
        // 特定タイプのチャンピオン取得（スライド表示用）
        $weekDate = $_GET['week_date'] ?? null;
        $type = $_GET['type'] ?? null;

        if (!$weekDate || !$type) {
            echo json_encode(['success' => false, 'error' => '日付とタイプが必要です']);
            exit;
        }

        $stmt = $db->prepare("
            SELECT c.*, m.name as member_name, m.photo_path
            FROM champions c
            LEFT JOIN members m ON c.member_id = m.id
            WHERE c.week_date = :week_date AND c.type = :type
            ORDER BY c.rank, c.count DESC, c.id
        ");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':type', $type, SQLITE3_TEXT);
        $result = $stmt->execute();

        $champions = [];
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $champions[] = $row;
        }

        echo json_encode(['success' => true, 'champions' => $champions]);
        break;

    case 'save':
        // チャンピオンデータ保存
        $weekDate = $postData['week_date'] ?? null;
        $type = $postData['type'] ?? null;
        $championsData = $postData['champions'] ?? [];

        if (!$weekDate || !$type) {
            echo json_encode(['success' => false, 'error' => '日付とタイプが必要です']);
            exit;
        }

        // トランザクション開始
        $db->exec('BEGIN');

        try {
            // 既存データを削除
            $stmt = $db->prepare("DELETE FROM champions WHERE week_date = :week_date AND type = :type");
            $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
            $stmt->bindValue(':type', $type, SQLITE3_TEXT);
            $stmt->execute();

            // 新しいデータを挿入
            foreach ($championsData as $champion) {
                $stmt = $db->prepare("
                    INSERT INTO champions (week_date, type, rank, member_id, count)
                    VALUES (:week_date, :type, :rank, :member_id, :count)
                ");

                $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
                $stmt->bindValue(':type', $type, SQLITE3_TEXT);
                $stmt->bindValue(':rank', $champion['rank'], SQLITE3_INTEGER);
                $stmt->bindValue(':member_id', $champion['member_id'], SQLITE3_INTEGER);
                $stmt->bindValue(':count', $champion['count'], SQLITE3_INTEGER);
                $stmt->execute();
            }

            $db->exec('COMMIT');
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $db->exec('ROLLBACK');
            echo json_encode(['success' => false, 'error' => 'データベースエラー: ' . $e->getMessage()]);
        }
        break;

    case 'delete':
        // チャンピオンデータ削除
        $weekDate = $postData['week_date'] ?? $_POST['week_date'] ?? null;
        $type = $postData['type'] ?? $_POST['type'] ?? null;

        if (!$weekDate || !$type) {
            echo json_encode(['success' => false, 'error' => '日付とタイプが必要です']);
            exit;
        }

        $stmt = $db->prepare("DELETE FROM champions WHERE week_date = :week_date AND type = :type");
        $stmt->bindValue(':week_date', $weekDate, SQLITE3_TEXT);
        $stmt->bindValue(':type', $type, SQLITE3_TEXT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'データベース削除エラー']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => '無効なアクションです']);
        break;
}

$db->close();
