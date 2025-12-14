<?php
/**
 * BNI Slide System V2 - Champions CRUD API
 * チャンピオン管理API
 */

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

try {
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->execute();

        $champions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->execute();

        $champions = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
            $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            $stmt->execute();

            // 新しいデータを挿入
            foreach ($championsData as $champion) {
                $stmt = $db->prepare("
                    INSERT INTO champions (week_date, type, rank, member_id, count)
                    VALUES (:week_date, :type, :rank, :member_id, :count)
                ");

                $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
                $stmt->bindValue(':type', $type, PDO::PARAM_STR);
                $stmt->bindValue(':rank', $champion['rank'], PDO::PARAM_INT);
                $stmt->bindValue(':member_id', $champion['member_id'], PDO::PARAM_INT);
                $stmt->bindValue(':count', $champion['count'], PDO::PARAM_INT);
                $stmt->execute();
            }

            $db->commit();

            // 保存成功後、スライド画像を生成
            switch ($type) {
                case 'referral':
                    generateSlideImage('referral_champion.php', 91, $weekDate);
                    break;
                case 'value':
                    generateSlideImage('value_champion.php', 92, $weekDate);
                    break;
                case 'visitor':
                    generateSlideImage('visitor_champion.php', 93, $weekDate);
                    break;
                case '1to1':
                    generateSlideImage('1to1_champion.php', 94, $weekDate);
                    break;
                case 'ceu':
                    generateSlideImage('ceu_champion.php', 95, $weekDate);
                    break;
            }
            // 全チャンピオンスライドも更新
            generateSlideImage('all_champions.php', 96, $weekDate);

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            $db->rollBack();
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
        $stmt->bindValue(':week_date', $weekDate, PDO::PARAM_STR);
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);

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
